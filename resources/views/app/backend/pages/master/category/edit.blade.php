<form action="{{ route('master.category.update', $category->id) }}" method="post" id="ajxForm"
    data-ajxForm-reset="false">
    @csrf
    @method('PUT')

    {{-- Name --}}
    <div class="form-group mb-3 row">
        <label class="form-label col-3 col-form-label">Name *</label>
        <div class="col">
            <input type="text" class="form-control" placeholder="Enter category name" name="name"
                value="{{ old('name', $category->name) }}" required />
        </div>
    </div>

    {{-- Slug --}}
    <div class="form-group mb-3 row">
        <label class="form-label col-3 col-form-label">Slug *</label>
        <div class="col">
            <input type="text" class="form-control" placeholder="Enter unique slug" name="slug"
                value="{{ old('slug', $category->slug) }}" required />
            <small class="form-hint">Slug must be unique (used in URL)</small>
        </div>
    </div>

    {{-- Description --}}
    <div class="form-group mb-3 row">
        <label class="form-label col-3 col-form-label">Description</label>
        <div class="col">
            <textarea class="form-control" placeholder="Enter description" name="description" rows="3">{{ old('description', $category->description) }}</textarea>
        </div>
    </div>

    {{-- Type --}}
    <div class="form-group mb-3 row">
        <label class="form-label col-3 col-form-label">Type *</label>
        <div class="col">
            <select class="form-select" name="type" required>
                <option value="">-- Select Type --</option>
                <option value="product" {{ old('type', $category->type) == 'product' ? 'selected' : '' }}>Product
                </option>
                <option value="service" {{ old('type', $category->type) == 'service' ? 'selected' : '' }}>Service
                </option>
            </select>
        </div>
    </div>

    {{-- Status --}}
    <div class="form-group mb-3 row">
        <label class="form-label col-3 col-form-label pt-0">Status</label>
        <div class="col">
            <label class="form-check">
                <input class="form-check-input" type="checkbox" name="is_active" value="1"
                    {{ old('is_active', $category->is_active) ? 'checked' : '' }}>
                <span class="form-check-label">Active</span>
            </label>
        </div>
    </div>

    {{-- Submit --}}
    <div class="form-footer text-end mt-3">
        <button type="submit" class="btn btn-primary">Update</button>
    </div>
</form>
