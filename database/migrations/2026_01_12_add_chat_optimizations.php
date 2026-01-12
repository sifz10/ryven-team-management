<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add missing indexes for chat message queries optimization
     */
    public function up(): void
    {
        Schema::table('chat_messages', function (Blueprint $table) {
            // Index for sender_type filtering (skip if already exists)
            if (!$this->indexExists('chat_messages', 'sender_type')) {
                $table->index('sender_type');
            }
            
            // Composite index for common queries: conversation + sender type + read status
            if (!$this->indexExists('chat_messages', ['chat_conversation_id', 'sender_type', 'read_at'])) {
                $table->index(['chat_conversation_id', 'sender_type', 'read_at']);
            }
        });

        // Ensure chat_message_attachments table has proper indexes
        if (Schema::hasTable('chat_message_attachments')) {
            Schema::table('chat_message_attachments', function (Blueprint $table) {
                // Primary index for fetching attachments by message
                if (!$this->indexExists('chat_message_attachments', 'chat_message_id')) {
                    $table->index('chat_message_id');
                }
                
                // Compound index for pagination or bulk operations
                if (!$this->indexExists('chat_message_attachments', ['chat_message_id', 'created_at'])) {
                    $table->index(['chat_message_id', 'created_at']);
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('chat_messages', function (Blueprint $table) {
            if ($this->indexExists('chat_messages', 'sender_type')) {
                $table->dropIndex(['sender_type']);
            }
            if ($this->indexExists('chat_messages', ['chat_conversation_id', 'sender_type', 'read_at'])) {
                $table->dropIndex(['chat_conversation_id', 'sender_type', 'read_at']);
            }
        });

        if (Schema::hasTable('chat_message_attachments')) {
            Schema::table('chat_message_attachments', function (Blueprint $table) {
                if ($this->indexExists('chat_message_attachments', 'chat_message_id')) {
                    $table->dropIndex(['chat_message_id']);
                }
                if ($this->indexExists('chat_message_attachments', ['chat_message_id', 'created_at'])) {
                    $table->dropIndex(['chat_message_id', 'created_at']);
                }
            });
        }
    }

    /**
     * Check if an index exists on a table
     */
    protected function indexExists(string $table, $columns): bool
    {
        $indexColumns = is_array($columns) ? $columns : [$columns];
        $sql = "SELECT * FROM information_schema.STATISTICS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME IN (" . str_repeat('?,', count($indexColumns) - 1) . "?)";
        $result = \DB::select($sql, array_merge([$table], $indexColumns));
        return count($result) > 0;
    }
};
