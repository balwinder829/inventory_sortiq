<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. SEO Metas for Individual Pages
        if (!Schema::hasTable('seo_metas')) {
            Schema::create('seo_metas', function (Blueprint $table) {
                $table->id();
                $table->string('page_key')->unique();
                $table->string('page_name');
                $table->string('path');
                $table->string('meta_title')->nullable();
                $table->text('meta_description')->nullable();
                $table->text('meta_keywords')->nullable();
                $table->string('canonical_url')->nullable();
                $table->string('og_title')->nullable();
                $table->text('og_description')->nullable();
                $table->string('og_image')->nullable();
                $table->string('robots')->default('index, follow');
                $table->string('changefreq')->default('weekly');
                $table->decimal('priority', 2, 1)->default(0.8);
                $table->boolean('is_in_sitemap')->default(true);
                $table->timestamps();
            });

            // Seed default frontend pages
            $defaultPages = [
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
                    'created_at'       => now(),
                    'updated_at'       => now(),
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
                    'created_at'       => now(),
                    'updated_at'       => now(),
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
                    'created_at'       => now(),
                    'updated_at'       => now(),
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
                    'created_at'       => now(),
                    'updated_at'       => now(),
                ],
                [
                    'page_key'         => 'catalog',
                    'page_name'        => 'Live Catalog',
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
                    'created_at'       => now(),
                    'updated_at'       => now(),
                ],
                [
                    'page_key'         => 'pricing',
                    'page_name'        => 'Pricing',
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
                    'created_at'       => now(),
                    'updated_at'       => now(),
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
                    'created_at'       => now(),
                    'updated_at'       => now(),
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
                    'created_at'       => now(),
                    'updated_at'       => now(),
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
                    'created_at'       => now(),
                    'updated_at'       => now(),
                ],
                [
                    'page_key'         => 'portal_login',
                    'page_name'        => 'Buyer Login',
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
                    'created_at'       => now(),
                    'updated_at'       => now(),
                ],
                [
                    'page_key'         => 'portal_signup',
                    'page_name'        => 'Buyer Registration',
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
                    'created_at'       => now(),
                    'updated_at'       => now(),
                ],
            ];

            DB::table('seo_metas')->insert($defaultPages);
        }

        // 2. Global SEO Settings & Robots.txt
        if (!Schema::hasTable('seo_settings')) {
            Schema::create('seo_settings', function (Blueprint $table) {
                $table->id();
                $table->string('site_title_suffix')->default(' | StockFlow Systems');
                $table->text('default_meta_description')->nullable();
                $table->text('default_meta_keywords')->nullable();
                $table->string('default_og_image')->nullable();
                $table->longText('robots_txt_content')->nullable();
                $table->string('google_analytics_id')->nullable();
                $table->longText('header_scripts')->nullable();
                $table->longText('footer_scripts')->nullable();
                $table->timestamps();
            });

            DB::table('seo_settings')->insert([
                'site_title_suffix'        => ' | StockFlow Systems',
                'default_meta_description' => 'StockFlow Systems delivers intelligent enterprise inventory tracking, multi-warehouse stock visibility, and streamlined corporate procurement.',
                'default_meta_keywords'    => 'inventory management, stock tracking, warehouse logistics, b2b procurement, supply chain',
                'default_og_image'         => '/assets/inventory/navbarlogo.png',
                'robots_txt_content'       => "User-agent: *\nDisallow: /admin/\nDisallow: /portal/\nDisallow: /login\nDisallow: /register\nAllow: /\n\nSitemap: " . url('/sitemap.xml'),
                'google_analytics_id'      => null,
                'header_scripts'           => null,
                'footer_scripts'           => null,
                'created_at'               => now(),
                'updated_at'               => now(),
            ]);
        } else {
            Schema::table('seo_settings', function (Blueprint $table) {
                if (!Schema::hasColumn('seo_settings', 'header_scripts')) {
                    $table->longText('header_scripts')->nullable();
                }
                if (!Schema::hasColumn('seo_settings', 'footer_scripts')) {
                    $table->longText('footer_scripts')->nullable();
                }
                if (!Schema::hasColumn('seo_settings', 'google_analytics_id')) {
                    $table->string('google_analytics_id')->nullable();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seo_settings');
        Schema::dropIfExists('seo_metas');
    }
};
