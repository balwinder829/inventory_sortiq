@include('admin.layouts.header')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
/* ---------- GLOBAL UI IMPROVEMENTS ---------- */
.analytics-card {
    border-radius: 12px;
    transition: 0.25s ease-in-out;
    border: none;
    overflow: hidden;
}
.analytics-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.12);
}

.card-title-sm {
    font-size: 0.9rem;
    font-weight: 600;
    color: #6c757d;
}
.card-value-lg {
    font-size: 1.8rem;
    font-weight: 800;
}

.table thead th {
    background: #f8f9fa !important;
    font-weight: 700;
}

.filter-card {
    border-radius: 12px;
    padding: 18px;
    background: #ffffff;
    box-shadow: 0 4px 14px rgba(0,0,0,0.06);
    border: none;
}

.modal-content {
    border-radius: 14px;
}

.badge-soft {
    padding: 6px 10px;
    border-radius: 6px;
}
</style>

<div class="content-wrapper p-4">

    <!-- -------------------- FILTER CARD -------------------- -->
    <div class="filter-card mb-4">
        <form method="GET" class="row g-3">

            <div class="col-md-3">
                <label class="form-label fw-semibold">Quick Range</label>
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

            <div class="col-md-3">
                <label class="form-label fw-semibold">Start Date</label>
                <input type="date" id="startDate" name="start_date" value="{{ $uiStart }}" class="form-control">
            </div>

            <div class="col-md-3">
                <label class="form-label fw-semibold">End Date</label>
                <input type="date" id="endDate" name="end_date" value="{{ $uiEnd }}" class="form-control">
            </div>

            <div class="col-md-3">
                <label class="form-label fw-semibold">Product (optional)</label>
                <select name="product_id" class="form-select">
                    <option value="">All Products</option>
                    @foreach($products as $p)
                        <option value="{{ $p->id }}" {{ $productId == $p->id ? 'selected':'' }}>{{ $p->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-12 text-end">
                <button class="btn btn-primary px-4 mt-2">
                    <i class="fa fa-filter me-1"></i> Apply Filters
                </button>
            </div>

        </form>
    </div>

    <!-- -------------------- SUMMARY CARDS -------------------- -->
    <div class="row g-4 mb-4">

        <div class="col-md-3">
            <div class="card analytics-card p-4 shadow-sm">
                <div class="card-title-sm">Total Profit (All Time)</div>
                <div class="card-value-lg text-success">@currency($overallProfit)</div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card analytics-card p-4 shadow-sm">
                <div class="card-title-sm">
                    Profit ({{ date('d-m-Y', strtotime($start)) }} → {{ date('d-m-Y', strtotime($end)) }})
                </div>
                <div class="card-value-lg text-primary">@currency($totalProfit)</div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card analytics-card p-4 shadow-sm">
                <div class="card-title-sm">Today's Profit</div>
                <div class="card-value-lg text-info">@currency($todayProfit)</div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card analytics-card p-4 shadow-sm">
                <div class="card-title-sm">This Month Profit</div>
                <div class="card-value-lg text-warning">@currency($thisMonthProfit)</div>
            </div>
        </div>

    </div>

    <!-- -------------------- PRODUCT SUMMARY -------------------- -->
    @if($productId && $productSummary)
        <h4 class="fw-bold mt-4 mb-3">Product Summary</h4>

        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="card analytics-card p-4 shadow-sm">
                    <div class="card-title-sm">Total Orders</div>
                    <div class="card-value-lg">{{ $productSummary->total_orders ?? 0 }}</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card analytics-card p-4 shadow-sm">
                    <div class="card-title-sm">Units Sold</div>
                    <div class="card-value-lg">{{ $productSummary->total_qty ?? 0 }}</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card analytics-card p-4 shadow-sm">
                    <div class="card-title-sm">Total Revenue</div>
                    <div class="card-value-lg text-primary">@currency($productSummary->total_revenue)</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card analytics-card p-4 shadow-sm">
                    <div class="card-title-sm">Total Profit</div>
                    <div class="card-value-lg text-success">@currency($productSummary->total_profit)</div>
                </div>
            </div>
        </div>

        <button class="btn btn-secondary mb-4" data-bs-toggle="modal" data-bs-target="#productModal">
            <i class="fa fa-list"></i> View Order Details
        </button>
    @endif

    <!-- -------------------- ORDER PROFIT TABLE -------------------- -->
    <h4 class="fw-bold">Order-wise Profit</h4>

    <div class="card shadow-sm analytics-card p-3">
        <div class="table-responsive">
            <table class="table table-bordered table-sm mb-0">
                <thead>
                <tr>
                    <th>Invoice</th>
                    <th>Date</th>
                    <th class="text-end">Profit</th>
                </tr>
                </thead>

                <tbody>
                @forelse($orderProfit as $o)
                    <tr>
                        <td>{{ $o->invoice_no }}</td>
                        <td>{{ date('d-m-Y', strtotime($o->invoice_date)) }}</td>
                        <td class="text-end fw-bold">@currency($o->total_profit)</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center text-muted">No records found</td>
                    </tr>
                @endforelse
                </tbody>

                <tfoot class="fw-bold bg-light">
                <tr>
                    <td colspan="2" class="text-end">Total Profit:</td>
                    <td class="text-end">@currency($totalOrderProfit)</td>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>

</div>

<!-- -------------------- PRODUCT MODAL -------------------- -->
@if($productId && $orderWiseProduct)
<div class="modal fade" id="productModal">
    <div class="modal-dialog modal-xl">
        <div class="modal-content p-4">

            <h4 class="fw-bold mb-3">Orders Containing This Product</h4>

            <div class="table-responsive">
                <table class="table table-bordered table-sm">
                    <thead>
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
                            <td>{{ date('d-m-Y', strtotime($item->invoice_date)) }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>@currency($item->subtotal)</td>
                            <td class="fw-bold text-success">@currency($item->profit)</td>
                        </tr>
                    @endforeach
                    </tbody>

                    <tfoot class="fw-bold bg-light">
                    <tr>
                        <td>Totals:</td>
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
</div>
@endif


<!-- Auto disable date when quick-range selected -->
<script>
function updateDateInputs() {
    let quick = document.getElementById('quickRange').value;
    let start = document.getElementById('startDate');
    let end   = document.getElementById('endDate');

    if (!quick) {
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
