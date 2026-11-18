<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\InvoiceItemBatch;
use App\Models\Item;
use App\Models\ItemBatch;
use App\Models\ReturnItem;
use App\Models\ReturnItemBatch;

class ReturnController extends Controller
{
    public function __construct()
    {
       $this->middleware('auth');
       $this->middleware('permission:return_invoice.view')->only(['create']);
       $this->middleware('permission:return_invoice.edit')->only(['store']);       
    }

public function create(Invoice $invoice)
{
    // 🔐 Admin check
    if (!auth()->user() || !auth()->user()->is_superadmin) {
        // abort(403, 'Unauthorized');
    }

    $order = $invoice->load([
        'items.product',
        'items.batches.batch'
    ]);

    $hasReturnableItems = false;

    foreach ($order->items as $item) {

        // mark deleted product
        $item->is_deleted = !$item->product || $item->product->trashed();

        $item->batches = $item->batches->map(function ($batch) {

            $returnedQty = \DB::table('return_item_batches')
                ->where('invoice_item_batch_id', $batch->id)
                ->sum('quantity');

            $batch->remaining_returnable = $batch->quantity - $returnedQty;

            return $batch;

        })->filter(function ($batch) {
            return $batch->remaining_returnable > 0;
        })->values();

        // only count valid (non-deleted) items
        if (!$item->is_deleted && $item->batches->count() > 0) {
            $hasReturnableItems = true;
        }
    }
    // foreach ($order->items as $item) {
    //      if (!$item->product || $item->product->trashed()) {
    //         $item->is_returnable = false;
    //         continue;
    //     }

    //     $item->is_returnable = true;

    //     $item->batches = $item->batches->map(function ($batch) {

    //         $returnedQty = \DB::table('return_item_batches')
    //             ->where('invoice_item_batch_id', $batch->id)
    //             ->sum('quantity');

    //         $batch->remaining_returnable = $batch->quantity - $returnedQty;

    //         return $batch;

    //     })->filter(function ($batch) {

    //         return $batch->remaining_returnable > 0;

    //     })->values();

    //     // 🔥 check if this item still has returnable batches
    //     if ($item->batches->count() > 0) {
    //         $hasReturnableItems = true;
    //     }
    // }

    // 🔥 remove items with no batches (IMPORTANT)
    // $order->items = $order->items->filter(function ($item) {
    //     // return $item->batches->count() > 0;
    //     return $item->is_returnable && $item->batches->count() > 0;
    // })->values();

    // pass flag to blade
    $order->has_returnable_items = $hasReturnableItems;

    return view('admin.new_invoices.return', compact('order'));
}
    public function createold(Invoice $invoice)
{
    // 🔐 Admin check (extra safety, even if middleware exists)
    if (!auth()->user() || !auth()->user()->is_superadmin) {
        abort(403, 'Unauthorized');
    }

    /*
    |--------------------------------------------------------------------------
    | Load full invoice with items + batches
    |--------------------------------------------------------------------------
    */
    $order = $invoice->load([
        'items.product',
        'items.batches.batch'
    ]);

    /*
    |--------------------------------------------------------------------------
    | Optional: filter out fully returned batches
    |--------------------------------------------------------------------------
    */
    foreach ($order->items as $item) {

        $item->batches = $item->batches->map(function ($batch) {

            // already returned qty
            $returnedQty = \DB::table('return_item_batches')
                ->where('invoice_item_batch_id', $batch->id)
                ->sum('quantity');

            // remaining allowed return
            $batch->remaining_returnable = $batch->quantity - $returnedQty;

            return $batch;
        })->filter(function ($batch) {
            return $batch->remaining_returnable > 0;
        })->values();
    }

    return view('admin.new_invoices.return', compact('order'));
}
    /**
     * Store return (Batch click-based)
     */
    public function store(Request $request, Invoice $invoice)
    {
        if (!$request->has('items') || !is_array($request->items)) {
            return back()->with('error', 'No items selected for return.');
        }
        DB::transaction(function () use ($request, $invoice) {

            foreach ($request->items as $itemData) {

                
                $invoiceItem = InvoiceItem::with('batches')
                    ->findOrFail($itemData['invoice_item_id']);

                $totalReturnQty = 0;
                $totalRefund = 0;
                $totalProfitReversed = 0;

                // Create return_items row FIRST
                $returnItem = ReturnItem::create([
                    'invoice_id' => $invoice->id,
                    'invoice_item_id' => $invoiceItem->id,
                    'product_id' => $invoiceItem->product_id,
                    'total_quantity' => 0, // will update later
                    'total_refund_amount' => 0,
                    'total_profit_reversed' => 0,
                    'reason' => $request->reason ?? null,
                    'created_by' => auth()->id(),
                ]);

                /*
                |--------------------------------------------------------------------------
                | 🔁 Loop batch-level return (from UI clicks)
                |--------------------------------------------------------------------------
                */

                if (!isset($itemData['batches']) || !is_array($itemData['batches'])) {
                    continue;
                }

                foreach ($itemData['batches'] as $batchInput) {

                    // $returnQty = (int) $batchInput['quantity'];
                    $returnQty = (int) ($batchInput['quantity'] ?? 0);

                    if ($returnQty <= 0) continue;

                    $batchRow = InvoiceItemBatch::findOrFail(
                        $batchInput['invoice_item_batch_id']
                    );

                    /*
                    |--------------------------------------------------------------------------
                    | ❗ VALIDATION: Prevent over-return
                    |--------------------------------------------------------------------------
                    */
                    $alreadyReturned = DB::table('return_item_batches')
                        ->where('invoice_item_batch_id', $batchRow->id)
                        ->sum('quantity');

                    $availableQty = $batchRow->quantity - $alreadyReturned;

                    if ($returnQty > $availableQty) {
                        throw new \Exception("Return exceeds available quantity for batch ID {$batchRow->id}");
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | Restore stock
                    |--------------------------------------------------------------------------
                    */
                    $batch = ItemBatch::findOrFail($batchRow->batch_id);
                    $batch->increment('remaining_quantity', $returnQty);

                    $item = Item::findOrFail($batchRow->item_id);
                    $oldQty = $item->quantity;
                    $item->increment('quantity', $returnQty);

                    /*
                    |--------------------------------------------------------------------------
                    | Stock history
                    |--------------------------------------------------------------------------
                    */
                    DB::table('stock_history')->insert([
                        'item_id'       => $item->id,
                        'old_quantity'  => $oldQty,
                        'new_quantity'  => $item->quantity,
                        'change_amount' => $returnQty,
                        'cost_price'    => $batchRow->buy_price,
                        'changed_by'    => auth()->id(),
                        'reason'        => 'Return from Invoice ' . $invoice->invoice_no,
                        'created_at'    => now(),
                        'updated_at'    => now(),
                    ]);

                    /*
                    |--------------------------------------------------------------------------
                    | Calculate values
                    |--------------------------------------------------------------------------
                    */
                    $costAmount = $returnQty * $batchRow->buy_price;
                    $refundAmount = $returnQty * $batchRow->sale_price;
                    $profitReversed = $refundAmount - $costAmount;

                    /*
                    |--------------------------------------------------------------------------
                    | Save return_item_batches
                    |--------------------------------------------------------------------------
                    */
                    ReturnItemBatch::create([
                        'return_item_id'         => $returnItem->id,
                        'invoice_item_batch_id'  => $batchRow->id,
                        'batch_id'               => $batchRow->batch_id,
                        'item_id'                => $batchRow->item_id,

                        'quantity'               => $returnQty,

                        'buy_price'              => $batchRow->buy_price,
                        'sale_price'             => $batchRow->sale_price,

                        'cost_amount'            => $costAmount,
                        'refund_amount'          => $refundAmount,
                        'profit_reversed'        => $profitReversed,
                    ]);

                    /*
                    |--------------------------------------------------------------------------
                    | Accumulate totals
                    |--------------------------------------------------------------------------
                    */
                    $totalReturnQty += $returnQty;
                    $totalRefund += $refundAmount;
                    $totalProfitReversed += $profitReversed;
                }

                /*
                |--------------------------------------------------------------------------
                | Update return_items summary
                |--------------------------------------------------------------------------
                */
                $returnItem->update([
                    'total_quantity' => $totalReturnQty,
                    'total_refund_amount' => $totalRefund,
                    'total_profit_reversed' => $totalProfitReversed,
                ]);

                /*
                |--------------------------------------------------------------------------
                | Update invoice_item
                |--------------------------------------------------------------------------
                */
                $invoiceItem->quantity -= $totalReturnQty;
                $invoiceItem->subtotal -= $totalRefund;
                $invoiceItem->profit -= $totalProfitReversed;
                $invoiceItem->save();

                /*
                |--------------------------------------------------------------------------
                | Update invoice totals
                |--------------------------------------------------------------------------
                */
                $invoice->total_amount -= $totalRefund;
                $invoice->total_profit -= $totalProfitReversed;
            }

            $invoice->save();
        });

        return redirect()->route('invoices.index')->with('success', 'Return processed successfully.');
    }
}