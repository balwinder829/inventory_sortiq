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
use App\Models\ItemBatch;


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

    // -----------------------------------------------------
    // VALIDATION RULES
    // -----------------------------------------------------
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

    // -----------------------------------------------------
    // MAIN IMPORT LOGIC
    // -----------------------------------------------------
    public function model(array $row)
    {
        try {

            // -----------------------------------------------------
            // 1️⃣ Clean & validate SKU
            // -----------------------------------------------------
            $sku = trim($row['sku'] ?? '');

            if ($sku === '') {
                $this->skipped++;
                $this->errors[] = "Skipped row: Missing SKU.";
                return null;
            }

            // -----------------------------------------------------
            // 2️⃣ Find item (including soft-deleted)
            // -----------------------------------------------------
            $item = Item::withTrashed()->where('sku', $sku)->first();

            if ($item && $item->trashed()) {
                $item->restore();
            }

            $importQty  = (int) ($row['quantity'] ?? 0);
            $importCost = isset($row['cost_price']) && $row['cost_price'] !== ''
                            ? (float) $row['cost_price']
                            : null;

            // -----------------------------------------------------
            // 3️⃣ CREATE NEW ITEM
            // -----------------------------------------------------
            if (!$item) {

                if ($importQty > 0 && $importCost === null) {
                    throw new \Exception("Cost price required for new item SKU: {$sku}");
                }

                $item = Item::create([
                    'name'        => $row['name'] ?? null,
                    'store_id'    => $row['store_id'] ?? null,
                    'sku'         => $sku,
                    'price'       => $row['price'] ?? 0,
                    'cost_price'  => $importCost,
                    'quantity'    => $importQty,
                    'category'    => $row['category'] ?? null,
                    'description' => $row['description'] ?? null,
                ]);

                // FIFO batch (initial purchase)
                if ($importQty > 0) {
                    ItemBatch::create([
                        'item_id'            => $item->id,
                        'quantity'           => $importQty,
                        'remaining_quantity' => $importQty,
                        'cost_price'         => $importCost,
                        'created_at'         => now(),
                    ]);
                }

                StockHistory::create([
                    'item_id'       => $item->id,
                    'type'          => 'in',
                    'old_quantity'  => 0,
                    'new_quantity'  => $importQty,
                    'change_amount' => $importQty,
                    'cost_price'    => $importCost,
                    'changed_by'    => Auth::id(),
                    'reason'        => 'CSV Import - New Item (Purchase)',
                ]);

                $this->imported++;
                return $item;
            }

            // -----------------------------------------------------
            // 4️⃣ UPDATE EXISTING ITEM (MODEL B)
            //     Quantity increase = PURCHASE
            // -----------------------------------------------------
            $oldQty = $item->quantity;
            $addQty = $importQty;

            if ($addQty <= 0) {
                $this->skipped++;
                $this->errors[] = "Skipped SKU {$sku}: Quantity must be > 0 for import.";
                return null;
            }

            // Decide purchase cost
            if ($importCost !== null) {
                $purchaseCost = $importCost;
                $item->cost_price = $purchaseCost; // update last cost
            } else {
                $purchaseCost = $item->cost_price;
            }

            if ($purchaseCost === null) {
                throw new \Exception("Cost price not available for SKU {$sku}");
            }

            // Update item quantity
            $item->quantity = $oldQty + $addQty;
            $item->price    = $row['price'] ?? $item->price;

            if (!empty($row['category'])) {
                $item->category = $row['category'];
            }
            if (!empty($row['description'])) {
                $item->description = $row['description'];
            }

            $item->save();

            // -----------------------------------------------------
            // 5️⃣ CREATE FIFO BATCH (ALWAYS)
            // -----------------------------------------------------
            ItemBatch::create([
                'item_id'            => $item->id,
                'quantity'           => $addQty,
                'remaining_quantity' => $addQty,
                'cost_price'         => $purchaseCost,
                'created_at'         => now(),
            ]);

            // -----------------------------------------------------
            // 6️⃣ STOCK HISTORY
            // -----------------------------------------------------
            StockHistory::create([
                'item_id'       => $item->id,
                'type'          => 'in',
                'old_quantity'  => $oldQty,
                'new_quantity'  => $item->quantity,
                'change_amount' => $addQty,
                'cost_price'    => $purchaseCost,
                'changed_by'    => Auth::id(),
                'reason'        => $importCost !== null
                                    ? 'CSV Import - Purchase (New Cost)'
                                    : 'CSV Import - Purchase (Same Cost)',
            ]);

            $this->imported++;
            return $item;

        } catch (\Throwable $e) {

            $this->skipped++;
            $this->errors[] = "Error on SKU {$sku}: " . $e->getMessage();
            return null;
        }
    }
    public function dec10model(array $row)
    {
        try {

            // Clean SKU
            $sku = trim($row['sku'] ?? '');

            if ($sku === '') {
                $this->skipped++;
                $this->errors[] = "Skipped row: Missing SKU.";
                return null;
            }

            // Find item including soft-deleted ones
            $item = Item::withTrashed()->where('sku', $sku)->first();

            // -----------------------------------------------------
            // CASE 1: ITEM EXISTS BUT IS SOFT-DELETED → RESTORE IT
            // -----------------------------------------------------
            if ($item && $item->trashed()) {
                $item->restore();
            }

            // -----------------------------------------------------
            // CASE 2: CREATE NEW ITEM
            // -----------------------------------------------------
            if (!$item) {

                $newItem = new Item([
                    'name'        => $row['name'] ?? null,
                    'store_id'    => $row['store_id'] ?? null,
                    'sku'         => $sku,
                    'price'       => $row['price'] ?? 0,
                    'cost_price'  => $row['cost_price'] ?? 0,
                    'avg_cost'    => $row['cost_price'] ?? 0, // Initial avg cost
                    'quantity'    => $row['quantity'] ?? 0,
                    'category'    => $row['category'] ?? null,
                    'description' => $row['description'] ?? null,
                ]);

                $newItem->save();

                if($row['quantity'] > 0){
                    ItemBatch::create([
                        'item_id' => $newItem->id,
                        'quantity' => $row['quantity'],
                        'remaining_quantity' => $row['quantity'],
                        'cost_price' => $row['cost_price'],
                    ]);
                }
                

                // Stock log
                StockHistory::create([
                    'item_id'       => $newItem->id,
                    'type'          => 'in',
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

            // -----------------------------------------------------
            // CASE 3: UPDATE EXISTING ITEM (APPLY AVG COST)
            // -----------------------------------------------------

            $oldQty  = $item->quantity;
            $addQty  = (int) $row['quantity'];
            $newCost = (float) $row['cost_price'];

            // existing avg or fallback
            $oldAvg = $item->avg_cost ?? $item->cost_price ?? 0;

            // Weighted average cost
            if (($oldQty + $addQty) > 0) {
                $newAvgCost = (($oldQty * $oldAvg) + ($addQty * $newCost))
                              / ($oldQty + $addQty);
            } else {
                $newAvgCost = $newCost;
            }

            // Update the product
            $item->name        = $row['name'];
            $item->store_id    = $row['store_id'];
            $item->price       = $row['price'];
            $item->avg_cost    = $newAvgCost;
            $item->quantity    = $oldQty + $addQty;
            $item->cost_price  = $newCost; // last purchase cost for UI

            if (!empty($row['category'])) {
                $item->category = $row['category'];
            }
            if (!empty($row['description'])) {
                $item->description = $row['description'];
            }

            $item->save();

            ItemBatch::create([
                'item_id' => $item->id,
                'quantity' => $addQty,
                'remaining_quantity' => $addQty,
                'cost_price' => $newCost,
            ]);

            // Stock history entry
            StockHistory::create([
                'item_id'       => $item->id,
                'type'          => 'in',
                'old_quantity'  => $oldQty,
                'new_quantity'  => $item->quantity,
                'change_amount' => $addQty,
                'cost_price'    => $newCost,
                'changed_by'    => Auth::id(),
                'reason'        => "CSV Import - Stock Added",
            ]);

            $this->imported++;
            return $item;

        } catch (\Throwable $e) {

            $this->skipped++;
            $this->errors[] = "Error on SKU {$sku}: " . $e->getMessage();
            return null;
        }
    }
}
