<form action="{{ route('customer.update', $customer->id) }}" method="post" id="ajxForm" data-ajxForm-reset="false">
    @csrf
    @method('PUT')

    {{-- Name --}}
    <div class="form-group mb-3 row">
        <label class="form-label col-3 col-form-label">Name *</label>
        <div class="col">
            <input type="text" class="form-control" placeholder="Enter customer name" name="name"
                value="{{ old('name', $customer->name) }}" required />
        </div>
    </div>

    {{-- Phone --}}
    <div class="form-group mb-3 row">
        <label class="form-label col-3 col-form-label">Phone *</label>
        <div class="col">
            <input type="text" class="form-control" placeholder="Enter phone number" name="phone"
                value="{{ old('phone', $customer->phone) }}" required />
        </div>
    </div>

    {{-- Email --}}
    <div class="form-group mb-3 row">
        <label class="form-label col-3 col-form-label">Email</label>
        <div class="col">
            <input type="email" class="form-control" placeholder="Enter email address" name="email"
                value="{{ old('email', $customer->email) }}" />
        </div>
    </div>

    {{-- Address --}}
    <div class="form-group mb-3 row">
        <label class="form-label col-3 col-form-label">Address</label>
        <div class="col">
            <textarea class="form-control" placeholder="Enter address" name="address" rows="3">{{ old('address', $customer->address) }}</textarea>
        </div>
    </div>

    {{-- Vehicle Number --}}
    <div class="form-group mb-3 row">
        <label class="form-label col-3 col-form-label">Vehicle Number</label>
        <div class="col">
            <input type="text" class="form-control" placeholder="Enter vehicle number" name="vehicle_number"
                value="{{ old('vehicle_number', $customer->vehicle_number) }}" />
        </div>
    </div>

    {{-- Vehicle Type --}}
    <div class="form-group mb-3 row">
        <label class="form-label col-3 col-form-label">Vehicle Type</label>
        <div class="col">
            <input type="text" class="form-control" placeholder="Enter vehicle type" name="vehicle_type"
                value="{{ old('vehicle_type', $customer->vehicle_type) }}" />
        </div>
    </div>

    {{-- Submit --}}
    <div class="form-footer text-end mt-3">
        <button type="submit" class="btn btn-primary">Update</button>
    </div>
</form>
