<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerProductVotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        Schema::create('customer_product_votes', function (Blueprint $table) {
//            $table->id();
//            $table->integer('customer_id')->default(0);
//            $table->integer('product_id')->default(0);
//            $table->integer('vote')->default(0);
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
       //Schema::dropIfExists('customer_product_votes');
    }
}
