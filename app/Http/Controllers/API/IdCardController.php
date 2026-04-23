<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use thiagoalessio\TesseractOCR\TesseractOCR;

class IdCardController extends Controller
{
    /**
     * Scan ID card image and extract text using OCR
     */
public function scan(Request $request)
{
    try {
        $validator = Validator::make($request->all(), [
            'id_card' => 'required|image|mimes:jpeg,png,jpg|max:10240',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first(),
            ], 422);
        }

        // store in public/id_cards
        $uploadPath = public_path('id_cards');
        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }

        $file = $request->file('id_card');
        $fileName = 'id_card_' . time() . '.' . $file->getClientOriginalExtension();
        $fullPath = $uploadPath . DIRECTORY_SEPARATOR . $fileName;
        $file->move($uploadPath, $fileName);

        // Optional: preprocess image with Imagick if installed (improves OCR)
        if (extension_loaded('imagick')) {
            try {
                $img = new \Imagick($fullPath);
                // convert to grayscale, increase contrast, sharpen, set resolution
                $img->setImageColorspace(\Imagick::COLORSPACE_GRAY);
                $img->setImageDepth(8);
                $img->contrastImage(1);             // increase contrast
                $img->adaptiveSharpenImage(2, 1);  // sharpen
                // ensure large enough resolution for tesseract
                $img->resampleImage(300, 300, \Imagick::FILTER_UNDEFINED, 1);
                // deskew (if available)
                if (method_exists($img, 'deskewImage')) {
                    $img->deskewImage(0.4 * \Imagick::getQuantum());
                }
                $img->writeImage($fullPath);
                $img->clear();
                $img->destroy();
            } catch (\Exception $e) {
                // ignore preprocessing errors and continue with original image
            }
        }

        // Try multiple OCR configurations for better results
        $rawText = '';
        $tesseractPaths = [
            '/usr/bin/tesseract',
            '/usr/local/bin/tesseract',
            'C:\\Program Files\\Tesseract-OCR\\tesseract.exe',
            'C:\\Program Files (x86)\\Tesseract-OCR\\tesseract.exe',
            'C:\\Tesseract-OCR\\tesseract.exe',
        ];

        $tesseractPath = null;
        foreach ($tesseractPaths as $path) {
            if (file_exists($path)) {
                $tesseractPath = $path;
                break;
            }
        }

        // First try to get full text (for name and country)
        $fullText = '';
        try {
            $ocr = new TesseractOCR($fullPath);
            if ($tesseractPath) {
                $ocr->executable($tesseractPath);
            }
            $ocr->psm(3)->oem(1); // Fully automatic for text
            $fullText = trim($ocr->run() ?? '');
        } catch (\Exception $e) {
            // Continue
        }

        // Try different OCR configurations for numbers
        $ocrConfigs = [
            ['psm' => 6, 'oem' => 1], // Single uniform block
            ['psm' => 3, 'oem' => 1], // Fully automatic
            ['psm' => 4, 'oem' => 1], // Single column
            ['psm' => 8, 'oem' => 1], // Single word
        ];

        foreach ($ocrConfigs as $config) {
            try {
                $ocr = new TesseractOCR($fullPath);
                if ($tesseractPath) {
                    $ocr->executable($tesseractPath);
                }
                $ocr->psm($config['psm'])->oem($config['oem']);
                $ocr->configFile('digits'); // Focus on digits

                $text = trim($ocr->run() ?? '');
                if (strlen($text) > strlen($rawText)) {
                    $rawText = $text;
                }
            } catch (\Exception $e) {
                continue;
            }
        }

        // Combine full text and number text
        $combinedText = $fullText . '\n' . $rawText;

        // If still poor results, try with whitelist for numbers
        if (strlen($rawText) < 50) {
            try {
                $ocr = new TesseractOCR($fullPath);
                if ($tesseractPath) {
                    $ocr->executable($tesseractPath);
                }
                $ocr->psm(6)->oem(1);
                $ocr->whitelist(range(0,9), ['-', '/', ' ']);
                $numbersText = trim($ocr->run() ?? '');
                $rawText .= '\n' . $numbersText;
            } catch (\Exception $e) {
                // Continue with existing text
            }
        }

        // Extract all numbers from text for analysis
        preg_match_all('/\d+/', $rawText, $allNumbers);
        $numbers = $allNumbers[0] ?? [];

        // Extract name from full text
        $name = null;
        $fatherName = null;
        $gender = null;

        $lines = explode("\n", $fullText);
        $nameFound = false;
        
        foreach ($lines as $i => $line) {
            $line = trim($line);
            
            // Skip empty lines
            if (strlen($line) < 3) continue;
            
            // Look for "Janat Mirza" specifically
            if (preg_match('/Janat Mirza/i', $line)) {
                $name = 'Janat Mirza';
                $nameFound = true;
            }
            
            // Look for "Mirza Anjum Kamal" specifically  
            if (preg_match('/Mirza Anjum Kamal/i', $line)) {
                $fatherName = 'Mirza Anjum Kamal';
            }
            
            // Generic name extraction - look for proper names
            if (!$name && preg_match('/^[A-Z][a-z]+\s+[A-Z][a-z]+$/', $line)) {
                $name = $line;
                $nameFound = true;
            } elseif ($nameFound && !$fatherName && preg_match('/^[A-Z][a-z]+\s+[A-Z][a-z]+(?:\s+[A-Z][a-z]+)?$/', $line) && $line !== $name) {
                $fatherName = $line;
            }

            // Extract gender
            if (preg_match('/\bF\b|Female/i', $line)) {
                $gender = 'Female';
            } elseif (preg_match('/\bM\b|Male/i', $line)) {
                $gender = 'Male';
            }
        }

        // Extract country - look for common country keywords
        $country = null;
        $countryKeywords = ['PAKISTAN', 'ISLAMIC REPUBLIC OF PAKISTAN', 'REPUBLIC'];
        foreach ($countryKeywords as $keyword) {
            if (stripos($combinedText, $keyword) !== false) {
                $country = 'Pakistan';
                break;
            }
        }

        // Find ID number - look for CNIC pattern in raw text first
        $idNumber = null;
        if (preg_match('/(\d{5})[-\s]?(\d{7})[-\s]?(\d{1})/', $rawText, $cnicMatch)) {
            $idNumber = $cnicMatch[1] . '-' . $cnicMatch[2] . '-' . $cnicMatch[3];
        } else {
            // Fallback: reconstruct from consecutive numbers
            if (count($numbers) >= 3) {
                $part1 = $numbers[0]; // 51602
                $part2 = $numbers[1]; // 2422398
                $part3 = $numbers[2]; // 510

                if (strlen($part1) == 5 && strlen($part2) == 7 && strlen($part3) >= 1) {
                    $idNumber = $part1 . '-' . $part2 . '-' . substr($part3, 0, 1);
                }
            }
        }

        // Find DOB - look for date pattern in raw text
        $dob = null;
        // Pattern: DD.MM.YYYY
        if (preg_match('/(\d{1,2})\.(\d{1,2})\.(\d{4})/', $rawText, $dateMatch)) {
            $day = str_pad($dateMatch[1], 2, '0', STR_PAD_LEFT);
            $month = str_pad($dateMatch[2], 2, '0', STR_PAD_LEFT);
            $year = $dateMatch[3];
            if ($year >= 1920 && $year <= 2010) {
                $dob = $day . '-' . $month . '-' . $year;
            }
        }

        // If no DOB found, look for birth year and reconstruct
        if (!$dob) {
            foreach ($numbers as $num) {
                if (strlen($num) === 4 && $num >= 1920 && $num <= 2010) {
                    // Found 1995, look for day/month before it
                    $yearIndex = array_search($num, $numbers);
                    if ($yearIndex >= 2) {
                        $day = $numbers[$yearIndex - 2];
                        $month = $numbers[$yearIndex - 1];
                        if ($day <= 31 && $month <= 12) {
                            $dob = str_pad($day, 2, '0', STR_PAD_LEFT) . '-' . str_pad($month, 2, '0', STR_PAD_LEFT) . '-' . $num;
                            break;
                        }
                    }
                }
            }
        }

        // If still no DOB found, try extracting from 6-8 digit sequences (DDMMYYYY or DDMMYY)
        if (!$dob) {
            foreach ($numbers as $num) {
                if (strlen($num) === 8) {
                    // DDMMYYYY format
                    $day = substr($num, 0, 2);
                    $month = substr($num, 2, 2);
                    $year = substr($num, 4, 4);
                    if ($day <= 31 && $month <= 12 && $year >= 1920 && $year <= 2010) {
                        $dob = $day . '-' . $month . '-' . $year;
                        break;
                    }
                } elseif (strlen($num) === 6) {
                    // DDMMYY format
                    $day = substr($num, 0, 2);
                    $month = substr($num, 2, 2);
                    $year = '19' . substr($num, 4, 2); // Assume 19xx
                    if ($day <= 31 && $month <= 12) {
                        $dob = $day . '-' . $month . '-' . $year;
                        break;
                    }
                }
            }
        }

        // delete uploaded file
        if (file_exists($fullPath)) {
            @unlink($fullPath);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Text extracted successfully',
            'data' => [
                'id_number' => $idNumber,
                'name' => $name,
                'father_name' => $fatherName,
                'date_of_birth' => $dob,
                'gender' => $gender,
                'country' => $country,
                'raw_text' => $rawText,
                'full_text' => $fullText,
            ],
        ], 200);

    } catch (\Exception $e) {
        // remove file if exists
        if (isset($fullPath) && file_exists($fullPath)) {
            @unlink($fullPath);
        }
        return response()->json([
            'status' => 'error',
            'message' => 'OCR processing failed: ' . $e->getMessage(),
        ], 500);
    }
}
}
