@include('admin.layouts.header')

<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">

        <div class="container" style="max-width: 650px;">
            
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="fw-bold mb-0"><i class="fa fa-box-open me-2"></i> Add Item</h4>

                <a href="{{ url()->previous() }}" class="btn btn-secondary btn-sm">
                    <i class="fa fa-arrow-left me-1"></i> Back
                </a>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Whoops! Something went wrong.</strong>
                    <ul class="mt-2 mb-0">
                        @foreach ($errors->all() as $error)
                            <li>- {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-body">

                    <form action="{{ route('items.store') }}" method="POST">
                        @csrf

                        <input type="hidden" name="store_id" value="{{ $store->id }}">

                        <!-- BASIC INFO -->
                        <h5 class="fw-bold mb-3">📦 Basic Information</h5>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Item Name</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name') }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Description</label>
                            <textarea name="description" class="form-control" rows="2">{{ old('description') }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Category</label>
                            <input type="text" name="category" class="form-control" value="{{ old('category') }}">
                        </div>

                        <!-- PRICING SECTION -->
                        <h5 class="fw-bold mt-4 mb-3">💵 Pricing</h5>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Cost Price</label>
                                <input type="number" step="0.01" name="cost_price" class="form-control"
                                       value="{{ old('cost_price') }}">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Sale Price</label>
                                <input type="number" step="0.01" name="price" class="form-control"
                                       value="{{ old('price') }}">
                            </div>
                        </div>

                        <!-- STOCK -->
                        <h5 class="fw-bold mt-4 mb-3">📦 Inventory</h5>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Quantity</label>
                            <input type="number" name="quantity" class="form-control"
                                   value="{{ old('quantity', 0) }}">
                        </div>

                        <!-- IDENTIFIERS -->
                        <h5 class="fw-bold mt-4 mb-3">🏷️ Identification</h5>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">SKU</label>
                                <input type="text" name="sku" class="form-control" value="{{ old('sku') }}">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Brand</label>
                                <input type="text" name="brand" class="form-control" value="{{ old('brand') }}">
                            </div>
                        </div>

                        <!-- EXTRA INFO -->
                        <h5 class="fw-bold mt-4 mb-3">📦 Additional Details</h5>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Weight</label>
                                <input type="text" name="weight" class="form-control" value="{{ old('weight') }}">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Dimensions</label>
                                <input type="text" name="dimensions" class="form-control"
                                       value="{{ old('dimensions') }}">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Tags (JSON Format)</label>
                            <input type="text" name="tags" class="form-control"
                                   placeholder='["tag1","tag2"]'
                                   value="{{ old('tags') }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Status</label>
                            <select name="status" class="form-select">
                                <option value="publish" {{ old('status') === 'publish' ? 'selected' : '' }}>Publish</option>
                                <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                            </select>
                        </div>

                        <!-- BUTTONS -->
                        <div class="d-flex justify-content-end mt-4">
                            <button class="btn btn-primary me-2">
                                <i class="fa fa-save me-1"></i> Add Item
                            </button>
                            <a href="{{ url()->previous() }}" class="btn btn-secondary">
                                Cancel
                            </a>
                        </div>

                    </form>
                </div>
            </div>

        </div>

        <div class="content-backdrop fade"></div>
    </div>
</div>

@include('admin.layouts.footer')
