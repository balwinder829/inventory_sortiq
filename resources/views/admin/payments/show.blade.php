@include('admin.layouts.header')

<div class="content-wrapper">
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">


    <div class="page-body">
        <div class="container-xl">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title mb-0">
                    {{ __('Payment Details') }}
                </h3>

                <a href="{{ route('payments.index') }}" class="btn btn-secondary">
                    {{ __('Back') }}
                </a>
            </div>


                <div class="card-body">
                    <!-- <div class="row row-cards mb-3">
                        <div class="col">
                            <label for="order_date" class="form-label required">
                                {{ __('Order Date') }}
                            </label>
                             
                        </div>

                        <div class="col">
                            <label for="invoice_no" class="form-label required">
                                {{ __('Invoice No.') }}
                            </label>
                             
                        </div>

                        <div class="col">
                            <label for="customer" class="form-label required">
                                {{ __('Customer') }}
                            </label>
                            
                        </div>

                        <div class="col">
                            <label for="payment_type" class="form-label required">
                                {{ __('Payment Type') }}
                            </label>

                            
                        </div>
                    </div> -->

                    <div class="table-responsive">
                        <table class="table table-bordered w-100">
        <tbody>
            <tr>
                <th style="width: 200px;">Payment ID</th>
                <td>PMT-1050</td>
            </tr>
            <tr>
                <th>Customer Name</th>
                <td>Jane Smith</td>
            </tr>
            <tr>
                <th>Payment Type</th>
                <td>Subscription</td>
            </tr>
            <tr>
                <th>Date</th>
                <td>2025-06-02</td>
            </tr>
            <tr>
                <th>Payment Mode</th>
                <td>Credit Card</td>
            </tr>
            <tr>
                <th>Status</th>
                <td>Completed</td>
            </tr>
            <tr>
                <th>Amount</th>
                <td>$129.99</td>
            </tr>
            <tr>
                <th>Currency</th>
                <td>USD</td>
            </tr>
            <tr>
                <th>Reference No.</th>
                <td>SUB-202506-1050</td>
            </tr>
            <tr>
                <th>Description</th>
                <td>June 2025 subscription payment</td>
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
