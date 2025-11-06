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
        Schema::create('email_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('email_account_id')->constrained()->onDelete('cascade');
            $table->string('message_id')->unique(); // Unique identifier from email server
            $table->string('in_reply_to')->nullable(); // For threading
            $table->text('references')->nullable(); // For threading
            
            $table->string('folder')->default('INBOX'); // INBOX, Sent, Drafts, Trash, etc.
            $table->string('from_email');
            $table->string('from_name')->nullable();
            $table->text('to'); // JSON array of recipients
            $table->text('cc')->nullable(); // JSON array
            $table->text('bcc')->nullable(); // JSON array
            
            $table->string('subject')->nullable();
            $table->longText('body_html')->nullable();
            $table->longText('body_text')->nullable();
            
            $table->boolean('is_read')->default(false);
            $table->boolean('is_starred')->default(false);
            $table->boolean('is_draft')->default(false);
            $table->boolean('has_attachments')->default(false);
            
            $table->enum('direction', ['incoming', 'outgoing'])->default('incoming');
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();
            
            $table->index(['email_account_id', 'folder', 'sent_at']);
            $table->index(['email_account_id', 'is_read']);
            $table->index('message_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_messages');
    }
};
