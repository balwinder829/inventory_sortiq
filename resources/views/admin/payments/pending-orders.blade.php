@include('admin.layouts.header')

<div class="content-wrapper">
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">

<div class="page-body">
    

    @if($orders->isEmpty())
    <div class="empty">
        <div class="empty-icon">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <circle cx="12" cy="12" r="9" />
                <line x1="9" y1="10" x2="9.01" y2="10" />
                <line x1="15" y1="10" x2="15.01" y2="10" />
                <path d="M9.5 15.25a3.5 3.5 0 0 1 5 0" />
            </svg>
        </div>
        <p class="empty-title">No orders found</p>
        <p class="empty-subtitle text-secondary">
            Try adjusting your search or filter to find what you're looking for.
        </p>
        <div class="empty-action">
            <a href="{{ route('orders.create') }}" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M12 5l0 14"></path><path d="M5 12l14 0"></path></svg>
                Add your first Order
            </a>
        </div>
    </div>
    @else
    <div class="container-xl">
        <!-- <div class="card"> -->
            <div class="card-header">
                <div>
                    <h3 class="card-title">
                        {{ __('Orders: Pending') }}
                    </h3>
                </div>

            <!--     <div class="card-actions">
                    <a href="{{ route('orders.create') }}" class="btn btn-icon btn-outline-success">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-plus" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>
                    </a>
                </div> -->
            </div>
                 <div class="d-flex justify-content-end mb-3">
    <div>
        <label class="form-label me-2 mb-0">Search:</label>
        <input type="text" id="globalSearch" class="form-control d-inline-block w-auto" placeholder="Search orders...">
    </div>
</div>
            <div class="table-responsive">
                <table class="table table-bordered card-table table-vcenter text-nowrap datatable" id="pending_orders">
                    <thead class="thead-light">
                    <tr>
                        <th scope="col" class="text-center">{{ __('No.') }}</th>
                        <th scope="col" class="text-center">{{ __('Invoice No.') }}</th>
                        <th scope="col" class="text-center">{{ __('Customer') }}</th>
                        <th scope="col" class="text-center">{{ __('Date') }}</th>
                        <th scope="col" class="text-center">{{ __('Payment') }}</th>
                        <th scope="col" class="text-center">{{ __('Total') }}</th>
                        <th scope="col" class="text-center">{{ __('Status') }}</th>
                        <th scope="col" class="text-center">{{ __('Actions') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($orders as $order)
                        <tr>
                            <td class="text-center">
                                {{ $loop->iteration }}
                            </td>
                            <td class="text-center py-3 px-4">{{ $order->invoice_no }}</td>
                            <td class="text-center py-3 px-4">{{ $order->customer->name }}</td>
                            <td class="text-center py-3 px-4">{{ $order->order_date->format('d-m-Y') }}</td>
                            <td class="text-center py-3 px-4">{{ $order->payment_type }}</td>
                            <td class="text-center py-3 px-4">{{ Number::currency($order->total, 'USD') }}</td>
                            <td class="text-center py-3 px-4">
                                <!-- <span class="badge bg-orange text-white text-uppercase"> -->
                                    {{ \App\Enums\OrderStatus::PENDING->label() }}
                                <!-- </span> -->
                            </td>
                            <td class="text-center py-3 px-4">
                                <a href="{{ route('orders.show', $order) }}" title="View Order" style="color: #566A7F;">
                            <i class="fa fa-eye"></i>&nbsp;
                        </a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                {{--- ---}}
            <!-- </div> -->
        </div>
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
    const table = $('#pending_orders').DataTable({
        paging: true,
        searching: true,
        ordering: true,
        responsive: true,
        autoWidth: false,
        dom: 'lrtip'
    });

    // // Status filter (column 6 = Status)
    // $('#statusFilter').on('change', function () {
    //     table.column(6).search(this.value).draw();
    // });

    // // Payment filter (column 4 = Payment)
    // $('#paymentFilter').on('change', function () {
    //     table.column(4).search(this.value).draw();
    // });

    // Global search
    $('#globalSearch').on('keyup', function () {
        table.search(this.value).draw();
    });
});
</script>