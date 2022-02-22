<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerFavoritesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        Schema::create('customer_favorites', function (Blueprint $table) {
//            $table->id();
//            $table->integer('customer_id');
//            $table->integer('product_id');
//            $table->integer('memory_id')->default(0);
//            $table->integer('color_id')->default(0);
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
        //Schema::dropIfExists('customer_favorites');
    }
}
