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
        Schema::create('user_answers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('message_id');
            $table->text('answer');
            $table->boolean('correct')->nullable();
            $table->timestamps();
        });
        Schema::table('user_answers', function (Blueprint $table) {
            $table->foreign('user_id')
                ->references('id')->on('sessions')
                ->onUpdate('restrict')->onDelete('restrict');
            $table->foreign('message_id')
                ->references('id')->on('bot_messages')
                ->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_answers', function (Blueprint $table) {
            $table->dropForeign('user_answers_user_id_foreign');
            $table->dropForeign('user_answers_message_id_foreign');
        });
        Schema::dropIfExists('user_answers');
    }
};
