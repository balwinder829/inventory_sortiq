@include('admin.layouts.header')

<div class="content-wrapper">

    <div class="container-xxl flex-grow-1 container-p-y">

        <div class="card shadow-sm">

            <div class="card-header d-flex justify-content-between align-items-center">

                <h4 class="mb-0">
                    Create Purchase
                </h4>

                <a href="{{ route('purchases.index') }}"
                   class="btn btn-secondary">

                    Back

                </a>

            </div>

            <div class="card-body">

                @if ($errors->any())

                    <div class="alert alert-danger">

                        <ul class="mb-0">

                            @foreach ($errors->all() as $error)

                                <li>{{ $error }}</li>

                            @endforeach

                        </ul>

                    </div>

                @endif

                <form action="{{ route('purchases.store') }}"
                      method="POST">

                    @csrf

                    {{-- Purchase Header --}}
                    <div class="row mb-4">

                        {{-- Supplier --}}
                        <div class="col-md-4 mb-3">

                            <label class="form-label fw-bold">
                                Supplier
                            </label>

                            <select name="supplier_id"
                                    class="form-select"
                                    required>

                                <option value="">
                                    Select Supplier
                                </option>

                                @foreach($suppliers as $supplier)

                                    <option value="{{ $supplier->id }}">
                                        {{ $supplier->name }}
                                    </option>

                                @endforeach

                            </select>

                        </div>

                        {{-- Purchase Date --}}
                        <div class="col-md-4 mb-3">

                            <label class="form-label fw-bold">
                                Purchase Date
                            </label>

                            <input type="date"
                                   name="purchase_date"
                                   class="form-control"
                                   value="{{ now()->format('Y-m-d') }}"
                                   required>

                        </div>

                        {{-- Bill No --}}
                        <div class="col-md-4 mb-3">

                            <label class="form-label fw-bold">
                                Bill Number
                            </label>

                            <input type="text"
                                   name="bill_no"
                                   class="form-control"
                                   placeholder="Enter Bill Number"
                                   required>

                        </div>

                    </div>

                    {{-- Purchase Items Table --}}
                    <div class="table-responsive">

                        <table class="table table-bordered"
                               id="purchaseTable">

                            <thead class="table-light">

                                <tr>

                                    <th>
                                        Store
                                    </th>

                                    <th>
                                        Item
                                    </th>

                                    <th>
                                        Quantity
                                    </th>

                                    <th>
                                        Cost Price
                                    </th>

                                    <th>
                                        Selling Price
                                    </th>

                                    <th>
                                        Total
                                    </th>

                                    <th width="80">
                                        Action
                                    </th>

                                </tr>

                            </thead>

                            <tbody>

                                <tr>

                                    {{-- Store --}}
                                    <td>

                                        <select name="items[0][store_id]"
                                                class="form-select"
                                                required>

                                            <option value="">
                                                Select Store
                                            </option>

                                            @foreach($stores as $store)

                                                <option value="{{ $store->id }}">
                                                    {{ $store->name }}
                                                </option>

                                            @endforeach

                                        </select>

                                    </td>

                                    {{-- Item --}}
                                    <td>

                                        <select name="items[0][item_id]"
                                                class="form-select item-select"
                                                required>

                                            <option value="">
                                                Select Item
                                            </option>

                                            @foreach($items as $item)

                                                <option value="{{ $item->id }}"
                                                    data-store="{{ $item->store_id }}"
                                                    data-price="{{ $item->price }}"
                                                    data-cost="{{ $item->cost_price }}">

                                                    {{ $item->name }}
                                                    ({{ $item->sku }})

                                                </option>

                                            @endforeach

                                        </select>

                                    </td>

                                    {{-- Quantity --}}
                                    <td>

                                        <input type="number"
                                               name="items[0][quantity]"
                                               class="form-control quantity"
                                               min="1"
                                               value="1"
                                               required>

                                    </td>

                                    {{-- Cost Price --}}
                                    <td>

                                        <input type="number"
                                               step="0.01"
                                               name="items[0][unitcost]"
                                               class="form-control unitcost"
                                               required>

                                    </td>

                                    {{-- Selling Price --}}
                                    <td>

                                        <input type="number"
                                               step="0.01"
                                               name="items[0][selling_price]"
                                               class="form-control selling_price"
                                               required>

                                    </td>

                                    {{-- Total --}}
                                    <td>

                                        <input type="text"
                                               class="form-control total"
                                               readonly>

                                    </td>

                                    {{-- Action --}}
                                    <td>

                                        <button type="button"
                                                class="btn btn-danger removeRow">

                                            <i class="fa fa-trash"></i>

                                        </button>

                                    </td>

                                </tr>

                            </tbody>

                        </table>

                    </div>

                    {{-- Add Row --}}
                    <div class="mb-3">

                        <button type="button"
                                class="btn btn-primary"
                                id="addRow">

                            <i class="fa fa-plus"></i>
                            Add Item

                        </button>

                    </div>

                    {{-- Grand Total --}}
                    <div class="row justify-content-end mb-4">

                        <div class="col-md-3">

                            <label class="form-label fw-bold">
                                Grand Total
                            </label>

                            <input type="text"
                                   id="grandTotal"
                                   class="form-control"
                                   readonly>

                        </div>

                    </div>

                    {{-- Submit --}}
                    <div class="text-end">

                        <button type="submit"
                                class="btn btn-success">

                            Save Purchase

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

</div>

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
      rel="stylesheet"/>

@include('admin.layouts.footer')

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script>

let rowIndex = 1;

// Add Row
$('#addRow').click(function () {

    let row = `

    <tr>

        <td>
            <select name="items[${rowIndex}][store_id]" class="form-select" required>
                <option value="">Select Store</option>

                @foreach($stores as $store)
                    <option value="{{ $store->id }}">{{ $store->name }}</option>
                @endforeach

            </select>
        </td>

        <td>
            <select name="items[${rowIndex}][item_id]" class="form-select item-select" required>

                <option value="">Select Item</option>

                @foreach($items as $item)

                    <option value="{{ $item->id }}"
                        data-store="{{ $item->store_id }}"
                        data-price="{{ $item->price }}"
                        data-cost="{{ $item->cost_price }}">

                        {{ $item->name }} ({{ $item->sku }})

                    </option>

                @endforeach

            </select>
        </td>

        <td>
            <input type="number"
                   name="items[${rowIndex}][quantity]"
                   class="form-control quantity"
                   min="1"
                   value="1"
                   required>
        </td>

        <td>
            <input type="number"
                   step="0.01"
                   name="items[${rowIndex}][unitcost]"
                   class="form-control unitcost"
                   required>
        </td>

        <td>
            <input type="number"
                   step="0.01"
                   name="items[${rowIndex}][selling_price]"
                   class="form-control selling_price"
                   required>
        </td>

        <td>
            <input type="text"
                   class="form-control total"
                   readonly>
        </td>

        <td>
            <button type="button"
                    class="btn btn-danger removeRow">

                <i class="fa fa-trash"></i>

            </button>
        </td>

    </tr>
    `;

    $('#purchaseTable tbody').append(row);

    rowIndex++;
});

// Remove Row
$(document).on('click', '.removeRow', function () {

    if ($('#purchaseTable tbody tr').length > 1) {

        $(this).closest('tr').remove();

        calculateGrandTotal();
    }
});

// Autofill prices
$(document).on('change', '.item-select', function () {

    let row = $(this).closest('tr');

    let option = $(this).find(':selected');

    row.find('.unitcost').val(option.data('cost'));

    row.find('.selling_price').val(option.data('price'));

    calculateRow(row);
});

// Quantity/Cost change
$(document).on('keyup change', '.quantity, .unitcost', function () {

    let row = $(this).closest('tr');

    calculateRow(row);
});

// Calculate row total
function calculateRow(row)
{
    let qty = parseFloat(row.find('.quantity').val()) || 0;

    let cost = parseFloat(row.find('.unitcost').val()) || 0;

    let total = qty * cost;

    row.find('.total').val(total.toFixed(2));

    calculateGrandTotal();
}

// Grand Total
function calculateGrandTotal()
{
    let grandTotal = 0;

    $('.total').each(function () {

        grandTotal += parseFloat($(this).val()) || 0;
    });

    $('#grandTotal').val(grandTotal.toFixed(2));
}
// Filter items by selected store
$(document).on('change', 'select[name*="[store_id]"]', function () {

    let selectedStore = $(this).val();

    let row = $(this).closest('tr');

    let itemSelect = row.find('.item-select');

    // Reset selected item
    itemSelect.val('');

    itemSelect.find('option').each(function () {

        let storeId = $(this).data('store');

        // Keep default option visible
        if (!storeId) {

            $(this).show();

            return;
        }

        // Show only matching store items
        if (storeId == selectedStore) {

            $(this).show();

        } else {

            $(this).hide();
        }

    });

});
</script>