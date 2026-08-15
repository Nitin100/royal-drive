<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FleetImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'fleet_id',
        'image_path',
        'sort_order',
    ];

    public function fleet(): BelongsTo
    {
        return $this->belongsTo(Fleet::class);
    }
}
