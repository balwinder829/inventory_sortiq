@include('admin.layouts.header')

<div class="content-wrapper">
  <div class="container-xxl flex-grow-1 container-p-y">
    <div class="container" style="width: 65%;">

      <div class="card shadow-lg rounded-3">
        <div class="card-header text-white">
          <h4 class="mb-0">Edit Product - {{ $product->name }}</h4>
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

          <form action="{{ route('wc.product.update', $product->id) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- Basic Info --}}
            <div class="mb-3">
              <label>Product Name*</label>
              <input type="text" name="name" class="form-control" value="{!! old('name', $product->name) !!}" required>
              @error('name')<small class="text-danger">{{ $message }}</small>@enderror
            </div>

            <div class="mb-3">
              <label>Slug</label>
              <input type="text" name="slug" class="form-control" value="{{ old('slug', $product->slug) }}">
              @error('slug')<small class="text-danger">{{ $message }}</small>@enderror
            </div>

            <div class="mb-3">
              <label>Description</label>
              <textarea name="description" class="form-control" rows="4">{{ strip_tags(old('description', $product->description)) }}</textarea>

              @error('description')<small class="text-danger">{{ $message }}</small>@enderror
            </div>

            <div class="mb-3">
              <label>Short Description</label>
              <textarea name="description" class="form-control" rows="4">{{ strip_tags(old('short_description', $product->short_description)) }}</textarea>

              @error('short_description')<small class="text-danger">{{ $message }}</small>@enderror
            </div>

            {{-- Pricing --}}
            <div class="mb-3">
              <label>SKU</label>
              <input type="text" name="sku" class="form-control" value="{{ old('sku', $product->sku) }}">
              @error('sku')<small class="text-danger">{{ $message }}</small>@enderror
            </div>

            <div class="mb-3">
              <label>Regular Price</label>
              <input type="text" name="regular_price" class="form-control" value="{{ old('regular_price', $product->regular_price) }}">
              @error('regular_price')<small class="text-danger">{{ $message }}</small>@enderror
            </div>

            <div class="mb-3">
              <label>Sale Price</label>
              <input type="text" name="sale_price" class="form-control" value="{{ old('sale_price', $product->sale_price) }}">
              @error('sale_price')<small class="text-danger">{{ $message }}</small>@enderror
            </div>

            {{-- Stock Management --}}
            <div class="form-check mb-3">
              <input type="checkbox" name="manage_stock" id="manage_stock" value="1" class="form-check-input" {{ old('manage_stock', $product->manage_stock) ? 'checked' : '' }}>
              <label class="form-check-label" for="manage_stock">Manage Stock?</label>
            </div>

            <div class="mb-3">
              <label>Stock Quantity</label>
              <input type="number" name="stock_quantity" class="form-control" value="{{ old('stock_quantity', $product->stock_quantity) }}">
              @error('stock_quantity')<small class="text-danger">{{ $message }}</small>@enderror
            </div>

            <div class="mb-3">
              <label>Stock Status</label>
              <select name="stock_status" class="form-select">
                <option value="instock" {{ old('stock_status', $product->stock_status) == 'instock' ? 'selected' : '' }}>In Stock</option>
                <option value="outofstock" {{ old('stock_status', $product->stock_status) == 'outofstock' ? 'selected' : '' }}>Out of Stock</option>
                <option value="onbackorder" {{ old('stock_status', $product->stock_status) == 'onbackorder' ? 'selected' : '' }}>On Backorder</option>
              </select>
              @error('stock_status')<small class="text-danger">{{ $message }}</small>@enderror
            </div>

            <div class="mb-3">
            <label>Product Status</label>
            <select name="status" class="form-select">
              <option value="publish" {{ old('status', $product->status) == 'publish' ? 'selected' : '' }}>Publish</option>
              <option value="draft" {{ old('status', $product->status) == 'draft' ? 'selected' : '' }}>Draft</option>
              <option value="pending" {{ old('status', $product->status) == 'pending' ? 'selected' : '' }}>Pending</option>
              
            </select>
            @error('status')<small class="text-danger">{{ $message }}</small>@enderror
          </div>

            {{-- Flags --}}
           <!--  <div class="form-check mb-3">
              <input type="checkbox" name="virtual" id="virtual" value="1" class="form-check-input" {{ old('virtual', $product->virtual) ? 'checked' : '' }}>
              <label class="form-check-label" for="virtual">Virtual</label>
            </div> -->

           <!--  <div class="form-check mb-3">
              <input type="checkbox" name="downloadable" id="downloadable" value="1" class="form-check-input" {{ old('downloadable', $product->downloadable) ? 'checked' : '' }}>
              <label class="form-check-label" for="downloadable">Downloadable</label>
            </div>
 -->
           <!--  <div class="mb-3">
              <label>Download Limit</label>
              <input type="number" name="download_limit" class="form-control" value="{{ old('download_limit', $product->download_limit) }}">
            </div>

            <div class="mb-3">
              <label>Download Expiry (days)</label>
              <input type="number" name="download_expiry" class="form-control" value="{{ old('download_expiry', $product->download_expiry) }}">
            </div> -->

            {{-- Tax --}}
            <div class="mb-3">
              <label>Tax Status</label>
              <select name="tax_status" class="form-select">
                <option value="taxable" {{ old('tax_status', $product->tax_status) == 'taxable' ? 'selected' : '' }}>Taxable</option>
                <option value="shipping" {{ old('tax_status', $product->tax_status) == 'shipping' ? 'selected' : '' }}>Shipping</option>
                <option value="none" {{ old('tax_status', $product->tax_status) == 'none' ? 'selected' : '' }}>None</option>
              </select>
            </div>

            <div class="mb-3">
              <label>Tax Class</label>
              <input type="text" name="tax_class" class="form-control" value="{{ old('tax_class', $product->tax_class) }}">
            </div>

            <div class="mb-3">
              <label>Backorders</label>
              <select name="backorders" class="form-select">
                <option value="no" {{ old('backorders', $product->backorders) == 'no' ? 'selected' : '' }}>Do not allow</option>
                <option value="notify" {{ old('backorders', $product->backorders) == 'notify' ? 'selected' : '' }}>Allow, but notify customer</option>
                <option value="yes" {{ old('backorders', $product->backorders) == 'yes' ? 'selected' : '' }}>Allow</option>
              </select>
            </div>

            {{-- Featured --}}
            <div class="form-check mb-3">
              <input type="checkbox" name="featured" id="featured" value="1" class="form-check-input" {{ old('featured', $product->featured) ? 'checked' : '' }}>
              <label class="form-check-label" for="featured">Featured</label>
            </div>

            {{-- Sale Dates --}}
            @php
              $sale_start = property_exists($product, 'sale_start') && $product->sale_start ? \Carbon\Carbon::parse($product->sale_start)->format('Y-m-d\TH:i') : '';
              $sale_end = property_exists($product, 'sale_end') && $product->sale_end ? \Carbon\Carbon::parse($product->sale_end)->format('Y-m-d\TH:i') : '';
            @endphp
           <!--  <div class="mb-3">
              <label>Sale Start Date</label>
              <input type="datetime-local" name="sale_start" class="form-control" value="{{ old('sale_start', $sale_start) }}">

            </div>

            <div class="mb-3">
              <label>Sale End Date</label>
              <input type="datetime-local" name="sale_end" class="form-control" value="{{ old('sale_end', $sale_end) }}">

            </div> -->

            {{-- JSON Fields (hidden inputs or textarea for now) --}}
            <!-- <div class="mb-3">
              <label>Images (JSON)</label>
              <textarea name="images" class="form-control" rows="3">{{ old('images', json_encode($product->images, JSON_PRETTY_PRINT)) }}</textarea>

              <small class="text-muted">JSON format</small>
            </div> -->

             

            @php
                // Extract selected category IDs from the product
                $selectedCategoryIds = collect($product->categories ?? [])->pluck('id')->toArray();
            @endphp

            <div class="mb-3">
                <label>Categories</label>
                <select name="categories[]" class="form-select" multiple>
                    @foreach ($allCategories as $category)
                        <option value="{{ $category->id }}"
                            {{ in_array($category->id, old('categories', $selectedCategoryIds)) ? 'selected' : '' }}>
                            {!! $category->name !!}
                        </option>
                    @endforeach
                </select>
                <small class="text-muted">Hold Ctrl (Windows) or Cmd (Mac) to select multiple categories</small>
            </div>


            @if (!empty($product->images) && is_array($product->images))
                <div class="mb-3">
                    <label>Current Images</label>
                    <div class="d-flex flex-wrap gap-3">
                        @foreach ($product->images as $image)
                            <div>
                                <img src="{{ $image->src }}" alt="{{ $image->alt ?? '' }}" style="width: 100px; height: auto; border: 1px solid #ddd; border-radius: 4px;">
                                <p class="text-muted small text-center mt-1">{{ $image->name }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif



           

            <!-- <div class="mb-3">
              <label>Dimensions (JSON)</label>
              <textarea name="dimensions" class="form-control" rows="3">{{ old('dimensions', json_encode($product->dimensions, JSON_PRETTY_PRINT)) }}</textarea>

              <small class="text-muted">JSON format (length, width, height, weight)</small>
            </div> -->

            <!-- <div class="mb-3">
              <label>Attributes (JSON)</label>
              <textarea name="attributes" class="form-control" rows="3">{{ old('attributes', json_encode($product->attributes, JSON_PRETTY_PRINT)) }}</textarea>

              <small class="text-muted">JSON format</small>
            </div> -->

            <div class="d-flex justify-content-end">
              <button type="submit" class="btn btn-primary me-2">Update Product</button>
              <a href="{{ url()->previous() }}" class="btn btn-secondary">Cancel</a>
            </div>

          </form>

        </div>
      </div
