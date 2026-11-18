<?php

namespace App\Http\Controllers;

use App\Enums\PurchaseStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Purchase\StorePurchaseRequest;
use App\Models\Category;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseDetails;
use App\Models\Supplier;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Item;
use App\Models\ItemBatch;
use App\Models\StockHistory;

use App\Models\Store;


// use PhpOffice\PhpSpreadsheet\Spreadsheet;
// use PhpOffice\PhpSpreadsheet\Writer\Xls;

class PurchaseController extends Controller
{

    public function __construct()
    {
       $this->middleware('auth');
       $this->middleware('permission:purchases.view|store_management.view')->only(['index','show']);
       $this->middleware('permission:purchases.create|store_management.view')->only(['create','store']);
       $this->middleware('permission:purchases.edit|store_management.view')->only(['edit','update']);
       $this->middleware('permission:purchases.delete|store_management.view')->only(['destroy']);
       
    }

    public function index()
    {
        // return view('admin.purchases.index', [
        //     'purchases' => Purchase::latest()->get(),
        // ]);

        $purchases = Purchase::latest()->get();

        return view('admin.purchases.index', compact('purchases'));
    }

    public function approvedPurchases()
    {
        $purchases = Purchase::with(['supplier'])
            ->where('status', PurchaseStatus::APPROVED)->get(); // 1 = approved

        // return view('purchases.approved-purchases', [
        //     'purchases' => $purchases,
        // ]);

         return view('admin.purchases.approved-purchases', compact(
            'purchases'
        ));
    }

    public function show(Purchase $purchase)
    {
        $purchase->load([
            'supplier',
            'details.item',
            'details.store',
        ]);

        return view(
            'admin.purchases.show',
            compact('purchase')
        );
    }
    public function show12(Purchase $purchase)
    {
        $purchase->loadMissing(['supplier', 'details'])->get();

        $products = PurchaseDetails::where('purchase_id', $purchase->id)->get();

        return view('admin.purchases.details-purchase', compact(
            'purchase',
            'products'
        ));
    }

    public function edit(Purchase $purchase)
    {
        // N+1 Problem if load 'createdBy', 'updatedBy',
        $purchase->with(['supplier', 'details'])->get();

        return view('admin.purchases.edit', [
            'purchase' => $purchase,
        ]);
    }

    public function create()
    {
        $suppliers = Supplier::where('is_active', 1)->get();

        // $stores = Store::storeAccess()->get();
        $stores = auth()->user()->is_superadmin
        ? Store::all()
        : auth()->user()->stores;

        $items = Item::storeAccess()->get();

        return view(
            'admin.purchases.create',
            compact(
                'suppliers',
                'stores',
                'items'
            )
        );
    }
    public function create11may()
    {
        $suppliers = Supplier::where('is_active', 1)->get();

        $stores = Store::all();

        $items = Item::all();

        return view(
            'admin.purchases.create',
            compact(
                'suppliers',
                'stores',
                'items'
            )
        );
    }

    public function store(Request $request)
{
    $request->validate([

        'supplier_id' => 'required|exists:suppliers,id',

        'purchase_date' => 'required|date',

        'bill_no' => 'required|string|max:255',

        'items' => 'required|array|min:1',

        'items.*.store_id' => 'required|exists:stores,id',

        'items.*.item_id' => 'required|exists:items,id',

        'items.*.quantity' => 'required|integer|min:1',

        'items.*.unitcost' => 'required|numeric|min:0',

        'items.*.selling_price' => 'required|numeric|min:0',

    ]);

    DB::transaction(function () use ($request) {

        // Create Purchase
        $purchase = Purchase::create([

            'supplier_id' => $request->supplier_id,

            'purchase_date' => $request->purchase_date,

            'bill_no' => $request->bill_no,

            'status' => 1,

            'total_amount' => 0,

            'created_by' => auth()->id(),

        ]);

        $grandTotal = 0;

        foreach ($request->items as $row) {

            $item = Item::lockForUpdate()->findOrFail($row['item_id']);
            if ($item->store_id != $row['store_id']) {

                $storeName = Store::find($row['store_id'])?->name ?? 'Selected Store';

                return redirect()
                    ->back()
                    ->withInput()
                    ->withErrors([

                        'items.' . $loopIndex . '.item_id' =>

                            $item->name .
                            ' does not belong to store: ' .
                            $storeName

                    ]);
            }

            $oldQty = $item->quantity;

            $newQty = $oldQty + $row['quantity'];

            $lineTotal = $row['quantity'] * $row['unitcost'];

            // Purchase Detail
            PurchaseDetails::create([

                'purchase_id' => $purchase->id,

                'store_id' => $row['store_id'],

                'item_id' => $item->id,

                'quantity' => $row['quantity'],

                'unitcost' => $row['unitcost'],

                'total' => $lineTotal,

            ]);

            // Update Item
            $item->quantity = $newQty;

            $item->cost_price = $row['unitcost'];

            $item->price = $row['selling_price'];

            $item->save();

            // FIFO Batch
            ItemBatch::create([

                'item_id' => $item->id,

                'quantity' => $row['quantity'],

                'remaining_quantity' => $row['quantity'],

                'cost_price' => $row['unitcost'],

                'created_at' => now(),

            ]);

            // Stock History
            StockHistory::create([

                'item_id' => $item->id,

                'type' => 'in',

                'old_quantity' => $oldQty,

                'new_quantity' => $newQty,

                'change_amount' => $row['quantity'],

                'cost_price' => $row['unitcost'],

                'changed_by' => auth()->id(),

                'reason' => 'Purchase Bill: ' . $request->bill_no,

            ]);

            $grandTotal += $lineTotal;
        }

        // Update Total
        $purchase->update([
            'total_amount' => $grandTotal
        ]);
    });

    return redirect()
        ->route('purchases.index')
        ->with('success', 'Purchase Created Successfully');
}
    public function storeold(StorePurchaseRequest $request)
    {
        $purchase = Purchase::create($request->all());

        /*
         * TODO: Must validate that
         */
        if (! $request->invoiceProducts == null) {
            $pDetails = [];

            foreach ($request->invoiceProducts as $product) {
                $pDetails['purchase_id'] = $purchase['id'];
                $pDetails['product_id'] = $product['product_id'];
                $pDetails['quantity'] = $product['quantity'];
                $pDetails['unitcost'] = $product['unitcost'];
                $pDetails['total'] = $product['total'];
                $pDetails['created_at'] = Carbon::now();

                //PurchaseDetails::insert($pDetails);
                $purchase->details()->insert($pDetails);
            }
        }

        return redirect()
            ->route('purchases.index')
            ->with('success', 'Purchase has been created!');
    }

    public function update(Purchase $purchase, Request $request)
    {
        $products = PurchaseDetails::where('purchase_id', $purchase->id)->get();

        foreach ($products as $product) {
            Product::where('id', $product->product_id)
                ->update(['quantity' => DB::raw('quantity+'.$product->quantity)]);
        }

        Purchase::findOrFail($purchase->id)
            ->update([
                //'purchase_status' => 1, // 1 = approved, 0 = pending
                'status' => PurchaseStatus::APPROVED,
                'updated_by' => auth()->user()->id,
            ]);

        return redirect()
            ->route('purchases.index')
            ->with('success', 'Purchase has been approved!');
    }

    public function destroy(Purchase $purchase)
    {
        $purchase->delete();

        return redirect()
            ->route('purchases.index')
            ->with('success', 'Purchase has been deleted!');
    }

    public function dailyPurchaseReport()
    {
        $purchases = Purchase::with(['supplier'])
            //->where('purchase_status', 1)
            ->where('date', today()->format('Y-m-d'))->get();

        return view('purchases.daily-report', [
            'purchases' => $purchases,
        ]);
    }

    public function getPurchaseReport()
    {
        return view('purchases.report-purchase');
    }

    public function exportPurchaseReport(Request $request)
    {
        $rules = [
            'start_date' => 'required|string|date_format:Y-m-d',
            'end_date' => 'required|string|date_format:Y-m-d',
        ];

        $validatedData = $request->validate($rules);

        $sDate = $validatedData['start_date'];
        $eDate = $validatedData['end_date'];

        $purchases = DB::table('purchase_details')
            ->join('products', 'purchase_details.product_id', '=', 'products.id')
            ->join('purchases', 'purchase_details.purchase_id', '=', 'purchases.id')
            ->join('users', 'users.id', '=', 'purchases.created_by')
            ->whereBetween('purchases.purchase_date', [$sDate, $eDate])
            ->where('purchases.purchase_status', '1')
            ->select('purchases.purchase_no', 'purchases.purchase_date', 'purchases.supplier_id', 'products.code', 'products.name', 'purchase_details.quantity', 'purchase_details.unitcost', 'purchase_details.total', 'users.name as created_by')
            ->get();

        dd($purchases);

        $purchase_array[] = [
            'Date',
            'No Purchase',
            'Supplier',
            'Product Code',
            'Product',
            'Quantity',
            'Unitcost',
            'Total',
            'Created By'
        ];

        foreach ($purchases as $purchase) {
            $purchase_array[] = [
                'Date' => $purchase->purchase_date,
                'No Purchase' => $purchase->purchase_no,
                'Supplier' => $purchase->supplier_id,
                'Product Code' => $purchase->product_code,
                'Product' => $purchase->product_name,
                'Quantity' => $purchase->quantity,
                'Unitcost' => $purchase->unitcost,
                'Total' => $purchase->total,
            ];
        }

        $this->exportExcel($purchase_array);
    }

    public function exportExcel($products)
    {
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '4000M');

        try {
            $spreadSheet = new Spreadsheet();
            $spreadSheet->getActiveSheet()->getDefaultColumnDimension()->setWidth(20);
            $spreadSheet->getActiveSheet()->fromArray($products);
            $Excel_writer = new Xls($spreadSheet);
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="purchase-report.xls"');
            header('Cache-Control: max-age=0');
            ob_end_clean();
            $Excel_writer->save('php://output');
            exit();
        } catch (Exception $e) {
            return $e;
        }
    }
}
