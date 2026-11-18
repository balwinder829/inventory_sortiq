@include('admin.layouts.header')

<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="page-body">

            {{-- Filters --}}
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

            {{-- KPI Cards --}}
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card shadow-sm border-0 rounded-3 text-center">
                        <div class="card-body">
                            <h6 class="text-muted mb-1">Total Revenue</h6>
                            <h4 class="fw-bold text-success">₹{{ number_format($totalRevenue,2) }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm border-0 rounded-3 text-center">
                        <div class="card-body">
                            <h6 class="text-muted mb-1">Total Orders</h6>
                            <h4 class="fw-bold text-primary">{{ $totalOrders }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm border-0 rounded-3 text-center">
                        <div class="card-body">
                            <h6 class="text-muted mb-1">Avg Order Value</h6>
                            <h4 class="fw-bold text-warning">₹{{ number_format($avgOrderValue,2) }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm border-0 rounded-3 text-center">
                        <div class="card-body">
                            <h6 class="text-muted mb-1">Top Product</h6>
                            <h4 class="fw-bold text-danger">{{ $topProduct->name ?? 'N/A' }}</h4>
                            <small>{{ $topProduct->total_qty ?? 0 }} sold</small>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Charts --}}
            <div class="row">
                <div class="col-md-6 mb-4">
                    <div class="card shadow-sm border-0 rounded-3">
                        <div class="card-body">
                            <h6 class="fw-semibold mb-3">Daily Sales</h6>
                            <canvas id="dailySalesChart" height="200"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="card shadow-sm border-0 rounded-3">
                        <div class="card-body">
                            <h6 class="fw-semibold mb-3">Sales by Product</h6>
                            <canvas id="salesByProductChart" height="200"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-4">
                    <div class="card shadow-sm border-0 rounded-3">
                        <div class="card-body">
                            <h6 class="fw-semibold mb-3">Revenue Share</h6>
                            <canvas id="revenueShareChart" height="200"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="card shadow-sm border-0 rounded-3">
                        <div class="card-body">
                            <h6 class="fw-semibold mb-3">MoM & YoY Revenue</h6>
                            <canvas id="comparisonChart" height="200"></canvas>
                        </div>
                    </div>
                </div>
            </div>

             <div class="row">

            <div class="row mt-4">
    <!-- Day of Week Sales -->
    <div class="col-md-6">
        <div class="card shadow-sm border-0 rounded-3">
            <div class="card-body">
                <h6 class="mb-3 fw-bold">Sales by Day of Week</h6>
                <canvas id="dayOfWeekChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Least Selling Products -->
    <div class="col-md-6">
        <div class="card shadow-sm border-0 rounded-3">
            <div class="card-body">
                <h6 class="mb-3 fw-bold">Least Selling Products</h6>
                <canvas id="leastSellingChart"></canvas>
            </div>
        </div>
    </div>
</div>
</div>



        </div>
    </div>
</div>

{{-- Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    // Daily Sales Line Chart
    new Chart(document.getElementById("dailySalesChart"), {
        type: 'line',
        data: {
            labels: {!! json_encode($dailySales->keys()) !!},
            datasets: [{
                label: "Sales (₹)",
                data: {!! json_encode($dailySales->values()) !!},
                borderColor: "#4e73df",
                backgroundColor: "rgba(78, 115, 223, 0.1)",
                fill: true,
                tension: 0.3
            }]
        }
    });

    // Sales by Product (Bar)
    new Chart(document.getElementById("salesByProductChart"), {
        type: 'bar',
        data: {
            labels: {!! json_encode($salesByProduct->pluck('name')) !!},
            datasets: [{
                label: "Revenue (₹)",
                data: {!! json_encode($salesByProduct->pluck('total_amount')) !!},
                backgroundColor: "#1cc88a"
            }]
        }
    });

    // Revenue Share (Pie)
    new Chart(document.getElementById("revenueShareChart"), {
        type: 'pie',
        data: {
            labels: {!! json_encode($revenueShare->keys()) !!},
            datasets: [{
                data: {!! json_encode($revenueShare->values()) !!},
                backgroundColor: ["#4e73df", "#1cc88a", "#36b9cc", "#f6c23e", "#e74a3b"]
            }]
        }
    });

    // MoM & YoY Comparison (Bar)
    new Chart(document.getElementById("comparisonChart"), {
        type: 'bar',
        data: {
            labels: ["Last Month", "This Month", "Last Year", "This Year"],
            datasets: [{
                label: "Revenue (₹)",
                data: [
                    {{ $momLast }},
                    {{ $momCurrent }},
                    {{ $yoyLast }},
                    {{ $yoyCurrent }}
                ],
                backgroundColor: ["#f6c23e", "#4e73df", "#e74a3b", "#1cc88a"]
            }]
        }
    });

    // Day of Week Sales Chart
const dayOfWeekCtx = document.getElementById('dayOfWeekChart');
new Chart(dayOfWeekCtx, {
    type: 'bar',
    data: {
        labels: @json(array_keys($dayOfWeekSales->toArray())),
        datasets: [{
            label: 'Revenue',
            data: @json(array_values($dayOfWeekSales->toArray())),
            backgroundColor: 'rgba(54, 162, 235, 0.6)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1
        }]
    }
});

// Least Selling Products Chart
const leastSellingCtx = document.getElementById('leastSellingChart');
new Chart(leastSellingCtx, {
    type: 'bar',
    data: {
        labels: @json($leastSellingProducts->pluck('name')),
        datasets: [{
            label: 'Revenue',
            data: @json($leastSellingProducts->pluck('total_amount')),
            backgroundColor: 'rgba(255, 99, 132, 0.6)',
            borderColor: 'rgba(255, 99, 132, 1)',
            borderWidth: 1
        }]
    }
});

});
</script>

@include('admin.layouts.footer')
