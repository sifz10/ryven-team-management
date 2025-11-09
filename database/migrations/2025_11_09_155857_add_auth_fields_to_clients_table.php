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
        Schema::table('clients', function (Blueprint $table) {
            $table->string('password')->nullable()->after('email');
            $table->string('otp_code', 6)->nullable()->after('password');
            $table->timestamp('otp_expires_at')->nullable()->after('otp_code');
            $table->boolean('must_change_password')->default(false)->after('otp_expires_at');
            $table->timestamp('last_login_at')->nullable()->after('must_change_password');
            $table->rememberToken()->after('last_login_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn([
                'password',
                'otp_code',
                'otp_expires_at',
                'must_change_password',
                'last_login_at',
                'remember_token',
            ]);
        });
    }
};
