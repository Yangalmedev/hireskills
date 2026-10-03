<?php

namespace App\Support;

/**
 * Philippine mobile numbers only: 11 digits, starting with 09 (e.g. 09171234567).
 * "+63 917 123 4567" and "639171234567" are accepted and converted to 09171234567.
 */
class PhoneNumber
{
    public const PATTERN = '/^09\d{9}$/';

    public const MESSAGE = 'Enter a valid 11-digit mobile number starting with 09 (example: 09171234567). Letters and symbols are not allowed.';

    /**
     * Tidy what the person typed. Only spaces, dashes, dots and brackets are removed;
     * letters and other characters are kept on purpose so validation can reject them.
     */
    public static function normalize(?string $value): ?string
    {
        $value = preg_replace('/[\s\-().]/', '', trim((string) $value));

        if ($value === '' || $value === null) {
            return null;
        }

        // +639171234567 or 639171234567  ->  09171234567
        if (preg_match('/^\+?63(9\d{9})$/', $value, $m)) {
            return '0'.$m[1];
        }

        return $value;
    }

    public static function isValid(?string $value): bool
    {
        return (bool) preg_match(self::PATTERN, (string) $value);
    }
}
