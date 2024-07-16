<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id('order_id');
            $table->unsignedBigInteger('discount_code_id')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->string('order_number', 20)->unique();
            $table->string('user_name');
            $table->string('user_email');
            $table->string('phone_number');
            $table->dateTime('order_date');
            $table->tinyInteger('order_status')->default(0)->comment('0: Đang xử lý, 1: Đã xác nhận, 2: Đã hủy');
            $table->tinyInteger('payment_method')->default(1)->comment('1: COD, 2: PayPal');
            $table->dateTime('shipment_date')->nullable();
            $table->dateTime('cancel_date')->nullable();
            $table->decimal('sub_total', 10, 2);
            $table->decimal('total', 10, 2);
            $table->decimal('tax', 10, 2);
            $table->decimal('ship_charge', 10, 2);
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->string('discount_code')->nullable();
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('discount_code_id')->references('id')->on('discount_codes')->onDelete('set null');        
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
