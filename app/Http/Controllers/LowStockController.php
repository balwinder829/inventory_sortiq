<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Support\Facades\Session;

class LowStockController extends Controller
{
    public function __construct()
    {
       $this->middleware('auth');
       $this->middleware('permission:products.view|store_management.view')->only(['index','show']);
    }
    public function index()
    {
        // $products = Item::lowStock()->paginate(10);
        $products = Item::storeAccess()->lowStock()->paginate(10);
        return view('admin.low_stock.index', compact('products'));
    }

    public function dismiss()
    {
        // Remember dismissal until next login
        Session::forget('low_stock_alert');
        Session::put('dismiss_low_stock_alert', true);

        return response()->json(['status' => 'ok']);
    }
}
