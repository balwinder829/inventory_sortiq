<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Order\OrderStoreRequest;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Invoice;
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

class StoreProductController extends Controller
{
    public function index()
    {
        $orders = Invoice::latest()->get();
        // dd($orders);
         return view('admin.new_invoices.index', compact('orders'));
        // return view('orders.index', [
        //     'orders' => $orders,
        // ]);
    }

    public function export()
    {
        $filename = 'invoices_' . now()->format('Ymd_His') . '.xlsx';
        return Excel::download(new InvoicesExport, $filename);
    }


    public function create()
    {   
        // $products = WooProduct::get();
        $products = WooProduct::where('stock_quantity', '>', 0)->get();
        // dd($products);
        // $products = Item::get();
        return view('admin.new_invoices.create', compact('products'));
    }

    public function store(Request $request)
    {
        // ✅ validation
        $request->validate([
            'name'            => 'required',
            'price'           => 'required|numeric',
            'quantity'        => 'required|integer|min:1',
            'customer_name'   => 'required|string',
            'customer_mobile' => 'required|string',
            'store_name'      => 'required|string',
            'store_phone'     => 'required|string',
            'payment_mode'     => 'required|string',
        ]);

         // ✅ Fetch the product and check stock
    $product = WooProduct::find($request->name);

    if (!$product) {
        return back()->withErrors(['name' => 'Selected product not found.'])->withInput();
    }

    if ($product->stock_quantity < $request->quantity) {
        return back()->withErrors([
            'quantity' => 'Quantity cannot exceed available stock (' . $product->stock_quantity . ').'
        ])->withInput();
    }

          // ✅ Generate unique invoice no
        $latestInvoice = \App\Models\Invoice::latest()->first();
        $nextNumber = $latestInvoice ? ($latestInvoice->id + 1) : 1;
        $invoiceNo = 'INV-' . date('Y') . '-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

        // ✅ save
       $invoice =  Invoice::create([
            'invoice_no'       => $invoiceNo,
            'product_id'       => $request->name, // product_id from select
            'price'            => $request->price,
            'quantity'         => $request->quantity,
            'payment_mode'         => $request->payment_mode,
            
            'customer_name'    => $request->customer_name,
            'customer_mobile'  => $request->customer_mobile,
            'customer_email'   => $request->customer_email,
            'customer_address' => $request->customer_address,
            
            'store_name'       => $request->store_name,
            'store_phone'      => $request->store_phone,
            'store_email'      => $request->store_email,
            'store_address'    => $request->store_address,

            // ✅ set invoice date
            'invoice_date'     => Carbon::now()->toDateString(),
        ]);

        $this->sendInvoiceToMail($invoice);

             // ✅ Redirect here
        return redirect()->route('invoices.index')
        ->with('success', 'Invoice created and sent successfully!');

        // return redirect()->back()->with('success', 'Invoice created successfully!');
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
         return redirect()->route('invoices.index')
        ->with('success', 'Invoice created and sent successfully!');
    }
    // public function sendInvoiceToMail(Invoice $invoice){
    //     // dd($invoice);
    //     //  Mail::to($invoice->customer_email)->send(new InvoiceMail($invoice));
    //     //  return redirect()->route('invoices.index')
    //     // ->with('success', 'Email sent successfully!');
    //     // return redirect()->route('invoices.index')->with('success', 'Invoice created successfully!');

    //     $total = $invoice->invoice_quantity*$invoice->invoice_price;
    //     $message = <<<EOT
    //         Hi {$invoice->customer_name},

    //         Thank you for your purchase! 🎉  
    //         Here are your invoice details:

    //         📄 Invoice No: {$invoice->invoice_no}  
    //         💰 Amount: {$total}  
             

    //         We appreciate your business.  
    //         Best regards,  
    //         EOT;
    //     $whatsapp = new WhatsAppService();
    //     $response = $whatsapp->sendInvoice(
    //         "+919996199744", // recipient number with country code
    //         $message
    //     );

    //     return redirect()->route('invoices.index')->with('success', $response->sid);
    //     // return response()->json(["status" => "Message sent!", "sid" => $response->sid]);
    // }

    public function sendInvoiceToMail(Invoice $invoice)
    {
        // Calculate total
        // dd($invoice);

        // Mail::to($invoice->customer_email)->send(new InvoiceMail($invoice));

        $order = $invoice;
        $pdf = Pdf::loadView('emails.invoice_pdf', ['order' => $order]);

        // Convert PDF to string
        $pdfContent = $pdf->output();

        // Send email with PDF attachment
        Mail::to($invoice->customer_email)->send(new PdfMail($pdfContent, $order));

        $total = $invoice->quantity * $invoice->price;
        $date  = $invoice->created_at->format('d-M-Y'); // or $invoice->invoice_date
        $p_name = html_entity_decode($invoice->product->name);
        // Build dynamic WhatsApp message
        $message = <<<EOT
        Hi {$invoice->customer_name},

        Thank you for your purchase! 🎉  
        Here are your invoice details:

        🛒 Product: {$p_name}  
        📄 Invoice No: {$invoice->invoice_no} 
        💰 Amount: ₹{$total}  
        📅 Date: {$date}  

        We appreciate your business.  
        Best regards,  
        Sandal Multi Store
        EOT;

        // Send WhatsApp
        $whatsapp = new WhatsAppService();
        $response = $whatsapp->sendInvoice(
            $invoice->customer_mobile,  // recipient number
            $message
        );

        // return redirect()->route('invoices.index')
        // ->with('success', 'Invoice created & email & whatsapp sent successfully!');
        

            // Redirect with success
            // return redirect()->route('invoices.index')
                // ->with('success', "WhatsApp sent! SID: {$response->sid}");
    }

 

    public function show(Invoice $invoice)
    {   
        $order = $invoice; // assign to new variable
        // dd($order);
        return view('admin.new_invoices.show', compact('order'));
        // return view('admin.new_invoices.show', compact('invoice'));
      
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

    public function downloadInvoice($order)
    {
        $order = Invoice::with(['product'])
            ->where('id', $order)
            ->first();

        return view('admin.new_invoices.print-invoice', [
            'order' => $order,
        ]);
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
