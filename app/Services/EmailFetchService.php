<?php

namespace App\Services;

use App\Models\EmailAccount;
use App\Models\EmailMessage;
use App\Models\EmailAttachment;
use Webklex\PHPIMAP\ClientManager;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class EmailFetchService
{
    public function fetchEmails(EmailAccount $account, int $limit = 20): int
    {
        try {
            // Set a shorter timeout to prevent hanging
            set_time_limit(30);
            
            // Use ClientManager to create a client with custom config
            $cm = new ClientManager();
            
            $client = $cm->make([
                'host' => $account->imap_host,
                'port' => $account->imap_port,
                'encryption' => $account->imap_encryption ?: 'ssl',
                'validate_cert' => false,
                'username' => $account->imap_username,
                'password' => $account->imap_password,
                'protocol' => $account->protocol ?: 'imap',
                'timeout' => 5, // 5 second connection timeout
                'fetch_timeout' => 5, // 5 second fetch timeout
            ]);
            
            Log::info('Attempting to connect to IMAP server', [
                'host' => $account->imap_host,
                'port' => $account->imap_port,
                'encryption' => $account->imap_encryption,
            ]);
            
            $client->connect();

            $newEmailsCount = 0;

            // Fetch from both INBOX and Sent folders
            $foldersToFetch = ['INBOX', 'Sent', 'INBOX.Sent', '[Gmail]/Sent Mail'];
            
            foreach ($foldersToFetch as $folderName) {
                try {
                    $folder = $client->getFolder($folderName);
                    if (!$folder) {
                        continue;
                    }
                    
                    // Fetch only recent unseen messages first, then all messages up to limit
                    $messages = $folder->messages()->unseen()->limit($limit)->get();
                    
                    // If no unseen, get recent messages
                    if ($messages->isEmpty()) {
                        $messages = $folder->messages()->all()->limit($limit)->get();
                    }
                    
                    foreach ($messages as $message) {
                $messageId = $message->getMessageId();
                
                // Skip if already exists
                if (EmailMessage::where('message_id', $messageId)->exists()) {
                    continue;
                }

                // Parse recipients
                $to = $this->parseAddresses($message->getTo());
                $cc = $this->parseAddresses($message->getCc());
                $bcc = $this->parseAddresses($message->getBcc());

                // Determine direction based on folder
                $direction = (str_contains(strtolower($folderName), 'sent')) ? 'outgoing' : 'incoming';
                
                // Create email message
                $emailMessage = EmailMessage::create([
                    'email_account_id' => $account->id,
                    'message_id' => $messageId,
                    'in_reply_to' => $message->getInReplyTo(),
                    'references' => $message->getReferences(),
                    'folder' => $folderName,
                    'from_email' => $message->getFrom()[0]->mail ?? '',
                    'from_name' => $message->getFrom()[0]->name ?? '',
                    'to' => $to,
                    'cc' => $cc,
                    'bcc' => $bcc,
                    'subject' => $message->getSubject(),
                    'body_html' => $message->getHTMLBody(),
                    'body_text' => $message->getTextBody(),
                    'is_read' => $message->getFlags()->has('seen'),
                    'is_starred' => $message->getFlags()->has('flagged'),
                    'has_attachments' => $message->hasAttachments(),
                    'direction' => $direction,
                    'sent_at' => $message->getDate(),
                ]);

                // Only broadcast for incoming emails, not sent
                if ($direction === 'incoming') {
                    broadcast(new \App\Events\NewEmailReceived($emailMessage, $account->user_id))->toOthers();
                }

                // Handle attachments
                if ($message->hasAttachments()) {
                    $attachments = $message->getAttachments();
                    foreach ($attachments as $attachment) {
                        $filename = $attachment->getName();
                        $content = $attachment->getContent();
                        
                        // Store attachment
                        $path = 'email-attachments/' . $account->id . '/' . Str::uuid() . '_' . $filename;
                        Storage::disk('public')->put($path, $content);

                        EmailAttachment::create([
                            'email_message_id' => $emailMessage->id,
                            'filename' => $filename,
                            'mime_type' => $attachment->getMimeType(),
                            'size' => strlen($content),
                            'storage_path' => $path,
                            'content_id' => $attachment->getId(),
                        ]);
                    }
                }

                    $newEmailsCount++;
                }
                
                } catch (\Exception $e) {
                    Log::info("Folder {$folderName} not found or inaccessible, skipping...");
                    continue;
                }
            }

            // Update last synced time
            $account->update(['last_synced_at' => now()]);

            return $newEmailsCount;

        } catch (\Exception $e) {
            Log::error('Email fetch error for account ' . $account->email . ': ' . $e->getMessage(), [
                'account_id' => $account->id,
                'exception' => get_class($e),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    private function parseAddresses($addresses): array
    {
        if (!$addresses) {
            return [];
        }

        $result = [];
        foreach ($addresses as $address) {
            $result[] = [
                'email' => $address->mail ?? '',
                'name' => $address->name ?? '',
            ];
        }

        return $result;
    }

    public function testConnection(EmailAccount $account): array
    {
        try {
            set_time_limit(30);
            
            $cm = new ClientManager();
            
            $client = $cm->make([
                'host' => $account->imap_host,
                'port' => $account->imap_port,
                'encryption' => $account->imap_encryption ?: 'ssl',
                'validate_cert' => false,
                'username' => $account->imap_username,
                'password' => $account->imap_password,
                'protocol' => $account->protocol ?: 'imap',
                'timeout' => 10,
            ]);
            
            $client->connect();
            $client->disconnect();
            
            return ['success' => true, 'message' => 'Connection successful!'];
        } catch (\Exception $e) {
            Log::error('Connection test failed for ' . $account->email, [
                'error' => $e->getMessage()
            ]);
            
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
}
