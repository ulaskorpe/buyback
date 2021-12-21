<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenuSubItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        Schema::create('menu_sub_items', function (Blueprint $table) {
//            $table->id();
//            $table->integer('menu_id');
//            $table->string('title');
//            $table->string('link')->nullable()->default(null);
//            $table->string('thumb')->nullable()->default(null);
//            $table->string('image')->nullable()->default(null);
//            $table->integer('location')->default(0);
//            $table->integer('order')->default(0);
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
    //    Schema::dropIfExists('menu_sub_items');
    }
}
