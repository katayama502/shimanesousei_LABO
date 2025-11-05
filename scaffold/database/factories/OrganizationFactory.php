<?php

namespace Database\Factories;

use App\Models\Organization;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrganizationFactory extends Factory
{
    protected $model = Organization::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->company(),
            'type' => 'club',
            'description' => $this->faker->sentence(),
            'prefecture' => '東京都',
            'city' => '千代田区',
            'is_verified' => false,
        ];
    }
}
