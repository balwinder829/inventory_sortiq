@include('admin.layouts.header')

<!-- Content wrapper -->
<div class="content-wrapper">
  <div class="container-xxl flex-grow-1 container-p-y">
    <div class="container" style="width: 75%;">

      <div class="card shadow-lg rounded-3">
        <div class="card-header text-white">
          <h4 class="mb-0">Create Invoice</h4>
        </div>
        <div class="card-body">

          {{-- Validation Errors --}}
          @if ($errors->any())
              <div class="alert alert-danger">
                  <ul class="mb-0">
                      @foreach ($errors->all() as $error)
                          <li>{{ $error }}</li>
                      @endforeach
                  </ul>
              </div>
          @endif

          <form action="{{ route('invoices.store') }}" method="POST">
            @csrf

            {{-- =================== Product Section =================== --}}
            <h5 class="fw-bold mb-3 border-bottom pb-2">Products</h5>

            <div id="productContainer">
              <div class="product-row border rounded p-3 mb-3 bg-light">
                <div class="row g-3 align-items-center">

                  <div class="col-md-5">
                    <label class="form-label">Product*</label>
                    <select name="products[0][id]" class="form-select product-select" required>
                        <option value="">-- Select Product --</option>
                        @foreach($products as $item)
                            <option value="{{ $item->id }}"
                                    data-stock="{{ $item->quantity }}"
                                    data-price="{{ $item->price }}">
                                {{ $item->name }}
                            </option>
                        @endforeach
                    </select>
                    <small class="stock-flag text-success fw-semibold d-block mt-1" style="display:none;"></small>
                  </div>

                  <div class="col-md-2">
                    <label class="form-label">Price*</label>
                    <input type="number" step="0.01" name="products[0][price]" class="form-control price-input" placeholder="0.00" required>
                  </div>

                  <div class="col-md-2">
                    <label class="form-label">Qty*</label>
                    <input type="number" name="products[0][quantity]" class="form-control quantity-input" placeholder="1" required>
                  </div>

                  <div class="col-md-2">
                    <label class="form-label">Subtotal</label>
                    <input type="text" class="form-control subtotal-input" placeholder="0.00" readonly>
                  </div>

                  <div class="col-md-1 text-end">
                    <button type="button" class="btn btn-danger btn-sm remove-product" style="display:none;">✖</button>
                  </div>

                </div>
              </div>
            </div>

            <div class="text-end mb-4">
              <button type="button" class="btn btn-outline-primary" id="addProduct">+ Add Product</button>
            </div>

            {{-- =================== Total =================== --}}
            <div class="text-end mb-4">
              <h5 class="fw-bold">Total: ₹ <span id="totalAmount">0.00</span></h5>
            </div>

            {{-- =================== Payment & Customer Info =================== --}}
            <h5 class="fw-bold mb-3 border-bottom pb-2">Payment Details</h5>
            <div class="mb-3">
                <label class="form-label">Payment Mode*</label>
                <select name="payment_mode" class="form-select" required>
                    <option value="">-- Select Payment Mode --</option>
                    <option value="cash">Cash</option>
                    <option value="card">Card</option>
                    <option value="upi">UPI</option>
                    <option value="bank_transfer">Bank Transfer</option>
                    <option value="paypal">PayPal</option>
                    <option value="cheque">Cheque</option>
                    <option value="wallet">Wallet</option>
                </select>
            </div>

            <h5 class="fw-bold mb-3 border-bottom pb-2">Customer Information</h5>
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label">Customer Name*</label>
                <input type="text" name="customer_name" class="form-control" value="{{ old('customer_name') }}" required>
              </div>
              <div class="col-md-6">
                <label class="form-label">Mobile*</label>
                <input type="text" name="customer_mobile" class="form-control" value="{{ old('customer_mobile') }}" required>
              </div>
              <div class="col-md-6">
                <label class="form-label">Email</label>
                <input type="email" name="customer_email" class="form-control" value="{{ old('customer_email') }}">
              </div>
              <div class="col-md-6">
                <label class="form-label">Address</label>
                <textarea name="customer_address" class="form-control" rows="1">{{ old('customer_address') }}</textarea>
              </div>
            </div>

            <h5 class="fw-bold mb-3 border-bottom pb-2 mt-4">Store Information</h5>
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label">Store Name*</label>
                <input type="text" name="store_name" class="form-control" value="{{ old('store_name') }}" required>
              </div>
              <div class="col-md-6">
                <label class="form-label">Store Mobile*</label>
                <input type="text" name="store_phone" class="form-control" value="{{ old('store_phone') }}" required>
              </div>
              <div class="col-md-6">
                <label class="form-label">Store Email</label>
                <input type="email" name="store_email" class="form-control" value="{{ old('store_email') }}">
              </div>
              <div class="col-md-6">
                <label class="form-label">Store Address</label>
                <textarea name="store_address" class="form-control" rows="1">{{ old('store_address') }}</textarea>
              </div>
            </div>

            <div class="d-flex justify-content-end mt-4">
              <button class="btn btn-primary me-2">Create Invoice</button>
              <a href="{{ url()->previous() }}" class="btn btn-secondary">Cancel</a>
            </div>

          </form>
        </div>
      </div>

    </div>
  </div>
</div>

<!-- Scripts -->
<script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
<script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
<script src="{{ asset('assets/js/main.js') }}"></script>

<script>
$(document).ready(function () {
    let productIndex = 1;

    // Add product row
    $('#addProduct').on('click', function () {
        const newRow = $('.product-row:first').clone();
        newRow.find('select, input').each(function () {
            const name = $(this).attr('name');
            if (name) {
                const newName = name.replace(/\[\d+\]/, '[' + productIndex + ']');
                $(this).attr('name', newName).val('');
            } else {
                $(this).val('');
            }
        });
        newRow.find('.stock-flag').hide();
        newRow.find('.remove-product').show();
        $('#productContainer').append(newRow);
        productIndex++;
        calculateTotal();
    });

    // Remove product row
    $(document).on('click', '.remove-product', function () {
        $(this).closest('.product-row').remove();
        calculateTotal();
    });

    // Product change event
    $(document).on('change', '.product-select', function () {
        const $row = $(this).closest('.product-row');
        const selected = $(this).find(':selected');
        const stock = parseInt(selected.data('stock')) || 0;
        const price = selected.data('price');
        const $stockFlag = $row.find('.stock-flag');
        const $qty = $row.find('.quantity-input');
        const $price = $row.find('.price-input');

        if (stock > 0) {
            $stockFlag.text('🟢 Available: ' + stock + ' in stock')
                      .removeClass('text-danger')
                      .addClass('text-success')
                      .show();
            $qty.attr('max', stock).attr('placeholder', 'Max: ' + stock).val('');
        } else if ($(this).val() !== "") {
            $stockFlag.text('🔴 Out of stock')
                      .removeClass('text-success')
                      .addClass('text-danger')
                      .show();
            $qty.attr('max', 0).val('');
        } else {
            $stockFlag.hide();
            $qty.removeAttr('max').attr('placeholder', 'Quantity').val('');
        }

        if (price) $price.val(price);
        calculateSubtotal($row);
    });

    // Quantity / price input changes
    $(document).on('input', '.quantity-input, .price-input', function () {
        const $row = $(this).closest('.product-row');
        const $stockFlag = $row.find('.stock-flag');
        const max = parseInt($row.find('.quantity-input').attr('max')) || 0;
        const val = parseInt($row.find('.quantity-input').val()) || 0;

        if (max && val > max) {
            $row.find('.quantity-input').val(max);
            $stockFlag.text('🔴 Max ' + max + ' available')
                      .removeClass('text-success')
                      .addClass('text-danger');
        } else if (max) {
            $stockFlag.text('🟢 Available: ' + max + ' in stock')
                      .removeClass('text-danger')
                      .addClass('text-success');
        }

        calculateSubtotal($row);
    });

    // Calculate subtotal for a row
    function calculateSubtotal($row) {
        const price = parseFloat($row.find('.price-input').val()) || 0;
        const qty = parseFloat($row.find('.quantity-input').val()) || 0;
        const subtotal = price * qty;
        $row.find('.subtotal-input').val(subtotal.toFixed(2));
        calculateTotal();
    }

    // Calculate total for all products
    function calculateTotal() {
        let total = 0;
        $('.subtotal-input').each(function () {
            total += parseFloat($(this).val()) || 0;
        });
        $('#totalAmount').text(total.toFixed(2));
    }
});
</script>
