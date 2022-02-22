<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        Schema::create('customer_addresses', function (Blueprint $table) {
//            $table->id();
//            $table->integer('customer_id');
//            $table->string('title');
//            $table->string('name_surname')->nullable()->default(null);
//            $table->string('address')->nullable()->default(null);
//            $table->integer('neighborhood_id')->default(0);
//            $table->integer('district_id')->default(0);
//            $table->integer('town_id')->default(0);
//            $table->integer('city_id')->default(0);
//            $table->string('phone_number')->nullable()->default(null);
//            $table->string('phone_number_2')->nullable()->default(null);
//            $table->boolean('first')->default(1);
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
//        Schema::dropIfExists('customer_addresses');
    }
}
