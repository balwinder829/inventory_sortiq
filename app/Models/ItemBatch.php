<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemBatch extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'item_id',
        'quantity',
        'remaining_quantity',
        'cost_price',
        'created_at',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
