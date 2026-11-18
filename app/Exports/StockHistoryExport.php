<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class StockHistoryExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    protected $itemId;

    public function __construct($itemId)
    {
        $this->itemId = $itemId;
    }

    public function collection()
    {
        return DB::table('stock_history')
            ->leftJoin('users','users.id','=','stock_history.changed_by')
            ->select(
                'old_quantity',
                'new_quantity',
                'change_amount',
                'cost_price',
                'reason',
                'users.name as updated_by',
                'stock_history.created_at'
            )
            ->where('item_id', $this->itemId)
            ->orderBy('stock_history.id','desc')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Old Quantity',
            'New Quantity',
            'Change',
            'Cost Price',
            'Reason',
            'Updated By',
            'Date'
        ];
    }
}
