
@include('admin.layouts.header')
          <!-- Content wrapper -->
          <div class="content-wrapper">
            <div class="container-xxl flex-grow-1 container-p-y">
           <div class="container">
            @if(auth()->check() && auth()->user()->is_superadmin)
              <h4 class="mb-2">Store List</h4>
            @else
              <h4 class="mb-2">My Stores</h4>
            @endif
            
    <table id="store-table" class="display">
        <thead>
            <tr>
                <th>Name</th>
                <th>Assigned To</th>
                <th>Created At</th>
                <th>Action</th>     
            </tr>
        </thead>
        <tbody>
            @foreach($stores as $store)
                <tr>
                    <td>{{ $store->name }}</td>
                     <td>{{ optional($store->user)->name }}</td>
                    <td>{{ $store->created_at->format('Y-m-d H:i') }}</td>
                      <td>
                        <a href="{{ route('stores.show', $store->id) }}" title="View Store" style="color: red;">
                            <i class="fa fa-eye"></i>&nbsp;
                        </a>
                        @if(auth()->check() && auth()->user()->is_superadmin)
                         <a href="javascript:void(0);" class="delete-btn" data-id="{{ $store->id }}" title="Delete Store" style="color: red;">
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

            

            <div class="content-backdrop fade"></div>
          </div>
          <!-- Content wrapper -->
        </div>
        <!-- / Layout page -->
      </div>

      <!-- Overlay -->
    
    </div>
    <!-- / Layout wrapper -->

    <!-- <div class="buy-now">
      <a
        href="https://themeselection.com/products/sneat-bootstrap-html-admin-template/"
        target="_blank"
        class="btn btn-danger btn-buy-now"
        >Upgrade to Pro</a
      >
    </div> -->

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <link
  href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
  rel="stylesheet"
/>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>

    <script src="{{ asset('assets/vendor/js/menu.js') }}"></script>
    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src="{{ asset('assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>

    <!-- Main JS -->
    <script src="{{ asset('assets/js/main.js') }}"></script>

    <!-- Page JS -->
    <script src="{{ asset('assets/js/dashboards-analytics.js') }}"></script>

    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script>
$(document).ready(function () {
//     $('#store-table').DataTable({
//         // ajax: '/stores/list',
//         columns: [
//             { data: 'name' },
//             { data: 'users' },
//             { data: 'created_at' }
//         ]
//     });
// });
// </script>
<script>
    $(document).ready(function () {
        $('#store-table').DataTable();

          // Delete button click handler
        $('.delete-btn').on('click', function() {
            if (!confirm('Are you sure you want to delete this store?')) {
                return;
            }
            var storeId = $(this).data('id');

            $.ajax({
                url: '/admin/stores/' + storeId,
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    //alert('Store deleted successfully');
                    location.reload(); // reload to reflect changes
                },
                error: function(xhr) {
                    //alert('Failed to delete store');
                }
            });
        });

    });
</script>
  </body>
</html>
