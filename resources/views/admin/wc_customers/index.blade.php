@include('admin.layouts.header')

<div class="content-wrapper">
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="page-body">
            @if($paginator->isEmpty())
                <div class="empty-state text-center py-5">
                    <h2>No Data found</h2>
                    
                </div>
            @else
                <div class="container-xl">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- <div class="d-flex justify-content-end mb-3">
                        <input type="text" id="globalSearch" class="form-control w-25" placeholder="Search...">
                    </div> -->


<h2 class="mb-4">{{ __('Customers') }}</h2>


                    <table class="table table-striped table-bordered" id="order">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Email</th>
                                <th>Created Date</th>
                                <th>Action</th>
                                
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($paginator as $data)
                                <tr>
                                    <td class="py-3 px-4">{{ $data->id }}</td>
                                    <td class="py-3 px-4">{{ $data->first_name }}</td>
                                    <td class="py-3 px-4">{{ $data->last_name }}</td>
                                    <td class="py-3 px-4">{{ $data->email }}</td>
                                   <td class="py-3 px-4">
                                    {{ $data->date_created ? \Carbon\Carbon::parse($data->date_created)->format('d-m-Y') : '-' }}
                                </td>
                                <td>
                                    <a href="{{ route('wc.customer.show',$data->id) }}" title="View" style="color: red;">
                                        <i class="fa fa-eye"></i>&nbsp;
                                    </a>
                                </td> 
                                    
                                     
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                     <div class="mt-4">
         
                        {{ $paginator->links('pagination::bootstrap-5') }}
                     
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
