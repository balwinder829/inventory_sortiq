<?php

namespace App\Helpers;

use App\Models\SeoMeta;
use App\Models\SeoSetting;
use Illuminate\Support\Facades\Schema;

class SeoHelper
{
    /**
     * Resolve SEO metadata for the current request.
     */
    public static function getMeta(?string $overridePath = null): array
    {
        $currentPath = $overridePath ?? '/' . ltrim(request()->path(), '/');
        if ($currentPath === '/index' || $currentPath === '/home') {
            $currentPath = '/';
        }

        $defaultMeta = [
            'title'       => 'StockFlow Systems - Premium Corporate Inventory Sourcing Portal',
            'description' => 'Real-time multi-warehouse stock intelligence, automated procurement workflows, and enterprise inventory logistics.',
            'keywords'    => 'inventory management, stock tracking, warehouse logistics, b2b procurement, supply chain',
            'canonical'   => url($currentPath === '/' ? '' : $currentPath),
            'robots'      => 'index, follow',
            'og_title'    => 'StockFlow Systems - Enterprise Inventory Management',
            'og_description' => 'Empowering modern organizations with intelligent real-time stock allocation and logistics visibility.',
            'og_image'    => asset('assets/inventory/navbarlogo.png'),
            'og_type'     => 'website',
            'twitter_card'=> 'summary_large_image',
        ];

        try {
            if (!Schema::hasTable('seo_metas')) {
                return $defaultMeta;
            }

            // Attempt exact path match
            $meta = SeoMeta::where('path', $currentPath)->first();

            // Fallback: match by route name
            if (!$meta && request()->route()) {
                $routeName = request()->route()->getName();
                $cleanRoute = str_replace('frontend.', '', (string) $routeName);
                $meta = SeoMeta::where('page_key', $cleanRoute)->first();
            }

            // Fallback: catalog/products path alias
            if (!$meta && in_array($currentPath, ['/catalog', '/products'])) {
                $meta = SeoMeta::whereIn('path', ['/products', '/catalog'])->first();
            }

            if ($meta) {
                $setting = Schema::hasTable('seo_settings') ? SeoSetting::first() : null;
                $suffix = $setting->site_title_suffix ?? ' | StockFlow Systems';

                $title = $meta->meta_title ?: $defaultMeta['title'];
                if ($suffix && !str_contains($title, $suffix)) {
                    $title .= $suffix;
                }

                $ogImage = $meta->og_image ?: ($setting->default_og_image ?? '/assets/inventory/navbarlogo.png');
                if ($ogImage && !str_starts_with($ogImage, 'http')) {
                    $ogImage = asset(ltrim($ogImage, '/'));
                }

                return [
                    'title'          => $title,
                    'description'    => $meta->meta_description ?: ($setting->default_meta_description ?? $defaultMeta['description']),
                    'keywords'       => $meta->meta_keywords ?: ($setting->default_meta_keywords ?? $defaultMeta['keywords']),
                    'canonical'      => $meta->canonical_url ?: url($currentPath === '/' ? '' : $currentPath),
                    'robots'         => $meta->robots ?: 'index, follow',
                    'og_title'       => $meta->og_title ?: $title,
                    'og_description' => $meta->og_description ?: ($meta->meta_description ?: $defaultMeta['og_description']),
                    'og_image'       => $ogImage,
                    'og_type'        => 'website',
                    'twitter_card'   => 'summary_large_image',
                ];
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('SeoHelper error: ' . $e->getMessage());
        }

        return $defaultMeta;
    }

    /**
     * Render full HTML <head> meta tags.
     */
    public static function render(): string
    {
        $seo = self::getMeta();

        $html = [];
        $html[] = '<title>' . e($seo['title']) . '</title>';
        $html[] = '<meta name="description" content="' . e($seo['description']) . '">';
        if (!empty($seo['keywords'])) {
            $html[] = '<meta name="keywords" content="' . e($seo['keywords']) . '">';
        }
        $html[] = '<meta name="robots" content="' . e($seo['robots']) . '">';
        $html[] = '<link rel="canonical" href="' . e($seo['canonical']) . '">';

        // OpenGraph
        $html[] = '<meta property="og:type" content="' . e($seo['og_type']) . '">';
        $html[] = '<meta property="og:url" content="' . e($seo['canonical']) . '">';
        $html[] = '<meta property="og:title" content="' . e($seo['og_title']) . '">';
        $html[] = '<meta property="og:description" content="' . e($seo['og_description']) . '">';
        if (!empty($seo['og_image'])) {
            $html[] = '<meta property="og:image" content="' . e($seo['og_image']) . '">';
        }

        // Twitter Cards
        $html[] = '<meta name="twitter:card" content="' . e($seo['twitter_card']) . '">';
        $html[] = '<meta name="twitter:title" content="' . e($seo['og_title']) . '">';
        $html[] = '<meta name="twitter:description" content="' . e($seo['og_description']) . '">';
        if (!empty($seo['og_image'])) {
            $html[] = '<meta name="twitter:image" content="' . e($seo['og_image']) . '">';
        }

        return implode("\n    ", $html);
    }

    /**
     * Render Header Tracking Scripts (e.g. Google Analytics gtag.js, Meta Pixel, GTM)
     */
    public static function renderHeaderScripts(): string
    {
        $output = [];

        try {
            if (Schema::hasTable('seo_settings')) {
                $setting = SeoSetting::first();
                if ($setting) {
                    // 1. Google Analytics Measurement ID
                    if (!empty($setting->google_analytics_id)) {
                        $gaId = htmlspecialchars(trim($setting->google_analytics_id));
                        $output[] = "<!-- Google tag (gtag.js) -->";
                        $output[] = "<script async src=\"https://www.googletagmanager.com/gtag/js?id={$gaId}\"></script>";
                        $output[] = "<script>\n  window.dataLayer = window.dataLayer || [];\n  function gtag(){dataLayer.push(arguments);}\n  gtag('js', new Date());\n  gtag('config', '{$gaId}');\n</script>";
                    }

                    // 2. Custom Raw Header Script Tags
                    if (!empty($setting->header_scripts)) {
                        $output[] = trim($setting->header_scripts);
                    }
                }
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('SeoHelper header scripts error: ' . $e->getMessage());
        }

        return implode("\n", $output);
    }

    /**
     * Render Footer Scripts (before </body>)
     */
    public static function renderFooterScripts(): string
    {
        try {
            if (Schema::hasTable('seo_settings')) {
                $setting = SeoSetting::first();
                if ($setting && !empty($setting->footer_scripts)) {
                    return trim($setting->footer_scripts);
                }
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('SeoHelper footer scripts error: ' . $e->getMessage());
        }

        return '';
    }
}
