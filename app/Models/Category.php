<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'description', 'image', 'status'];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function getNameAttribute($value): string
    {
        return \App\Models\Product::fixUtf8((string) $value);
    }

    public function setNameAttribute($value): void
    {
        $this->attributes['name'] = \App\Models\Product::fixUtf8((string) $value);
    }

    public function subcategories(): HasMany
    {
        return $this->hasMany(Subcategory::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
