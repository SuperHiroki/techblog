<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUniqueConstraintToCommentLikes extends Migration
{
    public function up()
    {
        Schema::table('comment_likes', function (Blueprint $table) {
            // comment_id と user_id の組み合わせでユニーク制約を追加
            $table->unique(['comment_id', 'user_id']);
        });
    }

    public function down()
    {
        Schema::table('comment_likes', function (Blueprint $table) {
            // ユニーク制約を削除
            $table->dropUnique(['comment_id', 'user_id']);
        });
    }
}
