<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeLinksColumnsToTextInArticlesTable extends Migration
{
    public function up()
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->text('thumbnail_url')->change();
            $table->text('favicon_url')->change();
        });
    }

    public function down()
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->string('thumbnail_url', 767)->change();
            $table->string('favicon_url', 767)->change();
        });
    }
}

