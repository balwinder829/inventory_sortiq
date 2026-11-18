<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ItemBatch;

class StockController extends Controller
{   
    public function __construct()
    {
       $this->middleware('auth');
       $this->middleware('permission:stock_management.view|store_management.view')->only(['index','show']);
       $this->middleware('permission:stock_management.edit|store_management.view')->only(['edit','update']);
       // $this->middleware('permission:stock_management.delete')->only(['destroy']);
       // $this->middleware('permission:products.import')->only(['import_page','import']);
       $this->middleware('permission:products.view|stock_management.view|store_management.view')->only(['history']);
       
    }
    // Show all items with stock filters
    public function index(Request $request)
    {
        $filter = $request->get('filter');

        $query = Item::query();
        // if (!auth()->user()->is_superadmin) {
        //     $storeId = \App\Models\Store::where('user_id', auth()->id())->value('id');
        //     $query->where('store_id', $storeId);
        // }

        if (!auth()->user()->is_superadmin) {
            // Get all store IDs assigned to user
            $storeIds = \App\Models\Store::where('user_id', auth()->id())->pluck('id');

            $query->whereIn('store_id', $storeIds);
        }

        // Filters
        if ($filter === 'low') {
            $query->where('quantity', '<=', 10)->where('quantity', '>', 0);
        } elseif ($filter === 'out') {
            $query->where('quantity', 0);
        } elseif ($filter === 'in') {
            $query->where('quantity', '>', 5);
        }

        $products = $query->get();

        return view('admin.stock.index', compact('products', 'filter'));
    }

    // Show edit stock page
    public function edit(Item $item)
    {   
         $query = Item::query();

        if (!auth()->user()->is_superadmin) {
            $storeId = \App\Models\Store::where('user_id', auth()->id())->value('id');
            $query->where('store_id', $storeId);
        }

        $item = $query->findOrFail($item->id);
        return view('admin.stock.update', compact('item'));
    }

    public function update(Request $request, Item $item)
    {
        $validated = $request->validate([
            'quantity'    => 'required|integer|min:0',
            'cost_price'  => 'nullable|numeric|min:0',
            'update_cost' => 'nullable|boolean',
        ]);

        return DB::transaction(function () use ($validated, $request, $item) {

            $oldQty = $item->quantity;
            $newQty = (int) $validated['quantity'];
            $qtyChange = $newQty - $oldQty;

            /*
            |--------------------------------------------------------------------------
            | 0️⃣ NO CHANGE
            |--------------------------------------------------------------------------
            */
            if ($qtyChange === 0) {

                // Only cost update (UI / future purchases)
                if ($request->boolean('update_cost')) {
                    $item->cost_price = (float) $validated['cost_price'];
                    $item->save();

                    DB::table('stock_history')->insert([
                        'item_id'       => $item->id,
                        'old_quantity'  => $oldQty,
                        'new_quantity'  => $newQty,
                        'change_amount' => 0,
                        'cost_price'    => $item->cost_price,
                        'changed_by'    => auth()->id(),
                        'reason'        => 'Cost Price Updated (No Stock Change)',
                        'created_at'    => now(),
                        'updated_at'    => now(),
                    ]);

                    return redirect()->route('stocks.index')
                        ->with('success', 'Cost price updated successfully.');
                }

                return redirect()->route('stocks.index')
                    ->with('success', 'No changes were made.');
            }

            /*
            |--------------------------------------------------------------------------
            | 1️⃣ STOCK INCREASE = PURCHASE (ALWAYS)
            |--------------------------------------------------------------------------
            */
            if ($qtyChange > 0) {

                $addedQty = $qtyChange;

                // Decide purchase cost
                if ($request->boolean('update_cost')) {
                    $purchaseCost = (float) $validated['cost_price'];
                    $item->cost_price = $purchaseCost; // store last cost
                } else {
                    $purchaseCost = $item->cost_price;
                }

                if ($purchaseCost === null) {
                    throw new \Exception("Cost price not available for {$item->name}");
                }

                // ✅ CREATE FIFO BATCH (ALWAYS)
                ItemBatch::create([
                    'item_id'            => $item->id,
                    'quantity'           => $addedQty,
                    'remaining_quantity' => $addedQty,
                    'cost_price'         => $purchaseCost,
                    'created_at'         => now(),
                ]);

                // Update UI quantity
                $item->quantity = $newQty;
                $item->save();

                DB::table('stock_history')->insert([
                    'item_id'       => $item->id,
                    'old_quantity'  => $oldQty,
                    'new_quantity'  => $newQty,
                    'change_amount' => $addedQty,
                    'cost_price'    => $purchaseCost,
                    'changed_by'    => auth()->id(),
                    'reason'        => $request->boolean('update_cost')
                                        ? 'Purchase (New Cost)'
                                        : 'Purchase (Same Cost as Previous)',
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ]);

                return redirect()->route('stocks.index')
                    ->with('success', 'Stock increased successfully (Purchase recorded).');
            }

            /*
            |--------------------------------------------------------------------------
            | 2️⃣ STOCK DECREASE = LOSS / ADJUSTMENT (FIFO)
            |--------------------------------------------------------------------------
            */
            if ($qtyChange < 0) {

                $removeQty = abs($qtyChange);
                $requiredQty = $removeQty;

                $batches = ItemBatch::where('item_id', $item->id)
                    ->where('remaining_quantity', '>', 0)
                    ->orderBy('created_at')
                    ->lockForUpdate()
                    ->get();

                foreach ($batches as $batch) {

                    if ($requiredQty <= 0) {
                        break;
                    }

                    $consumeQty = min($batch->remaining_quantity, $requiredQty);

                    $batch->remaining_quantity -= $consumeQty;
                    $batch->save();

                    $requiredQty -= $consumeQty;
                }

                if ($requiredQty > 0) {
                    throw new \Exception("FIFO stock mismatch during decrease for {$item->name}");
                }

                // Update UI quantity
                $item->quantity = $newQty;
                $item->save();

                DB::table('stock_history')->insert([
                    'item_id'       => $item->id,
                    'old_quantity'  => $oldQty,
                    'new_quantity'  => $newQty,
                    'change_amount' => -$removeQty,
                    'cost_price'    => null,
                    'changed_by'    => auth()->id(),
                    'reason'        => 'Stock Decrease (Loss / Adjustment)',
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ]);

                return redirect()->route('stocks.index')
                    ->with('success', 'Stock decreased successfully.');
            }
        });
    }

   public function update10dec(Request $request, Item $item)
{
    $validated = $request->validate([
        'quantity'    => 'required|integer|min:0',
        'cost_price'  => 'nullable|numeric|min:0',
        'update_cost' => 'nullable|boolean'
    ]);

    $updateCost = $request->boolean('update_cost'); // safe checkbox
    $oldQty  = $item->quantity;
    $newQty  = (int) $validated['quantity'];
    $qtyChange = $newQty - $oldQty;

    $oldAvg  = $item->avg_cost;
    $oldCost = $item->cost_price;

    // ----------------------------------------------------
    // 0) COST UPDATE ONLY (NO QTY CHANGE)
    // ----------------------------------------------------
    if ($qtyChange === 0 && $updateCost) {

        $newCost = (float) $validated['cost_price'];

        // Update ONLY cost_price (avg_cost unchanged)
        $item->cost_price = $newCost;
        $item->save();

        // Log cost update
        DB::table('stock_history')->insert([
            'item_id'       => $item->id,
            'old_quantity'  => $oldQty,
            'new_quantity'  => $newQty,
            'change_amount' => 0,
            'cost_price'    => $newCost,
            'changed_by'    => auth()->id(),
            'reason'        => 'Cost Price Updated Only',
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);

        return redirect()->route('stocks.index')
            ->with('success', 'Cost price updated successfully.');
    }

    // ----------------------------------------------------
    // 1) NO CHANGE AT ALL
    // ----------------------------------------------------
    if ($qtyChange === 0 && !$updateCost) {
        return redirect()->route('stocks.index')
            ->with('success', 'No changes were made.');
    }

    // ----------------------------------------------------
    // 2) STOCK INCREASE
    // ----------------------------------------------------
    if ($qtyChange > 0) {

        $addedQty = $qtyChange;

        // PURCHASE MODE
        if ($updateCost) {

            $purchaseCost = (float) $validated['cost_price'];

            // weighted avg
            $newAvg = (($oldQty * $oldAvg) + ($addedQty * $purchaseCost))
                        / ($oldQty + $addedQty);

            $item->avg_cost   = $newAvg;
            $item->cost_price = $purchaseCost;
            $item->quantity   = $newQty;
            $item->save();

            DB::table('stock_history')->insert([
                'item_id'       => $item->id,
                'old_quantity'  => $oldQty,
                'new_quantity'  => $newQty,
                'change_amount' => $addedQty,
                'cost_price'    => $purchaseCost,
                'changed_by'    => auth()->id(),
                'reason'        => 'Purchase / Stock-In',
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);

            return redirect()->route('stocks.index')
                ->with('success', 'Stock increased (Purchase) successfully.');
        }

        // MANUAL INCREASE (NO COST)
        else {
            $item->quantity = $newQty;
            $item->save();

            DB::table('stock_history')->insert([
                'item_id'       => $item->id,
                'old_quantity'  => $oldQty,
                'new_quantity'  => $newQty,
                'change_amount' => $addedQty,
                'cost_price'    => $oldAvg,
                'changed_by'    => auth()->id(),
                'reason'        => 'Manual Stock Increase (Adjustment)',
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);

            return redirect()->route('stocks.index')
                ->with('success', 'Stock increased (Adjustment) successfully.');
        }
    }

    // ----------------------------------------------------
    // 3) STOCK DECREASE
    // ----------------------------------------------------
    if ($qtyChange < 0) {

        $removedQty = abs($qtyChange);

        $item->quantity = $newQty;
        $item->save();

        DB::table('stock_history')->insert([
            'item_id'       => $item->id,
            'old_quantity'  => $oldQty,
            'new_quantity'  => $newQty,
            'change_amount' => -$removedQty,
            'cost_price'    => $oldAvg,
            'changed_by'    => auth()->id(),
            'reason'        => 'Manual Stock Decrease (Adjustment)',
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);

        return redirect()->route('stocks.index')
            ->with('success', 'Stock decreased successfully.');
    }
}


    public function updateold9dec(Request $request, Item $item)
    {
        $request->validate([
            'quantity'   => 'required|integer|min:0',
            'cost_price' => 'nullable|numeric|min:0',
            'update_cost'=> 'nullable|boolean'
        ]);

        $oldQuantity = $item->quantity;
        $newQuantity = (int) $request->quantity;
        $quantityChanged = $oldQuantity !== $newQuantity;

        $oldCost = $item->cost_price;
        $costChanged = false;

        // Update cost only when checkbox is checked
        if ($request->update_cost) {
            $costChanged = ($oldCost != $request->cost_price);
            if ($costChanged) {
                $item->cost_price = $request->cost_price;
            }
        }

        // Update quantity (only if changed)
        if ($quantityChanged) {
            $item->quantity = $newQuantity;
        }

        // Save item only if something changed
        if ($quantityChanged || $costChanged) {
            $item->save();
        } else {
            return redirect()->route('stocks.index')
                ->with('success', 'No changes were made.');
        }

        // Insert history only if something changed
        if ($quantityChanged || $costChanged) {
            DB::table('stock_history')->insert([
                'item_id'       => $item->id,
                'old_quantity'  => $oldQuantity,
                'new_quantity'  => $newQuantity,
                'change_amount' => $quantityChanged ? ($newQuantity - $oldQuantity) : 0,
                'cost_price'    => $item->cost_price,
                'changed_by'    => auth()->id() ?? 1,
                'reason'        => $quantityChanged && $costChanged ? 'Quantity + Cost updated'
                                 : ($quantityChanged ? 'Quantity updated'
                                 : 'Cost price updated'),
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);
        }

        return redirect()->route('stocks.index')
            ->with('success', 'Stock updated successfully.');
    }


    public function exportHistory(Item $item)
    {
        $filename = 'stock_history_' . $item->id . '_' . now()->format('Ymd_His') . '.xlsx';

        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\StockHistoryExport($item->id),
            $filename
        );
    }

    public function history($itemId)
    {
        $item = Item::findOrFail($itemId);

        $history = DB::table('stock_history')
            ->where('item_id', $itemId)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.stock.history', compact('item', 'history'));
    }


}
