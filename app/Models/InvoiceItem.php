<?php

namespace App\Models;

use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InvoiceItem extends Model
{
    protected $fillable = [
        'invoice_id', 'product_id', 'price', 'buy_price', 'quantity', 'subtotal', 'profit'
    ];

    public function product()
    {
        return $this->belongsTo(Item::class)->withTrashed();
    }

    public function batches()
    {
        return $this->hasMany(
            \App\Models\InvoiceItemBatch::class,
            'invoice_item_id'
        );
    }

    public function returnBatches()
    {
        return $this->hasMany(ReturnItemBatch::class, 'invoice_item_batch_id');
    }

}
