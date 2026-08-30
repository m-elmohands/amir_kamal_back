<?php

namespace Database\Factories;

use App\Models\Brand;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->unique()->words(3, true);

        return ['category_id' => Category::factory(), 'brand_id' => Brand::factory(), 'en' => ['name' => str($name)->title(), 'summary' => fake()->sentence(), 'description' => fake()->paragraphs(2, true), 'specifications' => ['Material' => 'Hardened steel', 'Accuracy' => '0.01 mm']], 'slug' => str($name)->slug(), 'sku' => fake()->unique()->bothify('??-###-??'), 'availability_status' => 'in_stock', 'stock_quantity' => 24, 'minimum_order_quantity' => 1, 'is_featured' => false, 'is_active' => true];
    }
}
