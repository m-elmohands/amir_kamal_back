<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\SiteSettingsResource;
use App\Models\SiteSetting;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class SiteSettingsController extends Controller
{
    public function __invoke(): JsonResponse
    {
        $locale = app()->getLocale();
        $data = Cache::rememberForever("site-settings.public.{$locale}", function () {
            $settings = SiteSetting::current();
            $settings->setAttribute('logo_url', $settings->logo_path ? url(Storage::disk('public')->url($settings->logo_path)) : null);
            $settings->setAttribute('favicon_url', $settings->favicon_path ? url(Storage::disk('public')->url($settings->favicon_path)) : null);

            return (new SiteSettingsResource($settings))->resolve();
        });

        return response()->json([
            'data' => $data,
            'message' => 'Settings retrieved successfully.',
            'errors' => null,
        ]);
    }
}
