<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\ClientUser;
use App\Mail\ClientWelcomeMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $clients = Client::withCount('projects')->latest()->paginate(20);
        return view('clients.index', compact('clients'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('clients.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:clients,email',
            'phone' => 'nullable|string|max:50',
            'company' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'logo' => 'nullable|image|max:2048',
            'contact_person' => 'nullable|string|max:255',
            'contact_person_phone' => 'nullable|string|max:50',
            'contact_person_email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'status' => 'required|in:active,inactive',
            'notes' => 'nullable|string',
        ]);

        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('clients/logos', 'public');
        }

        $client = Client::create($validated);

        // Generate random password for client
        $password = Str::random(12);

        // Create client user account
        ClientUser::create([
            'client_id' => $client->id,
            'email' => $client->email,
            'password' => $password,
            'must_change_password' => true,
        ]);

        // Send welcome email with credentials
        try {
            Mail::to($client->email)->send(new ClientWelcomeMail($client, $password));
        } catch (\Exception $e) {
            // Log the error but don't fail the client creation
            Log::error('Failed to send welcome email to client: ' . $e->getMessage());
        }

        return redirect()->route('clients.index')->with('status', 'Client created successfully! Welcome email sent to ' . $client->email);
    }

    /**
     * Display the specified resource.
     */
    public function show(Client $client)
    {
        $client->load(['projects' => function($query) {
            $query->latest();
        }]);
        return view('clients.show', compact('client'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Client $client)
    {
        return view('clients.edit', compact('client'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Client $client)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:clients,email,' . $client->id,
            'phone' => 'nullable|string|max:50',
            'company' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'logo' => 'nullable|image|max:2048',
            'contact_person' => 'nullable|string|max:255',
            'contact_person_phone' => 'nullable|string|max:50',
            'contact_person_email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'status' => 'required|in:active,inactive',
            'notes' => 'nullable|string',
        ]);

        if ($request->hasFile('logo')) {
            // Delete old logo
            if ($client->logo) {
                Storage::disk('public')->delete($client->logo);
            }
            $validated['logo'] = $request->file('logo')->store('clients/logos', 'public');
        }

        $client->update($validated);

        return redirect()->route('clients.index')->with('status', 'Client updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Client $client)
    {
        // Delete logo if exists
        if ($client->logo) {
            Storage::disk('public')->delete($client->logo);
        }

        $client->delete();

        return redirect()->route('clients.index')->with('status', 'Client deleted successfully!');
    }
}
