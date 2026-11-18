<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SeoSetting extends Model
{
    protected $table = 'seo_settings';

    protected $fillable = [
        'site_title_suffix',
        'default_meta_description',
        'default_meta_keywords',
        'default_og_image',
        'robots_txt_content',
        'google_analytics_id',
        'header_scripts',
        'footer_scripts',
    ];
}
