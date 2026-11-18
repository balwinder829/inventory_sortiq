@include('admin.layouts.header')
<style>
  .help-btn {
    position: absolute;
    top: 15px;
    right: 15px;
    padding: 8px 16px;
    background: linear-gradient(145deg, #00d4ff, #00bfe6);
    color: #00334d;
    border-radius: 30px;
    border: none;
    display: flex;
    align-items: center;
    gap: 6px;
    font-weight: 600;
    font-size: 14px;
    cursor: pointer;
    box-shadow: 0 6px 18px rgba(0, 200, 255, 0.35);
    transition: 0.25s ease;
}

.help-btn i {
    font-size: 18px;
    color: #00334d;
}

.help-btn:hover {
    transform: scale(1.07);
    box-shadow: 0 10px 28px rgba(0, 200, 255, 0.45);
}

</style>

<!-- Content wrapper -->
<div class="content-wrapper">
  <div class="container-xxl flex-grow-1 container-p-y">
    <div class="container" style="width: 65%;">

      <div class="card shadow-lg rounded-3 position-relative">

        <!-- HELP BUTTON (Top Right) -->
     <button type="button"
    class="help-btn"
    data-bs-toggle="modal"
    data-bs-target="#sampleModal"
    title="Click to view sample file & import instructions">
    <i class="fa fa-question-circle"></i> HELP
</button>




        <div class="card-header text-white">
          <h4 class="mb-0">Import</h4>
        </div>

        <div class="card-body">

          {{-- Success --}}
          @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
              <strong>Success!</strong> {{ session('success') }}
              <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
          @endif

          {{-- Error --}}
          @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
              <strong>Error:</strong> {{ session('error') }}
              <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
          @endif

          {{-- Validation Errors --}}
          @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
              <ul class="mb-0">
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
              <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
          @endif

          {{-- Error Log --}}
          @if(session('logFile'))
            <a href="{{ route('import.log', session('logFile')) }}" class="btn btn-warning mt-3">
              Download Error Log
            </a>
          @endif


          <!-- IMPORT FORM -->
          <form action="{{ route('items.import') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
              <label class="fw-bold">Import File *</label>
              <input type="file" name="file" class="form-control" required>
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
</div>



<!-- SAMPLE FILE MODAL -->
<div class="modal fade" id="sampleModal" tabindex="-1">
  <div class="modal-dialog modal-md modal-dialog-centered">
    <div class="modal-content rounded-3 shadow-lg">

      <div class="modal-header text-white">
        <h5 class="modal-title">Sample Import File</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">

        <p class="mb-1"><strong>Download sample format:</strong></p>

        <a href="{{ asset('assets/inventory_sample_products.xlsx') }}" 
           class="btn btn-primary btn-sm mb-3">
          <i class="fa fa-download"></i> Download Sample File
        </a>

        <hr>

        <h6 class="fw-bold">Required Columns:</h6>

        <ul>
          <li><code>name</code></li>
          <li><code>store_id</code></li>
          <li><code>sku</code></li>
          <li><code>price</code></li>
          <li><code>cost_price</code></li>
          <li><code>quantity</code></li>
        </ul>

         <h6 class="fw-bold">Optional Columns:</h6>

        <ul>
          
          <li><code>category</code></li>
          <li><code>description</code></li>
        </ul>

        <p class="text-muted">Make sure your CSV follows the same headers to avoid errors.</p>
      </div>

      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div>


<!-- Scripts -->
<script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/popper/popper.js') }}"></script>
<script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
<script src="{{ asset('assets/vendor/js/menu.js') }}"></script>
<script src="{{ asset('assets/js/main.js') }}"></script>

<script>
    // Enable tooltips
    // var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    // tooltipTriggerList.map(function (tooltipTriggerEl) {
    //   return new bootstrap.Tooltip(tooltipTriggerEl)
    // })
    // var tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'))
    //   tooltipTriggerList.map(function (tooltipTriggerEl) {
    //       return new bootstrap.Tooltip(tooltipTriggerEl)
    //   })
var tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'))
tooltipTriggerList.map(function (tooltipTriggerEl) {
  return new bootstrap.Tooltip(tooltipTriggerEl)
})

</script>
