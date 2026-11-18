@include('admin.layouts.header')
<style>
    .pagination-wrapper svg {
        width: 14px!important;   /* adjust to your liking */
        height: 14px!important;
    }
    .mt-4 svg {
         width: 14px!important;   /* adjust to your liking */
        height: 14px!important;
    }
</style>
 <div class="content-wrapper">
            <!-- Content -->

            <div class="container-xxl flex-grow-1 container-p-y">
<div class="page-body">
    @if($paginator->isEmpty())
        <div class="empty-state text-center py-5">
            <h2>No products found</h2>
            <p>Try adjusting your search or filter to find what you're looking for.</p>
            <a href="{{ route('products.create') }}" class="btn btn-primary mt-3">
                {{ __('Add your first Product') }}
            </a>
        </div>
    @else
        <div class="container container-xl">
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
           <div class="d-flex justify-content-end align-items-end mb-3">
    <div>
         
    </div>
    <div class="d-flex gap-2 justify-content-end flex-wrap">
                            <!-- Import Form -->
                            

                            <!-- Optional Export button -->
                           
                             <a href="{{ route('wc.product.create') }}" class="btn btn-primary">
                                <i class="fa fa-upload"></i> Add Product
                            </a>

                             <a href="{{ route('syncAll') }}" class="btn btn-primary">
                                <i class="fa fa-download"></i> Sync All Products
                            </a>
                        </div>
     <div>
        <!-- <label class="me-2">Price:
            <select id="priceFilter" class="form-select d-inline w-auto">
                <option value="">All</option>
                <option value="0-50">Under $50</option>
                <option value="50-100">$50 - $100</option>
                <option value="100-500">$100 - $500</option>
                <option value="500-">$500+</option>
            </select>
        </label> -->
    </div>

    <div>
       <!--  <label>Search:
            <input type="text" id="globalSearch" class="form-control d-inline w-auto" placeholder="Search products...">
        </label> -->
    </div>
</div>


            <!-- Example static table (replace with real data if needed) -->
            <table class="table table-bordered table-striped" id="products2">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Permalink</th>
                        <th>Price</th>
                        <th>Category</th>
                        <th>Stock Status</th>
                        <th>Status</th>
                        <th>Selling Price</th>
                        <th>Action</th>
                    </tr>
                </thead>
             
               <tbody>
    @foreach($paginator as $product)
        <tr>
            <td class="py-3 px-4">{{ $product->id ?? '-' }}</td>
            <td class="py-3 px-4">{!! $product->name ?? 'Unnamed' !!}</td>
            <td class="py-3 px-4"><a href="{{ $product->permalink}}" target="_blank" /> {{ $product->permalink ?? 'N/A' }}</a></td>
            
            <td class="py-3 px-4">
                {!! $product->price ? format_currency($product->price) : '-' !!}
            </td>
            <td class="py-3 px-4">{{ $product->categories[0]->name ?? '-' }}</td>
            <td class="py-3 px-4">{{ $product->stock_status ?? '—' }}</td>
            <td class="py-3 px-4">{{ $product->status ?? '' }}</td>
            <td class="py-3 px-4">
                {!! $product->sale_price ? format_currency($product->sale_price) : '-' !!}
            </td>
           <td class="py-3 px-4">
                <a href="{{ $product->id ? route('wc.product.show', $product->id) : '#' }}" 
                   title="View" style="color: red;">
                    <i class="fa fa-eye"></i>&nbsp;
                </a>
                <a href="{{ $product->id ? route('wc.product.edit', $product->id) : '#' }}" 
                   title="Edit" style="color: red;">
                    <i class="fa fa-edit"></i>&nbsp;
                </a>

                @if($product->id)
                <form action="{{ route('wc.product.delete', $product->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this product?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" title="Delete" style="background:none; border:none; padding:0; color:red; cursor:pointer;">
                        <i class="fa fa-trash"></i>&nbsp;
                    </button>
                </form>
                @else
                    <span style="color: grey;"><i class="fa fa-trash"></i>&nbsp;</span>
                @endif
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
@include('admin.layouts.footer')
{{-- Before closing </body> --}}
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>




