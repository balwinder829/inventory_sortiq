@include('admin.layouts.header')

<div class="content-wrapper">
    <!-- Content -->
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
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- 🔹 Filter Panel -->
                    <div class="card mb-4 shadow-sm border-0">
                        <div class="card-body">
                            <div class="row g-3 align-items-end">

                                <!-- Store Filter -->
                                <div class="col-md-3 col-sm-6">
                                    <label for="storeFilter" class="form-label fw-semibold">Store</label>
                                    <select id="storeFilter" class="form-select">
                                        <option value="">All Stores</option>
                                        @foreach($products->pluck('store.name')->unique() as $store)
                                            @if($store)
                                                <option value="{{ $store }}">{{ $store }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Quantity Filter -->
                                <div class="col-md-3 col-sm-6">
                                    <label for="quantityFilter" class="form-label fw-semibold">Quantity</label>
                                    <select id="quantityFilter" class="form-select">
                                        <option value="">All</option>
                                        <option value="0">Out of Stock</option>
                                        <option value="1-10">Low (1–10)</option>
                                        <option value="11-50">Medium (11–50)</option>
                                        <option value="50-">High (50+)</option>
                                    </select>
                                </div>

                                <!-- Global Search -->
                                <div class="col-md-4 col-sm-6">
                                    <label for="globalSearch" class="form-label fw-semibold">Search Products</label>
                                    <input type="text" id="globalSearch" class="form-control" placeholder="Search by name, SKU...">
                                </div>

                                <!-- Import Button -->
                                <div class="col-md-2 col-sm-6 text-md-end text-sm-start">
                                    <label class="form-label d-block fw-semibold invisible">Action</label>
                                    <a href="{{ route('item.import_page') }}" class="btn btn-primary w-100">
                                        <i class="fa fa-upload me-2"></i> Import
                                    </a>
                                </div>

                            </div>
                        </div>
                    </div>

                    <!-- 🔹 Products Table -->
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
                               <tr>
                                    <td class="py-3 px-4">{{ $product->id }}</td>
                                    <td class="py-3 px-4">{{ ucfirst(optional($product->store)->name ?? '') }}</td>
                                    <td class="py-3 px-4">{{ ucfirst($product->name) }}</td>
                                    <td class="py-3 px-4">{{ $product->sku }}</td>
                                    <td class="py-3 px-4">{{ ucfirst($product->category ?? '') }}</td>
                                    <td class="py-3 px-4">{{ $product->quantity }}</td>
                                    <td class="py-3 px-4">@currency($product->price)</td>
                                    <td class="py-3 px-4 text-center">
                                        <a href="{{ route('stocks.edit', $product->id) }}" title="Edit Stock" style="color: red;">
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

<!-- 🔹 Styles -->
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

<!-- 🔹 Scripts -->
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

    // ✅ Global search
    $('#globalSearch').on('keyup', function () {
        table.search(this.value).draw();
    });

    // ✅ Store filter
    $('#storeFilter').on('change', function () {
        const val = $(this).val();
        table.column(1).search(val).draw(); // column 1 = Store
    });

    // ✅ Quantity filter
    $('#quantityFilter').on('change', function () {
        const val = $(this).val();
        table.rows().every(function () {
            const qtyText = this.data()[5]; // column 5 = Quantity
            const qty = parseInt(qtyText);
            let match = false;

            if (!val) {
                match = true; // Show all
            } else if (val === '0') {
                match = qty === 0;
            } else if (val.includes('-')) {
                const [min, max] = val.split('-').map(v => v ? parseInt(v) : null);
                if (max === null) match = qty >= min;
                else match = qty >= min && qty <= max;
            }

            $(this.node()).toggle(match);
        });
    });
});
</script>
