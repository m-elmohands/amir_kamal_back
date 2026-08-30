<?php

namespace App\Models;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model implements TranslatableContract
{
    use Translatable;

    public array $translatedAttributes = [
        'site_name',
        'address',
        'footer_description',
        'seo_title',
        'seo_description',
    ];

    protected $fillable = [
        'logo_path',
        'favicon_path',
        'email',
        'phone',
        'whatsapp',
        'facebook_url',
        'instagram_url',
        'linkedin_url',
        'youtube_url',
        'maintenance_mode',
    ];

    protected function casts(): array
    {
        return ['maintenance_mode' => 'boolean'];
    }

    public static function current(): self
    {
        return static::query()->with('translations')->firstOrCreate(
            ['id' => 1],
            ['maintenance_mode' => false],
        );
    }
}
