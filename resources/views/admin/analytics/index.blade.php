@include('admin.layouts.header')

<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="page-body">

            {{-- ===================== FILTERS ===================== --}}
            <form method="GET" action="{{ route('analytics.index') }}" class="card shadow-sm p-3 mb-4 border-0">
                <div class="row g-3 align-items-end">

                    {{-- Start Date --}}
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">Start Date</label>
                        <input type="date" name="start_date"
                               value="{{ request('start_date') }}"
                               class="form-control">
                    </div>

                    {{-- End Date --}}
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">End Date</label>
                        <input type="date" name="end_date"
                               value="{{ request('end_date') }}"
                               class="form-control">
                    </div>

                    {{-- Quick Range --}}
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">Quick Range</label>
                        <select name="range" class="form-select">
                            <option value="">Select</option>
                            <option value="today"      {{ request('range') == 'today' ? 'selected' : '' }}>Today</option>
                            <option value="7days"      {{ request('range') == '7days' ? 'selected' : '' }}>Last 7 Days</option>
                            <option value="month"      {{ request('range') == 'month' ? 'selected' : '' }}>This Month</option>
                            <option value="last_month" {{ request('range') == 'last_month' ? 'selected' : '' }}>Last Month</option>
                            <option value="year"       {{ request('range') == 'year' ? 'selected' : '' }}>This Year</option>
                        </select>
                    </div>

                    {{-- Store Filter --}}
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">Store</label>
                        <select name="store" class="form-select">
                            <option value="">All Stores</option>
                            @foreach(App\Models\Store::all() as $store)
                                <option value="{{ $store->id }}"
                                    {{ request('store') == $store->id ? 'selected' : '' }}>
                                    {{ $store->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Customer Mobile --}}
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">Customer Mobile</label>
                        <input type="text" name="customer"
                               value="{{ request('customer') }}"
                               class="form-control"
                               placeholder="Search customer...">
                    </div>

                    {{-- Payment Mode --}}
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">Payment Mode</label>
                        <select name="payment_mode" class="form-select">
                            <option value="">All</option>
                            <option value="cash"   {{ request('payment_mode') == 'cash' ? 'selected' : '' }}>Cash</option>
                            <option value="card"   {{ request('payment_mode') == 'card' ? 'selected' : '' }}>Card</option>
                            <option value="bank"   {{ request('payment_mode') == 'bank' ? 'selected' : '' }}>Bank</option>
                            <option value="upi"    {{ request('payment_mode') == 'upi' ? 'selected' : '' }}>UPI</option>
                            <option value="credit" {{ request('payment_mode') == 'credit' ? 'selected' : '' }}>Credit</option>
                        </select>
                    </div>

                    {{-- Product Filter --}}
                    <div class="col-md-3 mt-3">
                        <label class="form-label fw-semibold">Product</label>
                        <select name="product_id" class="form-select">
                            <option value="">All Products</option>
                            @foreach(App\Models\Item::all() as $item)
                                <option value="{{ $item->id }}"
                                    {{ request('product_id') == $item->id ? 'selected' : '' }}>
                                    {{ $item->name }} ({{ $item->sku }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Apply Filter --}}
                    <div class="col-md-3 mt-3">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fa fa-filter me-1"></i> Apply Filter
                        </button>
                    </div>

                    {{-- Reset --}}
                    <div class="col-md-3 mt-3">
                        <a href="{{ route('analytics.index') }}" class="btn btn-secondary w-100">
                            <i class="fa fa-undo me-1"></i> Reset
                        </a>
                    </div>

                </div>
            </form>

            {{-- ================= KPI CARDS ================= --}}
            <div class="row mb-4">

                @php
                    $cards = [
                        ['label' => 'Total Revenue', 'value' => format_currency($totalRevenue), 'color' => 'text-success'],
                        ['label' => 'Total Profit', 'value' => format_currency($totalProfit), 'color' => 'text-primary'],
                        ['label' => 'Total Orders', 'value' => $totalOrders, 'color' => 'text-warning'],
                        ['label' => 'Avg Order Value', 'value' => format_currency($avgOrderValue), 'color' => 'text-danger'],
                    ];
                @endphp

                @foreach($cards as $c)
                <div class="col-md-3">
                    <div class="card shadow-sm text-center border-0">
                        <div class="card-body">
                            <h6 class="text-muted">{{ $c['label'] }}</h6>
                            <h4 class="fw-bold {{ $c['color'] }}">{{ $c['value'] }}</h4>
                        </div>
                    </div>
                </div>
                @endforeach

            </div>

            {{-- ================= MAIN CHARTS ================= --}}
            <div class="row">

                {{-- Daily Revenue + Profit --}}
                <div class="col-md-6 mb-4">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h6 class="fw-semibold mb-3">Daily Revenue & Profit</h6>
                            <canvas id="dailySalesChart" height="180"></canvas>
                        </div>
                    </div>
                </div>

                {{-- Sales by Product --}}
                <div class="col-md-6 mb-4">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h6 class="fw-semibold mb-3">Sales by Product</h6>
                            <canvas id="salesByProductChart" height="180"></canvas>
                        </div>
                    </div>
                </div>

            </div>

            {{-- SECOND ROW --}}
            <div class="row">

                {{-- Revenue Share --}}
                <div class="col-md-6 mb-4">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h6 class="fw-semibold mb-3">Revenue Share</h6>
                            <canvas id="revenueShareChart" height="180"></canvas>
                        </div>
                    </div>
                </div>

                {{-- MoM & YoY --}}
                <div class="col-md-6 mb-4">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h6 class="fw-semibold mb-3">MoM & YoY Comparison</h6>
                            <canvas id="comparisonChart" height="180"></canvas>
                        </div>
                    </div>
                </div>

            </div>

            {{-- THIRD ROW --}}
            <div class="row mt-4">

                {{-- Day of Week Sales --}}
                <div class="col-md-6">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h6 class="fw-bold mb-3">Sales by Day of Week</h6>
                            <canvas id="dayOfWeekChart" height="180"></canvas>
                        </div>
                    </div>
                </div>

                {{-- Least Selling Products --}}
                <div class="col-md-6">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h6 class="fw-bold mb-3">Least Selling Products</h6>
                            <canvas id="leastSellingChart" height="180"></canvas>
                        </div>
                    </div>
                </div>

            </div>

            {{-- ================= CUSTOMER ANALYTICS ================= --}}
            <div class="row mt-5">

                {{-- Buyer of Month --}}
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm border-0">
                        <div class="card-body text-center">
                            <h6 class="fw-semibold text-muted">Buyer of the Month</h6>
                            @if($buyerOfMonth)
                                <h5 class="fw-bold text-success">{{ $buyerOfMonth->customer_name }}</h5>
                                <p class="mb-1">📞 {{ $buyerOfMonth->customer_mobile }}</p>
                                <h6 class="fw-bold">{{ format_currency($buyerOfMonth->month_spent) }}</h6>
                            @else
                                <p class="text-muted">No data available</p>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Top Buyers --}}
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <h6 class="fw-semibold mb-3">Top Buyers (Most Orders)</h6>
                            <ul class="list-group">
                                @forelse($topBuyers as $buyer)
                                    <li class="list-group-item d-flex justify-content-between">
                                        <span>
                                            <strong>{{ $buyer->customer_name }}</strong><br>
                                            <small class="text-muted">{{ $buyer->customer_mobile }}</small>
                                        </span>
                                        <span class="badge bg-primary">{{ $buyer->total_orders }} orders</span>
                                    </li>
                                @empty
                                    <li class="list-group-item text-center text-muted">No Data</li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>

                {{-- Top Spenders --}}
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <h6 class="fw-semibold mb-3">Top Spenders</h6>
                            <ul class="list-group">
                                @forelse($topSpenders as $sp)
                                    <li class="list-group-item d-flex justify-content-between">
                                        <span>
                                            <strong>{{ $sp->customer_name }}</strong><br>
                                            <small class="text-muted">{{ $sp->customer_mobile }}</small>
                                        </span>
                                        <span class="badge bg-success">{{ format_currency($sp->total_spent) }}</span>
                                    </li>
                                @empty
                                    <li class="list-group-item text-center text-muted">No Data</li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>

            </div>

            {{-- ================= CUSTOMER CHARTS ================= --}}
            <div class="row mt-4">

                {{-- Top Buyers Chart --}}
                <div class="col-md-6">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h6 class="fw-semibold mb-3">Top Buyers Chart</h6>
                            <canvas id="topBuyersChart" height="180"></canvas>
                        </div>
                    </div>
                </div>

                {{-- Top Spenders Chart --}}
                <div class="col-md-6">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h6 class="fw-semibold mb-3">Top Spenders Chart</h6>
                            <canvas id="topSpendersChart" height="180"></canvas>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>

{{-- ================= CHART JS ================= --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const CURRENCY_SYMBOL = "{{ config('app.currency_symbol') }}";
</script>

<script>
document.addEventListener("DOMContentLoaded", function () {

    /* ================= COLORS ================= */
    const colors = {
        blue: "rgba(78,115,223,0.8)",
        green: "rgba(28,200,138,0.8)",
        yellow: "rgba(246,194,62,0.8)",
        red: "rgba(231,74,59,0.8)",
        cyan: "rgba(54,162,235,0.8)",
        pink: "rgba(255,99,132,0.7)"
    };

    const currencyTooltip = {
        callbacks: {
            label: function(context) {
                return CURRENCY_SYMBOL + " " + context.raw.toLocaleString();
            }
        }
    };

    /* ================= DAILY SALES ================= */
    new Chart(document.getElementById("dailySalesChart"), {
        type: 'line',
        data: {
            labels: {!! json_encode($dailySales->pluck('date')) !!},
            datasets: [
                {
                    label: "Revenue (" + CURRENCY_SYMBOL + ")",
                    data: {!! json_encode($dailySales->pluck('revenue')) !!},
                    borderColor: colors.blue,
                    backgroundColor: "rgba(78,115,223,0.15)",
                    fill: true,
                    tension: 0.4
                },
                {
                    label: "Profit (" + CURRENCY_SYMBOL + ")",
                    data: {!! json_encode($dailySales->pluck('profit')) !!},
                    borderColor: colors.green,
                    backgroundColor: "rgba(28,200,138,0.15)",
                    fill: true,
                    tension: 0.4
                }
            ]
        },
        options: { plugins: { tooltip: currencyTooltip } }
    });

    /* ================= SALES BY PRODUCT ================= */
    new Chart(document.getElementById("salesByProductChart"), {
        type: 'bar',
        data: {
            labels: {!! json_encode($salesByProduct->pluck('name')) !!},
            datasets: [{
                label: "Revenue (" + CURRENCY_SYMBOL + ")",
                data: {!! json_encode($salesByProduct->pluck('total_amount')) !!},
                backgroundColor: colors.green
            }]
        },
        options: { plugins: { tooltip: currencyTooltip } }
    });

    /* ================= REVENUE SHARE ================= */
    new Chart(document.getElementById("revenueShareChart"), {
        type: 'pie',
        data: {
            labels: {!! json_encode($revenueShare->keys()) !!},
            datasets: [{
                data: {!! json_encode($revenueShare->values()) !!},
                backgroundColor: [
                    colors.blue, colors.green,
                    colors.cyan, colors.yellow, colors.red
                ]
            }]
        },
        options: { plugins: { tooltip: currencyTooltip } }
    });

    /* ================= MOM & YOY ================= */
    new Chart(document.getElementById("comparisonChart"), {
        type: 'bar',
        data: {
            labels: ["Last Month", "This Month", "Last Year", "This Year"],
            datasets: [{
                label: "Revenue (" + CURRENCY_SYMBOL + ")",
                data: [{{ $momLast }}, {{ $momCurrent }}, {{ $yoyLast }}, {{ $yoyCurrent }}],
                backgroundColor: [
                    colors.yellow, colors.blue,
                    colors.red, colors.green
                ]
            }]
        },
        options: { plugins: { tooltip: currencyTooltip } }
    });

    /* ================= DAY OF WEEK ================= */
    new Chart(document.getElementById("dayOfWeekChart"), {
        type: 'bar',
        data: {
            labels: @json(array_keys($dayOfWeekSales->toArray())),
            datasets: [{
                label: "Sales (" + CURRENCY_SYMBOL + ")",
                data: @json(array_values($dayOfWeekSales->toArray())),
                backgroundColor: colors.cyan
            }]
        },
        options: { plugins: { tooltip: currencyTooltip } }
    });

    /* ================= LEAST SELLING ================= */
    new Chart(document.getElementById("leastSellingChart"), {
        type: 'bar',
        data: {
            labels: @json($leastSellingProducts->pluck('name')),
            datasets: [{
                label: "Qty Sold",
                data: @json($leastSellingProducts->pluck('total_qty')),
                backgroundColor: colors.pink
            }]
        }
    });

    /* ================= TOP BUYERS ================= */
    new Chart(document.getElementById("topBuyersChart"), {
        type: 'bar',
        data: {
            labels: @json($topBuyers->pluck('customer_name')),
            datasets: [{
                label: "Orders",
                data: @json($topBuyers->pluck('total_orders')),
                backgroundColor: colors.blue
            }]
        }
    });

    /* ================= TOP SPENDERS ================= */
    new Chart(document.getElementById("topSpendersChart"), {
        type: 'bar',
        data: {
            labels: @json($topSpenders->pluck('customer_name')),
            datasets: [{
                label: "Spent (" + CURRENCY_SYMBOL + ")",
                data: @json($topSpenders->pluck('total_spent')),
                backgroundColor: colors.green
            }]
        },
        options: { plugins: { tooltip: currencyTooltip } }
    });

});
</script>

@include('admin.layouts.footer')
