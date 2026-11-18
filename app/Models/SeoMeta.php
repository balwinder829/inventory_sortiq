<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SeoMeta extends Model
{
    protected $table = 'seo_metas';

    protected $fillable = [
        'page_key',
        'page_name',
        'path',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'canonical_url',
        'og_title',
        'og_description',
        'og_image',
        'robots',
        'changefreq',
        'priority',
        'is_in_sitemap',
    ];

    protected $casts = [
        'priority'      => 'float',
        'is_in_sitemap' => 'boolean',
    ];
}
