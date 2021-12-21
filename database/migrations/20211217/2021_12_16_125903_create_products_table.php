<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        Schema::create('products', function (Blueprint $table) {
//            $table->id();
//            $table->string('title');
//            $table->integer('brand_id')->default(0);
//            $table->integer('category_id')->default(0);
//            $table->integer('model_id')->default(0);
//            $table->text('description')->nullable()->default(null);
//            $table->integer('quantity')->default(0);
//            $table->float('price')->default(0);
//            $table->float('price_ex')->default(0);
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
      //  Schema::dropIfExists('products');
    }
}
