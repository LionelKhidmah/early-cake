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
        'shape',
        'color',
        'decorations',
        'design_config',
        'theme',
        'message',
        'reference_image',
        'description',
        'estimated_price',
        'final_price',
        'status',
        'notification_token_hash',
        'rejection_reason',
    ];

    protected function casts(): array
    {
        return [
            'decorations' => 'array',
            'design_config' => 'array',
            'estimated_price' => 'decimal:2',
            'final_price' => 'decimal:2',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function notifications()
    {
    return $this->hasMany(CustomCakeNotification::class);
    }

    public function pushSubscriptions()
    {
    return $this->hasMany(CustomCakePushSubscription::class);
    }

}
