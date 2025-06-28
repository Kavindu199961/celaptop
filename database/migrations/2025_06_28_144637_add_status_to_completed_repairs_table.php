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
    Schema::table('completed_repairs', function (Blueprint $table) {
        $table->enum('status', ['pending', 'in_progress', 'completed', 'cancelled'])
              ->default('completed')
              ->after('customer_number');
    });
}

public function down(): void
{
    Schema::table('completed_repairs', function (Blueprint $table) {
        $table->dropColumn('status');
    });
}
};
