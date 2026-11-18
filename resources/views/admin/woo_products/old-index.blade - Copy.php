@include('admin.layouts.header')

 <div class="content-wrapper">
            <!-- Content -->

            <div class="container-xxl flex-grow-1 container-p-y">
<div class="page-body">
   @extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Products</h2>

    <div class="row">
        @foreach ($paginator as $product)
            <div class="col-md-4 mb-3">
                <div class="card h-100">
                    @if(!empty($product->images))
                        <img src="{{ $product->images[0]->src }}" 
                             class="card-img-top" 
                             alt="{{ $product->name }}">
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">{{ $product->name }}</h5>
                        <p class="card-text">{!! $product->short_description !!}</p>
                        <p><strong>Price:</strong> {{ $product->price }} {{ $product->currency ?? 'USD' }}</p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-4">
        {{ $paginator->links() }}
    </div>
</div>
@endsection

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
// $(document).ready(function () {
//     const table = $('#products').DataTable({
//         paging: true,
//         searching: true,  // this enables internal search but we will override it below
//         ordering: true,
//         responsive: true,
//         autoWidth: false,

//         // Disable built-in search input (optional, if you want to hide it)
//         dom: 'lrtip' // l - length changing input, r - processing, t - table, i - info, p - pagination. Notice 'f' (filter) is missing so no built-in search box.
//     });

//     // Category filter
//     $('#categoryFilter').on('change', function () {
//         const val = $(this).val();
//         table.column(3).search(val).draw();  // filter category column
//     });

//     // External global search
//     $('#globalSearch').on('keyup', function () {
//         table.search(this.value).draw();
//     });

//      // Price filter
//     $('#priceFilter').on('change', function () {
//         const val = $(this).val();

//         table.column(5).search('', true, false); // clear previous search

//         if (val) {
//             table.rows().every(function () {
//                 const priceText = this.data()[5].replace('$', '');
//                 const price = parseFloat(priceText);

//                 let match = false;
//                 if (val.includes('-')) {
//                     const [min, max] = val.split('-').map(v => v ? parseFloat(v) : null);
//                     if (max === null) {
//                         match = price >= min;
//                     } else {
//                         match = price >= min && price <= max;
//                     }
//                 }
//                 if (match) {
//                     $(this.node()).show();
//                 } else {
//                     $(this.node()).hide();
//                 }
//             });
//         } else {
//             table.rows().every(function () {
//                 $(this.node()).show();
//             });
//         }
//     });

// });
</script>


