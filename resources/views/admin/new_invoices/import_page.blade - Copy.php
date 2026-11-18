@include('admin.layouts.header')
<!-- Content wrapper -->
<div class="content-wrapper">
  <div class="container-xxl flex-grow-1 container-p-y">
    <div class="container" style="width: 65%;">

      <div class="card shadow-lg rounded-3">
        <div class="card-header text-white">
          <h4 class="mb-0">Import</h4>
        </div>
        <div class="card-body">

          @if ($errors->any())
              <div class="alert alert-danger">
                  <strong>Whoops! There were some problems with your input.</strong>
                  <ul>
                      @foreach ($errors->all() as $error)
                          <li>{{ $error }}</li>
                      @endforeach
                  </ul>
              </div>
          @endif

          <form action="{{ route('items.import') }}" method="POST"  enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
              <label>Import*</label>
              <input type="file" name="file" class="form-control" value="{{ old('file') }}" placeholder="Quantity"  required>
              @error('file')
                <small class="text-danger">{{ $message }}</small>
              @enderror
            </div>

            <div class="d-flex justify-content-end">
              <button class="btn btn-primary me-2">Import Products</button>
              <a href="{{ url()->previous() }}" class="btn btn-secondary">Cancel</a>
            </div>
          </form>

        </div>
      </div>

    </div>

    <div class="content-backdrop fade"></div>
  </div>
  <!-- Content wrapper -->
</div>
<!-- / Layout page -->
</div>

<!-- Overlay -->
</div>
</div>
<!-- / Layout wrapper -->

<script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/popper/popper.js') }}"></script>
<script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
<script src="{{ asset('assets/vendor/js/menu.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>
<script src="{{ asset('assets/js/main.js') }}"></script>
<script src="{{ asset('assets/js/dashboards-analytics.js') }}"></script>
<script async defer src="https://button
