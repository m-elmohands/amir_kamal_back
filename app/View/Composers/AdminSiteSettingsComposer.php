<?php

namespace App\View\Composers;

use App\Models\SiteSetting;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class AdminSiteSettingsComposer
{
    public function compose(View $view): void
    {
        $settings = SiteSetting::query()->with('translations')->first();
        $siteName = $settings?->site_name ?: config('app.name', 'Admin');

        $view->with('adminSite', [
            'name' => $siteName,
            'initial' => mb_substr($siteName, 0, 1),
            'logo_url' => $settings?->logo_path
                ? url(Storage::disk('public')->url($settings->logo_path))
                : null,
            'favicon_url' => $settings?->favicon_path
                ? url(Storage::disk('public')->url($settings->favicon_path))
                : null,
        ]);
    }
}
