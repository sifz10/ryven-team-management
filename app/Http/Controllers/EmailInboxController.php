<?php

namespace App\Http\Controllers;

use App\Models\EmailAccount;
use App\Models\EmailMessage;
use App\Services\EmailSendService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class EmailInboxController extends Controller
{
    use AuthorizesRequests;
    public function index(Request $request)
    {
        $accounts = Auth::user()->emailAccounts()->with('unreadMessages')->get();
        
        $accountId = $request->get('account', $accounts->first()?->id);
        $folder = $request->get('folder', 'INBOX');
        $filter = $request->get('filter'); // unread, starred
        
        $query = EmailMessage::where('email_account_id', $accountId)
            ->with('attachments');

        // Filter by folder, but exclude trash unless explicitly viewing trash
        if ($folder === 'Trash') {
            $query->where('folder', 'Trash');
        } else {
            $query->where('folder', $folder)
                  ->where('folder', '!=', 'Trash');
        }

        if ($filter === 'unread') {
            $query->where('is_read', false);
        } elseif ($filter === 'starred') {
            $query->where('is_starred', true);
        }

        // Search
        if ($search = $request->get('search')) {
            $query->where(function($q) use ($search) {
                $q->where('subject', 'like', "%{$search}%")
                  ->orWhere('from_email', 'like', "%{$search}%")
                  ->orWhere('body_text', 'like', "%{$search}%");
            });
        }

        $perPage = $request->get('per_page', 25); // 25, 50, or 100 emails per page
        $messages = $query->orderBy('sent_at', 'desc')->paginate($perPage)->appends($request->except('page'));

        $currentAccount = EmailAccount::find($accountId);

        return view('email.inbox.index', compact('messages', 'accounts', 'currentAccount', 'folder', 'filter'));
    }

    public function show(EmailMessage $message)
    {
        // Mark as read
        if (!$message->is_read) {
            $message->markAsRead();
        }

        return view('email.inbox.show', compact('message'));
    }

    public function compose()
    {
        $accounts = Auth::user()->emailAccounts()->where('is_active', true)->get();
        $defaultAccount = $accounts->where('is_default', true)->first() ?? $accounts->first();
        
        return view('email.inbox.compose', compact('accounts', 'defaultAccount'));
    }

    public function send(Request $request, EmailSendService $sendService)
    {
        $validated = $request->validate([
            'account_id' => 'required|exists:email_accounts,id',
            'to' => 'required|string',
            'cc' => 'nullable|string',
            'bcc' => 'nullable|string',
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
            'attachments.*' => 'nullable|file|max:10240', // 10MB max
        ]);

        $account = EmailAccount::findOrFail($validated['account_id']);
        $this->authorize('update', $account);

        // Parse recipients
        $to = array_map('trim', explode(',', $validated['to']));
        $cc = !empty($validated['cc']) ? array_map('trim', explode(',', $validated['cc'])) : [];
        $bcc = !empty($validated['bcc']) ? array_map('trim', explode(',', $validated['bcc'])) : [];

        // Handle attachments
        $attachments = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $attachments[] = [
                    'path' => $file->getRealPath(),
                    'name' => $file->getClientOriginalName(),
                    'mime' => $file->getMimeType(),
                ];
            }
        }

        try {
            $sendService->sendEmail($account, [
                'to' => $to,
                'cc' => $cc,
                'bcc' => $bcc,
                'subject' => $validated['subject'],
                'body_html' => $validated['body'],
                'body_text' => strip_tags($validated['body']),
                'attachments' => $attachments,
            ]);

            // Trigger immediate sync to catch quick replies
            \App\Jobs\FetchEmails::dispatch()->onQueue('default');

            return redirect()->route('email.inbox.index', ['folder' => 'Sent'])
                ->with('success', 'Email sent successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to send email: ' . $e->getMessage())->withInput();
        }
    }

    public function reply(EmailMessage $message)
    {
        $accounts = Auth::user()->emailAccounts()->where('is_active', true)->get();
        $account = $message->account;
        
        return view('email.inbox.reply', compact('message', 'accounts', 'account'));
    }

    public function sendReply(Request $request, EmailMessage $originalMessage, EmailSendService $sendService)
    {
        $validated = $request->validate([
            'account_id' => 'required|exists:email_accounts,id',
            'body' => 'required|string',
            'attachments.*' => 'nullable|file|max:10240',
        ]);

        $account = EmailAccount::findOrFail($validated['account_id']);
        $this->authorize('update', $account);

        // Ensure account has required fields
        if (empty($account->email)) {
            return back()->with('error', 'Email account configuration is incomplete.')->withInput();
        }

        // Determine recipients based on whether this is a sent or received email
        $recipients = [];
        if ($originalMessage->direction === 'outgoing' || $originalMessage->folder === 'Sent') {
            // For sent emails, reply to the original recipients
            if (is_array($originalMessage->to) && !empty($originalMessage->to)) {
                foreach ($originalMessage->to as $recipient) {
                    if (is_array($recipient) && isset($recipient['email']) && !empty($recipient['email'])) {
                        $recipients[] = $recipient['email'];
                    } elseif (is_string($recipient) && !empty($recipient)) {
                        $recipients[] = $recipient;
                    }
                }
            }
        } else {
            // For received emails, reply to the sender
            if (!empty($originalMessage->from_email)) {
                $recipients = [$originalMessage->from_email];
            }
        }

        // Validate we have recipients
        if (empty($recipients)) {
            return back()->with('error', 'Cannot determine reply recipient. Original email may be missing sender/recipient information.')->withInput();
        }

        // Handle attachments
        $attachments = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $attachments[] = [
                    'path' => $file->getRealPath(),
                    'name' => $file->getClientOriginalName(),
                    'mime' => $file->getMimeType(),
                ];
            }
        }

        try {
            $sendService->sendEmail($account, [
                'to' => $recipients,
                'subject' => 'Re: ' . $originalMessage->subject,
                'body_html' => $validated['body'],
                'body_text' => strip_tags($validated['body']),
                'in_reply_to' => $originalMessage->message_id,
                'references' => $originalMessage->references 
                    ? $originalMessage->references . ' ' . $originalMessage->message_id
                    : $originalMessage->message_id,
                'attachments' => $attachments,
            ]);

            // Trigger immediate sync to catch quick replies
            \App\Jobs\FetchEmails::dispatch()->onQueue('default');

            return redirect()->route('email.inbox.show', $originalMessage)
                ->with('success', 'Reply sent successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to send reply: ' . $e->getMessage())->withInput();
        }
    }

    public function markAsRead(EmailMessage $message)
    {
        $message->markAsRead();
        return back();
    }

    public function markAsUnread(EmailMessage $message)
    {
        $message->markAsUnread();
        return back();
    }

    public function toggleStar(EmailMessage $message)
    {
        $message->toggleStar();
        return back();
    }

    public function downloadAttachment($id)
    {
        $attachment = \App\Models\EmailAttachment::findOrFail($id);
        
        return Storage::disk('public')->download($attachment->storage_path, $attachment->filename);
    }

    public function unreadCount()
    {
        $userId = Auth::user()->id;
        
        $count = EmailMessage::whereHas('emailAccount', function($query) use ($userId) {
            $query->where('user_id', $userId);
        })
        ->where('is_read', false)
        ->where('folder', '!=', 'Trash')
        ->count();

        return response()->json(['count' => $count]);
    }

    public function trash(EmailMessage $message)
    {
        $this->authorize('update', $message->emailAccount);
        
        $message->update(['folder' => 'Trash']);
        
        return back()->with('success', 'Email moved to trash!');
    }

    public function destroy(EmailMessage $message)
    {
        $this->authorize('delete', $message->emailAccount);
        
        $message->delete();
        
        return back()->with('success', 'Email permanently deleted!');
    }

    public function restore(EmailMessage $message)
    {
        $this->authorize('update', $message->emailAccount);
        
        $message->update(['folder' => 'INBOX']);
        
        return back()->with('success', 'Email restored!');
    }

    public function bulkAction(Request $request)
    {
        $validated = $request->validate([
            'action' => 'required|in:trash,delete,restore',
            'ids' => 'required|json',
        ]);

        $ids = json_decode($validated['ids']);
        $action = $validated['action'];

        // Get messages that belong to the user
        $messages = EmailMessage::whereIn('id', $ids)
            ->whereHas('emailAccount', function($query) {
                $query->where('user_id', Auth::user()->id);
            })
            ->get();

        if ($messages->isEmpty()) {
            return back()->with('error', 'No emails found or you do not have permission.');
        }

        $count = 0;
        foreach ($messages as $message) {
            try {
                $this->authorize('update', $message->emailAccount);
                
                switch ($action) {
                    case 'trash':
                        $message->update(['folder' => 'Trash']);
                        $count++;
                        break;
                    case 'delete':
                        $message->delete();
                        $count++;
                        break;
                    case 'restore':
                        $message->update(['folder' => 'INBOX']);
                        $count++;
                        break;
                }
            } catch (\Exception $e) {
                continue;
            }
        }

        $actionText = match($action) {
            'trash' => 'moved to trash',
            'delete' => 'permanently deleted',
            'restore' => 'restored',
        };

        return back()->with('success', "{$count} email(s) {$actionText}!");
    }
}
