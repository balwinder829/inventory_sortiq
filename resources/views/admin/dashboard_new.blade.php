@include('admin.layouts.header')

{{-- ApexCharts CDN --}}
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<style>
/* ── Premium Dashboard Styles ── */
:root {
  --dash-primary:   #696cff;
  --dash-success:   #71dd37;
  --dash-warning:   #ffab00;
  --dash-danger:    #ff3e1d;
  --dash-info:      #03c3ec;
  --dash-purple:    #8c57ff;
  --dash-teal:      #00bad1;
}

.dash-gradient-purple {
  background: linear-gradient(135deg, #696cff 0%, #8c57ff 100%);
}
.dash-gradient-green {
  background: linear-gradient(135deg, #71dd37 0%, #28c76f 100%);
}
.dash-gradient-orange {
  background: linear-gradient(135deg, #ffab00 0%, #ff6f00 100%);
}
.dash-gradient-blue {
  background: linear-gradient(135deg, #03c3ec 0%, #0082cc 100%);
}
.dash-gradient-danger {
  background: linear-gradient(135deg, #ff3e1d 0%, #c0392b 100%);
}
.dash-gradient-teal {
  background: linear-gradient(135deg, #00bad1 0%, #0077b6 100%);
}

.kpi-card {
  border: none;
  border-radius: 16px;
  overflow: hidden;
  transition: transform 0.25s, box-shadow 0.25s;
  position: relative;
}
.kpi-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 20px 40px rgba(0,0,0,0.15) !important;
}
.kpi-card .card-body {
  padding: 1.5rem;
}
.kpi-icon {
  width: 54px;
  height: 54px;
  border-radius: 14px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.5rem;
  background: rgba(255,255,255,0.2);
  color: #fff;
  flex-shrink: 0;
}
.kpi-value {
  font-size: 1.75rem;
  font-weight: 700;
  color: #fff;
  line-height: 1.1;
  margin-bottom: 2px;
}
.kpi-label {
  font-size: 0.78rem;
  color: rgba(255,255,255,0.8);
  font-weight: 500;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}
.kpi-badge {
  font-size: 0.72rem;
  font-weight: 600;
  padding: 3px 8px;
  border-radius: 20px;
  background: rgba(255,255,255,0.2);
  color: #fff;
  display: inline-flex;
  align-items: center;
  gap: 3px;
}
.kpi-badge.positive { background: rgba(255,255,255,0.25); }
.kpi-badge.negative { background: rgba(255,0,0,0.25); }

.chart-card {
  border: none;
  border-radius: 16px;
  box-shadow: 0 4px 20px rgba(0,0,0,0.06);
}
.chart-card .card-header {
  background: transparent;
  border-bottom: 1px solid rgba(0,0,0,0.06);
  padding: 1.2rem 1.5rem 0.8rem;
  display: flex;
  align-items: center;
  justify-content: space-between;
}
.chart-card .card-header h5 {
  font-weight: 700;
  font-size: 0.95rem;
  margin: 0;
  color: #566a7f;
}
.section-title {
  font-size: 0.7rem;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 1px;
  color: #a5b7c3;
  margin-bottom: 1rem;
  padding-bottom: 0.5rem;
  border-bottom: 2px solid #f0f0f0;
}
.store-switcher-bar {
  background: linear-gradient(135deg, #1e1e2d 0%, #2d2d4e 100%);
  border-radius: 16px;
  padding: 1rem 1.5rem;
  margin-bottom: 1.5rem;
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 1rem;
  flex-wrap: wrap;
}
.store-switcher-bar h4 {
  color: #fff;
  font-weight: 700;
  font-size: 1.1rem;
  margin: 0;
}
.store-switcher-bar .store-select {
  background: rgba(255,255,255,0.1);
  border: 1px solid rgba(255,255,255,0.2);
  color: #fff;
  border-radius: 10px;
  padding: 0.4rem 0.8rem;
  font-size: 0.85rem;
}
.store-switcher-bar .store-select option {
  background: #2d2d4e;
  color: #fff;
}
.mini-stat {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 0.8rem;
  border-radius: 12px;
  background: #f8f9fa;
  margin-bottom: 0.7rem;
  transition: background 0.2s;
}
.mini-stat:hover { background: #eef0f5; }
.mini-stat-icon {
  width: 40px;
  height: 40px;
  border-radius: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.1rem;
  flex-shrink: 0;
}
.mini-stat-label { font-size: 0.75rem; color: #8592a3; font-weight: 500; }
.mini-stat-value { font-size: 0.95rem; font-weight: 700; color: #566a7f; }

.badge-pill-sm {
  font-size: 0.7rem;
  padding: 2px 8px;
  border-radius: 20px;
  font-weight: 600;
}
.growth-up   { color: #71dd37; }
.growth-down { color: #ff3e1d; }

.recent-table th { font-size: 0.73rem; text-transform: uppercase; letter-spacing: 0.5px; color: #a5b7c3; font-weight: 600; border: none; padding: 0.6rem 1rem; }
.recent-table td { font-size: 0.85rem; color: #566a7f; padding: 0.7rem 1rem; border-color: #f0f2f5; vertical-align: middle; }
.recent-table tr:hover td { background: #f8f9fb; }

.stock-bar { height: 8px; border-radius: 10px; overflow: hidden; background: #eee; display: flex; }
.stock-bar-in { background: linear-gradient(90deg, #71dd37, #28c76f); }
.stock-bar-low { background: linear-gradient(90deg, #ffab00, #ff6f00); }
.stock-bar-out { background: linear-gradient(90deg, #ff3e1d, #c0392b); }
</style>

<div class="content-wrapper">
  <div class="container-xxl flex-grow-1 container-p-y">

    {{-- ═══════════ STORE SWITCHER & DATE FILTER BAR ═══════════ --}}
    <div class="store-switcher-bar">
      <div class="d-flex align-items-center gap-3">
        <div>
          <div style="font-size:0.72rem;color:rgba(255,255,255,0.6);text-transform:uppercase;letter-spacing:1px;">LIVE ANALYTICS</div>
          <h4 class="mb-0">
            @if($selectedStoreId)
              {{ $allStores->firstWhere('id', $selectedStoreId)?->name ?? 'Store' }} Dashboard
            @else
              {{ $isSuperAdmin ? 'All Stores — Global View' : 'My Store Dashboard' }}
            @endif
          </h4>
        </div>
        <span class="badge" style="background:rgba(113,221,55,0.2);color:#71dd37;font-size:0.7rem;padding:4px 10px;border-radius:20px;">
          <span style="width:6px;height:6px;border-radius:50%;background:#71dd37;display:inline-block;margin-right:4px;"></span>
          Live
        </span>
      </div>

      <form method="GET" action="{{ route('admin.dashboard') }}" class="d-flex align-items-center gap-3 flex-wrap">
        {{-- Store Switcher --}}
        @if($allStores->count() > 1 || $isSuperAdmin)
          <div class="d-flex align-items-center gap-2">
            <label style="color:rgba(255,255,255,0.6);font-size:0.8rem;margin:0;white-space:nowrap;">
              <i class="bx bx-store me-1"></i>Store:
            </label>
            <select name="store_id" class="store-select" onchange="this.form.submit()">
              @if($isSuperAdmin)
                <option value="" {{ !$selectedStoreId ? 'selected' : '' }}>All Stores</option>
              @endif
              @foreach($allStores as $s)
                <option value="{{ $s->id }}" {{ $selectedStoreId == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
              @endforeach
            </select>
          </div>
        @else
          @if($selectedStoreId)
            <input type="hidden" name="store_id" value="{{ $selectedStoreId }}">
          @endif
        @endif

        {{-- Date Filters --}}
        <div class="d-flex align-items-center gap-2">
          <label style="color:rgba(255,255,255,0.6);font-size:0.8rem;margin:0;white-space:nowrap;">
            <i class="bx bx-calendar me-1"></i>From:
          </label>
          <input type="date" name="start_date" class="store-select" value="{{ request('start_date') }}" onchange="this.form.submit()">
        </div>

        <div class="d-flex align-items-center gap-2">
          <label style="color:rgba(255,255,255,0.6);font-size:0.8rem;margin:0;white-space:nowrap;">
            To:
          </label>
          <input type="date" name="end_date" class="store-select" value="{{ request('end_date') }}" onchange="this.form.submit()">
        </div>

        @if(request('start_date') || request('end_date'))
          <a href="{{ route('admin.dashboard', ['store_id' => $selectedStoreId]) }}" class="badge bg-label-danger text-decoration-none py-2 px-3" style="font-size: 0.75rem; border-radius: 8px;">
            <i class="bx bx-reset me-1"></i>Reset Date
          </a>
        @endif
      </form>
    </div>

    {{-- ═══════════ ROW 1 — PRIMARY KPI CARDS ═══════════ --}}
    <div class="section-title"><i class="bx bx-trending-up me-1"></i>Revenue & Sales</div>
    <div class="row g-4 mb-4">

      {{-- Total Revenue --}}
      <div class="col-sm-6 col-xl-3">
        <div class="kpi-card card shadow-sm dash-gradient-purple">
          <div class="card-body d-flex justify-content-between align-items-start">
            <div>
              <div class="kpi-label">Total Revenue</div>
              <div class="kpi-value">{{ format_currency($totalRevenue) }}</div>
              <div class="mt-2">
                <span class="kpi-badge {{ $revenueGrowth >= 0 ? 'positive' : 'negative' }}">
                  <i class="bx {{ $revenueGrowth >= 0 ? 'bx-trending-up' : 'bx-trending-down' }}"></i>
                  {{ abs($revenueGrowth) }}% vs last month
                </span>
              </div>
            </div>
            <div class="kpi-icon"><i class="bx bx-rupee"></i></div>
          </div>
        </div>
      </div>

      {{-- Total Profit --}}
      <div class="col-sm-6 col-xl-3">
        <div class="kpi-card card shadow-sm dash-gradient-green">
          <div class="card-body d-flex justify-content-between align-items-start">
            <div>
              <div class="kpi-label">Total Profit</div>
              <div class="kpi-value">{{ format_currency($totalProfit) }}</div>
              <div class="mt-2">
                <span class="kpi-badge {{ $profitGrowth >= 0 ? 'positive' : 'negative' }}">
                  <i class="bx {{ $profitGrowth >= 0 ? 'bx-trending-up' : 'bx-trending-down' }}"></i>
                  {{ $profitMargin }}% margin
                </span>
              </div>
            </div>
            <div class="kpi-icon"><i class="bx bx-line-chart"></i></div>
          </div>
        </div>
      </div>

      {{-- Total Orders --}}
      <div class="col-sm-6 col-xl-3">
        <div class="kpi-card card shadow-sm dash-gradient-blue">
          <div class="card-body d-flex justify-content-between align-items-start">
            <div>
              <div class="kpi-label">Total Invoices</div>
              <div class="kpi-value">{{ number_format($totalOrders) }}</div>
              <div class="mt-2">
                <span class="kpi-badge positive">
                  <i class="bx bx-calendar"></i>{{ $todayOrders }} today
                </span>
              </div>
            </div>
            <div class="kpi-icon"><i class="bx bx-receipt"></i></div>
          </div>
        </div>
      </div>

      {{-- Avg Order Value --}}
      <div class="col-sm-6 col-xl-3">
        <div class="kpi-card card shadow-sm dash-gradient-orange">
          <div class="card-body d-flex justify-content-between align-items-start">
            <div>
              <div class="kpi-label">Avg Order Value</div>
              <div class="kpi-value">{{ format_currency($avgOrderValue) }}</div>
              <div class="mt-2">
                <span class="kpi-badge positive">
                  <i class="bx bx-store"></i>{{ $storesCount }} {{ Str::plural('store', $storesCount) }}
                </span>
              </div>
            </div>
            <div class="kpi-icon"><i class="bx bx-bar-chart-alt-2"></i></div>
          </div>
        </div>
      </div>

    </div>

    {{-- ═══════════ ROW 2 — TODAY + MONTH QUICK STATS ═══════════ --}}
    <div class="row g-4 mb-4">

      {{-- Today Card --}}
      <div class="col-md-6 col-xl-3">
        <div class="chart-card card h-100">
          <div class="card-body">
            <div class="d-flex align-items-center justify-content-between mb-3">
              <span class="fw-semibold text-muted" style="font-size:0.85rem;">TODAY</span>
              <span class="badge bg-label-primary rounded-pill" style="font-size:0.7rem;">{{ now()->format('d M Y') }}</span>
            </div>
            <div class="mini-stat">
              <div class="mini-stat-icon bg-label-success"><i class="bx bx-rupee text-success"></i></div>
              <div><div class="mini-stat-label">Revenue</div><div class="mini-stat-value">{{ format_currency($todayRevenue) }}</div></div>
            </div>
            <div class="mini-stat">
              <div class="mini-stat-icon bg-label-primary"><i class="bx bx-trending-up text-primary"></i></div>
              <div><div class="mini-stat-label">Profit</div><div class="mini-stat-value">{{ format_currency($todayProfit) }}</div></div>
            </div>
            <div class="mini-stat">
              <div class="mini-stat-icon bg-label-info"><i class="bx bx-receipt text-info"></i></div>
              <div><div class="mini-stat-label">Invoices</div><div class="mini-stat-value">{{ $todayOrders }}</div></div>
            </div>
            @if($topProductToday)
            <div class="mini-stat">
              <div class="mini-stat-icon bg-label-warning"><i class="bx bx-star text-warning"></i></div>
              <div><div class="mini-stat-label">Top Product</div><div class="mini-stat-value" style="font-size:0.8rem;">{{ Str::limit($topProductToday->name, 18) }}</div></div>
            </div>
            @endif
          </div>
        </div>
      </div>

      {{-- This Month Card --}}
      <div class="col-md-6 col-xl-3">
        <div class="chart-card card h-100">
          <div class="card-body">
            <div class="d-flex align-items-center justify-content-between mb-3">
              <span class="fw-semibold text-muted" style="font-size:0.85rem;">THIS MONTH</span>
              <span class="badge bg-label-success rounded-pill" style="font-size:0.7rem;">{{ now()->format('M Y') }}</span>
            </div>
            <div class="mini-stat">
              <div class="mini-stat-icon bg-label-success"><i class="bx bx-rupee text-success"></i></div>
              <div>
                <div class="mini-stat-label">Revenue</div>
                <div class="mini-stat-value">
                  {{ format_currency($monthRevenue) }}
                  <span class="ms-1 {{ $revenueGrowth >= 0 ? 'growth-up' : 'growth-down' }}" style="font-size:0.72rem;">
                    <i class="bx {{ $revenueGrowth >= 0 ? 'bx-up-arrow-alt' : 'bx-down-arrow-alt' }}"></i>{{ abs($revenueGrowth) }}%
                  </span>
                </div>
              </div>
            </div>
            <div class="mini-stat">
              <div class="mini-stat-icon bg-label-primary"><i class="bx bx-trending-up text-primary"></i></div>
              <div>
                <div class="mini-stat-label">Profit</div>
                <div class="mini-stat-value">
                  {{ format_currency($monthProfit) }}
                  <span class="ms-1 {{ $profitGrowth >= 0 ? 'growth-up' : 'growth-down' }}" style="font-size:0.72rem;">
                    <i class="bx {{ $profitGrowth >= 0 ? 'bx-up-arrow-alt' : 'bx-down-arrow-alt' }}"></i>{{ abs($profitGrowth) }}%
                  </span>
                </div>
              </div>
            </div>
            <div class="mini-stat">
              <div class="mini-stat-icon bg-label-info"><i class="bx bx-receipt text-info"></i></div>
              <div><div class="mini-stat-label">Invoices</div><div class="mini-stat-value">{{ $monthOrders }}</div></div>
            </div>
            <div class="mini-stat">
              <div class="mini-stat-icon bg-label-warning"><i class="bx bx-wallet text-warning"></i></div>
              <div><div class="mini-stat-label">Expenses</div><div class="mini-stat-value">{{ format_currency($monthExpenses) }}</div></div>
            </div>
          </div>
        </div>
      </div>

      {{-- Stock Health --}}
      <div class="col-md-6 col-xl-3">
        <div class="chart-card card h-100">
          <div class="card-body">
            <div class="d-flex align-items-center justify-content-between mb-3">
              <span class="fw-semibold text-muted" style="font-size:0.85rem;">STOCK HEALTH</span>
              <a href="{{ route('stocks.index') }}" class="badge bg-label-secondary rounded-pill text-decoration-none" style="font-size:0.7rem;">Manage</a>
            </div>
            @php
              $stockTotal = max($totalProducts, 1);
              $pIn  = round(($inStockCount / $stockTotal) * 100);
              $pLow = round(($lowStockCount / $stockTotal) * 100);
              $pOut = round(($outOfStockCount / $stockTotal) * 100);
            @endphp
            <div class="d-flex justify-content-between mb-2">
              <span style="font-size:0.78rem;color:#566a7f;">{{ $totalProducts }} Total Products</span>
              <span style="font-size:0.78rem;color:#8592a3;">Stock Value: {{ format_currency($totalStockValue) }}</span>
            </div>
            <div class="stock-bar mb-3">
              <div class="stock-bar-in" style="width:{{ $pIn }}%"></div>
              <div class="stock-bar-low" style="width:{{ $pLow }}%"></div>
              <div class="stock-bar-out" style="width:{{ $pOut }}%"></div>
            </div>
            <div class="mini-stat" style="background:#e8f8f0;">
              <div class="mini-stat-icon" style="background:#d1f0e0;"><i class="bx bx-check-circle text-success"></i></div>
              <div><div class="mini-stat-label">In Stock</div><div class="mini-stat-value text-success">{{ $inStockCount }}</div></div>
            </div>
            <div class="mini-stat" style="background:#fff8e6;">
              <div class="mini-stat-icon" style="background:#ffeab5;"><i class="bx bx-error text-warning"></i></div>
              <div><div class="mini-stat-label">Low Stock</div><div class="mini-stat-value text-warning">{{ $lowStockCount }}</div></div>
            </div>
            <div class="mini-stat" style="background:#fff0ee;">
              <div class="mini-stat-icon" style="background:#ffd5cf;"><i class="bx bx-x-circle text-danger"></i></div>
              <div><div class="mini-stat-label">Out of Stock</div><div class="mini-stat-value text-danger">{{ $outOfStockCount }}</div></div>
            </div>
          </div>
        </div>
      </div>

      {{-- Financial Summary --}}
      <div class="col-md-6 col-xl-3">
        <div class="chart-card card h-100">
          <div class="card-body">
            <div class="d-flex align-items-center justify-content-between mb-3">
              <span class="fw-semibold text-muted" style="font-size:0.85rem;">FINANCIALS</span>
              @if(request('start_date') || request('end_date'))
                <span class="badge bg-label-warning rounded-pill" style="font-size:0.7rem;">Filtered Period</span>
              @else
                <span class="badge bg-label-danger rounded-pill" style="font-size:0.7rem;">All Time</span>
              @endif
            </div>
            <div class="mini-stat">
              <div class="mini-stat-icon bg-label-success"><i class="bx bx-trending-up text-success"></i></div>
              <div><div class="mini-stat-label">Total Revenue</div><div class="mini-stat-value">{{ format_currency($totalRevenue) }}</div></div>
            </div>
            <div class="mini-stat">
              <div class="mini-stat-icon bg-label-primary"><i class="bx bx-line-chart text-primary"></i></div>
              <div><div class="mini-stat-label">Total Profit</div><div class="mini-stat-value">{{ format_currency($totalProfit) }}</div></div>
            </div>
            <div class="mini-stat">
              <div class="mini-stat-icon bg-label-danger"><i class="bx bx-wallet text-danger"></i></div>
              <div><div class="mini-stat-label">Total Expenses</div><div class="mini-stat-value">{{ format_currency($totalExpenses) }}</div></div>
            </div>
            <div class="mini-stat" style="background: {{ $netIncome >= 0 ? '#e8f8f0' : '#fff0ee' }};">
              <div class="mini-stat-icon" style="background: {{ $netIncome >= 0 ? '#d1f0e0' : '#ffd5cf' }};">
                <i class="bx bx-money {{ $netIncome >= 0 ? 'text-success' : 'text-danger' }}"></i>
              </div>
              <div><div class="mini-stat-label">Net Income</div><div class="mini-stat-value {{ $netIncome >= 0 ? 'text-success' : 'text-danger' }}">{{ format_currency($netIncome) }}</div></div>
            </div>
          </div>
        </div>
      </div>

    </div>

    {{-- ═══════════ ROW 3 — CHARTS ═══════════ --}}
    <div class="section-title mt-2"><i class="bx bx-bar-chart me-1"></i>Revenue & Profit Charts</div>
    <div class="row g-4 mb-4">

      {{-- Monthly Revenue & Profit Line Chart --}}
      <div class="col-xl-8">
        <div class="chart-card card h-100">
          <div class="card-header">
            <h5><i class="bx bx-line-chart me-2 text-primary"></i>Monthly Revenue & Profit (Last 12 Months)</h5>
            <span class="badge bg-label-primary rounded-pill" style="font-size:0.7rem;">12 Months</span>
          </div>
          <div class="card-body pt-3">
            <div id="monthlyRevenueChart"></div>
          </div>
        </div>
      </div>

      {{-- Payment Mode Donut --}}
      <div class="col-xl-4">
        <div class="chart-card card h-100">
          <div class="card-header">
            <h5><i class="bx bx-credit-card me-2 text-success"></i>Payment Modes</h5>
            <span class="badge bg-label-success rounded-pill" style="font-size:0.7rem;">This Month</span>
          </div>
          <div class="card-body pt-2">
            <div id="paymentModeChart"></div>
            <div class="mt-2">
              @foreach($paymentBreakdown as $pm)
                <div class="d-flex justify-content-between align-items-center mb-2">
                  <span style="font-size:0.8rem;color:#566a7f;text-transform:capitalize;">{{ $pm->payment_mode ?? 'N/A' }}</span>
                  <div class="d-flex align-items-center gap-2">
                    <span style="font-size:0.75rem;color:#8592a3;">{{ $pm->count }} orders</span>
                    <span class="fw-bold" style="font-size:0.82rem;">{{ format_currency($pm->total) }}</span>
                  </div>
                </div>
              @endforeach
              @if($paymentBreakdown->isEmpty())
                <p class="text-center text-muted small">No invoices this month</p>
              @endif
            </div>
          </div>
        </div>
      </div>

    </div>

    {{-- ═══════════ ROW 4 — DAILY + EXPENSES ═══════════ --}}
    <div class="row g-4 mb-4">

      {{-- Daily Sales (last 30 days) --}}
      <div class="col-xl-8">
        <div class="chart-card card h-100">
          <div class="card-header">
            <h5><i class="bx bx-area-chart me-2 text-info"></i>Daily Sales (Last 30 Days)</h5>
            <span class="badge bg-label-info rounded-pill" style="font-size:0.7rem;">30 Days</span>
          </div>
          <div class="card-body pt-3">
            <div id="dailySalesChart"></div>
          </div>
        </div>
      </div>

      {{-- Expense Breakdown --}}
      <div class="col-xl-4">
        <div class="chart-card card h-100">
          <div class="card-header">
            <h5><i class="bx bx-wallet me-2 text-danger"></i>Expense by Category</h5>
            <a href="{{ route('expenses.index') }}" class="badge bg-label-danger rounded-pill text-decoration-none" style="font-size:0.7rem;">View All</a>
          </div>
          <div class="card-body pt-2">
            <div id="expenseChart"></div>
            <div class="mt-2">
              @forelse($expenseByCategory as $ec)
                <div class="d-flex justify-content-between align-items-center mb-2">
                  <span style="font-size:0.8rem;color:#566a7f;text-transform:capitalize;">{{ $ec->category }}</span>
                  <span class="fw-bold" style="font-size:0.82rem;">{{ format_currency($ec->total) }}</span>
                </div>
              @empty
                <p class="text-center text-muted small mt-3">No expenses this month</p>
              @endforelse
            </div>
          </div>
        </div>
      </div>

    </div>

    {{-- ═══════════ ROW 5 — TOP PRODUCTS + RECENT INVOICES ═══════════ --}}
    <div class="section-title mt-2"><i class="bx bx-package me-1"></i>Products & Recent Activity</div>
    <div class="row g-4 mb-4">

      {{-- Top Products This Month --}}
      <div class="col-xl-5">
        <div class="chart-card card h-100">
          <div class="card-header">
            <h5><i class="bx bx-trophy me-2 text-warning"></i>Top Products This Month</h5>
            <span class="badge bg-label-warning rounded-pill" style="font-size:0.7rem;">By Revenue</span>
          </div>
          <div class="card-body pt-3">
            @if($topProducts->isEmpty())
              <div class="text-center py-4">
                <i class="bx bx-package display-4 text-muted"></i>
                <p class="text-muted mt-2">No sales this month</p>
              </div>
            @else
              @php $maxRev = $topProducts->max('total_revenue'); @endphp
              @foreach($topProducts as $i => $product)
                <div class="d-flex align-items-center mb-3">
                  <div class="me-3" style="width:24px;height:24px;border-radius:50%;background:linear-gradient(135deg,#696cff,#8c57ff);color:#fff;font-size:0.7rem;font-weight:700;display:flex;align-items:center;justify-content:center;flex-shrink:0;">{{ $i + 1 }}</div>
                  <div class="flex-grow-1">
                    <div class="d-flex justify-content-between mb-1">
                      <span style="font-size:0.82rem;font-weight:600;color:#566a7f;">{{ Str::limit($product->name, 28) }}</span>
                      <span style="font-size:0.8rem;font-weight:700;color:#696cff;">{{ format_currency($product->total_revenue) }}</span>
                    </div>
                    <div style="height:5px;border-radius:5px;background:#eee;overflow:hidden;">
                      <div style="height:100%;width:{{ $maxRev > 0 ? round(($product->total_revenue / $maxRev) * 100) : 0 }}%;background:linear-gradient(90deg,#696cff,#8c57ff);border-radius:5px;"></div>
                    </div>
                    <div style="font-size:0.72rem;color:#8592a3;margin-top:2px;">{{ number_format($product->total_qty) }} units · Profit: {{ format_currency($product->total_profit) }}</div>
                  </div>
                </div>
              @endforeach
            @endif
          </div>
        </div>
      </div>

      {{-- Recent Invoices --}}
      <div class="col-xl-7">
        <div class="chart-card card h-100">
          <div class="card-header">
            <h5><i class="bx bx-file me-2 text-primary"></i>Recent Invoices</h5>
            <a href="{{ route('invoices.index') }}" class="badge bg-label-primary rounded-pill text-decoration-none" style="font-size:0.7rem;">View All</a>
          </div>
          <div class="table-responsive">
            <table class="table recent-table mb-0">
              <thead>
                <tr>
                  <th>Invoice</th>
                  <th>Customer</th>
                  <th>Payment</th>
                  <th class="text-end">Amount</th>
                  <th class="text-end">Profit</th>
                  <th>Date</th>
                </tr>
              </thead>
              <tbody>
                @forelse($recentInvoices as $inv)
                  <tr>
                    <td>
                      <a href="{{ route('invoices.show', $inv->id) }}" class="fw-bold text-primary text-decoration-none">{{ $inv->invoice_no }}</a>
                    </td>
                    <td>
                      <span class="fw-semibold">{{ $inv->customer_name ?: 'Walk-in' }}</span>
                      @if($inv->customer_mobile)
                        <br><small class="text-muted">{{ $inv->customer_mobile }}</small>
                      @endif
                    </td>
                    <td>
                      <span class="badge rounded-pill
                        @if($inv->payment_mode == 'cash') bg-label-success
                        @elseif($inv->payment_mode == 'upi') bg-label-primary
                        @elseif($inv->payment_mode == 'card') bg-label-info
                        @elseif($inv->payment_mode == 'credit') bg-label-danger
                        @else bg-label-secondary @endif"
                        style="font-size:0.7rem;text-transform:capitalize;">
                        {{ $inv->payment_mode ?? 'N/A' }}
                      </span>
                    </td>
                    <td class="text-end fw-bold text-success">{{ format_currency($inv->total_amount) }}</td>
                    <td class="text-end fw-bold text-primary">{{ format_currency($inv->total_profit) }}</td>
                    <td><small class="text-muted">{{ \Carbon\Carbon::parse($inv->invoice_date)->format('d M Y') }}</small></td>
                  </tr>
                @empty
                  <tr><td colspan="6" class="text-center text-muted py-4">No invoices yet</td></tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>

    </div>

    {{-- ═══════════ ROW 6 — PURCHASE + RETURNS QUICK STATS ═══════════ --}}
    <div class="row g-4 mb-5">
      <div class="col-md-4">
        <div class="kpi-card card shadow-sm dash-gradient-teal">
          <div class="card-body d-flex justify-content-between align-items-center">
            <div>
              <div class="kpi-label">Total Purchases</div>
              <div class="kpi-value">{{ format_currency($purchaseTotal) }}</div>
              <span class="kpi-badge positive"><i class="bx bx-package"></i>{{ $purchaseCount }} orders</span>
            </div>
            <div class="kpi-icon"><i class="bx bx-cart"></i></div>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="kpi-card card shadow-sm dash-gradient-danger">
          <div class="card-body d-flex justify-content-between align-items-center">
            <div>
              <div class="kpi-label">Supplier Returns</div>
              <div class="kpi-value">{{ format_currency($supplierReturnsTotal) }}</div>
              <a href="{{ route('returns.supplier.index') }}" class="kpi-badge positive text-decoration-none">
                <i class="bx bx-undo"></i>View details
              </a>
            </div>
            <div class="kpi-icon"><i class="bx bx-undo"></i></div>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="kpi-card card shadow-sm dash-gradient-purple">
          <div class="card-body d-flex justify-content-between align-items-center">
            <div>
              <div class="kpi-label">Profit Margin</div>
              <div class="kpi-value">{{ $profitMargin }}%</div>
              <span class="kpi-badge positive"><i class="bx bx-line-chart"></i>{{ $totalOrders }} invoices</span>
            </div>
            <div class="kpi-icon"><i class="bx bx-pie-chart-alt-2"></i></div>
          </div>
        </div>
      </div>
    </div>

  </div>
</div>

@include('admin.layouts.footer')

{{-- ═══════════ APEXCHARTS SCRIPTS ═══════════ --}}
<script>
const FMT = (v) => '₹' + parseFloat(v).toLocaleString('en-IN', {minimumFractionDigits:0, maximumFractionDigits:0});

// ── MONTHLY REVENUE & PROFIT ──
(function() {
  const months   = @json($monthlySales->pluck('month'));
  const revenue  = @json($monthlySales->pluck('revenue')->map(fn($v) => (float)$v));
  const profit   = @json($monthlySales->pluck('profit')->map(fn($v) => (float)$v));

  new ApexCharts(document.querySelector('#monthlyRevenueChart'), {
    chart: { type: 'area', height: 280, toolbar: { show: false }, zoom: { enabled: false } },
    series: [
      { name: 'Revenue', data: revenue },
      { name: 'Profit',  data: profit  }
    ],
    colors: ['#696cff', '#71dd37'],
    fill: {
      type: 'gradient',
      gradient: { shadeIntensity: 1, opacityFrom: 0.4, opacityTo: 0.05, stops: [0, 100] }
    },
    stroke: { curve: 'smooth', width: 3 },
    xaxis: { categories: months, labels: { style: { fontSize: '11px', colors: '#8592a3' } } },
    yaxis: { labels: { formatter: FMT, style: { colors: '#8592a3' } } },
    tooltip: { y: { formatter: FMT } },
    legend: { position: 'top', fontSize: '12px' },
    grid: { borderColor: '#f0f2f5', strokeDashArray: 4 },
    dataLabels: { enabled: false }
  }).render();
})();

// ── DAILY SALES ──
(function() {
  const dates   = @json($dailySales->pluck('date'));
  const revenue = @json($dailySales->pluck('revenue')->map(fn($v) => (float)$v));
  const profit  = @json($dailySales->pluck('profit')->map(fn($v) => (float)$v));

  new ApexCharts(document.querySelector('#dailySalesChart'), {
    chart: { type: 'bar', height: 280, toolbar: { show: false }, stacked: false },
    series: [
      { name: 'Revenue', data: revenue },
      { name: 'Profit',  data: profit  }
    ],
    colors: ['#03c3ec', '#71dd37'],
    plotOptions: { bar: { borderRadius: 6, columnWidth: '55%' } },
    xaxis: { categories: dates, labels: { style: { fontSize: '10px', colors: '#8592a3' }, rotate: -35 } },
    yaxis: { labels: { formatter: FMT, style: { colors: '#8592a3' } } },
    tooltip: { y: { formatter: FMT } },
    legend: { position: 'top', fontSize: '12px' },
    grid: { borderColor: '#f0f2f5', strokeDashArray: 4 },
    dataLabels: { enabled: false }
  }).render();
})();

// ── PAYMENT MODE DONUT ──
(function() {
  const labels = @json($paymentBreakdown->pluck('payment_mode')->map(fn($v) => ucfirst($v ?? 'N/A')));
  const values = @json($paymentBreakdown->pluck('total')->map(fn($v) => (float)$v));

  if (labels.length === 0) return;

  new ApexCharts(document.querySelector('#paymentModeChart'), {
    chart: { type: 'donut', height: 200 },
    series: values,
    labels: labels,
    colors: ['#696cff','#71dd37','#03c3ec','#ffab00','#ff3e1d'],
    legend: { show: false },
    plotOptions: { pie: { donut: { size: '72%', labels: { show: true, total: { show: true, label: 'Total', formatter: (w) => FMT(w.globals.seriesTotals.reduce((a,b) => a+b, 0)) } } } } },
    dataLabels: { enabled: false },
    tooltip: { y: { formatter: FMT } }
  }).render();
})();

// ── EXPENSE DONUT ──
(function() {
  const labels = @json($expenseByCategory->pluck('category')->map(fn($v) => ucfirst($v)));
  const values = @json($expenseByCategory->pluck('total')->map(fn($v) => (float)$v));

  if (labels.length === 0) return;

  new ApexCharts(document.querySelector('#expenseChart'), {
    chart: { type: 'donut', height: 200 },
    series: values,
    labels: labels,
    colors: ['#ff3e1d','#ffab00','#8c57ff','#03c3ec'],
    legend: { show: false },
    plotOptions: { pie: { donut: { size: '72%', labels: { show: true, total: { show: true, label: 'Expenses', formatter: (w) => FMT(w.globals.seriesTotals.reduce((a,b) => a+b, 0)) } } } } },
    dataLabels: { enabled: false },
    tooltip: { y: { formatter: FMT } }
  }).render();
})();
</script>
