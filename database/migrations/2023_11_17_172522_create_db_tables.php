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
        Schema::create('sessions', function (Blueprint $table) {
            $table->id();
            $table->string('external_id')->unique();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('username')->nullable();
            $table->integer('status')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title')->unique();
            $table->integer('ord')->default(100);
            $table->boolean('enabled')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('bot_messages', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->unsignedBigInteger('task_id')->nullable();
            $table->integer('content_type')->default(1);
            $table->text('text_content')->nullable();
            $table->text('external_url')->nullable();
            $table->text('image')->nullable();
            $table->timestamps();
        });
        Schema::table('bot_messages', function (Blueprint $table) {
            $table->foreign('task_id')
                ->on('tasks')->references('id')
                ->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::create('actions', function (Blueprint $table) {
            $table->id();
            $table->text('text')->nullable(false);
            $table->unsignedBigInteger('message_id');
            $table->unsignedBigInteger('next_message_id')->nullable();
            $table->timestamps();
        });
        Schema::table('actions', function (Blueprint $table) {
            $table->foreign('message_id')
                ->on('bot_messages')->references('id')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('next_message_id')
                ->on('bot_messages')->references('id')
                ->onUpdate('cascade')->onDelete('cascade');
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('actions');
        Schema::dropIfExists('bot_messages');
        Schema::dropIfExists('tasks');
        Schema::dropIfExists('users');
    }
};
