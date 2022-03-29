<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        Schema::create('order_addresses', function (Blueprint $table) {
//            $table->id();
//            $table->integer('customer_id');
//            $table->integer('order_id');
//            $table->string('name')->nullable()->default(null);
//            $table->string('surname')->nullable()->default(null);
//            $table->string('address_1')->nullable()->default(null);
//            $table->string('address_2')->nullable()->default(null);
//            $table->string('city')->nullable()->default(null);
//            $table->string('zipcode')->nullable()->default(null);
//            $table->string('phone')->nullable()->default(null);
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
   //     Schema::dropIfExists('order_addresses');
    }
}
