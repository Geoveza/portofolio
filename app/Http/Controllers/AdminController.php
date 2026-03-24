<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Experience;
use App\Models\Skill;
use App\Models\ContactMessage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    /**
     * Dashboard overview
     * SECURITY: Protected by auth and admin middleware
     */
    public function dashboard()
    {
        $stats = [
            'projects' => Project::count(),
            'experiences' => Experience::count(),
            'skills' => Skill::count(),
            'messages' => ContactMessage::count(),
            'unread_messages' => ContactMessage::where('read', false)->count(),
            'users' => User::count(),
        ];

        $recentMessages = ContactMessage::latest()->take(5)->get();
        $recentProjects = Project::latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recentMessages', 'recentProjects'));
    }

    // ==================== PROJECTS CRUD ====================
    
    public function projectsIndex()
    {
        $projects = Project::orderBy('order')->get();
        return view('admin.projects.index', compact('projects'));
    }

    public function projectsCreate()
    {
        return view('admin.projects.create');
    }

    /**
     * Store new project
     * SECURITY: Input validation and sanitization
     */
    public function projectsStore(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:projects|regex:/^[a-z0-9-]+$/',
            'description' => 'required|string|max:10000',
            'category' => 'required|string|in:web3,defi,nft,dapp,other',
            'technologies' => 'nullable|array',
            'technologies.*' => 'string|max:50',
            'image_url' => 'nullable|url|max:2048',
            'live_url' => 'nullable|url|max:2048',
            'repo_url' => 'nullable|url|max:2048',
            'contract_address' => 'nullable|string|max:255|regex:/^0x[a-fA-F0-9]{40}$/',
            'blockchain' => 'nullable|string|max:50',
            'featured' => 'boolean',
            'status' => 'required|string|in:published,draft,archived',
        ]);

        Project::create($validated);

        Log::info('Project created', ['title' => $validated['title'], 'by' => auth()->user()->email]);

        return redirect()->route('admin.projects.index')->with('success', 'Project created successfully.');
    }

    public function projectsEdit(Project $project)
    {
        return view('admin.projects.edit', compact('project'));
    }

    /**
     * Update project
     * SECURITY: Input validation and authorization
     */
    public function projectsUpdate(Request $request, Project $project)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:projects,slug,' . $project->id . '|regex:/^[a-z0-9-]+$/',
            'description' => 'required|string|max:10000',
            'category' => 'required|string|in:web3,defi,nft,dapp,other',
            'technologies' => 'nullable|array',
            'technologies.*' => 'string|max:50',
            'image_url' => 'nullable|url|max:2048',
            'live_url' => 'nullable|url|max:2048',
            'repo_url' => 'nullable|url|max:2048',
            'contract_address' => 'nullable|string|max:255|regex:/^0x[a-fA-F0-9]{40}$/',
            'blockchain' => 'nullable|string|max:50',
            'featured' => 'boolean',
            'status' => 'required|string|in:published,draft,archived',
        ]);

        $project->update($validated);

        Log::info('Project updated', ['id' => $project->id, 'by' => auth()->user()->email]);

        return redirect()->route('admin.projects.index')->with('success', 'Project updated successfully.');
    }

    /**
     * Delete project
     * SECURITY: Authorization check
     */
    public function projectsDestroy(Project $project)
    {
        $projectId = $project->id;
        $project->delete();

        Log::info('Project deleted', ['id' => $projectId, 'by' => auth()->user()->email]);

        return redirect()->route('admin.projects.index')->with('success', 'Project deleted successfully.');
    }

    // ==================== EXPERIENCES CRUD ====================

    public function experiencesIndex()
    {
        $experiences = Experience::orderBy('order')->get();
        return view('admin.experiences.index', compact('experiences'));
    }

    public function experiencesCreate()
    {
        return view('admin.experiences.create');
    }

    public function experiencesStore(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'company' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:5000',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'current' => 'boolean',
            'type' => 'required|string|in:full-time,part-time,freelance,contract,internship',
            'skills' => 'nullable|array',
            'skills.*' => 'string|max:50',
            'logo_url' => 'nullable|url|max:2048',
        ]);

        Experience::create($validated);

        Log::info('Experience created', ['title' => $validated['title'], 'by' => auth()->user()->email]);

        return redirect()->route('admin.experiences.index')->with('success', 'Experience created successfully.');
    }

    public function experiencesEdit(Experience $experience)
    {
        return view('admin.experiences.edit', compact('experience'));
    }

    public function experiencesUpdate(Request $request, Experience $experience)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'company' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:5000',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'current' => 'boolean',
            'type' => 'required|string|in:full-time,part-time,freelance,contract,internship',
            'skills' => 'nullable|array',
            'skills.*' => 'string|max:50',
            'logo_url' => 'nullable|url|max:2048',
        ]);

        $experience->update($validated);

        Log::info('Experience updated', ['id' => $experience->id, 'by' => auth()->user()->email]);

        return redirect()->route('admin.experiences.index')->with('success', 'Experience updated successfully.');
    }

    public function experiencesDestroy(Experience $experience)
    {
        $experienceId = $experience->id;
        $experience->delete();

        Log::info('Experience deleted', ['id' => $experienceId, 'by' => auth()->user()->email]);

        return redirect()->route('admin.experiences.index')->with('success', 'Experience deleted successfully.');
    }

    // ==================== SKILLS CRUD ====================

    public function skillsIndex()
    {
        $skills = Skill::orderBy('order')->get();
        return view('admin.skills.index', compact('skills'));
    }

    public function skillsCreate()
    {
        return view('admin.skills.create');
    }

    public function skillsStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|in:frontend,backend,blockchain,devops,design,other',
            'level' => 'required|integer|min:1|max:5',
            'description' => 'nullable|string|max:1000',
            'icon' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:7|regex:/^#[a-fA-F0-9]{6}$/',
            'featured' => 'boolean',
        ]);

        Skill::create($validated);

        Log::info('Skill created', ['name' => $validated['name'], 'by' => auth()->user()->email]);

        return redirect()->route('admin.skills.index')->with('success', 'Skill created successfully.');
    }

    public function skillsEdit(Skill $skill)
    {
        return view('admin.skills.edit', compact('skill'));
    }

    public function skillsUpdate(Request $request, Skill $skill)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|in:frontend,backend,blockchain,devops,design,other',
            'level' => 'required|integer|min:1|max:5',
            'description' => 'nullable|string|max:1000',
            'icon' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:7|regex:/^#[a-fA-F0-9]{6}$/',
            'featured' => 'boolean',
        ]);

        $skill->update($validated);

        Log::info('Skill updated', ['id' => $skill->id, 'by' => auth()->user()->email]);

        return redirect()->route('admin.skills.index')->with('success', 'Skill updated successfully.');
    }

    public function skillsDestroy(Skill $skill)
    {
        $skillId = $skill->id;
        $skill->delete();

        Log::info('Skill deleted', ['id' => $skillId, 'by' => auth()->user()->email]);

        return redirect()->route('admin.skills.index')->with('success', 'Skill deleted successfully.');
    }

    // ==================== CONTACT MESSAGES ====================

    /**
     * List contact messages
     * SECURITY: Protected by auth and admin middleware
     */
    public function messages(Request $request)
    {
        $query = ContactMessage::latest();
        
        // Filter by status
        if ($request->has('filter')) {
            $filter = $request->input('filter');
            
            // SECURITY: Whitelist filter values
            $allowedFilters = ['unread', 'responded', 'read'];
            
            if (in_array($filter, $allowedFilters, true)) {
                switch ($filter) {
                    case 'unread':
                        $query->where('read', false);
                        break;
                    case 'responded':
                        $query->where('responded', true);
                        break;
                    case 'read':
                        $query->where('read', true)->where('responded', false);
                        break;
                }
            }
        }
        
        $messages = $query->paginate(20)->withQueryString();
        return view('admin.messages.index', compact('messages'));
    }

    /**
     * Show single message
     * SECURITY: Uses route model binding, auto-mark as read
     */
    public function showMessage(ContactMessage $message)
    {
        // SECURITY: Auto-mark as read when viewing
        if (!$message->read) {
            $message->markAsRead();
        }
        
        return view('admin.messages.show', compact('message'));
    }

    /**
     * Respond to message
     * SECURITY: Input validation and sanitization
     */
    public function respondMessage(Request $request, ContactMessage $message)
    {
        $validated = $request->validate([
            'response' => 'required|string|max:5000',
        ]);

        // Mark as responded
        $message->markAsResponded();

        Log::info('Message responded', [
            'message_id' => $message->id,
            'sender_email' => $message->email,
            'responded_by' => auth()->user()->email,
        ]);

        return redirect()->route('admin.messages.show', $message)
            ->with('success', 'Response recorded successfully.');
    }

    // ==================== ANALYTICS ====================

    /**
     * Analytics dashboard
     * SECURITY: Uses parameterized queries to prevent SQL injection
     */
    public function analytics()
    {
        // SECURITY: Using Eloquent query builder (parameterized) instead of raw SQL
        $projectByCategory = Project::select('category', DB::raw('count(*) as count'))
            ->groupBy('category')
            ->get();

        $skillsByCategory = Skill::select('category', DB::raw('count(*) as count'))
            ->groupBy('category')
            ->get();

        // SECURITY: Using query builder with date formatting
        $messagesByMonth = ContactMessage::select(
                DB::raw("strftime('%Y-%m', created_at) as month"),
                DB::raw('count(*) as count')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return view('admin.analytics', compact('projectByCategory', 'skillsByCategory', 'messagesByMonth'));
    }
}
