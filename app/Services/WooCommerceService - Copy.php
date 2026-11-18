<?php

namespace App\Services;

use Automattic\WooCommerce\Client;

class WooCommerceService
{
    protected $woocommerce;

    public function __construct()
    {
        $this->woocommerce = new Client(
            config('woocommerce.store_url'),
            config('woocommerce.consumer_key'),
            config('woocommerce.consumer_secret'),
            [
                'version' => 'wc/v3',
                'verify_ssl' => false, // for local
            ]
        );
    }

    public function getProducts($perPage = 10)
    {
        return $this->woocommerce->get('products', ['per_page' => $perPage]);
    }

      /**
     * Get orders
     */
    public function getOrders($perPage = 10, $page = 1, $status = null)
    {
        $params = [
            'per_page' => $perPage,
            'page'     => $page,
        ];

        if ($status) {
            $params['status'] = $status; // e.g., 'completed', 'pending', etc.
        }

        return $this->woocommerce->get('orders', $params);
    }
}
