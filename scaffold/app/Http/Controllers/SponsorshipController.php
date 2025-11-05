<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Sponsorship;
use App\Models\SponsorshipTier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class SponsorshipController extends Controller
{
    public function store(Request $request, Project $project)
    {
        Gate::authorize('sponsor', $project);

        $validated = $request->validate([
            'tier_id' => ['nullable', 'exists:sponsorship_tiers,id'],
            'amount' => ['required_without:tier_id', 'integer', 'min:1000'],
            'message' => ['nullable', 'string', 'max:1000'],
            'payment_method' => ['required', 'in:invoice,bank,offline'],
        ]);

        $companyOrganization = $request->user()->organizations()
            ->where('organizations.type', 'company')
            ->firstOrFail();

        if (!empty($validated['tier_id'])) {
            $tier = SponsorshipTier::findOrFail($validated['tier_id']);
            abort_if($tier->project_id !== $project->id, 422, 'Invalid tier selected.');
            $amount = $tier->amount;
        } else {
            $amount = $validated['amount'];
        }

        $sponsorship = Sponsorship::create([
            'project_id' => $project->id,
            'company_org_id' => $companyOrganization->id,
            'tier_id' => $validated['tier_id'] ?? null,
            'amount' => $amount,
            'message' => $validated['message'] ?? null,
            'status' => 'pending',
            'payment_method' => $validated['payment_method'],
        ]);

        return redirect()->route('sponsorships.show', $sponsorship)
            ->with('status', '協賛申込を送信しました。');
    }

    public function show(Sponsorship $sponsorship)
    {
        Gate::authorize('view', $sponsorship);

        $sponsorship->load(['project.organization', 'company', 'tier', 'messages.sender']);

        return view('dashboard.sponsorships.show', compact('sponsorship'));
    }

    public function updateStatus(Request $request, Sponsorship $sponsorship)
    {
        Gate::authorize('updateStatus', $sponsorship);

        $validated = $request->validate([
            'status' => ['required', 'in:approved,rejected,canceled,completed'],
        ]);

        $sponsorship->update($validated);

        return back()->with('status', 'ステータスを更新しました。');
    }
}
