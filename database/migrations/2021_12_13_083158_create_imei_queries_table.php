<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImeiQueriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('imei_queries', function (Blueprint $table) {
            $table->id();
            $table->string('imei');
            $table->integer('user_id')->default(0);
            $table->integer('model_id')->default(0);
            $table->boolean('result')->default(0);
            $table->string('token',100);
            $table->string('token_type',25);
            $table->string('scope',100);
            $table->string('ip_address',50);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('imei_queries');
    }
}
