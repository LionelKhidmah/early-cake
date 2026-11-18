<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomCakePushSubscription extends Model
{
    protected $fillable = [
        'custom_cake_id',
        'endpoint',
        'endpoint_hash',
        'public_key',
        'auth_token',
        'content_encoding',
    ];

    public function customCake(): BelongsTo
    {
        return $this->belongsTo(CustomCake::class);
    }
}
