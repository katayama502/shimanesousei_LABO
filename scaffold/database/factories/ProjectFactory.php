<?php

namespace Database\Factories;

use App\Models\Organization;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Project>
 */
class ProjectFactory extends Factory
{
    protected $model = Project::class;

    public function definition(): array
    {
        $organization = Organization::factory()->create(['type' => 'club']);

        return [
            'organization_id' => $organization->id,
            'title' => $this->faker->sentence(3),
            'slug' => Str::slug($this->faker->unique()->sentence(3)) . '-' . Str::random(6),
            'summary' => $this->faker->sentence(),
            'description' => $this->faker->paragraph(),
            'target_amount' => 100000,
            'status' => 'published',
        ];
    }
}
