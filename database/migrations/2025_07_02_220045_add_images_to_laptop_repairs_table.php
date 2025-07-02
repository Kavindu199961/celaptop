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
        Schema::table('laptop_repairs', function (Blueprint $table) {
            $table->json('images')->nullable()->after('status'); // Store multiple image paths as JSON
        });
        }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('laptop_repairs', function (Blueprint $table) {
        $table->dropColumn('images');
    });
    }
};
