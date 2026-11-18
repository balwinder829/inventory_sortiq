<?php

namespace App\Http\Controllers;

use App\Models\SupplierReturn;
use App\Models\SupplierReturnItem;
use App\Models\Supplier;
use App\Models\Store;
use App\Models\Item;
use App\Models\StockHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SupplierReturnController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of supplier returns.
     */
    public function index()
    {
        // Query returns using the StoreAccess scope
        $returns = SupplierReturn::storeAccess()
            ->with(['supplier', 'store', 'creator'])
            ->orderBy('return_date', 'desc')
            ->get();

        // Totals
        $totals = [
            'total_refund' => $returns->sum('total_refund_amount'),
            'count'        => $returns->count(),
        ];

        return view('admin.returns.supplier.index', compact('returns', 'totals'));
    }

    /**
     * Show the form for creating a new supplier return.
     */
    public function create()
    {
        if (auth()->user()->is_superadmin) {
            $stores = Store::all();
        } else {
            $stores = auth()->user()->stores;
        }

        $suppliers = Supplier::all();
        // Get all active products/items scoped to store access if non-superadmin
        $items = Item::storeAccess()->where('quantity', '>', 0)->get();

        return view('admin.returns.supplier.create', compact('stores', 'suppliers', 'items'));
    }

    /**
     * Store a newly created supplier return in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'store_id'     => 'required|exists:stores,id',
            'supplier_id'  => 'required|exists:suppliers,id',
            'return_date'  => 'required|date',
            'reference_no' => 'nullable|string|max:255',
            'reason'       => 'nullable|string',
            'items'        => 'required|array|min:1',
            'items.*.item_id'  => 'required|exists:items,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price'    => 'required|numeric|min:0.00',
        ]);

        try {
            DB::beginTransaction();

            $totalRefundAmount = 0;
            $returnItemsData = [];

            foreach ($request->items as $itemInput) {
                $item = Item::findOrFail($itemInput['item_id']);
                $qty = (int)$itemInput['quantity'];
                $price = (float)$itemInput['price'];

                // ❗ Stock validation
                if ($item->quantity < $qty) {
                    throw new \Exception("Return quantity ({$qty}) exceeds available stock ({$item->quantity}) for item: {$item->name}");
                }

                $subtotal = $qty * $price;
                $totalRefundAmount += $subtotal;

                $returnItemsData[] = [
                    'item'     => $item,
                    'quantity' => $qty,
                    'price'    => $price,
                    'subtotal' => $subtotal,
                ];
            }

            // Create return record
            $supplierReturn = SupplierReturn::create([
                'store_id'            => $request->store_id,
                'supplier_id'         => $request->supplier_id,
                'return_date'         => $request->return_date,
                'reference_no'        => $request->reference_no,
                'total_refund_amount' => $totalRefundAmount,
                'reason'              => $request->reason,
                'created_by'          => auth()->id(),
            ]);

            // Save items and adjust stock
            foreach ($returnItemsData as $data) {
                $item = $data['item'];
                $qty = $data['quantity'];

                SupplierReturnItem::create([
                    'supplier_return_id' => $supplierReturn->id,
                    'item_id'            => $item->id,
                    'quantity'           => $qty,
                    'purchase_price'     => $data['price'],
                    'subtotal'           => $data['subtotal'],
                ]);

                // Decrement stock
                $oldQty = $item->quantity;
                $item->decrement('quantity', $qty);

                // Record stock history
                StockHistory::create([
                    'item_id'       => $item->id,
                    'type'          => 'out',
                    'old_quantity'  => $oldQty,
                    'new_quantity'  => $item->quantity,
                    'change_amount' => -$qty,
                    'cost_price'    => $data['price'],
                    'changed_by'    => auth()->id(),
                    'reason'        => 'Supplier Return ' . ($request->reference_no ?: '#' . $supplierReturn->id),
                ]);
            }

            DB::commit();

            return redirect()
                ->route('returns.supplier.index')
                ->with('success', 'Supplier return of ₹' . number_format($totalRefundAmount, 2) . ' processed successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified supplier return.
     */
    public function show(SupplierReturn $supplierReturn)
    {
        // Scoping check
        if (!auth()->user()->is_superadmin && !auth()->user()->stores->contains('id', $supplierReturn->store_id)) {
            abort(403, 'Unauthorized.');
        }

        $supplierReturn->load(['supplier', 'store', 'creator', 'returnItems.item']);

        return view('admin.returns.supplier.show', compact('supplierReturn'));
    }

    /**
     * Remove the specified supplier return from storage (reverting stock levels).
     */
    public function destroy(SupplierReturn $supplierReturn)
    {
        // Scoping check
        if (!auth()->user()->is_superadmin && !auth()->user()->stores->contains('id', $supplierReturn->store_id)) {
            abort(403, 'Unauthorized.');
        }

        try {
            DB::beginTransaction();

            $supplierReturn->load('returnItems');

            // Revert stock levels
            foreach ($supplierReturn->returnItems as $returnItem) {
                $item = Item::findOrFail($returnItem->item_id);
                $oldQty = $item->quantity;
                $item->increment('quantity', $returnItem->quantity);

                // Stock history
                StockHistory::create([
                    'item_id'       => $item->id,
                    'type'          => 'in',
                    'old_quantity'  => $oldQty,
                    'new_quantity'  => $item->quantity,
                    'change_amount' => $returnItem->quantity,
                    'cost_price'    => $returnItem->purchase_price,
                    'changed_by'    => auth()->id(),
                    'reason'        => 'Reverted Supplier Return #' . $supplierReturn->id,
                ]);
            }

            $supplierReturn->delete();

            DB::commit();

            return redirect()
                ->route('returns.supplier.index')
                ->with('success', 'Supplier return record deleted and stock levels successfully reverted.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
}
