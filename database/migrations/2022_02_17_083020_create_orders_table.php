<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        Schema::create('orders', function (Blueprint $table) {
//            $table->id();
//            $table->string('order_code')->unique();
//            $table->string('name_surname');
//            $table->integer('cargo_company_id');
//            $table->integer('cargo_company_branch_id');
//            $table->string('cargo_code');
//            $table->integer('customer_id');
//            $table->integer('customer_address_id');
//            $table->integer('service_address_id');
//            $table->boolean('status')->default(0);
//            $table->boolean('direction')->default(0);
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
    //    Schema::dropIfExists('orders');
    }
}
