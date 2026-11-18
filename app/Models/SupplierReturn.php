<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\StoreScope;

class SupplierReturn extends Model
{
    use HasFactory, StoreScope;

    protected $fillable = [
        'store_id',
        'supplier_id',
        'return_date',
        'reference_no',
        'total_refund_amount',
        'reason',
        'created_by',
    ];

    protected $casts = [
        'return_date'         => 'date',
        'total_refund_amount' => 'decimal:2',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function returnItems()
    {
        return $this->hasMany(SupplierReturnItem::class);
    }
}
