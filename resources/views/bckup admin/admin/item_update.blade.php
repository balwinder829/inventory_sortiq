
@include('admin.layouts.header')
          <!-- Content wrapper -->
          <div class="content-wrapper">
            <div class="container-xxl flex-grow-1 container-p-y">
           <div class="container">
            <h4 class="mb-2">Update Item</h4>
            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Whoops! There were some problems with your input.</strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form action="{{ route('items.update', $item->id) }}" method="POST">
    @csrf
    @method('PUT')
<input type="hidden" name="store_id" value="{{ $item->store_id }}">
    <div class="mb-3">
        <label>Item Name</label>
        <input type="text" name="name" class="form-control" 
               value="{{ old('name', $item->name) }}">
        @error('name')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>

    <div class="mb-3">
        <label>Description</label>
        <textarea name="description" class="form-control">{{ old('description', $item->description) }}</textarea>
        @error('description')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>

    <div class="mb-3">
        <label>Category</label>
        <input type="text" name="category" class="form-control" 
               value="{{ old('category', $item->category) }}">
        @error('category')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>

    <div class="mb-3">
        <label>Price</label>
        <input type="number" step="0.01" name="price" class="form-control" 
               value="{{ old('price', $item->price) }}">
        @error('price')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>

    <div class="mb-3">
        <label>Quantity</label>
        <input type="number" name="quantity" class="form-control" 
               value="{{ old('quantity', $item->quantity) }}">
        @error('quantity')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>

    <!-- <div class="mb-3">
        <label>SKU</label>
        <input type="text" name="sku" class="form-control" 
               value="{{ old('sku', $item->sku) }}">
        @error('sku')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div> -->

    <div class="mb-3">
        <label>Brand</label>
        <input type="text" name="brand" class="form-control" 
               value="{{ old('brand', $item->brand) }}">
        @error('brand')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>

    <div class="mb-3">
        <label>Weight</label>
        <input type="text" name="weight" class="form-control" 
               value="{{ old('weight', $item->weight) }}">
        @error('weight')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>

    <div class="mb-3">
        <label>Dimensions</label>
        <input type="text" name="dimensions" class="form-control" 
               value="{{ old('dimensions', $item->dimensions) }}">
        @error('dimensions')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>

    <div class="mb-3">
        <label>Status</label>
        <select name="status" class="form-control">
            <option value="available" {{ old('status', $item->status) === 'available' ? 'selected' : '' }}>Available</option>
            <option value="out_of_stock" {{ old('status', $item->status) === 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
            <option value="discontinued" {{ old('status', $item->status) === 'discontinued' ? 'selected' : '' }}>Discontinued</option>
        </select>
        @error('status')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>

  <!--  <div class="mb-3">
    <label>Tags (JSON format)</label>
    <input type="text" name="tags" class="form-control" placeholder='["tag1", "tag2"]' 
           value="{{ old('tags', json_encode($item->tags)) }}">
    @error('tags')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div> -->

    <div class="mb-3">
        <label>Sale Price</label>
        <input type="number" step="0.01" name="sale_price" class="form-control" 
               value="{{ old('sale_price', $item->sale_price) }}">
        @error('sale_price')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
<!-- <div class="d-flex justify-content-between"> -->
    <button class="btn btn-primary">Update Item</button>

    <!-- <button type="submit" class="btn btn-primary">Update Item</button> -->
    <a href="{{ url()->previous() }}" class="btn btn-secondary">Cancel</a>
<!-- </div> -->
</form>



        </div>

            

            <div class="content-backdrop fade"></div>
          </div>
          <!-- Content wrapper -->
        </div>
        <!-- / Layout page -->
      </div>

      <!-- Overlay -->
    
    </div>
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
  </body>
</html>
