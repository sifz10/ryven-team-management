<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    // Display list of all projects
    public function index()
    {
        $projects = Project::latest()->paginate(20);
        return view('projects.index', compact('projects'));
    }

    // Show form to create new project
    public function create()
    {
        return view('projects.create');
    }

    // Store new project
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:active,completed,on-hold,cancelled',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'client_name' => 'nullable|string|max:255',
            'client_email' => 'nullable|email|max:255',
            'client_phone' => 'nullable|string|max:50',
            'client_company' => 'nullable|string|max:255',
            'client_address' => 'nullable|string',
            'budget' => 'nullable|numeric|min:0',
            'currency' => 'required|string|max:10',
            'priority' => 'required|integer|min:1|max:4',
        ]);

        Project::create($validated);

        return redirect()->route('projects.index')->with('status', 'Project created successfully!');
    }

    // Show single project
    public function show(Project $project)
    {
        $project->load(['workSubmissions.employee', 'workSubmissions.dailyChecklist']);
        return view('projects.show', compact('project'));
    }

    // Show form to edit project
    public function edit(Project $project)
    {
        return view('projects.edit', compact('project'));
    }

    // Update project
    public function update(Request $request, Project $project)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:active,completed,on-hold,cancelled',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'client_name' => 'nullable|string|max:255',
            'client_email' => 'nullable|email|max:255',
            'client_phone' => 'nullable|string|max:50',
            'client_company' => 'nullable|string|max:255',
            'client_address' => 'nullable|string',
            'budget' => 'nullable|numeric|min:0',
            'currency' => 'required|string|max:10',
            'priority' => 'required|integer|min:1|max:4',
        ]);

        $project->update($validated);

        return redirect()->route('projects.index')->with('status', 'Project updated successfully!');
    }

    // Delete project
    public function destroy(Project $project)
    {
        $project->delete();
        return redirect()->route('projects.index')->with('status', 'Project deleted successfully!');
    }
}
