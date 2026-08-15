<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chauffeur_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chauffeur_id')->constrained()->cascadeOnDelete();
            $table->string('booking_reference');
            $table->dateTime('assigned_at');
            $table->date('service_date');
            $table->time('pickup_time')->nullable();
            $table->string('route_name')->nullable();
            $table->decimal('distance_km', 8, 2)->nullable();
            $table->enum('status', ['assigned', 'completed', 'cancelled'])->default('assigned');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chauffeur_assignments');
    }
};
