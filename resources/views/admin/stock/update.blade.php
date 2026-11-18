@include('admin.layouts.header')

<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">

        <div class="container" style="width: 55%;">

            <div class="card shadow-lg rounded-3">
                <div class="card-header text-white">
                    <h4 class="mb-0">
                        <i class="fa fa-box-open me-2"></i> Update Stock
                    </h4>
                </div>

                <div class="card-body">

                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- === BUTTON: STOCK HISTORY === --}}
                    <div class="text-end mb-3">
                        <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#stockHistoryModal">
                            <i class="fa fa-clock-rotate-left"></i> View Stock History
                        </button>
                    </div>

                    <form action="{{ route('stocks.update', $item->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <input type="hidden" name="store_id" value="{{ $item->store_id }}">

                        <!-- Product Info -->
                        <div class="mb-3">
                            <label class="fw-semibold">Item Name</label>
                            <input type="text" class="form-control" value="{{ $item->name }}" disabled>
                        </div>

                        <div class="mb-3">
                            <label class="fw-semibold">SKU</label>
                            <input type="text" class="form-control" value="{{ $item->sku }}" disabled>
                        </div>

                        <div class="mb-3">
                            <label class="fw-semibold">Store</label>
                            <input type="text" class="form-control" value="{{ optional($item->store)->name }}" disabled>
                        </div>

                        <!-- Current Quantity -->
                        <div class="mb-3">
                            <label class="fw-semibold">Current Quantity</label>
                            <input type="number" class="form-control bg-light" value="{{ $item->quantity }}" disabled>
                        </div>

                        <!-- New Quantity -->
                        <div class="mb-3">
                            <label class="fw-semibold">Set New Quantity</label>
                            <input 
                                type="number" 
                                name="quantity" 
                                class="form-control" 
                                min="0"
                                value="{{ old('quantity', $item->quantity) }}"
                                required>
                        </div>

                        {{-- Update Cost Checkbox --}}
                        <div class="form-check mb-2">
                            <input 
                                class="form-check-input" 
                                type="checkbox" 
                                name="update_cost" 
                                value="1" 
                                id="updateCost">
                            <label class="form-check-label" for="updateCost">
                                Update cost price?
                            </label>
                        </div>

                        {{-- Cost Price --}}
                        <div class="mb-3">
                            <label class="fw-semibold">Cost Price</label>
                            <input 
                                type="number" 
                                name="cost_price" 
                                id="costPrice" 
                                class="form-control" 
                                step="0.01" 
                                disabled>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-save me-2"></i> Update Stock
                        </button>

                        <a href="{{ route('stocks.index') }}" class="btn btn-secondary ms-2">Cancel</a>
                    </form>

                </div>
            </div>

        </div>
    </div>
</div>


{{-- ========================================================= --}}
{{-- STOCK HISTORY MODAL (FULL VERSION WITH FILTERS + EXPORT) --}}
{{-- ========================================================= --}}
<div class="modal fade" id="stockHistoryModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fa fa-clock-rotate-left me-2"></i> Stock History
                </h5>

                <a href="{{ route('stocks.exportHistory', $item->id) }}" class="btn btn-success btn-sm me-2">
                    <i class="fa fa-file-excel"></i> Export
                </a>

                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                @php
                    $history = DB::table('stock_history')
                        ->leftJoin('users','users.id','=','stock_history.changed_by')
                        ->select('stock_history.*','users.name as user_name')
                        ->where('item_id',$item->id)
                        ->orderBy('stock_history.id','desc')
                        ->get();
                @endphp

                {{-- Filters --}}
                <div class="row mb-3 g-2">
                    <div class="col-md-3">
                        <label class="fw-bold">From Date</label>
                        <input type="date" id="histStart" class="form-control">
                    </div>

                    <div class="col-md-3">
                        <label class="fw-bold">To Date</label>
                        <input type="date" id="histEnd" class="form-control">
                    </div>

                    <div class="col-md-3">
                        <label class="fw-bold">Change Type</label>
                        <select id="histType" class="form-select">
                            <option value="">All</option>
                            <option value="increase">Increase Only</option>
                            <option value="decrease">Decrease Only</option>
                        </select>
                    </div>

                    <div class="col-md-3 d-flex align-items-end">
                        <button class="btn btn-primary w-100" id="applyHistoryFilter">
                            <i class="fa fa-filter"></i> Apply
                        </button>
                    </div>
                </div>

                {{-- History Table --}}
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="historyTable">
                        <thead class="table-light">
                            <tr>
                                <th>Old Qty</th>
                                <th>New Qty</th>
                                <th>Change</th>
                                <th>Cost Price</th>
                                <th>Reason</th>
                                <th>Updated By</th>
                                <th>Date</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($history as $h)
                                <tr>
                                    <td>{{ $h->old_quantity }}</td>
                                    <td>{{ $h->new_quantity }}</td>

                                    <td class="{{ $h->change_amount > 0 ? 'text-success' : 'text-danger' }}">
                                        {{ $h->change_amount }}
                                    </td>

                                    <td>{{ $h->cost_price ? number_format($h->cost_price,2) : '-' }}</td>

                                    <td>{{ $h->reason ?? 'Manual Update' }}</td>

                                    <td>{{ $h->user_name ?? 'Admin' }}</td>

                                    <td>{{ date('Y-m-d H:i', strtotime($h->created_at)) }}</td>
                                </tr>
                            @endforeach
                        </tbody>

                    </table>
                </div>

            </div>

        </div>
    </div>
</div>



@include('admin.layouts.footer')

{{-- ====================== JAVASCRIPT ====================== --}}
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {

    const checkbox = document.getElementById('updateCost');
    const costPriceInput = document.getElementById('costPrice');

    checkbox.addEventListener('change', function () {
        costPriceInput.disabled = !this.checked;
    });

    // ===================== HISTORY TABLE ======================
    let table = new DataTable("#historyTable", {
        pageLength: 10,
        order: [[6, "desc"]]
    });

    document.getElementById("applyHistoryFilter").addEventListener("click", () => {

        let start = document.getElementById("histStart").value;
        let end = document.getElementById("histEnd").value;
        let type = document.getElementById("histType").value;

        $.fn.dataTable.ext.search = [];

        $.fn.dataTable.ext.search.push(function (settings, data) {

            let date = new Date(data[6]);
            let change = parseInt(data[2]);

            if (start && date < new Date(start)) return false;
            if (end && date > new Date(end + " 23:59:59")) return false;

            if (type === "increase" && change <= 0) return false;
            if (type === "decrease" && change >= 0) return false;

            return true;
        });

        table.draw();
    });

});
</script>
