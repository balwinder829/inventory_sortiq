@include('admin.layouts.header')

<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4 d-print-none">
            <h4 class="fw-bold mb-0">
                <i class="bx bx-bar-chart-alt-2 me-2 fs-3"></i> Reports Management Center
            </h4>
            <div>
                <button onclick="window.print()" class="btn btn-secondary me-2">
                    <i class="bx bx-printer"></i> Print Report
                </button>
                <button onclick="exportTableToCSV('report-data-table', '{{ $reportType }}-report.csv')" class="btn btn-success">
                    <i class="bx bx-download"></i> Export CSV
                </button>
            </div>
        </div>

        <!-- Filter Panel -->
        <div class="card shadow-sm mb-4 d-print-none">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.reports.index') }}" id="report-filter-form">
                    <div class="row g-3 align-items-end">
                        
                        <!-- Report Type -->
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Report Type</label>
                            <select name="report_type" id="report_type" class="form-select" onchange="toggleSupplierFilter()">
                                <option value="invoices" {{ $reportType === 'invoices' ? 'selected' : '' }}>Sales / Invoices</option>
                                <option value="purchases" {{ $reportType === 'purchases' ? 'selected' : '' }}>Purchases & Stock</option>
                            </select>
                        </div>

                        <!-- Supplier Filter (Conditional) -->
                        <div class="col-md-3" id="supplier-filter-col" style="display: {{ $reportType === 'purchases' ? 'block' : 'none' }};">
                            <label class="form-label fw-semibold">Filter by Supplier</label>
                            <select name="supplier_id" class="form-select">
                                <option value="">All Suppliers (General Purchases)</option>
                                @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}" {{ $supplierId == $supplier->id ? 'selected' : '' }}>
                                        {{ $supplier->name }} ({{ $supplier->shop_name ?? 'N/A' }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Timeframe Period -->
                        <div class="col-md-2">
                            <label class="form-label fw-semibold">Timeframe</label>
                            <select name="period" id="period" class="form-select" onchange="toggleDatePickers()">
                                <option value="daily" {{ $period === 'daily' ? 'selected' : '' }}>Daily (Today)</option>
                                <option value="weekly" {{ $period === 'weekly' ? 'selected' : '' }}>Weekly (Last 7 Days)</option>
                                <option value="monthly" {{ $period === 'monthly' ? 'selected' : '' }}>Monthly (Current Month)</option>
                                <option value="custom" {{ $period === 'custom' ? 'selected' : '' }}>Custom Date Range</option>
                            </select>
                        </div>

                        <!-- Start Date -->
                        <div class="col-md-2 date-picker-group" style="display: {{ $period === 'custom' ? 'block' : 'none' }};">
                            <label class="form-label fw-semibold">Start Date</label>
                            <input type="date" name="start_date" class="form-control" value="{{ $startDate }}">
                        </div>

                        <!-- End Date -->
                        <div class="col-md-2 date-picker-group" style="display: {{ $period === 'custom' ? 'block' : 'none' }};">
                            <label class="form-label fw-semibold">End Date</label>
                            <input type="date" name="end_date" class="form-control" value="{{ $endDate }}">
                        </div>

                        <!-- Submit Button -->
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bx bx-filter-alt"></i> Generate Report
                            </button>
                        </div>

                    </div>
                </form>
            </div>
        </div>

        <!-- Metric Summary Cards -->
        <div class="row mb-4">
            
            <div class="col-md-4 mb-3">
                <div class="card border-start border-primary border-3 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="avatar bg-light-primary text-primary p-3 me-3 rounded">
                                <i class="bx bx-list-check fs-2"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block mb-1">
                                    {{ $reportType === 'invoices' ? 'Total Transactions' : ($supplierId ? 'Total Items Supplied' : 'Total Orders') }}
                                </small>
                                <h4 class="mb-0 fw-bold">{{ number_format($totals['count']) }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-3">
                <div class="card border-start border-success border-3 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="avatar bg-light-success text-success p-3 me-3 rounded">
                                <i class="bx bx-dollar-circle fs-2"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block mb-1">Total Amount</small>
                                <h4 class="mb-0 fw-bold">₹{{ number_format($totals['amount'], 2) }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if($reportType === 'invoices')
                <div class="col-md-4 mb-3">
                    <div class="card border-start border-info border-3 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="avatar bg-light-info text-info p-3 me-3 rounded">
                                    <i class="bx bx-trending-up fs-2"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block mb-1">Total Net Profit</small>
                                    <h4 class="mb-0 fw-bold text-success">₹{{ number_format($totals['profit'], 2) }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

        </div>

        <!-- Print-only Report Header -->
        <div class="d-none d-print-block mb-4 text-center">
            <h3>StockFlow Systems</h3>
            <h5>{{ ucfirst($reportType) }} Report ({{ ucfirst($period) }})</h5>
            <p class="text-muted">Report Period: {{ date('d M Y', strtotime($startDate)) }} to {{ date('d M Y', strtotime($endDate)) }}</p>
            <hr>
        </div>

        <!-- Detailed Report Table -->
        <div class="card shadow-sm">
            <div class="card-header border-bottom">
                <h5 class="card-title m-0">Detailed Transaction Report Ledger</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-striped m-0" id="report-data-table">
                    
                    @if($reportType === 'invoices')
                        <!-- Sales/Invoices Headers -->
                        <thead class="table-light">
                            <tr>
                                <th>Date</th>
                                <th>Invoice No</th>
                                <th>Customer Name</th>
                                <th>Payment Mode</th>
                                <th class="text-end">Sales Amount</th>
                                <th class="text-end text-success">Net Profit</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($results as $invoice)
                                <tr>
                                    <td>{{ $invoice->invoice_date->format('d M Y h:i A') }}</td>
                                    <td class="fw-semibold">{{ $invoice->invoice_no }}</td>
                                    <td>{{ $invoice->customer_name }}</td>
                                    <td><span class="badge bg-light text-dark border">{{ $invoice->payment_mode }}</span></td>
                                    <td class="text-end fw-semibold">₹{{ number_format($invoice->total_amount, 2) }}</td>
                                    <td class="text-end text-success fw-semibold">₹{{ number_format($invoice->total_profit, 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">No sales invoices recorded in this period.</td>
                                </tr>
                            @endforelse
                        </tbody>

                    @elseif($reportType === 'purchases')
                        
                        @if($supplierId)
                            <!-- Supplier Stock Details Headers -->
                            <thead class="table-light">
                                <tr>
                                    <th>Date Received</th>
                                    <th>Supplier</th>
                                    <th>Product Code</th>
                                    <th>Product Name</th>
                                    <th class="text-center">Quantity Supplied</th>
                                    <th class="text-end">Unit Cost</th>
                                    <th class="text-end">Total cost</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($results as $detail)
                                    <tr>
                                        <td>{{ Carbon\Carbon::parse($detail->purchase->purchase_date)->format('d M Y') }}</td>
                                        <td class="fw-semibold">{{ $detail->purchase->supplier->name }}</td>
                                        <td><code>{{ $detail->item->code ?? 'N/A' }}</code></td>
                                        <td>{{ $detail->item->name ?? 'N/A' }}</td>
                                        <td class="text-center fw-semibold">{{ $detail->quantity }}</td>
                                        <td class="text-end">₹{{ number_format($detail->unitcost, 2) }}</td>
                                        <td class="text-end fw-semibold">₹{{ number_format($detail->total, 2) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted">No stock supplied by this supplier in the selected timeframe.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        @else
                            <!-- General Purchases Headers -->
                            <thead class="table-light">
                                <tr>
                                    <th>Date</th>
                                    <th>Purchase Bill No</th>
                                    <th>Supplier</th>
                                    <th>Status</th>
                                    <th class="text-end">Total Bill Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($results as $purchase)
                                    <tr>
                                        <td>{{ Carbon\Carbon::parse($purchase->purchase_date)->format('d M Y') }}</td>
                                        <td class="fw-semibold">{{ $purchase->bill_no }}</td>
                                        <td>{{ $purchase->supplier->name }} ({{ $purchase->supplier->shop_name ?? 'N/A' }})</td>
                                        <td>
                                            @if($purchase->status === 'approved')
                                                <span class="badge bg-success">Approved</span>
                                            @else
                                                <span class="badge bg-warning">Pending</span>
                                            @endif
                                        </td>
                                        <td class="text-end fw-semibold">₹{{ number_format($purchase->total_amount, 2) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">No purchase orders recorded in this period.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        @endif

                    @endif

                </table>
            </div>
        </div>

    </div>
</div>

<style>
.bg-light-primary { background-color: rgba(105, 108, 255, 0.16) !important; }
.bg-light-success { background-color: rgba(113, 221, 55, 0.16) !important; }
.bg-light-info { background-color: rgba(3, 195, 236, 0.16) !important; }
.avatar {
    width: 48px;
    height: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.border-3 {
    border-width: 3px !important;
}

@media print {
    body * {
        visibility: hidden;
    }
    .d-print-block, .d-print-block * {
        visibility: visible;
    }
    #report-data-table, #report-data-table * {
        visibility: visible;
    }
    .d-print-block {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
    }
    #report-data-table {
        position: absolute;
        left: 0;
        top: 150px;
        width: 100%;
        border-collapse: collapse;
    }
    /* Clean up borders for print */
    table, th, td {
        border: 1px solid #ddd !important;
    }
}
</style>

<script>
function toggleSupplierFilter() {
    var reportType = document.getElementById('report_type').value;
    var supplierCol = document.getElementById('supplier-filter-col');
    if (reportType === 'purchases') {
        supplierCol.style.display = 'block';
    } else {
        supplierCol.style.display = 'none';
    }
}

function toggleDatePickers() {
    var period = document.getElementById('period').value;
    var datePickers = document.getElementsByClassName('date-picker-group');
    for (var i = 0; i < datePickers.length; i++) {
        if (period === 'custom') {
            datePickers[i].style.display = 'block';
        } else {
            datePickers[i].style.display = 'none';
        }
    }
}

// Browser-based zero-dependency HTML Table to CSV downloader
function exportTableToCSV(tableID, filename) {
    var csv = [];
    var rows = document.querySelectorAll("#" + tableID + " tr");
    
    for (var i = 0; i < rows.length; i++) {
        var row = [], cols = rows[i].querySelectorAll("td, th");
        
        for (var j = 0; j < cols.length; j++) {
            // Clean text formatting and escape double quotes
            var data = cols[j].innerText.replace(/(\r\n|\n|\r)/gm, "").replace(/(\s\s)/gm, " ");
            data = data.replace(/"/g, '""');
            row.push('"' + data + '"');
        }
        
        csv.push(row.join(","));
    }

    // Download CSV file
    var csvFile = new Blob([csv.join("\n")], {type: "text/csv;charset=utf-8;"});
    var downloadLink = document.createElement("a");
    downloadLink.download = filename;
    downloadLink.href = window.URL.createObjectURL(csvFile);
    downloadLink.style.display = "none";
    document.body.appendChild(downloadLink);
    downloadLink.click();
    document.body.removeChild(downloadLink);
}
</script>

@include('admin.layouts.footer')
