<?php

namespace App\Exports;

use App\Models\Invoice;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class InvoicesExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    public function collection()
    {
        // return Invoice::with(['items.product'])
        //     ->orderBy('invoice_date', 'desc')   // ✅ Latest invoices first
        //     ->orderBy('id', 'desc')             // Secondary order for accuracy
        //     ->get();
        $query = Invoice::with(['items.product'])
            ->orderBy('invoice_date', 'desc')   // ✅ Latest invoices first
            ->orderBy('id', 'desc');            // Secondary order for accuracy

        if (auth()->check() && !auth()->user()->is_superadmin) {
            $myStoreIds = auth()->user()->stores->pluck('id')->toArray();
            $invoiceIds = \DB::table('invoice_items')
                ->join('items', 'invoice_items.product_id', '=', 'items.id')
                ->whereIn('items.store_id', $myStoreIds)
                ->pluck('invoice_items.invoice_id')
                ->unique();

            $query->whereIn('id', $invoiceIds);
        }

        return $query->get();
    }

    public function map($invoice): array
    {
        // Products: Name (xQty)
        $products = $invoice->items->map(function ($item) {
            $name = $item->product->name ?? 'Unknown';
            return $name . ' (x' . $item->quantity . ')';
        })->implode(', ');

        return [
            $invoice->invoice_no,
            $products,
            number_format($invoice->total_amount, 2),
            number_format($invoice->total_profit, 2),
            ucfirst(str_replace('_', ' ', $invoice->payment_mode)),
            $invoice->customer_name ?? 'N/A',
            $invoice->customer_mobile ?? 'N/A',
            $invoice->customer_email ?? 'N/A',
            date('d-m-Y', strtotime($invoice->invoice_date)),  // ✅ Proper formatting
        ];
    }

    public function headings(): array
    {
        return [
            'Invoice Number',
            'Products',
            'Total Amount',
            'Total Profit',
            'Payment Mode',
            'Customer Name',
            'Customer Mobile',
            'Customer Email',
            'Invoice Date',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]], // Header row bold
        ];
    }
}
