<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Models\SupplierPayment;
use Illuminate\Http\Request;

class SupplierPaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Store a newly created payment record.
     */
    public function store(Request $request, Supplier $supplier)
    {
        $request->validate([
            'payment_date' => 'required|date',
            'amount'       => 'required|numeric|min:0.01',
            'payment_mode' => 'required|string|max:255',
            'reference_no' => 'nullable|string|max:255',
            'notes'        => 'nullable|string',
        ]);

        $supplier->payments()->create([
            'payment_date' => $request->payment_date,
            'amount'       => $request->amount,
            'payment_mode' => $request->payment_mode,
            'reference_no' => $request->reference_no,
            'notes'        => $request->notes,
        ]);

        return redirect()
            ->route('suppliers.show', $supplier->id)
            ->with('success', 'Payment of ' . number_format($request->amount, 2) . ' recorded successfully.');
    }

    /**
     * Delete the specified payment record.
     */
    public function destroy(SupplierPayment $payment)
    {
        $supplierId = $payment->supplier_id;
        $amount = $payment->amount;

        $payment->delete();

        return redirect()
            ->route('suppliers.show', $supplierId)
            ->with('success', 'Payment record of ' . number_format($amount, 2) . ' deleted successfully.');
    }
}
