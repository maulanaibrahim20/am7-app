<div class="card border-warning mb-4 shadow-sm">
    <div class="card-body">
        <h6 class="text-warning mb-3">
            <i class="fas fa-exclamation-circle me-2"></i> Service Information
        </h6>
        <ul class="list-unstyled mb-0">
            <li><strong>Name:</strong> {{ $service->name }}</li>
            <li><strong>Description:</strong> {{ $service->description ?? '-' }}</li>
            <li><strong>Vehicle Type:</strong>
                @if ($service->vehicle_type === 'both')
                    Car & Truck
                @else
                    {{ ucfirst($service->vehicle_type) }}
                @endif
            </li>
            <li><strong>Base Price:</strong> Rp {{ number_format($service->base_price, 0, ',', '.') }}</li>
            <li><strong>Estimated Duration:</strong> {{ $service->estimated_duration }} minutes</li>
        </ul>
    </div>
</div>
<form action="{{ route('landing.booking.store') }}" method="post" id="ajxForm" data-ajxForm-reset="false">
    @csrf
    <input type="hidden" name="service_id" value="{{ $service->id }}">
    <div class="card-body">
        <div class="form-group mb-3 row">
            <label class="form-label col-3 col-form-label">Customer Name *</label>
            <div class="col">
                <input type="text" class="form-control" name="customer_name" placeholder="Enter customer name"
                    value="{{ old('customer_name') }}" required>
            </div>
        </div>

        <div class="form-group mb-3 row">
            <label class="form-label col-3 col-form-label">Phone *</label>
            <div class="col">
                <input type="text" class="form-control" name="customer_phone" placeholder="Enter phone number"
                    value="{{ old('customer_phone') }}" maxlength="20" required>
            </div>
        </div>

        <div class="form-group mb-3 row">
            <label class="form-label col-3 col-form-label">Email</label>
            <div class="col">
                <input type="email" class="form-control" name="customer_email" placeholder="Enter email (optional)"
                    value="{{ old('customer_email') }}">
            </div>
        </div>

        <div class="form-group mb-3 row">
            <label class="form-label col-3 col-form-label">Vehicle Type *</label>
            <div class="col">
                <input type="text" class="form-control" name="vehicle_type" placeholder="e.g. Truck, Car, Motorcycle"
                    value="{{ old('vehicle_type') }}" required>
            </div>
        </div>

        <div class="form-group mb-3 row">
            <label class="form-label col-3 col-form-label">Vehicle Number *</label>
            <div class="col">
                <input type="text" class="form-control" name="vehicle_number"
                    placeholder="Enter license plate number" value="{{ old('vehicle_number') }}" required>
            </div>
        </div>

        <div class="form-group mb-3 row">
            <label class="form-label col-3 col-form-label">Problem Description *</label>
            <div class="col">
                <textarea class="form-control" name="problem_description" rows="3" required>{{ old('problem_description') }}</textarea>
            </div>
        </div>

        <div class="form-group mb-3 row">
            <label class="form-label col-3 col-form-label">Booking Date *</label>
            <div class="col">
                <input type="date" class="form-control" name="booking_date" value="{{ old('booking_date') }}"
                    required>
            </div>
        </div>

        <div class="form-group mb-3 row">
            <label class="form-label col-3 col-form-label">Booking Time *</label>
            <div class="col">
                <input type="time" class="form-control" name="booking_time" value="{{ old('booking_time') }}"
                    required>
            </div>
        </div>

        <div class="form-footer text-end mt-3">
            <button type="reset" class="btn btn-warning me-2">Cancel</button>
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </div>
</form>
