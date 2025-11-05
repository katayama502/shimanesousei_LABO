<?php

namespace Tests\Feature;

use App\Models\Organization;
use App\Models\Project;
use App\Models\Sponsorship;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SponsorshipAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_create_sponsorship(): void
    {
        $project = Project::factory()->create();

        $response = $this->post(route('projects.sponsor', $project));

        $response->assertRedirect(route('login'));
    }

    public function test_company_can_view_only_own_sponsorship(): void
    {
        $this->seed();
        $companyUser = User::where('role', 'company')->first();
        $otherSponsorship = Sponsorship::factory()->create();

        $this->actingAs($companyUser);
        $response = $this->get(route('sponsorships.show', $otherSponsorship));

        $response->assertStatus(403);
    }
}
