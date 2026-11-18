@include('admin.layouts.header')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="page-body">
            <div class="container-xl">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
                    <!-- Left side: Filters -->
                    <div class="d-flex gap-2 flex-wrap">
                        <label class="mb-0">
                            Status:
                            <select id="statusFilter" class="form-select d-inline w-auto">
                                <option value="">All</option>
                                <option value="Paid">Paid</option>
                                <option value="Pending">Pending</option>
                                <option value="Unpaid">Unpaid</option>
                            </select>
                        </label>

                        <label class="mb-0">
                            Product:
                            <select id="productFilter" class="form-select d-inline w-auto">
                                <option value="">All</option>
                                <option value="Laptop">Laptop</option>
                                <option value="Keyboard">Keyboard</option>
                                <option value="Mouse">Mouse</option>
                                <option value="Monitor">Monitor</option>
                                <option value="Tablet">Tablet</option>
                            </select>
                        </label>

                    </div>


                    <!-- Right side: Global search -->
                    <div>
                        <label class="mb-0">
                            Search:
                            <input type="text" id="globalSearch" class="form-control d-inline w-auto" placeholder="Search sales...">
                        </label>
                    </div>
                </div>

                <table class="table table-striped table-bordered" id="salesTable">
                    <thead>
                        <tr>
                            <th>Invoice No</th>
                            <th>Date</th>
                            <th>Customer Name</th>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Total ($)</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<link
  href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
  rel="stylesheet"
/>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
@include('admin.layouts.footer')
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>
$(document).ready(function () {
    const customers = ["John Doe", "Jane Smith", "Emily Clark", "Michael Lee", "Sarah Johnson"];
    const products = ["Laptop", "Keyboard", "Mouse", "Monitor", "Tablet"];
    const statuses = ["Paid", "Pending", "Unpaid"];
    const tbody = $('#salesTable tbody');
    const baseViewUrl = "{{ route('sales.show', ':id') }}";

    for (let i = 1001; i <= 1070; i++) {
        const invoice = `INV-${i}`;
        const date = `2025-06-${String((i % 30) + 1).padStart(2, '0')}`;
        const customer = customers[i % customers.length];
        const product = products[i % products.length];
        const quantity = Math.floor(Math.random() * 5) + 1;
        const total = (quantity * (Math.random() * 200 + 50)).toFixed(2);
        const status = statuses[i % statuses.length];
        const viewUrl = baseViewUrl.replace(':id', i);

        tbody.append(`
            <tr>
                <td>${invoice}</td>
                <td>${date}</td>
                <td>${customer}</td>
                <td>${product}</td>
                <td>${quantity}</td>
                <td>$${total}</td>
                <td>${status}</td>
                <td>
                    <a href="${viewUrl}" title="View Sale" style="color: red;">
                        <i class="fa fa-eye"></i>
                    </a>
                </td>
            </tr>
        `);
    }

    const table = $('#salesTable').DataTable({
        paging: true,
        ordering: true,
        responsive: true,
        autoWidth: false,
        dom: 'lrtip'
    });

    // Global search
    $('#globalSearch').on('keyup', function () {
        table.search(this.value).draw();
    });

    // Status filter
    // Status filter (exact match using regex)
    $('#statusFilter').on('change', function () {
        const val = this.value;
        if (val) {
            table.column(6).search('^' + val + '$', true, false).draw();
        } else {
            table.column(6).search('', true, false).draw();
        }
    });

    // Product filter (exact match using regex)
    $('#productFilter').on('change', function () {
        const val = this.value;
        if (val) {
            table.column(3).search('^' + val + '$', true, false).draw();
        } else {
            table.column(3).search('', true, false).draw();
        }
    });


});
</script>
