<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropUnusedTables extends Migration
{
    public function up()
    {
        // Drop unused tables
        Schema::dropIfExists('tbl_social');
        Schema::dropIfExists('failed_jobs');
        Schema::dropIfExists('password_resets');
    }

    public function down()
    {
        // Recreate tables if needed to rollback
        Schema::create('tbl_social', function (Blueprint $table) {
            $table->increments('user_id');
            $table->string('provider_user_id');
            $table->string('provider');
            $table->integer('user');
        });

        Schema::create('failed_jobs', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->text('connection');
            $table->text('queue');
            $table->longText('payload');
            $table->longText('exception');
            $table->timestamp('failed_at')->useCurrent();
        });

        Schema::create('password_resets', function (Blueprint $table) {
            $table->string('email')->index();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });
    }
} 