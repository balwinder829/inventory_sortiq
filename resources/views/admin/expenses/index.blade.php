@include('admin.layouts.header')

<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">

        {{-- Success/Error Alerts --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bx bx-check-circle me-1"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bx bx-error-circle me-1"></i>
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Title and Header Actions --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold py-1 mb-0">
                <span class="text-muted fw-light">Management /</span> Expenses
            </h4>
            <a href="{{ route('expenses.create') }}" class="btn btn-primary">
                <i class="bx bx-plus me-1"></i> Record New Expense
            </a>
        </div>

        {{-- Metrics Grid & Grand Total --}}
        <div class="row">
            {{-- Category Cards --}}
            <div class="col-lg-8">
                <div class="row">
                    {{-- Operational Expenses --}}
                    <div class="col-md-6 mb-4">
                        <div class="card h-100 shadow-sm border-0">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div class="avatar flex-shrink-0">
                                        <span class="avatar-initial rounded bg-label-primary p-2">
                                            <i class="bx bx-cog fs-3"></i>
                                        </span>
                                    </div>
                                    <span class="badge bg-label-primary rounded-pill">Operational</span>
                                </div>
                                <span class="fw-semibold d-block mb-1 text-muted">Operational Expenses</span>
                                <h3 class="card-title mb-1 text-primary">₹{{ number_format($totals['operational'], 2) }}</h3>
                                <small class="text-muted">Direct business operations</small>
                            </div>
                        </div>
                    </div>

                    {{-- Store Expenses --}}
                    <div class="col-md-6 mb-4">
                        <div class="card h-100 shadow-sm border-0">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div class="avatar flex-shrink-0">
                                        <span class="avatar-initial rounded bg-label-success p-2">
                                            <i class="bx bx-store-alt fs-3"></i>
                                        </span>
                                    </div>
                                    <span class="badge bg-label-success rounded-pill">Store</span>
                                </div>
                                <span class="fw-semibold d-block mb-1 text-muted">Store Expenses</span>
                                <h3 class="card-title mb-1 text-success">₹{{ number_format($totals['store'], 2) }}</h3>
                                <small class="text-muted">Premises & retail costs</small>
                            </div>
                        </div>
                    </div>

                    {{-- Utility Bills --}}
                    <div class="col-md-6 mb-4">
                        <div class="card h-100 shadow-sm border-0">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div class="avatar flex-shrink-0">
                                        <span class="avatar-initial rounded bg-label-warning p-2">
                                            <i class="bx bx-bulb fs-3"></i>
                                        </span>
                                    </div>
                                    <span class="badge bg-label-warning rounded-pill">Utility Bills</span>
                                </div>
                                <span class="fw-semibold d-block mb-1 text-muted">Utility Bills</span>
                                <h3 class="card-title mb-1 text-warning">₹{{ number_format($totals['utility'], 2) }}</h3>
                                <small class="text-muted">Electricity, water, internet</small>
                            </div>
                        </div>
                    </div>

                    {{-- Other Expenses --}}
                    <div class="col-md-6 mb-4">
                        <div class="card h-100 shadow-sm border-0">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div class="avatar flex-shrink-0">
                                        <span class="avatar-initial rounded bg-label-danger p-2">
                                            <i class="bx bx-receipt fs-3"></i>
                                        </span>
                                    </div>
                                    <span class="badge bg-label-danger rounded-pill">Other</span>
                                </div>
                                <span class="fw-semibold d-block mb-1 text-muted">Other Expenses</span>
                                <h3 class="card-title mb-1 text-danger">₹{{ number_format($totals['other'], 2) }}</h3>
                                <small class="text-muted">Misc & one-off payments</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Grand Total & Overview Card --}}
            <div class="col-lg-4 mb-4">
                <div class="card h-100 shadow-sm border-0 bg-light-primary position-relative overflow-hidden" style="background: linear-gradient(135deg, #696cff 0%, #4f52e5 100%); color: #fff;">
                    <div class="card-body d-flex flex-column justify-content-between p-4">
                        <div>
                            <div class="d-flex align-items-center mb-3">
                                <span class="badge bg-white text-primary rounded-pill p-2 me-2">
                                    <i class="bx bx-wallet fs-4"></i>
                                </span>
                                <h5 class="card-title text-white mb-0">Total Ledger</h5>
                            </div>
                            <p class="text-white-50 mb-4">Aggregated spending across scoped stores</p>
                            <h1 class="display-5 fw-bold text-white mb-2">₹{{ number_format($totals['grand_total'], 2) }}</h1>
                            <span class="badge bg-label-light rounded-pill px-3 py-2 text-white">Active Operational Tracking</span>
                        </div>
                        
                        <div class="mt-4 pt-3 border-top border-white-10">
                            <div class="d-flex justify-content-between text-white-50 small">
                                <span>Active Records</span>
                                <span class="text-white fw-bold">{{ $expenses->count() }} entries</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Filter & Data Table --}}
        @if($expenses->isEmpty())
            <div class="card shadow-sm border-0">
                <div class="card-body text-center py-5">
                    <div class="text-muted mb-3">
                        <i class="bx bx-wallet-open display-1 text-primary"></i>
                    </div>
                    <h4>No Expense Records Found</h4>
                    <p class="text-muted">
                        Get started by recording your first store expense.
                    </p>
                    <a href="{{ route('expenses.create') }}" class="btn btn-primary mt-2">
                        <i class="bx bx-plus me-1"></i> Record First Expense
                    </a>
                </div>
            </div>
        @else
            <div class="card shadow-sm border-0">
                <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-3 border-bottom py-3">
                    <h5 class="mb-0">Expense Ledger</h5>
                    
                    {{-- Quick Filters --}}
                    <div class="d-flex flex-wrap gap-2">
                        <select id="categoryFilter" class="form-select w-auto">
                            <option value="">All Categories</option>
                            <option value="operational">Operational</option>
                            <option value="store">Store</option>
                            <option value="utility">Utility</option>
                            <option value="other">Other</option>
                        </select>

                        <input type="text" id="expenseSearch" class="form-control w-auto" placeholder="Search expenses...">
                    </div>
                </div>

                <div class="table-responsive text-nowrap">
                    <table class="table table-hover" id="expensesTable">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Reference No</th>
                                <th>Store</th>
                                <th>Category</th>
                                <th class="text-end">Amount</th>
                                <th>Recorded By</th>
                                <th>Notes</th>
                                <th class="text-center" width="100">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            @foreach($expenses as $expense)
                                <tr>
                                    <td>
                                        <span class="fw-semibold">{{ \Carbon\Carbon::parse($expense->expense_date)->format('M d, Y') }}</span>
                                    </td>
                                    <td>
                                        <code class="text-dark fw-bold">{{ $expense->reference_no ?? 'N/A' }}</code>
                                    </td>
                                    <td>
                                        <span class="badge bg-label-secondary"><i class="bx bx-store me-1"></i>{{ $expense->store->name ?? 'N/A' }}</span>
                                    </td>
                                    <td>
                                        @if($expense->category == 'operational')
                                            <span class="badge bg-label-primary"><i class="bx bx-cog me-1"></i>Operational</span>
                                        @elseif($expense->category == 'store')
                                            <span class="badge bg-label-success"><i class="bx bx-store-alt me-1"></i>Store</span>
                                        @elseif($expense->category == 'utility')
                                            <span class="badge bg-label-warning"><i class="bx bx-bulb me-1"></i>Utility Bills</span>
                                        @else
                                            <span class="badge bg-label-danger"><i class="bx bx-receipt me-1"></i>Other</span>
                                        @endif
                                    </td>
                                    <td class="text-end fw-bold text-dark">
                                        ₹{{ number_format($expense->amount, 2) }}
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ $expense->creator->name ?? 'System' }}</small>
                                    </td>
                                    <td>
                                        <span class="text-wrap d-inline-block" style="max-width: 200px;" title="{{ $expense->notes }}">
                                            {{ Str::limit($expense->notes, 30) ?: '-' }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div class="dropdown">
                                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                                <i class="bx bx-dots-vertical-rounded"></i>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                <a class="dropdown-item" href="{{ route('expenses.edit', $expense->id) }}">
                                                    <i class="bx bx-edit-alt me-1 text-warning"></i> Edit
                                                </a>
                                                <form action="{{ route('expenses.destroy', $expense->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to permanently delete this expense record?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item">
                                                        <i class="bx bx-trash me-1 text-danger"></i> Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

    </div>
</div>

@include('admin.layouts.footer')

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>
$(document).ready(function () {
    if ($('#expensesTable').length) {
        const table = $('#expensesTable').DataTable({
            paging: true,
            searching: true,
            ordering: true,
            responsive: true,
            autoWidth: false,
            dom: 'rtip',
            pageLength: 10,
            columnDefs: [
                { targets: [6, 7], orderable: false }
            ]
        });

        // Category Filter
        $('#categoryFilter').on('change', function () {
            const val = this.value;
            if (val) {
                // filter column 3 (Category)
                table.column(3).search(val, true, false).draw();
            } else {
                table.column(3).search('').draw();
            }
        });

        // Custom Search
        $('#expenseSearch').on('keyup', function () {
            table.search(this.value).draw();
        });
    }
});
</script>
