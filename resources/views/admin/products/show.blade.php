@include('admin.layouts.header')

<div class="page-header d-print-none">
    <div class="container-xl mb-3">
       <!--  <div class="row g-2 align-items-center mb-3">
            <div class="col">
                <h2 class="page-title">
                    {{ __('Edit Product') }}
                </h2>
            </div>
        </div> -->

        
    </div>
</div>
<div class="content-wrapper">
            <!-- Content -->

            <div class="container-xxl flex-grow-1 container-p-y">
<div class="page-body">
    <div class="container-xl">
        <div class="row row-cards">

            <div class="row">
                <div class="col-lg-4">
                    <div class="card text-center shadow-sm border-0 mb-4">
                        <div class="card-body py-4">
                            <h4 class="card-title mb-3 fw-bold text-dark">
                                {{ __('Product QR Code') }}
                            </h4>
                            <div class="d-flex justify-content-center align-items-center mb-3 p-3 bg-light rounded" style="width: fit-content; margin: 0 auto; border: 1px solid #eee;">
                                <canvas id="qrcode-canvas"></canvas>
                            </div>
                            <p class="text-muted small mb-4">Scan this QR code with any mobile device to quickly access the product view page.</p>
                            <a href="{{ route('products.print-qr', $product->id) }}" target="_blank" class="btn btn-primary w-100">
                                <i class="fa fa-print me-1"></i> Print QR Label
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                {{ __('Product Details') }}
                            </h3>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered card-table table-vcenter text-nowrap datatable">
                                <tbody>
                                    <tr>
                                        <td>Name</td>
                                        <td>{{ $product->name }}</td>
                                    </tr>
                                    <tr>
                                        <td>SKU</td>
                                        <td>{{ $product->sku }}</td>
                                    </tr>
                                     <tr>
                                        <td>Price</td>
                                        <td>@currency($product->price)</td>
                                    </tr>
                                    <tr>
                                        <td><span class="text-secondary">Brand</span></td>
                                        <td>{{ $product->brand }}</td>
                                    </tr>

                                    <tr>
                                        <td>Quantity</td>
                                        <td>{{ $product->quantity }}</td>
                                    </tr>
                                    <tr>
                                        <td>Status</td>
                                        <td>
                                            <span class="badge bg-red-lt">
                                                {{ $product->status }}
                                            </span>
                                        </td>
                                    </tr>                                    
                                    <tr>
                                        <td>{{ __('Notes') }}</td>
                                        <td>{{ $product->description }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                       <!--  -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>
@include('admin.layouts.footer')

<script src="{{ asset('assets/js/qrious.min.js') }}"></script>
<script>
    (function() {
        new QRious({
            element: document.getElementById('qrcode-canvas'),
            value: "{{ route('products.show', $product->id) }}",
            size: 160,
            padding: 4
        });
    })();
</script>