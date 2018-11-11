<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class OrderSumHistories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_sum_histories', function (Blueprint $table) {
            $table->increments('id');
            $table->double('order_id')->unique();
            $table->integer('token_no');
            $table->dateTime('order_time');
            $table->string('customer_type');
            $table->string('seller_name');
            $table->float('subtotal');
            $table->float('Total_discount');
            $table->float('total_price');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_sum_histories');
    }
}
