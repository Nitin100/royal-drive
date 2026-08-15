<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class TourTag extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
    ];

    public function tours(): BelongsToMany
    {
        return $this->belongsToMany(Tour::class, 'tour_tag_tour')->withTimestamps();
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}