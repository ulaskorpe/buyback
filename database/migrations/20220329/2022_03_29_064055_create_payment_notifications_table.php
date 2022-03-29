<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        Schema::create('payment_notifications', function (Blueprint $table) {
//            $table->id();
//            $table->integer('order_id');
//            $table->integer('customer_id');
//            $table->text('description')->nullable()->default(null);
//            $table->string('document')->nullable()->default(null);
//            $table->date('date');
//            $table->boolean('status')->default(0);
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
      //  Schema::dropIfExists('payment_notifications');
    }
}
