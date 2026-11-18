@include('admin.layouts.header')

<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">

        {{-- Breadcrumb and Navigation --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold py-1 mb-0">
                <span class="text-muted fw-light">Supplier Returns /</span> Details
            </h4>
            <a href="{{ route('returns.supplier.index') }}" class="btn btn-outline-secondary">
                <i class="bx bx-arrow-back me-1"></i> Back to Ledger
            </a>
        </div>

        <div class="row">
            {{-- Summary details panel --}}
            <div class="col-lg-4 mb-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header border-bottom py-3">
                        <h5 class="card-title mb-0"><i class="bx bx-receipt fs-4 text-primary me-2"></i>Return Overview</h5>
                    </div>
                    <div class="card-body pt-4">
                        <table class="table table-borderless align-middle mb-0">
                            <tbody>
                                <tr>
                                    <td class="fw-semibold text-muted py-2" width="130">Return ID:</td>
                                    <td class="fw-bold text-dark py-2">#{{ $supplierReturn->id }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold text-muted py-2">Return Date:</td>
                                    <td class="fw-bold text-dark py-2">{{ \Carbon\Carbon::parse($supplierReturn->return_date)->format('M d, Y') }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold text-muted py-2">Reference No:</td>
                                    <td class="py-2"><code class="text-dark fw-bold">{{ $supplierReturn->reference_no ?? 'N/A' }}</code></td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold text-muted py-2">Store:</td>
                                    <td class="py-2">
                                        <span class="badge bg-label-secondary">
                                            <i class="bx bx-store me-1"></i>{{ $supplierReturn->store->name ?? 'N/A' }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold text-muted py-2">Supplier:</td>
                                    <td class="fw-bold text-dark py-2">{{ $supplierReturn->supplier->name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold text-muted py-2">Total Refund:</td>
                                    <td class="fw-bold text-success fs-5 py-2">₹{{ number_format($supplierReturn->total_refund_amount, 2) }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold text-muted py-2">Recorded By:</td>
                                    <td class="text-muted py-2">{{ $supplierReturn->creator->name ?? 'System' }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold text-muted py-2">Created At:</td>
                                    <td class="text-muted py-2">{{ $supplierReturn->created_at?->format('M d, Y h:i A') ?? 'N/A' }}</td>
                                </tr>
                            </tbody>
                        </table>

                        @if($supplierReturn->reason)
                            <div class="mt-4 pt-3 border-top">
                                <h6 class="fw-bold mb-2">Reason for Return:</h6>
                                <p class="bg-light p-3 rounded mb-0 text-muted small" style="white-space: pre-wrap;">{{ $supplierReturn->reason ?? 'N/A' }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Returned Items card --}}
            <div class="col-lg-8 mb-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header border-bottom py-3">
                        <h5 class="card-title mb-0"><i class="bx bx-package fs-4 text-success me-2"></i>Returned Stock Details</h5>
                    </div>
                    <div class="card-body pt-4">
                        <div class="table-responsive text-nowrap">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Product/Item Name</th>
                                        <th class="text-center" width="120">Returned Qty</th>
                                        <th class="text-end" width="150">Purchase Price</th>
                                        <th class="text-end" width="180">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($supplierReturn->returnItems as $item)
                                        <tr>
                                            <td>
                                                <span class="fw-semibold text-dark">{{ $item->item->name ?? 'Deleted Item' }}</span>
                                            </td>
                                            <td class="text-center fw-bold text-dark">
                                                {{ $item->quantity }}
                                            </td>
                                            <td class="text-end">
                                                ₹{{ number_format($item->purchase_price, 2) }}
                                            </td>
                                            <td class="text-end fw-bold text-dark">
                                                ₹{{ number_format($item->subtotal, 2) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="3" class="text-end fw-bold">Grand Total Refund Amount:</th>
                                        <th class="text-end fw-bold text-success fs-5">₹{{ number_format($supplierReturn->total_refund_amount, 2) }}</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@include('admin.layouts.footer')
