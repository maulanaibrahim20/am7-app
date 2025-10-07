<form action="{{ route('product.update', $product->id) }}" method="post" id="ajxForm" data-ajxForm-reset="false">
    @csrf
    @method('PUT')

    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Category <span class="text-danger">*</span></label>
                <select class="form-select" name="category_id" required>
                    <option value="">Select Category</option>
                    @foreach ($category as $cat)
                        <option value="{{ $cat->id }}" {{ $product->category_id == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label">SKU <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="sku" value="{{ old('sku', $product->sku) }}"
                    required>
            </div>

            <div class="col-12">
                <label class="form-label">Product Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="name" value="{{ old('name', $product->name) }}"
                    required>
            </div>

            <div class="col-12">
                <label class="form-label">Description</label>
                <textarea class="form-control" name="description" rows="3">{{ old('description', $product->description) }}</textarea>
            </div>

            <div class="col-md-4">
                <label class="form-label">Unit</label>
                <input type="text" class="form-control" name="unit" value="{{ old('unit', $product->unit) }}">
            </div>

            <div class="col-md-4">
                <label class="form-label">Purchase Price <span class="text-danger">*</span></label>
                <input type="number" class="form-control" name="purchase_price" step="0.01"
                    value="{{ old('purchase_price', $product->purchase_price) }}" required>
            </div>

            <div class="col-md-4">
                <label class="form-label">Selling Price <span class="text-danger">*</span></label>
                <input type="number" class="form-control" name="selling_price" step="0.01"
                    value="{{ old('selling_price', $product->selling_price) }}" required>
            </div>

            <div class="col-md-3">
                <label class="form-label">Stock Quantity</label>
                <input type="number" class="form-control" name="stock_quantity"
                    value="{{ old('stock_quantity', $product->stock_quantity) }}">
            </div>

            <div class="col-md-3">
                <label class="form-label">Min Stock</label>
                <input type="number" class="form-control" name="min_stock"
                    value="{{ old('min_stock', $product->min_stock) }}">
            </div>

            <div class="col-md-3">
                <label class="form-label">Max Stock</label>
                <input type="number" class="form-control" name="max_stock"
                    value="{{ old('max_stock', $product->max_stock) }}">
            </div>

            <div class="col-md-3">
                <label class="form-label">Reorder Point</label>
                <input type="number" class="form-control" name="reorder_point"
                    value="{{ old('reorder_point', $product->reorder_point) }}">
            </div>

            <div class="col-md-6">
                <label class="form-label">Brand</label>
                <input type="text" class="form-control" name="brand" value="{{ old('brand', $product->brand) }}">
            </div>

            <div class="col-md-6">
                <label class="form-label">Compatible Vehicles</label>
                <input type="text" class="form-control" name="compatible_vehicles"
                    value="{{ old('compatible_vehicles', $product->compatible_vehicles) }}">
            </div>

            <div class="col-12 mt-2">
                <label class="form-check">
                    <input class="form-check-input" type="checkbox" name="is_active" value="1"
                        {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                    <span class="form-check-label">Active</span>
                </label>
            </div>
        </div>
    </div>

    <div class="form-footer text-end mt-3">
        <button type="reset" class="btn btn-warning me-2">Cancel</button>
        <button type="submit" class="btn btn-primary">Submit</button>
    </div>
</form>
