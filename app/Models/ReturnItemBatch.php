<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReturnItemBatch extends Model
{
    protected $table = 'return_item_batches';

    public $timestamps = false;

    protected $fillable = [
        'return_item_id',
        'invoice_item_batch_id',
        'batch_id',
        'item_id',
        'quantity',
        'buy_price',
        'sale_price',
        'cost_amount',
        'refund_amount',
        'profit_reversed',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function returnItem()
    {
        return $this->belongsTo(ReturnItem::class);
    }

    public function invoiceItemBatch()
    {
        return $this->belongsTo(InvoiceItemBatch::class, 'invoice_item_batch_id');
    }

    public function batch()
    {
        return $this->belongsTo(ItemBatch::class, 'batch_id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}