<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Models\Purchase;
use App\Models\PurchaseDetails;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display the reporting dashboard.
     */
    public function index(Request $request)
    {
        $suppliers = Supplier::orderBy('name')->get();

        // Get filter inputs
        $reportType = $request->input('report_type', 'invoices');
        $period = $request->input('period', 'daily');
        $supplierId = $request->input('supplier_id');
        $startDateInput = $request->input('start_date');
        $endDateInput = $request->input('end_date');

        // Determine date range based on period
        $startDate = Carbon::today()->startOfDay();
        $endDate = Carbon::today()->endOfDay();

        if ($period === 'weekly') {
            $startDate = Carbon::today()->subDays(6)->startOfDay();
            $endDate = Carbon::today()->endOfDay();
        } elseif ($period === 'monthly') {
            $startDate = Carbon::now()->startOfMonth()->startOfDay();
            $endDate = Carbon::now()->endOfMonth()->endOfDay();
        } elseif ($period === 'custom' && $startDateInput && $endDateInput) {
            try {
                $startDate = Carbon::parse($startDateInput)->startOfDay();
                $endDate = Carbon::parse($endDateInput)->endOfDay();
            } catch (\Exception $e) {
                // Keep default if invalid
            }
        }

        // Initialize variables
        $results = collect();
        $totals = [
            'count'  => 0,
            'amount' => 0,
            'profit' => 0,
        ];

        if ($reportType === 'invoices') {
            // Retrieve invoices in range
            
            if (!auth()->user()->is_superadmin) {
                $myStoreIds = auth()->user()->stores->pluck('id')->toArray();
                $invoiceIds = \DB::table('invoice_items')
                    ->join('items', 'invoice_items.product_id', '=', 'items.id')
                    ->whereIn('items.store_id', $myStoreIds)
                    ->pluck('invoice_items.invoice_id')
                    ->unique();

                $query->whereIn('id', $invoiceIds);
            }
            
            $query = Invoice::whereBetween('invoice_date', [$startDate, $endDate])
                ->with('items.product');

            $results = $query->orderBy('id', 'DESC')->get();

            $totals['count'] = $results->count();
            $totals['amount'] = $results->sum('total_amount');
            $totals['profit'] = $results->sum('total_profit');

        } elseif ($reportType === 'purchases') {
            if ($supplierId) {
                // Supplier-filtered: Fetch detailed purchase items (exact stock details)
                $results = PurchaseDetails::whereHas('purchase', function ($q) use ($supplierId, $startDate, $endDate) {
                    $q->where('supplier_id', $supplierId)
                      ->whereBetween('purchase_date', [$startDate, $endDate]);
                })->with(['purchase.supplier', 'item'])->get();

                $totals['count'] = $results->sum('quantity'); // total quantity of items supplied
                $totals['amount'] = $results->sum('total'); // total cost of all supplied items
            } else {
                // General Purchases: Fetch all purchase records in range
                $results = Purchase::whereBetween('purchase_date', [$startDate, $endDate])
                    ->with('supplier')
                    ->orderBy('id', 'DESC')
                    ->get();

                $totals['count'] = $results->count();
                $totals['amount'] = $results->sum('total_amount');
            }
        }

        return view('admin.reports.index', [
            'suppliers'  => $suppliers,
            'reportType' => $reportType,
            'period'     => $period,
            'supplierId' => $supplierId,
            'startDate'  => $startDate->format('Y-m-d'),
            'endDate'    => $endDate->format('Y-m-d'),
            'results'    => $results,
            'totals'     => $totals,
        ]);
    }
}
