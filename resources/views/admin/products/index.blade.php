@include('admin.layouts.header')

<div class="content-wrapper">
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="page-body">

            @if($products->isEmpty())
                <div class="empty-state text-center py-5">
                    <h2>No products found</h2>
                    <p>Try adjusting your search or filter to find what you're looking for.</p>
                    <!-- <a href="{{ route('products.create') }}" class="btn btn-primary mt-3">
                        {{ __('Add your first Product') }}
                    </a> -->
                </div>
            @else
                <div class="container container-xl">

                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- ==========================
                         FILTERS + ACTIONS SECTION
                    =========================== -->
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-body">
                            <div class="row g-3 align-items-end">
                                
                                <!-- Search -->
                                <div class="col-md-3">
                                    <label for="globalSearch" class="form-label fw-semibold">
                                        <i class="fa fa-search me-1 text-secondary"></i> Search
                                    </label>
                                    <input type="text" id="globalSearch" class="form-control" placeholder="Search products...">
                                </div>

                                <!-- Price Filter -->
                                <div class="col-md-2">
                                    <label for="priceFilter" class="form-label fw-semibold">
                                        <i class="fa fa-dollar-sign me-1 text-success"></i> Price
                                    </label>
                                    <select id="priceFilter" class="form-select">
                                        <option value="">All</option>
                                        <option value="0-50">Under $50</option>
                                        <option value="50-100">$50 - $100</option>
                                        <option value="100-500">$100 - $500</option>
                                        <option value="500-">$500+</option>
                                    </select>
                                </div>

                                <!-- Store Filter -->
                                <div class="col-md-3">
                                    <label for="storeFilter" class="form-label fw-semibold">
                                        <i class="fa fa-store me-1 text-primary"></i> Store
                                    </label>
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
                                <div class="col-md-2">
                                    <label for="quantityFilter" class="form-label fw-semibold">
                                        <i class="fa fa-boxes-stacked me-1 text-warning"></i> Quantity
                                    </label>
                                    <select id="quantityFilter" class="form-select">
                                        <option value="">All</option>
                                        <option value="0">Out of Stock</option>
                                        <option value="1-10">Low (1–10)</option>
                                        <option value="11-50">Medium (11–50)</option>
                                        <option value="50-">High (50+)</option>
                                    </select>
                                </div>

                                <!-- Import Button -->
                               <!--  <div class="col-md-2 text-md-end text-center">
                                    <a href="{{ route('item.import_page') }}" class="btn btn-primary w-100">
                                        <i class="fa fa-upload me-1"></i> Import
                                    </a>
                                </div> -->
                                 <div class="col-md-2 text-md-end text-center">
                                    <a href="{{ route('purchases.create') }}" class="btn btn-primary w-100">
                                        <i class="fa fa-upload me-1"></i> Add Purchase
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ==========================
                         PRODUCTS TABLE
                    =========================== -->
                    <table class="table table-bordered table-striped align-middle" id="products">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Store Name</th>
                                <th>Name</th>
                                <th>SKU</th>
                                <th>Category</th>
                                <th>Quantity</th>
                                <th>Selling Price</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($products as $product)
                                <tr>
                                    <td class="py-3 px-4">{{ $product->id }}</td>
                                    <td class="py-3 px-4">{{ optional($product->store)->name }}</td>
                                    <td class="py-3 px-4">{{ $product->name }}</td>
                                    <td class="py-3 px-4">{{ $product->sku }}</td>
                                    <td class="py-3 px-4">{{ $product->category ?? '' }}</td>
                                    <td class="py-3 px-4">{{ $product->quantity }}</td>
                                    <td class="py-3 px-4">@currency($product->price)</td>
                                    <td class="py-3 px-4 text-center">
                                        <a href="{{ route('products.show', $product->id) }}" class="text-info me-2" title="View Product">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                        <a href="{{ route('items.edit', $product->id) }}" class="text-warning" title="Edit Product">
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

<!-- ========== STYLES ========== -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet"/>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

<style>
.card-body label.form-label {
    font-size: 0.9rem;
    color: #444;
}
.card-body .form-select,
.card-body .form-control {
    border-radius: 0.5rem;
    border-color: #ddd;
    box-shadow: none;
    transition: 0.2s ease;
}
.card-body .form-select:focus,
.card-body .form-control:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.15rem rgba(13,110,253,.15);
}
.btn-primary {
    border-radius: 0.5rem;
    font-weight: 500;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}
.table thead th {
    font-weight: 600;
    color: #333;
}
.table-bordered td, .table-bordered th {
    vertical-align: middle;
}
</style>

@include('admin.layouts.footer')

<!-- ========== SCRIPTS ========== -->
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
        pageLength: 50 
    });

    // 🔍 Global Search
    $('#globalSearch').on('keyup', function () {
        table.search(this.value).draw();
    });

    // 💲 Price Filter
    $('#priceFilter').on('change', function () {
        const val = $(this).val();
        table.rows().every(function () {
            const priceText = this.data()[6].replace('$', '');
            const price = parseFloat(priceText);
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

    // 🏪 Store Filter
    $('#storeFilter').on('change', function () {
        const val = $(this).val();
        table.column(1).search(val).draw(); // Store Name column
    });

    // 📦 Quantity Filter
    $('#quantityFilter').on('change', function () {
        const val = $(this).val();
        table.rows().every(function () {
            const qty = parseInt(this.data()[5]);
            let match = false;

            if (!val) match = true;
            else if (val === '0') match = qty === 0;
            else if (val.includes('-')) {
                const [min, max] = val.split('-').map(v => v ? parseInt(v) : null);
                if (max === null) match = qty >= min;
                else match = qty >= min && qty <= max;
            }

            $(this.node()).toggle(match);
        });
    });
});
</script>
