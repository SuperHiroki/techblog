<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::rename('article_user_good', 'article_user_like');
    }
    
    public function down()
    {
        Schema::rename('article_user_like', 'article_user_good');
    }
};
