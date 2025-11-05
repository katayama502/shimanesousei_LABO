<?php

namespace Database\Factories;

use App\Models\Organization;
use App\Models\Project;
use App\Models\Sponsorship;
use Illuminate\Database\Eloquent\Factories\Factory;

class SponsorshipFactory extends Factory
{
    protected $model = Sponsorship::class;

    public function definition(): array
    {
        $company = Organization::factory()->create(['type' => 'company']);
        $project = Project::factory()->create();

        return [
            'project_id' => $project->id,
            'company_org_id' => $company->id,
            'amount' => 10000,
            'status' => 'pending',
            'payment_method' => 'invoice',
        ];
    }
}
