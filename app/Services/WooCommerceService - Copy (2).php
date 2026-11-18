<?php

namespace App\Services;

use Automattic\WooCommerce\Client;

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
            ]
        );
    }

//     public function getProducts($perPage = 10, $page = 1)
// {
//     return $this->woocommerce->get('products', [
//         'per_page' => $perPage,
//         'page'     => $page,
//     ]);
// }

//     public function getProducts($perPage = 10, $page = 1)
// {
//     try {
//         $products = $this->woocommerce->get('products', [
//             'per_page' => $perPage,
//             'page' => $page,
//         ]);

//         // Return both products and last response
//         return [
//             'products' => $products,
//             'response' => $this->woocommerce->http->getLastResponse(), // get headers
//         ];
//     } catch (\Exception $e) {
//         return ['products' => [], 'response' => null];
//     }
// }

// public function getProducts($perPage = 10, $page = 1)
// {
//     try {
//         // Get raw response including headers
//         $response = $this->woocommerce->get('products', [
//             'per_page' => $perPage,
//             'page' => $page,
//         ]);

//         // Headers are not directly accessible from the returned object,
//         // so we'll get total products from the 'X-WP-Total' header via HttpClient
//         $client = $this->woocommerce->http;
//         $headers = $client->getResponse()->getHeaders() ?? [];

//         return [
//             'products' => $response,
//             'headers' => $headers,
//         ];
//     } catch (\Exception $e) {
//         return ['products' => [], 'headers' => []];
//     }
// }
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

}
