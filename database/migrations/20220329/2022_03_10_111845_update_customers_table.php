<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        Schema::table('customers', function (Blueprint $table) {
//
//            $table->enum('gender', ['male','female',null])->default(null)->after('avatar');
//
//        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
//                Schema::table('customers', function (Blueprint $table) {
//
//                  $table->dropColumn('gender');
//        });
    }
}
