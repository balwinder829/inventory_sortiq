<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WooProduct extends Model
{   
    protected $table = 'woocommerce_products';
    protected $fillable = [
        'woo_id', 'name', 'slug', 'description', 'short_description',
        'sku', 'regular_price', 'sale_price', 'price',
        'manage_stock', 'stock_quantity', 'stock_status',
        'virtual', 'downloadable', 'download_limit', 'download_expiry',
        'tax_status', 'tax_class', 'backorders', 'featured',
        'thumbnail_id', 'total_sales', 'menu_order',
        'sale_start', 'sale_end', 'images', 'categories', 'dimensions', 'attributes'
    ];

    protected $casts = [
        'images' => 'array',
        'categories' => 'array',
        'dimensions' => 'array',
        'attributes' => 'array',
        'manage_stock' => 'boolean',
        'virtual' => 'boolean',
        'downloadable' => 'boolean',
        'featured' => 'boolean',
    ];
}
