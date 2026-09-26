<?php

namespace App\Services;

use Illuminate\Support\Str;

class TwoFactorService
{
    protected const BASE32_CHARS = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';

    /**
     * Generate a random 16 or 32 character Base32 secret key.
     */
    public function generateSecretKey(int $length = 16): string
    {
        $secret = '';
        for ($i = 0; $i < $length; $i++) {
            $secret .= self::BASE32_CHARS[random_int(0, 31)];
        }
        return $secret;
    }

    /**
     * Verify a 6-digit TOTP code against a Base32 secret with drift window tolerance.
     */
    public function verifyKey(string $secret, string $code, int $window = 1): bool
    {
        $code = trim($code);
        if (strlen($code) !== 6 || !ctype_digit($code)) {
            return false;
        }

        $currentTimeSlice = (int) floor(time() / 30);

        for ($i = -$window; $i <= $window; $i++) {
            $calculatedCode = $this->calculateCode($secret, $currentTimeSlice + $i);
            if (hash_equals($calculatedCode, $code)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Calculate 6-digit TOTP code for a specific time slice.
     */
    public function calculateCode(string $secret, int $timeSlice): string
    {
        $secretKey = $this->base32Decode($secret);
        $time = chr(0).chr(0).chr(0).chr(0).pack('N*', $timeSlice);
        $hmac = hash_hmac('sha1', $time, $secretKey, true);
        
        $offset = ord(substr($hmac, -1)) & 0x0F;
        $hashPart = substr($hmac, $offset, 4);
        
        $value = unpack('N', $hashPart);
        $value = $value[1] & 0x7FFFFFFF;
        
        $modulo = $value % 1000000;
        return str_pad((string) $modulo, 6, '0', STR_PAD_LEFT);
    }

    /**
     * Generate an otpauth:// URL for authenticator apps.
     */
    public function getOtpAuthUrl(string $issuer, string $accountName, string $secret): string
    {
        $encodedIssuer = rawurlencode($issuer);
        $encodedAccount = rawurlencode($accountName);
        
        return "otpauth://totp/{$encodedIssuer}:{$encodedAccount}?secret={$secret}&issuer={$encodedIssuer}&algorithm=SHA1&digits=6&period=30";
    }

    /**
     * Generate a set of 8 recovery codes.
     */
    public function generateRecoveryCodes(int $count = 8): array
    {
        $codes = [];
        for ($i = 0; $i < $count; $i++) {
            $codes[] = Str::random(10);
        }
        return $codes;
    }

    /**
     * Decode a Base32 string into raw binary.
     */
    protected function base32Decode(string $b32): string
    {
        $b32 = strtoupper(trim($b32));
        $b32 = preg_replace('/[^A-Z2-7]/', '', $b32);
        
        $buffer = 0;
        $bufferSize = 0;
        $binary = '';

        for ($i = 0; $i < strlen($b32); $i++) {
            $position = strpos(self::BASE32_CHARS, $b32[$i]);
            if ($position === false) {
                continue;
            }

            $buffer = ($buffer << 5) | $position;
            $bufferSize += 5;

            if ($bufferSize >= 8) {
                $bufferSize -= 8;
                $binary .= chr(($buffer >> $bufferSize) & 0xFF);
            }
        }

        return $binary;
    }
}
