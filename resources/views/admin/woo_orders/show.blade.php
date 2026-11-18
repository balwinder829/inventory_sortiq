@include('admin.layouts.header')

<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="page-body">
            <div class="container-xl">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title mb-0">{{ __('Order Details') }}</h3>
                        <a href="{{ route('wc.orders') }}" class="btn btn-secondary">{{ __('Back') }}</a>
                    </div>

                    <div class="card-body">
                        {{-- Flash messages --}}
                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif
                        @if(session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif

                        <div class="row row-cards mb-3">
                            <div class="col">
                                <label class="form-label required">{{ __('Order Date') }}</label>
                                <input type="text"
                                       class="form-control"
                                       value="{{ \Carbon\Carbon::parse($order->date_created)->format('d-m-Y') }}"
                                       disabled>
                            </div>

                            <div class="col">
                                <label class="form-label required">{{ __('Invoice No.') }}</label>
                                <input type="text" class="form-control" value="{{ $order->id }}" disabled>
                            </div>

                            <div class="col">
                                <label class="form-label required">{{ __('Customer') }}</label>
                                <input type="text" class="form-control" value="{{ $order->billing->first_name }} {{ $order->billing->last_name }}" disabled>
                            </div>

                            <div class="col">
                                <label class="form-label required">{{ __('Payment Type') }}</label>
                                <input type="text" class="form-control" value="{{ $order->payment_method_title ?? $order->payment_method }}" disabled>
                            </div>

                            {{-- ✅ Updated Order Status --}}
                            <div class="col">
                                <label class="form-label required">{{ __('Order Status') }}</label>
                                @if($order->status !== 'completed')
                                    <form action="{{ route('wc.order.updateStatus', $order->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')

                                        <select name="status" class="form-select" onchange="this.form.submit()">
                                            <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Processing</option>
                                            <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>Completed</option>
                                            <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                            <option value="refunded" {{ $order->status === 'refunded' ? 'selected' : '' }}>Refunded</option>
                                        </select>
                                    </form>
                                @else
                                    <span class="badge bg-success">{{ ucfirst($order->status) }}</span>
                                @endif
                            </div>
                        </div>

                        {{-- Order Items Table --}}
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered align-middle">
                                <thead class="thead-light">
                                    <tr>
                                        <th class="align-middle text-center">No.</th>
                                        <th class="align-middle text-center">Photo</th>
                                        <th class="align-middle text-center">Product Name</th>
                                        <th class="align-middle text-center">SKU</th>
                                        <th class="align-middle text-center">Quantity</th>
                                        <th class="align-middle text-center">Price</th>
                                        <th class="align-middle text-center">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                   @foreach ($order->line_items as $index => $item)
                                        @php
                                            $product = $products[$item->product_id] ?? null;
                                            $hasImage = $product && !empty($product->images[0]->src);
                                            $img = $hasImage ? $product->images[0]->src : null;
                                            $sku = $product->sku ?? 'N/A';
                                        @endphp
                                        <tr>
                                            <td class="align-middle text-center">{{ $index + 1 }}</td>
                                            <td class="align-middle text-center">
                                                @if($hasImage)
                                                    <div style="max-height: 80px; max-width: 80px; overflow: hidden;">
                                                        <img class="img-fluid" src="{{ $img }}" style="object-fit: contain; width: 100%; height: 100%;">
                                                    </div>
                                                @endif
                                            </td>
                                            <td class="align-middle text-center">{{ $item->name }}</td>
                                            <td class="align-middle text-center">{{ $sku }}</td>
                                            <td class="align-middle text-center">{{ $item->quantity }}</td>
                                            <td class="align-middle text-center"> @currency($item->price)</td>
                                            <td class="align-middle text-center"> @currency($item->total)</td>
                                        </tr>
                                    @endforeach

                                    {{-- Totals row --}}
                                    <tr>
                                        <td colspan="6" class="text-end">Total</td>
                                        <td class="text-center">@currency($order->total)</td>
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
