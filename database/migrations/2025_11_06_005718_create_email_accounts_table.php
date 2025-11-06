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
        Schema::create('email_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name'); // Display name for the account
            $table->string('email')->unique();
            $table->string('protocol')->default('imap'); // imap, pop3, smtp
            
            // Incoming mail settings (IMAP/POP3)
            $table->string('imap_host')->nullable();
            $table->integer('imap_port')->nullable();
            $table->string('imap_encryption')->nullable(); // ssl, tls, null
            $table->string('imap_username')->nullable();
            $table->text('imap_password')->nullable(); // encrypted
            
            // Outgoing mail settings (SMTP)
            $table->string('smtp_host')->nullable();
            $table->integer('smtp_port')->nullable();
            $table->string('smtp_encryption')->nullable(); // ssl, tls, null
            $table->string('smtp_username')->nullable();
            $table->text('smtp_password')->nullable(); // encrypted
            
            $table->boolean('is_default')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_synced_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_accounts');
    }
};
