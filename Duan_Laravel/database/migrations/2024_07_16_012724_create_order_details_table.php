<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('order_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->string('product_id');
            $table->string('product_name');
            $table->float('price_buy');
            $table->integer('quantity');
            $table->unsignedBigInteger('shipping_address_id');
            $table->timestamps();
        
            $table->foreign('order_id')->references('order_id')->on('orders')->onDelete('cascade');
            $table->foreign('product_id')->references('product_id')->on('products')->onDelete('cascade');
            $table->foreign('shipping_address_id')->references('id')->on('shipping_addresses')->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_details');
    }
};
