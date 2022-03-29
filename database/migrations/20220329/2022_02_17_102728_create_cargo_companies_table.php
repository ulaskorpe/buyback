<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCargoCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        Schema::create('cargo_companies', function (Blueprint $table) {
//            $table->id();
//            $table->string('name');
//            $table->string('person')->nullable()->default(null);
//            $table->string('logo')->nullable()->default(null);
//            $table->string('email')->nullable()->default(null);
//            $table->string('phone_number')->nullable()->default(null);
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
    //    Schema::dropIfExists('cargo_companies');
    }
}
