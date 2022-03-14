<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterBuybackUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//       Schema::table('buyback_users', function (Blueprint $table) {
//
//            $table->integer('customer_id')->default(0)->after('user_id');
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
//        Schema::table('buybacks', function (Blueprint $table) {
//
//                  $table->dropColumn('buyback_users');
//        });
    }
}
