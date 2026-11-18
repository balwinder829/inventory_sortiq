@include('admin.layouts.header')

<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="page-body">

            {{-- Success --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($orders->isEmpty())
                <div class="empty-state text-center py-5">
                    <h2>No invoice found</h2>
                    <a href="{{ route('invoices.create') }}" class="btn btn-primary mt-3">Add your first Invoice</a>
                </div>
            @else

         @php
    // -------------------------------------------
    // MULTI-PRODUCT SALES ANALYTICS (correct: subtotal)
    // -------------------------------------------

    $productSales = [];
    
    $userStoreIds = auth()->user()->is_superadmin ? null : auth()->user()->stores->pluck('id')->toArray();
    

    foreach ($orders as $order) {
        foreach ($order->items as $item) {
            if ($userStoreIds && $item->product && !in_array($item->product->store_id, $userStoreIds)) {
                continue;
            }

            $name = $item->product->name ?? 'Unknown Product';
            $qty = intval($item->quantity);

            // FIX: Use subtotal (correct product sale amount)
            $amount = (float) $item->subtotal; // ← THIS FIXES THE 0 ISSUE

            if (!isset($productSales[$name])) {
                $productSales[$name] = [
                    'qty'     => 0,
                    'amount'  => 0.0,
                    'entries' => 0
                ];
            }

            $productSales[$name]['qty']     += $qty;
            $productSales[$name]['amount']  += $amount;
            $productSales[$name]['entries']++;
        }
    }

    // Correct numeric sorting
    $sortedProducts = collect($productSales)->sortByDesc(fn($v) => $v['entries']);

    $maxByQty = collect($productSales)
        ->sortByDesc(fn($v) => $v['qty'])
        ->keys()
        ->first();

    $maxByAmount = collect($productSales)
        ->sortByDesc(fn($v) => $v['amount'])
        ->keys()
        ->first();

    $maxByEntries = collect($productSales)
        ->sortByDesc(fn($v) => $v['entries'])
        ->keys()
        ->first();

    // totals for filters work on invoice total_amount
    $totals = $orders->map(fn($o) => (float) $o->total_amount);
    $maxTotal = $totals->max() ?? 0;
    $minTotal = $totals->min() ?? 0;
    $uniqueTotals = $totals->unique()->sort()->values();
    $useRanges = ($uniqueTotals->count() > 6 && $maxTotal - $minTotal > 500);
@endphp



            <div class="container-xl">

                {{-- Buttons --}}
                <div class="d-flex justify-content-end align-items-center mb-3 gap-2 flex-wrap">

                    <button class="btn btn-outline-secondary" id="toggleFilters">
                        <i class="fa fa-filter"></i> Toggle Filters
                    </button>

                    <a href="{{ route('invoices.export') }}" class="btn btn-success">
                        <i class="fa fa-download"></i> Download Invoices
                    </a>

                    <a href="{{ route('invoices.create') }}" class="btn btn-primary">
                        <i class="fa fa-plus"></i> Add Invoice
                    </a>
                </div>

                {{-- Filters --}}
                <div id="filtersSection" class="card shadow-sm border-0 mb-4" style="display:none;">
                    <div class="card-body d-flex flex-wrap gap-3">

                        {{-- Product --}}
                        <div class="d-flex align-items-center gap-2">
                            <label class="fw-semibold">Product:</label>
                            <select id="productFilter" class="form-select" style="min-width:200px;">
                                <option value="">All</option>
                                @foreach($sortedProducts as $name => $data)
                                    <option value="{{ $name }}">
                                        {{ $name }} ({{ $data['entries'] }} orders)
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Total --}}
                        <div class="d-flex align-items-center gap-2">
                            <label class="fw-semibold">Total:</label>
                            <select id="totalFilter" class="form-select" style="min-width:220px;">
                                <option value="">All</option>

                                @if($useRanges)
                                    @php $range = ceil($maxTotal/4); @endphp
                                    <option value="0-{{ $range }}"> {{ format_currency(0) }} - {{ format_currency($range) }}</option>
                                    <option value="{{ $range+1 }}-{{ $range*2 }}">{{ format_currency($range+1) }} - {{ format_currency($range*2) }}</option>
                                    <option value="{{ $range*2+1 }}-{{ $range*3 }}">{{ format_currency($range*2+1) }} - {{ format_currency($range*3) }}</option>
                                    <option value="{{ $range*3+1 }}-{{ $maxTotal }}"> {{ format_currency($range*3+1) }} - {{ format_currency($maxTotal) }}</option>
                                @else
                                    @foreach($uniqueTotals as $t)
                                        <option value="{{ $t }}">{{ format_currency($t) }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        {{-- Profit Filter (NEW) --}}
                        <div class="d-flex align-items-center gap-2">
                            <label class="fw-semibold">Profit:</label>
                            <select id="profitFilter" class="form-select" style="min-width:220px;">
                                <option value="">All</option>
                                <option value="loss">Only Loss</option>
                                <option value="0-100">0 – 100</option>
                                <option value="101-500">101 – 500</option>
                                <option value="501-1000">501 – 1000</option>
                                <option value="1000-">1000+</option>
                            </select>
                        </div>

                        {{-- Date --}}
                        <div class="d-flex align-items-center gap-2">
                            <label class="fw-semibold">Date:</label>
                            <input type="date" id="startDate" class="form-control">
                            <span class="fw-bold">to</span>
                            <input type="date" id="endDate" class="form-control">
                        </div>

                        {{-- Search --}}
                        <div class="d-flex align-items-center gap-2">
                            <label class="fw-semibold">Search:</label>
                            <input type="text" id="globalSearch" class="form-control" placeholder="Search orders..." style="min-width:200px;">
                        </div>
                    </div>
                </div>


                {{-- Stats --}}
                @php
                    $currencyIcons = [
                        'INR' => 'fa-indian-rupee-sign',
                        'USD' => 'fa-dollar-sign',
                        'EUR' => 'fa-euro-sign',
                        'GBP' => 'fa-pound-sign'
                    ];

                    $currencyCode = config('app.currency_code');
                    $currencyIcon = $currencyIcons[$currencyCode] ?? 'fa-money-bill';
                @endphp

                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="card shadow-sm border-0 text-center p-3">
                            <h6 class="text-muted"><i class="fa fa-cubes text-primary"></i> Top by Quantity</h6>
                            <h5 class="fw-bold text-primary">{{ $maxByQty ?? 'N/A' }}</h5>
                            <small>{{ $productSales[$maxByQty]['qty'] ?? 0 }} units</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card shadow-sm border-0 text-center p-3">
                            <h6 class="text-muted"><i class="fa {{ $currencyIcon }} text-success"></i> Top by Amount</h6>
                            <h5 class="fw-bold text-success">{{ $maxByAmount ?? 'N/A' }}</h5>
                            <small>@currency($productSales[$maxByAmount]['amount'] ?? 0)</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card shadow-sm border-0 text-center p-3">
                            <h6 class="text-muted"><i class="fa fa-list text-warning"></i> Top by Orders</h6>
                            <h5 class="fw-bold text-warning">{{ $maxByEntries ?? 'N/A' }}</h5>
                            <small>{{ $productSales[$maxByEntries]['entries'] ?? 0 }} orders</small>
                        </div>
                    </div>
                </div>

                {{-- Table --}}
                <table class="table table-striped table-bordered" id="orders">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Invoice No</th>
                            <th>Products</th>
                            <th>Customer</th>
                            <th>Date</th>
                            <th>Total</th>
                            <th>Profit</th> {{-- NEW --}}
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($orders as $order)
                        <tr>
                            <td>{{ $order->id }}</td>
                            <td>{{ $order->invoice_no }}</td>

                            <td>
                                @foreach ($order->items as $it)
                                    {{ $it->product->name }} (x{{ $it->quantity }})<br>
                                @endforeach
                            </td>

                            <td>{{ $order->customer_name }}</td>

                            <td>{{ date('d-m-Y', strtotime($order->invoice_date)) }}</td>

                            <td>@currency($order->total_amount)</td>

                            {{-- PROFIT COLUMN --}}
                            <td class="{{ $order->total_profit >= 0 ? 'text-success' : 'text-danger' }}">
                                @currency($order->total_profit)
                            </td>

                            <td>
                                <a href="{{ route('invoices.show',$order->id) }}" style="color:red;">
                                    <i class="fa fa-eye"></i>
                                </a>

                                <a href="{{ route('invoices.downloadInvoice',$order->id) }}" target="_blank" style="color:red;">
                                    <i class="fa fa-print"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
            @endif

        </div>
    </div>
</div>

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet"/>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

@include('admin.layouts.footer')

<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>

{{-- DataTables + Filters JS --}}
<script>
$(document).ready(function () {

   const table = $('#orders').DataTable({
    pageLength: 25,
    order: [[0, "desc"]], // SHOW LATEST FIRST (Sort by ID)
    // OR use [[4, "desc"]] for date column
    dom: '<"d-flex justify-content-between mb-2"Bf>rtip',
    buttons: [
        {
            extend: 'excelHtml5',
            text: '<i class="fa fa-file-excel"></i> Download Filtered Invoices',
            className: 'btn btn-success btn-sm',
            exportOptions: { columns: [0,1,2,3,4,5,6] }
        }
    ]
});


    $('#toggleFilters').click(() => $('#filtersSection').slideToggle());

    $('#productFilter').change(function(){ table.column(2).search(this.value).draw(); });

    $('#globalSearch').keyup(function(){ table.search(this.value).draw(); });

    // TOTAL FILTER
    $('#totalFilter').change(function(){
        let value = this.value;

        $.fn.dataTable.ext.search = $.fn.dataTable.ext.search.filter(fn => !fn._totalFilter);

        if (!value) { table.draw(); return; }

        let filterFn = function(settings,data){
            let total = parseFloat(data[5].replace(/[₹,]/g,'')) || 0;

            if (value.includes('-')) {
                let [min, max] = value.split('-').map(v => parseFloat(v) || 0);
                if (!max) max = Infinity;
                return total >= min && total <= max;
            }

            return total == parseFloat(value);
        };

        filterFn._totalFilter = true;

        $.fn.dataTable.ext.search.push(filterFn);
        table.draw();
    });

    // PROFIT FILTER (NEW)
    $('#profitFilter').change(function () {
        let val = this.value;

        $.fn.dataTable.ext.search = $.fn.dataTable.ext.search.filter(fn => !fn._profitFilter);

        if (!val) { table.draw(); return; }

        let filterFn = function(settings, data) {
            let profit = parseFloat(data[6].replace(/[₹,]/g,'')) || 0;

            if (val === "loss") {
                return profit < 0;
            }

            if (val.includes("-")) {
                let [min, max] = val.split("-");
                min = parseFloat(min);
                max = max ? parseFloat(max) : Infinity;
                return profit >= min && profit <= max;
            }

            return true;
        };

        filterFn._profitFilter = true;

        $.fn.dataTable.ext.search.push(filterFn);
        table.draw();
    });

    // Date filter
    $.fn.dataTable.ext.search.push(function(settings,data){
        let min = $('#startDate').val(), max = $('#endDate').val();
        let date = new Date(data[4]);

        if (min && date < new Date(min)) return false;
        if (max && date > new Date(max)) return false;
        return true;
    });

    $('#startDate, #endDate').change(()=> table.draw());
});
</script>
