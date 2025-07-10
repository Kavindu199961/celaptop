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
        Schema::create('repair_items', function (Blueprint $table) {
            $table->id();
            $table->string('item_number'); // Format: SH-XX-0001
            $table->foreignId('shop_id')->constrained('shop_names')->onDelete('cascade');
            $table->string('item_name');
            $table->decimal('price', 10, 2)->nullable();
            $table->text('description')->nullable();
            $table->string('serial_number')->nullable();
            $table->date('date')->default(now());
            $table->enum('status', ['pending', 'in_progress', 'completed', 'canceled'])->default('pending');
            $table->timestamps();
            
            // Optional: Add index for better performance
            $table->index('shop_id');
            $table->index('item_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('repair_items', function (Blueprint $table) {
            $table->dropForeign(['shop_id']);
        });
        Schema::dropIfExists('repair_items');
    }
};