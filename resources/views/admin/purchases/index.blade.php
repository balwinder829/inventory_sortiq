@include('admin.layouts.header')

<div class="content-wrapper">

    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">

        <div class="page-body">

            <div class="container-xl">

                {{-- Success Message --}}
                @if(session('success'))

                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>

                @endif

                {{-- Header --}}
                <div class="d-flex justify-content-between align-items-center mb-4">

                    <h3 class="mb-0">
                        Purchase List
                    </h3>

                    <a href="{{ route('purchases.create') }}"
                       class="btn btn-primary">

                        <i class="fa fa-plus"></i>
                        Add Purchase

                    </a>

                </div>

                {{-- Empty State --}}
                @if($purchases->isEmpty())

                    <div class="card shadow-sm">

                        <div class="card-body text-center py-5">

                            <h4>No Purchases Found</h4>

                            <p class="text-muted">
                                Start by creating your first purchase.
                            </p>

                            <a href="{{ route('purchases.create') }}"
                               class="btn btn-primary mt-2">

                                Add Purchase

                            </a>

                        </div>

                    </div>

                @else

                    {{-- Filters --}}
                  <div class="d-flex justify-content-between align-items-center mb-3 py-2 flex-wrap gap-2">

    <div class="d-flex gap-2 flex-wrap">

        {{-- Status Filter --}}
        <!-- <div>

            <label class="me-2">

                Status:

                <select id="statusFilter"
                        class="form-select d-inline w-auto">

                    <option value="">
                        All
                    </option>

                    <option value="Pending">
                        Pending
                    </option>

                    <option value="Approved">
                        Approved
                    </option>

                </select>

            </label>

        </div> -->

        {{-- Supplier Filter --}}
        <div>

            <label class="me-2">

                Supplier:

                <select id="supplierFilter"
                        class="form-select d-inline w-auto">

                    <option value="">
                        All
                    </option>

                    @foreach($purchases->pluck('supplier.name')->unique() as $supplier)

                        @if($supplier)

                            <option value="{{ $supplier }}">

                                {{ $supplier }}

                            </option>

                        @endif

                    @endforeach

                </select>

            </label>

        </div>

    </div>

    {{-- Search --}}
    <div>

        <label>

            Search:

            <input type="text"
                   id="globalSearch"
                   class="form-control d-inline w-auto"
                   placeholder="Search bill no, supplier...">

        </label>

    </div>

</div>

                    {{-- Purchase Table --}}
                    <div class="card shadow-sm">

                        <div class="card-body">

                            <table class="table table-striped table-bordered"
                                   id="purchasesTable">

                                <thead>

                                    <tr>

                                        <th>ID</th>

                                        <th>Bill No.</th>

                                        <th>Supplier</th>

                                        <th>Purchase Date</th>

                                        <th>Total Amount</th>

                                        <th>Status</th>

                                        <th width="120">
                                            Action
                                        </th>

                                    </tr>

                                </thead>

                                <tbody>

                                    @foreach($purchases as $purchase)

                                        <tr>

                                            <td class="py-3 px-4">
                                                {{ $purchase->id }}
                                            </td>

                                            <td class="py-3 px-4">
                                                {{ $purchase->bill_no }}
                                            </td>

                                            <td class="py-3 px-4">
                                                @if($purchase->supplier)

                                                    @if($purchase->supplier->trashed())

                                                        <span class="text-danger fw-bold">

                                                            {{ $purchase->supplier->name }}
                                                            (Deleted)

                                                        </span>

                                                    @else

                                                        {{ $purchase->supplier->name }}

                                                    @endif

                                                @else

                                                    <span class="text-danger">

                                                        Supplier Missing

                                                    </span>

                                                @endif
                                            </td>

                                            <td class="py-3 px-4">

                                                {{ \Carbon\Carbon::parse($purchase->purchase_date)->format('d M Y') }}

                                            </td>

                                            <td class="py-3 px-4">

                                                ₹{{ number_format($purchase->total_amount, 2) }}

                                            </td>

                                            <td class="py-3 px-4">

                                                @if($purchase->status == 1)

                                                    <span class="badge bg-success">
                                                        Approved
                                                    </span>

                                                @else

                                                    <span class="badge bg-warning">
                                                        Pending
                                                    </span>

                                                @endif

                                            </td>

                                            <td class="py-3 px-4">

                                                {{-- View --}}
                                                <a href="{{ route('purchases.show', $purchase->id) }}"
                                                   title="View Purchase"
                                                   class="text-info me-2">

                                                    <i class="fa fa-eye"></i>

                                                </a>

                                                {{-- Delete --}}
                                               <!--  <form action="{{ route('purchases.destroy', $purchase->id) }}"
                                                      method="POST"
                                                      style="display:inline-block;">

                                                    @csrf
                                                    @method('DELETE')

                                                    <button type="submit"
                                                            class="btn btn-sm p-0 border-0 bg-transparent text-danger"
                                                            onclick="return confirm('Delete this purchase?')">

                                                        <i class="fa fa-trash"></i>

                                                    </button>

                                                </form> -->

                                            </td>

                                        </tr>

                                    @endforeach

                                </tbody>

                            </table>

                        </div>

                    </div>

                @endif

            </div>

        </div>

    </div>

</div>

{{-- Styles --}}
<link
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
    rel="stylesheet"
/>

<link rel="stylesheet"
      href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

@include('admin.layouts.footer')

{{-- Scripts --}}
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>

$(document).ready(function () {

    const table = $('#purchasesTable').DataTable({
        paging: true,
        searching: true,
        ordering: true,
        responsive: true,
        autoWidth: false,
        dom: 'lrtip'
    });

    // Status Filter
    // $('#statusFilter').on('change', function () {

    //     table.column(5).search(this.value).draw();

    // });

    // Supplier Filter
    $('#supplierFilter').on('change', function () {

        table.column(2).search(this.value).draw();

    });
    // Global Search
    $('#globalSearch').on('keyup', function () {

        table.search(this.value).draw();

    });

});

</script>