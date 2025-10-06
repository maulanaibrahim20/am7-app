<div class="card">
    <div class="card-body">
        <div class="form-group mb-3 row">
            <label class="form-label col-3 col-form-label">Name</label>
            <div class="col pt-2">
                {{ $user->name }}
            </div>
        </div>

        <div class="form-group mb-3 row">
            <label class="form-label col-3 col-form-label">Email</label>
            <div class="col pt-2">
                {{ $user->email }}
            </div>
        </div>

        <div class="form-group mb-3 row">
            <label class="form-label col-3 col-form-label">Phone</label>
            <div class="col pt-2">
                {{ $user->phone ?? '-' }}
            </div>
        </div>

        <div class="form-group mb-3 row">
            <label class="form-label col-3 col-form-label">Status</label>
            <div class="col pt-2">
                @if ($user->is_active)
                    <span class="badge bg-success">Active</span>
                @else
                    <span class="badge bg-secondary">Inactive</span>
                @endif
            </div>
        </div>

        <div class="form-group mb-3 row">
            <label class="form-label col-3 col-form-label">Email Verified</label>
            <div class="col pt-2">
                @if ($user->email_verified_at)
                    <span class="badge bg-success">Verified ({{ $user->email_verified_at->format('d M Y H:i') }})</span>
                @else
                    <span class="badge bg-danger">Not Verified</span>
                @endif
            </div>
        </div>
    </div>
</div>
