<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBankAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        Schema::create('bank_accounts', function (Blueprint $table) {
//            $table->id();
//            $table->string('bank_name')->nullable()->default(null);
//            $table->string('name_surname')->nullable()->default(null);
//            $table->string('branch')->nullable()->default(null);
//            $table->integer('account_number')->default(0);
//            $table->string('iban')->nullable()->default(null);
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
    //    Schema::dropIfExists('bank_accounts');
    }
}
