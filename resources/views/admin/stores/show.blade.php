@include('admin.layouts.header')

<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">

        <div class="container">

            <!-- PAGE HEADER -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3 class="fw-bold mb-1">
                        <i class="fa-solid fa-store text-primary me-2"></i>
                        Store: {{ ucfirst($store->name) }} <br>
                       
                    </h3>
                    <h6 class="text-muted mb-0">
                        Store ID:
                        <span class="fw-semibold">
                            {{ ucfirst($store->id) }}
                        </span>
                    </h6>
                    <h6 class="text-muted mb-0">
                        Assigned To:
                        <span class="fw-semibold">
                            {{ optional($store->user)->name ? ucfirst(optional($store->user)->name) : 'N/A' }}
                        </span>
                    </h6>
                </div>

                <div class="d-flex gap-2">
                    <!-- Back button -->
                    <a href="{{ route('stores.index') }}" class="btn btn-secondary">
                        <i class="fa fa-arrow-left me-1"></i> Back
                    </a>

                    <!-- Add Item -->
                    <a href="{{ route('items.create', ['store_id' => $store->id]) }}"
                       class="btn btn-primary shadow-sm">
                        <i class="fa fa-plus me-1"></i> Add Item
                    </a>
                </div>
            </div>

            <!-- LOW STOCK ALERTS -->
            @php
            
                $lowLimit = config('app.low_stock_limit', 10);  
                $mediumLimit = $lowLimit * 5;                       // e.g., 50
                                        
                $outOfStock = $store->items->where('quantity', 0)->count();
                $lowStock = $store->items->where('quantity', '<=', $lowLimit)->where('quantity', '>', 0)->count();

                $totalItems = $store->items->count();
            @endphp
            @php
                $totalInventoryValue = 0;

                foreach ($store->items as $item) {
                    foreach ($item->batches as $batch) {
                        $totalInventoryValue += $batch->remaining_quantity * $batch->cost_price;
                    }
                }
            @endphp

            <div class="row g-3 mb-4">

                <div class="col-md-3">
                    <div class="card shadow-sm border-0 p-3 text-center h-100">
                        <h6 class="text-muted">Total Items</h6>
                        <h4 class="text-primary fw-bold">{{ $totalItems }}</h4>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card shadow-sm border-0 p-3 text-center h-100">
                        <h6 class="text-muted">Out of Stock</h6>
                        <h4 class="text-danger fw-bold">{{ $outOfStock }}</h4>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card shadow-sm border-0 p-3 text-center h-100">
                        <h6 class="text-muted">Low Stock (≤ {{ $lowLimit }})</h6>
                        <h4 class="text-warning fw-bold">{{ $lowStock }}</h4>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card shadow-sm border-0 p-3 text-center h-100">
                        <h6 class="text-muted">Inventory Value</h6>
                        <h4 class="text-success fw-bold">@currency($totalInventoryValue)</h4>
                    </div>
                </div>
            </div>

            <!-- ITEMS TABLE -->
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-body p-3 p-md-4">

                    @if($store->items->isEmpty())
                        <div class="text-center py-5">
                            <h5>No items found in this store.</h5>
                            <a href="{{ route('items.create', ['store_id' => $store->id]) }}"
                               class="btn btn-primary mt-3">
                                <i class="fa fa-plus me-2"></i>Add First Item
                            </a>
                        </div>
                    @else

                        <table id="item-table" class="table table-striped table-bordered align-middle" width="100%">
                            <thead class="table-light">
                                <tr>
                                    <th>Name</th>
                                    <th>Category</th>
                                    <th>Cost Price</th>
                                    <th>Sale Price</th>
                                    <th>Quantity</th>
                                    <th>Brand</th>
                                    <th class="text-center" style="width: 130px;">Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($store->items as $item)
                                    <tr class="hover-row">
                                        <td class="fw-semibold">{{ ucfirst($item->name) }}</td>
                                        <td>{{ $item->category ?? 'Uncategorized' }}</td>
                                        <td>@currency($item->cost_price)</td>
                                        <td>@currency($item->price)</td>

                                        

                                        <td>
                                            @if($item->quantity == 0)
                                                <span class="badge bg-danger px-3 py-2">Out</span>

                                            @elseif($item->quantity <= $lowLimit)
                                                <span class="badge bg-warning text-dark px-3 py-2">{{ $item->quantity }}</span>
                                            @elseif($item->quantity <= $mediumLimit)
                                                <span class="badge bg-info text-dark px-3 py-2">{{ $item->quantity }}</span>

                                            @else
                                                <span class="badge bg-success px-3 py-2">{{ $item->quantity }}</span>
                                            @endif
                                        </td>


                                        <td>{{ ucfirst($item->brand ?? 'N/A') }}</td>

                                        <td class="text-center">

                                            <!-- Stock History Button -->
                                            <a href="{{ route('stocks.history', $item->id) }}" target="_blank"
                                               class="text-info fs-5 me-3"
                                               title="Stock History">
                                                <i class="fa fa-clock-rotate-left"></i>
                                            </a>

                                            <!-- Edit -->
                                            <a href="{{ route('items.edit', $item->id) }}"
                                               class="text-primary fs-5 me-3"
                                               title="Edit Item">
                                                <i class="fa fa-edit"></i>
                                            </a>

                                            <!-- Delete -->
                                            <a href="javascript:void(0);"
                                               class="text-danger fs-5 delete-btn"
                                               data-id="{{ $item->id }}"
                                               title="Delete Item">
                                                <i class="fa fa-trash"></i>
                                            </a>

                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                    @endif

                </div>
            </div>

        </div>

    </div>
</div>

<!-- CSS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet"/>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

<style>
    .table th { font-weight: 600; }
    .hover-row:hover { background: #f7f9fc; }
    #item-table_filter input {
        width: 260px;
        padding: 6px 10px;
        border-radius: 6px;
    }
</style>

@include('admin.layouts.footer')

<!-- JS -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>
$(document).ready(function () {

    $('#item-table').DataTable({
        pageLength: 25,
        order: [[0, "asc"]],
    });

    // Delete Item
    $('.delete-btn').on('click', function() {
        if (!confirm('Are you sure you want to delete this item?')) return;

        let itemId = $(this).data('id');

        $.ajax({
            url: '/admin/items/' + itemId,
            type: 'DELETE',
            data: { _token: '{{ csrf_token() }}' },

            success: function() {
                location.reload();
            },

            error: function() {
                alert("Failed to delete item.");
            }
        });
    });

});
</script>

</body>
</html>
