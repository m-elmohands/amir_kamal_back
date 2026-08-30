<?php

namespace Tests\Feature;

use App\Models\Inquiry;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminDataTableTest extends TestCase
{
    use RefreshDatabase;

    public function test_products_endpoint_returns_server_side_datatable_data(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        Product::factory()->create(['en' => ['name' => 'Precision Chuck', 'summary' => 'Workshop chuck', 'description' => 'Description']]);

        $this->actingAs($admin)->getJson(route('admin.products.data', [
            'draw' => 1, 'start' => 0, 'length' => 15,
            'search' => ['value' => 'Precision', 'regex' => 'false'],
            'columns' => [
                ['data' => 'name', 'name' => 'name', 'searchable' => 'true', 'orderable' => 'true', 'search' => ['value' => '', 'regex' => 'false']],
                ['data' => 'sku', 'name' => 'sku', 'searchable' => 'true', 'orderable' => 'true', 'search' => ['value' => '', 'regex' => 'false']],
            ],
        ]))->assertOk()->assertJsonPath('recordsFiltered', 1)->assertJsonCount(1, 'data');
    }

    public function test_inquiries_endpoint_returns_server_side_datatable_data(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        Inquiry::query()->create(['name' => 'Mona Ali', 'email' => 'mona@example.com', 'subject' => 'Quote', 'message' => 'Please quote this tooling request.']);

        $this->actingAs($admin)->getJson(route('admin.inquiries.data', ['draw' => 1, 'start' => 0, 'length' => 15]))
            ->assertOk()->assertJsonPath('recordsTotal', 1)->assertJsonCount(1, 'data');
    }

    public function test_non_admin_cannot_access_datatable_endpoints(): void
    {
        $this->actingAs(User::factory()->create())->getJson(route('admin.products.data'))->assertForbidden();
    }
}
