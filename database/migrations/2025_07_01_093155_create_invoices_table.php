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
        Schema::create('invoices', function (Blueprint $table) {
        $table->id();
        $table->string('invoice_number')->unique();
        $table->string('customer_name');
        $table->string('customer_phone');
        $table->string('sales_rep');
        $table->date('issue_date');
        $table->decimal('total_amount', 10, 2);
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
