<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('shops', function (Blueprint $table) {
            $table->string('shamcash_account_number')->nullable()->after('logo_path');
            $table->string('shamcash_qr_path')->nullable()->after('shamcash_account_number');
            $table->boolean('shamcash_is_active')->default(false)->after('shamcash_qr_path');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->string('payment_method')->default('cod')->after('promo_code_used');
            $table->string('shamcash_transaction_number')->nullable()->after('payment_method');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['payment_method', 'shamcash_transaction_number']);
        });

        Schema::table('shops', function (Blueprint $table) {
            $table->dropColumn(['shamcash_account_number', 'shamcash_qr_path', 'shamcash_is_active']);
        });
    }
};

