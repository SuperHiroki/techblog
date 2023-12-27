<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUniqueConstraintToUserAuthorFollows extends Migration
{
    public function up()
    {
        Schema::table('user_author_follows', function (Blueprint $table) {
            $table->unique(['user_id', 'author_id']);
        });
    }

    public function down()
    {
        Schema::table('user_author_follows', function (Blueprint $table) {
            $table->dropUnique(['user_id', 'author_id']);
        });
    }
}

