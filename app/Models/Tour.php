<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tour extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'meta_title',
        'meta_description',
        'featured_image_path',
        'content',
        'is_featured',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
    ];

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(TourCategory::class, 'tour_category_tour')->withTimestamps();
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(TourTag::class, 'tour_tag_tour')->withTimestamps();
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}