<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use DB;

class ProfitAnalysisController extends Controller
{
     public function __construct()
    {
       $this->middleware('auth');
       $this->middleware('permission:profit_analytics.view')->only(['index']);
    }
    public function index(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | QUICK DATE RANGE
        |--------------------------------------------------------------------------
        */

        $quick = $request->quick_range;

        // UI values (kept visible)
        $uiStart = $request->start_date;
        $uiEnd   = $request->end_date;

        switch ($quick) {
            case 'today':
                $start = $end = today()->toDateString();
                break;

            case 'yesterday':
                $start = $end = today()->subDay()->toDateString();
                break;

            case '7days':
                $start = today()->subDays(6)->toDateString();
                $end   = today()->toDateString();
                break;

            case 'this_month':
                $start = now()->startOfMonth()->toDateString();
                $end   = now()->endOfMonth()->toDateString();
                break;

            case 'last_month':
                $start = now()->subMonth()->startOfMonth()->toDateString();
                $end   = now()->subMonth()->endOfMonth()->toDateString();
                break;

            case 'all':
                $start = Invoice::min('invoice_date');
                $end   = Invoice::max('invoice_date');
                break;

            default:
                // Custom Range
                $start = $request->start_date ?: Invoice::min('invoice_date');
                $end   = $request->end_date   ?: Invoice::max('invoice_date');
        }

        /*
        |--------------------------------------------------------------------------
        | PRODUCT FILTER LIST
        |--------------------------------------------------------------------------
        */

        $productId = $request->product_id;
        $products = DB::table('items')->orderBy('name')->get();

        /*
        |--------------------------------------------------------------------------
        | MAIN PROFIT NUMBERS
        |--------------------------------------------------------------------------
        */

        $overallProfit = Invoice::sum('total_profit');

        $totalProfit = Invoice::whereBetween('invoice_date', [$start, $end])
            ->sum('total_profit');

        $todayProfit = Invoice::whereDate('invoice_date', today())
            ->sum('total_profit');

        $thisMonthProfit = Invoice::whereYear('invoice_date', now()->year)
            ->whereMonth('invoice_date', now()->month)
            ->sum('total_profit');

        /*
        |--------------------------------------------------------------------------
        | ORDER-WISE PROFIT
        |--------------------------------------------------------------------------
        */

        $orderProfit = Invoice::whereBetween('invoice_date', [$start, $end])
            ->select('invoice_no', 'invoice_date', 'total_profit')
            ->orderByDesc('invoice_date')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | PRODUCT SUMMARY (OPTIONAL)
        |--------------------------------------------------------------------------
        */

        $productSummary = null;
        $orderWiseProduct = null;

        if ($productId) {

            $summaryRaw = DB::table('invoice_items')
                ->join('invoices', 'invoice_items.invoice_id', '=', 'invoices.id')
                ->whereBetween('invoices.invoice_date', [$start, $end])
                ->where('invoice_items.product_id', $productId)
                ->select(
                    DB::raw('SUM(invoice_items.quantity) AS total_qty'),
                    DB::raw('SUM(invoice_items.subtotal) AS total_revenue'),
                    DB::raw('SUM(invoice_items.profit) AS total_profit'),
                    DB::raw('COUNT(DISTINCT invoice_items.invoice_id) AS total_orders')
                )
                ->first();

            // Safe fallback values (avoid null)
            $productSummary = (object)[
                'total_qty'     => $summaryRaw->total_qty     ?? 0,
                'total_revenue' => $summaryRaw->total_revenue ?? 0,
                'total_profit'  => $summaryRaw->total_profit  ?? 0,
                'total_orders'  => $summaryRaw->total_orders  ?? 0,
            ];

            $orderWiseProduct = DB::table('invoice_items')
                ->join('invoices', 'invoice_items.invoice_id', '=', 'invoices.id')
                ->whereBetween('invoices.invoice_date', [$start, $end])
                ->where('invoice_items.product_id', $productId)
                ->select(
                    'invoice_items.quantity',
                    'invoice_items.subtotal',
                    'invoice_items.profit',
                    'invoices.invoice_no',
                    'invoices.invoice_date'
                )
                ->orderByDesc('invoice_items.profit')
                ->get();
        }

        $totalOrderProfit = $orderProfit->sum('total_profit');

        $totalProductQty     = $orderWiseProduct ? $orderWiseProduct->sum('quantity') : 0;
        $totalProductRevenue = $orderWiseProduct ? $orderWiseProduct->sum('subtotal') : 0;
        $totalProductProfit  = $orderWiseProduct ? $orderWiseProduct->sum('profit')   : 0;

        return view('admin.profit.index', compact(
            'uiStart', 'uiEnd', 'quick',
            'start', 'end',
            'overallProfit',
            'totalProfit',
            'todayProfit',
            'thisMonthProfit',
            'orderProfit',
            'products',
            'productId',
            'productSummary',
            'orderWiseProduct',
            'totalOrderProfit',
            'totalProductQty',
            'totalProductRevenue',
            'totalProductProfit'
        ));
    }
}
