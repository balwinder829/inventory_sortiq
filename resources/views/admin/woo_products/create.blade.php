@include('admin.layouts.header')

<div class="content-wrapper">
  <div class="container-xxl flex-grow-1 container-p-y">
    <div class="container" style="width: 65%;">

      <div class="card shadow-lg rounded-3">
        <div class="card-header text-white">
          <h4 class="mb-0">Add New Product</h4>
        </div>
        <div class="card-body">

          @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
          @endif

          @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
          @endif

          @if ($errors->any())
              <div class="alert alert-danger">
                  <strong>Whoops! Please fix the errors below:</strong>
                  <ul>
                      @foreach ($errors->all() as $error)
                          <li>{{ $error }}</li>
                      @endforeach
                  </ul>
              </div>
          @endif

          <form action="{{ route('wc.product.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- Basic Info --}}
            <div class="mb-3">
              <label>Product Name*</label>
              <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
              @error('name')<small class="text-danger">{{ $message }}</small>@enderror
            </div>

            <div class="mb-3">
              <label>Slug</label>
              <input type="text" name="slug" class="form-control" value="{{ old('slug') }}">
              @error('slug')<small class="text-danger">{{ $message }}</small>@enderror
            </div>

            <div class="mb-3">
              <label>Description</label>
              <textarea name="description" class="form-control" rows="4">{{ old('description') }}</textarea>
              @error('description')<small class="text-danger">{{ $message }}</small>@enderror
            </div>

            <div class="mb-3">
              <label>Short Description</label>
              <textarea name="short_description" class="form-control" rows="4">{{ old('short_description') }}</textarea>
              @error('short_description')<small class="text-danger">{{ $message }}</small>@enderror
            </div>

            {{-- Pricing --}}
            <div class="mb-3">
              <label>SKU</label>
              <input type="text" name="sku" class="form-control" value="{{ old('sku') }}">
            </div>

            <div class="mb-3">
              <label>Regular Price</label>
              <input type="text" name="regular_price" class="form-control" value="{{ old('regular_price') }}">
            </div>

            <div class="mb-3">
              <label>Sale Price</label>
              <input type="text" name="sale_price" class="form-control" value="{{ old('sale_price') }}">
            </div>

            {{-- Stock --}}
            <div class="form-check mb-3">
              <input type="checkbox" name="manage_stock" id="manage_stock" value="1" class="form-check-input" {{ old('manage_stock') ? 'checked' : '' }}>
              <label class="form-check-label" for="manage_stock">Manage Stock?</label>
            </div>

            <div class="mb-3">
              <label>Stock Quantity</label>
              <input type="number" name="stock_quantity" class="form-control" value="{{ old('stock_quantity') }}">
            </div>

            <div class="mb-3">
              <label>Stock Status</label>
              <select name="stock_status" class="form-select">
                <option value="instock" {{ old('stock_status') == 'instock' ? 'selected' : '' }}>In Stock</option>
                <option value="outofstock" {{ old('stock_status') == 'outofstock' ? 'selected' : '' }}>Out of Stock</option>
                <option value="onbackorder" {{ old('stock_status') == 'onbackorder' ? 'selected' : '' }}>On Backorder</option>
              </select>
            </div>

            {{-- Status --}}
            <div class="mb-3">
              <label>Product Status</label>
              <select name="status" class="form-select">
                <option value="publish" {{ old('status') == 'publish' ? 'selected' : '' }}>Publish</option>
                <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
              </select>
            </div>

            {{-- Tax --}}
            <div class="mb-3">
              <label>Tax Status</label>
              <select name="tax_status" class="form-select">
                <option value="taxable" {{ old('tax_status') == 'taxable' ? 'selected' : '' }}>Taxable</option>
                <option value="shipping" {{ old('tax_status') == 'shipping' ? 'selected' : '' }}>Shipping</option>
                <option value="none" {{ old('tax_status') == 'none' ? 'selected' : '' }}>None</option>
              </select>
            </div>

            <div class="mb-3">
              <label>Tax Class</label>
              <input type="text" name="tax_class" class="form-control" value="{{ old('tax_class') }}">
            </div>

            <div class="mb-3">
              <label>Backorders</label>
              <select name="backorders" class="form-select">
                <option value="no" {{ old('backorders') == 'no' ? 'selected' : '' }}>Do not allow</option>
                <option value="notify" {{ old('backorders') == 'notify' ? 'selected' : '' }}>Allow, notify customer</option>
                <option value="yes" {{ old('backorders') == 'yes' ? 'selected' : '' }}>Allow</option>
              </select>
            </div>

            <div class="form-check mb-3">
              <input type="checkbox" name="featured" value="1" class="form-check-input" {{ old('featured') ? 'checked' : '' }}>
              <label class="form-check-label">Featured</label>
            </div>

            {{-- Categories --}}
            <div class="mb-3">
              <label>Categories</label>
              <select name="categories[]" class="form-select" multiple>
                @foreach ($allCategories as $category)
                  <option value="{{ $category->id }}" {{ in_array($category->id, old('categories', [])) ? 'selected' : '' }}>
                    {{ $category->name }}
                  </option>
                @endforeach
              </select>
              <small class="text-muted">Select one or more categories</small>
            </div>

           <!--  <div class="mb-3">
            <label>Product Image</label>
            <input type="file" name="image" class="form-control" accept="image/*">
            @error('image')<small class="text-danger">{{ $message }}</small>@enderror
          </div> -->

            {{-- Submit --}}
            <div class="d-flex justify-content-end">
              <button type="submit" class="btn btn-primary me-2">Add Product</button>
              <a href="{{ route('wc.products') }}" class="btn btn-secondary">Cancel</a>
            </div>

          </form>

        </div>
      </div>
    </div>
  </div>
</div>
