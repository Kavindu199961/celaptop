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
    Schema::table('users', function (Blueprint $table) {
        $table->string('smtp_host')->nullable();
        $table->integer('smtp_port')->nullable();
        $table->string('smtp_encryption')->nullable(); // 'tls' or 'ssl'
        $table->string('email_username')->nullable(); // SMTP username (could be same as email)
        $table->text('email_password')->nullable(); // Encrypted SMTP password
    });
}

public function down()
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn(['smtp_host', 'smtp_port', 'smtp_encryption', 'email_username', 'email_password']);
    });
}
};
