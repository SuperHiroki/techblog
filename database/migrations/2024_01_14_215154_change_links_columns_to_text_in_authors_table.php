<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

//->nullable(true)を明示的に指定しないとエラーになる。

class ChangeLinksColumnsToTextInAuthorsTable extends Migration
{
    public function up()
    {
        Schema::table('authors', function (Blueprint $table) {
            $table->string('thumbnail_url', 767)->nullable(true)->change();
            $table->string('favicon_url', 767)->nullable(true)->change();
        });
    }

    public function down()
    {
        Schema::table('authors', function (Blueprint $table) {
            $table->string('thumbnail_url', 255)->nullable(true)->change();
            $table->string('favicon_url', 255)->nullable(true)->change();
        });
    }
}
