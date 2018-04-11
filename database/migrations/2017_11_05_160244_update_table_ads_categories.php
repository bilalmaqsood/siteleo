<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTableAdsCategories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ads_categories', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('ads_id')->unsigned()->default(1);
            //$table->foreign('ads_id')->references('id')->on('ads');

            //$table->integer('categories_id')->unsigned()->default(1);
            //$table->foreign('categories_id')->references('id')->on('categories');

            $table->timestamps();
        });

        Schema::table('ads_categories', function($table) {
            $table->foreign('ads_id')->references('id')->on('ads');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ads_categories', function (Blueprint $table) {
            //
        });
    }
}
