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
        Schema::table('laptop_repairs', function (Blueprint $table) {
            // Add the user_id column as a foreign key
            $table->foreignId('user_id')
                  ->constrained() // references the 'id' column on 'users' table
                  ->onDelete('cascade'); // delete repairs when user is deleted
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('laptop_repairs', function (Blueprint $table) {
            // Drop the foreign key constraint first
            $table->dropForeign(['user_id']);
            // Then drop the column
            $table->dropColumn('user_id');
        });
    }
};
