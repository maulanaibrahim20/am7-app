<form action="{{ route('product.store') }}" method="post" id="ajxForm" data-ajxForm-reset="false">
    @csrf
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Category <span class="text-danger">*</span></label>
                <select class="form-select" name="category_id" required>
                    <option value="">Select Category</option>
                    @foreach ($category as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">SKU <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="sku" required>
            </div>
            <div class="col-12">
                <label class="form-label">Product Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="name" required>
            </div>
            <div class="col-12">
                <label class="form-label">Description</label>
                <textarea class="form-control" name="description" rows="3"></textarea>
            </div>
            <div class="col-md-4">
                <label class="form-label">Unit</label>
                <input type="text" class="form-control" name="unit" value="pcs">
            </div>
            <div class="col-md-4">
                <label class="form-label">Purchase Price <span class="text-danger">*</span></label>
                <input type="number" class="form-control" name="purchase_price" step="0.01" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Selling Price <span class="text-danger">*</span></label>
                <input type="number" class="form-control" name="selling_price" step="0.01" required>
            </div>
            <div class="col-md-3">
                <label class="form-label">Initial Stock</label>
                <input type="number" class="form-control" name="stock_quantity" value="0">
            </div>
            <div class="col-md-3">
                <label class="form-label">Min Stock</label>
                <input type="number" class="form-control" name="min_stock" value="5">
            </div>
            <div class="col-md-3">
                <label class="form-label">Max Stock</label>
                <input type="number" class="form-control" name="max_stock" value="100">
            </div>
            <div class="col-md-3">
                <label class="form-label">Reorder Point</label>
                <input type="number" class="form-control" name="reorder_point" value="10">
            </div>
            <div class="col-md-6">
                <label class="form-label">Brand</label>
                <input type="text" class="form-control" name="brand">
            </div>
            <div class="col-md-6">
                <label class="form-label">Compatible Vehicles</label>
                <input type="text" class="form-control" name="compatible_vehicles">
            </div>
            <div class="col-md-">
                <label class="form-label col-3 col-form-label pt-0">Status</label>
                <label class="form-check">
                    <input class="form-check-input" type="checkbox" name="is_active" value="1"
                        {{ old('is_active', 1) ? 'checked' : '' }}>
                    <span class="form-check-label">Active</span>
                </label>
            </div>
        </div>
    </div>
    <div class="form-footer text-end mt-3">
        <button type="submit" class="btn btn-primary">Submit</button>
    </div>
</form>
