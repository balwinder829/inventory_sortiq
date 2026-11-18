<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\StoreScope;

class Purchase extends Model
{
    use HasFactory;
    use StoreScope;
    
    protected $fillable = [
        'store_id',
        'supplier_id',
        'purchase_date',
        'bill_no',
        'status',
        'total_amount',
        'created_by',
        'updated_by',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class)
            ->withTrashed();
    }

    public function details()
    {
        return $this->hasMany(PurchaseDetails::class);
    }
}