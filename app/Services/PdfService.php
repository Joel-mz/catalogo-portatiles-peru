<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PdfService
{
    /**
     * Convert any image path or URL into a base64 data URI string suitable for DomPDF.
     */
    public static function imageToBase64(?string $path): ?string
    {
        if (empty($path)) {
            return null;
        }

        $path = trim($path);

        // Already a base64 data URI
        if (str_starts_with($path, 'data:image/')) {
            return $path;
        }

        // Remote URL (http/https)
        if (filter_var($path, FILTER_VALIDATE_URL) || str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return self::fetchRemoteImageAsBase64($path);
        }

        // Local file system
        return self::getLocalImageAsBase64($path);
    }

    /**
     * Get the main image of a product converted to a base64 data URI string.
     */
    public static function getProductMainImageBase64(Product $product): ?string
    {
        $mainImg = $product->images->firstWhere('is_main', true) ?? $product->images->first();
        if (!$mainImg || empty($mainImg->image_path)) {
            return null;
        }

        return self::imageToBase64($mainImg->image_path);
    }

    /**
     * Fetch a remote image and return as a base64 data URI, caching the result to optimize PDF generation.
     */
    protected static function fetchRemoteImageAsBase64(string $url): ?string
    {
        $cacheKey = 'pdf_img_b64_' . md5($url);

        return Cache::remember($cacheKey, 86400, function () use ($url): ?string {
            try {
                $response = Http::withoutVerifying()
                    ->timeout(8)
                    ->withHeaders([
                        'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                        'Accept' => 'image/jpeg,image/png,image/gif,image/webp,image/*;q=0.8',
                    ])
                    ->get($url);

                if ($response->successful() && !empty($response->body())) {
                    $rawMime = $response->header('Content-Type') ?: 'image/jpeg';
                    $mime = trim(explode(';', $rawMime)[0]);
                    $body = $response->body();

                    // Convert WebP to JPEG if needed and GD is available
                    if (str_contains($mime, 'webp') || str_ends_with(strtolower($url), '.webp')) {
                        $converted = self::convertWebpToJpeg($body);
                        if ($converted) {
                            return 'data:image/jpeg;base64,' . base64_encode($converted);
                        }
                    }

                    if (!in_array($mime, ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/svg+xml'])) {
                        $mime = 'image/jpeg';
                    }

                    return 'data:' . $mime . ';base64,' . base64_encode($body);
                }
            } catch (\Throwable $e) {
                Log::warning('Error fetching remote PDF image [' . $url . ']: ' . $e->getMessage());
            }

            return null;
        });
    }

    /**
     * Locate and read a local file, returning a base64 data URI string.
     */
    protected static function getLocalImageAsBase64(string $path): ?string
    {
        $cleaned = ltrim($path, '/\\');
        $withoutStorage = str_starts_with($cleaned, 'storage/') ? substr($cleaned, 8) : $cleaned;

        $candidatePaths = [
            storage_path('app/public/' . $withoutStorage),
            storage_path('app/public/' . $cleaned),
            public_path('storage/' . $withoutStorage),
            public_path($cleaned),
            storage_path('app/' . $cleaned),
            base_path($cleaned),
        ];

        foreach ($candidatePaths as $fullPath) {
            if (file_exists($fullPath) && is_file($fullPath)) {
                $mime = @mime_content_type($fullPath) ?: 'image/jpeg';
                $content = @file_get_contents($fullPath);
                if ($content === false || empty($content)) {
                    continue;
                }

                if (str_contains($mime, 'webp') || str_ends_with(strtolower($fullPath), '.webp')) {
                    $converted = self::convertWebpToJpeg($content);
                    if ($converted) {
                        return 'data:image/jpeg;base64,' . base64_encode($converted);
                    }
                }

                return 'data:' . $mime . ';base64,' . base64_encode($content);
            }
        }

        return null;
    }

    /**
     * Convert WebP binary data to JPEG binary data if GD supports it.
     */
    protected static function convertWebpToJpeg(string $binaryData): ?string
    {
        if (function_exists('imagecreatefromstring')) {
            $im = @imagecreatefromstring($binaryData);
            if ($im !== false) {
                ob_start();
                imagejpeg($im, null, 90);
                imagedestroy($im);
                return ob_get_clean() ?: null;
            }
        }

        return null;
    }
}
