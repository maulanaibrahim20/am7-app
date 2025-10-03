<style>
    .detail-card {
        transition: transform 0.3s ease;
    }

    .detail-card:hover {
        transform: scale(1.02);
    }

    .product-detail-image {
        transition: transform 0.3s ease;
    }

    .product-detail-image:hover {
        transform: scale(1.05);
    }
</style>
<div class="row g-4">
    <div class="col-md-5">
        <div class="product-image-container text-center mb-3">
            <img src="{{ url('/template/img/products/1.png') }}"
                class="img-fluid rounded-3 shadow-sm product-detail-image" alt="{{ $product->name }}"
                style="max-height:300px; object-fit: contain;">
        </div>
    </div>

    <div class="col-md-7">
        <div class="product-details">
            <div class="row g-3">
                <div class="col-6">
                    <div class="detail-card bg-light p-3 rounded-3">
                        <small class="text-muted d-block mb-1">SKU</small>
                        <h6 class="mb-0">{{ $product->sku }}</h6>
                    </div>
                </div>
                <div class="col-6">
                    <div class="detail-card bg-light p-3 rounded-3">
                        <small class="text-muted d-block mb-1">Kategori</small>
                        <h6 class="mb-0">{{ $product->category->name ?? '-' }}</h6>
                    </div>
                </div>
            </div>

            <hr class="my-3">

            <div class="product-description mb-3">
                <h6 class="text-muted mb-2">Deskripsi</h6>
                <p class="text-muted">{{ $product->description ?? 'Tidak ada deskripsi' }}</p>
            </div>

            <div class="pricing-section">
                <div class="row g-3">
                    <div class="col-6">
                        <div class="detail-card bg-light p-3 rounded-3">
                            <small class="text-muted d-block mb-1">Harga Beli</small>
                            <h6 class="mb-0 text-secondary">
                                {{ Number::currency($product->purchase_price, 'IDR', 'id', 0) }}
                            </h6>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="detail-card bg-light p-3 rounded-3">
                            <small class="text-muted d-block mb-1">Harga Jual</small>
                            <h6 class="mb-0 text-primary">
                                {{ Number::currency($product->selling_price, 'IDR', 'id', 0) }}
                            </h6>
                        </div>
                    </div>
                </div>
            </div>

            <hr class="my-3">

            <div class="stock-info">
                <h6 class="text-muted mb-2">Informasi Stok</h6>
                <div class="row g-3">
                    <div class="col-4">
                        <small class="text-muted d-block">Stok</small>
                        <span class="fw-bold">
                            {{ $product->stock_quantity }} {{ $product->unit }}
                        </span>
                    </div>
                    <div class="col-4">
                        <small class="text-muted d-block">Reorder Point</small>
                        <span class="fw-bold">{{ $product->reorder_point }}</span>
                    </div>
                    <div class="col-4">
                        <small class="text-muted d-block">Lead Time</small>
                        <span class="fw-bold">{{ $product->lead_time_days }} hari</span>
                    </div>
                </div>
                @if ($product->stock_quantity <= $product->min_stock)
                    <div class="alert alert-danger mt-2 mb-0 py-2 px-3">
                        <small>Stok Rendah - Segera Lakukan Pemesanan</small>
                    </div>
                @elseif($product->stock_quantity >= $product->max_stock)
                    <div class="alert alert-warning mt-2 mb-0 py-2 px-3">
                        <small>Stok Berlebih - Perlu Peninjauan</small>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
