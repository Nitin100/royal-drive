<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chauffeurs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('contact_number');
            $table->string('slug')->unique();
            $table->string('photo_path')->nullable();
            $table->string('license_number')->nullable();
            $table->date('license_expiry_date')->nullable();
            $table->unsignedInteger('experience_years')->nullable();
            $table->string('languages_spoken')->nullable();
            $table->decimal('rating', 3, 2)->default(0);
            $table->string('availability_status')->default('available');
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->longText('bio')->nullable();
            $table->json('availability_calendar')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chauffeurs');
    }
};
