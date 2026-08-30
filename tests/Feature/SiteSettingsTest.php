<?php

namespace Tests\Feature;

use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SiteSettingsTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_and_update_bilingual_site_settings(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $this->actingAs($admin)->get(route('admin.settings.edit'))->assertOk();
        $this->put(route('admin.settings.update'), $this->payload())->assertRedirect();

        $settings = SiteSetting::current();
        $this->assertSame('Axis Industrial', $settings->translate('en')->site_name);
        $this->assertSame('أكسس إندستريال', $settings->translate('ar')->site_name);
        $this->assertSame('sales@example.com', $settings->email);
        $this->assertTrue($settings->maintenance_mode);
    }

    public function test_public_settings_api_returns_the_requested_translation_and_envelope(): void
    {
        $settings = SiteSetting::current();
        $settings->fill($this->payload())->save();

        $this->getJson('/api/v1/settings?locale=ar')
            ->assertOk()
            ->assertJsonPath('data.site_name', 'أكسس إندستريال')
            ->assertJsonPath('data.seo.title', 'عنوان الموقع')
            ->assertJsonPath('data.maintenance_mode', true)
            ->assertJsonPath('message', 'Settings retrieved successfully.')
            ->assertJsonPath('errors', null);
    }

    public function test_favicon_is_managed_and_served_by_site_settings(): void
    {
        Storage::fake('public');
        $admin = User::factory()->create(['is_admin' => true]);

        $this->actingAs($admin)->put(route('admin.settings.update'), $this->payload() + [
            'app_icon' => UploadedFile::fake()->image('favicon.png', 128, 128),
        ])->assertRedirect();

        $settings = SiteSetting::current();
        $this->assertNotNull($settings->favicon_path);
        Storage::disk('public')->assertExists($settings->favicon_path);

        $this->getJson('/api/v1/settings?locale=en')
            ->assertOk()
            ->assertJsonPath('data.favicon_url', url(Storage::disk('public')->url($settings->favicon_path)));
    }

    public function test_logo_can_be_uploaded_replaced_removed_and_served_by_the_api(): void
    {
        Storage::fake('public');
        $admin = User::factory()->create(['is_admin' => true]);

        $this->actingAs($admin)->put(route('admin.settings.update'), $this->payload() + [
            'logo' => UploadedFile::fake()->image('logo.png', 600, 240),
        ])->assertRedirect();

        $settings = SiteSetting::current();
        $firstLogoPath = $settings->logo_path;
        $this->assertNotNull($firstLogoPath);
        Storage::disk('public')->assertExists($firstLogoPath);

        $this->getJson('/api/v1/settings?locale=en')
            ->assertOk()
            ->assertJsonPath('data.logo_url', url(Storage::disk('public')->url($firstLogoPath)));

        $this->put(route('admin.settings.update'), $this->payload() + [
            'logo' => UploadedFile::fake()->image('replacement.webp', 600, 240),
        ])->assertRedirect();

        $settings->refresh();
        $replacementLogoPath = $settings->logo_path;
        $this->assertNotSame($firstLogoPath, $replacementLogoPath);
        Storage::disk('public')->assertMissing($firstLogoPath);
        Storage::disk('public')->assertExists($replacementLogoPath);

        $this->put(route('admin.settings.update'), $this->payload() + [
            'remove_logo' => '1',
        ])->assertRedirect();

        $settings->refresh();
        $this->assertNull($settings->logo_path);
        Storage::disk('public')->assertMissing($replacementLogoPath);
    }

    public function test_non_admin_cannot_manage_site_settings(): void
    {
        $this->actingAs(User::factory()->create())
            ->get(route('admin.settings.edit'))
            ->assertForbidden();
    }

    public function test_admin_pages_use_site_settings_for_identity_and_icons(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('settings/admin-icon.png', 'icon');
        Storage::disk('public')->put('settings/admin-logo.png', 'logo');

        $settings = SiteSetting::current();
        $settings->fill($this->payload())->save();
        $settings->update([
            'favicon_path' => 'settings/admin-icon.png',
            'logo_path' => 'settings/admin-logo.png',
        ]);

        $faviconUrl = url(Storage::disk('public')->url('settings/admin-icon.png'));
        $logoUrl = url(Storage::disk('public')->url('settings/admin-logo.png'));

        $this->get(route('login'))
            ->assertOk()
            ->assertSee('<title>Admin sign in · Axis Industrial</title>', false)
            ->assertSee($logoUrl, false)
            ->assertSee($faviconUrl, false);

        $this->actingAs(User::factory()->create(['is_admin' => true]))
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('Axis Industrial')
            ->assertSee($logoUrl, false)
            ->assertSee($faviconUrl, false);
    }

    private function payload(): array
    {
        return [
            'en' => [
                'site_name' => 'Axis Industrial',
                'address' => 'Cairo',
                'footer_description' => 'CNC supplies',
                'seo_title' => 'Site title',
                'seo_description' => 'Site description',
            ],
            'ar' => [
                'site_name' => 'أكسس إندستريال',
                'address' => 'القاهرة',
                'footer_description' => 'مستلزمات سي إن سي',
                'seo_title' => 'عنوان الموقع',
                'seo_description' => 'وصف الموقع',
            ],
            'email' => 'sales@example.com',
            'phone' => '+201000000000',
            'whatsapp' => '+201000000000',
            'facebook_url' => 'https://facebook.com/example',
            'instagram_url' => 'https://instagram.com/example',
            'linkedin_url' => 'https://linkedin.com/company/example',
            'youtube_url' => 'https://youtube.com/@example',
            'maintenance_mode' => '1',
        ];
    }
}
