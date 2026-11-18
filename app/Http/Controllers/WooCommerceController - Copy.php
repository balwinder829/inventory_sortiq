<?php

namespace App\Http\Controllers;

use App\Services\WooCommerceService;
// use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
class WooCommerceController extends Controller
{
    protected $wc;

    public function __construct(WooCommerceService $wc)
    {
        $this->wc = $wc;
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

//   public function products(Request $request)
// {
//     $perPage = $request->input('per_page', 30);
//     $page    = $request->input('page', 1);

//     $result = $this->wc->getProducts($perPage, $page);
//     $products = $result['products'];
//     $headers = $result['headers'];

//     $total = $headers['X-WP-Total'][0] ?? count($products);

//     $paginator = new \Illuminate\Pagination\LengthAwarePaginator(
//         $products,
//         $total,
//         $perPage,
//         $page,
//         ['path' => $request->url(), 'query' => $request->query()]
//     );

//     return view('admin.woo_products.index', compact('paginator'));
// }


// public function products(Request $request)
// {
//     $perPage = $request->input('per_page', 30);
//     $page    = $request->input('page', 1);

//     $result = $this->wc->getProducts($perPage, $page);
//     $products = $result['products'];
//     $headers = $result['headers'];

//     // Total products from header
//     $total = $headers['X-WP-Total'][0] ?? count($products);

//     $paginator = new LengthAwarePaginator(
//         $products,
//         $total,
//         $perPage,
//         $page,
//         ['path' => $request->url(), 'query' => $request->query()]
//     );

//     return view('admin.woo_products.index', compact('paginator'));
// }

public function products(Request $request)
    {
        $perPage = $request->input('per_page', 30); // items per page
        $page    = $request->input('page', 1);     // current page

        // Fetch products page from WooCommerce
        $result = $this->wc->getProducts($perPage, $page);
        $products = $result['products'] ?? [];
        $headers = $result['headers'] ?? [];

        // Get total number of products from WooCommerce header
        $total = $headers['X-WP-Total'][0] ?? count($products);

        // Wrap products in a collection for Laravel pagination
        $items = collect($products);

        // Create paginator
        $paginator = new LengthAwarePaginator(
            $items,
            $total,
            $perPage,
            $page,
            [
                'path' => $request->url(),
                'query' => $request->query()
            ]
        );

        return view('admin.woo_products.index', compact('paginator'));
    }


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





    // public function orders()
    // {
    //     $orders = $this->wc->getOrders();
    //     return response()->json($orders);
    // }
}
