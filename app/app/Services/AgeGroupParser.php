<?php

namespace App\Services;

use InvalidArgumentException;

class AgeGroupParser
{
    public static function parse(?string $value): array
    {
        $value = trim((string) $value);

        if ($value === '') {
            throw new InvalidArgumentException('Age group is required.');
        }

        if (!preg_match('/^\d+\s*(?:-\s*\d+)?$/', $value)) {
            throw new InvalidArgumentException('Age group must be a number like 14 or a range like 14-30.');
        }

        if (str_contains($value, '-')) {
            [$min, $max] = array_map('trim', explode('-', $value, 2));
            $min = (int) $min;
            $max = (int) $max;

            if ($min <= 0 || $max <= 0) {
                throw new InvalidArgumentException('Age group values must be greater than zero.');
            }

            if ($min > $max) {
                throw new InvalidArgumentException('Age group range must be in ascending order.');
            }

            return [
                'min' => $min,
                'max' => $max,
                'single' => false,
            ];
        }

        $age = (int) $value;
        if ($age <= 0) {
            throw new InvalidArgumentException('Age group must be greater than zero.');
        }

        return [
            'min' => $age,
            'max' => $age,
            'single' => true,
        ];
    }

    public static function inferGenz(?string $value): ?string
    {
        try {
            $parsed = self::parse($value);
        } catch (InvalidArgumentException) {
            return null;
        }

        if ($parsed['max'] < 14) {
            return 'motherfits';
        }

        if ($parsed['min'] >= 14) {
            return 'fatherfits';
        }

        return null;
    }
}
