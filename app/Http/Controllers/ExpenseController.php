<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Store;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Query expenses using the StoreAccess scope trait
        $expenses = Expense::storeAccess()->with(['store', 'creator'])->orderBy('expense_date', 'desc')->get();

        // Calculate totals for widgets
        $totals = [
            'operational' => $expenses->where('category', 'operational')->sum('amount'),
            'store'       => $expenses->where('category', 'store')->sum('amount'),
            'utility'     => $expenses->where('category', 'utility')->sum('amount'),
            'other'       => $expenses->where('category', 'other')->sum('amount'),
            'grand_total' => $expenses->sum('amount')
        ];

        return view('admin.expenses.index', compact('expenses', 'totals'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Fetch stores accessible to the user
        if (auth()->user()->is_superadmin) {
            $stores = Store::all();
        } else {
            $stores = auth()->user()->stores;
        }

        return view('admin.expenses.create', compact('stores'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'expense_date' => 'required|date',
            'store_id'     => 'required|exists:stores,id',
            'category'     => 'required|string|in:operational,store,utility,other',
            'amount'       => 'required|numeric|min:0.01',
            'reference_no' => 'nullable|string|max:255',
            'notes'        => 'nullable|string',
        ]);

        Expense::create([
            'store_id'     => $request->store_id,
            'expense_date' => $request->expense_date,
            'category'     => $request->category,
            'amount'       => $request->amount,
            'reference_no' => $request->reference_no,
            'notes'        => $request->notes,
            'created_by'   => auth()->id(),
        ]);

        return redirect()
            ->route('expenses.index')
            ->with('success', 'New expense of ' . number_format($request->amount, 2) . ' recorded successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Expense $expense)
    {
        // Ensure store access scoping
        if (!auth()->user()->is_superadmin && !auth()->user()->stores->contains('id', $expense->store_id)) {
            abort(403, 'Unauthorized action.');
        }

        if (auth()->user()->is_superadmin) {
            $stores = Store::all();
        } else {
            $stores = auth()->user()->stores;
        }

        return view('admin.expenses.edit', compact('expense', 'stores'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Expense $expense)
    {
        // Ensure store access scoping
        if (!auth()->user()->is_superadmin && !auth()->user()->stores->contains('id', $expense->store_id)) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'expense_date' => 'required|date',
            'store_id'     => 'required|exists:stores,id',
            'category'     => 'required|string|in:operational,store,utility,other',
            'amount'       => 'required|numeric|min:0.01',
            'reference_no' => 'nullable|string|max:255',
            'notes'        => 'nullable|string',
        ]);

        $expense->update([
            'store_id'     => $request->store_id,
            'expense_date' => $request->expense_date,
            'category'     => $request->category,
            'amount'       => $request->amount,
            'reference_no' => $request->reference_no,
            'notes'        => $request->notes,
        ]);

        return redirect()
            ->route('expenses.index')
            ->with('success', 'Expense updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Expense $expense)
    {
        // Ensure store access scoping
        if (!auth()->user()->is_superadmin && !auth()->user()->stores->contains('id', $expense->store_id)) {
            abort(403, 'Unauthorized action.');
        }

        $amount = $expense->amount;
        $expense->delete();

        return redirect()
            ->route('expenses.index')
            ->with('success', 'Expense record of ' . number_format($amount, 2) . ' deleted successfully.');
    }
}
