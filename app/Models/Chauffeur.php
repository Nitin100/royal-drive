<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Chauffeur extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'contact_number',
        'slug',
        'photo_path',
        'license_number',
        'license_expiry_date',
        'experience_years',
        'languages_spoken',
        'rating',
        'availability_status',
        'meta_title',
        'meta_description',
        'bio',
        'availability_calendar',
    ];

    protected $casts = [
        'license_expiry_date' => 'date',
        'availability_calendar' => 'array',
        'rating' => 'decimal:2',
    ];

    public function assignments(): HasMany
    {
        return $this->hasMany(ChauffeurAssignment::class)->orderByDesc('service_date')->orderByDesc('assigned_at');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
