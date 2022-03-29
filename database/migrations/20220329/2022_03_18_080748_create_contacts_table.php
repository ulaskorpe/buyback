<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        Schema::create('contacts', function (Blueprint $table) {
//            $table->id();
//            $table->integer('customer_id')->default(0);
//            $table->string('guid')->nullable()->default(null);
//            $table->string('name')->nullable()->default(null);
//            $table->string('surname')->nullable()->default(null);
//            $table->string('email')->nullable()->default(null);
//            $table->string('phone_number')->nullable()->default(null);
//            $table->string('subject')->nullable()->default(null);
//            $table->string('message')->nullable()->default(null);
//            $table->tinyInteger('status')->default(0);
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
    //    Schema::dropIfExists('contacts');
    }
}
