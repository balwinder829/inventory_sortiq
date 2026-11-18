<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReturnItem extends Model
{
    protected $fillable = [
        'invoice_id',
        'invoice_item_id',
        'product_id',
        'total_quantity',
        'total_refund_amount',
        'total_profit_reversed',
        'reason',
        'created_by',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function invoiceItem()
    {
        return $this->belongsTo(InvoiceItem::class);
    }

    public function product()
    {
        return $this->belongsTo(Item::class, 'product_id');
    }

    public function batches()
    {
        return $this->hasMany(ReturnItemBatch::class);
    }
}