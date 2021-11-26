<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBuybackUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('buyback_users', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->default(0);
            $table->string('name')->nullable()->default(null);
            $table->string('surname')->nullable()->default(null);
            $table->string('tckn')->nullable()->default(null);
            $table->string('phone')->nullable()->default(null);
            $table->string('email')->nullable()->default(null);
            $table->integer('city_id')->default(0);
            $table->integer('town_id')->default(0);
            $table->integer('district_id')->default(0);
            $table->integer('neighborhood_id')->default(0);
            $table->string('address')->nullable()->default(null);
            $table->string('iban')->nullable()->default(null);
            $table->boolean('terms_of_use')->default(0);
            $table->boolean('campaigns')->default(0);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('buyback_users');
    }
}
