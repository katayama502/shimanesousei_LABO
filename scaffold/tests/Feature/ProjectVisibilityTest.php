<?php

namespace Tests\Feature;

use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectVisibilityTest extends TestCase
{
    use RefreshDatabase;

    public function test_reviewing_projects_do_not_appear_in_public_list(): void
    {
        $project = Project::factory()->create(['status' => 'reviewing']);

        $response = $this->get(route('projects.search'));

        $response->assertOk();
        $response->assertDontSee($project->title);
    }
}
