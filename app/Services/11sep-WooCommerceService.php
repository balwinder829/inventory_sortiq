<?php

namespace App\Services;

use Automattic\WooCommerce\Client;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use App\Models\WooCategory;
use App\Models\WooOrder;
use App\Models\WooProduct;
use Illuminate\Support\Facades\Http;

class WooCommerceService
{
    protected $woocommerce;

    public function __construct()
    {
        $this->woocommerce = new Client(
           env('WC_STORE_URL'),
            env('WC_CONSUMER_KEY'),
            env('WC_CONSUMER_SECRET'),
            [
                'version'    => 'wc/v3',
                'verify_ssl' => false, // disable only in local/dev
                 'timeout'    => 120,
            ]
        );
    }

    // new way to get all products
    public function getPaginatedProducts($perPage = 30, $page = 1)
    {
        try {
            $response = $this->woocommerce->get('products', [
                'per_page' => $perPage,
                'page' => $page,
            ]);

            $httpResponse = $this->woocommerce->http->getResponse();
            $headers = $httpResponse ? $httpResponse->getHeaders() : [];
           
            $total = $headers['x-wp-total']?? count($response);
             // dd($total);
            // Wrap products in paginator
            return new LengthAwarePaginator(
                $response,
                $total,
                $perPage,
                $page,
                [
                    'path' => request()->url(),
                    'query' => request()->query(),
                ]
            );
        } catch (\Exception $e) {
            // Handle error gracefully — return empty paginator
            return new LengthAwarePaginator([], 0, $perPage, $page);
        }
    }   

    public function getProducts($perPage = 30, $page = 1)
    {
        try {
            // Fetch products page from WooCommerce
            $products = $this->woocommerce->get('products', [
                'per_page' => $perPage,
                'page' => $page,
            ]);

            // Get HTTP client and last response to read headers
            $response = $this->woocommerce->http->getResponse();
            $headers = $response ? $response->getHeaders() : [];
            return [
                'products' => $products,
                'headers' => $headers,
            ];
        } catch (\Exception $e) {
            return [
                'products' => [],
                'headers' => [],
            ];
        }
    }


    /**
     * Get a single product by ID
     */
    public function getProduct($productId)
    {
        // return $this->woocommerce->get("products/{$productId}");
         try {
            // Fetch product from WooCommerce API
            $product = $this->woocommerce->get("products/{$productId}");
            return $product;
        } catch (\Exception $e) {
            return null; // Return null if product not found or API error
        }
    }

    /**
     * Update a single product by ID
     */
    public function updateProduct($id, array $data)
    {
        return $this->woocommerce->put("products/{$id}", $data);
    }

    /**
     * Delete a single product by ID
     * 
     * @param int $id Product ID
     * @param bool $force Permanently delete if true, otherwise move to trash
     * @return mixed
     */
    public function deleteProduct($id, $force = true)
    {
        return $this->woocommerce->delete("products/{$id}", ['force' => $force]);
    }

    public function getLastResponse()
    {
        return $this->woocommerce->http->getResponse();
    }

/**
     * Get orders list
     */
    public function getOrders($perPage = 10, $page = 1, $status = null)
    {
        $params = [
            'per_page' => $perPage,
            'page'     => $page,
        ];

        if ($status) {
            $params['status'] = $status; // e.g. pending, processing, completed
        }

        return $this->woocommerce->get('orders', $params);
    }

    /**
     * Get a single order by ID
     */
    public function getOrder($orderId)
    {
        return $this->woocommerce->get("orders/{$orderId}");
    }

    /***
     * Get all categories list 
     *****/
    public function getCategories()
    {
        try {
            $categories = $this->woocommerce->get('products/categories', [
                'per_page' => 100, // large enough to get all categories in one go
            ]);
            return $categories;
        } catch (\Exception $e) {
            \Log::error('WooCommerce Category Sync Error: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Sync Woocommerce products
     */
    public function syncProducts()
{
    $page = 1;
    $perPage = 100;

    do {
        try {
            $products = $this->woocommerce->get('products', [
                'page' => $page,
                'per_page' => $perPage
            ]);
        } catch (\Exception $e) {
            \Log::error('WooCommerce Product Sync Error: ' . $e->getMessage());
            break; // Stop on error
        }
        // dd($products);
        foreach ($products as $product) {
    WooProduct::updateOrCreate(
        ['woo_id' => $product->id],
        [
            'name'              => $product->name,
            'slug'              => $product->slug,
            'description'       => $product->description,
            'short_description' => $product->short_description,
            'sku'               => $product->sku,
           'regular_price' => is_numeric($product->regular_price) ? $product->regular_price : null,
            'sale_price'    => is_numeric($product->sale_price) ? $product->sale_price : null,
            'price'         => is_numeric($product->price ?? null)
                    ? $product->price
                    : (is_numeric($product->regular_price) ? $product->regular_price : null),

            'manage_stock'      => $product->manage_stock,
            'stock_quantity'    => $product->stock_quantity,
            'stock_status'      => $product->stock_status,
            'status'      => $product->status,
            'virtual'           => $product->virtual,
            'downloadable'      => $product->downloadable,
            'download_limit'    => $product->download_limit,
            'download_expiry'   => $product->download_expiry,
            'tax_status'        => $product->tax_status,
            'tax_class'         => $product->tax_class,
            'backorders'        => $product->backorders,
            'featured'          => $product->featured,
            'thumbnail_id'      => $product->images[0]->id ?? null,
            'total_sales'       => $product->total_sales,
            'menu_order'        => $product->menu_order,
            'sale_start'        => $product->date_on_sale_from,
            'sale_end'          => $product->date_on_sale_to,
            'images'            => json_encode($product->images),
            'categories'        => json_encode($product->categories),
            'dimensions'        => json_encode([
                'length' => $product->dimensions->length ?? null,
                'width'  => $product->dimensions->width ?? null,
                'height' => $product->dimensions->height ?? null,
                'weight' => $product->weight ?? null,
            ]),
            'attributes'        => json_encode($product->attributes),
        ]
    );
}


        $page++;
         sleep(1);
    } while (count($products) === $perPage);
}


   public function syncCategories()
    {
        $page = 1;
        $perPage = 100;

        do {
            try {
                $categories = $this->woocommerce->get('products/categories', [
                    'page' => $page,
                    'per_page' => $perPage,
                ]);
            } catch (\Exception $e) {
                \Log::error('WooCommerce Category Sync Error: ' . $e->getMessage());
                break;
            }

            // foreach ($categories as $category) {
            //     WooCategory::updateOrCreate(
            //         ['woo_id' => $category['id']],
            //         [
            //             'name'        => $category['name'],
            //             'slug'        => $category['slug'],
            //             'description' => $category['description'],
            //             'parent'      => $category['parent'] ?: null,
            //             'image'       => $category['image']['src'] ?? null,
            //             'menu_order'  => $category['menu_order'],
            //         ]
            //     );
            // }

            foreach ($categories as $category) {
                WooCategory::updateOrCreate(
                    ['woo_id' => $category->id],
                    [
                        'name'        => $category->name,
                        'slug'        => $category->slug,
                        'description' => $category->description,
                        'parent'      => $category->parent ?: null,
                        'image'       => $category->image->src ?? null,
                        'menu_order'  => $category->menu_order,
                    ]
                );
            }


            $page++;
            sleep(1); // optional: to avoid rate limiting
        } while (count($categories) === $perPage);
    }

    /**
 * Create a new product in WooCommerce.
 */
public function createProduct(array $data)
{
    return $this->woocommerce->post('products', $data);
}

   public function syncOrders()
{
    $page = 1;
    $perPage = 100;

    do {
        try {
            $orders = $this->woocommerce->get('orders', [
                'page' => $page,
                'per_page' => $perPage,
            ]);
        } catch (\Exception $e) {
            \Log::error('WooCommerce Order Sync Error: ' . $e->getMessage());
            break;
        }

        foreach ($orders as $order) {
            WooOrder::updateOrCreate(
                ['woo_id' => $order->id],
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
        }


        $page++;
        sleep(1); // optional: to avoid rate limit
    } while (count($orders) === $perPage);
}
    /****
     * upload image 
     * ****/


    public function uploadImageToWordPress($file)
    {
        try {
            $imagePath = $file->getPathName();
            $imageName = $file->getClientOriginalName();
            $mimeType = $file->getMimeType();
            $user = 'admin';
            $pwd = 'CwWO BhFI L00P c0SG GZFm AATC';
            // Prepare Basic Auth header with admin username and application password
            $auth = base64_encode($user . ':' . $pwd);
            // $auth = base64_encode(env('WC_ADMIN_USER') . ':' . env('WC_APPLICATION_PASSWORD'));

            $response = Http::withHeaders([
                'Authorization' => 'Basic ' . $auth,
                'Content-Disposition' => 'attachment; filename="' . $imageName . '"',
                'Content-Type' => $mimeType,
            ])->attach(
                'file',
                file_get_contents($imagePath),
                $imageName
            )->post(env('WC_STORE_URL') . '/wp-json/wp/v2/media');

            if ($response->successful()) {
                return $response->json(); // This will include the image ID, URL, etc.
            }

            \Log::error('Image Upload Error: ' . $response->body());
            return null;

        } catch (\Exception $e) {
            \Log::error('Image Upload Exception: ' . $e->getMessage());
            return null;
        }
    }

    // public function uploadImageToWordPress($file)
    // {
    //     try {
    //         $imagePath = $file->getPathName();
    //         $imageName = $file->getClientOriginalName();
    //         $mimeType = $file->getMimeType();

    //         $response = Http::withHeaders([
    //             'Authorization' => 'Basic ' . base64_encode(env('WC_CONSUMER_KEY') . ':' . env('WC_CONSUMER_SECRET')),
    //             'Content-Disposition' => 'attachment; filename="' . $imageName . '"',
    //             'Content-Type' => $mimeType,
    //         ])->attach(
    //             'file',
    //             file_get_contents($imagePath),
    //             $imageName
    //         )->post(env('WC_STORE_URL') . '/wp-json/wp/v2/media');

    //         if ($response->successful()) {
    //             return $response->json(); // Contains image ID, URL, etc.
    //         }

    //         \Log::error('Image Upload Error: ' . $response->body());
    //         return null;

    //     } catch (\Exception $e) {
    //         \Log::error('Image Upload Exception: ' . $e->getMessage());
    //         return null;
    //     }
    // }

    /**
     * Get orders list
     */
    public function getOrders($perPage = 10, $page = 1, $status = null)
    {
        $params = [
            'per_page' => $perPage,
            'page'     => $page,
        ];

        if ($status) {
            $params['status'] = $status; // e.g. pending, processing, completed
        }

        return $this->woocommerce->get('orders', $params);
    }

}
