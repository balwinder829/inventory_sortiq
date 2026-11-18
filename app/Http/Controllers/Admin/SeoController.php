<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SeoMeta;
use App\Models\SeoSetting;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class SeoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Default Frontend Pages Definition
     */
    protected function getDefaultPagesData(): array
    {
        return [
            [
                'page_key'         => 'home',
                'page_name'        => 'Home Page',
                'path'             => '/',
                'meta_title'       => 'StockFlow Systems - Premium Corporate Inventory Sourcing Portal',
                'meta_description' => 'Real-time multi-warehouse stock intelligence, automated procurement workflows, and enterprise inventory logistics.',
                'meta_keywords'    => 'inventory management, stock tracking, warehouse logistics, b2b procurement, supply chain',
                'canonical_url'    => null,
                'og_title'         => 'StockFlow Systems - Enterprise Inventory Management',
                'og_description'   => 'Empowering modern organizations with intelligent real-time stock allocation and logistics visibility.',
                'og_image'         => '/assets/inventory/navbarlogo.png',
                'robots'           => 'index, follow',
                'changefreq'       => 'daily',
                'priority'         => 1.0,
                'is_in_sitemap'    => true,
            ],
            [
                'page_key'         => 'about',
                'page_name'        => 'About Us',
                'path'             => '/about',
                'meta_title'       => 'About Us - StockFlow Global Logistics & Warehousing',
                'meta_description' => 'Learn about StockFlow Systems, our mission to modernize supply chain logistics, and our global distribution network.',
                'meta_keywords'    => 'about stockflow, inventory software team, global supply chain, logistics network',
                'canonical_url'    => null,
                'og_title'         => 'About StockFlow Systems',
                'og_description'   => 'Learn about our engineering, logistics hubs, and global supply chain platform.',
                'og_image'         => '/assets/inventory/navbarlogo.png',
                'robots'           => 'index, follow',
                'changefreq'       => 'monthly',
                'priority'         => 0.8,
                'is_in_sitemap'    => true,
            ],
            [
                'page_key'         => 'features',
                'page_name'        => 'Features',
                'path'             => '/features',
                'meta_title'       => 'Platform Features - Automated Reordering, Barcodes & Auditing',
                'meta_description' => 'Explore StockFlow features: instant barcode scanning, FIFO tracking, automated low-stock triggers, and supplier sync.',
                'meta_keywords'    => 'barcode scanner, FIFO inventory, stock audit, supplier reorder, inventory reports',
                'canonical_url'    => null,
                'og_title'         => 'Powerful Inventory Features | StockFlow',
                'og_description'   => 'Automated reorders, barcode labels, multi-store dispatch, and warehouse analytics.',
                'og_image'         => '/assets/inventory/navbarlogo.png',
                'robots'           => 'index, follow',
                'changefreq'       => 'weekly',
                'priority'         => 0.9,
                'is_in_sitemap'    => true,
            ],
            [
                'page_key'         => 'modules',
                'page_name'        => 'Modules',
                'path'             => '/modules',
                'meta_title'       => 'Enterprise Modules - Warehouse, Sourcing & Point of Sale',
                'meta_description' => 'Deploy specialized modules for store operations, quotation management, purchase orders, and supplier return tracking.',
                'meta_keywords'    => 'inventory modules, pos integration, purchase orders, quotations, dispatch modules',
                'canonical_url'    => null,
                'og_title'         => 'Enterprise Supply Modules | StockFlow',
                'og_description'   => 'Integrated modules tailored for modern multi-location distribution hubs.',
                'og_image'         => '/assets/inventory/navbarlogo.png',
                'robots'           => 'index, follow',
                'changefreq'       => 'weekly',
                'priority'         => 0.8,
                'is_in_sitemap'    => true,
            ],
            [
                'page_key'         => 'catalog',
                'page_name'        => 'Live Catalog / Products',
                'path'             => '/products',
                'meta_title'       => 'Live Stock Catalog - Sourcing & Corporate Supply',
                'meta_description' => 'Browse verified inventory items with live availability counters, specification sheets, and fast quotation requests.',
                'meta_keywords'    => 'product catalog, wholesale stock, hardware components, industrial supplies, inventory catalog',
                'canonical_url'    => null,
                'og_title'         => 'Live Inventory Catalog | StockFlow Systems',
                'og_description'   => 'Instant procurement and live inventory stock visibility.',
                'og_image'         => '/assets/inventory/navbarlogo.png',
                'robots'           => 'index, follow',
                'changefreq'       => 'daily',
                'priority'         => 0.9,
                'is_in_sitemap'    => true,
            ],
            [
                'page_key'         => 'pricing',
                'page_name'        => 'Pricing & Plans',
                'path'             => '/pricing',
                'meta_title'       => 'Flexible Pricing & Logistics Plans - StockFlow Systems',
                'meta_description' => 'Transparent plans scaled for small warehouses up to multinational retail operations. No hidden licensing costs.',
                'meta_keywords'    => 'inventory software pricing, warehouse tier subscription, enterprise supply software cost',
                'canonical_url'    => null,
                'og_title'         => 'Transparent Pricing Plans | StockFlow',
                'og_description'   => 'Scalable plans for growing warehouses and distribution enterprises.',
                'og_image'         => '/assets/inventory/navbarlogo.png',
                'robots'           => 'index, follow',
                'changefreq'       => 'monthly',
                'priority'         => 0.8,
                'is_in_sitemap'    => true,
            ],
            [
                'page_key'         => 'contact',
                'page_name'        => 'Contact Us',
                'path'             => '/contact',
                'meta_title'       => 'Contact Us - Sourcing Specialists & 24/7 Logistics Support',
                'meta_description' => 'Connect with our procurement support engineers for warehouse onboarding, custom API integrations, or high-volume orders.',
                'meta_keywords'    => 'contact stockflow, inventory support, procurement helpdesk, logistics inquiry',
                'canonical_url'    => null,
                'og_title'         => 'Contact StockFlow Logistics Support',
                'og_description'   => 'Get in touch with our warehouse logistics engineers.',
                'og_image'         => '/assets/inventory/navbarlogo.png',
                'robots'           => 'index, follow',
                'changefreq'       => 'monthly',
                'priority'         => 0.7,
                'is_in_sitemap'    => true,
            ],
            [
                'page_key'         => 'blogs',
                'page_name'        => 'Logistics Insights (Blog)',
                'path'             => '/blogs',
                'meta_title'       => 'Supply Chain & Inventory Best Practices - StockFlow Blog',
                'meta_description' => 'Expert guides on warehouse automation, barcode standards, FIFO vs LIFO valuation, and freight optimization.',
                'meta_keywords'    => 'inventory blog, logistics insights, warehouse management tips, supply chain automation',
                'canonical_url'    => null,
                'og_title'         => 'StockFlow Logistics & Supply Chain Blog',
                'og_description'   => 'Insights and best practices for modern warehouse operators.',
                'og_image'         => '/assets/inventory/navbarlogo.png',
                'robots'           => 'index, follow',
                'changefreq'       => 'weekly',
                'priority'         => 0.8,
                'is_in_sitemap'    => true,
            ],
            [
                'page_key'         => 'dashboard',
                'page_name'        => 'Buyer Portal Terminal',
                'path'             => '/dashboard',
                'meta_title'       => 'Procurement Terminal & Buyer Dashboard | StockFlow',
                'meta_description' => 'Manage RFQs, track submitted purchase requests, and review allocated stock shipments.',
                'meta_keywords'    => 'buyer portal, procurement dashboard, quote terminal',
                'canonical_url'    => null,
                'og_title'         => 'Buyer Procurement Terminal',
                'og_description'   => 'Real-time quotation review and order tracking.',
                'og_image'         => '/assets/inventory/navbarlogo.png',
                'robots'           => 'noindex, nofollow',
                'changefreq'       => 'monthly',
                'priority'         => 0.3,
                'is_in_sitemap'    => false,
            ],
            [
                'page_key'         => 'portal_login',
                'page_name'        => 'Buyer Portal Login',
                'path'             => '/portal/login',
                'meta_title'       => 'Sign In - Buyer Procurement Terminal | StockFlow',
                'meta_description' => 'Secure portal authentication for corporate buyers, suppliers, and procurement personnel.',
                'meta_keywords'    => 'buyer login, corporate portal sign in',
                'canonical_url'    => null,
                'og_title'         => 'Sign In to StockFlow Buyer Terminal',
                'og_description'   => 'Secure authentication for authorized purchasers.',
                'og_image'         => '/assets/inventory/navbarlogo.png',
                'robots'           => 'noindex, follow',
                'changefreq'       => 'monthly',
                'priority'         => 0.3,
                'is_in_sitemap'    => false,
            ],
            [
                'page_key'         => 'portal_signup',
                'page_name'        => 'Buyer Portal Signup',
                'path'             => '/portal/signup',
                'meta_title'       => 'Register Corporate Buyer Account | StockFlow Systems',
                'meta_description' => 'Create a verified corporate buyer account to unlock commercial volume discounts and digital quotation approvals.',
                'meta_keywords'    => 'register buyer account, wholesale account signup',
                'canonical_url'    => null,
                'og_title'         => 'Register Corporate Procurement Account',
                'og_description'   => 'Get verified access to corporate inventory pricing.',
                'og_image'         => '/assets/inventory/navbarlogo.png',
                'robots'           => 'noindex, follow',
                'changefreq'       => 'monthly',
                'priority'         => 0.3,
                'is_in_sitemap'    => false,
            ],
        ];
    }

    /**
     * Ensure SEO database tables and default records exist
     */
    protected function ensurePagesPopulated()
    {
        try {
            // Check if table exists
            if (!Schema::hasTable('seo_metas')) {
                try {
                    Artisan::call('migrate', ['--force' => true]);
                } catch (\Throwable $e) {
                    \Log::warning('Auto migrate failed in controller: ' . $e->getMessage());
                }
            }

            // If table exists and has 0 rows, populate all default pages
            if (Schema::hasTable('seo_metas')) {
                if (SeoMeta::count() === 0) {
                    $defaults = $this->getDefaultPagesData();
                    foreach ($defaults as $p) {
                        SeoMeta::create($p);
                    }
                }
            }

            if (Schema::hasTable('seo_settings')) {
                if (SeoSetting::count() === 0) {
                    SeoSetting::create([
                        'site_title_suffix'        => ' | StockFlow Systems',
                        'default_meta_description' => 'StockFlow Systems delivers intelligent enterprise inventory tracking, multi-warehouse stock visibility, and streamlined corporate procurement.',
                        'default_meta_keywords'    => 'inventory management, stock tracking, warehouse logistics, b2b procurement, supply chain',
                        'default_og_image'         => '/assets/inventory/navbarlogo.png',
                        'robots_txt_content'       => "User-agent: *\nDisallow: /admin/\nDisallow: /portal/\nDisallow: /login\nDisallow: /register\nAllow: /\n\nSitemap: " . url('/sitemap.xml'),
                    ]);
                }
            }
        } catch (\Throwable $e) {
            \Log::warning('Database check fallback in ensurePagesPopulated: ' . $e->getMessage());
        }
    }

    /**
     * SEO Interactive Master Dashboard & Page Meta Manager
     */
    public function index(Request $request)
    {
        $this->ensurePagesPopulated();

        $pages = collect();
        try {
            if (Schema::hasTable('seo_metas')) {
                $pages = SeoMeta::orderBy('id', 'asc')->get();
            }
        } catch (\Throwable $e) {
            $pages = collect();
        }

        // Fallback to memory defaults if database table could not be fetched
        if ($pages->isEmpty()) {
            $defaultList = $this->getDefaultPagesData();
            $pages = collect(array_map(function ($item, $idx) {
                $obj = new SeoMeta($item);
                $obj->id = $idx + 1;
                return $obj;
            }, $defaultList, array_keys($defaultList)));
        }

        // Determine currently active/selected page
        $selectedId = $request->query('page_id');
        $selectedPage = null;

        if ($selectedId) {
            $selectedPage = $pages->firstWhere('id', $selectedId);
        }

        if (!$selectedPage) {
            $selectedPage = $pages->first();
        }

        $settings = null;
        try {
            if (Schema::hasTable('seo_settings')) {
                $settings = SeoSetting::first();
            }
        } catch (\Throwable $e) {
            $settings = null;
        }

        $sitemapUrl = url('/sitemap.xml');
        $robotsUrl  = url('/robots.txt');

        return view('admin.seo.index', compact('pages', 'selectedPage', 'settings', 'sitemapUrl', 'robotsUrl'));
    }

    /**
     * Update Page SEO (Handles both single master page and dedicated edit view)
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'meta_title'       => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:1000',
            'meta_keywords'    => 'nullable|string|max:500',
            'canonical_url'    => 'nullable|url|max:255',
            'og_title'         => 'nullable|string|max:255',
            'og_description'   => 'nullable|string|max:1000',
            'og_image'         => 'nullable|string|max:255',
            'robots'           => 'required|string|max:100',
            'changefreq'       => 'required|in:always,hourly,daily,weekly,monthly,yearly,never',
            'priority'         => 'required|numeric|between:0.0,1.0',
            'is_in_sitemap'    => 'nullable|boolean',
        ]);

        $validated['is_in_sitemap'] = $request->has('is_in_sitemap');

        // Optional page_name and path if editing custom pages
        if ($request->has('page_name')) {
            $validated['page_name'] = $request->input('page_name');
        }
        if ($request->has('path')) {
            $validated['path'] = $request->input('path');
        }

        if (Schema::hasTable('seo_metas')) {
            $page = SeoMeta::find($id);
            if ($page) {
                $page->update($validated);
            }
        }

        $this->syncSitemapFile();

        return redirect()->route('admin.seo.index', ['page_id' => $id])
            ->with('success', "SEO metadata for '{$request->input('page_name', 'selected page')}' updated successfully.");
    }

    /**
     * Store Custom Page SEO
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'page_key'         => 'required|string|max:100|unique:seo_metas,page_key',
            'page_name'        => 'required|string|max:255',
            'path'             => 'required|string|max:255',
            'meta_title'       => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:1000',
            'meta_keywords'    => 'nullable|string|max:500',
            'canonical_url'    => 'nullable|url|max:255',
            'robots'           => 'required|string|max:100',
            'changefreq'       => 'required|in:always,hourly,daily,weekly,monthly,yearly,never',
            'priority'         => 'required|numeric|between:0.0,1.0',
        ]);

        $validated['is_in_sitemap'] = $request->has('is_in_sitemap');

        if (Schema::hasTable('seo_metas')) {
            $created = SeoMeta::create($validated);
            $this->syncSitemapFile();

            return redirect()->route('admin.seo.index', ['page_id' => $created->id])
                ->with('success', "Custom page '{$created->page_name}' added and configured successfully.");
        }

        return redirect()->route('admin.seo.index')
            ->with('success', 'Page configuration saved.');
    }

    /**
     * Delete Custom Page SEO
     */
    public function destroy($id)
    {
        if (Schema::hasTable('seo_metas')) {
            $page = SeoMeta::find($id);
            if ($page) {
                $page->delete();
                $this->syncSitemapFile();
            }
        }

        return redirect()->route('admin.seo.index')
            ->with('success', 'Page SEO configuration deleted.');
    }

    /**
     * Robots.txt Editor View
     */
    public function robots()
    {
        $settings = Schema::hasTable('seo_settings') ? SeoSetting::first() : null;
        $content = $settings->robots_txt_content ?? '';

        if (empty($content) && File::exists(public_path('robots.txt'))) {
            $content = File::get(public_path('robots.txt'));
        }

        if (empty($content)) {
            $content = "User-agent: *\nDisallow: /admin/\nDisallow: /portal/\nDisallow: /login\nDisallow: /register\nAllow: /\n\nSitemap: " . url('/sitemap.xml');
        }

        return view('admin.seo.robots', compact('content', 'settings'));
    }

    /**
     * Update Robots.txt
     */
    public function updateRobots(Request $request)
    {
        $validated = $request->validate([
            'robots_txt_content' => 'required|string',
            'site_title_suffix'  => 'nullable|string|max:100',
        ]);

        if (Schema::hasTable('seo_settings')) {
            $setting = SeoSetting::first() ?? new SeoSetting();
            $setting->robots_txt_content = $validated['robots_txt_content'];
            if (isset($validated['site_title_suffix'])) {
                $setting->site_title_suffix = $validated['site_title_suffix'];
            }
            $setting->save();
        }

        try {
            File::put(public_path('robots.txt'), $validated['robots_txt_content']);
        } catch (\Throwable $e) {
            \Log::warning('Could not write public/robots.txt: ' . $e->getMessage());
        }

        return redirect()->route('admin.seo.robots')
            ->with('success', 'robots.txt file and settings updated successfully.');
    }

    /**
     * Header & Footer Tracking Scripts Editor View
     */
    public function scripts()
    {
        $settings = null;
        if (Schema::hasTable('seo_settings')) {
            try {
                $settings = SeoSetting::first();
            } catch (\Throwable $e) {}
        }

        return view('admin.seo.scripts', compact('settings'));
    }

    /**
     * Update Header & Footer Tracking Scripts
     */
    public function updateScripts(Request $request)
    {
        $validated = $request->validate([
            'google_analytics_id' => 'nullable|string|max:50',
            'header_scripts'      => 'nullable|string',
            'footer_scripts'      => 'nullable|string',
        ]);

        if (Schema::hasTable('seo_settings')) {
            $setting = SeoSetting::first() ?? new SeoSetting();
            $setting->google_analytics_id = $validated['google_analytics_id'] ?? null;
            $setting->header_scripts      = $validated['header_scripts'] ?? null;
            $setting->footer_scripts      = $validated['footer_scripts'] ?? null;
            $setting->save();
        }

        return redirect()->route('admin.seo.scripts')
            ->with('success', 'Google Analytics and custom tracking scripts saved and activated successfully.');
    }

    /**
     * Sitemap Manager View
     */
    public function sitemap()
    {
        $urls = $this->collectSitemapUrls();
        $xmlContent = $this->buildSitemapXml($urls);
        $lastUpdated = File::exists(public_path('sitemap.xml'))
            ? date('Y-m-d H:i:s', File::lastModified(public_path('sitemap.xml')))
            : 'Not Generated Yet';

        return view('admin.seo.sitemap', compact('urls', 'xmlContent', 'lastUpdated'));
    }

    /**
     * Generate & Sync Sitemap File
     */
    public function generateSitemap()
    {
        $this->syncSitemapFile();

        return redirect()->route('admin.seo.sitemap')
            ->with('success', 'Sitemap.xml generated and saved successfully at ' . public_path('sitemap.xml'));
    }

    /**
     * Collect URLs for Sitemap
     */
    protected function collectSitemapUrls(): array
    {
        $urls = [];

        if (Schema::hasTable('seo_metas')) {
            try {
                $pages = SeoMeta::where('is_in_sitemap', true)->orderBy('priority', 'desc')->get();
                foreach ($pages as $p) {
                    $urls[] = [
                        'loc'        => url($p->path === '/' ? '' : $p->path),
                        'lastmod'    => $p->updated_at ? $p->updated_at->toAtomString() : now()->toAtomString(),
                        'changefreq' => $p->changefreq,
                        'priority'   => number_format((float) $p->priority, 1, '.', ''),
                        'title'      => $p->page_name,
                    ];
                }
            } catch (\Throwable $e) {}
        }

        if (empty($urls)) {
            $defaultList = $this->getDefaultPagesData();
            foreach ($defaultList as $p) {
                if ($p['is_in_sitemap']) {
                    $urls[] = [
                        'loc'        => url($p['path'] === '/' ? '' : $p['path']),
                        'lastmod'    => now()->toAtomString(),
                        'changefreq' => $p['changefreq'],
                        'priority'   => number_format((float) $p['priority'], 1, '.', ''),
                        'title'      => $p['page_name'],
                    ];
                }
            }
        }

        if (Schema::hasTable('items')) {
            try {
                $items = Item::where('status', 'publish')->take(200)->get();
                foreach ($items as $item) {
                    $urls[] = [
                        'loc'        => url('/products?item=' . $item->id),
                        'lastmod'    => $item->updated_at ? $item->updated_at->toAtomString() : now()->toAtomString(),
                        'changefreq' => 'weekly',
                        'priority'   => '0.6',
                        'title'      => $item->name,
                    ];
                }
            } catch (\Throwable $e) {}
        }

        return $urls;
    }

    /**
     * Build XML Sitemap String
     */
    protected function buildSitemapXml(array $urls): string
    {
        $xml = [];
        $xml[] = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml[] = '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        foreach ($urls as $u) {
            $xml[] = '  <url>';
            $xml[] = '    <loc>' . htmlspecialchars($u['loc']) . '</loc>';
            $xml[] = '    <lastmod>' . $u['lastmod'] . '</lastmod>';
            $xml[] = '    <changefreq>' . $u['changefreq'] . '</changefreq>';
            $xml[] = '    <priority>' . $u['priority'] . '</priority>';
            $xml[] = '  </url>';
        }

        $xml[] = '</urlset>';

        return implode("\n", $xml);
    }

    /**
     * Sync to public/sitemap.xml
     */
    protected function syncSitemapFile(): void
    {
        try {
            $urls = $this->collectSitemapUrls();
            $xml = $this->buildSitemapXml($urls);
            File::put(public_path('sitemap.xml'), $xml);
        } catch (\Throwable $e) {
            \Log::warning('Error syncing sitemap.xml: ' . $e->getMessage());
        }
    }

    /**
     * Public dynamic sitemap.xml handler
     */
    public function renderSitemap()
    {
        $urls = $this->collectSitemapUrls();
        $xml = $this->buildSitemapXml($urls);

        return Response::make($xml, 200, [
            'Content-Type' => 'application/xml; charset=utf-8',
        ]);
    }

    /**
     * Public dynamic robots.txt handler
     */
    public function renderRobots()
    {
        $settings = null;
        if (Schema::hasTable('seo_settings')) {
            try {
                $settings = SeoSetting::first();
            } catch (\Throwable $e) {}
        }

        $content = $settings->robots_txt_content ?? '';

        if (empty($content) && File::exists(public_path('robots.txt'))) {
            $content = File::get(public_path('robots.txt'));
        }

        if (empty($content)) {
            $content = "User-agent: *\nDisallow: /admin/\nDisallow: /portal/\nAllow: /\n\nSitemap: " . url('/sitemap.xml');
        }

        return Response::make($content, 200, [
            'Content-Type' => 'text/plain; charset=utf-8',
        ]);
    }
}
