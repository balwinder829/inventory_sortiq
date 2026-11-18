@include('admin.layouts.header')

<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">

        {{-- Breadcrumb and Navigation --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold py-1 mb-0">
                <span class="text-muted fw-light">Returns & Refunds /</span> Record Supplier Return
            </h4>
            <a href="{{ route('returns.supplier.index') }}" class="btn btn-outline-secondary">
                <i class="bx bx-arrow-back me-1"></i> Back to Ledger
            </a>
        </div>

        <div class="row justify-content-center">
            <div class="col-12">
                <form id="supplierReturnForm" method="POST" action="{{ route('returns.supplier.store') }}">
                    @csrf
                    
                    <div class="row">
                        {{-- Meta Details Card --}}
                        <div class="col-lg-4 mb-4">
                            <div class="card shadow-sm border-0 h-100">
                                <div class="card-header border-bottom py-3">
                                    <i class="bx bx-receipt fs-4 text-primary me-2"></i>
                                    <h5 class="card-title mb-0">Return Metadata</h5>
                                </div>
                                <div class="card-body pt-4">
                                    {{-- Errors Block --}}
                                    @if ($errors->any())
                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                            <i class="bx bx-error-circle me-1"></i>
                                            <ul class="mb-0 small">
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                        </div>
                                    @endif

                                    {{-- Store Select --}}
                                    <div class="mb-3">
                                        <label for="store_id" class="form-label fw-semibold">Target Store <span class="text-danger">*</span></label>
                                        <select id="store_id" name="store_id" class="form-select" required>
                                            <option value="" disabled {{ !old('store_id') ? 'selected' : '' }}>Select Store</option>
                                            @foreach($stores as $store)
                                                <option value="{{ $store->id }}" {{ old('store_id') == $store->id ? 'selected' : '' }}>
                                                    {{ $store->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    {{-- Supplier Select --}}
                                    <div class="mb-3">
                                        <label for="supplier_id" class="form-label fw-semibold">Supplier <span class="text-danger">*</span></label>
                                        <select id="supplier_id" name="supplier_id" class="form-select" required>
                                            <option value="" disabled {{ !old('supplier_id') ? 'selected' : '' }}>Select Supplier</option>
                                            @foreach($suppliers as $supplier)
                                                <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                                    {{ $supplier->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    {{-- Return Date --}}
                                    <div class="mb-3">
                                        <label for="return_date" class="form-label fw-semibold">Return Date <span class="text-danger">*</span></label>
                                        <input type="date" id="return_date" name="return_date" class="form-control" 
                                               value="{{ old('return_date', date('Y-m-d')) }}" required>
                                    </div>

                                    {{-- Reference No --}}
                                    <div class="mb-3">
                                        <label for="reference_no" class="form-label fw-semibold">Reference No. <small class="text-muted">(Optional)</small></label>
                                        <input type="text" id="reference_no" name="reference_no" class="form-control" 
                                               placeholder="e.g. SR-1002" value="{{ old('reference_no') }}">
                                    </div>

                                    {{-- Reason --}}
                                    <div class="mb-3">
                                        <label for="reason" class="form-label fw-semibold">Reason for Return <small class="text-muted">(Optional)</small></label>
                                        <textarea id="reason" name="reason" class="form-control" rows="3" 
                                                  placeholder="e.g. Damaged products, expired batches, excess stock...">{{ old('reason') }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Return Items Card --}}
                        <div class="col-lg-8 mb-4">
                            <div class="card shadow-sm border-0 h-100">
                                <div class="card-header border-bottom d-flex justify-content-between align-items-center py-3">
                                    <h5 class="card-title mb-0"><i class="bx bx-package fs-4 text-success me-2"></i>Items to Return</h5>
                                    <button type="button" id="addItemRow" class="btn btn-sm btn-success">
                                        <i class="bx bx-plus me-1"></i> Add Item
                                    </button>
                                </div>
                                <div class="card-body pt-4">
                                    <div class="table-responsive">
                                        <table class="table table-bordered align-middle" id="itemsTable">
                                            <thead>
                                                <tr>
                                                    <th>Item Name <span class="text-danger">*</span></th>
                                                    <th width="140">Available Stock</th>
                                                    <th width="130">Return Qty <span class="text-danger">*</span></th>
                                                    <th width="150">Cost Price (₹) <span class="text-danger">*</span></th>
                                                    <th width="160" class="text-end">Subtotal (₹)</th>
                                                    <th width="80" class="text-center">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody id="itemsContainer">
                                                {{-- Rows will be dynamically loaded via JS --}}
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th colspan="4" class="text-end fw-bold">Grand Total Amount (₹):</th>
                                                    <th class="text-end fw-bold text-success fs-5" id="grandTotalLabel">₹0.00</th>
                                                    <th></th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>

                                    {{-- Submission --}}
                                    <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                                        <a href="{{ route('returns.supplier.index') }}" class="btn btn-outline-secondary">Cancel</a>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="bx bx-check me-1"></i> Submit Return
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>

@include('admin.layouts.footer')

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script>
$(document).ready(function () {
    // Array of items passed from PHP
    const availableItems = @json($items);
    let rowIndex = 0;

    // Add row function
    function addRow() {
        rowIndex++;
        const rowHtml = `
            <tr id="row_${rowIndex}">
                <td>
                    <select name="items[${rowIndex}][item_id]" class="form-select item-select" required>
                        <option value="" disabled selected>Select Item</option>
                        ${availableItems.map(item => `<option value="${item.id}" data-stock="${item.quantity}" data-price="${item.cost_price}">${item.name}</option>`).join('')}
                    </select>
                </td>
                <td>
                    <input type="text" class="form-control bg-light stock-input" readonly value="0">
                </td>
                <td>
                    <input type="number" name="items[${rowIndex}][quantity]" class="form-control quantity-input" min="1" required disabled>
                </td>
                <td>
                    <input type="number" name="items[${rowIndex}][price]" class="form-control price-input" step="0.01" min="0.00" required disabled>
                </td>
                <td class="text-end fw-bold text-dark subtotal-cell">
                    ₹0.00
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-sm btn-icon btn-outline-danger remove-row">
                        <i class="bx bx-trash"></i>
                    </button>
                </td>
            </tr>
        `;
        $('#itemsContainer').append(rowHtml);
    }

    // Initialize with first row
    addRow();

    // Add Item button
    $('#addItemRow').on('click', function () {
        addRow();
    });

    // Remove row
    $(document).on('click', '.remove-row', function () {
        $(this).closest('tr').remove();
        calculateGrandTotal();
    });

    // Handle Item Selection changes
    $(document).on('change', '.item-select', function () {
        const option = $(this).find(':selected');
        const stock = parseInt(option.data('stock')) || 0;
        const price = parseFloat(option.data('price')) || 0.00;

        const row = $(this).closest('tr');
        row.find('.stock-input').value = stock; // update read-only stock field
        row.find('.stock-input').val(stock);

        const qtyInput = row.find('.quantity-input');
        qtyInput.prop('disabled', false).attr('max', stock).val(1);

        const priceInput = row.find('.price-input');
        priceInput.prop('disabled', false).val(price.toFixed(2));

        calculateRowSubtotal(row);
    });

    // Quantity / Price trigger
    $(document).on('input', '.quantity-input, .price-input', function () {
        const row = $(this).closest('tr');
        calculateRowSubtotal(row);
    });

    // Calculate Row Subtotal
    function calculateRowSubtotal(row) {
        const qty = parseInt(row.find('.quantity-input').val()) || 0;
        const price = parseFloat(row.find('.price-input').val()) || 0.00;
        const maxStock = parseInt(row.find('.quantity-input').attr('max')) || 0;

        // cap quantity at max stock
        if (qty > maxStock) {
            row.find('.quantity-input').val(maxStock);
            calculateRowSubtotal(row);
            return;
        }

        const subtotal = qty * price;
        row.find('.subtotal-cell').text('₹' + subtotal.toFixed(2));

        calculateGrandTotal();
    }

    // Calculate Grand Total
    function calculateGrandTotal() {
        let grandTotal = 0;
        $('.subtotal-cell').each(function () {
            const text = $(this).text().replace('₹', '');
            const val = parseFloat(text) || 0.00;
            grandTotal += val;
        });
        $('#grandTotalLabel').text('₹' + grandTotal.toFixed(2));
    }
});
</script>
