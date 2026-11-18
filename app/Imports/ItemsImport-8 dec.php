<?php

namespace App\Imports;

use App\Models\Item;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
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

            if (Item::where('sku', $row['sku'])->exists()) {
                $this->skipped++;
                $this->errors[] = "Duplicate SKU skipped: {$row['sku']}";
                return null;
            }

            $this->imported++;

            return new Item([
                'name'        => $row['name'] ?? null,
                'store_id'    => $row['store_id'] ?? null,
                'sku'         => $row['sku'],
                'price'       => $row['price'] ?? null,
                'cost_price'  => $row['cost_price'] ?? null,
                'quantity'    => $row['quantity'] ?? null,
                'category' => $row['category'] ?? null,
                'description' => $row['description'] ?? null,
            ]);

        } catch (\Throwable $e) {
            $this->skipped++;
            $this->errors[] = "Error on SKU {$row['sku']}: " . $e->getMessage();
            return null;
        }
    }
}
