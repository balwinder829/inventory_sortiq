@include('admin.layouts.header')

<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="page-body">
            <div class="container-xl">

                <div class="card shadow-sm">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title mb-0">
                            <i class="fa fa-undo text-danger"></i> Return Items
                        </h3>

                        <a href="{{ route('invoices.show', $order->id) }}" class="btn btn-secondary">
                            <i class="fa fa-arrow-left"></i> Back
                        </a>
                    </div>


                    <div class="card-body">

                        {{-- Invoice Info --}}
                        <div class="row mb-4">
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
                                <label class="form-label fw-semibold">Date</label>
                                <input type="text" class="form-control"
                                       value="{{ $order->invoice_date->format('d-m-Y') }}" disabled>
                            </div>
                        </div>
                        @if(!$order->has_returnable_items)
                            <div class="alert alert-warning">
                                No item to return.
                            </div>
                        @endif
                        <form method="POST" action="{{ route('invoices.return', $order->id) }}">
                            @csrf

                            {{-- Reason --}}
                            <div class="mb-4">
                                <label class="form-label fw-semibold">Return Reason</label>
                                <textarea name="reason" class="form-control" rows="2"
                                          placeholder="Enter reason (optional)"></textarea>
                            </div>

                            {{-- Items --}}
                            @foreach($order->items as $i => $item)

                                <div class="card mb-4 border">
                                    <div class="card-header bg-light">
                                        <!-- <strong>{{ $item->product->name }}</strong> -->
                                        <strong>
    {{ $item->product->name ?? 'Deleted Product' }}

    @if($item->is_deleted)
        <span class="badge bg-danger">Deleted</span>
    @endif
</strong>
                                        <span class="float-end">
                                            Sold/Available to return: {{ $item->quantity }}
                                        </span>
                                    </div>

                                    <div class="card-body">

                                        <input type="hidden"
                                               name="items[{{ $i }}][invoice_item_id]"
                                               value="{{ $item->id }}">

                                        <table class="table table-bordered">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Batch</th>
                                                    <th class="text-center">Cost</th>
                                                    <th class="text-center">Sale</th>
                                                    <th class="text-center">Sold Qty</th>
                                                    <th class="text-center">Return</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                @foreach($item->batches as $j => $batch)

                                                    <tr>
                                                        <td>
                                                            Batch (
                                                            {{ optional($batch->batch)->created_at
                                                                ? \Carbon\Carbon::parse($batch->batch->created_at)->format('d-m-Y')
                                                                : 'N/A'
                                                            }}
                                                            )
                                                        </td>

                                                        <td class="text-center">
                                                            @currency($batch->buy_price)
                                                        </td>

                                                        <td class="text-center">
                                                            @currency($batch->sale_price)
                                                        </td>

                                                        <td class="text-center">
                                                            {{ $batch->quantity }}
                                                        </td>

                                                        <td class="text-center">

                                                            <input type="hidden"
                                                                name="items[{{ $i }}][batches][{{ $j }}][invoice_item_batch_id]"
                                                                value="{{ $batch->id }}">

                                                            <div class="d-flex justify-content-center align-items-center gap-2">

                                                               <button type="button"
    class="btn btn-sm btn-outline-danger minus-btn"
    data-target="qty_{{ $batch->id }}"
    {{ $item->is_deleted ? 'disabled' : '' }}>
    -
</button>

<span id="qty_{{ $batch->id }}" class="fw-bold">0</span>

<button type="button"
    class="btn btn-sm btn-outline-success plus-btn"
    data-max="{{ $batch->remaining_returnable }}"
    data-price="{{ $batch->sale_price }}"
    data-target="qty_{{ $batch->id }}"
    {{ $item->is_deleted ? 'disabled' : '' }}>
    +
</button>

                                                                <input type="hidden"
                                                                    name="items[{{ $i }}][batches][{{ $j }}][quantity]"
                                                                    id="input_qty_{{ $batch->id }}"
                                                                    value="0">

                                                            </div>

                                                        </td>
                                                    </tr>

                                                @endforeach
                                            </tbody>
                                        </table>

                                    </div>
                                </div>

                            @endforeach

                            {{-- Submit --}}
                            <div class="text-end">
                             <button type="submit"
                                    id="submitBtn"
                                    class="btn btn-danger"
                                    disabled
                                    {{ !$order->has_returnable_items ? 'disabled' : '' }}>
                                Process Return
                            </button>
                            </div>

                        </form>

                    </div>
                                        <div class="card mt-4 border">
    <div class="card-body d-flex justify-content-between align-items-center">

        <div>
            <strong>Total Return Qty:</strong>
            <span id="totalQty" class="text-primary">0</span>
        </div>

        <div>
            <strong>Refund Amount:</strong>
            <span id="totalRefund" class="text-success">₹0.00</span>
        </div>

    </div>
</div>
                </div>

            </div>
        </div>
    </div>
</div>

{{-- JS --}}
 
    <script>
document.addEventListener('DOMContentLoaded', function () {

    function updateTotals() {
        let totalQty = 0;
        let totalRefund = 0;

        document.querySelectorAll('.plus-btn').forEach(btn => {
            let targetId = btn.dataset.target;
            let price = parseFloat(btn.dataset.price);

            let qty = parseInt(document.getElementById(targetId).innerText);

            totalQty += qty;
            totalRefund += qty * price;
        });

        document.getElementById('totalQty').innerText = totalQty;
        document.getElementById('totalRefund').innerText = '₹' + totalRefund.toFixed(2);
        document.getElementById('submitBtn').disabled = (totalQty === 0);
    }

    document.querySelectorAll('.plus-btn').forEach(btn => {
        btn.addEventListener('click', function () {

            let targetId = this.dataset.target;
            let max = parseInt(this.dataset.max);

            let display = document.getElementById(targetId);
            let input = document.getElementById('input_' + targetId);

            let current = parseInt(display.innerText);

            if (current < max) {
                current++;
                display.innerText = current;
                input.value = current;

                updateTotals();
            }
        });
    });

    document.querySelectorAll('.minus-btn').forEach(btn => {
        btn.addEventListener('click', function () {

            let targetId = this.dataset.target;

            let display = document.getElementById(targetId);
            let input = document.getElementById('input_' + targetId);

            let current = parseInt(display.innerText);

            if (current > 0) {
                current--;
                display.innerText = current;
                input.value = current;

                updateTotals();
            }
        });
    });

});
</script>


@include('admin.layouts.footer')