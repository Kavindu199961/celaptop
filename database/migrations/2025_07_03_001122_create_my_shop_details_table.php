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
       Schema::create('my_shop_details', function (Blueprint $table) {
            $table->id();
            $table->string('shop_name');
            $table->text('description')->nullable();
            $table->text('address');
            $table->string('hotline');
            $table->string('email');
            $table->string('logo_image')->nullable();
            $table->text('condition_1')->nullable();
            $table->text('condition_2')->nullable();
            $table->text('condition_3')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('my_shop_details');
    }
};
