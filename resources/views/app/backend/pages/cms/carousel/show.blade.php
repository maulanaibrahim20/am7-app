<div class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-inner">
        <div class="carousel-item active">
            <div class="position-relative">
                <img src="{{ asset('storage/' . $carousel->background_image) }}" class="d-block w-100"
                    alt="{{ $carousel->title }}" style="max-height: 500px; object-fit: cover;">

                @if ($carousel->foreground_image)
                    <img src="{{ asset('storage/' . $carousel->foreground_image) }}" alt="Overlay"
                        class="position-absolute top-50 start-50 translate-middle"
                        style="max-height: 300px; object-fit: contain;">
                @endif

                <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 rounded p-3">
                    <h3 class="text-white">{{ $carousel->title }}</h3>
                    @if ($carousel->subtitle)
                        <p class="text-light">{{ $carousel->subtitle }}</p>
                    @endif
                    @if ($carousel->button_text && $carousel->button_url)
                        <a href="{{ $carousel->button_url }}" class="btn btn-primary" target="_blank">
                            {{ $carousel->button_text }}
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
