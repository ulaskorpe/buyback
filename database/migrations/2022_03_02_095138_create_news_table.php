<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        Schema::create('news', function (Blueprint $table) {
//            $table->id();
//            $table->string('title');
//            $table->string('image')->nullable()->default(null);
//            $table->string('thumb')->nullable()->default(null);
//            $table->string('url')->nullable()->default(null);
//            $table->string('description')->nullable()->default(null);
//            $table->text('content')->nullable()->default(null);
//            $table->date('date')->default(null);
//            $table->boolean('active')->default(1);
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
      //  Schema::dropIfExists('news');
    }
}
