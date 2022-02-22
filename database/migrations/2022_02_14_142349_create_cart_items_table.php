<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCartItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        Schema::create('cart_items', function (Blueprint $table) {
//            $table->id();
//            $table->string('item_code')->unique();
//            $table->integer('customer_id');
//            $table->integer('product_id');
//            $table->integer('memory_id')->default(0);
//            $table->integer('color_id')->default(0);
//            $table->integer('order_id')->default(0);
//            $table->tinyInteger('status')->default(0);
//            $table->integer('quantity')->default(1);
//            $table->float('price')->default(0);
//            $table->softDeletes();
//            $table->timestamps();
//        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      // Schema::dropIfExists('cart_items');
    }
}
