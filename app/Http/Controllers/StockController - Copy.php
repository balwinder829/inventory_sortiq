<?php

namespace App\Http\Controllers;

use App\Services\WooCommerceService;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use App\Models\WooProduct;
use App\Models\WooOrder;
use Illuminate\Support\Facades\Http;
use App\Models\Product;
use App\Models\Sale;
use App\Models\Item;

class StockController extends Controller
{
    protected $wc;

    public function __construct(WooCommerceService $wc)
    {
        $this->wc = $wc;
    }

    public function index()
    {
       $products = Item::all();
       // dd($products);
        return view('admin.stock.index', compact('products'));
    }

    public function edit(Item $item)
    {
        // dd($item);
        return view('admin.stock.update', compact('item'));
    }

    public function update(Request $request, Item $item)
    {
        $validated = $request->validate([
            'store_id' => 'required|exists:stores,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:100',
            'price' => 'required|numeric|min:0',
            'cost_price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            // Ignore current item's SKU in unique check
            // 'sku' => 'required|string|max:100|unique:items,sku,' . $item->id,
            'image_path' => 'nullable|image|max:2048',
            'brand' => 'nullable|string|max:100',
            'weight' => 'nullable|string|max:50',
            'dimensions' => 'nullable|string|max:100',
            // 'status' => 'required|in:available,out_of_stock,discontinued',
            'status' => 'required|in:publish,draft',
            // 'tags' => 'nullable|string',
            // 'sale_price' => 'nullable|numeric|min:0|lt:price',
        ]);

        if (!empty($validated['tags'])) {
            $validated['tags'] = array_map('trim', explode(',', $validated['tags']));
        } else {
            $validated['tags'] = [];
        }

        // if ($request->hasFile('image_path')) {
        //     $validated['image_path'] = $request->file('image_path')->store('items', 'public');
        // }

        $item->update($validated);

        return redirect()->route('stocks.index')
                 ->with('success', 'Product updated successfully.');
        // return redirect()->route('stores.show', $validated['store_id'])
                 // ->with('success', 'Item created successfully.');
        // return redirect()->route('items.show', $item)->with('success', 'Item updated successfully.');
    }


    public function stock()
    {
       $products = WooProduct::all();

        return view('admin.stock.create', compact('products'));
    }

    // public function updateStock(Request $request)
    // {

    //     $request->validate([
    //         'woo_id' => 'required|exists:woocommerce_products,woo_id',
    //         'stock_quantity' => 'required|integer|min:1',
    //     ]);

    //      $product = WooProduct::where('woo_id', $request->woo_id)->first();

    //     if (!$product) {
    //         return back()->with('error', 'Product not found.');
    //     }
    //     // dd($product);
    //     // die('here2');
    //     // Increase stock
    //     $product->stock_quantity += $request->stock_quantity;
    //     $product->save();

    //     return redirect()->route('wc.stock')
    //     ->with('success', 'Stock updated successfully!');
    //     // return back()->with('success', 'Stock updated successfully!');
    // }

    public function updateStock(Request $request)
    {
        $request->validate([
            'woo_id' => 'required|exists:woocommerce_products,woo_id',
            'stock_quantity' => 'required|integer|min:1',
        ]);

        // Find product in local DB
        $product = WooProduct::where('woo_id', $request->woo_id)->first();

        if (!$product) {
            return back()->with('error', 'Product not found.');
        }

        // Calculate new total stock
        $newQuantity = ($product->stock_quantity ?? 0) + $request->stock_quantity;

        try {
            // ✅ 1. Update local database
            $product->stock_quantity = $newQuantity;
            $product->save();

            // ✅ 2. Update WooCommerce via your existing wrapper
            $data = [
                'manage_stock'   => true,        // Ensure stock tracking is enabled
                'stock_quantity' => $newQuantity // Update quantity on WooCommerce
            ];

            $updatedProduct = $this->wc->updateProduct($product->woo_id, $data);

            // ✅ Optional: verify response
            if (!empty($updatedProduct) && isset($updatedProduct->stock_quantity)) {
                // keep local DB consistent (in case WooCommerce adjusted value)
                $product->stock_quantity = $updatedProduct->stock_quantity;
                $product->save();
            }

            return redirect()->route('wc.stock')
                ->with('success', "Stock updated successfully on both Local DB and WooCommerce! (New Qty: {$newQuantity})");

        } catch (\Exception $e) {
            return back()->with('error', 'Stock update failed: ' . $e->getMessage());
        }
    }


    public function addStock(Request $request, Product $product)
    {
        $request->validate(['quantity' => 'required|integer|min:1']);

        $product->stock_quantity += $request->quantity;
        $product->save();

        app(WooCommerceService::class)->updateStock($product->woo_id, $product->stock_quantity);

        return redirect()->back()->with('success', 'Stock updated successfully!');
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);

        if ($product->stock_quantity < $request->quantity) {
            return redirect()->back()->with('error', 'Not enough stock available.');
        }

        $product->stock_quantity -= $request->quantity;
        $product->save();

        Sale::create([
            'product_id' => $product->id,
            'quantity' => $request->quantity,
        ]);

        app(WooCommerceService::class)->updateStock($product->woo_id, $product->stock_quantity);

        return redirect()->back()->with('success', "Sale recorded. Remaining stock: {$product->stock_quantity}");
    }
}
