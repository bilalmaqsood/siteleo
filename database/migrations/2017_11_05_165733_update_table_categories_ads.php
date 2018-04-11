<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTableCategoriesAds extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories_ads', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('ads_id')->unsigned()->default(1);
            //$table->foreign('ads_id')->references('id')->on('ads');

            $table->integer('category_id')->unsigned()->default(1);
            //$table->foreign('categorie_id')->references('id')->on('categories');

            $table->foreign('ads_id')->references('id')->on('ads')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');

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
        Schema::dropIfExists('categories_ads');
    }
}
