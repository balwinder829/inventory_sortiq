@include('admin.layouts.header')

<div class="content-wrapper">
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="page-body">

            {{-- Success Message --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if($orders->isEmpty())
                <div class="empty-state text-center py-5">
                    <h2>No Invoice found</h2>
                    <a href="{{ route('invoices.create') }}" class="btn btn-primary mt-3">
                        {{ __('Add your first Invoice') }}
                    </a>
                </div>
            @else
                <div class="container-xl">

                  {{-- Filters above table --}}
{{-- Filters and Add Invoice button --}}
<div class="mb-3 d-flex justify-content-between align-items-center">
    <div class="d-flex align-items-center gap-2">
        <label class="mb-0 fw-semibold">Product:</label>
        <select id="productFilter" class="form-select" style="min-width: 200px;">
            <option value="">All</option>
            @foreach($orders->pluck('product')->filter()->unique('id') as $product)
                <option value="{{ $product->name }}">{{ $product->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="d-flex align-items-center gap-2">
        <label class="mb-0 fw-semibold">Search:</label>
        <input type="text" id="globalSearch" class="form-control" placeholder="Search orders..." style="min-width: 200px;">
    </div>

    <div>
        <a href="{{ route('invoices.create') }}" class="btn btn-primary">
            <i class="fa fa-plus"></i> Add Invoice
        </a>
    </div>
</div>



                    {{-- Orders Table --}}
                    <table class="table table-striped table-bordered" id="orders">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Invoice No.</th>
                                <th>Product</th>
                                <th>Customer</th>
                                <th>Date</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Total</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                                <tr>
                                    <td class="py-3 px-4">{{ $order->id }}</td>
                                    <td class="py-3 px-4">{{ $order->invoice_no }}</td>
                                     <td class="py-3 px-4">{{ $order->product->name ?? 'N/A' }}</td>
                                    <td class="py-3 px-4">{{ $order->customer_name ?? 'N/A' }}</td>
                                    <td class="py-3 px-4">{{ $order->invoice_date ? date('Y-m-d', strtotime($order->invoice_date)) : '' }}</td>
                                    <td class="py-3 px-4">₹{{ is_numeric($order->price) ? number_format($order->price, 2) : '0.00' }}</td>
                                    <td class="py-3 px-4">{{ is_numeric($order->quantity) ? $order->quantity : '0' }}</td>
                                    <td class="py-3 px-4">
                                        ₹{{ (is_numeric($order->price) && is_numeric($order->quantity)) 
                                            ? number_format($order->price * $order->quantity, 2) 
                                            : '0.00' }}
                                    </td>
                                    <td>
                                        <a href="{{ route('invoices.show',$order->id) }}" title="View Invoice" style="color: red;">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                        <a href="{{ route('invoices.downloadInvoice',$order->id) }}" title="Download order" target="_blank" style="color: red;">
                                            <i class="fa fa-print"></i>
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

@include('admin.layouts.footer')

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>
$(document).ready(function () {
    const table = $('#orders').DataTable({
        paging: true,
        searching: true,
        ordering: true,
        responsive: true,
        autoWidth: false,
        pageLength: 25,
        dom: 'lrtip'
    });

    // Product filter (column 2 = Customer/Product depends on table)
    $('#productFilter').on('change', function () {
        table.column(2).search(this.value).draw(); // adjust column index if needed
    });

    // Global search
    $('#globalSearch').on('keyup', function () {
        table.search(this.value).draw();
    });
});
</script>
