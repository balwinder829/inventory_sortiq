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
            Payment Type:
            <select id="typeFilter" class="form-select d-inline w-auto">
                <option value="">All</option>
                <option value="Invoice">Invoice</option>
                <option value="Subscription">Subscription</option>
                <option value="Refund">Refund</option>
            </select>
        </label>
        <label class="mb-0">
            Payment Mode:
            <select id="modeFilter" class="form-select d-inline w-auto">
                <option value="">All</option>
                <option value="Credit Card">Credit Card</option>
                <option value="PayPal">PayPal</option>
                <option value="Bank Transfer">Bank Transfer</option>
                <option value="UPI">UPI</option>
                <option value="Debit Card">Debit Card</option>
            </select>
        </label>
    </div>

    <!-- Right side: Global search -->
    <div>
        <label class="mb-0">
            Search:
            <input type="text" id="globalSearch" class="form-control d-inline w-auto" placeholder="Search payments...">
        </label>
    </div>
</div>


                <table class="table table-striped table-bordered" id="paymentTable">
                    <thead>
                        <tr>
                            <th>Payment ID</th>
                            <th>Customer Name</th>
                            <th>Payment Type</th>
                            <th>Date</th>
                            <th>Payment Mode</th>
                            <th>Status</th>
                            <th>Amount</th>
                            <th>Currency</th>
                            <th>Reference No.</th>
                            <th>Description</th>
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
    const names = ["John Doe", "Jane Smith", "Emily Clark", "Michael Lee", "Sarah Johnson"];
    const types = ["Invoice", "Subscription", "Refund"];
    const modes = ["Credit Card", "PayPal", "Bank Transfer", "UPI", "Debit Card"];
    const statuses = ["Completed", "Pending", "Failed"];
    const currencies = ["USD"];
    const tbody = $('#paymentTable tbody');
    const baseViewUrl = "{{ route('payments.show', ':id') }}";
    
    for (let i = 1001; i <= 1060; i++) {
        const id = i;
        const name = names[i % names.length];
        const type = types[i % types.length];
        const mode = modes[i % modes.length];
        const status = statuses[i % statuses.length];
        const currency = currencies[i % currencies.length];
        const date = `2025-06-${String((i % 30) + 1).padStart(2, '0')}`;
        const amount = `$${(Math.random() * 200 + 50).toFixed(2)}`;
        const ref = `${type.substring(0, 3).toUpperCase()}-202506-${i}`;
        const desc = `${type} payment by ${name}`;
        const viewUrl = "{{ route('payments.show', ':id') }}";

        const row = `
            <tr>
                <td>PMT-${i}</td>
                <td>${name}</td>
                <td>${type}</td>
                <td>${date}</td>
                <td>${mode}</td>
                <td>${status}</td>
                <td>${amount}</td>
                <td>${currency}</td>
                <td>${ref}</td>
                <td>${desc}</td>
               <td>
                <a href="${baseViewUrl.replace(':id', i)}" title="View Payment" style="color: red;">
                    <i class="fa fa-eye"></i>&nbsp;
                </a>
            </td>
            </tr>`;
        tbody.append(row);
    }

    // ✅ Now initialize DataTable AFTER rows are added
    const table = $('#paymentTable').DataTable({
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

    // Column filters
    $('#typeFilter').on('change', function () {
        table.column(2).search(this.value).draw(); // Payment Type
    });

    $('#modeFilter').on('change', function () {
        table.column(4).search(this.value).draw(); // Payment Mode
    });
});
</script>

