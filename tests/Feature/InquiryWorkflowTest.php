<?php

namespace Tests\Feature;

use App\Models\Inquiry;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InquiryWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_qualify_assign_and_quote_an_inquiry(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $inquiry = Inquiry::factory()->create();

        $this->actingAs($admin)->patch(route('admin.inquiries.update', $inquiry), [
            'status' => 'quoted',
            'priority' => 'high',
            'assigned_to' => $admin->id,
            'quoted_amount' => 12500,
            'next_follow_up_at' => '2026-09-02 10:00:00',
            'internal_notes' => 'Customer needs a firm delivery date.',
        ])->assertRedirect()->assertSessionHas('success');

        $this->assertDatabaseHas(Inquiry::class, [
            'id' => $inquiry->id,
            'status' => 'quoted',
            'priority' => 'high',
            'assigned_to' => $admin->id,
            'quoted_amount' => 12500,
        ]);
        $this->assertNotNull($inquiry->refresh()->quoted_at);
    }

    public function test_quote_stage_requires_an_amount(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $inquiry = Inquiry::factory()->create();

        $this->actingAs($admin)->from(route('admin.inquiries.index'))->patch(route('admin.inquiries.update', $inquiry), [
            'status' => 'quoted',
            'priority' => 'normal',
        ])->assertRedirect(route('admin.inquiries.index'))->assertSessionHasErrors('quoted_amount');

        $this->assertDatabaseHas(Inquiry::class, ['id' => $inquiry->id, 'status' => 'new']);
    }

    public function test_non_admin_cannot_update_an_inquiry(): void
    {
        $inquiry = Inquiry::factory()->create();

        $this->actingAs(User::factory()->create())->patch(route('admin.inquiries.update', $inquiry), [
            'status' => 'in_progress',
            'priority' => 'normal',
        ])->assertForbidden();
    }
}
