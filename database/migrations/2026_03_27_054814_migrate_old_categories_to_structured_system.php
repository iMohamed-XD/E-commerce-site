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
        // Migrate data from 'category' string to 'category_id'
        $products = \Illuminate\Support\Facades\DB::table('products')->get();
        foreach ($products as $product) {
            if (!empty($product->category) && empty($product->category_id)) {
                $category = \Illuminate\Support\Facades\DB::table('categories')
                    ->where('shop_id', $product->shop_id)
                    ->where('name', $product->category)
                    ->first();

                if (!$category) {
                    $categoryId = \Illuminate\Support\Facades\DB::table('categories')->insertGetId([
                        'shop_id' => $product->shop_id,
                        'name' => $product->category,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                } else {
                    $categoryId = $category->id;
                }

                \Illuminate\Support\Facades\DB::table('products')
                    ->where('id', $product->id)
                    ->update(['category_id' => $categoryId]);
            }
        }

        // Drop the old string column
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('category');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('category')->nullable();
        });
    }
};
