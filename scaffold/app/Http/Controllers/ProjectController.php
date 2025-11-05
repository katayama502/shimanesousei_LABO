<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectRequest;
use App\Http\Requests\ProjectUpdateRequest;
use App\Models\Category;
use App\Models\Organization;
use App\Models\Project;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::published()
            ->with(['organization', 'tiers', 'media'])
            ->latest('created_at')
            ->take(6)
            ->get();

        $featured = Project::published()
            ->with(['organization'])
            ->orderByDesc('current_amount')
            ->take(3)
            ->get();

        return view('public.projects.index', [
            'projects' => $projects,
            'featured' => $featured,
        ]);
    }

    public function search(Request $request)
    {
        $validated = $request->validate([
            'q' => ['nullable', 'string', 'max:120'],
            'prefecture' => ['nullable', 'string', 'max:120'],
            'category' => ['nullable', 'string', 'max:120'],
            'min_amount' => ['nullable', 'integer', 'min:0'],
            'deadline' => ['nullable', 'date'],
            'type' => ['nullable', 'in:sport,culture'],
        ]);

        $projects = Project::published()
            ->with(['organization', 'tags'])
            ->when($validated['q'] ?? null, function ($query, $q) {
                $sanitized = str_replace(['%', '_'], ['\\%', '\\_'], $q);
                $like = "%{$sanitized}%";
                $query->where(function ($inner) use ($like) {
                    $inner->where('title', 'like', $like)
                        ->orWhere('summary', 'like', $like)
                        ->orWhere('description', 'like', $like);
                });
            })
            ->when($validated['prefecture'] ?? null, fn ($query, $prefecture) => $query->where('prefecture', $prefecture))
            ->when($validated['min_amount'] ?? null, fn ($query, $amount) => $query->where('target_amount', '>=', $amount))
            ->when($validated['deadline'] ?? null, fn ($query, $deadline) => $query->whereDate('end_at', '>=', $deadline))
            ->when($validated['category'] ?? null, function ($query, $slug) use ($validated) {
                $column = ($validated['type'] ?? 'sport') === 'culture' ? 'culture_category_id' : 'sport_category_id';
                $category = Category::where('slug', $slug)->first();
                if ($category) {
                    $query->where($column, $category->id);
                }
            })
            ->when($request->filled('tag'), function ($query) use ($request) {
                $tag = Tag::where('slug', $request->string('tag'))->first();
                if ($tag) {
                    $query->whereHas('tags', fn ($q) => $q->where('tags.id', $tag->id));
                }
            })
            ->paginate(12)
            ->withQueryString();

        return view('public.projects.search', [
            'projects' => $projects,
            'filters' => $validated,
            'sportCategories' => Category::where('type', 'sport')->get(),
            'cultureCategories' => Category::where('type', 'culture')->get(),
        ]);
    }

    public function show(string $slug)
    {
        $project = Project::where('slug', $slug)
            ->with([
                'organization',
                'tags',
                'tiers' => fn ($q) => $q->orderBy('amount'),
                'media' => fn ($q) => $q->orderBy('sort'),
                'updates' => fn ($q) => $q->latest('published_at'),
            ])
            ->published()
            ->firstOrFail();

        $related = Project::published()
            ->where('id', '!=', $project->id)
            ->where(function ($query) use ($project) {
                $query->where('prefecture', $project->prefecture)
                    ->orWhereHas('tags', fn ($tagQuery) => $tagQuery->whereIn('tags.id', $project->tags->pluck('id')));
            })
            ->with('organization')
            ->take(3)
            ->get();

        return view('public.projects.show', compact('project', 'related'));
    }

    public function store(ProjectRequest $request)
    {
        Gate::authorize('create', Project::class);

        $data = $request->validated();
        $organization = Organization::findOrFail($data['organization_id']);

        Gate::authorize('manageOrganization', $organization);

        $project = Project::create([
            'organization_id' => $organization->id,
            'title' => $data['title'],
            'slug' => Str::slug($data['title']) . '-' . Str::random(6),
            'summary' => $data['summary'] ?? null,
            'description' => $data['description'] ?? null,
            'sport_category_id' => $data['sport_category_id'] ?? null,
            'culture_category_id' => $data['culture_category_id'] ?? null,
            'target_amount' => $data['target_amount'],
            'start_at' => $data['start_at'] ?? null,
            'end_at' => $data['end_at'] ?? null,
            'status' => 'draft',
            'prefecture' => $data['prefecture'] ?? null,
            'city' => $data['city'] ?? null,
        ]);

        if (!empty($data['tags'])) {
            $project->tags()->sync($data['tags']);
        }

        return redirect()->route('dashboard.projects.edit', $project)
            ->with('status', 'プロジェクトを作成しました。');
    }

    public function update(ProjectRequest $request, Project $project)
    {
        Gate::authorize('update', $project);

        $payload = collect($request->validated());
        $projectData = $payload->except('tags')->toArray();

        $project->update(array_merge($projectData, [
            'summary' => $projectData['summary'] ?? null,
            'description' => $projectData['description'] ?? null,
        ]));

        $project->tags()->sync($payload->get('tags', []));

        if ($request->boolean('submit_for_review')) {
            $project->update(['status' => 'reviewing']);
        }

        return back()->with('status', 'プロジェクトを更新しました。');
    }

    public function destroy(Project $project)
    {
        Gate::authorize('delete', $project);

        $project->delete();

        return redirect()->route('dashboard.projects.index')
            ->with('status', 'プロジェクトを削除しました。');
    }

    public function storeMedia(Request $request, Project $project)
    {
        Gate::authorize('update', $project);

        $validated = $request->validate([
            'type' => ['required', 'in:image,video'],
            'media' => ['required_if:type,image', 'file', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'url' => ['required_if:type,video', 'url'],
            'caption' => ['nullable', 'string', 'max:255'],
        ]);

        $path = $validated['type'] === 'image'
            ? $validated['media']->store('project-media', 'public')
            : $validated['url'];

        $project->media()->create([
            'type' => $validated['type'],
            'path' => $path,
            'caption' => $validated['caption'] ?? null,
            'sort' => ($project->media()->max('sort') ?? 0) + 1,
        ]);

        return back()->with('status', 'メディアを追加しました。');
    }

    public function storeTier(Request $request, Project $project)
    {
        Gate::authorize('update', $project);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'amount' => ['required', 'integer', 'min:1000'],
            'description' => ['nullable', 'string', 'max:1000'],
            'limit_qty' => ['nullable', 'integer', 'min:1'],
        ]);

        $project->tiers()->create($validated);

        return back()->with('status', '協賛プランを追加しました。');
    }

    public function storeUpdate(ProjectUpdateRequest $request, Project $project)
    {
        Gate::authorize('update', $project);

        $project->updates()->create($request->validated());

        return back()->with('status', '活動報告を追加しました。');
    }
}
