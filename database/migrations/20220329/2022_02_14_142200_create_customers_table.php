<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        Schema::create('customers', function (Blueprint $table) {
//            $table->id();
//            $table->string('customer_id')->unique();
//            $table->string('name');
//            $table->string('surname');
//            $table->string('avatar')->nullable()->default(null);
//            $table->integer('activation_key')->default(0);
//            $table->string('email')->unique();
//            $table->string('password');
//            $table->tinyInteger('status')->default(0);
//            $table->string('ip_address')->nullable()->default(null);
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
    //    Schema::dropIfExists('customers');
    }
}
