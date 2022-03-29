<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHumanResourcesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        Schema::create('human_resources', function (Blueprint $table) {
//            $table->id();
//            $table->string('name')->nullable()->default(null);
//            $table->string('surname')->nullable()->default(null);
//            $table->string('expectation')->nullable()->default(null);
//            $table->string('department')->nullable()->default(null);
//            $table->string('cv_file')->nullable()->default(null);
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
    //    Schema::dropIfExists('human_resources');
    }
}
