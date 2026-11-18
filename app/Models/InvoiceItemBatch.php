<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceItemBatch extends Model
{
    protected $table = 'invoice_item_batches';

    protected $fillable = [
        'invoice_id',
        'invoice_item_id',
        'item_id',
        'batch_id',
        'quantity',
        'buy_price',
        'sale_price',
        'cost_amount',
        'sale_amount',
        'profit',
    ];

    // 🔗 Parent invoice line
    public function invoiceItem()
    {
        return $this->belongsTo(InvoiceItem::class, 'invoice_item_id');
    }

    // 🔗 Original stock batch
    public function batch()
    {
        return $this->belongsTo(ItemBatch::class, 'batch_id');
    }

    // 🔗 Product
    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }
}
