@include('admin.layouts.header')

<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">

        <div class="container">

            <!-- PAGE HEADER -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="fw-bold mb-0">
                    @if(auth()->check() && auth()->user()->is_superadmin)
                        <i class="fa-solid fa-store me-2 text-primary"></i> Store List
                    @else
                        <i class="fa-solid fa-store me-2 text-primary"></i> My Stores
                    @endif
                </h3>

                @if(auth()->check() && auth()->user()->is_superadmin)
                <a href="{{ route('stores.create') }}" class="btn btn-primary">
                    <i class="fa fa-plus me-2"></i>Add Store
                </a>
                @endif
            </div>

            <!-- CARD WRAPPER -->
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-body p-3 p-md-4">

                    <table id="store-table" class="table table-striped table-bordered align-middle" width="100%">
                        <thead class="table-light">
                            <tr>
                                <th>Name</th>
                                <th>Store ID</th>
                                <th>Assigned To</th>
                                <th>Created At</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($stores as $store)
                                <tr>
                                    <td class="fw-semibold">{{ ucfirst($store->name) }}</td>
                                    <td class="fw-semibold">{{ ucfirst($store->id) }}</td>

                                    <td>
                                        <span class="badge bg-info text-dark px-3 py-2">
                                            {{ optional($store->user)->name ?? 'Unassigned' }}
                                        </span>
                                    </td>

                                    <td>{{ $store->created_at->format('Y-m-d H:i') }}</td>

                                    <td class="text-center">
                                        <a href="{{ route('stores.show', $store->id) }}" 
                                           class="text-primary fs-5 me-3" 
                                           title="View Store">
                                            <i class="fa fa-eye"></i>
                                        </a>

                                        @if(auth()->check() && auth()->user()->is_superadmin)
                                            <a href="javascript:void(0);" 
                                               class="text-danger fs-5 delete-btn" 
                                               data-id="{{ $store->id }}" 
                                               title="Delete Store">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>

        </div>
    </div>
</div>

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet"/>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

<!-- Custom UI Enhancements -->
<style>
    .table th { font-weight: 600; }
    .hover-row:hover { background: #f6f6f6 !important; }
    #store-table_filter input {
        width: 230px;
        padding: 6px 10px;
        border-radius: 4px;
    }
    #store-table_length select {
        border-radius: 4px;
        padding: 6px;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        padding: 4px 10px !important;
        margin-top: 10px !important;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background: #556ee6 !important;
        color: white !important;
        border-radius: 4px;
        border: none !important;
    }
</style>

@include('admin.layouts.footer')

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>
$(document).ready(function () {

    $('#store-table').DataTable({
        pageLength: 25,
        order: [[2, "desc"]],
    });

    // Delete Action
    $('.delete-btn').on('click', function() {
        if (!confirm('Are you sure you want to delete this store?')) {
            return;
        }

        var storeId = $(this).data('id');

        $.ajax({
            url: '/admin/stores/' + storeId,
            type: 'DELETE',
            data: { _token: '{{ csrf_token() }}' },
            success: function() {
                location.reload();
            },
            error: function() {
                alert('Failed to delete store');
            }
        });
    });

});
</script>
