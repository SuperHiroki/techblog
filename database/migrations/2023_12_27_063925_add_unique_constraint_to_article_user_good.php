<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUniqueConstraintToArticleUserGood extends Migration
{
    public function up()
    {
        Schema::table('article_user_good', function (Blueprint $table) {
            $table->unique(['user_id', 'article_id']);
        });
    }

    public function down()
    {
        Schema::table('article_user_good', function (Blueprint $table) {
            $table->dropUnique(['user_id', 'article_id']);
        });
    }
}

