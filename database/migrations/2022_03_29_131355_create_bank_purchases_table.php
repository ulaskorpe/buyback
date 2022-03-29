<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBankPurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        Schema::create('bank_purchases', function (Blueprint $table) {
//            $table->id();
//            $table->integer('bank_id');
//            $table->integer('purchase_id');
//            $table->integer('purchase');
//            $table->float('commission')->default(0);
//            $table->integer('payment_plan_id')->default(0);
//            $table->integer('description_id')->default(0);
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
    //    Schema::dropIfExists('bank_purchases');
    }
}
