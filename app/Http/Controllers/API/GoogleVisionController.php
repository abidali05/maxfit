<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Mindee\ClientV2;
use Mindee\Input\PathInput;
use Mindee\Input\InferenceParameters;
use Mindee\Error\MindeeException;

class GoogleVisionController extends Controller
{
    public function scanIdCard(Request $request)
    {
        try {
            // Validate uploaded file
            $validator = Validator::make($request->all(), [
                'id_card' => 'required|image|mimes:jpeg,png,jpg|max:10240',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $validator->errors()->first(),
                ], 422);
            }

            // Store uploaded file temporarily
            $uploadPath = public_path('id_cards');
            if (!file_exists($uploadPath)) mkdir($uploadPath, 0777, true);

            $file = $request->file('id_card');
            $fileName = 'id_card_' . time() . '.' . $file->getClientOriginalExtension();
            $fullPath = $uploadPath . DIRECTORY_SEPARATOR . $fileName;
            $file->move($uploadPath, $fileName);

            // Initialize Google Vision client
            $imageAnnotator = new ImageAnnotatorClient([
                'credentials' => storage_path('app/google-service-account.json'),
            ]);

            // Create image object
            $image = new Image();
            $image->setContent(file_get_contents($fullPath));

            // Create feature for text detection
            $feature = new Feature();
            $feature->setType(Feature\Type::TEXT_DETECTION);

            // Create annotation request
            $request = new AnnotateImageRequest();
            $request->setImage($image);
            $request->setFeatures([$feature]);

            // Read text from image
            $response = $imageAnnotator->batchAnnotateImages([$request]);
            $annotations = $response->getResponses()[0]->getTextAnnotations();

            if (empty($annotations)) {
                @unlink($fullPath);
                return response()->json([
                    'status' => 'error',
                    'message' => 'No text found in the image',
                ], 400);
            }

            $rawText = $annotations[0]->getDescription();

            // Extract ID number and DOB
            $idNumber = $this->extractIdNumber($rawText);
            $dob = $this->extractDateOfBirth($rawText);

            // Cleanup
            @unlink($fullPath);
            $imageAnnotator->close();

            return response()->json([
                'status' => 'success',
                'message' => 'Text extracted successfully',
                'data' => [
                    'raw_text' => $rawText,
                    'id_number' => $idNumber,
                    'dob' => $dob,
                ],
            ], 200);
        } catch (\Exception $e) {
            if (isset($fullPath) && file_exists($fullPath)) @unlink($fullPath);
            return response()->json([
                'status' => 'error',
                'message' => 'OCR processing failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    private function extractIdNumber($text)
    {
        $patterns = [
            '/(\d{5})[-\s](\d{7})[-\s](\d{1})/',
            '/(\d{13})/',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $text, $matches)) {
                if (count($matches) === 4) {
                    return $matches[1] . '-' . $matches[2] . '-' . $matches[3];
                } elseif (strlen($matches[1]) === 13) {
                    $num = $matches[1];
                    return substr($num, 0, 5) . '-' . substr($num, 5, 7) . '-' . substr($num, 12, 1);
                }
            }
        }

        return null;
    }

    private function extractDateOfBirth($text)
    {
        $patterns = [
            '/(\d{1,2})\.(\d{1,2})\.(\d{4})/',
            '/(\d{1,2})-(\d{1,2})-(\d{4})/',
            '/(\d{1,2})\/(\d{1,2})\/(\d{4})/',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $text, $matches)) {
                $day = str_pad($matches[1], 2, '0', STR_PAD_LEFT);
                $month = str_pad($matches[2], 2, '0', STR_PAD_LEFT);
                $year = $matches[3];
                if ($year >= 1920 && $year <= 2010) return $day . '-' . $month . '-' . $year;
            }
        }

        return null;
    }

    public function idScanner(Request $request)
    {
        // 1️⃣ Validate input
        $validator = Validator::make($request->all(), [
            'id_card' => 'required|image|mimes:jpeg,png,jpg|max:10240',
        ]);

        // dd($request->all());

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $file = $request->file('id_card');

        // 2️⃣ Direct API call to avoid package bug
        try {
            $client = new \GuzzleHttp\Client();
            $json = [
                'processParam' => [
                    "scenario" => "FullProcess",
                    "resultTypeOutput" => [],
                    "doublePageSpread" => true,
                    "log" => true,
                    "alreadyCropped" => true
                ],
                'List' => [[
                    'ImageData' => ['image' => 'data:image/' . $file->getClientOriginalExtension() . ';base64,' . base64_encode(file_get_contents($file))],
                    'light' => 6,
                    'page_idx' => 0
                ]],
                'systemInfo' => new \ArrayObject()
            ];

            $apiResponse = $client->post('https://api.regulaforensics.com/api/process?logRequest=false', [
                'json' => $json
            ]);
            $rawData = json_decode($apiResponse->getBody(), true);

            // 3️⃣ Upload image to public/id_cards (optional, for storage)
            $uploadPath = public_path('id_cards');
            if (!file_exists($uploadPath)) mkdir($uploadPath, 0777, true);
            $fileName = 'id_card_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move($uploadPath, $fileName);
            return response()->json([
                'status' => 'success',
                'message' => 'ID scanned successfully',
                'data' => $rawData,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'ID scanning failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function upload(Request $request)
    {
        $request->validate([
            'id_card' => 'required|file|mimes:jpg,jpeg,png,pdf|max:10240',
        ]);

        $file = $request->file('id_card');
        $filePath = $file->getRealPath();

        $apiKey  = config('mindee.api_key');
        $modelId = config('mindee.model_id');

        try {
            $client = new ClientV2($apiKey);

            $params = new InferenceParameters(
                $modelId,
                rag: false,
                rawText: true,
                polygon: false,
                confidence: true
            );

            $input = new PathInput($filePath);
            $response = $client->enqueueAndGetInference($input, $params);

            $fields = $response->inference->result->fields ?? [];

            $firstName = $this->cleanText($fields['given_names']->value ?? null);
            $lastName  = $this->cleanText($fields['surnames']->value ?? null);

            $extracted = [
                'id_number' => $this->cleanText($fields['document_number']->value ?? null),

                'full_name' => trim("$firstName $lastName") ?: null,

                'father_name' => $this->cleanText(
                    $response->inference->result->rawText->pages[0]->content
                        ? (preg_match('/Father Name\s*(.*)/', $response->inference->result->rawText->pages[0]->content, $m) ? $m[1] : null)
                        : null
                ),

                'date_of_birth' => $fields['date_of_birth']->value ?? null,
                'issue_date'    => $fields['date_of_issue']->value ?? null,
                'expiry_date'   => $fields['date_of_expiry']->value ?? null,

                'gender'        => $this->cleanText($fields['sex']->value ?? null),
                'address'       => $this->cleanText($fields['address']->value ?? null),
            ];

            return response()->json([
                'success' => true,
                'data'    => $extracted,
                'raw'     => $response->inference,
            ]);
        } catch (MindeeException $e) {
            return response()->json([
                'success' => false,
                'message' => 'OCR processing failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    private function cleanText(?string $text): ?string
    {
        if (!$text) {
            return null;
        }

        // Remove special characters like / \ | etc & non-ASCII
        $text = preg_replace('/[^A-Za-z0-9\s]/', '', $text);

        // Remove multiple spaces
        $text = preg_replace('/\s+/', ' ', $text);

        return trim($text);
    }
}
