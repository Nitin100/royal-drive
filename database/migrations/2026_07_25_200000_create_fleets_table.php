<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('fleets', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('category');
            $table->string('brand');
            $table->unsignedInteger('passenger_capacity')->nullable();
            $table->unsignedInteger('luggage_capacity')->nullable();
            $table->string('availability_status')->default('available');
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('banner_image_path')->nullable();
            $table->longText('description')->nullable();
            $table->json('pricing_config')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fleets');
    }
};
