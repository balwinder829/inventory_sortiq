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

    public function getProducts($perPage = 10, $page = 1)
{
    return $this->woocommerce->get('products', [
        'per_page' => $perPage,
        'page'     => $page,
    ]);
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
