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
            $table->unsignedInteger('wait_time')->nullable()->after('image');
            $table->unsignedBigInteger('prev_message_id')->nullable()->after('image');
            $table->boolean('wait_answer')->default(false)->after('image');
        });
        Schema::table('bot_messages', function (Blueprint $table) {
            $table->foreign('prev_message_id')
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
            $table->dropForeign('bot_messages_prev_message_id_foreign');
        });
        Schema::table('bot_messages', function (Blueprint $table) {
            $table->dropColumn('wait_answer');
            $table->dropColumn('prev_message_id');
            $table->dropColumn('wait_time');
        });
    }
};
