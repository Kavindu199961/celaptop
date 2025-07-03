<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
    {
        Schema::table('counters', function (Blueprint $table) {
            // Add user_id column as foreign key
            $table->foreignId('user_id')
                  ->after('id')
                  ->constrained()
                  ->onDelete('cascade');
            
            // Add composite unique index for key and user_id
            $table->unique(['key', 'user_id']);
        });
    }

    public function down()
    {
        Schema::table('counters', function (Blueprint $table) {
            // Drop the unique index first
            $table->dropUnique(['key', 'user_id']);
            
            // Then drop the foreign key constraint
            $table->dropForeign(['user_id']);
            
            // Finally drop the column
            $table->dropColumn('user_id');
        });
    }
};
