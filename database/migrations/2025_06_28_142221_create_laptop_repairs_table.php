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
        Schema::create('laptop_repairs', function (Blueprint $table) {
            $table->id();
            $table->string('customer_name');
            $table->string('contact');
            $table->date('date');
            $table->text('fault');
            $table->string('device');
            $table->decimal('repair_price', 10, 2);
            $table->string('serial_number');
            $table->string('note_number')->nullable();
            $table->enum('status', ['pending', 'in_progress', 'completed', 'cancelled'])->default('pending');
            $table->string('customer_number')->unique()->default('CE-0001');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laptop_repairs');
    }
};
