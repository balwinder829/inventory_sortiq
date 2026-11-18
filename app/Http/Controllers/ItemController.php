<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Store;
use App\Models\Item;
use App\Imports\ItemsImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\ItemBatch;
use App\Models\StockHistory;

// use Maatwebsite\Excel\Excel;


class ItemController extends Controller
{
    public function __construct()
    {
       $this->middleware('auth');
       $this->middleware('permission:products.view|store_management.view')->only(['index','show']);
       $this->middleware('permission:products.create|store_management.view')->only(['create','store']);
       $this->middleware('permission:products.edit|store_management.view')->only(['edit','update']);
       $this->middleware('permission:products.delete|store_management.view')->only(['destroy']);
       // $this->middleware('permission:products.import')->only(['import_page','import']);
       $this->middleware('permission:products.import|stock_management.import|store_management.view')->only(['import_page','import']);
       
    }
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
            'file' => 'required|file|mimes:xlsx,csv',
        ]);

        $file = $request->file('file');

        /** STEP 1: READ HEADER ROW BEFORE IMPORT STARTS */
        $data = \Maatwebsite\Excel\Facades\Excel::toArray([], $file);

        if (empty($data) || empty($data[0]) || empty($data[0][0])) {
           
            return redirect()->back()->with('error', 'Uploaded file is empty or unreadable.');
        }

        // Convert header names to lowercase
        $headers = array_map('strtolower', $data[0][0]);

        $requiredHeaders = [
            'name',
            'store_id',
            'sku',
            'price',
            'cost_price',
            'quantity',
        ];

        // 1️⃣ Missing headers?
        $missing = array_diff($requiredHeaders, $headers);
        if (!empty($missing)) {
            \Log::info('Missing columns', $missing);
            return redirect()->back()->with('error', 'Missing required column(s): ' . implode(', ', $missing));
        }

        
        $uploadedRequiredHeaders = array_values(array_intersect($headers, $requiredHeaders));

        if ($uploadedRequiredHeaders !== $requiredHeaders) {
            return redirect()->back()->with('error',
                "Invalid column order! Required order: " . implode(' → ', $requiredHeaders)
            );
        }


        /** STEP 2: Import file (safe now) */
        try {
            $importer = new \App\Imports\ItemsImport();
            \Maatwebsite\Excel\Facades\Excel::import($importer, $file);

            $logFile = null;
            if (!empty($importer->errors)) {
                $logFile = 'items-import-log-' . time() . '.txt';
                \Storage::put($logFile, implode("\n", $importer->errors));
            }

            \Log::info('Flash set', [
                'success' => "Imported: {$importer->imported}, Skipped: {$importer->skipped}"
            ]);
            return redirect()->back()
                ->with('success', "Imported: {$importer->imported}, Skipped: {$importer->skipped}")
                ->with('logFile', $logFile);

        } catch (\Throwable $e) {
            \Log::error('Import failed', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Import failed: ' . $e->getMessage());
        }
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

    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'store_id'   => 'required|exists:stores,id',
            'name'       => 'required|string|max:255',
            'description'=> 'nullable|string',
            'category'   => 'nullable|string|max:100',

            // MODEL B: cost is REQUIRED for new item
            'cost_price' => 'required|numeric|min:0',

            'price'      => 'required|numeric|min:0',
            'quantity'   => 'required|integer|min:0',

            'sku'        => 'required|string|max:100|unique:items,sku',
            'brand'      => 'nullable|string|max:100',
            'weight'     => 'nullable|string|max:50',
            'dimensions' => 'nullable|string|max:100',
            'status'     => 'required|in:publish,draft',
            'tags'       => 'nullable|string',
            'sale_price' => 'nullable|numeric|min:0|lt:price',
        ]);

        // Convert tags string → array
        $validated['tags'] = !empty($validated['tags'])
            ? array_map('trim', explode(',', $validated['tags']))
            : [];

        /*
        |--------------------------------------------------------------------------
        | 1️⃣ CREATE ITEM (NO avg_cost ANYMORE)
        |--------------------------------------------------------------------------
        */
        $item = Item::create([
            'store_id'    => $validated['store_id'],
            'name'        => $validated['name'],
            'description' => $validated['description'] ?? null,
            'category'    => $validated['category'] ?? null,
            'price'       => $validated['price'],
            'sale_price'  => $validated['sale_price'] ?? null,

            // last known purchase cost (used for future purchases if cost not given)
            'cost_price'  => $validated['cost_price'],

            'quantity'    => $validated['quantity'],
            'sku'         => $validated['sku'],
            'brand'       => $validated['brand'] ?? null,
            'weight'      => $validated['weight'] ?? null,
            'dimensions'  => $validated['dimensions'] ?? null,
            'status'      => $validated['status'],
            'tags'        => $validated['tags'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 2️⃣ INITIAL STOCK = PURCHASE (MODEL B)
        |--------------------------------------------------------------------------
        */
        if ($validated['quantity'] > 0) {

            // ✅ CREATE FIFO BATCH
            ItemBatch::create([
                'item_id'            => $item->id,
                'quantity'           => $validated['quantity'],
                'remaining_quantity' => $validated['quantity'],
                'cost_price'         => $validated['cost_price'],
                'created_at'         => now(),
            ]);

            // ✅ STOCK HISTORY
            StockHistory::create([
                'item_id'       => $item->id,
                'type'          => 'in',
                'old_quantity'  => 0,
                'new_quantity'  => $validated['quantity'],
                'change_amount' => $validated['quantity'],
                'cost_price'    => $validated['cost_price'],
                'changed_by'    => auth()->id(),
                'reason'        => 'Initial Stock (Purchase on Item Creation)',
            ]);
        }

        return redirect()
            ->route('stores.show', $validated['store_id'])
            ->with('success', 'Item created successfully with FIFO stock.');
    }
    
    public function store10dec(Request $request)
    {
        $validated = $request->validate([
            'store_id' => 'required|exists:stores,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:100',
            'cost_price' => 'required|numeric|min:0',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'sku' => 'required|string|max:100|unique:items,sku',
            'brand' => 'nullable|string|max:100',
            'weight' => 'nullable|string|max:50',
            'dimensions' => 'nullable|string|max:100',
            'status' => 'required|in:publish,draft',
            'tags' => 'nullable|string',
            'sale_price' => 'nullable|numeric|min:0|lt:price',
        ]);

        // Convert tags into array
        $validated['tags'] = !empty($validated['tags'])
            ? array_map('trim', explode(',', $validated['tags']))
            : [];

        // -------------------------------------
        // IMPORTANT: Set initial avg_cost
        // -------------------------------------
        $validated['avg_cost'] = $validated['cost_price']; // Initial inventory cost

        // Create Item
        $item = Item::create($validated);

        // -------------------------------------
        // OPTIONAL BUT HIGHLY RECOMMENDED:
        // Create initial StockHistory entry
        // -------------------------------------
        if ($validated['quantity'] > 0) {
            \App\Models\StockHistory::create([
                'item_id'       => $item->id,
                'type'          => 'in',
                'old_quantity'  => 0,
                'new_quantity'  => $validated['quantity'],
                'change_amount' => $validated['quantity'],
                'cost_price'    => $validated['cost_price'], // purchase cost
                'changed_by'    => auth()->id(),
                'reason'        => "Initial stock on item creation",
            ]);

            // Add items to batch history 

            ItemBatch::create([
                'item_id' => $item->id,
                'quantity' => $validated['quantity'],
                'remaining_quantity' => $validated['quantity'],
                'cost_price' => $validated['cost_price'],
            ]);

        }

        return redirect()
            ->route('stores.show', $validated['store_id'])
            ->with('success', 'Item created successfully.');
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

   
    public function update(Request $request, Item $item)
    {
        $validated = $request->validate([
            'store_id'    => 'required|exists:stores,id',
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'category'    => 'nullable|string|max:100',
            'price'       => 'required|numeric|min:0',   // selling price only
            'sale_price'  => 'nullable|numeric|min:0|lt:price',
            'brand'       => 'nullable|string|max:100',
            'weight'      => 'nullable|string|max:50',
            'dimensions'  => 'nullable|string|max:100',
            'status'      => 'required|in:publish,draft',
            'tags'        => 'nullable|string',
            'image_path'  => 'nullable|image|max:2048',
        ]);

        // Convert CSV-like tags to array
        $validated['tags'] = !empty($validated['tags'])
            ? array_map('trim', explode(',', $validated['tags']))
            : [];

        // ---------------------------------------------------------
        // 🔒 PROTECT INVENTORY FIELDS (should never be editable)
        // ---------------------------------------------------------
        unset($validated['quantity']);    // Stock quantity must be changed via Stock Adjustment or Import
        unset($validated['cost_price']);  // Cost changes only through Purchases/Imports
        unset($validated['avg_cost']);    // Always auto-calculated
        unset($validated['sku']);         // SKU is identity key — do not allow change here

        // ---------------------------------------------------------
        // 📸 Handle image (optional)
        // ---------------------------------------------------------
        if ($request->hasFile('image_path')) {
            $validated['image_path'] = $request->file('image_path')->store('items', 'public');
        }

        // ---------------------------------------------------------
        // 📝 Update allowed fields only
        // ---------------------------------------------------------
        $item->update($validated);

        return redirect()
            ->route('stores.show', $item->store_id)
            ->with('success', 'Item updated successfully.');
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
