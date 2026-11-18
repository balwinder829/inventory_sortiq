<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WooOrder extends Model
{   
    protected $table = 'woocommerce_orders';
    protected $fillable = [
        'woo_id', 'status', 'currency', 'total', 'subtotal',
        'billing', 'shipping', 'line_items', 'tax_lines', 'shipping_lines'
    ];

    protected $casts = [
        'billing' => 'array',
        'shipping' => 'array',
        'line_items' => 'array',
        'tax_lines' => 'array',
        'shipping_lines' => 'array',
    ];
}

