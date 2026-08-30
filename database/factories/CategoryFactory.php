<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->unique()->words(2, true);

        return ['en' => ['name' => str($name)->title(), 'description' => fake()->sentence()], 'slug' => str($name)->slug(), 'is_active' => true];
    }
}
