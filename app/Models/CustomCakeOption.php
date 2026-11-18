<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomCakeOption extends Model
{
    protected $fillable = [
        'option_type',
        'name',
        'description',
        'additional_price',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'additional_price' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name');
    }
}
