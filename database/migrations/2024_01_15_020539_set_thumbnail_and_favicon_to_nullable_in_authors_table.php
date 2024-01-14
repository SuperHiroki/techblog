<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SetThumbnailAndFaviconToNullableInAuthorsTable extends Migration
{
    public function up()
    {
        Schema::table('authors', function (Blueprint $table) {
            $table->text('thumbnail_url')->nullable()->change();
            $table->text('favicon_url')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('authors', function (Blueprint $table) {
            $table->text('thumbnail_url')->nullable(false)->change();
            $table->text('favicon_url')->nullable(false)->change();
        });
    }
}       