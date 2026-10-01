<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'code', 'sku', 'control_type', 'serial_number', 'name', 'slug', 'category_id', 'subcategory_id', 'brand_id', 'device_model_id',
        'description', 'technical_specs', 'price', 'min_price', 'offer_price', 'warranty', 'stock',
        'status', 'state', 'is_featured', 'is_new', 'is_offer',
    ];

    protected $casts = [
        'technical_specs' => 'array',
        'price' => 'decimal:2',
        'min_price' => 'decimal:2',
        'offer_price' => 'decimal:2',
        'status' => 'boolean',
        'is_featured' => 'boolean',
        'is_new' => 'boolean',
        'is_offer' => 'boolean',
    ];

    /**
     * Determine if this product is a laptop / notebook.
     */
    public function isLaptop(): bool
    {
        $categoryName = mb_strtolower($this->category?->name ?? '');
        $categorySlug = mb_strtolower($this->category?->slug ?? '');
        $subCategoryName = mb_strtolower($this->subcategory?->name ?? '');
        $name = mb_strtolower($this->name ?? '');

        $laptopKeywords = ['laptop', 'portatil', 'portátil', 'portatiles', 'portátiles', 'notebook', 'macbook', 'ultrabook'];

        foreach ($laptopKeywords as $kw) {
            if (str_contains($categoryName, $kw) || str_contains($categorySlug, $kw) || str_contains($subCategoryName, $kw) || str_contains($name, $kw)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Detect laptop generation (e.g. 12ª Generación, 13ª Generación).
     * Returns null if not a laptop or generation is undetermined.
     */
    public function getLaptopGeneration(): ?string
    {
        if (!$this->isLaptop()) {
            return null;
        }

        // 1. Inspect technical specs
        $specs = is_array($this->technical_specs) ? $this->technical_specs : [];
        $textToSearch = ($this->name ?? '') . ' ' . ($this->description ?? '');

        foreach ($specs as $key => $val) {
            $keyLower = mb_strtolower(is_string($key) ? $key : ($val['name'] ?? ''));
            $valText = is_string($val) ? $val : ($val['value'] ?? '');

            if (str_contains($keyLower, 'generaci') || str_contains($keyLower, 'generation') || $keyLower === 'gen') {
                return trim($valText);
            }

            $textToSearch .= ' ' . $keyLower . ' ' . $valText;
        }

        // 2. 13ª Generación (13va, treceava, 13th, etc.)
        if (
            preg_match('/\b(13\s*(va|ava|ra|th|°|ª|\.a)?\s*(gen|generaci[oó]n)|trece\s*ava\s*generaci[oó]n|treceava)\b/iu', $textToSearch)
            || preg_match('/\bi[3579][-\s]?13\d{2,3}[a-z]*\b/i', $textToSearch)
        ) {
            return '13ª Generación';
        }

        // 3. 12ª Generación (12va, doceava, 12th, etc.)
        if (
            preg_match('/\b(12\s*(va|ava|da|th|°|ª|\.a)?\s*(gen|generaci[oó]n)|doce\s*ava\s*generaci[oó]n|doceava)\b/iu', $textToSearch)
            || preg_match('/\bi[3579][-\s]?12\d{2,3}[a-z]*\b/i', $textToSearch)
        ) {
            return '12ª Generación';
        }

        // 4. 14ª Generación (14va, catorceava, etc.)
        if (
            preg_match('/\b(14\s*(va|ava|ta|th|°|ª|\.a)?\s*(gen|generaci[oó]n)|catorce\s*ava\s*generaci[oó]n|catorceava)\b/iu', $textToSearch)
            || preg_match('/\bi[3579][-\s]?14\d{2,3}[a-z]*\b/i', $textToSearch)
        ) {
            return '14ª Generación';
        }

        // 5. 11ª Generación
        if (
            preg_match('/\b(11\s*(va|ava|th|°|ª|\.a)?\s*(gen|generaci[oó]n)|once\s*ava\s*generaci[oó]n|onceava)\b/iu', $textToSearch)
            || preg_match('/\bi[3579][-\s]?11\d{2,3}[a-z]*\b/i', $textToSearch)
        ) {
            return '11ª Generación';
        }

        // 6. 10ª Generación
        if (
            preg_match('/\b(10\s*(ma|va|th|°|ª|\.a)?\s*(gen|generaci[oó]n)|d[eé]cima\s*generaci[oó]n)\b/iu', $textToSearch)
            || preg_match('/\bi[3579][-\s]?10\d{2,3}[a-z]*\b/i', $textToSearch)
        ) {
            return '10ª Generación';
        }

        // 7. Intel Core Ultra
        if (preg_match('/core\s*ultra\s*[579]/i', $textToSearch)) {
            return 'Intel Core Ultra (Serie 1 IA)';
        }

        // 8. AMD Ryzen Series
        if (preg_match('/ryzen\s*[3579][-\s]?([789543])\d{3}[a-z]*/i', $textToSearch, $m)) {
            return 'Serie AMD Ryzen ' . $m[1] . '000';
        }

        // 9. Apple Silicon
        if (preg_match('/\b(m[1234](?:\s*(?:pro|max|ultra))?)\b/i', $textToSearch, $m)) {
            return 'Chip Apple ' . strtoupper($m[1]);
        }

        return null;
    }

    public function getNameAttribute($value): string
    {
        return self::fixUtf8((string) $value);
    }

    public function setNameAttribute($value): void
    {
        $this->attributes['name'] = self::fixUtf8((string) $value);
    }

    public function getDescriptionAttribute($value): ?string
    {
        return $value ? self::fixUtf8((string) $value) : null;
    }

    public static function fixUtf8(?string $str): string
    {
        if (empty($str)) {
            return '';
        }

        $replacements = [
            'Ã¡' => 'á', 'Ã©' => 'é', 'Ã­' => 'í', 'Ã³' => 'ó', 'Ãº' => 'ú', 'Ã±' => 'ñ',
            'Ã ' => 'Á', 'Ã‰' => 'É', 'Ã ' => 'Í', 'Ã“' => 'Ó', 'Ãš' => 'Ú', 'Ã‘' => 'Ñ',
            'Ã¼' => 'ü', 'Ãœ' => 'Ü',
            'Â¿' => '¿', 'Â¡' => '¡', 'Â°' => '°', 'Âº' => 'º', 'Âª' => 'ª',
            'â€œ' => '“', 'â€ ' => '”', 'â€˜' => '‘', 'â€™' => '’', 'â€“' => '–', 'â€”' => '—',
            'Â ' => ' ',
        ];

        $cleaned = strtr($str, $replacements);

        if (preg_match('/[\xC2-\xDF][\x80-\xBF]/', $cleaned) && function_exists('mb_convert_encoding')) {
            $converted = @mb_convert_encoding($cleaned, 'ISO-8859-1', 'UTF-8');
            if ($converted !== false && mb_check_encoding($converted, 'UTF-8') && !empty($converted)) {
                $cleaned = $converted;
            }
        }

        return $cleaned;
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function subcategory(): BelongsTo
    {
        return $this->belongsTo(Subcategory::class);
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function deviceModel(): BelongsTo
    {
        return $this->belongsTo(DeviceModel::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(ProductReview::class);
    }
}
