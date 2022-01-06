<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductStockMovementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        Schema::create('product_stock_movements', function (Blueprint $table) {
//            $table->id();
//            $table->integer('product_id');
//            $table->integer('color_id')->default(0);
//            $table->integer('memory_id')->default(0);
//            $table->integer('quantity')->default(0);
//            $table->enum('status',['in','out']);
//            $table->softDeletes();
//
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
     //   Schema::dropIfExists('product_stock_movements');
    }
}
