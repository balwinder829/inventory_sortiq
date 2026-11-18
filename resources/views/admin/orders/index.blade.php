@include('admin.layouts.header')

<div class="content-wrapper">
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="page-body">
            @if($orders->isEmpty())
                <div class="empty-state text-center py-5">
                    <h2>No orders found</h2>
                    <p>Try adjusting your search or filter to find what you're looking for.</p>
                    <a href="{{ route('orders.create') }}" class="btn btn-primary mt-3">
                        {{ __('Add your first Order') }}
                    </a>
                </div>
            @else
                <div class="container-xl">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <label class="me-2">Order Status:
                                <select id="statusFilter" class="form-select d-inline w-auto">
                                    <option value="">All</option>
                                    @foreach($orders->pluck('order_status')->unique() as $status)
                                        <option value="{{ $status->label() }}">{{ $status->label() }}</option>
                                    @endforeach
                                </select>
                            </label>
                        </div>

                        <div>
                            <label class="me-2">Payment:
                                <select id="paymentFilter" class="form-select d-inline w-auto">
                                    <option value="">All</option>
                                    @foreach($orders->pluck('payment_type')->unique() as $payment)
                                        <option value="{{ $payment }}">{{ ucfirst($payment) }}</option>
                                    @endforeach
                                </select>
                            </label>
                        </div>

                        <div>
                            <label>Search:
                                <input type="text" id="globalSearch" class="form-control d-inline w-auto" placeholder="Search orders...">
                            </label>
                        </div>
                    </div>


                    <table class="table table-striped table-bordered" id="orders">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Invoice No.</th>
                                <th>Customer</th>
                                <th>Date</th>
                                <th>Payment</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                                <tr>
                                    <td class="py-3 px-4">{{ $order->id }}</td>
                                    <td class="py-3 px-4">{{ $order->invoice_no }}</td>
                                    <td class="py-3 px-4">{{ $order->customer->name ?? 'N/A' }}</td>
                                    <td class="py-3 px-4">{{ $order->created_at->format('Y-m-d') }}</td>
                                    
                                    <td class="py-3 px-4">{{ ucfirst($order->payment_type) }}</td>
                                    <td class="py-3 px-4">@currency($order->total, 2)</td>
                                    <td class="py-3 px-4">{{ $order->order_status->label() }}</td>
                                    <td>
                                    <a href="{{ route('orders.show',$order->id) }}" title="View Order" style="color: red;">
                                        <i class="fa fa-eye"></i>&nbsp;
                                    </a>
                                    <a href="{{ route('order.downloadInvoice',$order->id) }}" title="Download order" target="_blank" style="color: red;">
                                        <i class="fa fa-print"></i>&nbsp;
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
<link
  href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
  rel="stylesheet"
/>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
@include('admin.layouts.footer')
{{-- Before closing </body> --}}
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
        dom: 'lrtip'
    });

    // Status filter (column 6 = Status)
    $('#statusFilter').on('change', function () {
        table.column(6).search(this.value).draw();
    });

    // Payment filter (column 4 = Payment)
    $('#paymentFilter').on('change', function () {
        table.column(4).search(this.value).draw();
    });

    // Global search
    $('#globalSearch').on('keyup', function () {
        table.search(this.value).draw();
    });
});
</script>

   
</script>
