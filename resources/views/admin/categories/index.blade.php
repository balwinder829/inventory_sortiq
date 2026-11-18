@include('admin.layouts.header')

<div class="content-wrapper">
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="page-body">
            @if($categories->isEmpty())
              <!--   <div class="empty-state text-center py-5">
                    <h2>No orders found</h2>
                    <p>Try adjusting your search or filter to find what you're looking for.</p>
                    <a href="{{ route('suppliers.create') }}" class="btn btn-primary mt-3">
                        {{ __('Add your first Order') }}
                    </a>
                </div> -->
            @else
                <div class="container-xl">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="d-flex justify-content-end align-items-center mb-3">
                    <div>
                        <label for="globalSearch" class="form-label mb-0 me-2">Search:</label>
                        <input type="text" id="globalSearch" class="form-control d-inline w-auto" placeholder="Search...">
                    </div>
                </div>



                    <table class="table table-striped table-bordered" id="orders">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Slug</th>
                                <!-- <th>Action</th> -->
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($categories as $category)
                                <tr>
                                    <td class="py-3 px-4">{{ $category->id }}</td>
                                    <td class="py-3 px-4">{{ $category->name }}</td>
                                    <td class="py-3 px-4">{{ $category->slug }}</td>
                                  <!--   <td>
                                     <a href="{{ route('categories.show',$category->slug) }}" title="View Supplier" style="color: red;">
                                        <i class="fa fa-eye"></i>&nbsp;
                                    </a></td> -->
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
    // $('#statusFilter').on('change', function () {
    //     table.column(4).search(this.value).draw();
    // });

    // Payment filter (column 4 = Payment)
    // $('#paymentFilter').on('change', function () {
    //     table.column(4).search(this.value).draw();
    // });

    // Global search
    $('#globalSearch').on('keyup', function () {
        table.search(this.value).draw();
    });
});
</script>

   
</script>
