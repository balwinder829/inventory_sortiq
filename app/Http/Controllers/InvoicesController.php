<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Order\OrderStoreRequest;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\OrderDetails;
use App\Models\Product;
use App\Models\Item;
use Carbon\Carbon;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\InvoiceMail;
use App\Services\WhatsAppService;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Mail\PdfMail;
// use App\Imports\UsersImport;
use App\Exports\InvoicesExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\WooProduct;
use App\Models\ItemBatch;


class InvoicesController extends Controller
{

     public function __construct()
    {
       $this->middleware('auth');
       $this->middleware('permission:invoices.view|store_management.view')->only(['index','show','export','sendPdfEmail','sendInvoiceToMail','sendInvoice','downloadInvoice']);
       $this->middleware('permission:invoices.create|store_management.view')->only(['create','store']);
       
    }

    public function index()
    {
        // Load invoices + items + related products (prevents N+1 queries)
        $query = Invoice::with(['items.product'])
            ->orderBy('id', 'DESC');

        if (!auth()->user()->is_superadmin) {
            $myStoreIds = auth()->user()->stores->pluck('id')->toArray();
            $invoiceIds = DB::table('invoice_items')
                ->join('items', 'invoice_items.product_id', '=', 'items.id')
                ->whereIn('items.store_id', $myStoreIds)
                ->pluck('invoice_items.invoice_id')
                ->unique();

            $query->whereIn('id', $invoiceIds);
        }

        $orders = $query->get();

        return view('admin.new_invoices.index', compact('orders'));
    }


    public function export()
    {
        $filename = 'invoices_' . now()->format('Ymd_His') . '.xlsx';
        return Excel::download(new InvoicesExport, $filename);
    }


    public function create()
    {   
        $products = Item::storeAccess()->where('quantity', '>', 0)->get();

        return view('admin.new_invoices.create', compact('products'));
    }

    public function store(Request $request)
    {

        try {
            $request->validate([
                'products' => 'required|array|min:1',
                'products.*.id' => 'required|exists:items,id',
                'products.*.price' => 'required|numeric|min:0',
                'products.*.quantity' => 'required|integer|min:1',

                'payment_mode' => 'required|string',

                'customer_name' => 'required|string',
                'customer_mobile' => 'required|string',
                'customer_email' => 'nullable|email',
                'customer_address' => 'nullable|string',

                'store_name' => 'required|string',
                'store_phone' => 'required|string',
                'store_email' => 'nullable|email',
                'store_address' => 'nullable|string',
            ]);

            return DB::transaction(function () use ($request) {

                /*
                |--------------------------------------------------------------------------
                | 1️⃣ Fetch items once + lock (safe concurrency)
                |--------------------------------------------------------------------------
                */
                $items = Item::whereIn(
                    'id',
                    collect($request->products)->pluck('id')
                )->lockForUpdate()->get()->keyBy('id');

                /*
                |--------------------------------------------------------------------------
                | 2️⃣ Stock availability check (UI quantity)
                |--------------------------------------------------------------------------
                */
                foreach ($request->products as $product) {
                    $item = $items[$product['id']];
                    if ($item->quantity < $product['quantity']) {
                        return back()->withErrors([
                            'stock' => "Not enough stock for {$item->name}. Available: {$item->quantity}"
                        ])->withInput();
                    }
                }

                /*
                |--------------------------------------------------------------------------
                | 3️⃣ Create Invoice
                |--------------------------------------------------------------------------
                */
                $invoice = Invoice::create([
                    'payment_mode' => $request->payment_mode,
                    'invoice_date' => now(),

                    'customer_name' => $request->customer_name,
                    'customer_mobile' => $request->customer_mobile,
                    'customer_email' => $request->customer_email,
                    'customer_address' => $request->customer_address,

                    'store_name' => $request->store_name,
                    'store_phone' => $request->store_phone,
                    'store_email' => $request->store_email,
                    'store_address' => $request->store_address,

                    'total_amount' => 0,
                    'total_profit' => 0,
                ]);

                // Invoice number
                $invoiceNo = 'INV-' . date('Y') . '-' . str_pad($invoice->id, 4, '0', STR_PAD_LEFT);
                $invoice->update(['invoice_no' => $invoiceNo]);

                $grandTotal  = 0;
                $grandProfit = 0;

                /*
                |--------------------------------------------------------------------------
                | 4️⃣ Process each product (FIFO + batch-wise profit)
                |--------------------------------------------------------------------------
                */
                foreach ($request->products as $product) {

                    $item      = $items[$product['id']];
                    $saleQty   = (int) $product['quantity'];
                    $salePrice = (float) $product['price'];

                    $requiredQty = $saleQty;
                    $totalCost   = 0;
                    $totalProfit = 0;

                    /*
                    |--------------------------------------------------------------------------
                    | FIFO: Consume oldest batches first
                    |--------------------------------------------------------------------------
                    */
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

                        $costAmount = $consumeQty * $batch->cost_price;
                        $saleAmount = $consumeQty * $salePrice;
                        $profit     = $saleAmount - $costAmount;

                        /*
                        |--------------------------------------------------------------------------
                        | Store batch-wise profit (REAL accounting)
                        |--------------------------------------------------------------------------
                        */
                        DB::table('invoice_item_batches')->insert([
                            'invoice_id'      => $invoice->id,
                            'invoice_item_id' => null, // linked after invoice_item create
                            'item_id'         => $item->id,
                            'batch_id'        => $batch->id,

                            'quantity'        => $consumeQty,
                            'buy_price'       => $batch->cost_price,
                            'sale_price'      => $salePrice,

                            'cost_amount'     => $costAmount,
                            'sale_amount'     => $saleAmount,
                            'profit'          => $profit,

                            'created_at'      => now(),
                        ]);

                        $totalCost   += $costAmount;
                        $totalProfit += $profit;

                        // Reduce batch stock
                        $batch->remaining_quantity -= $consumeQty;
                        $batch->save();

                        $requiredQty -= $consumeQty;
                    }

                    // Safety check
                    if ($requiredQty > 0) {
                        // throw new \Exception("FIFO stock mismatch for item: {$item->name}");
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | 5️⃣ Create invoice_items (summary row)
                    |--------------------------------------------------------------------------
                    */
                    $subtotal = $saleQty * $salePrice;

                    $invoiceItem = InvoiceItem::create([
                        'invoice_id' => $invoice->id,
                        'product_id' => $item->id,
                        'price'      => $salePrice,
                        'buy_price'  => round($totalCost / $saleQty, 2), // display only
                        'quantity'   => $saleQty,
                        'subtotal'   => $subtotal,
                        'profit'     => $totalProfit,
                    ]);

                    /*
                    |--------------------------------------------------------------------------
                    | 6️⃣ Link batch rows to invoice_item
                    |--------------------------------------------------------------------------
                    */
                    DB::table('invoice_item_batches')
                        ->where('invoice_id', $invoice->id)
                        ->where('item_id', $item->id)
                        ->whereNull('invoice_item_id')
                        ->update(['invoice_item_id' => $invoiceItem->id]);

                    /*
                    |--------------------------------------------------------------------------
                    | 7️⃣ Update item quantity (UI only)
                    |--------------------------------------------------------------------------
                    */
                    $item->quantity -= $saleQty;
                    $item->save();

                    /*
                    |--------------------------------------------------------------------------
                    | 8️⃣ Totals
                    |--------------------------------------------------------------------------
                    */
                    $grandTotal  += $subtotal;
                    $grandProfit += $totalProfit;

                    /*
                    |--------------------------------------------------------------------------
                    | 9️⃣ Low stock alert
                    |--------------------------------------------------------------------------
                    */
                    if ($item->quantity < 10) {
                        $adminEmail = config('app.admin_email', 'mehlakrish07@gmail.com');
                        \Mail::to($adminEmail)
                            ->send(new \App\Mail\LowStockAlertMail($item));
                    }
                }

                /*
                |--------------------------------------------------------------------------
                | 🔟 Update invoice totals
                |--------------------------------------------------------------------------
                */
                $invoice->update([
                    'total_amount' => $grandTotal,
                    'total_profit' => $grandProfit,
                ]);

                /*
                |--------------------------------------------------------------------------
                | 📧 Send invoice
                |--------------------------------------------------------------------------
                */
                $this->sendInvoiceToMail($invoice);

                return redirect()->route('invoices.index')
                    ->with('success', 'Invoice created successfully (FIFO + batch-wise profit).');
            });
            } catch (\Throwable $e) {

            \Log::error('Invoice Store Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return back()
                ->withErrors(['error' => 'Something went wrong while creating the invoice. Please try again.'])
                ->withInput();
        }
    }
     


    public function multi_store(Request $request)
    {
        // ✅ Validate Inputs
        $request->validate([
        'products'                  => 'required|array|min:1',
        'products.*.id'             => 'required|exists:items,id',
        'products.*.price'          => 'required|numeric|min:0',
        'products.*.quantity'       => 'required|integer|min:1',

        'payment_mode'              => 'required|string',

        'customer_name'             => 'required|string',
        'customer_mobile'           => 'required|string',
        'customer_email'            => 'nullable|email',
        'customer_address'          => 'nullable|string',

        'store_name'                => 'required|string',
        'store_phone'               => 'required|string',
        'store_email'               => 'nullable|email',
        'store_address'             => 'nullable|string',
    ]);



    // =============================
    // ✅ Stock Availability Check
    // =============================
    foreach ($request->products as $product) {
        $item = Item::findOrFail($product['id']);

        if ($item->quantity < $product['quantity']) {
            return back()->withErrors([
                'stock' => "Not enough stock for {$item->name}. Available: {$item->quantity}"
            ])->withInput();
        }
    }

    // =============================
    // ✅ Generate Invoice Number
    // =============================
    $latestInvoice = Invoice::latest()->first();
    $nextNumber = $latestInvoice ? ($latestInvoice->id + 1) : 1;

    $invoiceNo = 'INV-' . date('Y') . '-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

    // =============================
    // ✅ Create Invoice (Main Table)
    // =============================
    $invoice = Invoice::create([
        'invoice_no'       => $invoiceNo,
        'payment_mode'     => $request->payment_mode,
        'invoice_date'     => now(),

        'customer_name'    => $request->customer_name,
        'customer_mobile'  => $request->customer_mobile,
        'customer_email'   => $request->customer_email,
        'customer_address' => $request->customer_address,

        'store_name'       => $request->store_name,
        'store_phone'      => $request->store_phone,
        'store_email'      => $request->store_email,
        'store_address'    => $request->store_address,

        'total_amount'     => 0, // updated later
    ]);

    $grandTotal = 0;

    // =============================
    // ✅ Save Each Product in invoice_items
    // =============================
    foreach ($request->products as $product) {

        $item = Item::findOrFail($product['id']);

        $subtotal = $product['price'] * $product['quantity'];
        $grandTotal += $subtotal;

        // Store row in invoice_items
        InvoiceItem::create([
            'invoice_id'  => $invoice->id,
            'product_id'  => $item->id,
            'price'       => $product['price'],
            'quantity'    => $product['quantity'],
            'subtotal'    => $subtotal,
        ]);

        // Update stock
        $item->quantity -= $product['quantity'];
        $item->save();

        // Low stock alert
        if ($item->quantity < 10) {
            $adminEmail = config('app.admin_email', 'hs875147@gmail.com');
            \Mail::to($adminEmail)
                ->send(new \App\Mail\LowStockAlertMail($item));
        }
    }

    // =============================
    // ✅ Update final total
    // =============================
    $invoice->total_amount = $grandTotal;
    $invoice->save();

    // =============================
    // ✅ Send Invoice Email
    // =============================
    $this->sendInvoiceToMail($invoice);

    // =============================
    // ✅ Redirect
    // =============================
    return redirect()->route('invoices.index')
        ->with('success', 'Invoice created successfully with multiple products!');
}



    public function sendPdfEmail(Invoice $invoice)
    {   

        $this->sendInvoiceToMail($invoice);
        // Generate PDF from a Blade view
        // $order = $invoice;
        // $pdf = Pdf::loadView('emails.invoice_pdf', ['order' => $order]);

        // Convert PDF to string
        // $pdfContent = $pdf->output();

        // Send email with PDF attachment
        // Mail::to('mehlakrish07@gmail.com')->send(new PdfMail($pdfContent, $order));
         return redirect()->route('invoices.index')
        ->with('success', 'Invoice created and sent successfully!');
    }
    

    public function sendInvoiceToMail(Invoice $invoice)
    {
        // Load invoice with related items & product data
        $invoice->load('items.product');

        // =============================
        // ✅ Generate PDF (multi-product)
        // =============================
        $pdf = Pdf::loadView('emails.invoice_pdf', ['order' => $invoice]);

        $pdfContent = $pdf->output();

        // =============================
        // ✅ Send Email with PDF Attachment
        // =============================
        if ($invoice->customer_email) {
            Mail::to($invoice->customer_email)->send(
                new PdfMail($pdfContent, $invoice)
            );
        }

        // =============================
        // ✅ Build WhatsApp message for MULTIPLE ITEMS
        // =============================
        $date = $invoice->created_at->format('d-M-Y');

        $productList = "";
        foreach ($invoice->items as $item) {
            $productList .= "• {$item->product->name} (Qty: {$item->quantity}) - ₹" .
                            number_format($item->subtotal, 2) . "\n";
        }

        $message = <<<EOT
    Hi {$invoice->customer_name},

    Thank you for your purchase! 🎉  
    Here are your invoice details:

    🧾 Invoice No: {$invoice->invoice_no}
    📅 Date: {$date}

    🛍️ Products:
    {$productList}
    💰 Grand Total: ₹{$invoice->total_amount}

    We appreciate your business.  
    – Sandal Multi Store
    EOT;

        // =============================
        // ✅ Send to WhatsApp
        // =============================
        if ($invoice->customer_mobile) {
            $whatsapp = new WhatsAppService();
            $whatsapp->sendInvoice(
                $invoice->customer_mobile,
                $message
            );
        }

        // Nothing returned (called inside store())
    }


 

    // public function show(Invoice $invoice)
    // {   
    //     $order = $invoice; // assign to new variable
    //     // dd($order);
    //     return view('admin.new_invoices.show', compact('order'));
    //     // return view('admin.new_invoices.show', compact('invoice'));
      
    // }

    public function show(Invoice $invoice)
    {
        if (!auth()->user()->is_superadmin) {
            $myStoreIds = auth()->user()->stores->pluck('id')->toArray();
            $hasAccess = DB::table('invoice_items')
                ->join('items', 'invoice_items.product_id', '=', 'items.id')
                ->where('invoice_items.invoice_id', $invoice->id)
                ->whereIn('items.store_id', $myStoreIds)
                ->exists();

            if (!$hasAccess) {
                abort(403, 'Unauthorized access.');
            }
        }

        $order = $invoice->load([
            'items.product',
            'items.batches' // important for return calculation
        ]);

        /*
        |--------------------------------------------------------------------------
        | Calculate returned qty per item
        |--------------------------------------------------------------------------
        */
        // foreach ($order->items as $item) {

        //     // Get all batch IDs used in this invoice item
        //     $batchIds = $item->batches->pluck('id');

        //     // Sum returned qty from those batches
        //     $returnedQty = \DB::table('return_item_batches')
        //         ->whereIn('invoice_item_batch_id', $batchIds)
        //         ->sum('quantity');

        //     $item->returned_qty = $returnedQty;

        //     // original sold qty = current + returned
        //     $item->original_qty = $item->quantity + $returnedQty;
        // }
        foreach ($order->items as $item) {

            $batchIds = $item->batches->pluck('id');

            $returnedQty = \DB::table('return_item_batches')
                ->whereIn('invoice_item_batch_id', $batchIds)
                ->sum('quantity');

            $item->returned_qty = $returnedQty;
            $item->original_qty = $item->quantity + $returnedQty;

            // 🔥 NEW: batch-level return tracking
            foreach ($item->batches as $batch) {

                $batchReturned = \DB::table('return_item_batches')
                    ->where('invoice_item_batch_id', $batch->id)
                    ->sum('quantity');

                $batch->returned_qty = $batchReturned;
                // $batch->available_qty = $batch->quantity - $batchReturned;
                $batch->sold_qty = $batch->quantity; // original sold
                $batch->returned_qty = $batchReturned;
                $batch->available_qty = $batch->sold_qty - $batchReturned;
            }
        }

        return view('admin.new_invoices.show', compact('order'));
    }
    



    public function sendInvoice()
    {
        $whatsapp = new WhatsAppService();
        $response = $whatsapp->sendInvoice(
            "+919876543210", // recipient number with country code
            "INV-2025-01",
            "$250.00"
        );

        return response()->json(["status" => "Message sent!", "sid" => $response->sid]);
    }

    // public function downloadInvoice($order)
    // {
    //     $order = Invoice::with(['product'])
    //         ->where('id', $order)
    //         ->first();

    //     return view('admin.new_invoices.print-invoice', [
    //         'order' => $order,
    //     ]);
    // }

    public function downloadInvoice($order)
    {
        if (!auth()->user()->is_superadmin) {
            $myStoreIds = auth()->user()->stores->pluck('id')->toArray();
            $hasAccess = DB::table('invoice_items')
                ->join('items', 'invoice_items.product_id', '=', 'items.id')
                ->where('invoice_items.invoice_id', $order)
                ->whereIn('items.store_id', $myStoreIds)
                ->exists();

            if (!$hasAccess) {
                abort(403, 'Unauthorized access.');
            }
        }

        $order = Invoice::with(['items.product'])
            ->where('id', $order)
            ->firstOrFail();

        return view('admin.new_invoices.print-invoice', compact('order'));
    }




    public function update(Order $order, Request $request)
    {
        // TODO refactoring

        // Reduce the stock
        $products = OrderDetails::where('order_id', $order)->get();

        foreach ($products as $product) {
            Product::where('id', $product->product_id)
                ->update(['quantity' => DB::raw('quantity-' . $product->quantity)]);
        }

        $order->update([
            'order_status' => OrderStatus::COMPLETE,
        ]);

        return redirect()
            ->route('orders.complete')
            ->with('success', 'Order has been completed!');
    }

    public function destroy(Order $order)
    {
        $order->delete();
    }

    
}
