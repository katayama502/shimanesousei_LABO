<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'reportable_type' => ['required', 'string', 'max:120'],
            'reportable_id' => ['required', 'integer'],
            'reason' => ['required', 'string', 'max:2000'],
        ]);

        $report = Report::create([
            'reportable_type' => $validated['reportable_type'],
            'reportable_id' => $validated['reportable_id'],
            'reporter_user_id' => $request->user()?->id,
            'reason' => $validated['reason'],
        ]);

        return back()->with('status', '通報を受け付けました。');
    }
}
