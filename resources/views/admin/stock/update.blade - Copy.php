@include('admin.layouts.header')
<!-- Content wrapper -->
<div class="content-wrapper">
  <div class="container-xxl flex-grow-1 container-p-y">
    <div class="container" style="width: 65%;">

      <div class="card shadow-lg rounded-3">
        <div class="card-header text-white">
          <h4 class="mb-0">Create Invoice</h4>
        </div>
        <div class="card-body">

          @if ($errors->any())
              <div class="alert alert-danger">
                  <!-- <strong>Whoops! There were some problems with your input.</strong> -->
                  <ul>
                      @foreach ($errors->all() as $error)
                          <li>{{ $error }}</li>
                      @endforeach
                  </ul>
              </div>
          @endif

          <form action="{{ route('stocks.update', $item->id) }}" method="POST">
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
        <label>Cost Price</label>
        <input type="number" step="0.01" name="cost_price" class="form-control" 
               value="{{ old('cost_price', $item->cost_price) }}">
        @error('cost_price')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>

     <div class="mb-3">
        <label>Sale Price</label>
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
            <option value="publish" {{ old('status', $item->status) === 'publish' ? 'selected' : '' }}>Publish</option>
            <option value="draft" {{ old('status', $item->status) === 'draft' ? 'selected' : '' }}>Draft</option>
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

    <!-- <div class="mb-3">
        <label>Sale Price</label>
        <input type="number" step="0.01" name="sale_price" class="form-control" 
               value="{{ old('sale_price', $item->sale_price) }}">
        @error('sale_price')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div> -->
<!-- <div class="d-flex justify-content-between"> -->
    <button class="btn btn-primary">Update Item</button>

    <!-- <button type="submit" class="btn btn-primary">Update Item</button> -->
    <a href="{{ url()->previous() }}" class="btn btn-secondary">Cancel</a>
<!-- </div> -->
</form>

        </div>
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
</div>
<!-- / Layout wrapper -->

<script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/popper/popper.js') }}"></script>
<script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
<script src="{{ asset('assets/vendor/js/menu.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>
<script src="{{ asset('assets/js/main.js') }}"></script>
<script src="{{ asset('assets/js/dashboards-analytics.js') }}"></script>
<!-- <script>
$(document).ready(function () {
    $('#productSelect').on('change', function () {
        var stock = $(this).find(':selected').data('stock');
        var price = $(this).find(':selected').data('price');
        
        if (stock) {
            $('input[name="quantity"]').attr('max', stock);
            $('input[name="quantity"]').attr('placeholder', 'Max: ' + stock);
        } else {
            $('input[name="quantity"]').removeAttr('max');
            $('input[name="quantity"]').attr('placeholder', 'Quantity');
        }

        if (price) {
            $('input[name="price"]').val(price);
        }
    });

    // Optional: prevent typing beyond stock
    $('input[name="quantity"]').on('input', function () {
        var max = parseInt($(this).attr('max'));
        var val = parseInt($(this).val());
        if (val > max) {
            $(this).val(max);
        }
    });
});
</script> -->
<!-- <script>
$(document).ready(function() {
  console.log('ready');
    const $productSelect = $('#productSelect');
    const $stockFlag = $('#stockFlag');
    const $quantityInput = $('input[name="quantity"]');
    const $priceInput = $('input[name="price"]');

    $productSelect.on('change', function() {
        const selected = $(this).find(':selected');
        const stock = selected.data('stock');
        const price = selected.data('price');
console.log('stock',stock);
        if (stock) {
            // ✅ Show available stock as flag
          console.log('stock inner',stock);
            $stockFlag
                .text('🟢 Available: ' + stock + ' in stock')
                .css('display', 'block')
                .removeClass('text-danger')
                .addClass('text-success');

            // ✅ Set max and placeholder for quantity
            $quantityInput.attr('max', stock).attr('placeholder', 'Max: ' + stock);

        } else {
            $stockFlag.hide();
            $quantityInput.removeAttr('max').attr('placeholder', 'Quantity');
        }

        if (price) {
            $priceInput.val(price);
        }
    });

    // Optional: warn if entered quantity exceeds stock
    $quantityInput.on('input', function() {
      console.log('input');
      
        const max = parseInt($(this).attr('max'));
        const val = parseInt($(this).val());
        console.log('max',max);
        console.log('val',val);
        if (max && val > max) {
            $stockFlag
                .text('🔴 Only ' + max + ' available in stock!')
                .removeClass('text-success')
                .addClass('text-danger');
        } else if (max) {
            $stockFlag
                .text('🟢 Available: ' + max + ' in stock')
                .removeClass('text-danger')
                .addClass('text-success');
        }
    });
});
</script> -->


<script>
$(document).ready(function() {
    const $productSelect = $('#productSelect');
    const $stockFlag = $('#stockFlag');
    const $quantityInput = $('input[name="quantity"]');
    const $priceInput = $('input[name="price"]');

    // When product changes
    $productSelect.on('change', function() {
        const selected = $(this).find('option:selected');
        const stock = parseInt(selected.data('stock')) || 0;
        const price = selected.data('price');

        if (stock > 0) {
            // Show available stock
            $stockFlag
                .text('🟢 Available: ' + stock + ' in stock')
                .css('display', 'block')
                .removeClass('text-danger')
                .addClass('text-success');

            // Set max limit and placeholder
            $quantityInput
                .attr('max', stock)
                .attr('placeholder', 'Max: ' + stock)
                .val(''); // clear old input
        } else if ($(this).val() !== "") {
            $stockFlag
                .text('🔴 Out of stock')
                .css('display', 'block')
                .removeClass('text-success')
                .addClass('text-danger');
            $quantityInput.attr('max', 0).val('');
        } else {
            $stockFlag.hide();
            $quantityInput.removeAttr('max').attr('placeholder', 'Quantity').val('');
        }

        // Autofill price if available
        if (price) {
            $priceInput.val(price);
        }
    });

    // Enforce quantity restriction
    $quantityInput.on('input', function() {
        const max = parseInt($(this).attr('max')) || 0;
        let val = parseInt($(this).val()) || 0;

        // If above stock, limit to max
        if (max && val > max) {
            $(this).val(max);
            $stockFlag
                .text('🔴 You cannot order more than ' + max + ' units.')
                .removeClass('text-success')
                .addClass('text-danger')
                .show();
        } else if (max) {
            $stockFlag
                .text('🟢 Available: ' + max + ' in stock')
                .removeClass('text-danger')
                .addClass('text-success')
                .show();
        }
    });

    // Also prevent manual arrow increments beyond max
    $quantityInput.on('keydown', function(e) {
        const max = parseInt($(this).attr('max')) || 0;
        const val = parseInt($(this).val()) || 0;

        if ((e.key === 'ArrowUp' || e.key === 'ArrowRight') && val >= max) {
            e.preventDefault();
        }
    });
});
</script>
