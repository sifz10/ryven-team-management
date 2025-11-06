<?php

namespace App\Http\Controllers;

use App\Models\ReviewCycle;
use Illuminate\Http\Request;

class ReviewCycleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cycles = ReviewCycle::with('creator')
            ->latest()
            ->paginate(15);
            
        return view('performance.review-cycles.index', compact('cycles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('performance.review-cycles.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:quarterly,annual,mid_year,probation,project_based',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'review_due_date' => 'required|date|after:end_date',
            'description' => 'nullable|string',
            'status' => 'required|in:scheduled,active,completed,cancelled',
        ]);

        $validated['created_by'] = auth()->id();

        ReviewCycle::create($validated);

        return redirect()->route('review-cycles.index')
            ->with('success', 'Review cycle created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(ReviewCycle $reviewCycle)
    {
        $reviewCycle->load(['creator', 'performanceReviews.employee', 'goals.employee']);
        
        return view('performance.review-cycles.show', compact('reviewCycle'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
