<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\Item;
use Carbon\Carbon;
use DB;
use App\Models\WooProduct;

class InvoiceAnalyticsController extends Controller
{
    public function index(Request $request)
{
    // ✅ Default to full range if no filter
    $startDate = $request->start_date ?? Invoice::min('created_at');
    $endDate   = $request->end_date   ?? Invoice::max('created_at');

    // Base query
    $query = Invoice::whereBetween('created_at', [$startDate, $endDate]);

    // 🔹 Total Revenue
    $totalRevenue = (clone $query)->sum(DB::raw('price * quantity'));

    // 🔹 Total Orders
    $totalOrders = (clone $query)->count();

    // 🔹 Average Order Value
    $avgOrderValue = $totalOrders ? $totalRevenue / $totalOrders : 0;

    // 🔹 Top Product (by quantity)
    $topProduct = Invoice::join('woocommerce_products', 'invoices.product_id', '=', 'woocommerce_products.id')
        ->whereBetween('invoices.created_at', [$startDate, $endDate])
        ->select('woocommerce_products.name as name', DB::raw('SUM(invoices.quantity) as total_qty'))
        ->groupBy('woocommerce_products.id', 'woocommerce_products.name')
        ->orderByDesc('total_qty')
        ->first();

    // 🔹 Daily Sales (line chart)
    $dailySales = (clone $query)
        ->selectRaw('DATE(created_at) as date, SUM(price*quantity) as total')
        ->groupBy('date')
        ->orderBy('date')
        ->pluck('total', 'date');

    // 🔹 Sales by Product
    $salesByProduct = Invoice::join('woocommerce_products', 'invoices.product_id', '=', 'woocommerce_products.id')
        ->whereBetween('invoices.created_at', [$startDate, $endDate])
        ->select(
            'woocommerce_products.name as name',
            DB::raw('SUM(invoices.quantity) as total_qty'),
            DB::raw('SUM(invoices.price*invoices.quantity) as total_amount')
        )
        ->groupBy('woocommerce_products.id', 'woocommerce_products.name')
        ->orderByDesc('total_amount')
        ->get();

    $revenueShare = $salesByProduct->pluck('total_amount', 'name');

    // 🔹 YoY Revenue
    $currentYear = Carbon::now()->year;
    $lastYear    = $currentYear - 1;

    $yoyCurrent = Invoice::whereYear('created_at', $currentYear)
        ->sum(DB::raw('price*quantity'));
    $yoyLast = Invoice::whereYear('created_at', $lastYear)
        ->sum(DB::raw('price*quantity'));

    // 🔹 MoM Revenue
    $currentMonth = Carbon::now()->month;
    $lastMonth    = Carbon::now()->subMonth()->month;

    $momCurrent = Invoice::whereYear('created_at', $currentYear)
        ->whereMonth('created_at', $currentMonth)
        ->sum(DB::raw('price*quantity'));

    $momLast = Invoice::whereYear('created_at', $currentYear)
        ->whereMonth('created_at', $lastMonth)
        ->sum(DB::raw('price*quantity'));

    // 🔹 Day of Week Sales
    $dayOfWeekSales = Invoice::whereBetween('invoices.created_at', [$startDate, $endDate])
    ->selectRaw("DAYNAME(invoices.created_at) as day, SUM(price * quantity) as total")
    ->groupBy('day') // ✅ alias instead of repeating the function
    ->orderByRaw("FIELD(day, 'Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday')")
    ->pluck('total', 'day');


    // 🔹 Least Selling Products
    $leastSellingProducts = Invoice::join('woocommerce_products', 'invoices.product_id', '=', 'woocommerce_products.id')
        ->whereBetween('invoices.created_at', [$startDate, $endDate])
        ->select(
            'woocommerce_products.name as name',
            DB::raw('SUM(invoices.price*invoices.quantity) as total_amount')
        )
        ->groupBy('woocommerce_products.id', 'woocommerce_products.name')
        ->orderBy('total_amount', 'asc')
        ->limit(5)
        ->get();

    return view('admin.analytics.index', compact(
        'totalRevenue',
        'totalOrders',
        'avgOrderValue',
        'topProduct',
        'dailySales',
        'salesByProduct',
        'revenueShare',
        'startDate',
        'endDate',
        'yoyCurrent',
        'yoyLast',
        'momCurrent',
        'momLast',
        'dayOfWeekSales',
        'leastSellingProducts'
    ));
}

}
