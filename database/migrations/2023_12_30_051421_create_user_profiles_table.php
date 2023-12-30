<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserProfilesTable extends Migration
{
    public function up()
    {
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('public_email')->nullable();
            $table->string('github')->nullable();
            $table->string('website')->nullable();
            $table->string('organization')->nullable();
            $table->string('location')->nullable();
            $table->text('bio')->nullable();
            $table->string('sns1')->nullable();
            $table->string('sns2')->nullable();
            $table->string('sns3')->nullable();
            $table->string('sns4')->nullable();
            $table->string('sns5')->nullable();
            $table->string('sns6')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_profiles');
    }
}
