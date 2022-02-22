<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenuSubItemLinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        Schema::create('menu_sub_item_links', function (Blueprint $table) {
//            $table->id();
//            $table->integer('sub_link_group_id');
//            $table->string('title');
//            $table->string('link')->nullable()->default(null);
//            $table->integer('order');
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
    //    Schema::dropIfExists('menu_sub_item_links');
    }
}
