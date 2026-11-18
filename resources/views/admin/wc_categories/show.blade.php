@include('admin.layouts.header')

<div class="page-header d-print-none">
    <div class="container-xl mt-3 mb-3 d-flex justify-content-between align-items-center">
        <h2 class="page-title">{{ __('Customer Details') }}</h2>
        <a href="{{ route('wc.customers') }}" class="btn btn-secondary">
            {{ __('Back') }}
        </a>
    </div>
</div>

<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="page-body">
            <div class="container-xl">

                <div class="row row-cards">

                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">{{ __('Customer Details') }}</h3>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered card-table table-vcenter text-nowrap">
                                    <tbody>
                                        <tr>
                                            <td>Name</td>
                                            <td>{{ trim(($data->first_name ?? '') . ' ' . ($data->last_name ?? '')) ?: '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td>Email</td>
                                            <td>{{ $data->email ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td>Created Date</td>
                                            <td>
                                                {{ 
                                                    $data->date_created 
                                                    ? \Carbon\Carbon::parse($data->date_created)->format('d-m-Y') 
                                                    : '-' 
                                                }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Billing Address</td>
                                            <td>
                                                @php
                                                    $billing = $data->billing ?? null;

                                                    if ($billing) {
                                                        $billingAddress = collect([
                                                            $billing->first_name ?? '',
                                                            $billing->last_name ?? '',
                                                            $billing->company ?? '',
                                                            $billing->address_1 ?? '',
                                                            $billing->address_2 ?? '',
                                                            $billing->city ?? '',
                                                            $billing->state ?? '',
                                                            $billing->postcode ?? '',
                                                            $billing->country ?? '',
                                                            $billing->email ? 'Email: ' . $billing->email : '',
                                                            $billing->phone ? 'Phone: ' . $billing->phone : '',
                                                        ])->filter()->implode(', ');
                                                    } else {
                                                        $billingAddress = '-';
                                                    }
                                                @endphp

                                                {{ $billingAddress ?: '-' }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Shipping Address</td>
                                            <td>
                                                @php
                                                    $shipping = $data->shipping ?? null;

                                                    if ($shipping) {
                                                        $shippingAddress = collect([
                                                            $shipping->first_name ?? '',
                                                            $shipping->last_name ?? '',
                                                            $shipping->company ?? '',
                                                            $shipping->address_1 ?? '',
                                                            $shipping->address_2 ?? '',
                                                            $shipping->city ?? '',
                                                            $shipping->state ?? '',
                                                            $shipping->postcode ?? '',
                                                            $shipping->country ?? '',
                                                            $shipping->phone ? 'Phone: ' . $shipping->phone : '',
                                                        ])->filter()->implode(', ');
                                                    } else {
                                                        $shippingAddress = '-';
                                                    }
                                                @endphp

                                                {{ $shippingAddress ?: '-' }}
                                            </td>
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
