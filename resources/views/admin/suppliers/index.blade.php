@include('admin.layouts.header')

<div class="content-wrapper">

    <div class="container-xxl flex-grow-1 container-p-y">

        <div class="page-body">

            <div class="container-xl">

                {{-- Success Message --}}
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                {{-- Top Actions --}}
                <div class="d-flex justify-content-between align-items-center mb-4">

                    <h4 class="mb-0">Suppliers List</h4>

                    <a href="{{ route('suppliers.create') }}"
                       class="btn btn-primary">
                        <i class="fa fa-plus"></i> Add Supplier
                    </a>

                </div>

                {{-- Empty State --}}
                @if($suppliers->isEmpty())

                    <div class="card shadow-sm">
                        <div class="card-body text-center py-5">

                            <h4>No Suppliers Found</h4>

                            <p class="text-muted">
                                Start by adding your first supplier.
                            </p>

                            <a href="{{ route('suppliers.create') }}"
                               class="btn btn-primary mt-2">
                                Add Supplier
                            </a>

                        </div>
                    </div>

                @else

                    {{-- Filters --}}
                    <div class="d-flex justify-content-between align-items-center mb-3">

                        <div>
                            <label class="me-2">
                                Supplier Type:

                                <select id="statusFilter"
                                        class="form-select d-inline w-auto">

                                    <option value="">All</option>

                                    @foreach($suppliers->pluck('supplier_type')->unique() as $type)

                                        @if($type)
                                            <option value="{{ $type }}">
                                                {{ $type }}
                                            </option>
                                        @endif

                                    @endforeach

                                </select>
                            </label>
                        </div>

                        <div>
                            <label>
                                Search:

                                <input type="text"
                                       id="globalSearch"
                                       class="form-control d-inline w-auto"
                                       placeholder="Search supplier...">
                            </label>
                        </div>

                    </div>

                    {{-- Table --}}
                    <div class="card shadow-sm">

                        <div class="card-body">

                            <table class="table table-striped table-bordered"
                                   id="suppliersTable">

                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Shop Name</th>
                                        <th>Type</th>
                                        <th>Phone</th>
                                        <th>Email</th>
                                        <th>Status</th>
                                        <th width="150">Action</th>
                                    </tr>
                                </thead>

                                <tbody>

                                    @foreach($suppliers as $supplier)

                                        <tr>

                                            <td>{{ $supplier->id }}</td>

                                            <td>{{ $supplier->name }}</td>

                                            <td>{{ $supplier->shop_name }}</td>

                                            <td>{{ $supplier->supplier_type }}</td>

                                            <td>{{ $supplier->phone }}</td>

                                            <td>{{ $supplier->email }}</td>

                                            <td>
                                                @if($supplier->is_active)
                                                    <span class="badge bg-success">
                                                        Active
                                                    </span>
                                                @else
                                                    <span class="badge bg-danger">
                                                        Inactive
                                                    </span>
                                                @endif
                                            </td>

                                            <td>

                                                {{-- View --}}
                                                <a href="{{ route('suppliers.show', $supplier->id) }}"
                                                   title="View Supplier"
                                                   class="text-info me-2">

                                                    <i class="fa fa-eye"></i>

                                                </a>

                                                {{-- Edit --}}
                                                <a href="{{ route('suppliers.edit', $supplier->id) }}"
                                                   title="Edit Supplier"
                                                   class="text-warning me-2">

                                                    <i class="fa fa-edit"></i>

                                                </a>

                                                {{-- Delete --}}
                                                <form action="{{ route('suppliers.destroy', $supplier->id) }}"
                                                      method="POST"
                                                      style="display:inline-block;">

                                                    @csrf
                                                    @method('DELETE')

                                                    <button type="submit"
                                                            class="btn btn-sm p-0 border-0 bg-transparent text-danger"
                                                            onclick="return confirm('Delete this supplier?')">

                                                        <i class="fa fa-trash"></i>

                                                    </button>

                                                </form>

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

    const table = $('#suppliersTable').DataTable({
        paging: true,
        searching: true,
        ordering: true,
        responsive: true,
        autoWidth: false,
        dom: 'lrtip'
    });

    // Supplier Type Filter
    $('#statusFilter').on('change', function () {
        table.column(3).search(this.value).draw();
    });

    // Global Search
    $('#globalSearch').on('keyup', function () {
        table.search(this.value).draw();
    });

});

</script>