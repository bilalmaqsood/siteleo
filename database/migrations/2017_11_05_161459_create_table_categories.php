<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableCategories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->increments('id')->unsigned()->index();
            $table->integer('parent_id')->unsigned()->nullable();
            $table->string('uri');
            $table->integer('order')->default('0');
            $table->string('title');
            $table->text('icon')->nullabe();
            $table->integer('ads_count')->default(0);
            $table->text('seo_title')->default(null);
            $table->text('seo_keywords')->default(null);
            $table->text('seo_description')->default(null);

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
        Schema::dropIfExists('categories');
    }
}
