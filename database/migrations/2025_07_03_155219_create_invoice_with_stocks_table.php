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
        Schema::create('invoice_with_stocks', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->string('customer_name');
            $table->string('customer_phone');
            $table->string('sales_rep');
            $table->date('issue_date');
            $table->decimal('total_amount', 10, 2);
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('invoice_with_stocks');
    }
};
