<form action="{{ route('user.mechanic.store') }}" method="post" id="ajxForm" data-ajxForm-reset="false">
    @csrf

    <div class="form-group mb-3 row">
        <label class="form-label col-3 col-form-label">Name *</label>
        <div class="col">
            <input type="text" class="form-control" placeholder="Enter full name" name="name"
                value="{{ old('name') }}" required />
        </div>
    </div>

    <div class="form-group mb-3 row">
        <label class="form-label col-3 col-form-label">Email *</label>
        <div class="col">
            <input type="email" class="form-control" placeholder="Enter email address" name="email"
                value="{{ old('email') }}" required />
        </div>
    </div>

    <div class="form-group mb-3 row">
        <label class="form-label col-3 col-form-label">Password *</label>
        <div class="col">
            <input type="password" class="form-control" placeholder="Enter password" name="password" required />
        </div>
    </div>

    <div class="form-group mb-3 row">
        <label class="form-label col-3 col-form-label">Phone</label>
        <div class="col">
            <input type="text" class="form-control" placeholder="Enter phone number" name="phone"
                value="{{ old('phone') }}" maxlength="20" />
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

    <div class="form-group mb-3 row">
        <label class="form-label col-3 col-form-label pt-0">Email Verified</label>
        <div class="col">
            <label class="form-check">
                <input class="form-check-input" type="checkbox" name="email_verified" value="1"
                    {{ old('email_verified') ? 'checked' : '' }}>
                <span class="form-check-label">Mark as Verified</span>
            </label>
            <small class="form-hint">If checked, <code>email_verified_at</code> will be set to now()</small>
        </div>
    </div>

    <div class="form-footer text-end mt-3">
        <button type="reset" class="btn btn-warning me-2">Cancel</button>
        <button type="submit" class="btn btn-primary">Submit</button>
    </div>
</form>
