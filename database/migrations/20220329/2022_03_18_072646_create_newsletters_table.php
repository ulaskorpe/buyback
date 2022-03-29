<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewslettersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        Schema::create('newsletters', function (Blueprint $table) {
//            $table->id();
//            $table->integer('customer_id')->default(0);
//            $table->string('guid')->nullable()->default(null);
//            $table->string('email');
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
     //   Schema::dropIfExists('newsletters');
    }
}
