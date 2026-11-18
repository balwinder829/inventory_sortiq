<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseDetails extends Model
{
    use HasFactory;

    protected $table = 'purchase_details';

    protected $fillable = [
        'purchase_id',
        'store_id',
        'item_id',
        'quantity',
        'unitcost',
        'total',
    ];

    /**
     * Purchase Relation
     */
    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }

    /**
     * Item Relation
     */
    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    /**
     * Store Relation
     */
    public function store()
    {
        return $this->belongsTo(Store::class);
    }
}