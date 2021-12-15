<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSiteSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        Schema::create('site_settings', function (Blueprint $table) {
//            $table->id();
//            $table->string('setting_name');
//            $table->string('description')->nullable()->default(null);
//            $table->integer('value')->default(0);
//            $table->string('code')->unique();
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
      //  Schema::dropIfExists('site_settings');
    }
}
