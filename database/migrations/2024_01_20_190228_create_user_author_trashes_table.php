<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserAuthorTrashesTable extends Migration
{
    public function up()
    {
        Schema::create('user_author_trashes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('author_id');
            $table->timestamps();

            $table->unique(['user_id', 'author_id']);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('author_id')->references('id')->on('authors')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_author_trashes');
    }
}
