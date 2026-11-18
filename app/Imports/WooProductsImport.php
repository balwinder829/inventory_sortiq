<?php

namespace App\Imports;

use App\Models\WooProduct;
use App\Services\WooCommerceService;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;

class WooProductsImport implements ToCollection, WithHeadingRow
{
    protected $wooService;

    public function __construct()
    {
        $this->wooService = new WooCommerceService();
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            if (empty($row['sku']) && empty($row['name'])) {
                continue;
            }

            // Step 1️⃣: Save to local DB
            $product = WooProduct::updateOrCreate(
                ['sku' => $row['sku']],
                [
                    'name'            => $row['name'] ?? '',
                    'regular_price'   => $row['regular_price'] ?? 0,
                    'sale_price'      => $row['sale_price'] ?? null,
                    'price'           => $row['price'] ?? ($row['sale_price'] ?? $row['regular_price']),
                    'stock_quantity'  => $row['stock_quantity'] ?? 0,
                    'status'          => $row['status'] ?? 'publish',
                    'description'     => $row['description'] ?? '',
                ]
            );

            // Step 2️⃣: Prepare WooCommerce data
            $data = [
                'name'            => $product->name,
                'sku'             => $product->sku,
                'regular_price'   => (string) $product->regular_price,
                'sale_price'      => (string) ($product->sale_price ?? ''),
                'manage_stock'    => true,
                'stock_quantity'  => $product->stock_quantity,
                'status'          => $product->status,
                'description'     => $product->description,
                'type'            => 'simple',
            ];

            try {
                // Step 3️⃣: Sync to WooCommerce
                if ($product->woo_id) {
                    $response = $this->wooService->updateProduct($product->woo_id, $data);
                } else {
                    $response = $this->wooService->createProduct($data);
                    $product->update(['woo_id' => $response->id ?? null]);
                }

                Log::info("Synced product: {$product->sku}");
            } catch (\Throwable $e) {
                Log::error("WooCommerce Sync Failed for SKU {$product->sku}", [
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }
}
