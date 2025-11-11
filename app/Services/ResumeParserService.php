<?php

namespace App\Services;

class ResumeParserService
{
    /**
     * Extract email address from resume text
     *
     * @param string $text
     * @return string|null
     */
    public function extractEmail(string $text): ?string
    {
        // Email regex pattern
        $pattern = '/\b[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Z|a-z]{2,}\b/';

        if (preg_match($pattern, $text, $matches)) {
            return strtolower(trim($matches[0]));
        }

        return null;
    }

    /**
     * Extract phone number from resume text
     * Supports multiple formats:
     * - US: (123) 456-7890, 123-456-7890, 1234567890
     * - International: +1 123 456 7890, +880-1234-567890
     * - With extensions: 123-456-7890 ext 123
     *
     * @param string $text
     * @return string|null
     */
    public function extractPhone(string $text): ?string
    {
        // Array of phone regex patterns (from most specific to general)
        $patterns = [
            // International format with country code: +1 234 567 8900, +880-1234-567890
            '/\+\d{1,3}[\s.-]?\(?\d{1,4}\)?[\s.-]?\d{1,4}[\s.-]?\d{1,9}/',

            // US format with parentheses: (123) 456-7890
            '/\(\d{3}\)[\s.-]?\d{3}[\s.-]?\d{4}/',

            // Standard format with dashes or spaces: 123-456-7890, 123 456 7890
            '/\d{3}[\s.-]\d{3}[\s.-]\d{4}/',

            // 10+ digit number (without separators): 1234567890
            '/\b\d{10,15}\b/',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $text, $matches)) {
                // Clean up the match
                $phone = trim($matches[0]);

                // Skip if it's just a date or other number pattern
                if ($this->isLikelyPhoneNumber($phone)) {
                    return $this->formatPhone($phone);
                }
            }
        }

        return null;
    }

    /**
     * Check if extracted number is likely a phone number
     * Filters out dates, years, IDs, etc.
     *
     * @param string $number
     * @return bool
     */
    private function isLikelyPhoneNumber(string $number): bool
    {
        // Remove all non-digit characters for validation
        $digits = preg_replace('/\D/', '', $number);

        // Phone numbers should have 10-15 digits
        if (strlen($digits) < 10 || strlen($digits) > 15) {
            return false;
        }

        // Filter out years (1900-2099)
        if (preg_match('/^(19|20)\d{2}$/', $digits)) {
            return false;
        }

        // Filter out sequential numbers like 1234567890
        if ($this->isSequential($digits)) {
            return false;
        }

        return true;
    }

    /**
     * Check if number is sequential (likely not a real phone)
     *
     * @param string $digits
     * @return bool
     */
    private function isSequential(string $digits): bool
    {
        $length = strlen($digits);
        $sequential = 0;

        for ($i = 1; $i < $length; $i++) {
            if (abs($digits[$i] - $digits[$i - 1]) <= 1) {
                $sequential++;
            }
        }

        // If more than 80% of digits are sequential, it's likely fake
        return ($sequential / $length) > 0.8;
    }

    /**
     * Format phone number to a consistent format
     *
     * @param string $phone
     * @return string
     */
    private function formatPhone(string $phone): string
    {
        // If it starts with +, keep international format
        if (str_starts_with($phone, '+')) {
            return preg_replace('/[^\d+]/', '', $phone); // Keep + and digits only
        }

        // Otherwise, return cleaned format with dashes
        $digits = preg_replace('/\D/', '', $phone);

        // Format based on length
        if (strlen($digits) == 10) {
            // US format: 123-456-7890
            return substr($digits, 0, 3) . '-' . substr($digits, 3, 3) . '-' . substr($digits, 6);
        } elseif (strlen($digits) == 11 && str_starts_with($digits, '1')) {
            // US with country code: 1-123-456-7890
            return '1-' . substr($digits, 1, 3) . '-' . substr($digits, 4, 3) . '-' . substr($digits, 7);
        }

        // For other lengths, just add dashes every 3-4 digits
        return $phone;
    }

    /**
     * Extract both email and phone from resume text
     *
     * @param string $text
     * @return array{email: string|null, phone: string|null}
     */
    public function extractContactInfo(string $text): array
    {
        return [
            'email' => $this->extractEmail($text),
            'phone' => $this->extractPhone($text),
        ];
    }

    /**
     * Extract full name from resume text
     * Looks for name in first few lines (common pattern in resumes)
     *
     * @param string $text
     * @return array{first_name: string|null, last_name: string|null}
     */
    public function extractName(string $text): array
    {
        // Get first 5 lines of resume
        $lines = explode("\n", $text);
        $topLines = array_slice($lines, 0, 5);

        foreach ($topLines as $line) {
            $line = trim($line);

            // Skip lines that are too short or too long
            if (strlen($line) < 5 || strlen($line) > 50) {
                continue;
            }

            // Skip lines with emails or phones
            if (preg_match('/@|http|\.com|\d{3}[-\s]?\d{3}/', $line)) {
                continue;
            }

            // Look for 2-3 word names
            if (preg_match('/^([A-Z][a-z]+)\s+([A-Z][a-z]+(?:\s+[A-Z][a-z]+)?)$/', $line, $matches)) {
                $nameParts = explode(' ', $matches[0]);
                return [
                    'first_name' => $nameParts[0],
                    'last_name' => implode(' ', array_slice($nameParts, 1)),
                ];
            }
        }

        return [
            'first_name' => null,
            'last_name' => null,
        ];
    }
}
