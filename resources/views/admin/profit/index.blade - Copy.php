@include('admin.layouts.header')

<div class="content-wrapper p-4">

    <!-- FILTERS -->
    <form method="GET" class="row g-3 mb-4">

        <!-- QUICK RANGE -->
        <div class="col-md-3">
            <label class="form-label">Quick Range</label>
            <select name="quick_range" id="quickRange" class="form-select">
                <option value="">Custom Range</option>
                <option value="today"      {{ $quick=='today' ? 'selected':'' }}>Today</option>
                <option value="yesterday"  {{ $quick=='yesterday' ? 'selected':'' }}>Yesterday</option>
                <option value="7days"      {{ $quick=='7days' ? 'selected':'' }}>Last 7 Days</option>
                <option value="this_month" {{ $quick=='this_month' ? 'selected':'' }}>This Month</option>
                <option value="last_month" {{ $quick=='last_month' ? 'selected':'' }}>Last Month</option>
                <option value="all"        {{ $quick=='all' ? 'selected':'' }}>All Time</option>
            </select>
        </div>

        <!-- START DATE -->
        <div class="col-md-3">
            <label class="form-label">Start Date</label>
            <input type="date" id="startDate" name="start_date" value="{{ $uiStart }}" class="form-control">
        </div>

        <!-- END DATE -->
        <div class="col-md-3">
            <label class="form-label">End Date</label>
            <input type="date" id="endDate" name="end_date" value="{{ $uiEnd }}" class="form-control">
        </div>

        <!-- PRODUCT FILTER -->
        <div class="col-md-3">
            <label class="form-label">Product (optional)</label>
            <select name="product_id" class="form-select">
                <option value="">All Products</option>
                @foreach($products as $p)
                    <option value="{{ $p->id }}" {{ $productId == $p->id ? 'selected':'' }}>
                        {{ $p->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-12 text-end">
            <button class="btn btn-primary mt-2">Apply</button>
        </div>

    </form>



    <!-- SUMMARY CARDS -->
    <div class="row mb-4">

        <div class="col-md-3">
            <div class="card p-3 shadow">
                <h6>Total Profit (All Time)</h6>
                <h3>@currency($overallProfit)</h3>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card p-3 shadow">
                <h6>
                    Profit ({{ \Carbon\Carbon::parse($start)->format('d-m-Y') }} 
                    → 
                    {{ \Carbon\Carbon::parse($end)->format('d-m-Y') }})
                </h6>

                <h3>@currency($totalProfit)</h3>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card p-3 shadow">
                <h6>Today's Profit</h6>
                <h3>@currency($todayProfit)</h3>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card p-3 shadow">
                <h6>This Month Profit</h6>
                <h3>@currency($thisMonthProfit)</h3>
            </div>
        </div>

    </div>



    <!-- PRODUCT SUMMARY -->
    @if($productId && $productSummary)
        <h4 class="mt-4">Product Summary</h4>

        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card p-3 shadow">
                    <h6>Total Orders</h6>
                    <h3>{{ $productSummary->total_orders ?? 0 }}</h3>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card p-3 shadow">
                    <h6>Total Units Sold</h6>
                    <h3>{{ $productSummary->total_qty ?? 0 }}</h3>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card p-3 shadow">
                    <h6>Total Revenue</h6>
                    <h3>@currency($productSummary->total_revenue ?? 0)</h3>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card p-3 shadow">
                    <h6>Total Profit</h6>
                    <h3>@currency($productSummary->total_profit ?? 0)</h3>
                </div>
            </div>
        </div>

        <button class="btn btn-secondary mb-3" data-bs-toggle="modal" data-bs-target="#productModal">
            View Orders of This Product
        </button>
    @endif



    <!-- ORDER-WISE PROFIT -->
    <h4>Order-wise Profit</h4>

    <table class="table table-bordered">
        <thead class="table-light">
            <tr>
                <th>Invoice</th>
                <th>Date</th>
                <th>Profit</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orderProfit as $o)
                <tr>
                    <td>{{ $o->invoice_no }}</td>
                    <td>{{ \Carbon\Carbon::parse($o->invoice_date)->format('d-m-Y') }}</td>
                    <td>@currency($o->total_profit)</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="fw-bold bg-light">
                <td colspan="2" class="text-end">Total Profit:</td>
                <td>@currency($totalOrderProfit)</td>
            </tr>
        </tfoot>

    </table>

</div>



<!-- PRODUCT DETAIL MODAL -->
@if($productId && $orderWiseProduct)
<div class="modal fade" id="productModal">
    <div class="modal-dialog modal-xl">
        <div class="modal-content p-4">

            <h4>Orders Containing This Product</h4>

            <table class="table table-bordered mt-3">
                <thead class="table-light">
                    <tr>
                        <th>Invoice</th>
                        <th>Date</th>
                        <th>Qty</th>
                        <th>Revenue</th>
                        <th>Profit</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orderWiseProduct as $item)
                        <tr>
                            <td>{{ $item->invoice_no }}</td>
                            <td>{{ \Carbon\Carbon::parse($item->invoice_date)->format('d-m-Y') }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>@currency($item->subtotal)</td>
                            <td>@currency($item->profit)</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="fw-bold bg-light">
                        <td class="text-end">Totals:</td>
                        <td>—</td>
                        <td>{{ $totalProductQty }}</td>
                        <td>@currency($totalProductRevenue)</td>
                        <td>@currency($totalProductProfit)</td>
                    </tr>
                </tfoot>

            </table>

        </div>
    </div>
</div>
@endif



<!-- Disable manual date fields if quick selected -->
<script>
function updateDateInputs() {
    let quick = document.getElementById('quickRange').value;
    let start = document.getElementById('startDate');
    let end   = document.getElementById('endDate');

    if (quick === '' || quick === null) {
        start.disabled = false;
        end.disabled = false;
    } else {
        start.disabled = true;
        end.disabled = true;
        start.value = '';
        end.value = '';
    }
}

updateDateInputs();
document.getElementById('quickRange').addEventListener('change', updateDateInputs);
</script>

@include('admin.layouts.footer')
