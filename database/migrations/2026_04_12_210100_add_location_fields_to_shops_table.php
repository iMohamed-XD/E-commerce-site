<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('shops', function (Blueprint $table) {
            $table->string('location_text')->nullable()->after('description');
            $table->string('city')->nullable()->after('location_text');
            $table->decimal('latitude', 10, 7)->nullable()->after('city');
            $table->decimal('longitude', 10, 7)->nullable()->after('latitude');
            $table->boolean('same_day_delivery_enabled')->default(true)->after('longitude');
        });
    }

    public function down(): void
    {
        Schema::table('shops', function (Blueprint $table) {
            $table->dropColumn([
                'location_text',
                'city',
                'latitude',
                'longitude',
                'same_day_delivery_enabled',
            ]);
        });
    }
};
