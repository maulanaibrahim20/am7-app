<form action="{{ route('cashier.hold-cart') }}" method="POST" id="ajxForm" data-ajxForm-reset="true">
    @csrf
    <div class="card-body">
        <div class="alert alert-info">
            <i class="ti ti-info-circle"></i>
            This transaction will be saved and you can resume it later.
        </div>

        <div class="mb-3">
            <label class="form-label">Notes</label>
            <textarea class="form-control" name="notes" rows="3" placeholder="e.g., Waiting for service completion..."></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Hold Duration</label>
            <select class="form-select" name="expired_hours">
                <option value="1">1 Hour</option>
                <option value="3">3 Hours</option>
                <option value="6">6 Hours</option>
                <option value="12">12 Hours</option>
                <option value="24" selected>24 Hours (1 Day)</option>
                <option value="48">48 Hours (2 Days)</option>
                <option value="72">72 Hours (3 Days)</option>
            </select>
        </div>
    </div>
    <div class="card-footer text-end">
        <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">
            Cancel
        </button>
        <button type="submit" class="btn btn-warning">
            <i class="ti ti-clock"></i> Hold Transaction
        </button>
    </div>
</form>
