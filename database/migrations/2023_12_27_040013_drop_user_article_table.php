<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropUserArticleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('user_article');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('user_article', function (Blueprint $table) {
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('article_id')->unsigned();

            $table->primary(['user_id', 'article_id']);

            $table->foreign('user_id')->references('id')->on('users')
                  ->onDelete('cascade');
            $table->foreign('article_id')->references('id')->on('articles')
                  ->onDelete('cascade');

            $table->timestamps();
        });
    }
}
