<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('razorpay_payment_id')->nullable()->after('razorpay_order_id');
            $table->string('payment_status')->nullable()->default('pending')->after('razorpay_payment_id');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['razorpay_payment_id', 'payment_status']);
        });
    }
};
