<div class="d-flex flex-column justify-content-center align-items-center h-100">
    <div class="w-100" style="max-width: 400px;">
        <div class="card shadow-sm">
            <div class="card-body">
                <h6 class="mb-3 fw-bold text-center">Filter Bookings</h6>

                <form id="bookingFilterForm">
                    <div class="mb-3">
                        <label class="form-label">Start Date</label>
                        <input type="date" id="start_date" name="start_date" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">End Date</label>
                        <input type="date" id="end_date" name="end_date" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select id="status" name="status" class="form-select">
                            <option value="">-- All --</option>
                            <option value="pending">Pending</option>
                            <option value="paid">Paid</option>
                            <option value="partial">Partial</option>
                        </select>
                    </div>

                    <div class="d-flex justify-content-end mt-3">
                        <button type="button" id="btn-apply-filter" class="btn btn-primary me-2">
                            <i class="ti ti-filter"></i> Apply
                        </button>
                        <button type="reset" class="btn btn-secondary" id="btn-reset-filter">Reset</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
