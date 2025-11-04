<?php

namespace App\Http\Controllers;

use App\Models\PersonalNote;
use App\Models\SavedEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class PersonalNoteController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = $request->user()->personalNotes()->with('recipients');

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Search
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('content', 'like', '%' . $request->search . '%');
            });
        }

        $notes = $query->latest()->paginate(20);

        return view('notes.index', compact('notes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('notes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:website_link,password,backup_code,text,file',
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'url' => 'nullable|url|max:500',
            'file' => 'nullable|file|max:10240', // 10MB
            'reminder_time' => 'nullable|date|after:now',
            'reminder_emails' => 'nullable|array',
            'reminder_emails.*' => 'email',
        ]);

        $note = $request->user()->personalNotes()->create([
            'type' => $validated['type'],
            'title' => $validated['title'],
            'content' => $validated['content'] ?? null,
            'url' => $validated['url'] ?? null,
            'reminder_time' => $validated['reminder_time'] ?? null,
        ]);

        // Handle file upload
        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('personal-notes', 'public');
            $note->update(['file_path' => $path]);
        }

        // Store reminder emails
        if (!empty($validated['reminder_emails'])) {
            foreach ($validated['reminder_emails'] as $email) {
                $note->recipients()->create(['email' => $email]);
                
                // Save email for autocomplete
                $this->saveEmailForUser($request->user()->id, $email);
            }
        }

        return redirect()->route('notes.index')
            ->with('success', 'Note created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(PersonalNote $note)
    {
        $this->authorize('view', $note);
        
        $note->load('recipients');
        
        return view('notes.show', compact('note'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PersonalNote $note)
    {
        $this->authorize('update', $note);
        
        $note->load('recipients');
        
        return view('notes.edit', compact('note'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PersonalNote $note)
    {
        $this->authorize('update', $note);

        $validated = $request->validate([
            'type' => 'required|in:website_link,password,backup_code,text,file',
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'url' => 'nullable|url|max:500',
            'file' => 'nullable|file|max:10240',
            'reminder_time' => 'nullable|date',
            'reminder_emails' => 'nullable|array',
            'reminder_emails.*' => 'email',
        ]);

        $note->update([
            'type' => $validated['type'],
            'title' => $validated['title'],
            'content' => $validated['content'] ?? null,
            'url' => $validated['url'] ?? null,
            'reminder_time' => $validated['reminder_time'] ?? null,
            'reminder_sent' => false, // Reset if reminder time changed
        ]);

        // Handle file upload
        if ($request->hasFile('file')) {
            // Delete old file if exists
            if ($note->file_path) {
                Storage::disk('public')->delete($note->file_path);
            }
            $path = $request->file('file')->store('personal-notes', 'public');
            $note->update(['file_path' => $path]);
        }

        // Update reminder emails
        $note->recipients()->delete();
        if (!empty($validated['reminder_emails'])) {
            foreach ($validated['reminder_emails'] as $email) {
                $note->recipients()->create(['email' => $email]);
                
                // Save email for autocomplete
                $this->saveEmailForUser($request->user()->id, $email);
            }
        }

        return redirect()->route('notes.index')
            ->with('success', 'Note updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PersonalNote $note)
    {
        $this->authorize('delete', $note);

        // Delete file if exists
        if ($note->file_path) {
            Storage::disk('public')->delete($note->file_path);
        }

        $note->delete();

        return redirect()->route('notes.index')
            ->with('success', 'Note deleted successfully!');
    }

    /**
     * Search saved emails for autocomplete
     */
    public function searchEmails(Request $request)
    {
        $search = $request->get('q', '');
        
        $emails = SavedEmail::where('user_id', $request->user()->id)
            ->where('email', 'like', '%' . $search . '%')
            ->orderByDesc('usage_count')
            ->orderByDesc('last_used_at')
            ->limit(10)
            ->get(['email', 'name']);

        return response()->json($emails);
    }

    /**
     * Save email for autocomplete
     */
    protected function saveEmailForUser($userId, $email)
    {
        $savedEmail = SavedEmail::firstOrCreate(
            ['user_id' => $userId, 'email' => $email],
            ['usage_count' => 0]
        );
        
        $savedEmail->incrementUsage();
    }
}
