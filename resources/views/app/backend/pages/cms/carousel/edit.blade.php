<form id="ajxForm" enctype="multipart/form-data" method="post"
    action="{{ route('cms.carousel.update', $carousel->id) }}">
    @csrf
    @method('PUT')
    <div class="card-body p-4 shadow-sm mb-3 bg-body rounded">
        <div class="row g-4">
            <div class="col-lg-6">
                <h6 class="fw-bold mb-3 text-primary">
                    <i class="fas fa-info-circle me-2"></i>Informasi Carousel
                </h6>

                <div class="mb-3">
                    <label for="title" class="form-label fw-semibold">Title <span
                            class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="title" name="title"
                        value="{{ old('title', $carousel->title) }}" required>
                </div>

                <div class="mb-3">
                    <label for="subtitle" class="form-label fw-semibold">Subtitle</label>
                    <textarea class="form-control" id="subtitle" name="subtitle" rows="3">{{ old('subtitle', $carousel->subtitle) }}</textarea>
                </div>

                <div class="mb-3">
                    <label for="button_text" class="form-label fw-semibold">Button Text</label>
                    <input type="text" class="form-control" id="button_text" name="button_text"
                        value="{{ old('button_text', $carousel->button_text) }}">
                </div>

                <div class="mb-3">
                    <label for="button_url" class="form-label fw-semibold">Button URL</label>
                    <input type="url" class="form-control" id="button_url" name="button_url"
                        value="{{ old('button_url', $carousel->button_url) }}">
                </div>

                <div class="row">
                    <div class="col-6">
                        <label for="order" class="form-label fw-semibold">Urutan</label>
                        <input type="number" class="form-control" id="order" name="order"
                            value="{{ old('order', $carousel->order) }}" min="0">
                    </div>
                    <div class="col-6">
                        <label class="form-label fw-semibold d-block">Status</label>
                        <div class="form-check form-switch mt-2">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active"
                                {{ $carousel->is_active ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                <span class="badge {{ $carousel->is_active ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $carousel->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <h6 class="fw-bold mb-3 text-primary">
                    <i class="fas fa-images me-2"></i>Media Carousel
                </h6>

                <div class="mb-4">
                    <label for="background_image" class="form-label fw-semibold">Background Image</label>
                    @if ($carousel->background_image)
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $carousel->background_image) }}" alt="Background"
                                class="img-fluid rounded border" style="max-height: 150px;">
                        </div>
                    @endif
                    <input type="file" class="form-control" id="background_image" name="background_image"
                        accept="image/*">
                    <small class="text-muted">Kosongkan jika tidak ingin mengganti.</small>
                </div>

                <div class="mb-3">
                    <label for="foreground_image" class="form-label fw-semibold">Foreground Image</label>
                    @if ($carousel->foreground_image)
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $carousel->foreground_image) }}" alt="Foreground"
                                class="img-fluid rounded border" style="max-height: 150px;">
                        </div>
                    @endif
                    <input type="file" class="form-control" id="foreground_image" name="foreground_image"
                        accept="image/*">
                    <small class="text-muted">Kosongkan jika tidak ingin mengganti.</small>
                </div>
            </div>
        </div>
    </div>

    <div class="card-footer text-end">
        <a href="{{ route('cms.carousel.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Kembali
        </a>
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save me-2"></i>Update Carousel
        </button>
    </div>
</form>
