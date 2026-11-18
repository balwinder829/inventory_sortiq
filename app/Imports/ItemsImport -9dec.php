<?php

namespace App\Imports;

use App\Models\Item;
use App\Models\StockHistory;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\SkipsFailures;

class ItemsImport implements 
    ToModel, 
    WithHeadingRow, 
    WithValidation, 
    SkipsOnFailure
{
    use SkipsFailures;

    public $imported = 0;
    public $skipped = 0;
    public $errors = [];

    public function rules(): array
    {
        return [
            'name'        => 'required',
            'store_id'    => 'required|numeric',
            'sku'         => 'required',
            'price'       => 'required|numeric',
            'cost_price'  => 'required|numeric',
            'quantity'    => 'required|numeric',
        ];
    }

    public function model(array $row)
    {
        try {
            if (empty($row['sku'])) {
                $this->skipped++;
                $this->errors[] = "Skipped row: Missing SKU.";
                return null;
            }

            // CHECK IF ITEM EXIST
            $item = Item::where('sku', $row['sku'])->first();

            // ======================
            // CREATE NEW PRODUCT
            // ======================
            if (!$item) {

                $newItem = new Item([
                    'name'        => $row['name'] ?? null,
                    'store_id'    => $row['store_id'] ?? null,
                    'sku'         => $row['sku'],
                    'price'       => $row['price'] ?? null,
                    'cost_price'  => $row['cost_price'] ?? null,
                    'quantity'    => $row['quantity'] ?? null,
                    'category'    => $row['category'] ?? null,
                    'description' => $row['description'] ?? null,
                ]);
                $newItem->save();

                // STOCK HISTORY - initial IN movement
                StockHistory::create([
                    'item_id'       => $newItem->id,
                    'type'          => 'in', // new stock
                    'old_quantity'  => 0,
                    'new_quantity'  => $row['quantity'],
                    'change_amount' => $row['quantity'],
                    'cost_price'    => $row['cost_price'],
                    'changed_by'    => Auth::id(),
                    'reason'        => "CSV Import - New Item",
                ]);

                $this->imported++;
                return $newItem;
            }

            // ======================
            // UPDATE EXISTING PRODUCT
            // (Use ADD QTY mode)
            // ======================

            $oldQty = $item->quantity;
            $addQty = $row['quantity'];    
            $newQty = $oldQty + $addQty;   // ADD MODE

            // Update fields
            $item->name        = $row['name'];
            $item->store_id    = $row['store_id'];
            $item->price       = $row['price'];
            $item->cost_price  = $row['cost_price'];
            $item->quantity    = $newQty;

            // Optional fields
            if (!empty($row['category'])) {
                $item->category = $row['category'];
            }
            if (!empty($row['description'])) {
                $item->description = $row['description'];
            }

            $item->save();

            // STOCK HISTORY - IN movement
            StockHistory::create([
                'item_id'       => $item->id,
                'type'          => 'in',
                'old_quantity'  => $oldQty,
                'new_quantity'  => $newQty,
                'change_amount' => $addQty,
                'cost_price'    => $row['cost_price'],
                'changed_by'    => Auth::id(),
                'reason'        => "CSV Import - Stock Added",
            ]);

            $this->imported++;
            return $item;

        } catch (\Throwable $e) {
            $this->skipped++;
            $this->errors[] = "Error on SKU {$row['sku']}: " . $e->getMessage();
            return null;
        }
    }
}
