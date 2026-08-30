<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CatalogApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_products_are_paginated_and_hidden_products_are_excluded(): void
    {
        Product::factory()->create(['name' => 'Visible Chuck', 'is_active' => true]);
        Product::factory()->create(['is_active' => false]);
        $this->getJson('/api/v1/products?search=Chuck')->assertOk()->assertJsonCount(1, 'data')->assertJsonPath('data.0.name', 'Visible Chuck')->assertJsonStructure(['data', 'links', 'meta']);
    }

    public function test_product_can_be_retrieved_by_slug(): void
    {
        $product = Product::factory()->create();
        $this->getJson('/api/v1/products/'.$product->slug)->assertOk()->assertJsonPath('data.sku', $product->sku)->assertJsonPath('data.availability.status', 'in_stock')->assertJsonPath('data.availability.can_request_quote', true);
    }

    public function test_catalog_uses_the_requested_arabic_translation(): void
    {
        $product = Product::factory()->create([
            'en' => ['name' => 'Precision Chuck', 'summary' => 'English summary', 'description' => 'English description'],
            'ar' => ['name' => 'ظرف دقيق', 'summary' => 'ملخص عربي', 'description' => 'وصف عربي'],
        ]);

        $this->getJson("/api/v1/products/{$product->slug}?locale=ar")
            ->assertOk()
            ->assertJsonPath('data.name', 'ظرف دقيق')
            ->assertJsonPath('data.summary', 'ملخص عربي');
    }
}
