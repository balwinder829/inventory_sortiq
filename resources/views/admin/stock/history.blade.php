@include('admin.layouts.header')

<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">

        <div class="card shadow-sm border-0">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0">
                    <i class="fa fa-clock-rotate-left text-primary"></i> 
                    Stock History — {{ $item->name }}
                </h4>

                <!-- <a href="{{ route('stocks.edit', $item->id) }}" class="btn btn-secondary">
                    <i class="fa fa-arrow-left"></i> Back
                </a> -->
            </div>

            <div class="card-body">

                @if($history->isEmpty())
                    <p class="text-center text-muted py-4">No stock history available.</p>
                @else
                    <table class="table table-bordered table-striped" id="historyTable">
                        <thead class="table-light">
                            <tr>
                                <th>Date</th>
                                <th>Old Qty</th>
                                <th>New Qty</th>
                                <th>Change</th>
                                <th>Cost Price</th>
                                <th>Changed By</th>
                                <th>Reason</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($history as $h)
                                <tr>
                                    <td>{{ date('Y-m-d H:i', strtotime($h->created_at)) }}</td>
                                    <td>{{ $h->old_quantity }}</td>
                                    <td>{{ $h->new_quantity }}</td>

                                    <td class="{{ $h->change_amount >= 0 ? 'text-success' : 'text-danger' }}">
                                        {{ $h->change_amount > 0 ? '+' : '' }}{{ $h->change_amount }}
                                    </td>

                                    <td>@currency($h->cost_price)</td>

                                    <td>
                                        @php 
                                            $u = \App\Models\User::find($h->changed_by); 
                                        @endphp
                                        {{ $u->name ?? 'N/A' }}
                                    </td>

                                    <td>{{ $h->reason ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif

            </div>
        </div>

    </div>
</div>

@include('admin.layouts.footer')

<script>
    new DataTable('#historyTable');
</script>
