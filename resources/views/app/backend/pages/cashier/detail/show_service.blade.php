<style>
    .detail-card {
        transition: transform 0.3s ease;
    }

    .detail-card:hover {
        transform: scale(1.02);
    }

    .service-detail-image {
        transition: transform 0.3s ease;
    }

    .service-detail-image:hover {
        transform: scale(1.05);
    }
</style>

<div class="row g-4">
    <div class="col-md-5">
        <div class="service-image-container text-center mb-3">
            <img src="{{ url('/img/technical-support.png') }}" class="img-fluid rounded-3 shadow-sm service-detail-image"
                alt="{{ $service->name }}" style="max-height:300px; object-fit: contain;">
        </div>
    </div>

    <div class="col-md-7">
        <div class="service-details">
            <h4 class="mb-3">{{ $service->name }}</h4>

            <div class="row g-3">
                <div class="col-6">
                    <div class="detail-card bg-light p-3 rounded-3">
                        <small class="text-muted d-block mb-1">Kode</small>
                        <h6 class="mb-0">{{ $service->code }}</h6>
                    </div>
                </div>
                <div class="col-6">
                    <div class="detail-card bg-light p-3 rounded-3">
                        <small class="text-muted d-block mb-1">Kategori</small>
                        <h6 class="mb-0">{{ $service->category->name ?? '-' }}</h6>
                    </div>
                </div>
            </div>

            <hr class="my-3">

            <div class="service-description mb-3">
                <h6 class="text-muted mb-2">Deskripsi</h6>
                <p class="text-muted">{{ $service->description ?? 'Tidak ada deskripsi' }}</p>
            </div>

            <div class="row g-3">
                <div class="col-6">
                    <div class="detail-card bg-light p-3 rounded-3">
                        <small class="text-muted d-block mb-1">Harga Dasar</small>
                        <h6 class="mb-0 text-primary">
                            {{ Number::currency($service->base_price, 'IDR', 'id', 0) }}
                        </h6>
                    </div>
                </div>
                <div class="col-6">
                    <div class="detail-card bg-light p-3 rounded-3">
                        <small class="text-muted d-block mb-1">Durasi Estimasi</small>
                        <h6 class="mb-0">{{ $service->estimated_duration }} menit</h6>
                    </div>
                </div>
            </div>

            <hr class="my-3">

            <div class="vehicle-type">
                <h6 class="text-muted mb-2">Jenis Kendaraan</h6>
                <span class="badge bg-info text-dark">{{ ucfirst($service->vehicle_type) }}</span>
            </div>

            <div class="status mt-3">
                @if (!$service->is_active)
                    <div class="alert alert-warning py-2 px-3">
                        <small>Service ini tidak aktif</small>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
