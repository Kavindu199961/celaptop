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
        Schema::table('note_counters', function (Blueprint $table) {
            $table->foreignId('user_id')->after('id')->constrained()->onDelete('cascade');

            // If you want to prevent duplicate key/user_id pairs
            $table->unique(['user_id', 'key']);
        });
    }

    public function down(): void
    {
        Schema::table('note_counters', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropUnique(['user_id', 'key']);
            $table->dropColumn('user_id');
        });
    }
};
