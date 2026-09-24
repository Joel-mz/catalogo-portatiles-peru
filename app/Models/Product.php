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
        'code', 'sku', 'serial_number', 'name', 'slug', 'category_id', 'subcategory_id', 'brand_id', 'device_model_id',
        'description', 'technical_specs', 'price', 'offer_price', 'warranty', 'stock',
        'status', 'is_featured', 'is_new', 'is_offer',
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
