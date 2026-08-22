<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    use HasFactory;

    public const STATUSES = [
        'pending',
        'confirmed',
        'assigned',
        'in_progress',
        'completed',
        'cancelled',
    ];

    protected $fillable = [
        'name',
        'email',
        'contact',
        'pickup_location_id',
        'dropoff_location_id',
        'pickup_date',
        'pickup_time',
        'is_return_trip',
        'passenger_count',
        'luggage_count',
        'fleet_id',
        'service_id',
        'status',
        'flight_number',
        'special_requests',
    ];

    protected $casts = [
        'pickup_date' => 'date',
        'is_return_trip' => 'boolean',
    ];

    public static function statusOptions(): array
    {
        return [
            'pending' => 'Pending',
            'confirmed' => 'Confirmed',
            'assigned' => 'Assigned',
            'in_progress' => 'In Progress',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled',
        ];
    }

    public function pickupLocation(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'pickup_location_id');
    }

    public function dropoffLocation(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'dropoff_location_id');
    }

    public function fleet(): BelongsTo
    {
        return $this->belongsTo(Fleet::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }
}
