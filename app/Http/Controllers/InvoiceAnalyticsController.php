<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\Expense;
use App\Models\Purchase;
use App\Models\Item;
use App\Models\Store;
use App\Models\SupplierReturn;
use App\Models\ReturnItem;
use DB;
use Carbon\Carbon;

class InvoiceAnalyticsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:invoices_analytics.view')->only(['index']);
    }

    public function index(Request $request)
    {
        $user        = auth()->user();
        $isSuperAdmin = $user->is_superadmin;

        /*
        |--------------------------------------------------------------------------
        | STORE SCOPING
        |--------------------------------------------------------------------------
        */
        if ($isSuperAdmin) {
            $allStores   = Store::all();
            $myStoreIds  = $allStores->pluck('id')->toArray();
        } else {
            $allStores  = $user->stores;
            $myStoreIds = $allStores->pluck('id')->toArray();
        }

        // Selected store from filter
        $selectedStore = $request->store;
        if ($selectedStore && !$isSuperAdmin && !in_array($selectedStore, $myStoreIds)) {
            $selectedStore = null;
        }

        /*
        |--------------------------------------------------------------------------
        | DATE RANGE
        |--------------------------------------------------------------------------
        */
        $startDate = $request->start_date;
        $endDate   = $request->end_date;

        if ($request->range) {
            switch ($request->range) {
                case 'today':
                    $startDate = $endDate = today()->toDateString();
                    break;
                case '7days':
                    $startDate = today()->subDays(6)->toDateString();
                    $endDate   = today()->toDateString();
                    break;
                case 'month':
                    $startDate = today()->startOfMonth()->toDateString();
                    $endDate   = today()->endOfMonth()->toDateString();
                    break;
                case 'last_month':
                    $startDate = today()->subMonth()->startOfMonth()->toDateString();
                    $endDate   = today()->subMonth()->endOfMonth()->toDateString();
                    break;
                case 'year':
                    $startDate = today()->startOfYear()->toDateString();
                    $endDate   = today()->toDateString();
                    break;
            }
        }

        if (!$startDate) $startDate = Invoice::min('invoice_date') ?? today()->subMonth()->toDateString();
        if (!$endDate)   $endDate   = today()->toDateString();

        /*
        |--------------------------------------------------------------------------
        | BASE INVOICE QUERY (store-scoped)
        |--------------------------------------------------------------------------
        */
        $storeFilterIds = $selectedStore ? [$selectedStore] : $myStoreIds;

        $invoiceIdsFromStore = DB::table('invoice_items')
            ->join('items', 'invoice_items.product_id', '=', 'items.id')
            ->whereIn('items.store_id', $storeFilterIds)
            ->pluck('invoice_items.invoice_id')
            ->unique()
            ->toArray();

        $invoiceBaseQ = Invoice::whereIn('id', $invoiceIdsFromStore)
            ->whereBetween('invoice_date', [$startDate, $endDate]);

        // Additional filters
        if ($request->customer) {
            $invoiceBaseQ->where(function ($q) use ($request) {
                $q->where('customer_name', 'LIKE', "%{$request->customer}%")
                  ->orWhere('customer_mobile', 'LIKE', "%{$request->customer}%");
            });
        }
        if ($request->payment_mode) {
            $invoiceBaseQ->where('payment_mode', $request->payment_mode);
        }
        if ($request->product_id) {
            $pidInvoices = DB::table('invoice_items')
                ->where('product_id', $request->product_id)
                ->pluck('invoice_id')->toArray();
            $invoiceBaseQ->whereIn('id', $pidInvoices);
        }

        $invoices   = $invoiceBaseQ->get();
        $invoiceIds = $invoices->pluck('id');

        /*
        |--------------------------------------------------------------------------
        | KPI - REVENUE & PROFIT
        |--------------------------------------------------------------------------
        */
        $totalRevenue    = $invoices->sum('total_amount');
        $totalProfit     = $invoices->sum('total_profit');
        $totalOrders     = $invoices->count();
        $avgOrderValue   = $totalOrders ? round($totalRevenue / $totalOrders, 2) : 0;
        $profitMargin    = $totalRevenue > 0 ? round(($totalProfit / $totalRevenue) * 100, 1) : 0;

        // Today
        $todayRevenue = Invoice::whereIn('id', $invoiceIdsFromStore)->whereDate('invoice_date', today())->sum('total_amount');
        $todayProfit  = Invoice::whereIn('id', $invoiceIdsFromStore)->whereDate('invoice_date', today())->sum('total_profit');

        // This month
        $monthRevenue = Invoice::whereIn('id', $invoiceIdsFromStore)
            ->whereYear('invoice_date', now()->year)
            ->whereMonth('invoice_date', now()->month)
            ->sum('total_amount');
        $monthProfit = Invoice::whereIn('id', $invoiceIdsFromStore)
            ->whereYear('invoice_date', now()->year)
            ->whereMonth('invoice_date', now()->month)
            ->sum('total_profit');

        /*
        |--------------------------------------------------------------------------
        | EXPENSES (store-scoped)
        |--------------------------------------------------------------------------
        */
        $expenseStoreIds = $selectedStore ? [$selectedStore] : $myStoreIds;

        $totalExpenses    = Expense::whereIn('store_id', $expenseStoreIds)
            ->whereBetween('expense_date', [$startDate, $endDate])
            ->sum('amount');

        $netIncome = $totalProfit - $totalExpenses;

        // Expense by category
        $expenseByCategory = Expense::whereIn('store_id', $expenseStoreIds)
            ->whereBetween('expense_date', [$startDate, $endDate])
            ->select('category', DB::raw('SUM(amount) as total'))
            ->groupBy('category')
            ->orderByDesc('total')
            ->get();

        // Monthly expenses (last 6 months)
        $monthlyExpenses = Expense::whereIn('store_id', $expenseStoreIds)
            ->where('expense_date', '>=', now()->subMonths(5)->startOfMonth())
            ->select(
                DB::raw("DATE_FORMAT(expense_date, '%b %Y') as month"),
                DB::raw("DATE_FORMAT(expense_date, '%Y-%m') as ym"),
                DB::raw('SUM(amount) as total')
            )
            ->groupBy('month', 'ym')
            ->orderBy('ym')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | PURCHASES (store-scoped via store_id on purchases table)
        |--------------------------------------------------------------------------
        */
        $totalPurchases = Purchase::whereIn('store_id', $expenseStoreIds)
            ->whereBetween('purchase_date', [$startDate, $endDate])
            ->sum('total_amount');

        $purchaseCount = Purchase::whereIn('store_id', $expenseStoreIds)
            ->whereBetween('purchase_date', [$startDate, $endDate])
            ->count();

        /*
        |--------------------------------------------------------------------------
        | DAILY REVENUE + PROFIT CHART
        |--------------------------------------------------------------------------
        */
        $dailySales = Invoice::whereIn('id', $invoiceIds)
            ->select(
                DB::raw('DATE(invoice_date) as date'),
                DB::raw('SUM(total_amount) as revenue'),
                DB::raw('SUM(total_profit) as profit')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | MONTHLY REVENUE (last 12 months)
        |--------------------------------------------------------------------------
        */
        $monthlySales = Invoice::whereIn('id', $invoiceIdsFromStore)
            ->where('invoice_date', '>=', now()->subMonths(11)->startOfMonth())
            ->select(
                DB::raw("DATE_FORMAT(invoice_date, '%b %Y') as month"),
                DB::raw("DATE_FORMAT(invoice_date, '%Y-%m') as ym"),
                DB::raw('SUM(total_amount) as revenue'),
                DB::raw('SUM(total_profit) as profit')
            )
            ->groupBy('month', 'ym')
            ->orderBy('ym')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | TOP SELLING PRODUCTS
        |--------------------------------------------------------------------------
        */
        $salesByProduct = DB::table('invoice_items')
            ->join('items', 'invoice_items.product_id', '=', 'items.id')
            ->whereIn('invoice_items.invoice_id', $invoiceIds)
            ->select(
                'items.name',
                DB::raw('SUM(invoice_items.quantity) as total_qty'),
                DB::raw('SUM(invoice_items.subtotal) as total_amount'),
                DB::raw('SUM(invoice_items.profit) as total_profit')
            )
            ->groupBy('items.id', 'items.name')
            ->orderByDesc('total_amount')
            ->limit(10)
            ->get();

        // Least selling (bottom 5)
        $leastSellingProducts = DB::table('invoice_items')
            ->join('items', 'invoice_items.product_id', '=', 'items.id')
            ->whereIn('invoice_items.invoice_id', $invoiceIds)
            ->select('items.name', DB::raw('SUM(invoice_items.quantity) as total_qty'))
            ->groupBy('items.id', 'items.name')
            ->orderBy('total_qty')
            ->limit(5)
            ->get();

        $revenueShare = $salesByProduct->pluck('total_amount', 'name');

        /*
        |--------------------------------------------------------------------------
        | PAYMENT MODE BREAKDOWN
        |--------------------------------------------------------------------------
        */
        $paymentModeData = Invoice::whereIn('id', $invoiceIds)
            ->select('payment_mode', DB::raw('COUNT(*) as count'), DB::raw('SUM(total_amount) as total'))
            ->groupBy('payment_mode')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | DAY OF WEEK
        |--------------------------------------------------------------------------
        */
        $dayNames = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        $rawDay   = Invoice::whereIn('id', $invoiceIds)
            ->selectRaw("DAYNAME(invoice_date) as day, SUM(total_amount) as total")
            ->groupBy('day')
            ->pluck('total', 'day')
            ->toArray();

        $dayOfWeekSales = collect($dayNames)->mapWithKeys(fn($day) => [$day => round($rawDay[$day] ?? 0, 2)]);

        /*
        |--------------------------------------------------------------------------
        | HOUR OF DAY (peak hours)
        |--------------------------------------------------------------------------
        */
        $hourData = Invoice::whereIn('id', $invoiceIds)
            ->selectRaw("HOUR(created_at) as hr, COUNT(*) as cnt")
            ->groupBy('hr')
            ->pluck('cnt', 'hr')
            ->toArray();

        $hourOfDaySales = [];
        for ($h = 0; $h < 24; $h++) {
            $hourOfDaySales[] = $hourData[$h] ?? 0;
        }

        /*
        |--------------------------------------------------------------------------
        | MoM & YoY
        |--------------------------------------------------------------------------
        */
        $year = now()->year;
        $lastYear = $year - 1;

        $yoyCurrent = Invoice::whereIn('id', $invoiceIdsFromStore)->whereYear('invoice_date', $year)->sum('total_amount');
        $yoyLast    = Invoice::whereIn('id', $invoiceIdsFromStore)->whereYear('invoice_date', $lastYear)->sum('total_amount');

        $momCurrent = Invoice::whereIn('id', $invoiceIdsFromStore)
            ->whereYear('invoice_date', $year)->whereMonth('invoice_date', now()->month)->sum('total_amount');
        $momLast = Invoice::whereIn('id', $invoiceIdsFromStore)
            ->whereYear('invoice_date', $year)->whereMonth('invoice_date', now()->subMonth()->month)->sum('total_amount');

        $yoyGrowth = $yoyLast > 0 ? round((($yoyCurrent - $yoyLast) / $yoyLast) * 100, 1) : 0;
        $momGrowth = $momLast > 0 ? round((($momCurrent - $momLast) / $momLast) * 100, 1) : 0;

        /*
        |--------------------------------------------------------------------------
        | CUSTOMER ANALYTICS
        |--------------------------------------------------------------------------
        */
        $topBuyers = $invoices
            ->groupBy('customer_mobile')
            ->map(fn($g) => (object)[
                'customer_name'   => $g->first()->customer_name ?? 'Walk-in',
                'customer_mobile' => $g->first()->customer_mobile,
                'total_orders'    => $g->count(),
                'total_spent'     => $g->sum('total_amount'),
            ])
            ->sortByDesc('total_spent')
            ->take(10)
            ->values();

        $topSpenders = $invoices
            ->groupBy('customer_mobile')
            ->map(fn($g) => (object)[
                'customer_name'   => $g->first()->customer_name ?? 'Walk-in',
                'customer_mobile' => $g->first()->customer_mobile,
                'total_spent'     => $g->sum('total_amount'),
                'total_orders'    => $g->count(),
            ])
            ->sortByDesc('total_spent')
            ->take(10)
            ->values();

        $buyerOfMonth = Invoice::whereIn('id', $invoiceIdsFromStore)
            ->whereMonth('invoice_date', now()->month)
            ->whereYear('invoice_date', now()->year)
            ->select('customer_name', 'customer_mobile', DB::raw('SUM(total_amount) AS month_spent'), DB::raw('COUNT(*) as orders'))
            ->groupBy('customer_mobile', 'customer_name')
            ->orderByDesc('month_spent')
            ->first();

        $uniqueCustomers = $invoices->pluck('customer_mobile')->filter()->unique()->count();

        /*
        |--------------------------------------------------------------------------
        | STORE COMPARISON (superadmin only)
        |--------------------------------------------------------------------------
        */
        $storeComparison = [];
        if ($isSuperAdmin) {
            $storeComparison = DB::table('invoices')
                ->join('invoice_items', 'invoices.id', '=', 'invoice_items.invoice_id')
                ->join('items', 'invoice_items.product_id', '=', 'items.id')
                ->join('stores', 'items.store_id', '=', 'stores.id')
                ->whereBetween('invoices.invoice_date', [$startDate, $endDate])
                ->select(
                    'stores.name as store_name',
                    DB::raw('SUM(invoices.total_amount) as revenue'),
                    DB::raw('SUM(invoices.total_profit) as profit')
                )
                ->groupBy('stores.id', 'stores.name')
                ->orderByDesc('revenue')
                ->get();
        }

        /*
        |--------------------------------------------------------------------------
        | STOCK HEALTH
        |--------------------------------------------------------------------------
        */
        $limit = config('app.low_stock_limit', 10);
        $stockQuery = Item::whereIn('store_id', $expenseStoreIds);

        $stockInStock   = (clone $stockQuery)->where('quantity', '>', $limit)->count();
        $stockLow       = (clone $stockQuery)->where('quantity', '>', 0)->where('quantity', '<=', $limit)->count();
        $stockOut       = (clone $stockQuery)->where('quantity', '=', 0)->count();
        $totalStockValue = (clone $stockQuery)->selectRaw('SUM(quantity * cost_price) as val')->first()->val ?? 0;

        // Products with no sales in period
        $soldProductIds = DB::table('invoice_items')
            ->whereIn('invoice_id', $invoiceIds)
            ->pluck('product_id')
            ->unique()
            ->toArray();

        $deadStockCount = Item::whereIn('store_id', $expenseStoreIds)
            ->where('quantity', '>', 0)
            ->whereNotIn('id', $soldProductIds)
            ->count();

        /*
        |--------------------------------------------------------------------------
        | RETURNS DATA
        |--------------------------------------------------------------------------
        */
        $customerReturnsTotal = DB::table('return_items')
            ->join('invoices', 'return_items.invoice_id', '=', 'invoices.id')
            ->join('invoice_items', 'return_items.invoice_item_id', '=', 'invoice_items.id')
            ->join('items', 'invoice_items.product_id', '=', 'items.id')
            ->whereIn('items.store_id', $expenseStoreIds)
            ->whereBetween('return_items.created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->sum('return_items.total_refund_amount');

        $supplierReturnsTotal = SupplierReturn::whereIn('store_id', $expenseStoreIds)
            ->whereBetween('return_date', [$startDate, $endDate])
            ->sum('total_refund_amount');

        /*
        |--------------------------------------------------------------------------
        | RECENT INVOICES TABLE
        |--------------------------------------------------------------------------
        */
        $recentInvoices = Invoice::whereIn('id', $invoiceIds)
            ->latest('invoice_date')
            ->take(10)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | PRODUCTS LIST FOR FILTER
        |--------------------------------------------------------------------------
        */
        $productsList = Item::whereIn('store_id', $expenseStoreIds)->orderBy('name')->get();

        return view('admin.analytics.index', compact(
            // Meta
            'startDate', 'endDate', 'allStores', 'selectedStore', 'isSuperAdmin',
            // KPIs
            'totalRevenue', 'totalProfit', 'totalOrders', 'avgOrderValue', 'profitMargin',
            'todayRevenue', 'todayProfit', 'monthRevenue', 'monthProfit',
            // Expenses
            'totalExpenses', 'netIncome', 'expenseByCategory', 'monthlyExpenses',
            // Purchases
            'totalPurchases', 'purchaseCount',
            // Revenue/Profit charts
            'dailySales', 'monthlySales',
            // Products
            'salesByProduct', 'leastSellingProducts', 'revenueShare',
            // Payment mode
            'paymentModeData',
            // Time analysis
            'dayOfWeekSales', 'hourOfDaySales',
            // MoM/YoY
            'yoyCurrent', 'yoyLast', 'momCurrent', 'momLast', 'yoyGrowth', 'momGrowth',
            // Customers
            'topBuyers', 'topSpenders', 'buyerOfMonth', 'uniqueCustomers',
            // Store comparison
            'storeComparison',
            // Stock
            'stockInStock', 'stockLow', 'stockOut', 'totalStockValue', 'deadStockCount',
            // Returns
            'customerReturnsTotal', 'supplierReturnsTotal',
            // Table
            'recentInvoices', 'productsList'
        ));
    }
}
