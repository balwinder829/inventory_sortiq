<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use Carbon\Carbon;
use DB;

class InvoiceAnalyticsController extends Controller
{
    public function index(Request $request)
    {
        // Default date range
        $startDate = $request->start_date ?? Invoice::min('created_at');
        $endDate   = $request->end_date   ?? Invoice::max('created_at');

        /*
        |--------------------------------------------------------------------------
        | 1. TOTAL REVENUE (SUM OF invoice_items.subtotal)
        |--------------------------------------------------------------------------
        */
        $totalRevenue = DB::table('invoice_items')
            ->join('invoices', 'invoice_items.invoice_id', '=', 'invoices.id')
            ->whereBetween('invoices.created_at', [$startDate, $endDate])
            ->sum('invoice_items.subtotal');

        /*
        |--------------------------------------------------------------------------
        | 2. TOTAL ORDERS
        |--------------------------------------------------------------------------
        */
        $totalOrders = Invoice::whereBetween('created_at', [$startDate, $endDate])->count();

        /*
        |--------------------------------------------------------------------------
        | 3. AVERAGE ORDER VALUE
        |--------------------------------------------------------------------------
        */
        $avgOrderValue = $totalOrders ? $totalRevenue / $totalOrders : 0;

        /*
        |--------------------------------------------------------------------------
        | 4. TOP PRODUCT (using your items table)
        |--------------------------------------------------------------------------
        */
        $topProduct = DB::table('invoice_items')
            ->join('items', 'invoice_items.product_id', '=', 'items.id')
            ->join('invoices', 'invoice_items.invoice_id', '=', 'invoices.id')
            ->whereBetween('invoices.created_at', [$startDate, $endDate])
            ->select(
                'items.name',
                DB::raw('SUM(invoice_items.quantity) as total_qty')
            )
            ->groupBy('items.id', 'items.name')
            ->orderByDesc('total_qty')
            ->first();

        /*
        |--------------------------------------------------------------------------
        | 5. DAILY SALES
        |--------------------------------------------------------------------------
        */
        $dailySales = DB::table('invoice_items')
            ->join('invoices', 'invoice_items.invoice_id', '=', 'invoices.id')
            ->whereBetween('invoices.created_at', [$startDate, $endDate])
            ->selectRaw("DATE(invoices.created_at) as date, SUM(invoice_items.subtotal) as total")
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('total', 'date');

        /*
        |--------------------------------------------------------------------------
        | 6. SALES BY PRODUCT (items table)
        |--------------------------------------------------------------------------
        */
        $salesByProduct = DB::table('invoice_items')
            ->join('items', 'invoice_items.product_id', '=', 'items.id')
            ->join('invoices', 'invoice_items.invoice_id', '=', 'invoices.id')
            ->whereBetween('invoices.created_at', [$startDate, $endDate])
            ->select(
                'items.name',
                DB::raw('SUM(invoice_items.quantity) as total_qty'),
                DB::raw('SUM(invoice_items.subtotal) as total_amount')
            )
            ->groupBy('items.id', 'items.name')
            ->orderByDesc('total_amount')
            ->get();

        // Pie Chart Data
        $revenueShare = $salesByProduct->pluck('total_amount', 'name');

        /*
        |--------------------------------------------------------------------------
        | 7. YEAR OVER YEAR (YoY)
        |--------------------------------------------------------------------------
        */
        $currentYear = now()->year;
        $lastYear    = $currentYear - 1;

        $yoyCurrent = DB::table('invoice_items')
            ->join('invoices', 'invoice_items.invoice_id', '=', 'invoices.id')
            ->whereYear('invoices.created_at', $currentYear)
            ->sum('invoice_items.subtotal');

        $yoyLast = DB::table('invoice_items')
            ->join('invoices', 'invoice_items.invoice_id', '=', 'invoices.id')
            ->whereYear('invoices.created_at', $lastYear)
            ->sum('invoice_items.subtotal');

        /*
        |--------------------------------------------------------------------------
        | 8. MONTH OVER MONTH (MoM)
        |--------------------------------------------------------------------------
        */
        $currentMonth = now()->month;
        $lastMonth    = now()->subMonth()->month;

        $momCurrent = DB::table('invoice_items')
            ->join('invoices', 'invoice_items.invoice_id', '=', 'invoices.id')
            ->whereYear('invoices.created_at', $currentYear)
            ->whereMonth('invoices.created_at', $currentMonth)
            ->sum('invoice_items.subtotal');

        $momLast = DB::table('invoice_items')
            ->join('invoices', 'invoice_items.invoice_id', '=', 'invoices.id')
            ->whereYear('invoices.created_at', $currentYear)
            ->whereMonth('invoices.created_at', $lastMonth)
            ->sum('invoice_items.subtotal');

        /*
        |--------------------------------------------------------------------------
        | 9. DAY OF WEEK SALES
        |--------------------------------------------------------------------------
        */
        $dayOfWeekSales = DB::table('invoice_items')
            ->join('invoices', 'invoice_items.invoice_id', '=', 'invoices.id')
            ->whereBetween('invoices.created_at', [$startDate, $endDate])
            ->selectRaw("DAYNAME(invoices.created_at) as day, SUM(invoice_items.subtotal) as total")
            ->groupBy('day')
            ->orderByRaw("FIELD(day, 'Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday')")
            ->pluck('total', 'day');

        /*
        |--------------------------------------------------------------------------
        | 10. LEAST SELLING PRODUCTS (using items table)
        |--------------------------------------------------------------------------
        */
        $leastSellingProducts = DB::table('invoice_items')
            ->join('items', 'invoice_items.product_id', '=', 'items.id')
            ->join('invoices', 'invoice_items.invoice_id', '=', 'invoices.id')
            ->whereBetween('invoices.created_at', [$startDate, $endDate])
            ->select(
                'items.name',
                DB::raw('SUM(invoice_items.subtotal) as total_amount')
            )
            ->groupBy('items.id', 'items.name')
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
