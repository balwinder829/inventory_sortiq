<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Store;
use App\Models\Item;
use App\Imports\ItemsImport;
use Maatwebsite\Excel\Facades\Excel;
// use Maatwebsite\Excel\Excel;


class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $items = Item::paginate(10); // paginate for example
        return view('items.index', compact('items'));
    }

    public function import_page()
    {
        return view('admin.new_invoices.import_page');
    }

    

public function import(Request $request)
{
    $request->validate([
        'file' => 'required|file|mimes:xlsx,csv,txt',
    ]);

    Excel::import(new ItemsImport, $request->file('file'));

    return redirect()
        ->route('products.index')
        ->with('success', 'Products imported successfully!');
    // return redirect()->route('items.index')->with('success', 'Item Imported successfully.');
    // return back()->with('success', 'Products imported successfully!');
}


    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {   
        $user = auth()->user();
        $storeId = $request->query('store_id');
        $store = null;
        if ($storeId) {
            $store = Store::find($storeId);
        }
        if ($user->is_superadmin) {
            return view('admin.item_create', compact('store'));
        }

        if ($user->id === $store->user_id) {
            return view('admin.item_create', compact('store'));   
        }
        abort(403, 'Unauthorized access.');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'store_id' => 'required|exists:stores,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:100',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'sku' => 'required|string|max:100|unique:items,sku',
            // 'image_path' => 'nullable|image|max:2048', // max 2MB
            'brand' => 'nullable|string|max:100',
            'weight' => 'nullable|string|max:50',
            'dimensions' => 'nullable|string|max:100',
            'status' => 'required|in:publish,draft',
            'tags' => 'nullable|string', // comma-separated in form
            'sale_price' => 'nullable|numeric|min:0|lt:price',
        ]);

        // Convert tags from comma-separated string to array
        if (!empty($validated['tags'])) {
            $validated['tags'] = array_map('trim', explode(',', $validated['tags']));
        } else {
            $validated['tags'] = [];
        }

        // Handle image upload
        // if ($request->hasFile('image_path')) {
        //     $validated['image_path'] = $request->file('image_path')->store('items', 'public');
        // }

        $item = Item::create($validated);

        return redirect()->route('stores.show', $validated['store_id'])
                 ->with('success', 'Item created successfully.');
        // return redirect()->route('items.show', $item)->with('success', 'Item created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Item $item)
    {
        return view('items.show', compact('item'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Item $item)
    {
        // dd($item);
        return view('admin.item_update', compact('item'));
    }

    /**
     * Update the specified resource in storage.
     */
        public function update(Request $request, Item $item)
    {
        $validated = $request->validate([
            'store_id' => 'required|exists:stores,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:100',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            // Ignore current item's SKU in unique check
            // 'sku' => 'required|string|max:100|unique:items,sku,' . $item->id,
            'image_path' => 'nullable|image|max:2048',
            'brand' => 'nullable|string|max:100',
            'weight' => 'nullable|string|max:50',
            'dimensions' => 'nullable|string|max:100',
            // 'status' => 'required|in:available,out_of_stock,discontinued',
            'status' => 'required|in:publish,draft',
            // 'tags' => 'nullable|string',
            'sale_price' => 'nullable|numeric|min:0|lt:price',
        ]);

        if (!empty($validated['tags'])) {
            $validated['tags'] = array_map('trim', explode(',', $validated['tags']));
        } else {
            $validated['tags'] = [];
        }

        // if ($request->hasFile('image_path')) {
        //     $validated['image_path'] = $request->file('image_path')->store('items', 'public');
        // }

        $item->update($validated);

        return redirect()->route('stores.show', $validated['store_id'])
                 ->with('success', 'Item created successfully.');
        // return redirect()->route('stores.show', $validated['store_id'])
                 // ->with('success', 'Item created successfully.');
        // return redirect()->route('items.show', $item)->with('success', 'Item updated successfully.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Item $item)
    {
        $store = $item->store; 
       // echo $store->id;
       //  dd($store);
        $item->delete();
        return response()->json([
            'message' => 'Item deleted successfully.',
            'redirect' => route('stores.show', $store->id)
        ]);
        // return redirect()->route('admin.items.index')->with('success', 'Item deleted successfully.');
    }
}
