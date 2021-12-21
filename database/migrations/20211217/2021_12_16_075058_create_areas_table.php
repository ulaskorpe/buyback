<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAreasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        Schema::create('areas', function (Blueprint $table) {
//            $table->id();
//            $table->string('title');
//            $table->string('txt_1')->nullable()->default(null);
//            $table->string('txt_2')->nullable()->default(null);
//            $table->string('thumb')->nullable()->default(null);
//            $table->string('image')->nullable()->default(null);
//            $table->string('link')->nullable()->default(null);
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
        //Schema::dropIfExists('areas');
    }
}
