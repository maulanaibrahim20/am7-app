<form action="{{ route('user.cashier.update', $user->id) }}" method="post" id="ajxForm" data-ajxForm-reset="false">
    @csrf
    @method('PUT')

    <div class="form-group mb-3 row">
        <label class="form-label col-3 col-form-label">Name *</label>
        <div class="col">
            <input type="text" class="form-control" placeholder="Enter full name" name="name"
                value="{{ old('name', $user->name) }}" required />
        </div>
    </div>

    <div class="form-group mb-3 row">
        <label class="form-label col-3 col-form-label">Email *</label>
        <div class="col">
            <input type="email" class="form-control" placeholder="Enter email address" name="email"
                value="{{ old('email', $user->email) }}" required />
        </div>
    </div>

    <div class="form-group mb-3 row">
        <label class="form-label col-3 col-form-label">Password</label>
        <div class="col">
            <input type="password" class="form-control" placeholder="Leave blank to keep current password"
                name="password" />
            <small class="form-hint">Leave blank if you don’t want to change the password</small>
        </div>
    </div>

    <div class="form-group mb-3 row">
        <label class="form-label col-3 col-form-label">Phone</label>
        <div class="col">
            <input type="text" class="form-control" placeholder="Enter phone number" name="phone"
                value="{{ old('phone', $user->phone) }}" maxlength="20" />
        </div>
    </div>

    <div class="form-group mb-3 row">
        <label class="form-label col-3 col-form-label pt-0">Status</label>
        <div class="col">
            <label class="form-check">
                <input class="form-check-input" type="checkbox" name="is_active" value="1"
                    {{ old('is_active', $user->is_active) ? 'checked' : '' }}>
                <span class="form-check-label">Active</span>
            </label>
        </div>
    </div>

    <div class="form-group mb-3 row">
        <label class="form-label col-3 col-form-label pt-0">Email Verified</label>
        <div class="col">
            <label class="form-check">
                <input class="form-check-input" type="checkbox" name="email_verified" value="1"
                    {{ old('email_verified', $user->email_verified_at ? 1 : 0) ? 'checked' : '' }}>
                <span class="form-check-label">Mark as Verified</span>
            </label>
            <small class="form-hint">If checked, <code>email_verified_at</code> will be set to now()</small>
        </div>
    </div>

    <div class="form-footer text-end mt-3">
        <button type="submit" class="btn btn-primary">Update</button>
    </div>
</form>
