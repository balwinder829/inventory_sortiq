@include('admin.layouts.header')

<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="page-body">
            <div class="container-xl">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title mb-0">{{ __('Invoice Details') }}</h3>
                        <a href="{{ route('invoices.index') }}" class="btn btn-secondary">{{ __('Back') }}</a>
                    </div>

                    <div class="card-body">
                        <div class="row row-cards mb-3">
                            <div class="col">
                                <label class="form-label required">{{ __('Order Date') }}</label>
                                <input type="text"
                                       class="form-control"
                                       value="{{ $order->invoice_date ? date('Y-m-d', strtotime($order->invoice_date)) : '' }}"
                                       disabled>
                            </div>

                            <div class="col">
                                <label class="form-label required">{{ __('Invoice No.') }}</label>
                                <input type="text" class="form-control" value="{{ $order->invoice_no }}" disabled>
                            </div>

                            <div class="col">
                                <label class="form-label required">{{ __('Customer') }}</label>
                                <input type="text" class="form-control" value="{{ $order->customer_name }}" disabled>
                            </div>

                            <div class="col">
                                <label class="form-label required">{{ __('Payment Type') }}</label>
                                <input type="text" class="form-control" 
                                       value="{{ ucfirst(str_replace('_', ' ', $order->payment_mode ?? 'N/A')) }}" 
                                       disabled>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-striped table-bordered align-middle">
                                <thead class="thead-light">
                                    <tr>
                                        <th class="align-middle text-center">No.</th>
                                        <th class="align-middle text-center">Product Name</th>
                                        <th class="align-middle text-center">Quantity</th>
                                        <th class="align-middle text-center">Price</th>
                                        <th class="align-middle text-center">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                   
    
    <tr>
         

        {{-- Image column --}}
         

        <td class="align-middle text-center">{{ $order->id ?? 'N/A' }}</td>
        <td>{{ $order->product->name ?? 'N/A' }}</td>
        <td class="align-middle text-center">{{ $order->quantity }}</td>
        <td class="align-middle text-center">₹{{ is_numeric($order->price) ? number_format($order->price, 2) : '0.00' }}</td>
        <td class="align-middle text-center">₹{{ (is_numeric($order->price) && is_numeric($order->quantity)) 
                                            ? number_format($order->price * $order->quantity, 2) 
                                            : '0.00' }}</td>
    </tr>



                                    {{-- Totals rows --}}
                                    <tr>
                                        <td colspan="4" class="text-end">Total</td>
                                        <td class="text-center">₹{{ (is_numeric($order->price) && is_numeric($order->quantity)) 
                                            ? number_format($order->price * $order->quantity, 2) 
                                            : '0.00' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

@include('admin.layouts.footer')
