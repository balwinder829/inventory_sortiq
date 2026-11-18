<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FrontendController extends Controller
{
    /**
     * Display the StockFlow Sourcing Portal Homepage.
     */
    public function index()
    {
        return view('inventory-frontend.index');
    }

    /**
     * Display the About Us page.
     */
    public function about()
    {
        return view('inventory-frontend.about');
    }

    /**
     * Display the System Capabilities & Features page.
     */
    public function features()
    {
        return view('inventory-frontend.features');
    }

    /**
     * Display the Connected Pipeline Modules page.
     */
    public function modules()
    {
        return view('inventory-frontend.modules');
    }

    /**
     * Display the Operational Stock Catalog page.
     */
    public function products()
    {
        return view('inventory-frontend.products');
    }

    /**
     * Display the Sourcing Procurement Tiers & Pricing page.
     */
    public function pricing()
    {
        return view('inventory-frontend.pricing');
    }

    /**
     * Display the Inventory Helpdesk & Support page.
     */
    public function contact()
    {
        return view('inventory-frontend.contact');
    }

    /**
     * Display the Latest Logistics Insights & Blogs page.
     */
    public function blogs()
    {
        return view('inventory-frontend.blogs');
    }

    /**
     * Display the Registered Buyer / Operator Dashboard.
     */
    public function dashboard()
    {
        return view('inventory-frontend.dashboard');
    }

    /**
     * Display the Portal Operator Login page.
     */
    public function login()
    {
        return view('inventory-frontend.login');
    }

    /**
     * Display the Institutional Profile Registration page.
     */
    public function signup()
    {
        return view('inventory-frontend.signup');
    }
}
