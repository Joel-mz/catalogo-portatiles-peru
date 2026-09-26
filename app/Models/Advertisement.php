<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Advertisement extends Model
{
    protected $fillable = ['title', 'image', 'link', 'location', 'status'];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function isVideo(): bool
    {
        if (!$this->image) {
            return false;
        }
        $ext = strtolower(pathinfo($this->image, PATHINFO_EXTENSION));
        return in_array($ext, ['mp4', 'webm', 'ogg', 'mov', 'm4v']);
    }

    public function getMediaUrlAttribute(): string
    {
        if (!$this->image) {
            return '';
        }
        if (filter_var($this->image, FILTER_VALIDATE_URL)) {
            return $this->image;
        }
        $version = $this->updated_at ? $this->updated_at->timestamp : time();
        return '/storage/' . ltrim($this->image, '/') . '?v=' . $version;
    }
}
