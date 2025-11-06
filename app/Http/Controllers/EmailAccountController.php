<?php

namespace App\Http\Controllers;

use App\Models\EmailAccount;
use App\Services\EmailFetchService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class EmailAccountController extends Controller
{
    use AuthorizesRequests;
    public function index()
    {
        $accounts = Auth::user()->emailAccounts()->withCount('unreadMessages')->get();
        return view('email.accounts.index', compact('accounts'));
    }

    public function create()
    {
        return view('email.accounts.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:email_accounts,email',
            'protocol' => 'required|in:imap,pop3',
            'imap_host' => 'required|string',
            'imap_port' => 'required|integer',
            'imap_encryption' => 'nullable|in:ssl,tls',
            'imap_username' => 'required|string',
            'imap_password' => 'required|string',
            'smtp_host' => 'required|string',
            'smtp_port' => 'required|integer',
            'smtp_encryption' => 'nullable|in:ssl,tls',
            'smtp_username' => 'required|string',
            'smtp_password' => 'required|string',
            'is_default' => 'boolean',
        ]);

        $validated['user_id'] = Auth::id();
        
        // If this is set as default, unset other defaults
        if ($request->is_default) {
            Auth::user()->emailAccounts()->update(['is_default' => false]);
        }

        $account = EmailAccount::create($validated);

        return redirect()->route('email.accounts.index')
            ->with('success', 'Email account added successfully!');
    }

    public function edit(EmailAccount $account)
    {
        $this->authorize('update', $account);
        return view('email.accounts.edit', compact('account'));
    }

    public function update(Request $request, EmailAccount $account)
    {
        $this->authorize('update', $account);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'protocol' => 'required|in:imap,pop3',
            'imap_host' => 'required|string',
            'imap_port' => 'required|integer',
            'imap_encryption' => 'nullable|in:ssl,tls',
            'imap_username' => 'required|string',
            'imap_password' => 'nullable|string',
            'smtp_host' => 'required|string',
            'smtp_port' => 'required|integer',
            'smtp_encryption' => 'nullable|in:ssl,tls',
            'smtp_username' => 'required|string',
            'smtp_password' => 'nullable|string',
            'is_default' => 'boolean',
        ]);

        // Only update password if provided
        if (empty($validated['imap_password'])) {
            unset($validated['imap_password']);
        }
        if (empty($validated['smtp_password'])) {
            unset($validated['smtp_password']);
        }

        // If this is set as default, unset other defaults
        if ($request->is_default) {
            Auth::user()->emailAccounts()->where('id', '!=', $account->id)->update(['is_default' => false]);
        }

        $account->update($validated);

        return redirect()->route('email.accounts.index')
            ->with('success', 'Email account updated successfully!');
    }

    public function destroy(EmailAccount $account)
    {
        $this->authorize('delete', $account);
        
        $account->delete();

        return redirect()->route('email.accounts.index')
            ->with('success', 'Email account deleted successfully!');
    }

    public function sync(EmailAccount $account, EmailFetchService $fetchService)
    {
        $this->authorize('update', $account);

        try {
            $newEmailsCount = $fetchService->fetchEmails($account);
            
            $account->update(['last_synced_at' => now()]);
            
            // Return JSON for AJAX requests
            if (request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'new_emails' => $newEmailsCount,
                    'message' => "Synced successfully! Found {$newEmailsCount} new emails."
                ]);
            }
            
            return back()->with('success', "Synced successfully! Found {$newEmailsCount} new emails.");
        } catch (\Exception $e) {
            Log::error('Sync failed for account ' . $account->email, [
                'error' => $e->getMessage(),
                'account_id' => $account->id
            ]);
            
            $errorMessage = 'Failed to sync emails: ';
            
            // Provide more helpful error messages
            if (str_contains($e->getMessage(), 'Could not connect')) {
                $errorMessage .= 'Could not connect to mail server. Please check your host and port settings.';
            } elseif (str_contains($e->getMessage(), 'authentication')) {
                $errorMessage .= 'Authentication failed. Please check your username and password.';
            } elseif (str_contains($e->getMessage(), 'certificate')) {
                $errorMessage .= 'SSL certificate verification failed. This is common with self-signed certificates.';
            } else {
                $errorMessage .= $e->getMessage();
            }
            
            // Return JSON for AJAX requests
            if (request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'new_emails' => 0,
                    'message' => $errorMessage
                ], 500);
            }
            
            return back()->with('error', $errorMessage);
        }
    }

    public function toggleActive(EmailAccount $account)
    {
        $this->authorize('update', $account);
        
        $account->update(['is_active' => !$account->is_active]);

        return back()->with('success', 'Account status updated!');
    }

    public function testConnection(EmailAccount $account, EmailFetchService $fetchService)
    {
        $this->authorize('update', $account);

        $result = $fetchService->testConnection($account);
        
        if ($result['success']) {
            return back()->with('success', '✅ ' . $result['message']);
        } else {
            return back()->with('error', '❌ Connection failed: ' . $result['message']);
        }
    }
}
