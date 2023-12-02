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
        Schema::table('bot_messages', function (Blueprint $table) {
            $table->unsignedBigInteger('next_message_id')->nullable()->after('wait_answer');
        });
        Schema::table('bot_messages', function (Blueprint $table) {
            $table->foreign('next_message_id')
                ->references('id')->on('bot_messages')
                ->onUpdate('cascade')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bot_messages', function (Blueprint $table) {
            $table->dropForeign('bot_messages_next_message_id_foreign');
        });
        Schema::table('bot_messages', function (Blueprint $table) {
            $table->dropColumn('next_message_id');
        });
    }
};
