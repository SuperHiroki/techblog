<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->dropColumn(['good', 'bookmark', 'archive']);
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->tinyInteger('good')->default(0);
            $table->tinyInteger('bookmark')->default(0);
            $table->tinyInteger('archive')->default(0);
        });
    }
};
