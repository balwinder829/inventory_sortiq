@include('admin.layouts.header')
          <!-- Content wrapper -->
          <div class="content-wrapper">
            <div class="container-xxl flex-grow-1 container-p-y">
           <div class="container">
           
             <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
    <h4 class="mb-2" style="margin: 0;">Store: {{ $store->name }}</h4>

    <div style="text-align: right;">
        <h4 class="mb-2" style="margin: 0;">Assigned To: {{ optional($store->user)->name ? ucfirst(optional($store->user)->name) : 'N/A' }}
</h4>
        <a href="{{ route('items.create', ['store_id' => $store->id]) }}" 
           style="display: inline-block; margin-top: 0.5rem; padding: 0.4rem 0.8rem; background: #007bff; color: white; border-radius: 4px; text-decoration: none;">
           Add Item
        </a>
    </div>
</div>
            @if($store->items->isEmpty())
                <p>No items assigned to this store.</p>
            @else
    <table id="item-table" class="display">
        <thead>
            <tr>
                <th>Item Name</th>
                <th>Category</th>
                <th>Price</th>
                <th>Dis. Price</th>
                <th>Quantity</th>
                <th>Brand</th>
                
               
                  <th>Action</th>
               
            </tr>
        </thead>
        <tbody>

           
            @foreach($store->items as $item)
                <tr>
                    <td>{{ $item->name }}</td>
                     <td>{{ $item->category }}</td>
                     <td>{{ $item->price }}</td>
                     <td>{{ $item->sale_price }}</td>
                     <td>{{ $item->quantity }}</td>
                     <td>{{ $item->brand }}</td>
                      <td>
                        <a href="{{ route('items.edit', $item->id) }}" title="Edit Item" style="color: red;">
                            <i class="fa fa-edit"></i>&nbsp;
                        </a>

                         <a href="javascript:void(0);" class="delete-btn" data-id="{{ $item->id }}" title="Delete Item" style="color: red;">
                            <i class="fa fa-trash"></i>
                        </a>
                    </td>
                </tr>
            @endforeach

        </tbody>
    </table>
     @endif
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
        $('#item-table').DataTable();

          // Delete button click handler
        $('.delete-btn').on('click', function() {
            if (!confirm('Are you sure you want to delete this Item?')) {
                return;
            }
            var itemId = $(this).data('id');

            $.ajax({
                url: '/admin/items/' + itemId,
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
