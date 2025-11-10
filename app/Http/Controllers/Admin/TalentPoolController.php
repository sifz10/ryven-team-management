<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TalentPool;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TalentPoolController extends Controller
{
    /**
     * Display a listing of the talent pool.
     */
    public function index(Request $request)
    {
        $query = TalentPool::with(['application.jobPost', 'addedBy']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by experience level
        if ($request->filled('experience_level')) {
            $query->where('experience_level', $request->experience_level);
        }

        // Search by name or email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by skills
        if ($request->filled('skill')) {
            $query->whereJsonContains('skills', $request->skill);
        }

        $talents = $query->orderBy('created_at', 'desc')->paginate(20);

        // Get stats for the dashboard
        $stats = [
            'total' => TalentPool::count(),
            'potential' => TalentPool::where('status', 'potential')->count(),
            'contacted' => TalentPool::where('status', 'contacted')->count(),
            'interested' => TalentPool::where('status', 'interested')->count(),
            'hired' => TalentPool::where('status', 'hired')->count(),
        ];

        return view('admin.talent-pool.index', compact('talents', 'stats'));
    }

    /**
     * Display the specified talent.
     */
    public function show(TalentPool $talentPool)
    {
        $talentPool->load(['application.jobPost', 'addedBy']);

        return view('admin.talent-pool.show', compact('talentPool'));
    }

    /**
     * Update the specified talent in storage.
     */
    public function update(Request $request, TalentPool $talentPool)
    {
        $validated = $request->validate([
            'status' => 'required|in:potential,contacted,interested,hired,not_interested',
            'notes' => 'nullable|string',
            'skills' => 'nullable|array',
            'experience_level' => 'nullable|in:entry,junior,mid,senior,lead',
        ]);

        if ($request->filled('contacted')) {
            $validated['last_contacted_at'] = now();
        }

        $talentPool->update($validated);

        return redirect()
            ->route('admin.talent-pool.show', $talentPool)
            ->with('success', 'Talent pool entry updated successfully.');
    }

    /**
     * Remove the specified talent from storage.
     */
    public function destroy(TalentPool $talentPool)
    {
        $talentPool->delete();

        return redirect()
            ->route('admin.talent-pool.index')
            ->with('success', 'Talent removed from pool successfully.');
    }

    /**
     * Download the resume of a talent.
     */
    public function downloadResume(TalentPool $talentPool)
    {
        if (!$talentPool->resume_path || !Storage::exists($talentPool->resume_path)) {
            abort(404, 'Resume not found.');
        }

        return Storage::download($talentPool->resume_path, $talentPool->full_name . '_Resume.pdf');
    }
}
