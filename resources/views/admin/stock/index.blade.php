@include('admin.layouts.header')

<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="page-body">

            @if($products->isEmpty())
                <div class="empty-state text-center py-5">
                    <h2>No products found</h2>
                    <p>Try adjusting your search or filter to find what you're looking for.</p>
                </div>
            @else
                <div class="container container-xl">

                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <!-- 🔹 FILTER PANEL -->
                    <div class="card mb-4 shadow-sm border-0">
                        <div class="card-body">
                            <div class="row g-3 align-items-end">

                                <!-- Store Filter -->
                                <div class="col-md-3 col-sm-6">
                                    <label class="form-label fw-semibold">Store</label>
                                    <select id="storeFilter" class="form-select">
                                        <option value="">All Stores</option>
                                        @foreach($products->pluck('store.name')->unique() as $store)
                                            @if($store)
                                                <option value="{{ $store }}">{{ $store }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Category Filter (NEW) -->
                                <div class="col-md-3 col-sm-6">
                                    <label class="form-label fw-semibold">Category</label>
                                    <select id="categoryFilter" class="form-select">
                                        <option value="">All Categories</option>
                                        @foreach($products->pluck('category')->unique()->sort() as $category)
                                            @if($category)
                                                <option value="{{ $category }}">{{ ucfirst($category) }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Stock Status Filter -->
                                <div class="col-md-3 col-sm-6">
                                    <label class="form-label fw-semibold">Stock Status</label>
                                    <select id="quantityFilter" class="form-select">
                                        <option value="">All</option>
                                        <option value="0">Out of Stock</option>
                                        <option value="1-10">Low (1–10)</option>
                                        <option value="11-50">Medium (11–50)</option>
                                        <option value="50-">High (50+)</option>
                                    </select>
                                </div>

                                <!-- Price Range Filter (NEW) -->
                                <div class="col-md-3 col-sm-6">
                                    <label class="form-label fw-semibold">Price Range</label>
                                    <select id="priceFilter" class="form-select">
                                        <option value="">All Prices</option>
                                        <option value="0-100">0 – 100</option>
                                        <option value="101-500">101 – 500</option>
                                        <option value="501-1000">501 – 1000</option>
                                        <option value="1000-">1000+</option>
                                    </select>
                                </div>

                                <!-- Search -->
                                <div class="col-md-4 col-sm-6">
                                    <label class="form-label fw-semibold">Search</label>
                                    <input id="globalSearch" type="text" class="form-control" placeholder="Search name, SKU...">
                                </div>

                                <!-- Import Button (RIGHT ALIGNED) -->
                                <div class="col-md-2 col-sm-6 text-md-end text-sm-start ms-md-auto">
                                    <label class="form-label d-block fw-semibold invisible">Action</label>
                                    <!-- <a href="{{ route('item.import_page') }}" class="btn btn-primary w-100">
                                        <i class="fa fa-upload me-2"></i> Import
                                    </a> -->
                                    <a href="{{ route('purchases.create') }}"
                                       class="btn btn-primary">

                                        <i class="fa fa-plus"></i>
                                        Add Purchase

                                    </a>
                                </div>


                            </div>
                        </div>
                    </div>

                    <!-- 🔹 TABLE -->
                    <table class="table table-bordered table-striped" id="products">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Store</th>
                                <th>Name</th>
                                <th>SKU</th>
                                <th>Category</th>
                                <th>Available Quantity</th>
                                <th>Selling Price</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($products as $product)
                                @php $qty = $product->quantity; @endphp

                                <tr @if($qty==0) style="background:#ffecec;" @endif>

                                    <td>{{ $product->id }}</td>

                                    <td>{{ ucfirst(optional($product->store)->name ?? '') }}</td>

                                    <td>{{ ucfirst($product->name) }}</td>

                                    <td>{{ $product->sku }}</td>

                                    <td>{{ $product->category ? ucfirst($product->category) : 'Uncategorized' }}</td>

                                    <!-- STOCK BADGES -->
                                    <td>
                                        @php
                                            $lowLimit = config('app.low_stock_limit', 10);      // e.g., 10
                                            $mediumLimit = $lowLimit * 5;                       // e.g., 50
                                        @endphp

                                        @if($qty == 0)
                                            <span class="badge bg-danger px-3 py-2">Out of Stock</span>

                                        @elseif($qty <= $lowLimit)
                                            <span class="badge bg-warning text-dark px-3 py-2">Low ({{ $qty }})</span>

                                        @elseif($qty <= $mediumLimit)
                                            <span class="badge bg-info text-dark px-3 py-2">Medium ({{ $qty }})</span>

                                        @else
                                            <span class="badge bg-success px-3 py-2">High ({{ $qty }})</span>
                                        @endif


                                    <td>@currency($product->price)</td>

                                    <td class="text-center">
                                        <a href="{{ route('stocks.edit', $product->id) }}" class="text-primary" title="Edit Stock">
                                            <i class="fa fa-edit"></i>
                                        </a>
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

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet"/>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

<style>
.card-body {
    background-color: #f8f9fa;
    border-radius: 0.5rem;
}
.form-label {
    font-size: 0.9rem;
    color: #555;
}
.table thead th {
    background-color: #f1f3f5;
    font-weight: 600;
}
</style>

@include('admin.layouts.footer')

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>
$(document).ready(function () {

    const table = $('#products').DataTable({
        paging: true,
        searching: true,
        ordering: true,
        responsive: true,
        autoWidth: false,
        dom: 'lrtip',
        pageLength: 25
    });

    // Global Search
    $('#globalSearch').on('keyup', function () {
        table.search(this.value).draw();
    });

    // Store Filter
    $('#storeFilter').on('change', function () {
        table.column(1).search(this.value).draw();
    });

    // Category Filter
    $('#categoryFilter').on('change', function () {
        table.column(4).search(this.value).draw();
    });

    // Stock Filter
    $('#quantityFilter').on('change', function () {
        const val = $(this).val();

        table.rows().every(function () {

            let cellText = $(this.node()).find('td:eq(5)').text().trim();
            let qty = parseInt(cellText.match(/\d+/)?.[0] ?? 0);

            let match = false;

            if (!val) match = true;
            else if (val === '0') match = qty === 0;
            else if (val.includes('-')) {
                const [min, max] = val.split('-').map(v => v ? parseInt(v) : null);
                if (max === null) match = qty > min;
                else match = qty >= min && qty <= max;
            }

            $(this.node()).toggle(match);
        });
    });

    // Price Range Filter
    $('#priceFilter').on('change', function () {
        const val = $(this).val();

        table.rows().every(function () {

            let cellText = $(this.node()).find('td:eq(6)').text().replace(/[^0-9.]/g, '');
            let price = parseFloat(cellText) || 0;

            let match = false;

            if (!val) match = true;
            else if (val.includes('-')) {
                const [min, max] = val.split('-').map(v => v ? parseFloat(v) : null);

                if (max === null) match = price >= min;
                else match = price >= min && price <= max;
            }

            $(this.node()).toggle(match);
        });
    });

});
</script>
