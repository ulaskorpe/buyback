<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCargoBranchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        Schema::create('cargo_company_branches', function (Blueprint $table) {
//            $table->id();
//            $table->integer('cargo_company_id');
//            $table->string('title');
//            $table->string('person')->nullable()->default(null);
//            $table->string('address')->nullable()->default(null);
//            $table->integer('neighborhood_id')->default(0);
//            $table->integer('district_id')->default(0);
//            $table->integer('town_id')->default(0);
//            $table->integer('city_id')->default(0);
//            $table->string('phone_number')->nullable()->default(null);
//            $table->string('phone_number_2')->nullable()->default(null);
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
//        Schema::dropIfExists('cargo_branches');
//        Schema::dropIfExists('cargo_company_branches');
    }
}
