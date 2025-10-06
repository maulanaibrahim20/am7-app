<div class="card">
    <div class="card-body">

        <div class="form-group mb-3 row">
            <label class="form-label col-3 col-form-label">Category</label>
            <div class="col pt-2">
                {{ $service->category->name ?? '-' }}
            </div>
        </div>

        <div class="form-group mb-3 row">
            <label class="form-label col-3 col-form-label">Code</label>
            <div class="col pt-2">
                {{ $service->code }}
            </div>
        </div>

        <div class="form-group mb-3 row">
            <label class="form-label col-3 col-form-label">Name</label>
            <div class="col pt-2">
                {{ $service->name }}
            </div>
        </div>

        @if ($service->description)
            <div class="form-group mb-3 row">
                <label class="form-label col-3 col-form-label">Description</label>
                <div class="col pt-2">
                    {{ $service->description }}
                </div>
            </div>
        @endif

        <div class="form-group mb-3 row">
            <label class="form-label col-3 col-form-label">Base Price</label>
            <div class="col pt-2">
                Rp {{ number_format($service->base_price, 0, ',', '.') }}
            </div>
        </div>

        <div class="form-group mb-3 row">
            <label class="form-label col-3 col-form-label">Estimated Duration</label>
            <div class="col pt-2">
                {{ $service->estimated_duration }} minutes
            </div>
        </div>

        <div class="form-group mb-3 row">
            <label class="form-label col-3 col-form-label">Vehicle Type</label>
            <div class="col pt-2">
                @php
                    $types = [
                        'car' => 'Car',
                        'truck' => 'Truck',
                        'both' => 'Car & Truck',
                    ];
                @endphp
                <span class="badge bg-info">{{ $types[$service->vehicle_type] ?? $service->vehicle_type }}</span>
            </div>
        </div>

        <div class="form-group mb-3 row">
            <label class="form-label col-3 col-form-label">Status</label>
            <div class="col pt-2">
                @if ($service->is_active)
                    <span class="badge bg-success">Active</span>
                @else
                    <span class="badge bg-secondary">Inactive</span>
                @endif
            </div>
        </div>

        <div class="form-group mb-3 row">
            <label class="form-label col-3 col-form-label">Created At</label>
            <div class="col pt-2">
                {{ $service->created_at->format('d M Y H:i') }}
            </div>
        </div>

    </div>
</div>
