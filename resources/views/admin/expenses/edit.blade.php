@include('admin.layouts.header')

<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">

        {{-- Breadcrumb and Navigation --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold py-1 mb-0">
                <span class="text-muted fw-light">Expenses /</span> Edit Expense
            </h4>
            <a href="{{ route('expenses.index') }}" class="btn btn-outline-secondary">
                <i class="bx bx-arrow-back me-1"></i> Back to Ledger
            </a>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-7">
                <div class="card shadow-sm border-0">
                    <div class="card-header border-bottom d-flex align-items-center py-3">
                        <i class="bx bx-edit-alt fs-4 text-warning me-2"></i>
                        <h5 class="card-title mb-0">Modify Expense Entry</h5>
                    </div>
                    
                    <div class="card-body pt-4">
                        {{-- Errors block --}}
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="bx bx-error-circle me-1"></i>
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('expenses.update', $expense->id) }}">
                            @csrf
                            @method('PUT')

                            {{-- Date Input --}}
                            <div class="mb-3">
                                <label for="expense_date" class="form-label fw-semibold">Expense Date <span class="text-danger">*</span></label>
                                <div class="input-group input-group-merge">
                                    <span class="input-group-text"><i class="bx bx-calendar"></i></span>
                                    <input type="date" id="expense_date" name="expense_date" class="form-control" 
                                           value="{{ old('expense_date', \Carbon\Carbon::parse($expense->expense_date)->format('Y-m-d')) }}" required>
                                </div>
                                <div class="form-text">Choose the transaction date.</div>
                            </div>

                            {{-- Store Select --}}
                            <div class="mb-3">
                                <label for="store_id" class="form-label fw-semibold">Target Store <span class="text-danger">*</span></label>
                                <div class="input-group input-group-merge">
                                    <span class="input-group-text"><i class="bx bx-store"></i></span>
                                    <select id="store_id" name="store_id" class="form-select" required>
                                        <option value="" disabled>Select Store</option>
                                        @foreach($stores as $store)
                                            <option value="{{ $store->id }}" {{ old('store_id', $expense->store_id) == $store->id ? 'selected' : '' }}>
                                                {{ $store->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-text">The store to which this expense will be billed.</div>
                            </div>

                            <div class="row">
                                {{-- Category Input --}}
                                <div class="col-md-6 mb-3">
                                    <label for="category" class="form-label fw-semibold">Expense Category <span class="text-danger">*</span></label>
                                    <select id="category" name="category" class="form-select" required>
                                        <option value="" disabled>Choose Category</option>
                                        <option value="operational" {{ old('category', $expense->category) == 'operational' ? 'selected' : '' }}>Operational expenses</option>
                                        <option value="store" {{ old('category', $expense->category) == 'store' ? 'selected' : '' }}>Store expenses</option>
                                        <option value="utility" {{ old('category', $expense->category) == 'utility' ? 'selected' : '' }}>Utility bills</option>
                                        <option value="other" {{ old('category', $expense->category) == 'other' ? 'selected' : '' }}>Other expenses</option>
                                    </select>
                                </div>

                                {{-- Amount Input --}}
                                <div class="col-md-6 mb-3">
                                    <label for="amount" class="form-label fw-semibold">Amount (₹) <span class="text-danger">*</span></label>
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text">₹</span>
                                        <input type="number" id="amount" name="amount" class="form-control" 
                                               placeholder="0.00" step="0.01" min="0.01" value="{{ old('amount', $expense->amount) }}" required>
                                    </div>
                                </div>
                            </div>

                            {{-- Reference Number Input --}}
                            <div class="mb-3">
                                <label for="reference_no" class="form-label fw-semibold">Reference No. <small class="text-muted">(Optional)</small></label>
                                <div class="input-group input-group-merge">
                                    <span class="input-group-text"><i class="bx bx-receipt"></i></span>
                                    <input type="text" id="reference_no" name="reference_no" class="form-control" 
                                           placeholder="e.g. INV-10023, REC-48293" value="{{ old('reference_no', $expense->reference_no) }}">
                                </div>
                                <div class="form-text">Invoice, bill, or receipt reference number.</div>
                            </div>

                            {{-- Notes Input --}}
                            <div class="mb-4">
                                <label for="notes" class="form-label fw-semibold">Notes & Description <small class="text-muted">(Optional)</small></label>
                                <textarea id="notes" name="notes" class="form-control" rows="3" 
                                          placeholder="Provide descriptive details regarding this expenditure...">{{ old('notes', $expense->notes) }}</textarea>
                            </div>

                            {{-- Form Submission Actions --}}
                            <div class="d-flex justify-content-end gap-2 border-top pt-3">
                                <a href="{{ route('expenses.index') }}" class="btn btn-label-secondary">Cancel</a>
                                <button type="submit" class="btn btn-warning">
                                    <i class="bx bx-save me-1"></i> Save Changes
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@include('admin.layouts.footer')
