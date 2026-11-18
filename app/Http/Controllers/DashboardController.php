<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Item;
use App\Models\Store;
use App\Models\Expense;
use App\Models\Purchase;
use App\Models\SupplierReturn;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:dashboard.view')->only('index');
    }

    public function index()
    {
        $user         = auth()->user();
        $isSuperAdmin = $user->is_superadmin;

        /*
        |--------------------------------------------------------------------------
        | STORE SCOPING
        |--------------------------------------------------------------------------
        */
        $allStores = $isSuperAdmin ? Store::all() : $user->stores;
        $selectedStoreId = request()->query('store_id');
        $startDate = request()->query('start_date');
        $endDate = request()->query('end_date');

        // Validate non-superadmin can't spoof store_id
        if ($selectedStoreId && !$isSuperAdmin && !$allStores->contains('id', $selectedStoreId)) {
            $selectedStoreId = null;
        }

        $activeStoreIds = $selectedStoreId
            ? collect([$selectedStoreId])
            : $allStores->pluck('id');

        /*
        |--------------------------------------------------------------------------
        | DEFAULT METRIC VALUES (Safety Fallback)
        |--------------------------------------------------------------------------
        */
        $totalRevenue = 0; $totalProfit = 0; $totalOrders = 0; $avgOrderValue = 0; $profitMargin = 0;
        $todayRevenue = 0; $todayProfit = 0; $todayOrders = 0;
        $monthRevenue = 0; $monthProfit = 0; $monthOrders = 0;
        $lastMonthRevenue = 0; $revenueGrowth = 0; $profitGrowth = 0;
        $totalProducts = 0; $inStockCount = 0; $lowStockCount = 0; $outOfStockCount = 0; $totalStockValue = 0;
        $totalExpenses = 0; $monthExpenses = 0; $netIncome = 0; $expenseByCategory = collect();
        $purchaseTotal = 0; $purchaseCount = 0;
        $monthlySales = collect(); $dailySales = collect();
        $topProducts = collect(); $topProductToday = null;
        $paymentBreakdown = collect();
        $supplierReturnsTotal = 0;
        $recentInvoices = collect();

        try {
            /*
            |--------------------------------------------------------------------------
            | INVOICE QUERY BASE — scoped via invoice_items → items → store_id
            |--------------------------------------------------------------------------
            */
            if ($isSuperAdmin && !$selectedStoreId) {
                $scopedInvoiceIds = Invoice::pluck('id');
            } else {
                $scopedInvoiceIds = DB::table('invoice_items')
                    ->join('items', 'invoice_items.product_id', '=', 'items.id')
                    ->whereIn('items.store_id', $activeStoreIds)
                    ->pluck('invoice_items.invoice_id')
                    ->unique();
            }

            $invoiceBase = Invoice::whereIn('id', $scopedInvoiceIds);

            $filteredInvoiceBase = clone $invoiceBase;
            if ($startDate) {
                $filteredInvoiceBase->whereDate('invoice_date', '>=', $startDate);
            }
            if ($endDate) {
                $filteredInvoiceBase->whereDate('invoice_date', '<=', $endDate);
            }

            /*
            |--------------------------------------------------------------------------
            | PRODUCTS QUERY BASE
            |--------------------------------------------------------------------------
            */
            $productsBase = $isSuperAdmin && !$selectedStoreId
                ? Item::query()
                : Item::whereIn('store_id', $activeStoreIds);

            /*
            |--------------------------------------------------------------------------
            | KPI NUMBERS
            |--------------------------------------------------------------------------
            */
            $totalRevenue   = (clone $filteredInvoiceBase)->sum('total_amount');
            $totalProfit    = (clone $filteredInvoiceBase)->sum('total_profit');
            $totalOrders    = (clone $filteredInvoiceBase)->count();
            $avgOrderValue  = $totalOrders > 0 ? round($totalRevenue / $totalOrders, 2) : 0;
            $profitMargin   = $totalRevenue > 0 ? round(($totalProfit / $totalRevenue) * 100, 1) : 0;

            // Today
            $todayRevenue = (clone $invoiceBase)->whereDate('invoice_date', today())->sum('total_amount');
            $todayProfit  = (clone $invoiceBase)->whereDate('invoice_date', today())->sum('total_profit');
            $todayOrders  = (clone $invoiceBase)->whereDate('invoice_date', today())->count();

            // This month
            $monthRevenue = (clone $invoiceBase)->whereYear('invoice_date', now()->year)->whereMonth('invoice_date', now()->month)->sum('total_amount');
            $monthProfit  = (clone $invoiceBase)->whereYear('invoice_date', now()->year)->whereMonth('invoice_date', now()->month)->sum('total_profit');
            $monthOrders  = (clone $invoiceBase)->whereYear('invoice_date', now()->year)->whereMonth('invoice_date', now()->month)->count();

            // Last month (for comparison)
            $lastMonthRevenue = (clone $invoiceBase)->whereYear('invoice_date', now()->year)->whereMonth('invoice_date', now()->subMonth()->month)->sum('total_amount');
            $lastMonthProfit  = (clone $invoiceBase)->whereYear('invoice_date', now()->year)->whereMonth('invoice_date', now()->subMonth()->month)->sum('total_profit');

            $revenueGrowth = $lastMonthRevenue > 0 ? round((($monthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100, 1) : 0;
            $profitGrowth  = $lastMonthProfit  > 0 ? round((($monthProfit - $lastMonthProfit)   / $lastMonthProfit)  * 100, 1) : 0;

            /*
            |--------------------------------------------------------------------------
            | STOCK HEALTH
            |--------------------------------------------------------------------------
            */
            $limit         = config('app.low_stock_limit', 10);
            $totalProducts = (clone $productsBase)->count();
            $inStockCount  = (clone $productsBase)->where('quantity', '>', $limit)->count();
            $lowStockCount = (clone $productsBase)->where('quantity', '>', 0)->where('quantity', '<=', $limit)->count();
            $outOfStockCount = (clone $productsBase)->where('quantity', '=', 0)->count();
            $totalStockValue = (clone $productsBase)->selectRaw('SUM(quantity * cost_price) as val')->first()->val ?? 0;

            /*
            |--------------------------------------------------------------------------
            | EXPENSES (store-scoped)
            |--------------------------------------------------------------------------
            */
            $expenseStoreIds = $isSuperAdmin && !$selectedStoreId
                ? Store::pluck('id')
                : $activeStoreIds;

            $expensesQuery = Expense::whereIn('store_id', $expenseStoreIds);
            if ($startDate) {
                $expensesQuery->whereDate('expense_date', '>=', $startDate);
            }
            if ($endDate) {
                $expensesQuery->whereDate('expense_date', '<=', $endDate);
            }
            $totalExpenses   = $expensesQuery->sum('amount');

            $monthExpenses   = Expense::whereIn('store_id', $expenseStoreIds)
                ->whereYear('expense_date', now()->year)
                ->whereMonth('expense_date', now()->month)
                ->sum('amount');

            $netIncome = $totalProfit - $totalExpenses;

            /*
            |--------------------------------------------------------------------------
            | PURCHASES
            |--------------------------------------------------------------------------
            */
            $purchaseBase  = $isSuperAdmin && !$selectedStoreId
                ? Purchase::query()
                : Purchase::whereIn('created_by', $user->id ? [$user->id] : [0]);

            if ($startDate) {
                $purchaseBase->whereDate('purchase_date', '>=', $startDate);
            }
            if ($endDate) {
                $purchaseBase->whereDate('purchase_date', '<=', $endDate);
            }

            $purchaseTotal = (clone $purchaseBase)->sum('total_amount');
            $purchaseCount = (clone $purchaseBase)->count();

            /*
            |--------------------------------------------------------------------------
            | MONTHLY REVENUE & PROFIT (last 12 months) for chart
            |--------------------------------------------------------------------------
            */
            $monthlySalesQuery = Invoice::whereIn('id', $scopedInvoiceIds);
            if ($startDate) {
                $monthlySalesQuery->where('invoice_date', '>=', \Carbon\Carbon::parse($startDate)->startOfMonth());
            } else {
                $monthlySalesQuery->where('invoice_date', '>=', now()->subMonths(11)->startOfMonth());
            }
            if ($endDate) {
                $monthlySalesQuery->where('invoice_date', '<=', \Carbon\Carbon::parse($endDate)->endOfMonth());
            }

            $monthlySales = $monthlySalesQuery
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
            | DAILY SALES (last 30 days) for chart
            |--------------------------------------------------------------------------
            */
            $dailySalesQuery = Invoice::whereIn('id', $scopedInvoiceIds);
            if ($startDate) {
                $dailySalesQuery->where('invoice_date', '>=', $startDate);
            } else {
                $dailySalesQuery->where('invoice_date', '>=', now()->subDays(29));
            }
            if ($endDate) {
                $dailySalesQuery->where('invoice_date', '<=', $endDate);
            }

            $dailySales = $dailySalesQuery
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
            | TOP SELLING PRODUCTS (this month)
            |--------------------------------------------------------------------------
            */
            if ($startDate || $endDate) {
                $topProductsInvoiceIds = (clone $filteredInvoiceBase)->pluck('id');
            } else {
                $topProductsInvoiceIds = (clone $invoiceBase)
                    ->whereYear('invoice_date', now()->year)
                    ->whereMonth('invoice_date', now()->month)
                    ->pluck('id');
            }

            $topProducts = DB::table('invoice_items')
                ->join('items', 'invoice_items.product_id', '=', 'items.id')
                ->whereIn('invoice_items.invoice_id', $topProductsInvoiceIds)
                ->select(
                    'items.name',
                    DB::raw('SUM(invoice_items.quantity) as total_qty'),
                    DB::raw('SUM(invoice_items.subtotal) as total_revenue'),
                    DB::raw('SUM(invoice_items.profit) as total_profit')
                )
                ->groupBy('items.id', 'items.name')
                ->orderByDesc('total_revenue')
                ->limit(5)
                ->get();

            // Top product today
            $topProductToday = DB::table('invoice_items')
                ->join('invoices', 'invoice_items.invoice_id', '=', 'invoices.id')
                ->join('items', 'invoice_items.product_id', '=', 'items.id')
                ->whereDate('invoices.invoice_date', today())
                ->whereIn('items.store_id', $activeStoreIds)
                ->select('items.name', DB::raw('SUM(invoice_items.quantity) as qty'))
                ->groupBy('invoice_items.product_id', 'items.name')
                ->orderByDesc('qty')
                ->first();

            /*
            |--------------------------------------------------------------------------
            | PAYMENT MODE BREAKDOWN
            |--------------------------------------------------------------------------
            */
            $paymentBreakdownQuery = clone $filteredInvoiceBase;
            if (!$startDate && !$endDate) {
                $paymentBreakdownQuery->whereYear('invoice_date', now()->year)
                    ->whereMonth('invoice_date', now()->month);
            }

            $paymentBreakdown = $paymentBreakdownQuery
                ->select('payment_mode', DB::raw('COUNT(*) as count'), DB::raw('SUM(total_amount) as total'))
                ->groupBy('payment_mode')
                ->get();

            /*
            |--------------------------------------------------------------------------
            | EXPENSE BY CATEGORY (this month)
            |--------------------------------------------------------------------------
            */
            $expenseByCategoryQuery = Expense::whereIn('store_id', $expenseStoreIds);
            if ($startDate || $endDate) {
                if ($startDate) {
                    $expenseByCategoryQuery->whereDate('expense_date', '>=', $startDate);
                }
                if ($endDate) {
                    $expenseByCategoryQuery->whereDate('expense_date', '<=', $endDate);
                }
            } else {
                $expenseByCategoryQuery->whereYear('expense_date', now()->year)
                    ->whereMonth('expense_date', now()->month);
            }

            $expenseByCategory = $expenseByCategoryQuery
                ->select('category', DB::raw('SUM(amount) as total'))
                ->groupBy('category')
                ->get();

            /*
            |--------------------------------------------------------------------------
            | RETURNS
            |--------------------------------------------------------------------------
            */
            $supplierReturnsQuery = SupplierReturn::whereIn('store_id', $expenseStoreIds);
            if ($startDate) {
                $supplierReturnsQuery->whereDate('return_date', '>=', $startDate);
            }
            if ($endDate) {
                $supplierReturnsQuery->whereDate('return_date', '<=', $endDate);
            }
            $supplierReturnsTotal = $supplierReturnsQuery->sum('total_refund_amount');

            /*
            |--------------------------------------------------------------------------
            | RECENT INVOICES
            |--------------------------------------------------------------------------
            */
            $recentInvoices = (clone $filteredInvoiceBase)->latest('invoice_date')->take(8)->get();

        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('Dashboard query fallback: ' . $e->getMessage());
        }

        /*
        |--------------------------------------------------------------------------
        | STORES COUNT
        |--------------------------------------------------------------------------
        */
        $storesCount = $isSuperAdmin ? Store::count() : $allStores->count();

        return view('admin.dashboard_new', compact(
            // Store info
            'allStores', 'selectedStoreId', 'isSuperAdmin', 'storesCount',
            // KPIs
            'totalRevenue', 'totalProfit', 'totalOrders', 'avgOrderValue', 'profitMargin',
            'todayRevenue', 'todayProfit', 'todayOrders',
            'monthRevenue', 'monthProfit', 'monthOrders',
            'lastMonthRevenue', 'revenueGrowth', 'profitGrowth',
            // Stock
            'totalProducts', 'inStockCount', 'lowStockCount', 'outOfStockCount', 'totalStockValue',
            // Expenses
            'totalExpenses', 'monthExpenses', 'netIncome', 'expenseByCategory',
            // Purchases
            'purchaseTotal', 'purchaseCount',
            // Charts
            'monthlySales', 'dailySales',
            // Products
            'topProducts', 'topProductToday',
            // Payment
            'paymentBreakdown',
            // Returns
            'supplierReturnsTotal',
            // Table
            'recentInvoices'
        ));
    }
}
