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
        Schema::create('invoice_with_stock_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_with_stock_id')->constrained()->onDelete('cascade');
            $table->foreignId('stock_id')->constrained()->onDelete('cascade');
            $table->string('description');
            $table->string('warranty')->nullable();
            $table->integer('quantity');
            $table->decimal('unit_price', 10, 2);
            $table->decimal('amount', 10, 2);
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('invoice_with_stock_items');
    }
};
