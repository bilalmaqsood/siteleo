<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->increments('id');
            $table->string('uri')->comment('Идентификатор страницы');
            $table->text('page_name')->comment('Название страницы');
            $table->longText('page_description')->comment('Описание страницы');
            $table->text('header_name')->comment('Заголовок страницы');
            $table->string('header_cover')->comment('Изображение заголовка страницы');
            $table->longText('header_text')->comment('Контент заголовка страницы');
            $table->longText('seo_title')->comment('Мета тег Title');
            $table->longText('seo_keywords')->comment('Мета тег Keywords');
            $table->longText('seo_description')->comment('Мета тег Description');
            $table->integer('activ')->default('1')->comment('Активация страницы');
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
        Schema::dropIfExists('pages');
    }
}
