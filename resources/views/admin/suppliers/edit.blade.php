@include('admin.layouts.header')

<!-- Content wrapper -->
<div class="content-wrapper">
  <div class="container-xxl flex-grow-1 container-p-y">
    <div class="container" style="width: 75%;">

      <div class="card shadow-lg rounded-3">

        <div class="card-header text-white">
          <h4 class="mb-0">Edit Supplier</h4>
        </div>

        <div class="card-body">

          {{-- Validation Errors --}}
          @if ($errors->any())
              <div class="alert alert-danger">
                  <ul class="mb-0">
                      @foreach ($errors->all() as $error)
                          <li>{{ $error }}</li>
                      @endforeach
                  </ul>
              </div>
          @endif

          <form action="{{ route('suppliers.update', $supplier->id) }}"
                method="POST">

            @csrf
            @method('PUT')

            {{-- =================== Supplier Information =================== --}}
            <h5 class="fw-bold mb-3 border-bottom pb-2">
              Supplier Information
            </h5>

            <div class="row g-3">

              <div class="col-md-6">
                <label class="form-label">Supplier Name*</label>

                <input type="text"
                       name="name"
                       class="form-control"
                       value="{{ old('name', $supplier->name) }}"
                       required>
              </div>

              <div class="col-md-6">
                <label class="form-label">Shop Name</label>

                <input type="text"
                       name="shop_name"
                       class="form-control"
                       value="{{ old('shop_name', $supplier->shop_name) }}">
              </div>

              <div class="col-md-6">
                <label class="form-label">Supplier Type</label>

                <select name="supplier_type" class="form-select">

                  <option value="">Select Type</option>

                  <option value="Wholesaler"
                    {{ old('supplier_type', $supplier->supplier_type) == 'Wholesaler' ? 'selected' : '' }}>
                    Wholesaler
                  </option>

                  <option value="Distributor"
                    {{ old('supplier_type', $supplier->supplier_type) == 'Distributor' ? 'selected' : '' }}>
                    Distributor
                  </option>

                  <option value="Manufacturer"
                    {{ old('supplier_type', $supplier->supplier_type) == 'Manufacturer' ? 'selected' : '' }}>
                    Manufacturer
                  </option>

                  <option value="Retailer"
                    {{ old('supplier_type', $supplier->supplier_type) == 'Retailer' ? 'selected' : '' }}>
                    Retailer
                  </option>

                </select>
              </div>

              <div class="col-md-6">
                <label class="form-label">GST Number</label>

                <input type="text"
                       name="gst_number"
                       class="form-control"
                       value="{{ old('gst_number', $supplier->gst_number) }}">
              </div>

            </div>

            {{-- =================== Contact Information =================== --}}
            <h5 class="fw-bold mb-3 border-bottom pb-2 mt-4">
              Contact Information
            </h5>

            <div class="row g-3">

              <div class="col-md-6">
                <label class="form-label">Phone Number</label>

                <input type="text"
                       name="phone"
                       class="form-control"
                       value="{{ old('phone', $supplier->phone) }}">
              </div>

              <div class="col-md-6">
                <label class="form-label">Alternate Phone</label>

                <input type="text"
                       name="alternate_phone"
                       class="form-control"
                       value="{{ old('alternate_phone', $supplier->alternate_phone) }}">
              </div>

              <div class="col-md-6">
                <label class="form-label">Email Address</label>

                <input type="email"
                       name="email"
                       class="form-control"
                       value="{{ old('email', $supplier->email) }}">
              </div>

              <div class="col-md-6">
                <label class="form-label">Status</label>

                <select name="is_active" class="form-select">

                  <option value="1"
                    {{ old('is_active', $supplier->is_active) == 1 ? 'selected' : '' }}>
                    Active
                  </option>

                  <option value="0"
                    {{ old('is_active', $supplier->is_active) == 0 ? 'selected' : '' }}>
                    Inactive
                  </option>

                </select>
              </div>

              <div class="col-md-12">
                <label class="form-label">Address</label>

                <textarea name="address"
                          class="form-control"
                          rows="2">{{ old('address', $supplier->address) }}</textarea>
              </div>

              <div class="col-md-12">
                <label class="form-label">Notes</label>

                <textarea name="notes"
                          class="form-control"
                          rows="3">{{ old('notes', $supplier->notes) }}</textarea>
              </div>

            </div>

            {{-- =================== Buttons =================== --}}
            <div class="d-flex justify-content-end mt-4">

              <button type="submit" class="btn btn-primary me-2">
                Update Supplier
              </button>

              <a href="{{ route('suppliers.index') }}"
                 class="btn btn-secondary">
                Cancel
              </a>

            </div>

          </form>

        </div>
      </div>

    </div>
  </div>
</div>

<!-- Scripts -->
<script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
<script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
<script src="{{ asset('assets/js/main.js') }}"></script>