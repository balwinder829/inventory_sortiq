@include('admin.layouts.header')

<div class="content-wrapper">

    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">

        {{-- Page Header --}}
        <div class="page-header d-print-none">

            <div class="container-xl">

                <div class="row g-2 align-items-center mb-3">

                    <div class="col">

                        <h2 class="page-title">
                            {{ __('Supplier Details') }}
                        </h2>

                    </div>

                    <div class="col-auto ms-auto d-print-none">
                        <button type="button" class="btn btn-success me-2" data-bs-toggle="modal" data-bs-target="#recordPaymentModal">
                            <i class="fa fa-credit-card"></i> Record Payment
                        </button>
                        <a href="{{ route('suppliers.edit', $supplier->id) }}"
                           class="btn btn-primary">

                            <i class="fa fa-edit"></i>
                            Edit Supplier

                        </a>

                    </div>

                </div>

            </div>

        </div>

        {{-- Page Body --}}
        <div class="page-body">

            <div class="container-xl">

                <div class="row row-cards">

                    <!-- Success Alert -->
                    @if(session('success'))
                        <div class="col-12 mb-3">
                            <div class="alert alert-success alert-dismissible" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        </div>
                    @endif

                    <!-- Validation Errors -->
                    @if($errors->any())
                        <div class="col-12 mb-3">
                            <div class="alert alert-danger alert-dismissible" role="alert">
                                <ul class="mb-0 ps-3">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        </div>
                    @endif

                    <!-- Financial Summary Cards -->
                    <div class="col-12 mb-3">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="card border-start border-primary border-3 shadow-sm mb-3">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar bg-light-primary text-primary p-3 me-3 rounded">
                                                <i class="fa fa-shopping-cart fa-2x"></i>
                                            </div>
                                            <div>
                                                <small class="text-muted d-block mb-1">Total Purchases</small>
                                                <h4 class="mb-0 fw-bold">{{ number_format($supplier->total_purchases, 2) }}</h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card border-start border-success border-3 shadow-sm mb-3">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar bg-light-success text-success p-3 me-3 rounded">
                                                <i class="fa fa-wallet fa-2x"></i>
                                            </div>
                                            <div>
                                                <small class="text-muted d-block mb-1">Total Paid</small>
                                                <h4 class="mb-0 fw-bold">{{ number_format($supplier->total_paid, 2) }}</h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card border-start border-danger border-3 shadow-sm mb-3">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar bg-light-danger text-danger p-3 me-3 rounded">
                                                <i class="fa fa-calculator fa-2x"></i>
                                            </div>
                                            <div>
                                                <small class="text-muted d-block mb-1">Balance Due</small>
                                                <h4 class="mb-0 fw-bold">{{ number_format($supplier->balance_due, 2) }}</h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-12">

                        <div class="card shadow-sm">

                            {{-- Card Header --}}
                            <div class="card-header">

                                <h3 class="card-title">
                                    {{ __('Supplier Information') }}
                                </h3>

                            </div>

                            {{-- Table --}}
                            <div class="table-responsive">

                                <table class="table table-bordered card-table table-vcenter">

                                    <tbody>

                                        <tr>
                                            <td width="250">
                                                <strong>Supplier Name</strong>
                                            </td>

                                            <td>
                                                {{ $supplier->name ?? 'N/A' }}
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>
                                                <strong>Shop Name</strong>
                                            </td>

                                            <td>
                                                {{ $supplier->shop_name ?? 'N/A' }}
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>
                                                <strong>Supplier Type</strong>
                                            </td>

                                            <td>
                                                {{ $supplier->supplier_type ?? 'N/A' }}
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>
                                                <strong>Email Address</strong>
                                            </td>

                                            <td>
                                                {{ $supplier->email ?? 'N/A' }}
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>
                                                <strong>Phone Number</strong>
                                            </td>

                                            <td>
                                                {{ $supplier->phone ?? 'N/A' }}
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>
                                                <strong>Alternate Phone</strong>
                                            </td>

                                            <td>
                                                {{ $supplier->alternate_phone ?? 'N/A' }}
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>
                                                <strong>GST Number</strong>
                                            </td>

                                            <td>
                                                {{ $supplier->gst_number ?? 'N/A' }}
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>
                                                <strong>Address</strong>
                                            </td>

                                            <td>
                                                {{ $supplier->address ?? 'N/A' }}
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>
                                                <strong>Notes</strong>
                                            </td>

                                            <td>
                                                {{ $supplier->notes ?? 'N/A' }}
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>
                                                <strong>Status</strong>
                                            </td>

                                            <td>

                                                @if($supplier->is_active)

                                                    <span class="badge bg-success">
                                                        Active
                                                    </span>

                                                @else

                                                    <span class="badge bg-danger">
                                                        Inactive
                                                    </span>

                                                @endif

                                            </td>
                                        </tr>

                                        <tr>
                                            <td>
                                                <strong>Created At</strong>
                                            </td>

                                            <td>
                                                {{ $supplier->created_at ? $supplier->created_at->format('d M Y h:i A') : 'N/A' }}
                                            </td>
                                        </tr>

                                    </tbody>

                                </table>

                            </div>

                            {{-- Footer --}}
                            <div class="card-footer text-end">

                                <a href="{{ route('suppliers.index') }}"
                                   class="btn btn-secondary">

                                    Back

                                </a>

                            </div>

                        </div>

                    </div>

                    <!-- Stock Supply Ledger & History Card -->
                    <div class="col-lg-12 mt-4 d-print-none">
                        <div class="card shadow-sm">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h3 class="card-title m-0">Stock Supply History & Ledger</h3>
                                <div>
                                    <button onclick="window.print()" class="btn btn-sm btn-secondary me-2">
                                        <i class="fa fa-print"></i> Print Stock Report
                                    </button>
                                    <button onclick="exportTableToCSV('stock-history-table', 'supplier-stock-{{ $supplier->id }}.csv')" class="btn btn-sm btn-success">
                                        <i class="fa fa-download"></i> Export Stock CSV
                                    </button>
                                </div>
                            </div>
                            <div class="card-body border-bottom">
                                <!-- Date Filter Form -->
                                <form method="GET" action="{{ route('suppliers.show', $supplier->id) }}" class="row g-3 align-items-end">
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Start Date</label>
                                        <input type="date" name="start_date" class="form-control" value="{{ $startDateInput }}">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">End Date</label>
                                        <input type="date" name="end_date" class="form-control" value="{{ $endDateInput }}">
                                    </div>
                                    <div class="col-md-4 d-flex gap-2">
                                        <button type="submit" class="btn btn-primary w-100">
                                            <i class="fa fa-filter"></i> Filter Stock
                                        </button>
                                        @if($startDateInput || $endDateInput)
                                            <a href="{{ route('suppliers.show', $supplier->id) }}" class="btn btn-danger w-100">
                                                <i class="fa fa-refresh"></i> Reset
                                            </a>
                                        @endif
                                    </div>
                                </form>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered card-table table-vcenter m-0" id="stock-history-table">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Date Received</th>
                                            <th>Product Code</th>
                                            <th>Product Name</th>
                                            <th class="text-center">Quantity Supplied</th>
                                            <th class="text-end">Unit Cost</th>
                                            <th class="text-end">Total cost</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $totalQty = 0;
                                            $totalValuation = 0;
                                        @endphp
                                        @forelse($suppliedStocks as $detail)
                                            @php
                                                $totalQty += $detail->quantity;
                                                $totalValuation += $detail->total;
                                            @endphp
                                            <tr>
                                                <td>{{ Carbon\Carbon::parse($detail->purchase->purchase_date)->format('d M Y') }}</td>
                                                <td><code>{{ $detail->item->code ?? 'N/A' }}</code></td>
                                                <td class="fw-semibold">{{ $detail->item->name ?? 'N/A' }}</td>
                                                <td class="text-center">{{ $detail->quantity }}</td>
                                                <td class="text-end">₹{{ number_format($detail->unitcost, 2) }}</td>
                                                <td class="text-end fw-semibold">₹{{ number_format($detail->total, 2) }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center text-muted">No stock supplied by this supplier in the selected timeframe.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                    @if($suppliedStocks->isNotEmpty())
                                        <tfoot class="table-light">
                                            <tr class="fw-bold">
                                                <td colspan="3" class="text-end">Summary Totals:</td>
                                                <td class="text-center text-primary">{{ $totalQty }} Items</td>
                                                <td></td>
                                                <td class="text-end text-success">₹{{ number_format($totalValuation, 2) }}</td>
                                            </tr>
                                        </tfoot>
                                    @endif
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Print-only Stock Report Header -->
                    <div class="d-none d-print-block mb-4 text-center">
                        <h3>StockFlow Systems</h3>
                        <h5>Stock Supply History Report: {{ $supplier->name }}</h5>
                        @if($startDateInput && $endDateInput)
                            <p class="text-muted">Report Period: {{ date('d M Y', strtotime($startDateInput)) }} to {{ date('d M Y', strtotime($endDateInput)) }}</p>
                        @else
                            <p class="text-muted">Report Period: Full History</p>
                        @endif
                        <hr>
                    </div>

                    <!-- Payment History Card -->
                    <div class="col-lg-12 mt-4">
                        <div class="card shadow-sm">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h3 class="card-title m-0">Payment History Ledger</h3>
                                <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#recordPaymentModal">
                                    <i class="fa fa-plus"></i> Record Payment
                                </button>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered card-table table-vcenter m-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Date</th>
                                            <th>Amount</th>
                                            <th>Payment Mode</th>
                                            <th>Reference / Txn No</th>
                                            <th>Notes</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($supplier->payments()->orderBy('payment_date', 'desc')->get() as $payment)
                                            <tr>
                                                <td>{{ $payment->payment_date->format('d M Y') }}</td>
                                                <td class="fw-semibold text-success">{{ number_format($payment->amount, 2) }}</td>
                                                <td><span class="badge bg-light text-dark border">{{ $payment->payment_mode }}</span></td>
                                                <td>{{ $payment->reference_no ?? 'N/A' }}</td>
                                                <td>{{ $payment->notes ?? 'N/A' }}</td>
                                                <td class="text-center">
                                                    <form action="{{ route('suppliers.payments.destroy', $payment->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this payment record? This will update the supplier ledger.');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger">
                                                            <i class="fa fa-trash"></i> Void
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center text-muted">No payments recorded for this supplier.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

<!-- Record Payment Modal -->
<div class="modal fade" id="recordPaymentModal" tabindex="-1" aria-labelledby="recordPaymentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="recordPaymentModalLabel">Record Supplier Payment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('suppliers.payments.store', $supplier->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Payment Date</label>
                        <input type="date" name="payment_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Amount</label>
                        <input type="number" step="0.01" name="amount" class="form-control" value="{{ max(0.00, $supplier->balance_due) }}" placeholder="Enter amount to pay" required>
                        <small class="text-muted">Remaining Balance Due: <strong>{{ number_format($supplier->balance_due, 2) }}</strong></small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Payment Mode</label>
                        <select name="payment_mode" class="form-select" required>
                            <option value="Cash">Cash</option>
                            <option value="Bank Transfer">Bank Transfer</option>
                            <option value="Cheque">Cheque</option>
                            <option value="UPI">UPI</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Reference No / Transaction ID (Optional)</label>
                        <input type="text" name="reference_no" class="form-control" placeholder="Cheque number or UPI transaction id">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Notes (Optional)</label>
                        <textarea name="notes" class="form-control" rows="3" placeholder="Add custom ledger notes..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Save Payment</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.bg-light-primary { background-color: rgba(105, 108, 255, 0.16) !important; }
.bg-light-success { background-color: rgba(113, 221, 55, 0.16) !important; }
.bg-light-danger { background-color: rgba(255, 62, 29, 0.16) !important; }
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
</style>

<script>
// Browser-based zero-dependency HTML Table to CSV downloader
function exportTableToCSV(tableID, filename) {
    var csv = [];
    var rows = document.querySelectorAll("#" + tableID + " tr");
    
    for (var i = 0; i < rows.length; i++) {
        var row = [], cols = rows[i].querySelectorAll("td, th");
        
        for (var j = 0; j < cols.length; j++) {
            var data = cols[j].innerText.replace(/(\r\n|\n|\r)/gm, "").replace(/(\s\s)/gm, " ");
            data = data.replace(/"/g, '""');
            row.push('"' + data + '"');
        }
        
        csv.push(row.join(","));
    }

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