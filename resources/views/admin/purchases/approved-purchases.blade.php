@include('admin.layouts.header')

<div class="content-wrapper">
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- <div class="card"> -->
            <div class="card-header">
                <div>
                    <h3 class="card-title">
                        {{ __('Purchases: Approved') }}
                    </h3>
                </div>
                    <div class="d-flex justify-content-end align-items-center mb-3">


                       
                        <div>
                            <label>Search:
                                <input type="text" id="globalSearch" class="form-control d-inline w-auto" placeholder="Search purchase...">
                            </label>
                        </div>
                    </div>

               <!--  <div class="card-actions">
                    <a href="{{ route('purchases.create') }}" class="btn btn-icon btn-outline-success">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-plus" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>
                    </a>
                </div> -->
            </div>
            <div class="table-responsive py-3 px-4">
                <table class="table table-bordered card-table table-vcenter text-nowrap datatable" id="purchases">
                    <thead class="thead-light">
                        <tr>
                            <th scope="col" class="align-middle text-center w-1">No.</th>
                            <th scope="col" class="align-middle text-center">Purchase</th>
                            <th scope="col" class="align-middle text-center">Supplier</th>
                            <th scope="col" class="align-middle text-center">Date</th>
                            <th scope="col" class="align-middle text-center">Total</th>
                            <th scope="col" class="align-middle text-center">Status</th>
                            <th scope="col" class="align-middle text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($purchases as $purchase)
                        <tr>
                            <td class="align-middle text-center">
                                {{ $loop->iteration }}
                            </td>
                            <td class="align-middle text-center">
                                {{ $purchase->purchase_no }}
                            </td>
                            <td class="align-middle">
                                {{ $purchase->supplier->name }}
                            </td>
                            <td class="align-middle text-center">
                                {{ $purchase->purchase_date ? $purchase->purchase_date->format('d-m-Y') : 'N/A' }}
                            </td>
                            <td class="align-middle text-center">
                                {{ Number::currency($purchase->total_amount, 'USD') }}
                            </td>
                            <td class="align-middle text-center">
                                <span class="btn btn-{{ $purchase->purchase_status == 0 ? 'warning' : 'success' }} btn-sm text-uppercase">{{ $purchase->purchase_status == 0 ? 'pending' : 'approved' }}</span>
                            </td>
                            <td class="align-middle text-center">
                                 <a href="{{ route('purchases.show', $purchase) }}" title="View Purchase" style="color: red;">
                            <i class="fa fa-eye"></i>&nbsp;
                        </a>
                                <!-- <a href="{{ route('purchases.show', $purchase) }}" class="btn btn-icon btn-outline-info">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-eye" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" /></svg>
                                </a> -->
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                {{--- ---}}
            </div>
        </div>
    </div>
<!-- </div> -->
 
<link
  href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
  rel="stylesheet"
/>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
@include('admin.layouts.footer')
{{-- Before closing </body> --}}
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>
$(document).ready(function () {
    const table = $('#purchases').DataTable({
        paging: true,
        searching: true,
        ordering: true,
        responsive: true,
        autoWidth: false,
        dom: 'lrtip'
    });
 


    // Global search
    $('#globalSearch').on('keyup', function () {
        table.search(this.value).draw();
    });
});
</script>


