<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_club_cannot_edit_other_projects(): void
    {
        $this->seed();
        $project = Project::first();
        $otherProject = Project::where('id', '!=', $project->id)->first();
        $clubUser = $project->organization->users()->first();

        $this->actingAs($clubUser);
        $response = $this->put(route('dashboard.projects.update', $otherProject), [
            'organization_id' => $otherProject->organization_id,
            'title' => $otherProject->title,
            'summary' => $otherProject->summary,
            'description' => $otherProject->description,
            'target_amount' => $otherProject->target_amount,
        ]);

        $response->assertStatus(403);
    }
}
