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
        'offer_price' => 'decimal:2',
        'status' => 'boolean',
        'is_featured' => 'boolean',
        'is_new' => 'boolean',
        'is_offer' => 'boolean',
    ];

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
