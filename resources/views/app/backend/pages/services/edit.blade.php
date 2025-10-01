<form action="{{ route('service.update', $service->id) }}" method="post" id="ajxForm" data-ajxForm-reset="false">
    @csrf
    @method('PUT')

    <div class="card-body">
        <div class="row g-3">

            {{-- Category --}}
            <div class="col-md-6">
                <label class="form-label">Category <span class="text-danger">*</span></label>
                <select class="form-select" name="category_id" required>
                    <option value="">Select Category</option>
                    @foreach ($category as $cat)
                        <option value="{{ $cat->id }}" {{ $service->category_id == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Code --}}
            <div class="col-md-6">
                <label class="form-label">Service Code <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="code" value="{{ old('code', $service->code) }}"
                    required>
            </div>

            {{-- Name --}}
            <div class="col-12">
                <label class="form-label">Service Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="name" value="{{ old('name', $service->name) }}"
                    required>
            </div>

            {{-- Description --}}
            <div class="col-12">
                <label class="form-label">Description</label>
                <textarea class="form-control" name="description" rows="3">{{ old('description', $service->description) }}</textarea>
            </div>

            {{-- Base Price --}}
            <div class="col-md-6">
                <label class="form-label">Base Price <span class="text-danger">*</span></label>
                <input type="number" class="form-control" name="base_price" step="0.01"
                    value="{{ old('base_price', $service->base_price) }}" required>
            </div>

            {{-- Estimated Duration --}}
            <div class="col-md-6">
                <label class="form-label">Estimated Duration (minutes) <span class="text-danger">*</span></label>
                <input type="number" class="form-control" name="estimated_duration" min="1"
                    value="{{ old('estimated_duration', $service->estimated_duration) }}" required>
            </div>

            {{-- Vehicle Type --}}
            <div class="col-md-6">
                <label class="form-label">Vehicle Type <span class="text-danger">*</span></label>
                <select class="form-select" name="vehicle_type" required>
                    <option value="car" {{ $service->vehicle_type == 'car' ? 'selected' : '' }}>Car</option>
                    <option value="truck" {{ $service->vehicle_type == 'truck' ? 'selected' : '' }}>Truck</option>
                    <option value="both" {{ $service->vehicle_type == 'both' ? 'selected' : '' }}>Both</option>
                </select>
            </div>

            {{-- Status --}}
            <div class="col-md-6 mt-4">
                <label class="form-check">
                    <input class="form-check-input" type="checkbox" name="is_active" value="1"
                        {{ old('is_active', $service->is_active) ? 'checked' : '' }}>
                    <span class="form-check-label">Active</span>
                </label>
            </div>

        </div>
    </div>

    <div class="form-footer text-end mt-3">
        <button type="submit" class="btn btn-primary">Update</button>
    </div>
</form>
