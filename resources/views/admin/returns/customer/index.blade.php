@include('admin.layouts.header')

<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">

        {{-- Breadcrumbs --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold py-1 mb-0">
                <span class="text-muted fw-light">Returns & Refunds /</span> Customer Returns
            </h4>
        </div>

        {{-- Main Table Card --}}
        <div class="card shadow-sm border-0">
            <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-3 border-bottom py-3">
                <h5 class="mb-0">Customer Return Ledger</h5>
                
                {{-- Quick Filters --}}
                <div class="d-flex flex-wrap gap-2">
                    <input type="text" id="returnSearch" class="form-control w-auto" placeholder="Search returns...">
                </div>
            </div>

            @if($returns->isEmpty())
                <div class="card-body text-center py-5">
                    <div class="text-muted mb-3">
                        <i class="bx bx-undo display-1 text-primary"></i>
                    </div>
                    <h4>No Customer Returns Found</h4>
                    <p class="text-muted mb-0">Sales returns will appear here once processed from the Invoices ledger.</p>
                </div>
            @else
                <div class="table-responsive text-nowrap">
                    <table class="table table-hover" id="returnsTable">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Invoice No</th>
                                <th>Store</th>
                                <th>Customer</th>
                                <th>Product</th>
                                <th class="text-center">Quantity</th>
                                <th class="text-end">Refund Amount</th>
                                <th>Reason</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            @foreach($returns as $return)
                                <tr>
                                    <td>
                                        <span class="fw-semibold">{{ \Carbon\Carbon::parse($return->created_at)->format('M d, Y') }}</span>
                                    </td>
                                    <td>
                                        @if($return->invoice)
                                            <a href="{{ route('invoices.show', $return->invoice->id) }}" class="fw-bold text-primary">
                                                {{ $return->invoice->invoice_no }}
                                            </a>
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-label-secondary">
                                            <i class="bx bx-store me-1"></i>{{ $return->invoice->store_name ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td>
                                        {{ $return->invoice->customer_name ?? 'Walk-in Customer' }}
                                    </td>
                                    <td>
                                        <span class="fw-semibold text-dark">{{ $return->product->name ?? 'Deleted Item' }}</span>
                                    </td>
                                    <td class="text-center fw-bold">
                                        {{ $return->total_quantity }}
                                    </td>
                                    <td class="text-end fw-bold text-danger">
                                        ₹{{ number_format($return->total_refund_amount, 2) }}
                                    </td>
                                    <td>
                                        <span class="text-wrap d-inline-block" style="max-width: 250px;" title="{{ $return->reason }}">
                                            {{ $return->reason ?: '-' }}
                                        </span>
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

@include('admin.layouts.footer')

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>
$(document).ready(function () {
    if ($('#returnsTable').length) {
        const table = $('#returnsTable').DataTable({
            paging: true,
            searching: true,
            ordering: true,
            responsive: true,
            autoWidth: false,
            dom: 'rtip',
            pageLength: 10
        });

        // Custom Search
        $('#returnSearch').on('keyup', function () {
            table.search(this.value).draw();
        });
    }
});
</script>
