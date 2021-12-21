<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBuybacksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        Schema::create('buybacks', function (Blueprint $table) {
//            $table->id();
//            $table->integer('buyback_user_id');
//            $table->string('imei');
//            $table->integer('model_id');
//            $table->integer('color_id')->default(0);
//            $table->float('offer_price')->default(0);
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
  //      Schema::dropIfExists('buybacks');
    }
}
