<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropUserAuthorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('user_author');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('user_author', function (Blueprint $table) {
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('author_id')->unsigned();

            $table->primary(['user_id', 'author_id']);

            $table->foreign('user_id')->references('id')->on('users')
                  ->onDelete('cascade');
            $table->foreign('author_id')->references('id')->on('authors')
                  ->onDelete('cascade');

            $table->timestamps();
        });
    }
}
