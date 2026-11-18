<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockHistory extends Model
{
    protected $table = 'stock_history';

    protected $fillable = [
        'item_id',
        'type',          // in / out / adjustment
        'old_quantity',
        'new_quantity',
        'change_amount',
        'cost_price',
        'changed_by',
        'reason',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
