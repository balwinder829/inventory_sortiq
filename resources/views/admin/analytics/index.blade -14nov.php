@include('admin.layouts.header')

<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="page-body">

            {{-- ================= FILTERS ================= --}}
            <form method="GET" action="{{ route('analytics.index') }}" class="mb-4">
                <div class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Start Date</label>
                        <input type="date" name="start_date" value="{{ request('start_date') }}" class="form-control">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-semibold">End Date</label>
                        <input type="date" name="end_date" value="{{ request('end_date') }}" class="form-control">
                    </div>

                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fa fa-filter me-1"></i> Apply
                        </button>
                    </div>

                    <div class="col-md-3">
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
                        ['label' => 'Total Orders', 'value' => $totalOrders, 'color' => 'text-primary'],
                        ['label' => 'Avg Order Value', 'value' => format_currency($avgOrderValue), 'color' => 'text-warning'],
                        ['label' => 'Top Product', 'value' => $topProduct->name ?? 'N/A', 'color' => 'text-danger', 'sub' => $topProduct->total_qty ?? 0],
                    ];
                @endphp


                @foreach($cards as $c)
                <div class="col-md-3">
                    <div class="card shadow-sm text-center border-0">
                        <div class="card-body">
                            <h6 class="text-muted">{{ $c['label'] }}</h6>
                            <h4 class="fw-bold {{ $c['color'] }}">{{ $c['value'] }}</h4>

                            @isset($c['sub'])
                                <small>{{ $c['sub'] }} sold</small>
                            @endisset
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- ================= MAIN CHARTS ================= --}}
            <div class="row">
                <div class="col-md-6 mb-4">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h6 class="fw-semibold mb-3">Daily Sales</h6>
                            <canvas id="dailySalesChart" height="180"></canvas>
                        </div>
                    </div>
                </div>

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
                <div class="col-md-6 mb-4">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h6 class="fw-semibold mb-3">Revenue Share</h6>
                            <canvas id="revenueShareChart" height="180"></canvas>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 mb-4">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h6 class="fw-semibold mb-3">MoM & YoY Revenue</h6>
                            <canvas id="comparisonChart" height="180"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            {{-- THIRD ROW --}}
            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h6 class="fw-bold mb-3">Sales by Day of Week</h6>
                            <canvas id="dayOfWeekChart" height="180"></canvas>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h6 class="fw-bold mb-3">Least Selling Products</h6>
                            <canvas id="leastSellingChart" height="180"></canvas>
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

    // Standard colors
    const colors = {
        blue: "rgba(78,115,223,0.8)",
        green: "rgba(28,200,138,0.8)",
        yellow: "rgba(246,194,62,0.8)",
        red: "rgba(231,74,59,0.8)",
        cyan: "rgba(54,162,235,0.8)",
        pink: "rgba(255,99,132,0.7)"
    };

    // Tooltip currency format
    const currencyTooltip = {
        callbacks: {
            label: function(context) {
                return CURRENCY_SYMBOL + " " + context.raw.toLocaleString();
            }
        }
    };

    // -------- DAILY SALES --------
    new Chart(document.getElementById("dailySalesChart"), {
        type: 'line',
        data: {
            labels: {!! json_encode($dailySales->keys()) !!},
            datasets: [{
                label: "Daily Sales (" + CURRENCY_SYMBOL + ")",
                data: {!! json_encode($dailySales->values()) !!},
                borderColor: colors.blue,
                backgroundColor: "rgba(78,115,223,0.15)",
                fill: true,
                tension: 0.4
            }]
        },
        options: { plugins: { tooltip: currencyTooltip } }
    });

    // -------- SALES BY PRODUCT --------
    new Chart(document.getElementById("salesByProductChart"), {
        type: 'bar',
        data: {
            labels: {!! json_encode($salesByProduct->pluck('name')) !!},
            datasets: [{
                label: "Product Revenue (" + CURRENCY_SYMBOL + ")",
                data: {!! json_encode($salesByProduct->pluck('total_amount')) !!},
                backgroundColor: colors.green
            }]
        },
        options: { plugins: { tooltip: currencyTooltip } }
    });

    // -------- PIE: REVENUE SHARE --------
    new Chart(document.getElementById("revenueShareChart"), {
        type: 'pie',
        data: {
            labels: {!! json_encode($revenueShare->keys()) !!},
            datasets: [{
                label: "Revenue Share",
                data: {!! json_encode($revenueShare->values()) !!},
                backgroundColor: [colors.blue, colors.green, colors.cyan, colors.yellow, colors.red]
            }]
        },
        options: { plugins: { tooltip: currencyTooltip } }
    });

    // -------- MoM & YoY Revenue --------
    new Chart(document.getElementById("comparisonChart"), {
        type: 'bar',
        data: {
            labels: ["Last Month", "This Month", "Last Year", "This Year"],
            datasets: [{
                label: "Revenue (" + CURRENCY_SYMBOL + ")",
                data: [{{ $momLast }}, {{ $momCurrent }}, {{ $yoyLast }}, {{ $yoyCurrent }}],
                backgroundColor: [colors.yellow, colors.blue, colors.red, colors.green]
            }]
        },
        options: { plugins: { tooltip: currencyTooltip } }
    });

    // -------- DAY OF WEEK SALES --------
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

    // -------- LEAST SELLING PRODUCTS --------
    new Chart(document.getElementById("leastSellingChart"), {
        type: 'bar',
        data: {
            labels: @json($leastSellingProducts->pluck('name')),
            datasets: [{
                label: "Revenue (" + CURRENCY_SYMBOL + ")",
                data: @json($leastSellingProducts->pluck('total_amount')),
                backgroundColor: colors.pink
            }]
        },
        options: { plugins: { tooltip: currencyTooltip } }
    });

});
</script>

@include('admin.layouts.footer')
