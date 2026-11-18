<?php

namespace App\Http\Controllers;

use App\Services\WooCommerceService;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use App\Models\WooProduct;
use Illuminate\Support\Facades\Http;

class WooCommerceController extends Controller
{
    protected $wc;

    public function __construct(WooCommerceService $wc)
    {
        $this->wc = $wc;
    }

    public function products(Request $request)
    {
        $perPage = $request->input('per_page', 30);
        $page    = $request->input('page', 1);

        $paginator = $this->wc->getPaginatedProducts($perPage, $page);

        return view('admin.woo_products.index', compact('paginator'));
    }

    // public function products(Request $request)
    // {
    //     $perPage = $request->input('per_page', 30);
    //     $page    = $request->input('page', 1);

    //     // Fetch all products manually
    //     $allProducts = [];
    //     $fetchPage = 1;
    //     do {
    //         $batch = $this->wc->getProducts(100, $fetchPage);
    //         if (empty($batch)) break;
    //         $allProducts = array_merge($allProducts, $batch);
    //         $fetchPage++;
    //     } while (count($batch) === 100);

    //     $total = count($allProducts);

    //     // Slice products for current page
    //     $items = array_slice($allProducts, ($page - 1) * $perPage, $perPage);

    //     $paginator = new \Illuminate\Pagination\LengthAwarePaginator(
    //         $items,
    //         $total,
    //         $perPage,
    //         $page,
    //         ['path' => $request->url(), 'query' => $request->query()]
    //     );

    //     return view('admin.woo_products.index', compact('paginator'));
    // }

    // public function product($id)
    // {
    //     $product = $this->wc->getProduct($id);
    //     return view('admin.woo_products.show', compact('product'));
    // }

    public function product($id)
    {   
        
        $product = $this->wc->getProduct($id);
        // dd($product);
        if (!$product) {
            return redirect()->back()->with('error', 'Product not found.');
        }

        return view('admin.woo_products.show', compact('product'));
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

    // public function deleteProduct($id)
    // {
    //     // return redirect()->route('wc.products', $id)->with('success', 'Product updated successfully.');
    //     // dd($id);
    //     try {
    //         // Delete product permanently (force = true by default)
    //         $product = $this->wc->deleteProduct($id, false);

    //         return redirect()
    //             ->route('wc.products')
    //             ->with('success', 'Product deleted from WooCommerce successfully.');

    //     } catch (\Exception $e) {
    //         return redirect()
    //             ->route('wc.products')
    //             ->with('error', 'Failed to delete product: ' . $e->getMessage());
    //     }
    // }

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
        $perPage = $request->input('per_page', 20);
        $page    = $request->input('page', 1);

        $orders = $this->wc->getOrders($perPage, $page);

        $response = $this->wc->getLastResponse();
        $total    = $response->getHeaders()['X-WP-Total'][0] ?? count($orders);

        $paginator = new LengthAwarePaginator(
            $orders,
            $total,
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );
        // dd($paginator);
        return view('admin.woo_orders.index', compact('paginator'));
    }
    
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





    // public function orders()
    // {
    //     $orders = $this->wc->getOrders();
    //     return response()->json($orders);
    // }
}
