@include('admin.layouts.header')

<div class="content-wrapper">
  <div class="container-xxl flex-grow-1 container-p-y">
    <div class="container" style="width: 60%;">
      <div class="card shadow-lg rounded-3">
        <div class="card-header">
          <h4 class="mb-0">Update Product Inventory</h4>
        </div>

        <div class="card-body">
          {{-- Alerts --}}
          @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
          @endif

          @if($errors->any())
            <div class="alert alert-danger">
              <ul>
                @foreach($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          <form action="{{ route('wc.stock.update') }}" method="POST">
            @csrf

            {{-- Select Product --}}
            <div class="mb-3">
              <label class="form-label">Select Product*</label>
              <select name="woo_id" id="productSelect" class="form-control" required>
                <option value="">-- Choose Product --</option>
                @foreach($products as $product)
                  <option value="{{ $product->woo_id }}" data-stock="{{ $product->stock_quantity }}">
                    {{ $product->name }}
                  </option>
                @endforeach
              </select>
            </div>

            {{-- Current Stock --}}
            <div class="mb-3">
              <label class="form-label">Available Quantity</label>
              <input type="text" id="availableStock" class="form-control" readonly placeholder="Select a product">
            </div>

            {{-- Add Quantity --}}
            <div class="mb-3">
              <label class="form-label">Add Quantity*</label>
              <input type="number" name="stock_quantity" class="form-control" min="1" placeholder="Enter quantity to add" required>
            </div>

            {{-- Submit --}}
            <div class="d-flex justify-content-end">
              <button type="submit" class="btn btn-primary me-2">Update Stock</button>
              <a href="{{ route('wc.products') }}" class="btn btn-secondary">Cancel</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

{{-- Small Script to show available stock --}}
<script>
  document.getElementById('productSelect').addEventListener('change', function() {
    const selected = this.options[this.selectedIndex];
    const stock = selected.getAttribute('data-stock');
    document.getElementById('availableStock').value = stock ? stock : '';
  });
</script>
