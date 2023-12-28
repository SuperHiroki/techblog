<?php

//ここの一つ前の段階で、マイグレーションが失敗したり成功したりして、分からなくなった。

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('article_user_like', function (Blueprint $table) {
            $table->foreign('article_id')->references('id')->on('articles')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unique(['user_id', 'article_id']);
        });
    }

    public function down()
    {
        Schema::table('article_user_like', function (Blueprint $table) {
            $table->dropForeign(['article_id']);
            $table->dropForeign(['user_id']);
            $table->dropUnique(['user_id', 'article_id']);
        });
    }
};
