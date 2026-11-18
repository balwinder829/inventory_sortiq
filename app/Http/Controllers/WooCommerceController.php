<?php

namespace App\Http\Controllers;

use App\Imports\WooProductsImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Services\WooCommerceService;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use App\Models\WooProduct;
use App\Models\WooOrder;
use Illuminate\Support\Facades\Http;

class WooCommerceController extends Controller
{
    protected $wc;

     
    public function __construct(WooCommerceService $wc)
    {
        $this->wc = $wc;
        $this->middleware('auth');
       $this->middleware('permission:wc_products.view')->only(['products','product','sync','syncAll', 'printQr']);
       $this->middleware('permission:wc_products.create')->only(['addProduct','store']);
       $this->middleware('permission:wc_products.edit')->only(['editProduct','updateProduct']);
       $this->middleware('permission:wc_products.delete')->only(['deleteProduct']);
       $this->middleware('permission:wc_products.import')->only(['importProductsPage','importProducts']);

      $this->middleware('permission:wc_orders.view')->only(['orders','order','pendingOrders','processingOrders','']);
      $this->middleware('permission:wc_orders.edit')->only(['updateStatus']);

      $this->middleware('permission:wc_products.view')->only(['customers','customer']);

      $this->middleware('permission:wc_products.view')->only(['categories','category']);
    }

    public function products(Request $request)
    {
        $perPage = $request->input('per_page', 30);
        $page    = $request->input('page', 1);

        $paginator = $this->wc->getPaginatedProducts($perPage, $page);
        // dd($paginator);
        return view('admin.woo_products.index', compact('paginator'));
    }


    public function product($id)
    {   
        
        $product = $this->wc->getProduct($id);
        // dd($product);
        if (!$product) {
            return redirect()->back()->with('error', 'Product not found.');
        }

        return view('admin.woo_products.show', compact('product'));
    }

    public function printQr($id)
    {
        $product = $this->wc->getProduct($id);
        if (!$product) {
            return redirect()->back()->with('error', 'Product not found.');
        }

        return view('admin.woo_products.print-qrcode', compact('product'));
    }

    public function editProduct($id)
    {
        $product = $this->wc->getProduct($id);
        $allCategories = $this->wc->getCategories();
        // dd($allCategories);
        if (!$product) {
            return redirect()->back()->with('error', 'Product not found.');
        }

        return view('admin.woo_products.edit', compact('product','allCategories'));
    }

    public function deleteProduct($id)
    {
        try {
            // Delete product from WooCommerce
            $product = $this->wc->deleteProduct($id, false); // false = don't force delete (trash instead)

            // Also delete from local DB if exists
            $localProduct = WooProduct::where('woo_id', $id)->first();
            if ($localProduct) {
                $localProduct->delete();
            }

            return redirect()
                ->route('wc.products')
                ->with('success', 'Product deleted from WooCommerce and local database successfully.');

        } catch (\Exception $e) {
            return redirect()
                ->route('wc.products')
                ->with('error', 'Failed to delete product: ' . $e->getMessage());
        }
    }


    public function addProduct()
    {   
        $allCategories = $this->wc->getCategories();
        return view('admin.woo_products.create',compact('allCategories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'          => 'required',
            'regular_price' => 'required|numeric',
            'image'         => 'nullable|image|max:2048',
            'categories'    => 'nullable|array',
        ]);

        $imageData = null;

        // ✅ Upload to WordPress
        // if ($request->hasFile('image')) {
        //     $imageData = $this->wc->uploadImageToWordPress($request->file('image'));
        // }
        // dd($imageData);

       $productData = [
            'name'              => $request->name,
            'slug'              => $request->slug ?? '',
            'type'              => 'simple', // Assuming simple product, change if needed
            'regular_price'     => $request->regular_price,
            'sale_price'        => $request->sale_price ?? '',
            'description'       => $request->description ?? '',
            'short_description' => $request->short_description ?? '',
            'sku'               => $request->sku ?? '',
            'manage_stock'      => $request->has('manage_stock') ? true : false,
            'stock_quantity'    => $request->stock_quantity ? (int) $request->stock_quantity : null,
            'stock_status'      => $request->stock_status ?? 'instock',
            'status'            => $request->status ?? 'publish',
            'tax_status'        => $request->tax_status ?? 'taxable',
            'tax_class'         => $request->tax_class ?? '',
            'backorders'        => $request->backorders ?? 'no',
            'featured'          => $request->has('featured') ? true : false,
            'categories'        => collect($request->categories ?? [])->map(fn($id) => ['id' => (int) $id])->toArray(),
            // 'images'            => $imageData ? [['id' => $imageData['id']]] : [],
        ];


        try {
            $wooProduct = $this->wc->createProduct($productData); // Add this method next

            // Save locally in DB
            WooProduct::create([
                'woo_id'         => $wooProduct->id ?? null,
                'name'           => $wooProduct->name ?? null,
                'slug'           => $wooProduct->slug ?? null,
                'regular_price'  => $wooProduct->regular_price ?? null,
                'sale_price'     => $wooProduct->sale_price ?? null,
                'sku'            => $wooProduct->sku ?? null,
                'stock_quantity' => $wooProduct->stock_quantity ?? null,
                'stock_status'   => $wooProduct->stock_status ?? null,
                'status'         => $wooProduct->status ?? null,
                'description'    => $wooProduct->description ?? null,
                'short_description' => $wooProduct->short_description ?? null,
                'manage_stock'   => $wooProduct->manage_stock ?? false,
                'tax_status'     => $wooProduct->tax_status ?? null,
                'tax_class'      => $wooProduct->tax_class ?? null,
                'backorders'     => $wooProduct->backorders ?? null,
                'featured'       => $wooProduct->featured ?? false,
                // 'image_url'      => $imageData->source_url ?? null,  // assuming $imageData is also an object
            ]);

            return redirect()->route('wc.products')->with('success', 'Product created successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Product creation failed: ' . $e->getMessage());
        }
    }

    public function updateProduct(Request $request, $id)
    {
        // Validate input fields
        // dd($request->categories);
 
        // dd($categories);
        $request->validate([
            'name'             => 'required|string|max:255',
            'slug'             => 'nullable|string|max:255',
            'description'      => 'nullable|string',
            'short_description'=> 'nullable|string',
            'sku'              => 'nullable|string|max:255',
            'regular_price'    => 'nullable|numeric',
            'sale_price'       => 'nullable|numeric',
            'manage_stock'     => 'nullable|boolean',
            'stock_quantity'   => 'nullable|integer',
            'stock_status'     => 'nullable|string',
            'status'            => 'required|string',
            // 'virtual'          => 'nullable|boolean',
            // 'downloadable'     => 'nullable|boolean',
            // 'download_limit'   => 'nullable|integer',
            'download_expiry'  => 'nullable|integer',
            'tax_status'       => 'nullable|string',
            'tax_class'        => 'nullable|string',
            'backorders'       => 'nullable|string',
            'featured'         => 'nullable|boolean',
            'sale_start'       => 'nullable|date',
            'sale_end'         => 'nullable|date',
            // 'images'           => 'nullable|string',      // JSON string
            // 'categories'       => 'nullable|string',      // JSON string
            'dimensions'       => 'nullable|string',      // JSON string
            'attributes'       => 'nullable|string',      // JSON string
        ]);

        // Parse JSON fields safely
        // $images = null;
        $categories = null;
        $dimensions = null;
        $attributes = null;

        try {
            $images = $request->images ? json_decode($request->images, true) : null;
            // $categories = $request->categories ? json_decode($request->categories, true) : null;
            $categories = collect($request->categories ?? [])
    ->map(fn($id) => ['id' => (int) $id])
    ->toArray();
    // dd($categories);
            // $dimensions = $request->dimensions ? json_decode($request->dimensions, true) : null;
            // $dimensions = $request->input('dimensions') ? json_decode($request->input('dimensions'), true) : null;
            // $attributes = $request->attributes ? json_decode($request->attributes, true) : null;
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->withErrors(['json_error' => 'Invalid JSON format in one of the fields.']);
        }
        // dd($dimensions);
        // Prepare data for WooCommerce API
        $data = [
            // 'name'               => $request->name,
            'name'               => html_entity_decode($request->name),
            'slug'               => html_entity_decode($request->slug),
            'description'        => html_entity_decode($request->description),
            'short_description'  => html_entity_decode($request->short_description),
            'sku'                => html_entity_decode($request->sku),
            'regular_price'    => $request->regular_price,
            'sale_price'       => $request->sale_price,
            'manage_stock'     => $request->has('manage_stock') ? (bool)$request->manage_stock : false,
            'stock_quantity'   => $request->stock_quantity,
            'stock_status'     => $request->stock_status,
            'status'           => $request->status,
            'virtual'          => $request->has('virtual') ? (bool)$request->virtual : false,
            // 'downloadable'     => $request->has('downloadable') ? (bool)$request->downloadable : false,
            // 'download_limit'   => $request->download_limit,
            // 'download_expiry'  => $request->download_expiry,
            'tax_status'       => $request->tax_status,
            'tax_class'        => html_entity_decode($request->tax_class),
            'backorders'       => $request->backorders,
            'featured'         => $request->has('featured') ? (bool)$request->featured : false,
            // Sale dates: format to ISO8601 (WooCommerce expects this format)
            // 'sale_start'       => $request->sale_start ? \Carbon\Carbon::parse($request->sale_start)->toIso8601String() : null,
            // 'sale_end'         => $request->sale_end ? \Carbon\Carbon::parse($request->sale_end)->toIso8601String() : null,
            // 'images'           => $images,
            'categories'       => $categories,
            // 'dimensions'       => $dimensions,
            // 'attributes'       => $attributes,
        ];

        try {
            // 1. Update product via WooCommerce API
            $updatedProduct = $this->wc->updateProduct($id, $data);

            // 2. Update local DB record
            WooProduct::where('woo_id', $id)->update([
                'name'            => $updatedProduct->name ?? $request->name,
                'slug'            => $updatedProduct->slug ?? $request->slug,
                'description'     => $updatedProduct->description ?? $request->description,
                'short_description'=> $updatedProduct->short_description ?? $request->short_description,
                'sku'             => $updatedProduct->sku ?? $request->sku,
                'regular_price'   => is_numeric($updatedProduct->regular_price) ? $updatedProduct->regular_price : $request->regular_price,
                'sale_price'      => is_numeric($updatedProduct->sale_price) ? $updatedProduct->sale_price : $request->sale_price,
                'price'           => is_numeric($updatedProduct->price) ? $updatedProduct->price : null,
                'manage_stock'    => $updatedProduct->manage_stock ?? ($request->has('manage_stock') ? 1 : 0),
                'stock_quantity'  => $updatedProduct->stock_quantity ?? $request->stock_quantity,
                'stock_status'    => $updatedProduct->stock_status ?? $request->stock_status,
                'status'    => $updatedProduct->status ?? $request->status,
                // 'virtual'         => $updatedProduct->virtual ?? ($request->has('virtual') ? 1 : 0),
                // 'downloadable'    => $updatedProduct->downloadable ?? ($request->has('downloadable') ? 1 : 0),
                // 'download_limit'  => $updatedProduct->download_limit ?? $request->download_limit,
                // 'download_expiry' => $updatedProduct->download_expiry ?? $request->download_expiry,
                'tax_status'      => $updatedProduct->tax_status ?? $request->tax_status,
                'tax_class'       => $updatedProduct->tax_class ?? $request->tax_class,
                'backorders'      => $updatedProduct->backorders ?? $request->backorders,
                'featured'        => $updatedProduct->featured ?? ($request->has('featured') ? 1 : 0),
                // 'sale_start'      => $updatedProduct->sale_start ?? $request->sale_start,
                // 'sale_end'        => $updatedProduct->sale_end ?? $request->sale_end,
                // 'images'          => $updatedProduct->images ? json_encode($updatedProduct->images) : json_encode($images),
                'categories'      => $updatedProduct->categories ? json_encode($updatedProduct->categories) : json_encode($categories),
                // 'dimensions'      => $updatedProduct->dimensions ? json_encode($updatedProduct->dimensions) : json_encode($dimensions),
                // 'attributes'      => $updatedProduct->attributes ? json_encode($updatedProduct->attributes) : json_encode($attributes),
            ]);

            return redirect()->route('wc.products', $id)->with('success', 'Product updated successfully.');

        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Update failed: ' . $e->getMessage());
        }
    }



    public function orders(Request $request)
    {
        $perPage = $request->input('per_page', 30);
        $page    = $request->input('page', 1);

        $paginator = $this->wc->getOrders($perPage, $page);
        // dd($paginator);

        return view('admin.woo_orders.index', compact('paginator'));
    }

    // public function orders(Request $request)
    // {
    //     $perPage = $request->input('per_page', 2);
    //     $page    = $request->input('page', 1);

    //     $orders = $this->wc->getOrders($perPage, $page);

    //     $response = $this->wc->getLastResponse();
    //     $total    = $response->getHeaders()['X-WP-Total'][0] ?? count($orders);

    //     $paginator = new LengthAwarePaginator(
    //         $orders,
    //         $total,
    //         $perPage,
    //         $page,
    //         ['path' => $request->url(), 'query' => $request->query()]
    //     );
    //     // dd($paginator);
    //     return view('admin.woo_orders.index', compact('paginator'));
    // }
    
    public function order($id)
    {
        // Fetch the order
        $order = $this->wc->getOrder($id);

        // Collect all product IDs in the order
        $productIds = collect($order->line_items)->pluck('product_id')->filter()->unique();

        // Fetch product details for all products in a single loop (or cache)
        $products = [];
        foreach ($productIds as $pid) {
            try {
                $products[$pid] = $this->wc->getProduct($pid);
            } catch (\Exception $e) {
                $products[$pid] = null; // fallback if product deleted
            }
        }

        // Pass to Blade
        return view('admin.woo_orders.show', [
            'order'    => $order,
            'products' => $products,
        ]);
    }

    public function payments(Request $request)
    {
        $perPage = $request->input('per_page', 30);
        $page    = $request->input('page', 1);

        $paginator = $this->wc->getOrdersByStatus($perPage, $page,'completed');
        // dd($paginator);

        return view('admin.wc_payments.index', compact('paginator'));
    }

    public function pendingOrders(Request $request)
    {
        $perPage = $request->input('per_page', 30);
        $page    = $request->input('page', 1);

        $paginator = $this->wc->getOrdersByStatus($perPage, $page,'pending');
        // dd($paginator);

        return view('admin.woo_orders.pending-orders', compact('paginator'));
    }

    public function completedOrders(Request $request)
    {
        $perPage = $request->input('per_page', 30);
        $page    = $request->input('page', 1);

        $paginator = $this->wc->getOrdersByStatus($perPage, $page,'completed');
        // dd($paginator);

        return view('admin.woo_orders.complete-orders', compact('paginator'));
    }

    public function processingOrders(Request $request)
    {
        $perPage = $request->input('per_page', 30);
        $page    = $request->input('page', 1);

        $paginator = $this->wc->getOrdersByStatus($perPage, $page,'processing');
        // dd($paginator);

        return view('admin.woo_orders.processing-orders', compact('paginator'));
    }

    public function updateStatus(Request $request, $id)
    {   
        $request->validate([
            'status' => 'required|string'
        ]);

        $status = $request->status;

        try {
            // ✅ Update WooCommerce order (your wrapper must return full order object)
            $order = $this->wc->updateOrder($id, $status);

            // ✅ Sync with local DB
            WooOrder::updateOrCreate(
                ['woo_id' => $order->id], // match WooCommerce order ID
                [
                     'status'          => $order->status,
                    'currency'        => $order->currency,
                    'total'           => $order->total,
                    'subtotal'        => !empty($order->line_items)
                                            ? collect($order->line_items)->sum(fn($item) => (float) $item->subtotal)
                                            : 0,
                    'billing'         => json_encode($order->billing),
                    'shipping'        => json_encode($order->shipping),
                    'line_items'      => json_encode($order->line_items),
                    'tax_lines'       => json_encode($order->tax_lines),
                    'shipping_lines'  => json_encode($order->shipping_lines),
                ]
            );

            return redirect()->back()->with('success', 'Order status updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update order status: ' . $e->getMessage());
        }
    }


    public function sync(WooCommerceService $woo)
    {
        $woo->syncProducts();
        return response()->json(['message' => 'Products synced successfully.']);
    }

    /**
     * SYnc woocommerce product,order and categories
    **/
    public function syncAll(WooCommerceService $woo)
    {
        $woo->syncCategories();
        $woo->syncOrders();
        $woo->syncProducts();

        return redirect()
            ->route('wc.products')
            ->with('success', 'Categories, Products, and Orders synced from Woocommerce.');
        // return response()->json(['message' => 'Categories, Products, and Orders synced.']);
    }

    /*****
     * Get Customers
    *****/
    public function customers(Request $request)
    {
        $perPage = $request->input('per_page', 30);
        $page    = $request->input('page', 1);

        $paginator = $this->wc->getPaginatedCustomers($perPage, $page);
        // dd($paginator);

        return view('admin.wc_customers.index', compact('paginator'));
    }

    public function customer($id)
    {   
        
        $data = $this->wc->getCustomer($id);
        // dd($product);
        if (!$data) {
            return redirect()->back()->with('error', 'Customer not found.');
        }

        return view('admin.wc_customers.show', compact('data'));
    }

     /*****
     * Get Categories
    *****/
    public function categories(Request $request)
    {
        $perPage = $request->input('per_page', 30);
        $page    = $request->input('page', 1);

        $paginator = $this->wc->getPaginatedCategories($perPage, $page);
        // dd($paginator);

        return view('admin.wc_categories.index', compact('paginator'));
    }

    public function category($id)
    {   
        
        $data = $this->wc->getCategory($id);
        // dd($product);
        if (!$data) {
            return redirect()->back()->with('error', 'Customer not found.');
        }

        return view('admin.wc_customers.show', compact('data'));
    }

    // import products in bulk from excel file

    public function importProductsPage()
    {
        return view('admin.woo_products.import_products');
    }

    public function importProducts(Request $request, WooCommerceService $wooService)
    {
        // die('here');
        // ✅ Validate uploaded file
        $request->validate([
            'file' => 'required|file|mimes:xlsx,csv,txt', // limit to 5MB
        ]);

        try {
            // 🧩 Import + Sync products (handles both local + WooCommerce sync)
            Excel::import(new WooProductsImport($wooService), $request->file('file'));

            return redirect()
                ->route('products.index')
                ->with('success', '✅ Products imported and synced with WooCommerce successfully!');
        } catch (\Throwable $e) {
            \Log::error('❌ Product import failed', ['error' => $e->getMessage()]);
            
            return back()->with('error', 'Import failed: ' . $e->getMessage());
        }
    }

//     public function importProducts(Request $request, WooCommerceService $wooService)
// {
//     $request->validate([
//         'file' => 'required|file|mimes:xlsx,csv,txt',
//     ]);

//     $file = $request->file('file');

//     // --------------------------------------------
//     // ✅ STEP 1: Read header row BEFORE importing
//     // --------------------------------------------
//     $data = \Maatwebsite\Excel\Facades\Excel::toArray([], $file);

//     if (empty($data) || empty($data[0]) || empty($data[0][0])) {
//         return back()->with('error', 'Uploaded file is empty or unreadable.');
//     }

//     // Convert header to lowercase for safe matching
//     $headers = array_map('strtolower', $data[0][0]);

//     // Required header order
//     $requiredHeaders = [
//         'name',
//         'store_id',
//         'sku',
//         'description',
//         'price',
//         'quantity',
//     ];

//     // --------------------------------------------
//     // ✅ Missing column(s) check
//     // --------------------------------------------
//     $missing = array_diff($requiredHeaders, $headers);
//     if (!empty($missing)) {
//         return back()->with('error', 
//             'Missing required column(s): ' . implode(', ', $missing)
//         );
//     }

//     // --------------------------------------------
//     // ✅ Wrong order check
//     // --------------------------------------------
//     if ($headers !== $requiredHeaders) {
//         return back()->with('error',
//             "Invalid column order! Required order: " . implode(' → ', $requiredHeaders)
//         );
//     }

//     // --------------------------------------------
//     // ✅ STEP 2: If everything is OK → Import file
//     // --------------------------------------------
//     try {
//         \Maatwebsite\Excel\Facades\Excel::import(
//             new WooProductsImport($wooService),
//             $file
//         );

//         return redirect()
//             ->route('products.index')
//             ->with('success', 'Products imported and synced successfully!');

//     } catch (\Throwable $e) {
//         \Log::error('Product import failed', ['error' => $e->getMessage()]);

//         return back()->with('error', 'Import failed: ' . $e->getMessage());
//     }
// }

//    public function importProducts(Request $request)
// {
//     die('he');
//     $request->validate([
//         'file' => 'required|file|mimes:xlsx,csv',
//     ]);

//     $file = $request->file('file');

//     /** ------------------------------------------------------
//      *  STEP 1: READ HEADER ROW BEFORE IMPORT STARTS
//      * ----------------------------------------------------- */
//     $data = \Maatwebsite\Excel\Facades\Excel::toArray([], $file);

//     if (empty($data) || empty($data[0]) || empty($data[0][0])) {
//         return back()->with('error', 'Uploaded file is empty or unreadable.');
//     }

//     // Convert header names to lowercase
//     $headers = array_map('strtolower', $data[0][0]);

//     $requiredHeaders = [
//         'name',
//         'store_id',
//         'sku',
//         'description',
//         'price',
//         'quantity',
//     ];

//     // 1️⃣ Missing headers?
//     $missing = array_diff($requiredHeaders, $headers);
//     if (!empty($missing)) {
//         return back()->with(
//             'error',
//             'Missing required column(s): ' . implode(', ', $missing)
//         );
//     }

//     // 2️⃣ Incorrect order?
//     if ($headers !== $requiredHeaders) {
//         return back()->with(
//             'error',
//             "Invalid column order! Required: " . implode(' → ', $requiredHeaders)
//         );
//     }

//     /** ------------------------------------------------------
//      *  STEP 2: Import file (safe now)
//      * ----------------------------------------------------- */
//     try {
//         $importer = new \App\Imports\ItemsImport();

//         Excel::import($importer, $file);

//         // Create log file if errors exist
//         $logFile = null;

//         if (!empty($importer->errors)) {
//             $logFile = 'items-import-log-' . time() . '.txt';
//             \Storage::put($logFile, implode("\n", $importer->errors));
//         }

//         return back()
//             ->with('success', "Imported: {$importer->imported}, Skipped: {$importer->skipped}")
//             ->with('logFile', $logFile);

//     } catch (\Throwable $e) {

//         return back()->with('error', 'Import failed: ' . $e->getMessage());
//     }
// }





}
