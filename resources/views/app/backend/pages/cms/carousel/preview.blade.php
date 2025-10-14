@extends('layouts.admin.main')
@section('breadcrumb', 'Preview Carousel')
@section('page_nav_button')
    <a href="{{ route('cms.carousel.index') }}" class="btn btn-secondary">
        <i class="ti ti-arrow-back-up me-2"></i>Preview
    </a>
@endsection
@section('content')
    @if ($carousels->isNotEmpty())
        <div id="carouselExample" class="carousel slide" data-bs-ride="carousel">

            <ol class="carousel-indicators">
                @foreach ($carousels as $index => $carousel)
                    <li data-bs-target="#carouselExample" data-bs-slide-to="{{ $index }}"
                        class="{{ $index === 0 ? 'active' : '' }}">
                    </li>
                @endforeach
            </ol>

            <div class="carousel-inner">
                @foreach ($carousels as $index => $carousel)
                    <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                        <img class="d-block w-100" src="{{ asset('storage/' . $carousel->background_image) }}"
                            alt="{{ $carousel->title }}" style="max-height: 500px; object-fit: cover;">

                        @if ($carousel->foreground_image)
                            <img src="{{ asset('storage/' . $carousel->foreground_image) }}"
                                class="position-absolute top-50 start-50 translate-middle" alt="Overlay"
                                style="max-height: 300px; object-fit: contain;">
                        @endif

                        <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 rounded p-3">
                            <h4>{{ $carousel->title }}</h4>
                            @if ($carousel->subtitle)
                                <p>{{ $carousel->subtitle }}</p>
                            @endif
                            @if ($carousel->button_text && $carousel->button_url)
                                <a href="{{ $carousel->button_url }}" class="btn btn-primary" target="_blank">
                                    {{ $carousel->button_text }}
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <a class="carousel-control-prev" href="#carouselExample" role="button" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </a>
            <a class="carousel-control-next" href="#carouselExample" role="button" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </a>

        </div>
    @else
        <div class="text-center py-5 text-muted">
            <i class="fas fa-images fa-5x mb-3"></i>
            <h3>Belum ada carousel aktif</h3>
            <p>Silakan tambahkan carousel baru untuk melihat preview.</p>
        </div>
    @endif
@endsection
