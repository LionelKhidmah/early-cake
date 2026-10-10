<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomCake extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'cake_type',
        'size',
        'flavor',
        'theme',
        'message',
        'reference_image',
        'description',
        'estimated_price',
        'final_price',
        'status',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}