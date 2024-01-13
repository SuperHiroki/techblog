<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateArticlesTable extends Migration
{
    public function up()
    {
        Schema::table('articles', function (Blueprint $table) {
            //$table->text('link')->change();
            //$table->text('thumbnail_url')->change();
            //$table->text('favicon_url')->change();

            //MySQLでは、インデックスに使用できる最大バイト数は3072バイトです.
            //VARCHAR(1024)は最大で4096バイト（1024文字 × 4バイト）を必要とし、これは3072バイトの制限を超えます。
            $table->string('link', 767)->change();
            $table->string('thumbnail_url', 767)->change();
            $table->string('favicon_url', 767)->change();
        });
    }

    public function down()
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->string('link', 255)->change();
            $table->string('thumbnail_url', 255)->change();
            $table->string('favicon_url', 255)->change();
        });
    }
}