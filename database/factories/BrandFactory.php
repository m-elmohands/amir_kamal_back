<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class BrandFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->unique()->company();

        return ['en' => ['name' => $name, 'description' => fake()->sentence()], 'slug' => str($name)->slug(), 'is_active' => true];
    }
}
