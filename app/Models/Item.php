<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\StoreScope;


class Item extends Model
{
    use HasFactory;
    use SoftDeletes;
    use StoreScope;

    protected $fillable = [
        'store_id',
        'name',
        'description',
        'category',
        'price',
        'cost_price',
        'quantity',
        'sku',
        // 'image_path',
        'brand',
        'weight',
        'dimensions',
        'status',
        'tags',
        'sale_price',
        'avg_cost',
    ];

    protected $casts = [
        'tags' => 'array',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function scopeLowStock($query, $threshold = null)
    {
         // If no threshold passed, use config value
        $limit = $threshold ?? config('app.low_stock_limit', 10);
        
        return $query->where('quantity', '<=', $limit);
    }

    public function batches()
    {
        return $this->hasMany(\App\Models\ItemBatch::class);
    }



}
