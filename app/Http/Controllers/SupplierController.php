<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;


class SupplierController extends Controller
{
    public function __construct()
    {
       $this->middleware('auth');
       $this->middleware('permission:suppliers.view|store_management.view')->only(['index','show']);
       $this->middleware('permission:suppliers.create|store_management.view')->only(['create','store']);
       $this->middleware('permission:suppliers.edit|store_management.view')->only(['edit','update']);
       $this->middleware('permission:suppliers.delete|store_management.view')->only(['destroy']);
       
    }
    
    public function index()
    {
        $suppliers = Supplier::all();

        return view('admin.suppliers.index', compact('suppliers'));
    }

    public function create()
    {
        return view('admin.suppliers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'shop_name' => 'nullable|max:255',
            'supplier_type' => 'nullable|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|max:255',
            'alternate_phone' => 'nullable|max:255',
            'address' => 'nullable|max:255',
            'gst_number' => 'nullable|max:255',
            'notes' => 'nullable',
        ]);

        Supplier::create([
            'name' => $request->name,
            'shop_name' => $request->shop_name,
            'supplier_type' => $request->supplier_type,
            'email' => $request->email,
            'phone' => $request->phone,
            'alternate_phone' => $request->alternate_phone,
            'address' => $request->address,
            'gst_number' => $request->gst_number,
            'notes' => $request->notes,
            'is_active' => $request->is_active ?? 1,
        ]);

        return redirect()
            ->route('suppliers.index')
            ->with('success', 'Supplier Created Successfully');
    }

    public function show(Request $request, Supplier $supplier)
    {
        $startDateInput = $request->input('start_date');
        $endDateInput = $request->input('end_date');

        $query = \App\Models\PurchaseDetails::whereHas('purchase', function ($q) use ($supplier, $startDateInput, $endDateInput) {
            $q->where('supplier_id', $supplier->id);
            if ($startDateInput && $endDateInput) {
                $q->whereBetween('purchase_date', [$startDateInput, $endDateInput]);
            }
        })->with(['purchase', 'item']);

        $suppliedStocks = $query->orderBy('id', 'DESC')->get();

        return view('admin.suppliers.show', compact('supplier', 'suppliedStocks', 'startDateInput', 'endDateInput'));
    }

    public function edit(Supplier $supplier)
    {
        return view('admin.suppliers.edit', [
            'supplier' => $supplier
        ]);
    }

      /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Supplier $supplier)
    {
        $request->validate([
            'name' => 'required|max:255',
            'shop_name' => 'nullable|max:255',
            'supplier_type' => 'nullable|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|max:255',
            'alternate_phone' => 'nullable|max:255',
            'address' => 'nullable|max:255',
            'gst_number' => 'nullable|max:255',
            'notes' => 'nullable',
        ]);

        $supplier->update([
            'name' => $request->name,
            'shop_name' => $request->shop_name,
            'supplier_type' => $request->supplier_type,
            'email' => $request->email,
            'phone' => $request->phone,
            'alternate_phone' => $request->alternate_phone,
            'address' => $request->address,
            'gst_number' => $request->gst_number,
            'notes' => $request->notes,
            'is_active' => $request->is_active ?? 1,
        ]);

        return redirect()
            ->route('suppliers.index')
            ->with('success', 'Supplier Updated Successfully');
    } 

    public function destroy(Supplier $supplier)
    {
        $supplier->delete();

        return redirect()
            ->route('suppliers.index')
            ->with('success', 'Supplier Deleted Successfully');
    }
}
