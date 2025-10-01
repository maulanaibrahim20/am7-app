<form action="{{ route('service.store') }}" method="post" id="ajxForm" data-ajxForm-reset="false">
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
                <label class="form-label">Service Code <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="code" required>
            </div>

            <div class="col-12">
                <label class="form-label">Service Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="name" required>
            </div>

            <div class="col-12">
                <label class="form-label">Description</label>
                <textarea class="form-control" name="description" rows="3"></textarea>
            </div>

            <div class="col-md-6">
                <label class="form-label">Base Price <span class="text-danger">*</span></label>
                <input type="number" class="form-control" name="base_price" step="0.01" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">Estimated Duration (minutes) <span class="text-danger">*</span></label>
                <input type="number" class="form-control" name="estimated_duration" min="1" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">Vehicle Type <span class="text-danger">*</span></label>
                <select class="form-select" name="vehicle_type" required>
                    <option value="car">Car</option>
                    <option value="truck">Truck</option>
                    <option value="both" selected>Car & Truck</option>
                </select>
            </div>

            <div class="col-md-6">
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
