<form action="{{ route('customer.store') }}" method="post" id="ajxForm" data-ajxForm-reset="true">
    @csrf

    <div class="form-group mb-3 row">
        <label class="form-label col-3 col-form-label">Name *</label>
        <div class="col">
            <input type="text" class="form-control" placeholder="Enter customer name" name="name"
                value="{{ old('name') }}" required />
        </div>
    </div>

    <div class="form-group mb-3 row">
        <label class="form-label col-3 col-form-label">Phone *</label>
        <div class="col">
            <input type="text" class="form-control" placeholder="Enter phone number" name="phone"
                value="{{ old('phone') }}" required />
            <small class="form-hint">Phone must be unique</small>
        </div>
    </div>

    <div class="form-group mb-3 row">
        <label class="form-label col-3 col-form-label">Email</label>
        <div class="col">
            <input type="email" class="form-control" placeholder="Enter email address" name="email"
                value="{{ old('email') }}" />
        </div>
    </div>

    <div class="form-group mb-3 row">
        <label class="form-label col-3 col-form-label">Address</label>
        <div class="col">
            <textarea class="form-control" placeholder="Enter address" name="address" rows="3">{{ old('address') }}</textarea>
        </div>
    </div>

    <div class="form-group mb-3 row">
        <label class="form-label col-3 col-form-label">Vehicle Number</label>
        <div class="col">
            <input type="text" class="form-control" placeholder="Enter vehicle number" name="vehicle_number"
                value="{{ old('vehicle_number') }}" />
        </div>
    </div>

    <div class="form-group mb-3 row">
        <label class="form-label col-3 col-form-label">Vehicle Type</label>
        <div class="col">
            <input type="text" class="form-control" placeholder="Enter vehicle type" name="vehicle_type"
                value="{{ old('vehicle_type') }}" />
        </div>
    </div>

    <div class="form-footer text-end mt-3">
        <button type="submit" class="btn btn-primary">Submit</button>
    </div>
</form>
