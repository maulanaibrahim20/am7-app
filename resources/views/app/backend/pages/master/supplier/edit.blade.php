<form action="{{ route('master.supplier.update', $supplier->id) }}" method="post" id="ajxForm"
    data-ajxForm-reset="false">
    @csrf
    @method('PUT')

    <div class="form-group mb-3 row">
        <label class="form-label col-3 col-form-label">Code *</label>
        <div class="col">
            <input type="text" class="form-control" placeholder="Enter supplier code" name="code"
                value="{{ old('code', $supplier->code) }}" required />
            <small class="form-hint">Code must be unique</small>
        </div>
    </div>

    <div class="form-group mb-3 row">
        <label class="form-label col-3 col-form-label">Name *</label>
        <div class="col">
            <input type="text" class="form-control" placeholder="Enter supplier name" name="name"
                value="{{ old('name', $supplier->name) }}" required />
        </div>
    </div>

    <div class="form-group mb-3 row">
        <label class="form-label col-3 col-form-label">Contact Person</label>
        <div class="col">
            <input type="text" class="form-control" placeholder="Enter contact person" name="contact_person"
                value="{{ old('contact_person', $supplier->contact_person) }}" />
        </div>
    </div>

    <div class="form-group mb-3 row">
        <label class="form-label col-3 col-form-label">Phone *</label>
        <div class="col">
            <input type="text" class="form-control" placeholder="Enter phone number" name="phone"
                value="{{ old('phone', $supplier->phone) }}" maxlength="20" required />
        </div>
    </div>

    <div class="form-group mb-3 row">
        <label class="form-label col-3 col-form-label">Email</label>
        <div class="col">
            <input type="email" class="form-control" placeholder="Enter email address" name="email"
                value="{{ old('email', $supplier->email) }}" />
        </div>
    </div>

    <div class="form-group mb-3 row">
        <label class="form-label col-3 col-form-label">Address *</label>
        <div class="col">
            <textarea class="form-control" placeholder="Enter address" name="address" rows="3" required>{{ old('address', $supplier->address) }}</textarea>
        </div>
    </div>

    <div class="form-group mb-3 row">
        <label class="form-label col-3 col-form-label">Lead Time (Days)</label>
        <div class="col">
            <input type="number" class="form-control" placeholder="Enter lead time" name="lead_time_days"
                value="{{ old('lead_time_days', $supplier->lead_time_days) }}" min="1" required />
        </div>
    </div>

    <div class="form-group mb-3 row">
        <label class="form-label col-3 col-form-label pt-0">Status</label>
        <div class="col">
            <label class="form-check">
                <input class="form-check-input" type="checkbox" name="is_active" value="1"
                    {{ old('is_active', $supplier->is_active) ? 'checked' : '' }}>
                <span class="form-check-label">Active</span>
            </label>
        </div>
    </div>

    <div class="form-footer text-end mt-3">
        <button type="submit" class="btn btn-primary">Update</button>
    </div>
</form>
