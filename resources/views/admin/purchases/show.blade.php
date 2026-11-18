@include('admin.layouts.header')

<div class="content-wrapper">

    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">

        <div class="page-body">

            <div class="container-xl">

                <div class="card shadow-sm">

                    {{-- Card Header --}}
                    <div class="card-header d-flex justify-content-between align-items-center">

                        <div>

                            <h3 class="card-title mb-0">
                                Purchase Details
                            </h3>

                        </div>

                        <div class="d-flex align-items-center">

                            {{-- Delete --}}
                        <!--     <form action="{{ route('purchases.destroy', $purchase->id) }}"
                                  method="POST"
                                  class="me-2">

                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                        class="btn btn-danger btn-sm"
                                        onclick="return confirm('Delete this purchase?')">

                                    <i class="fa fa-trash"></i>
                                    Delete

                                </button>

                            </form> -->

                            {{-- Back --}}
                            <a href="{{ route('purchases.index') }}"
                               class="btn btn-secondary btn-sm">

                                <i class="fa fa-arrow-left"></i>
                                Back

                            </a>

                        </div>

                    </div>

                    {{-- Card Body --}}
                    <div class="card-body">

                        {{-- Purchase Info --}}
                        <div class="row mb-4">

                            {{-- Bill No --}}
                            <div class="col-md-3 mb-3">

                                <label class="form-label fw-bold">
                                    Bill No.
                                </label>

                                <input type="text"
                                       class="form-control"
                                       value="{{ $purchase->bill_no }}"
                                       disabled>

                            </div>

                            {{-- Supplier --}}
                            <div class="col-md-3 mb-3">

                                <label class="form-label fw-bold">
                                    Supplier
                                </label>

                                <input type="text"
                                       class="form-control"
                                       value="{{ $purchase->supplier->name ?? 'N/A' }}"
                                       disabled>

                            </div>

                            {{-- Purchase Date --}}
                            <div class="col-md-3 mb-3">

                                <label class="form-label fw-bold">
                                    Purchase Date
                                </label>

                                <input type="text"
                                       class="form-control"
                                       value="{{ \Carbon\Carbon::parse($purchase->purchase_date)->format('d M Y') }}"
                                       disabled>

                            </div>

                            {{-- Status --}}
                            <div class="col-md-3 mb-3">

                                <label class="form-label fw-bold">
                                    Status
                                </label>

                                <input type="text"
                                       class="form-control"
                                       value="{{ $purchase->status == 1 ? 'Approved' : 'Pending' }}"
                                       disabled>

                            </div>

                        </div>

                        {{-- Purchase Items Table --}}
                        <div class="table-responsive">

                            <table class="table table-bordered table-striped align-middle">

                                <thead class="table-light">

                                    <tr>

                                        <th width="60">
                                            #
                                        </th>

                                        <th>
                                            Store
                                        </th>

                                        <th>
                                            Item Name
                                        </th>

                                        <th>
                                            Current Stock
                                        </th>

                                        <th>
                                            Quantity
                                        </th>

                                        <th>
                                            Unit Cost
                                        </th>

                                        <th>
                                            Total
                                        </th>

                                    </tr>

                                </thead>

                                <tbody>

                                    @forelse($purchase->details as $detail)

                                        <tr>

                                            {{-- Row Number --}}
                                            <td>
                                                {{ $loop->iteration }}
                                            </td>

                                            {{-- Store --}}
                                            <td>

                                                {{ $detail->store->name ?? 'N/A' }}

                                            </td>

                                            {{-- Item --}}
                                            <td>

                                                {{ $detail->item->name ?? 'N/A' }}

                                            </td>

                                            {{-- Current Stock --}}
                                            <td>

                                                <span class="badge bg-primary">

                                                    {{ $detail->item->quantity ?? 0 }}

                                                </span>

                                            </td>

                                            {{-- Quantity --}}
                                            <td>

                                                <span class="badge bg-success">

                                                    {{ $detail->quantity }}

                                                </span>

                                            </td>

                                            {{-- Unit Cost --}}
                                            <td>

                                                ₹{{ number_format($detail->unitcost, 2) }}

                                            </td>

                                            {{-- Total --}}
                                            <td>

                                                ₹{{ number_format($detail->total, 2) }}

                                            </td>

                                        </tr>

                                    @empty

                                        <tr>

                                            <td colspan="7"
                                                class="text-center py-4">

                                                No Purchase Items Found

                                            </td>

                                        </tr>

                                    @endforelse

                                </tbody>

                                {{-- Footer --}}
                                <tfoot>

                                    <tr>

                                        <th colspan="6"
                                            class="text-end">

                                            Grand Total

                                        </th>

                                        <th>

                                            ₹{{ number_format($purchase->total_amount, 2) }}

                                        </th>

                                    </tr>

                                </tfoot>

                            </table>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

{{-- Styles --}}
<link
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
    rel="stylesheet"
/>

@include('admin.layouts.footer')