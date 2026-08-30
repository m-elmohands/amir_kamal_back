<?php

namespace Tests\Feature;

use App\Models\Inquiry;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InquiryApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_visitor_can_submit_an_inquiry(): void
    {
        $payload = ['name' => 'Ahmed Saleh', 'email' => 'ahmed@example.com', 'subject' => 'Tooling advice', 'message' => 'I need help choosing a boring bar.'];
        $this->postJson('/api/v1/inquiries', $payload)->assertCreated()->assertJsonPath('message', 'Your inquiry has been received. Our team will contact you shortly.');
        $this->assertDatabaseHas(Inquiry::class, ['email' => 'ahmed@example.com', 'status' => 'new']);
    }

    public function test_inquiry_validation_uses_json_errors(): void
    {
        $this->postJson('/api/v1/inquiries', [])->assertUnprocessable()->assertJsonValidationErrors(['name', 'email', 'subject', 'message']);
    }
}
