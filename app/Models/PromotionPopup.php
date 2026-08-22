<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromotionPopup extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'subtitle',
        'body',
        'button_label',
        'button_url',
        'image_path',
        'is_active',
        'starts_at',
        'ends_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where(function ($builder) {
                $builder->whereNull('starts_at')->orWhere('starts_at', '<=', now());
            })
            ->where(function ($builder) {
                $builder->whereNull('ends_at')->orWhere('ends_at', '>=', now());
            });
    }
}
