<form action="{{ route('cashier.select-customer') }}" method="POST" id="ajxForm" data-ajxForm-reset="true">
    @csrf
    <div class="card-body">
        <div class="mb-3">
            <label class="form-label">Existing Customer</label>
            <select class="form-select" name="customer_id" id="existingCustomer">
                <option value="">-- Select Customer --</option>
                @foreach ($customers as $customer)
                    <option value="{{ $customer->id }}">
                        {{ $customer->name }} ({{ $customer->phone }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="text-center mb-3">
            <strong>OR</strong>
        </div>

        <div id="newCustomerForm">
            <h6 class="mb-3">Add New Customer</h6>

            <div class="mb-3">
                <label class="form-label">Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="customer_name" placeholder="Enter customer name">
            </div>

            <div class="mb-3">
                <label class="form-label">Phone <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="customer_phone" placeholder="e.g., 081234567890">
            </div>

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" class="form-control" name="customer_email" placeholder="customer@example.com">
            </div>

            <div class="mb-3">
                <label class="form-label">Address</label>
                <textarea class="form-control" name="customer_address" rows="2" placeholder="Enter address"></textarea>
            </div>
        </div>
    </div>
    <div class="card-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            Cancel
        </button>
        <button type="submit" class="btn btn-primary">
            <i class="ti ti-check"></i> Continue
        </button>
    </div>
</form>

<script>
    document.getElementById('existingCustomer')?.addEventListener('change', function() {
        const newCustomerForm = document.getElementById('newCustomerForm');
        const inputs = newCustomerForm.querySelectorAll('input, textarea');

        if (this.value) {
            // Disable new customer fields if existing customer selected
            inputs.forEach(input => {
                input.disabled = true;
                input.required = false;
            });
            newCustomerForm.style.opacity = '0.5';
        } else {
            // Enable new customer fields
            inputs.forEach(input => {
                input.disabled = false;
                if (input.name === 'customer_name' || input.name === 'customer_phone') {
                    input.required = true;
                }
            });
            newCustomerForm.style.opacity = '1';
        }
    });
</script>
