<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Promo extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'badge',
        'description',
        'image',
        'code',
        'discount_type',
        'discount_value',
        'max_discount',
        'min_purchase',
        'usage_limit',
        'used_count',
        'first_order_only',
        'starts_at',
        'ends_at',
        'is_active',
        'terms',
    ];

    protected function casts(): array
    {
        return [
            'discount_value' => 'decimal:2',
            'max_discount' => 'decimal:2',
            'min_purchase' => 'decimal:2',
            'usage_limit' => 'integer',
            'used_count' => 'integer',
            'first_order_only' => 'boolean',
            'is_active' => 'boolean',
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'terms' => 'array',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Promo $promo) {
            if (empty($promo->slug)) {
                $promo->slug = Str::slug($promo->title);
            }

            $promo->code = strtoupper($promo->code);
        });
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query
            ->where('is_active', true)
            ->where('starts_at', '<=', now())
            ->where('ends_at', '>=', now());
    }

    public function isCurrentlyActive(): bool
    {
        return $this->is_active
            && $this->starts_at <= now()
            && $this->ends_at >= now();
    }
}
