<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->foreignId('product_option_id')->nullable()->after('product_id')->constrained('product_options')->nullOnDelete();
            $table->string('product_option_label')->nullable()->after('product_option_id');
            $table->decimal('unit_price_usd', 14, 2)->nullable()->after('price_at_time_of_order');
            $table->decimal('unit_price_syp', 14, 2)->nullable()->after('unit_price_usd');
        });

        DB::table('order_items')
            ->update([
                'unit_price_syp' => DB::raw('price_at_time_of_order'),
            ]);
    }

    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropConstrainedForeignId('product_option_id');
            $table->dropColumn([
                'product_option_label',
                'unit_price_usd',
                'unit_price_syp',
            ]);
        });
    }
};
