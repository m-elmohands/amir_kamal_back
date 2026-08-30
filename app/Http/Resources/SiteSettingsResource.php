<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SiteSettingsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'site_name' => $this->site_name,
            'logo_url' => $this->logo_url,
            'favicon_url' => $this->favicon_url,
            'email' => $this->email,
            'phone' => $this->phone,
            'whatsapp' => $this->whatsapp,
            'address' => $this->address,
            'footer_description' => $this->footer_description,
            'seo' => [
                'title' => $this->seo_title,
                'description' => $this->seo_description,
            ],
            'social' => [
                'facebook' => $this->facebook_url,
                'instagram' => $this->instagram_url,
                'linkedin' => $this->linkedin_url,
                'youtube' => $this->youtube_url,
            ],
            'maintenance_mode' => $this->maintenance_mode,
        ];
    }
}
