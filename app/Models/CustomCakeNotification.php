<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Services\CustomCakePushService;

class CustomCakeNotification extends Model
{
    protected $fillable = [
        'custom_cake_id',
        'title',
        'message',
        'type',
        'is_read',
        'read_at',
    ];

    protected function casts(): array
    {
        return [
            'is_read' => 'boolean',
            'read_at' => 'datetime',
        ];
    }

    public function customCake(): BelongsTo
    {
        return $this->belongsTo(CustomCake::class);
    }

    protected static function booted(): void
    {
        static::created(function (CustomCakeNotification $notification) {
            app(CustomCakePushService::class)->send($notification);
        });
    }
}
