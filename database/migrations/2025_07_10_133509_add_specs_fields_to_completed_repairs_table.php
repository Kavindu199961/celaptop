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
            $table->enum('ram', ['4GB', '8GB', '12GB', '16GB', '32GB', '64GB'])->nullable();
            $table->boolean('hdd')->default(false);
            $table->boolean('ssd')->default(false);
            $table->boolean('nvme')->default(false);
            $table->boolean('battery')->default(false);
            $table->boolean('dvd_rom')->default(false);
            $table->boolean('keyboard')->default(false);
        });
       
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('completed_repairs', function (Blueprint $table) {
           $table->dropColumn(['ram', 'hdd', 'ssd', 'nvme', 'battery', 'dvd_rom', 'keyboard']);
        });
    }
};
