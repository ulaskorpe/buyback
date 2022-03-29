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
//            $table->integer('cargo_company_id')->default(0);
//            $table->integer('cargo_company_branch_id')->default(0);
//            $table->string('cargo_code')->nullable()->default(null);
//            $table->integer('customer_id');
//            $table->integer('customer_address_id')->default(0);
//            $table->integer('service_address_id')->default(0);
//            $table->integer('order_method')->default(0);///0->cc else bank_accounts.id
//            $table->boolean('status')->default(0);//// 1completed
//            $table->float('amount')->default(0);
//            $table->float('shipping_price')->default(0);
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
      // Schema::dropIfExists('orders');
    }
}
