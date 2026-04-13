<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->boolean('has_options')->default(false)->after('quantity_available');
        });

        $migrationRate = (float) env('PRICE_MIGRATION_SYP_TO_USD_RATE', 12000);

        if ($migrationRate <= 0) {
            throw new RuntimeException('PRICE_MIGRATION_SYP_TO_USD_RATE must be greater than zero before running product price migration.');
        }

        DB::table('products')
            ->select(['id', 'price'])
            ->orderBy('id')
            ->chunkById(100, function ($products) use ($migrationRate): void {
                foreach ($products as $product) {
                    DB::table('products')
                        ->where('id', $product->id)
                        ->update([
                            'price' => round(((float) $product->price) / $migrationRate, 2),
                        ]);
                }
            });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('has_options');
        });
    }
};
