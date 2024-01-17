<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCascadeToArticlesAuthorIdForeign extends Migration
{
    public function up()
    {
        Schema::table('articles', function (Blueprint $table) {
            // まず現在の外部キー制約を削除
            $table->dropForeign(['author_id']);

            // 外部キー制約を再設定し、ON DELETE CASCADEを適用
            $table->foreign('author_id')
                  ->references('id')->on('authors')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('articles', function (Blueprint $table) {
            // ON DELETE CASCADEの削除
            $table->dropForeign(['author_id']);

            // 元の外部キー制約を復元
            $table->foreign('author_id')
                  ->references('id')->on('authors');
        });
    }
}

