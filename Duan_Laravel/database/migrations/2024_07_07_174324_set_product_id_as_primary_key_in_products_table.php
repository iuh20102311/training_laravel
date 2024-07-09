<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropPrimary('id'); // Bỏ khóa chính cũ nếu có
            $table->primary('product_id');
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropPrimary('product_id');
            $table->id(); // Khôi phục lại id tự tăng nếu cần
        });
    }
};