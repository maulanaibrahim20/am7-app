<form action="{{ route('master.category.store') }}" method="post" id="ajxForm" data-ajxForm-reset="false">
    @csrf

    <div class="form-group mb-3 row">
        <label class="form-label col-3 col-form-label">Name *</label>
        <div class="col">
            <input type="text" class="form-control" placeholder="Enter category name" name="name"
                value="{{ old('name') }}" required />
        </div>
    </div>

    <div class="form-group mb-3 row">
        <label class="form-label col-3 col-form-label">Slug *</label>
        <div class="col">
            <input type="text" class="form-control" placeholder="Enter unique slug" name="slug"
                value="{{ old('slug') }}" required />
            <small class="form-hint">Slug must be unique (used in URL)</small>
        </div>
    </div>

    <div class="form-group mb-3 row">
        <label class="form-label col-3 col-form-label">Description</label>
        <div class="col">
            <textarea class="form-control" placeholder="Enter description" name="description" rows="3">{{ old('description') }}</textarea>
        </div>
    </div>

    <div class="form-group mb-3 row">
        <label class="form-label col-3 col-form-label">Type *</label>
        <div class="col">
            <select class="form-select" name="type" required>
                <option value="">-- Select Type --</option>
                <option value="product" {{ old('type') == 'product' ? 'selected' : '' }}>Product</option>
                <option value="service" {{ old('type') == 'service' ? 'selected' : '' }}>Service</option>
            </select>
        </div>
    </div>

    <div class="form-group mb-3 row">
        <label class="form-label col-3 col-form-label pt-0">Status</label>
        <div class="col">
            <label class="form-check">
                <input class="form-check-input" type="checkbox" name="is_active" value="1"
                    {{ old('is_active', 1) ? 'checked' : '' }}>
                <span class="form-check-label">Active</span>
            </label>
        </div>
    </div>

    <div class="form-footer text-end mt-3">
        <button type="submit" class="btn btn-primary">Submit</button>
    </div>
</form>
