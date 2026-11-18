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

          <form action="{{ route('invoices.store') }}" method="POST">
            @csrf

            <div class="mb-3">
              <label>Product Name*</label>
              <select name="name" class="form-control" id="productSelect" required>
                  <option value="">-- Select Item --</option>
                  @foreach($products as $item)
                     
                      <option value="{{ $item->id }}"
                  data-stock="{{ $item->quantity }}"
                  data-price="{{ $item->price }}"
                  {{ old('name') == $item->id ? 'selected' : '' }}>
              {!! $item->name !!}
          </option>
                  @endforeach
              </select>
              @error('name')
                  <small class="text-danger">{{ $message }}</small>
              @enderror
            </div>
            


            <div class="mb-3">
              <label>Price*</label>
              <input type="text" name="price" class="form-control" value="{{ old('price') }}" placeholder="Price" required>
              @error('price')
                <small class="text-danger">{{ $message }}</small>
              @enderror
            </div>

            <div class="mb-3">
              <small id="stockFlag" class="text-success fw-semibold" style="display:none;"></small>
              <label>Quatity* </label>
              <input type="text" name="quantity" class="form-control" value="{{ old('quantity') }}" placeholder="Quantity"  required>
              @error('quantity')
                <small class="text-danger">{{ $message }}</small>
              @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Payment Mode*</label>
                <select name="payment_mode" class="form-select" required>
                    <option value="">-- Select Payment Mode --</option>
                    <option value="cash" {{ old('payment_mode', $order->payment_mode ?? '') == 'cash' ? 'selected' : '' }}>Cash</option>
                    <option value="card" {{ old('payment_mode', $order->payment_mode ?? '') == 'card' ? 'selected' : '' }}>Card</option>
                    <option value="upi" {{ old('payment_mode', $order->payment_mode ?? '') == 'upi' ? 'selected' : '' }}>UPI</option>
                    <option value="bank_transfer" {{ old('payment_mode', $order->payment_mode ?? '') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                    <option value="paypal" {{ old('payment_mode', $order->payment_mode ?? '') == 'paypal' ? 'selected' : '' }}>PayPal</option>
                    <option value="cheque" {{ old('payment_mode', $order->payment_mode ?? '') == 'cheque' ? 'selected' : '' }}>Cheque</option>
                    <option value="wallet" {{ old('payment_mode', $order->payment_mode ?? '') == 'wallet' ? 'selected' : '' }}>Wallet</option>
                </select>
            </div>



            <div class="mb-3">
              <label>Customer Name*</label>
              <input type="text" name="customer_name" class="form-control" value="{{ old('customer_name') }}" placeholder="Customer Name"  required>
              @error('customer_name')
                <small class="text-danger">{{ $message }}</small>
              @enderror
            </div>

            <div class="mb-3">
              <label>Customer Mobile*</label>
              <input type="text" name="customer_mobile" class="form-control" value="{{ old('customer_mobile') }}" placeholder="Customer Mobile"  required>
              @error('customer_mobile')
                <small class="text-danger">{{ $message }}</small>
              @enderror
            </div>

            <div class="mb-3">
              <label>Customer Email</label>
              <input type="text" name="customer_email" class="form-control" value="{{ old('customer_email') }}" placeholder="Customer Email"  required>
              @error('customer_email')
                <small class="text-danger">{{ $message }}</small>
              @enderror
            </div>

            <div class="mb-3">
              <label>Customer Address</label>
              <textarea name="customer_address" class="form-control" placeholder="Customer Address" >{{ old('customer_address') }}</textarea>
              @error('item.customer_address ')
                <small class="text-danger">{{ $message }}</small>
              @enderror
            </div>


            <div class="mb-3">
              <label>Store Name*</label>
              <input type="text" name="store_name" class="form-control" value="{{ old('store_name') }}"  placeholder="Store Name" required>
              @error('store_name')
                <small class="text-danger">{{ $message }}</small>
              @enderror
            </div>

            <div class="mb-3">
              <label>Store Mobile*</label>
              <input type="text" name="store_phone" class="form-control" value="{{ old('store_phone') }}" placeholder="Store Mobile"  required>
              @error('store_phone')
                <small class="text-danger">{{ $message }}</small>
              @enderror
            </div>

            <div class="mb-3">
              <label>Store Email</label>
              <input type="text" name="store_email" class="form-control" value="{{ old('store_email') }}"  placeholder="Store Email" required>
              @error('store_email')
                <small class="text-danger">{{ $message }}</small>
              @enderror
            </div>

            <div class="mb-3">
              <label>Store Address</label>
              <textarea name="store_address" class="form-control" placeholder="Store Address" >{{ old('store_address') }}</textarea>
              @error('item.store_address ')
                <small class="text-danger">{{ $message }}</small>
              @enderror
            </div>

           

            <div class="d-flex justify-content-end">
              <button class="btn btn-primary me-2">Create Invoice</button>
              <a href="{{ url()->previous() }}" class="btn btn-secondary">Cancel</a>
            </div>
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
