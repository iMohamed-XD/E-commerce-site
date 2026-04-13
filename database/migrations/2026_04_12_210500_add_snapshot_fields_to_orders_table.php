<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->text('buyer_location_text')->nullable()->after('buyer_address');
            $table->string('buyer_city')->nullable()->after('buyer_location_text');
            $table->string('seller_city_snapshot')->nullable()->after('buyer_city');
            $table->string('delivery_estimate', 32)->nullable()->after('seller_city_snapshot');
            $table->decimal('usd_to_syp_rate', 15, 6)->nullable()->after('delivery_estimate');
            $table->decimal('subtotal_usd', 14, 2)->nullable()->after('usd_to_syp_rate');
            $table->decimal('subtotal_syp', 14, 2)->nullable()->after('subtotal_usd');
            $table->decimal('discount_amount_usd', 14, 2)->nullable()->after('subtotal_syp');
            $table->decimal('discount_amount_syp', 14, 2)->nullable()->after('discount_amount_usd');
            $table->decimal('final_total_usd', 14, 2)->nullable()->after('discount_amount_syp');
            $table->decimal('final_total_syp', 14, 2)->nullable()->after('final_total_usd');
        });

        DB::table('orders')
            ->update([
                'buyer_location_text' => DB::raw('buyer_address'),
                'final_total_syp' => DB::raw('total_amount'),
            ]);
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'buyer_location_text',
                'buyer_city',
                'seller_city_snapshot',
                'delivery_estimate',
                'usd_to_syp_rate',
                'subtotal_usd',
                'subtotal_syp',
                'discount_amount_usd',
                'discount_amount_syp',
                'final_total_usd',
                'final_total_syp',
            ]);
        });
    }
};
