<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCreditCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        Schema::create('credit_cards', function (Blueprint $table) {
//            $table->id();
//            $table->string('cc_no');
//            $table->string('expires');
//            $table->string('cvc');
//            $table->integer('customer_id');
//            $table->boolean('status');
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
    //    Schema::dropIfExists('credit_cards');
    }
}
