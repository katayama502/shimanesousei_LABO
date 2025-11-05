<?php

namespace Tests\Feature;

use App\Models\Organization;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ValidationFeedbackTest extends TestCase
{
    use RefreshDatabase;

    public function test_sponsorship_requires_amount(): void
    {
        $project = Project::factory()->create();
        $companyUser = User::factory()->create(['role' => 'company']);
        $organization = Organization::factory()->create(['type' => 'company']);
        $companyUser->organizations()->attach($organization->id, ['role' => 'owner']);

        $response = $this->actingAs($companyUser)->post(route('projects.sponsor', $project), [
            'payment_method' => 'invoice',
        ]);

        $response->assertSessionHasErrors('amount');
    }
}
