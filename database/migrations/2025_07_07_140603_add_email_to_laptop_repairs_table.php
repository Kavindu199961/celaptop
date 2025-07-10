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
            Schema::table('laptop_repairs', function (Blueprint $table) {
                $table->string('email')->nullable()->after('contact');
            });
        }

        public function down()
        {
            Schema::table('laptop_repairs', function (Blueprint $table) {
                $table->dropColumn('email');
            });
        }

};
