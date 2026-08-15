<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('locations', function (Blueprint $table) {
            if (Schema::hasColumn('locations', 'city')) {
                $table->dropColumn('city');
            }

            if (Schema::hasColumn('locations', 'address')) {
                $table->dropColumn('address');
            }

            if (Schema::hasColumn('locations', 'description')) {
                $table->dropColumn('description');
            }
        });
    }

    public function down(): void
    {
        Schema::table('locations', function (Blueprint $table) {
            if (! Schema::hasColumn('locations', 'city')) {
                $table->string('city')->nullable()->after('type');
            }

            if (! Schema::hasColumn('locations', 'address')) {
                $table->string('address')->nullable()->after('city');
            }

            if (! Schema::hasColumn('locations', 'description')) {
                $table->text('description')->nullable()->after('address');
            }
        });
    }
};
