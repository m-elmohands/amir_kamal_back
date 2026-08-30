<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSettingTranslation extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'site_name',
        'address',
        'footer_description',
        'seo_title',
        'seo_description',
    ];
}
