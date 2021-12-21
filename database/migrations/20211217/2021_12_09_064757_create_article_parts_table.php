<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticlePartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        Schema::create('article_parts', function (Blueprint $table) {
//            $table->id();
//            $table->integer('article_id');
//            $table->string('title')->nullable()->default(null);
//            $table->string('image')->nullable()->default(null);
//            $table->string('thumb')->nullable()->default(null);
//            $table->text('paragraph')->nullable()->default(null);
//            $table->integer('count');
//            $table->boolean('status');
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
      //  Schema::dropIfExists('article_parts');
    }
}
