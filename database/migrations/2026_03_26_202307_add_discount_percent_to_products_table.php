<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // discount_percent replaces the confusing discount_price (final-price) concept.
            // discount_percent = 0–100 percentage reduction applied to the product's base price.
            $table->decimal('discount_percent', 5, 2)->nullable()->after('price');
            // Clear old discount_price data to avoid confusion (was used as final price)
            $table->decimal('discount_price', 10, 2)->nullable()->change();
        });

        // Null out all old discount_price values since the field semantics are changing
        DB::statement("UPDATE products SET discount_price = NULL, discount_starts_at = NULL, discount_ends_at = NULL WHERE discount_price IS NOT NULL");
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('discount_percent');
        });
    }
};
