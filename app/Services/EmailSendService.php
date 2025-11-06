<?php

namespace App\Services;

use App\Models\EmailAccount;
use App\Models\EmailMessage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Message;

class EmailSendService
{
    public function sendEmail(EmailAccount $account, array $data): EmailMessage
    {
        $to = is_array($data['to']) ? $data['to'] : [$data['to']];
        $cc = $data['cc'] ?? [];
        $bcc = $data['bcc'] ?? [];
        $attachments = $data['attachments'] ?? [];

        // Configure mailer with account settings
        config([
            'mail.mailers.custom_smtp' => [
                'transport' => 'smtp',
                'host' => $account->smtp_host,
                'port' => $account->smtp_port,
                'encryption' => $account->smtp_encryption,
                'username' => $account->smtp_username,
                'password' => $account->smtp_password,
                'timeout' => null,
            ],
        ]);

        // Validate account has required fields
        if (empty($account->email)) {
            throw new \Exception('Email account does not have a valid email address configured.');
        }

        // Send email
        Mail::mailer('custom_smtp')->send([], [], function (Message $message) use ($data, $to, $cc, $bcc, $account, $attachments) {
            $message->from($account->email, $account->name ?? $account->email);
            
            foreach ($to as $recipient) {
                if (is_array($recipient)) {
                    $message->to($recipient['email'], $recipient['name'] ?? '');
                } else {
                    $message->to($recipient);
                }
            }

            if (!empty($cc)) {
                foreach ($cc as $recipient) {
                    if (is_array($recipient)) {
                        $message->cc($recipient['email'], $recipient['name'] ?? '');
                    } else {
                        $message->cc($recipient);
                    }
                }
            }

            if (!empty($bcc)) {
                foreach ($bcc as $recipient) {
                    if (is_array($recipient)) {
                        $message->bcc($recipient['email'], $recipient['name'] ?? '');
                    } else {
                        $message->bcc($recipient);
                    }
                }
            }

            $message->subject($data['subject']);
            
            if (isset($data['in_reply_to'])) {
                $message->getHeaders()->addTextHeader('In-Reply-To', $data['in_reply_to']);
            }

            if (isset($data['references'])) {
                $message->getHeaders()->addTextHeader('References', $data['references']);
            }

            // Set body
            if (!empty($data['body_html'])) {
                $message->html($data['body_html']);
            } else {
                $message->text($data['body_text'] ?? '');
            }

            // Attach files
            foreach ($attachments as $attachment) {
                $message->attach($attachment['path'], [
                    'as' => $attachment['name'],
                    'mime' => $attachment['mime'],
                ]);
            }
        });

        // Save sent email to database
        $emailMessage = EmailMessage::create([
            'email_account_id' => $account->id,
            'message_id' => $this->generateMessageId($account->email),
            'in_reply_to' => $data['in_reply_to'] ?? null,
            'references' => $data['references'] ?? null,
            'folder' => 'Sent',
            'from_email' => $account->email,
            'from_name' => $account->name,
            'to' => $this->normalizeAddresses($to),
            'cc' => !empty($cc) ? $this->normalizeAddresses($cc) : null,
            'bcc' => !empty($bcc) ? $this->normalizeAddresses($bcc) : null,
            'subject' => $data['subject'],
            'body_html' => $data['body_html'] ?? null,
            'body_text' => $data['body_text'] ?? null,
            'is_read' => true,
            'has_attachments' => !empty($attachments),
            'direction' => 'outgoing',
            'sent_at' => now(),
        ]);

        return $emailMessage;
    }

    private function generateMessageId(string $email): string
    {
        $domain = substr($email, strpos($email, '@') + 1);
        return '<' . uniqid() . '@' . $domain . '>';
    }

    private function normalizeAddresses($addresses): array
    {
        $result = [];
        foreach ($addresses as $address) {
            if (is_array($address)) {
                $result[] = $address;
            } else {
                $result[] = ['email' => $address, 'name' => ''];
            }
        }
        return $result;
    }
}
