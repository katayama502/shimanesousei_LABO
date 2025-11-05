<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Project;
use App\Models\Sponsorship;
use App\Models\Tag;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if ($user->role === 'club') {
            return redirect()->route('dashboard.projects.index');
        }
        if ($user->role === 'company') {
            $organizationIds = $user->organizations()->pluck('organizations.id');
            $sponsorships = Sponsorship::with(['project.organization', 'tier'])
                ->whereIn('company_org_id', $organizationIds)
                ->latest()
                ->paginate(15);

            return view('dashboard.company.index', compact('sponsorships'));
        }

        return redirect()->route('admin.dashboard');
    }

    public function projects()
    {
        Gate::authorize('viewAny', Project::class);

        $projects = Project::whereHas('organization.users', function ($query) {
            $query->where('users.id', Auth::id());
        })
            ->withCount('sponsorships')
            ->orderByDesc('updated_at')
            ->paginate(15);

        return view('dashboard.projects.index', compact('projects'));
    }

    public function createProject()
    {
        Gate::authorize('create', Project::class);

        $categories = [
            'sport' => Category::where('type', 'sport')->get(),
            'culture' => Category::where('type', 'culture')->get(),
        ];
        $tags = Tag::all();

        return view('dashboard.projects.create', compact('categories', 'tags'));
    }

    public function editProject(Project $project)
    {
        $this->authorize('update', $project);

        $project->load(['media', 'tiers', 'updates', 'tags']);
        $categories = [
            'sport' => Category::where('type', 'sport')->get(),
            'culture' => Category::where('type', 'culture')->get(),
        ];
        $tags = Tag::all();

        return view('dashboard.projects.edit', compact('project', 'categories', 'tags'));
    }
}
