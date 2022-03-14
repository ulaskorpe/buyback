<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerOffersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        Schema::create('customer_offers', function (Blueprint $table) {
//            $table->id();
//            $table->integer('customer_id');
//            $table->integer('model_id');
//            $table->string('imei_no')->unique();
//            $table->string('answers')->nullable()->default(null);
//            $table->float('discount')->default(0);
//            $table->float('offered_price')->default(0);
//            $table->date('date');
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
      //  Schema::dropIfExists('customer_offers');
    }
}
