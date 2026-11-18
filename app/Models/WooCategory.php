<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WooCategory extends Model
{
    protected $table = 'woocommerce_categories';
    protected $fillable = ['woo_id', 'name', 'slug', 'description', 'parent', 'image', 'menu_order'];
}

