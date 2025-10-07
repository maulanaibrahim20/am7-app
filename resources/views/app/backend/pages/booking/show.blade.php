<div class="card shadow-sm">
    <div class="card-header bg-primary mb-3">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0 text-white">
                <i class="fas fa-calendar-check me-2"></i>Booking Details
            </h5>
            <span class="badge bg-light text-dark">{{ $booking->booking_code }}</span>
        </div>
    </div>
    <div class="card-body">
        @if ($booking->whatsapp_id)
            <div class="card border-info mb-4">
                <div class="card-body">
                    <h6 class="text-info mb-3">
                        <i class="fab fa-whatsapp me-2"></i>WhatsApp Notification Status
                    </h6>
                    @php
                        $waStatus = app(\App\Services\SidobeWhatsappService::class)->checkMessageStatus(
                            $booking->whatsapp_id,
                        );
                    @endphp
                    @if (!empty($waStatus['error']))
                        <div class="alert alert-warning mb-0">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Gagal cek status WhatsApp: {{ $waStatus['message'] }}
                        </div>
                    @else
                        <div class="alert alert-success mb-0">
                            <i class="fas fa-check-circle me-2"></i>
                            Pesan WhatsApp sudah dikirim! Status: {{ $waStatus['data']['status'] ?? 'unknown' }}
                        </div>
                    @endif
                </div>
            </div>
        @endif
        <!-- Status Timeline -->
        <div class="card border-0 bg-light mb-4">
            <div class="card-body">
                <h6 class="text-primary mb-3">
                    <i class="fas fa-stream me-2"></i>Booking Status
                </h6>
                <div class="d-flex justify-content-between align-items-center flex-wrap">
                    @php
                        $statuses = ['pending', 'approved', 'in_progress', 'completed'];
                        $currentIndex = array_search($booking->status, $statuses);
                        $statusIcons = [
                            'pending' => 'fa-hourglass-half',
                            'approved' => 'fa-check-circle',
                            'in_progress' => 'fa-wrench',
                            'completed' => 'fa-flag-checkered',
                            'rejected' => 'fa-times-circle',
                            'cancelled' => 'fa-ban',
                        ];
                    @endphp

                    @foreach ($statuses as $index => $status)
                        <div class="text-center flex-fill">
                            <div class="mb-2">
                                @if ($index < $currentIndex || $booking->status == $status)
                                    <i class="fas {{ $statusIcons[$status] }} fa-2x text-success"></i>
                                @else
                                    <i class="fas {{ $statusIcons[$status] }} fa-2x text-muted"></i>
                                @endif
                            </div>
                            <small
                                class="d-block fw-bold {{ $booking->status == $status ? 'text-primary' : 'text-muted' }}">
                                {{ ucfirst($status) }}
                            </small>
                        </div>
                        @if ($index < count($statuses) - 1)
                            <div class="flex-fill"
                                style="height: 2px; background: {{ $index < $currentIndex ? '#198754' : '#dee2e6' }}; margin: 0 10px; align-self: center;">
                            </div>
                        @endif
                    @endforeach
                </div>

                @if (in_array($booking->status, ['rejected', 'cancelled']))
                    <div class="alert alert-danger mt-3 mb-0">
                        <i class="fas {{ $statusIcons[$booking->status] }} me-2"></i>
                        <strong>{{ ucfirst($booking->status) }}</strong>
                    </div>
                @endif
            </div>
        </div>

        <!-- Customer & Vehicle Info -->
        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <div class="card border-0 bg-light h-100">
                    <div class="card-body">
                        <h6 class="card-title text-primary mb-3">
                            <i class="fas fa-user me-2"></i>Customer Information
                        </h6>
                        <div class="mb-2">
                            <strong>{{ $booking->customer_name }}</strong>
                        </div>
                        <div class="text-muted small">
                            <i class="fas fa-phone me-2"></i>{{ $booking->customer_phone }}
                        </div>
                        <div class="text-muted small">
                            <i class="fas fa-envelope me-2"></i>{{ $booking->customer_email ?? '-' }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card border-0 bg-light h-100">
                    <div class="card-body">
                        <h6 class="card-title text-primary mb-3">
                            <i class="fas fa-car me-2"></i>Vehicle Information
                        </h6>
                        <div class="mb-2">
                            <strong>{{ $booking->vehicle_type }}</strong>
                        </div>
                        <div class="text-muted">
                            <i class="fas fa-hashtag me-2"></i>{{ $booking->vehicle_number }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Problem Description -->
        <div class="card border-0 bg-light mb-4">
            <div class="card-body">
                <h6 class="card-title text-primary mb-3">
                    <i class="fas fa-exclamation-triangle me-2"></i>Problem Description
                </h6>
                <p class="mb-0">{{ $booking->problem_description }}</p>
            </div>
        </div>

        <!-- Schedule -->
        <div class="card border-0 bg-light mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <small class="text-muted d-block">Schedule</small>
                        <strong class="fs-5">
                            <i class="far fa-calendar me-2 text-primary"></i>
                            {{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') }}
                            <span class="text-muted">at</span>
                            {{ \Carbon\Carbon::parse($booking->booking_time)->format('H:i') }}
                        </strong>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block">Created</small>
                        <i class="far fa-clock me-2 text-primary"></i>
                        {{ $booking->created_at->format('d M Y H:i') }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons based on Status -->
        @if ($booking->status == 'pending')
            <div class="card border-warning mb-4">
                <div class="card-body">
                    <h6 class="text-warning mb-3">
                        <i class="fas fa-exclamation-circle me-2"></i>Action Required
                    </h6>
                    <div class="d-flex gap-2">
                        <form action="{{ route('booking.updateStatus', $booking->id) }}" method="POST" id="ajxForm"
                            data-ajxForm-reset="false" class="d-inline">
                            @csrf
                            <input type="hidden" name="status" value="approved">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-check me-2"></i>Approve Booking
                            </button>
                        </form>
                        <form action="{{ route('booking.updateStatus', $booking->id) }}" method="POST" id="ajxForm"
                            data-ajxForm-reset="false" class="d-inline">
                            @csrf
                            <input type="hidden" name="status" value="rejected">
                            <button type="submit" class="btn btn-danger"
                                onclick="return confirm('Are you sure want to reject this booking?')">
                                <i class="fas fa-times me-2"></i>Reject Booking
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endif

        @if ($booking->status == 'approved')
            <div class="card border-info mb-4">
                <div class="card-body">
                    <h6 class="text-info mb-3">
                        <i class="fas fa-user-cog me-2"></i>Assign Mechanic & Start Work
                    </h6>
                    <form action="{{ route('booking.updateStatus', $booking->id) }}" method="POST" id="ajxForm"
                        data-ajxForm-reset="false" class="d-inline">
                        @csrf
                        <input type="hidden" name="status" value="in_progress">
                        <div class="row g-3">
                            <div class="col-md-8">
                                <select name="mechanic_id" class="form-select" required>
                                    <option value="">Select Mechanic</option>
                                    @foreach ($mechanics ?? [] as $mechanic)
                                        <option value="{{ $mechanic->id }}">{{ $mechanic->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-info w-100">
                                    <i class="fas fa-play me-2"></i>Start Work
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        @endif

        @if ($booking->status == 'in_progress')
            <div class="card border-success mb-4">
                <div class="card-body">
                    <h6 class="text-success mb-3">
                        <i class="fas fa-flag-checkered me-2"></i>Complete Booking
                    </h6>
                    <form action="{{ route('booking.updateStatus', $booking->id) }}" method="POST" id="ajxForm"
                        data-ajxForm-reset="false">
                        @csrf
                        <input type="hidden" name="status" value="completed">
                        <button type="submit" class="btn btn-success"
                            onclick="return confirm('Mark this booking as completed?')">
                            <i class="fas fa-check-double me-2"></i>Mark as Completed
                        </button>
                    </form>
                </div>
            </div>
        @endif

        <!-- Approval & Mechanic Info -->
        @if ($booking->status != 'pending')
            <div class="row g-3 mb-4">
                @if ($booking->approved_by)
                    <div class="col-md-6">
                        <div class="card border-success">
                            <div class="card-body">
                                <h6 class="card-title text-success mb-3">
                                    <i class="fas fa-user-check me-2"></i>Approved By
                                </h6>
                                <div class="mb-1">
                                    <strong>{{ $booking->approvedBy->name ?? '-' }}</strong>
                                </div>
                                <small class="text-muted">
                                    <i class="far fa-calendar me-1"></i>
                                    {{ $booking->approved_at ? $booking->approved_at->format('d M Y H:i') : '' }}
                                </small>
                            </div>
                        </div>
                    </div>
                @endif

                @if ($booking->mechanic_id)
                    <div class="col-md-6">
                        <div class="card border-warning">
                            <div class="card-body">
                                <h6 class="card-title text-warning mb-3">
                                    <i class="fas fa-tools me-2"></i>Mechanic Assigned
                                </h6>
                                <div class="mb-2">
                                    <strong>{{ $booking->mechanic->name ?? '-' }}</strong>
                                </div>
                                @if ($booking->started_at)
                                    <small class="text-muted d-block">
                                        <i class="fas fa-play me-1"></i>Started:
                                        {{ $booking->started_at->format('d M Y H:i') }}
                                    </small>
                                @endif
                                @if ($booking->completed_at)
                                    <small class="text-success d-block">
                                        <i class="fas fa-check-circle me-1"></i>Completed:
                                        {{ $booking->completed_at->format('d M Y H:i') }}
                                    </small>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        @endif

        <!-- Services/Parts Used Section -->
        @if (in_array($booking->status, ['in_progress', 'completed']))
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light d-flex justify-content-between align-items-center mb-4">
                    <h6 class="mb-0">
                        <i class="fas fa-list me-2"></i>Services & Parts Used
                    </h6>
                </div>
                <div class="card-body">
                    @if (isset($booking->services) && $booking->services->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Service</th>
                                        <th>Description</th>
                                        <th class="text-end">Price</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $total = 0; @endphp
                                    @foreach ($booking->services as $service)
                                        <tr>
                                            <td>{{ $service->name }}</td>
                                            <td>{{ $service->description }}</td>
                                            <td class="text-end">Rp
                                                {{ number_format($service->base_price, 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                            <p>No services or parts added yet</p>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        <!-- Notes Section -->
        <div class="card shadow-sm">
            <div class="card-header bg-light mb-3">
                <h6 class="mb-0">
                    <i class="fas fa-sticky-note me-2"></i>Notes
                </h6>
            </div>
            <div class="card-body">
                <ul class="nav nav-pills mb-3" id="notesTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="admin-tab" data-bs-toggle="tab"
                            data-bs-target="#adminNotes" type="button" role="tab">
                            <i class="fas fa-user-shield me-1"></i>Admin Notes
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="mechanic-tab" data-bs-toggle="tab"
                            data-bs-target="#mechanicNotes" type="button" role="tab">
                            <i class="fas fa-wrench me-1"></i>Mechanic Notes
                        </button>
                    </li>
                </ul>
                <div class="tab-content" id="notesTabContent">
                    <div class="tab-pane fade show active" id="adminNotes" role="tabpanel">
                        <form action="{{ route('booking.updateNote', ['type' => 'admin_notes', $booking->id]) }}"
                            method="POST"id="ajxForm" data-ajxForm-reset="false">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <textarea name="admin_notes" class="form-control" rows="4" placeholder="Add admin notes here..."
                                    @if ($booking->status == 'completed') readonly @endif>{{ $booking->admin_notes }}</textarea>
                            </div>
                            @if ($booking->status != 'completed')
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Save Admin Notes
                                </button>
                            @endif
                        </form>
                    </div>
                    <div class="tab-pane fade" id="mechanicNotes" role="tabpanel">
                        <form action="{{ route('booking.updateNote', ['type' => 'mechanic_notes', $booking->id]) }}"
                            method="POST" id="ajxForm" data-ajxForm-reset="false">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <textarea name="mechanic_notes" class="form-control" rows="4" placeholder="Add mechanic notes here..."
                                    @if ($booking->status == 'completed') readonly @endif>{{ $booking->mechanic_notes }}</textarea>
                            </div>
                            @if ($booking->status != 'completed')
                                <button type="submit" class="btn btn-warning">
                                    <i class="fas fa-save me-2"></i>Save Mechanic Notes
                                </button>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const serviceSelect = document.querySelector('select[name="service_id"]');
        const priceInput = document.querySelector('input[name="price"]');

        if (serviceSelect && priceInput) {
            serviceSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                const price = selectedOption.getAttribute('data-price');
                if (price) {
                    priceInput.value = price;
                }
            });
        }
    });
</script>
