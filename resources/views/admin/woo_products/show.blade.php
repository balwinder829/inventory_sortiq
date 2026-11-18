@include('admin.layouts.header')



<div class="page-header d-print-none">
    <div class="container-xl mt-3 mb-3 d-flex justify-content-between align-items-center">
        <h2 class="page-title">{{ __('Product Details') }}</h2>
        <a href="{{ route('wc.products') }}" class="btn btn-secondary">
            {{ __('Back') }}
        </a>
    </div>
</div>

<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="page-body">
            <div class="container-xl">

                <div class="row row-cards">

                    <!-- Product Image -->
                    <div class="col-lg-4 mb-3">
                        <div class="card mb-3">
                            <div class="card-body text-center">
                                @if(!empty($product->images))
                                    <img src="{{ $product->images[0]->src }}" alt="{!! $product->name !!}" class="img-fluid" style="max-height: 300px; object-fit: contain;">
                                @else
                                    <img src="{{ asset('assets/img/products/default.webp') }}" alt="No Image" class="img-fluid" style="max-height: 300px; object-fit: contain;">
                                @endif
                            </div>
                        </div>

                        <!-- Product QR Code -->
                        <div class="card text-center shadow-sm border-0 mb-4">
                            <div class="card-body py-4">
                                <h4 class="card-title mb-3 fw-bold text-dark">
                                    {{ __('Product QR Code') }}
                                </h4>
                                <div class="d-flex justify-content-center align-items-center mb-3 p-3 bg-light rounded" style="width: fit-content; margin: 0 auto; border: 1px solid #eee;">
                                    <canvas id="qrcode-canvas"></canvas>
                                </div>
                                <p class="text-muted small mb-4">Scan this QR code with any mobile device to quickly access the product view page.</p>
                                <a href="{{ route('wc.product.print-qr', $product->id) }}" target="_blank" class="btn btn-primary w-100">
                                    <i class="fa fa-print me-1"></i> Print QR Label
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Product Details Table -->
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">{{ __('Product Details') }}</h3>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered card-table table-vcenter text-nowrap">
                                    <tbody>
                                        <tr>
                                            <td>Name</td>
                                            <td>{!! $product->name !!}</td>
                                        </tr>
                                        <tr>
                                            <td>Slug</td>
                                            <td>{{ $product->slug }}</td>
                                        </tr>
                                        <tr>
                                            <td>SKU</td>
                                            <td>{{ $product->sku ?? 'N/A' }}</td>
                                        </tr>

                                        <tr>
                                            <td>Price</td>
                                            <td>
                                               @if(!empty($product->sale_price))
                                                    <span style="text-decoration: line-through; color: #888;">
                                                        @currency($product->regular_price)
                                                    </span>
                                                    &nbsp;
                                                    <span style="color: red; font-weight: bold;">
                                                        @currency($product->sale_price)
                                                    </span>
                                                    <span class="badge bg-danger">On Sale</span>
                                                @else
                                                    @currency($product->price)
                                                @endif

                                            </td>
                                        </tr>

                                        <tr>
                                            <td>Total Sales</td>
                                            <td>{{ $product->total_sales ?? 0 }}</td>
                                        </tr>

                                        <tr>
                                            <td>Stock Status</td>
                                            <td>{{ ucfirst($product->stock_status) }}</td>
                                        </tr>

                                        <tr>
                                            <td>Stock Quantity</td>
                                            <td>{{ $product->stock_quantity ?? 'N/A' }}</td>
                                        </tr>

                                        <tr>
                                            <td>Categories</td>
                                            <td>
                                                @foreach($product->categories as $category)
                                                    <span class="badge bg-primary">{{ $category->name }}</span>
                                                @endforeach
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>Tags</td>
                                            <td>
                                                @foreach($product->tags as $tag)
                                                    <span class="badge bg-secondary">{{ $tag->name }}</span>
                                                @endforeach
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>Description</td>
                                            <td>{!! $product->description !!}</td>
                                        </tr>

                                        <tr>
                                            <td>Short Description</td>
                                            <td>{!! $product->short_description !!}</td>
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
</div>

@include('admin.layouts.footer')

<script src="{{ asset('assets/js/qrious.min.js') }}"></script>
<script>
    (function() {
        new QRious({
            element: document.getElementById('qrcode-canvas'),
            value: "{{ route('wc.product.show', $product->id) }}",
            size: 160,
            padding: 4
        });
    })();
</script>
