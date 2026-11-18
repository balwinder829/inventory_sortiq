@include('admin.layouts.header')

<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="page-body">
            <div class="container-xl">

                <div class="card shadow-sm">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title mb-0">Invoice Details</h3>

                        <div class="d-flex gap-2">
                            <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#profitModal">
                                <i class="fa fa-chart-line"></i> View Profit
                            </button>

                           @can('return_invoice.edit')
                                    <a href="{{ route('invoices.return.page', $order->id) }}" class="btn btn-danger">
                                        <i class="fa fa-undo"></i> Return Items
                                    </a>
                                @endcan
                            

                            <a href="{{ route('invoices.index') }}" class="btn btn-secondary">
                                <i class="fa fa-arrow-left"></i> Back
                            </a>
                        </div>
                    </div>

                    <div class="card-body">

                        {{-- INVOICE SUMMARY --}}
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Invoice Date</label>
                                <input type="text" class="form-control"
                                       value="{{ $order->invoice_date->format('d-m-Y') }}" disabled>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Invoice No</label>
                                <input type="text" class="form-control"
                                       value="{{ $order->invoice_no }}" disabled>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Customer</label>
                                <input type="text" class="form-control"
                                       value="{{ $order->customer_name }}" disabled>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Payment Mode</label>
                                <input type="text" class="form-control"
                                       value="{{ ucfirst(str_replace('_',' ',$order->payment_mode)) }}" disabled>
                            </div>
                        </div>

                        {{-- ITEMS TABLE --}}
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th>Product</th>
                                        <th class="text-center">Qty</th>
                                        <th class="text-center">Price</th>
                                        <th class="text-center">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order->items as $index => $item)
                                        <tr>
                                            <td class="text-center">{{ $index + 1 }}</td>
                                            <td>{{ $item->product->name ?? 'N/A' }}</td>
                                            <td class="text-center">{{ $item->quantity }}</td>
                                            <td class="text-center">@currency($item->price)</td>
                                            <td class="text-center">@currency($item->subtotal)</td>
                                        </tr>
                                    @endforeach

                                    <tr class="table-secondary">
                                        <td colspan="4" class="text-end fw-bold">Grand Total</td>
                                        <td class="text-center fw-bold">
                                            @currency($order->total_amount)
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>

                {{-- =======================
                     PROFIT MODAL (FIFO)
                ======================== --}}
                <div class="modal fade" id="profitModal" tabindex="-1">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">

                            <div class="modal-header">
                                <h5 class="modal-title">
                                    <i class="fa fa-chart-line text-success"></i> Profit Breakdown (FIFO)
                                </h5>
                                <button class="btn-close" data-bs-dismiss="modal"></button>
                            </div>

                            <div class="modal-body">

                                <div class="alert alert-light border">
                                    <strong>Total Profit:</strong>
                                    <span class="fw-bold {{ $order->total_profit >= 0 ? 'text-success' : 'text-danger' }}">
                                        @currency($order->total_profit)
                                    </span>
                                </div>

                                <table class="table table-bordered">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Product</th>
                                            <th class="text-center">Batch Cost</th>
                                            <th class="text-center">Sale Price</th>
                                            <th class="text-center">Qty</th>
                                            <th class="text-center">Profit</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach($order->items as $item)

                                            {{-- PRODUCT TOTAL ROW --}}
                                            <tr class="table-primary">
                                                <td>{{ $item->product->name }}</td>
                                                <td class="text-center">—</td>
                                                <td class="text-center">@currency($item->price)</td>
                                                <td class="text-center">
                                                    <div>Sold: {{ $item->original_qty }}</div>

                                                    @if($item->returned_qty > 0)
                                                        <div class="text-danger small">
                                                            Returned: {{ $item->returned_qty }}
                                                        </div>
                                                    @endif

                                                    <div class="text-success small">
                                                        Current: {{ $item->quantity }}
                                                    </div>
                                                </td>
                                                
                                                <td class="text-center fw-bold text-success">
                                                    @currency($item->profit)
                                                </td>
                                            </tr>

                                            {{-- FIFO BATCH ROWS --}}
                                            @foreach($item->batches as $batch)
                                                <tr class="table-light">
                                                    <td class="ps-4 text-muted">
                                                    ↳ Batch (
                                                    {{ optional($batch->batch)->created_at 
                                                        ? \Carbon\Carbon::parse($batch->batch->created_at)->format('d-m-Y') 
                                                        : 'N/A' 
                                                    }}
                                                    )
                                                </td>


                                                <td class="text-center">
                                                    {{ $batch->quantity }} × @currency($batch->buy_price)
                                                </td>

                                                <td class="text-center">
                                                    @currency($batch->sale_price)
                                                </td>

                                                <td class="text-center">
    <div>Sold: {{ $batch->sold_qty }}</div>

    @if($batch->returned_qty > 0)
        <div class="text-danger small">
            Returned: {{ $batch->returned_qty }}
        </div>
    @endif

    <div class="text-success small">
        Available: {{ $batch->available_qty }}
    </div>
</td>

                                                <td class="text-center fw-semibold {{ $batch->profit >= 0 ? 'text-success' : 'text-danger' }}">
                                                    @currency($batch->profit)
                                                </td>
                                            </tr>
                                            @endforeach


                                        @endforeach
                                    </tbody>
                                </table>

                            </div>

                            <div class="modal-footer">
                                <button class="btn btn-secondary" data-bs-dismiss="modal">
                                    Close
                                </button>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

@include('admin.layouts.footer')
