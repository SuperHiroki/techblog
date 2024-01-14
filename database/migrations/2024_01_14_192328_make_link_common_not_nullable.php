<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeLinkCommonNotNullable extends Migration
{
    public function up()
    {
        Schema::table('authors', function (Blueprint $table) {
            $table->string('link_common')->nullable(false)->change();
        });
    }

    public function down()
    {
        Schema::table('authors', function (Blueprint $table) {
            $table->string('link_common')->nullable()->change();
        });
    }
}
