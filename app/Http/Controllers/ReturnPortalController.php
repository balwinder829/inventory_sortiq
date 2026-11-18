<?php

namespace App\Http\Controllers;

use App\Models\ReturnItem;
use Illuminate\Http\Request;

class ReturnPortalController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of Customer (Sales) Returns.
     */
    public function customerIndex()
    {
        // Query returns with relations
        $returns = ReturnItem::with(['invoice', 'product'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.returns.customer.index', compact('returns'));
    }
}
