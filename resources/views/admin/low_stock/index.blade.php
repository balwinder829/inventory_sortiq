@include('admin.layouts.header')

 
<div class="container mt-4">
  <div class="card shadow-sm">
    <div class="card-header text-dark">
      <h5 class="mb-0">Low Stock Products</h5>
    </div>
    <div class="card-body">
      @if($products->count())
      <div class="table-responsive">
        <table class="table table-hover align-middle">
          <thead class="table-light">
            <tr>
              <th>SKU</th>
              <th>Name</th>
              <th>Quantity</th>
              <th>Price</th>
            </tr>
          </thead>
          <tbody>
            @foreach($products as $product)
            <tr>
              <td>{{ $product->sku }}</td>
              <td>{{ $product->name }}</td>
              <td><span class="badge bg-danger">{{ $product->quantity }}</span></td>
              <td>@currency($product->price)</td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      <div class="mt-4">
         
        {{ $products->links('pagination::bootstrap-5') }}
     
    </div>

      @else
      <p class="text-muted mb-0">All products are well stocked 🎉</p>
      @endif
    </div>
  </div>
</div>
 
