<?php
// app/Http/Controllers/WooWebhookController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Product;

class WooWebhookController extends Controller
{
    // 🧾 Order Webhook
    public function order(Request $request)
    {
        $data = $request->all();
        Log::info('WooCommerce Order Webhook', $data);

        foreach ($data['line_items'] ?? [] as $item) {
            $product = Product::where('woo_id', $item['product_id'])->first();
            if ($product) {
                $product->stock_quantity = max(0, $product->stock_quantity - $item['quantity']);
                $product->save();
            }
        }

        return response()->json(['status' => 'order processed']);
    }


    public function stockupdate(Request $request){
        die('here');
    }

    // 🛍️ Product Webhook
    public function product(Request $request)
    {
        $data = $request->all();
        Log::info('WooCommerce Product Webhook', $data);

        Product::updateOrCreate(
            ['woo_id' => $data['id']],
            [
                'name' => $data['name'] ?? 'Unnamed',
                'price' => $data['price'] ?? 0,
                'stock_quantity' => $data['stock_quantity'] ?? 0,
            ]
        );

        return response()->json(['status' => 'product synced']);
    }

    // 🏷️ Category Webhook
    public function category(Request $request)
    {
        $data = $request->all();
        Log::info('WooCommerce Category Webhook', $data);
        // You can handle categories here if you store them in DB
        return response()->json(['status' => 'category processed']);
    }

    // 📦 Inventory Update Webhook
    public function inventory(Request $request)
    {
        $data = $request->all();
        Log::info('WooCommerce Inventory Webhook', $data);

        $product = Product::where('woo_id', $data['id'])->first();
        if ($product && isset($data['stock_quantity'])) {
            $product->stock_quantity = $data['stock_quantity'];
            $product->save();
        }

        return response()->json(['status' => 'inventory updated']);
    }
}
