<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pickup_location_id')->constrained('locations')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('dropoff_location_id')->constrained('locations')->cascadeOnUpdate()->restrictOnDelete();
            $table->date('pickup_date');
            $table->time('pickup_time');
            $table->boolean('is_return_trip')->default(false);
            $table->unsignedTinyInteger('passenger_count');
            $table->unsignedTinyInteger('luggage_count')->default(0);
            $table->foreignId('fleet_id')->constrained('fleets')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('service_id')->constrained('services')->cascadeOnUpdate()->restrictOnDelete();
            $table->string('flight_number')->nullable();
            $table->text('special_requests')->nullable();
            $table->timestamps();

            $table->index(['pickup_date', 'pickup_time']);
            $table->index('is_return_trip');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
