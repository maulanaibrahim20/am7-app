<form action="{{ route('booking.store') }}" method="post" id="ajxForm" data-ajxForm-reset="false">
    @csrf
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
