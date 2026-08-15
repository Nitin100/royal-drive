<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    public const TYPES = [
        'airport',
        'city',
        'hotel',
        'tourist_attraction',
        'business_center',
    ];

    protected $fillable = [
        'name',
        'slug',
        'type',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public static function typeOptions(): array
    {
        return [
            'airport' => 'Airport Locations',
            'city' => 'Cities',
            'hotel' => 'Hotels',
            'tourist_attraction' => 'Tourist Attractions',
            'business_center' => 'Business Centers',
        ];
    }
}
