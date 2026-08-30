<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminTaxonomyCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_categories_and_brands_have_separate_admin_pages(): void
    {
        Category::factory()->create();
        Brand::factory()->create();

        $this->actingAs($this->admin())
            ->get(route('admin.categories.index'))
            ->assertOk()
            ->assertSee(__('Categories'))
            ->assertSee(route('admin.categories.destroy', Category::query()->first()), false);

        $this->get(route('admin.brands.index'))
            ->assertOk()
            ->assertSee(__('Brands'))
            ->assertSee(route('admin.brands.destroy', Brand::query()->first()), false);
    }

    public function test_admin_can_create_update_and_soft_delete_a_category(): void
    {
        $this->actingAs($this->admin())
            ->post(route('admin.categories.store'), $this->categoryData())
            ->assertRedirect();

        $category = Category::query()->firstOrFail();
        $this->assertSame('Cutting Tools', $category->translate('en')->name);

        $this->put(route('admin.categories.update', $category), $this->categoryData([
            'en' => ['name' => 'Updated Tools', 'description' => 'Updated description'],
        ]))->assertRedirect();

        $this->assertSame('Updated Tools', $category->refresh()->translate('en')->name);
        $this->delete(route('admin.categories.destroy', $category))->assertRedirect();
        $this->assertSoftDeleted($category);
    }

    public function test_admin_can_create_update_and_soft_delete_a_brand(): void
    {
        $admin = $this->admin();
        $this->actingAs($admin)->post(route('admin.brands.store'), [
            'en' => ['name' => 'Axis Tools', 'description' => 'Industrial tools'],
            'ar' => ['name' => 'أكسس تولز', 'description' => 'أدوات صناعية'],
            'slug' => 'axis-tools',
            'logo_url' => 'https://example.com/logo.png',
            'is_active' => '1',
        ])->assertRedirect();

        $brand = Brand::query()->firstOrFail();
        $this->put(route('admin.brands.update', $brand), [
            'en' => ['name' => 'Axis Pro', 'description' => 'Industrial tools'],
            'ar' => ['name' => 'أكسس برو', 'description' => 'أدوات صناعية'],
            'slug' => 'axis-pro',
            'logo_url' => 'https://example.com/logo.png',
            'is_active' => '1',
        ])->assertRedirect();

        $this->assertSame('Axis Pro', $brand->refresh()->translate('en')->name);
        $this->delete(route('admin.brands.destroy', $brand))->assertRedirect();
        $this->assertSoftDeleted($brand);
    }

    public function test_product_form_can_be_loaded_for_a_modal(): void
    {
        $this->actingAs($this->admin())
            ->get(route('admin.products.create', ['modal' => 1]))
            ->assertOk()
            ->assertSee('<form', false)
            ->assertDontSee('<html', false);
    }

    private function admin(): User
    {
        return User::factory()->create(['is_admin' => true]);
    }

    private function categoryData(array $overrides = []): array
    {
        return array_replace_recursive([
            'en' => ['name' => 'Cutting Tools', 'description' => 'Precision tooling'],
            'ar' => ['name' => 'أدوات القطع', 'description' => 'أدوات دقيقة'],
            'slug' => 'cutting-tools',
            'image_url' => 'https://example.com/category.png',
            'sort_order' => 1,
            'is_active' => '1',
        ], $overrides);
    }
}
