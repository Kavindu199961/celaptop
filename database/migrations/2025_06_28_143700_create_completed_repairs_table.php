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
    Schema::create('completed_repairs', function (Blueprint $table) {
        $table->id();
        $table->string('customer_name');
        $table->string('contact');
        $table->date('date');
        $table->text('fault');
        $table->string('device');
        $table->decimal('repair_price', 10, 2)->nullable();
        $table->string('serial_number');
        $table->string('note_number')->nullable();
        $table->string('customer_number');
        $table->dateTime('completed_at');
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('completed_repairs');
    }
};
