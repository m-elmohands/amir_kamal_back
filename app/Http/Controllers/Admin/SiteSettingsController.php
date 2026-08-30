<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Settings\UpdateSiteSettings;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateSiteSettingsRequest;
use App\Models\SiteSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SiteSettingsController extends Controller
{
    public function edit(): View
    {
        $settings = SiteSetting::current();
        $this->authorize('view', $settings);

        return view('admin.settings.edit', [
            'settings' => $settings,
            'logoUrl' => $settings->logo_path ? url(Storage::disk('public')->url($settings->logo_path)) : null,
            'iconUrl' => $settings->favicon_path ? url(Storage::disk('public')->url($settings->favicon_path)) : null,
        ]);
    }

    public function update(UpdateSiteSettingsRequest $request, UpdateSiteSettings $action): RedirectResponse
    {
        $action->handle($request, SiteSetting::current());

        return back()->with('success', __('Settings updated successfully.'));
    }
}
