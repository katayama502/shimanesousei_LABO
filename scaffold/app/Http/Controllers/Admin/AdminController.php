<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Project;
use App\Models\Report;
use App\Models\Sponsorship;
use App\Models\Tag;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->string('period', 'month');
        $rangeStart = $period === '30days' ? Carbon::now()->subDays(30) : Carbon::now()->startOfMonth();

        $sponsorships = Sponsorship::where('created_at', '>=', $rangeStart);
        $approvedCount = (clone $sponsorships)->where('status', 'approved')->count();
        $totalCount = (clone $sponsorships)->whereIn('status', ['pending', 'approved'])->count();

        $kpi = [
            'applications' => $sponsorships->count(),
            'approval_rate' => $totalCount > 0 ? round($approvedCount / $totalCount * 100, 2) : 0,
            'total_amount' => Sponsorship::where('status', 'approved')->where('created_at', '>=', $rangeStart)->sum('amount'),
            'by_prefecture' => Sponsorship::select('projects.prefecture', DB::raw('count(*) as total'))
                ->join('projects', 'projects.id', '=', 'sponsorships.project_id')
                ->where('sponsorships.created_at', '>=', $rangeStart)
                ->groupBy('projects.prefecture')
                ->get(),
        ];

        return view('admin.dashboard', compact('kpi', 'period'));
    }

    public function reviews()
    {
        $projects = Project::where('status', 'reviewing')->with('organization')->paginate(20);

        return view('admin.reviews', compact('projects'));
    }

    public function reviewAction(Request $request, Project $project)
    {
        $validated = $request->validate([
            'action' => ['required', 'in:approve,reject'],
            'reason' => ['nullable', 'string', 'max:1000'],
        ]);

        abort_unless($project->status === 'reviewing', 400, 'Invalid status.');

        if ($validated['action'] === 'approve') {
            $project->update(['status' => 'published']);
        } else {
            $project->update([
                'status' => 'draft',
                'summary' => trim(($project->summary ?? '') . "\n\nReject Reason: " . ($validated['reason'] ?? '')),
            ]);
        }

        return back()->with('status', '審査を処理しました。');
    }

    public function reports()
    {
        $reports = Report::with(['reportable', 'reporter'])->latest()->paginate(20);

        return view('admin.reports', compact('reports'));
    }

    public function resolveReport(Report $report)
    {
        $report->update(['status' => 'resolved']);

        return back()->with('status', '通報を解決済みにしました。');
    }

    public function master()
    {
        return view('admin.master', [
            'sportCategories' => Category::where('type', 'sport')->get(),
            'cultureCategories' => Category::where('type', 'culture')->get(),
            'tags' => Tag::all(),
        ]);
    }

    public function storeCategory(Request $request)
    {
        $validated = $request->validate([
            'type' => ['required', 'in:sport,culture'],
            'name' => ['required', 'string', 'max:120'],
            'slug' => ['required', 'string', 'max:120', 'unique:categories,slug'],
        ]);

        Category::create($validated);

        return back()->with('status', 'カテゴリを追加しました。');
    }

    public function storeTag(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'slug' => ['required', 'string', 'max:120', 'unique:tags,slug'],
        ]);

        Tag::create($validated);

        return back()->with('status', 'タグを追加しました。');
    }
}
