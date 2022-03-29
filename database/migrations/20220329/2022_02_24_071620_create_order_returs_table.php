<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderRetursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        Schema::create('order_returns', function (Blueprint $table) {
//            $table->id();
//            $table->integer('order_id');
//            $table->string('name_surname');
//            $table->integer('cargo_company_id')->default(0);
//            $table->integer('cargo_company_branch_id')->default(0);
//            $table->string('cargo_code')->default(null);
//            $table->integer('customer_address_id')->default(0);
//            $table->integer('service_address_id')->default(0);
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
   //     Schema::dropIfExists('order_returns');
    }
}
