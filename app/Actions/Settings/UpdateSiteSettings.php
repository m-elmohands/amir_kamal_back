<?php

namespace App\Actions\Settings;

use App\Http\Requests\Admin\UpdateSiteSettingsRequest;
use App\Models\SiteSetting;
use App\Services\AuditService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class UpdateSiteSettings
{
    public function __construct(private readonly AuditService $audit) {}

    public function handle(UpdateSiteSettingsRequest $request, SiteSetting $settings): void
    {
        $data = $request->safe()->except(['logo', 'remove_logo', 'app_icon', 'remove_app_icon']);
        $data['maintenance_mode'] = $request->boolean('maintenance_mode');
        $settings->fill($data)->save();

        if ($request->boolean('remove_logo')) {
            $this->removeMedia($settings, 'logo_path');
        } elseif ($request->hasFile('logo')) {
            $this->replaceMedia($settings, 'logo_path', $request->file('logo'), 'settings/logos');
        }

        if ($request->boolean('remove_app_icon')) {
            $this->removeMedia($settings, 'favicon_path');
        } elseif ($request->hasFile('app_icon')) {
            $this->replaceMedia($settings, 'favicon_path', $request->file('app_icon'), 'settings/icons');
        }

        Cache::forget('site-settings.public.en');
        Cache::forget('site-settings.public.ar');
        $this->audit->record($request, 'site_settings.updated', $settings);
    }

    private function replaceMedia(
        SiteSetting $settings,
        string $attribute,
        UploadedFile $file,
        string $directory,
    ): void {
        $previousPath = $settings->getAttribute($attribute);
        $settings->update([$attribute => $file->store($directory, 'public')]);

        if ($previousPath && $previousPath !== $settings->getAttribute($attribute)) {
            Storage::disk('public')->delete($previousPath);
        }
    }

    private function removeMedia(SiteSetting $settings, string $attribute): void
    {
        $path = $settings->getAttribute($attribute);

        if ($path) {
            Storage::disk('public')->delete($path);
        }

        $settings->update([$attribute => null]);
    }
}
