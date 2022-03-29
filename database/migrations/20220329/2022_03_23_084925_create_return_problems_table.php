<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReturnProblemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        Schema::create('return_problems', function (Blueprint $table) {
//            $table->id();
//            $table->string('description')->nullable()->default(null);
//            $table->integer('order_id')->default(0);
//            $table->boolean('status')->default(1);
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
     //   Schema::dropIfExists('return_problems');
    }
}
