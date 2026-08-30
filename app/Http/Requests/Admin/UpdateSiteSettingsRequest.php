<?php

namespace App\Http\Requests\Admin;

use App\Models\SiteSetting;
use Illuminate\Foundation\Http\FormRequest;

class UpdateSiteSettingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', SiteSetting::current());
    }

    public function rules(): array
    {
        return [
            'en.site_name' => ['required', 'string', 'max:160'],
            'en.address' => ['nullable', 'string', 'max:1000'],
            'en.footer_description' => ['nullable', 'string', 'max:1000'],
            'en.seo_title' => ['nullable', 'string', 'max:160'],
            'en.seo_description' => ['nullable', 'string', 'max:500'],
            'ar.site_name' => ['required', 'string', 'max:160'],
            'ar.address' => ['nullable', 'string', 'max:1000'],
            'ar.footer_description' => ['nullable', 'string', 'max:1000'],
            'ar.seo_title' => ['nullable', 'string', 'max:160'],
            'ar.seo_description' => ['nullable', 'string', 'max:500'],
            'logo' => ['nullable', 'file', 'mimes:png,jpg,jpeg,webp', 'max:4096'],
            'remove_logo' => ['nullable', 'boolean'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'whatsapp' => ['nullable', 'string', 'max:50'],
            'facebook_url' => ['nullable', 'url', 'max:2048'],
            'instagram_url' => ['nullable', 'url', 'max:2048'],
            'linkedin_url' => ['nullable', 'url', 'max:2048'],
            'youtube_url' => ['nullable', 'url', 'max:2048'],
            'maintenance_mode' => ['nullable', 'boolean'],
            'app_icon' => ['nullable', 'file', 'mimes:png,jpg,jpeg,webp,ico', 'max:2048'],
            'remove_app_icon' => ['nullable', 'boolean'],
        ];
    }
}
