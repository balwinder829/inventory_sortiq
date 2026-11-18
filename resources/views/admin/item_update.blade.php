@include('admin.layouts.header')

<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">

        <div class="container" style="max-width: 650px;">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="fw-bold mb-0"><i class="fa fa-pen-to-square me-2"></i> Update Item</h4>

                <a href="{{ url()->previous() }}" class="btn btn-secondary btn-sm">
                    <i class="fa fa-arrow-left me-1"></i> Back
                </a>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Whoops! There were some problems with your input.</strong>
                    <ul class="mt-2 mb-0">
                        @foreach ($errors->all() as $error)
                            <li>- {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-body">

                    <form action="{{ route('items.update', $item->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <input type="hidden" name="store_id" value="{{ $item->store_id }}">

                        <!-- BASIC INFORMATION -->
                        <h5 class="fw-bold mb-3">📦 Basic Information</h5>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Item Name</label>
                            <input type="text" name="name" class="form-control"
                                   value="{{ old('name', $item->name) }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Description</label>
                            <textarea name="description" class="form-control" rows="2">{{ old('description', $item->description) }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Category</label>
                            <input type="text" name="category" class="form-control"
                                   value="{{ old('category', $item->category) }}">
                        </div>

                        <!-- NON-EDITABLE INVENTORY SECTION -->
                        <h5 class="fw-bold mt-4 mb-3">📊 Inventory (Read Only)</h5>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">SKU</label>
                                <input type="text" class="form-control"
                                       value="{{ $item->sku }}" readonly>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Current Stock</label>
                                <input type="text" class="form-control"
                                       value="{{ $item->quantity }}" readonly>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Cost Price (Last Purchase)</label>
                                <input type="text" class="form-control"
                                       value="{{ $item->cost_price }}" readonly>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Average Cost</label>
                                <input type="text" class="form-control"
                                       value="{{ $item->avg_cost }}" readonly>
                            </div>
                        </div>

                        <!-- PRICING (EDITABLE) -->
                        <h5 class="fw-bold mt-4 mb-3">💵 Pricing</h5>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Selling Price</label>
                                <input type="number" step="0.01" name="price" class="form-control"
                                       value="{{ old('price', $item->price) }}">
                            </div>

                            <!-- <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Sale Price (Optional)</label>
                                <input type="number" step="0.01" name="sale_price" class="form-control"
                                       value="{{ old('sale_price', $item->sale_price) }}">
                            </div> -->
                        </div>

                        <!-- Product Detail -->
                        <h5 class="fw-bold mt-4 mb-3">🏷️ Product Details</h5>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Brand</label>
                                <input type="text" name="brand" class="form-control"
                                       value="{{ old('brand', $item->brand) }}">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Weight</label>
                                <input type="text" name="weight" class="form-control"
                                       value="{{ old('weight', $item->weight) }}">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Dimensions</label>
                            <input type="text" name="dimensions" class="form-control"
                                   value="{{ old('dimensions', $item->dimensions) }}">
                        </div>

                        <!-- STATUS -->
                        <h5 class="fw-bold mt-4 mb-3">⚙️ Status</h5>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Status</label>
                            <select name="status" class="form-select">
                                <option value="publish" {{ old('status', $item->status) === 'publish' ? 'selected' : '' }}>Publish</option>
                                <option value="draft" {{ old('status', $item->status) === 'draft' ? 'selected' : '' }}>Draft</option>
                            </select>
                        </div>

                        <!-- ACTION BUTTONS -->
                        <div class="d-flex justify-content-end mt-4">
                            <button class="btn btn-primary me-2">
                                <i class="fa fa-save me-1"></i> Update Item
                            </button>

                            <a href="{{ url()->previous() }}" class="btn btn-secondary">
                                Cancel
                            </a>
                        </div>

                    </form>

                </div>
            </div>

        </div>

    </div>
</div>

@include('admin.layouts.footer')
