<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['client_id', 'total_amount', 'status', 'details'];

    protected $casts = [
        'details' => 'array',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
