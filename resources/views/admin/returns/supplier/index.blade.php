@include('admin.layouts.header')

<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">

        {{-- Success/Error Alerts --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bx bx-check-circle me-1"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bx bx-error-circle me-1"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Breadcrumbs and Actions --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold py-1 mb-0">
                <span class="text-muted fw-light">Returns & Refunds /</span> Supplier Returns
            </h4>
            <a href="{{ route('returns.supplier.create') }}" class="btn btn-primary">
                <i class="bx bx-plus me-1"></i> Record Supplier Return
            </a>
        </div>

        {{-- Metrics & Quick Overview --}}
        <div class="row">
            {{-- Total Supplier Refunds Widget --}}
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="avatar flex-shrink-0">
                                <span class="avatar-initial rounded bg-label-success p-2">
                                    <i class="bx bx-undo fs-3"></i>
                                </span>
                            </div>
                            <span class="badge bg-label-success rounded-pill">Total Value</span>
                        </div>
                        <span class="fw-semibold d-block mb-1 text-muted">Supplier Refunds Collected</span>
                        <h3 class="card-title mb-1 text-success">₹{{ number_format($totals['total_refund'], 2) }}</h3>
                        <small class="text-muted">Total value returned to supplier accounts</small>
                    </div>
                </div>
            </div>

            {{-- Return Counts Widget --}}
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="avatar flex-shrink-0">
                                <span class="avatar-initial rounded bg-label-primary p-2">
                                    <i class="bx bx-receipt fs-3"></i>
                                </span>
                            </div>
                            <span class="badge bg-label-primary rounded-pill">Total Entries</span>
                        </div>
                        <span class="fw-semibold d-block mb-1 text-muted">Supplier Returns Recorded</span>
                        <h3 class="card-title mb-1 text-primary">{{ $totals['count'] }} entries</h3>
                        <small class="text-muted">Aggregated across active stores</small>
                    </div>
                </div>
            </div>
        </div>

        {{-- Main Table Card --}}
        <div class="card shadow-sm border-0">
            <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-3 border-bottom py-3">
                <h5 class="mb-0">Supplier Return Ledger</h5>
                
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
                    <h4>No Supplier Returns Found</h4>
                    <p class="text-muted mb-0">Record your first return back to a supplier to adjust inventory levels.</p>
                    <a href="{{ route('returns.supplier.create') }}" class="btn btn-primary mt-3">
                        <i class="bx bx-plus me-1"></i> Record Supplier Return
                    </a>
                </div>
            @else
                <div class="table-responsive text-nowrap">
                    <table class="table table-hover" id="returnsTable">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Reference No</th>
                                <th>Store</th>
                                <th>Supplier</th>
                                <th class="text-end">Total Refund</th>
                                <th>Reason</th>
                                <th>Recorded By</th>
                                <th class="text-center" width="100">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            @foreach($returns as $return)
                                <tr>
                                    <td>
                                        <span class="fw-semibold">{{ \Carbon\Carbon::parse($return->return_date)->format('M d, Y') }}</span>
                                    </td>
                                    <td>
                                        <code class="text-dark fw-bold">{{ $return->reference_no ?? 'N/A' }}</code>
                                    </td>
                                    <td>
                                        <span class="badge bg-label-secondary">
                                            <i class="bx bx-store me-1"></i>{{ $return->store->name ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="fw-semibold text-dark">{{ $return->supplier->name ?? 'N/A' }}</span>
                                    </td>
                                    <td class="text-end fw-bold text-success">
                                        ₹{{ number_format($return->total_refund_amount, 2) }}
                                    </td>
                                    <td>
                                        <span class="text-wrap d-inline-block" style="max-width: 200px;" title="{{ $return->reason }}">
                                            {{ Str::limit($return->reason, 30) ?: '-' }}
                                        </span>
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ $return->creator->name ?? 'System' }}</small>
                                    </td>
                                    <td class="text-center">
                                        <div class="dropdown">
                                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                                <i class="bx bx-dots-vertical-rounded"></i>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                <a class="dropdown-item" href="{{ route('returns.supplier.show', $return->id) }}">
                                                    <i class="bx bx-show-alt me-1 text-info"></i> View Details
                                                </a>
                                                <form action="{{ route('returns.supplier.destroy', $return->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to permanently delete this return? Active item stock levels will be automatically restored/reverted!')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item">
                                                        <i class="bx bx-trash me-1 text-danger"></i> Void & Restore Stock
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
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
            pageLength: 10,
            columnDefs: [
                { targets: [5, 7], orderable: false }
            ]
        });

        // Custom Search
        $('#returnSearch').on('keyup', function () {
            table.search(this.value).draw();
        });
    }
});
</script>
