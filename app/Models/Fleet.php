<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Fleet extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'category',
        'brand',
        'passenger_capacity',
        'luggage_capacity',
        'availability_status',
        'meta_title',
        'meta_description',
        'banner_image_path',
        'description',
        'pricing_config',
    ];

    protected $casts = [
        'pricing_config' => 'array',
    ];

    public function images(): HasMany
    {
        return $this->hasMany(FleetImage::class)->orderBy('sort_order')->orderBy('id');
    }

    public function amenityFleet(): HasMany
    {
        return $this->hasMany(AmenityFleet::class);
    }

    public function amenities(): BelongsToMany
    {
        return $this->belongsToMany(Amenity::class);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
