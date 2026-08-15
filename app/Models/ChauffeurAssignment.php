<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChauffeurAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'chauffeur_id',
        'booking_reference',
        'assigned_at',
        'service_date',
        'pickup_time',
        'route_name',
        'distance_km',
        'status',
        'notes',
    ];

    protected $casts = [
        'assigned_at' => 'datetime',
        'service_date' => 'date',
        'distance_km' => 'decimal:2',
    ];

    public function chauffeur(): BelongsTo
    {
        return $this->belongsTo(Chauffeur::class);
    }
}
