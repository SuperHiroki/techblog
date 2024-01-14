<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SetThumbnailAndFaviconToNullableInArticlesTable extends Migration
{
    public function up()
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->text('thumbnail_url')->nullable()->change();
            $table->text('favicon_url')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->text('thumbnail_url')->nullable(false)->change();
            $table->text('favicon_url')->nullable(false)->change();
        });
    }
}   
