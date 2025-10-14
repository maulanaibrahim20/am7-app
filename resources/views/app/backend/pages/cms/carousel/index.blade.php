@extends('layouts.admin.main')
@push('css')
    <style>
        .card-img-wrapper {
            position: relative;
            width: 100%;
            height: 220px;
            overflow: hidden;
            border-radius: 0.5rem 0.5rem 0 0;
            background-color: #f8f9fa;
        }

        .card-img-wrapper img.card-img-top {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .foreground-overlay {
            position: absolute;
            top: 10px;
            right: 10px;
            max-width: 30%;
            opacity: 0.9;
        }

        .foreground-overlay img {
            width: 100%;
            height: auto;
            object-fit: contain;
        }

        .active-indicator {
            position: absolute;
            bottom: 10px;
            left: 10px;
            background: rgba(255, 255, 255, 0.8);
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .active-indicator.inactive {
            background: rgba(0, 0, 0, 0.3);
            color: #eee;
        }
    </style>
@endpush
@section('breadcrumb', 'Carousel Management')
@section('page_nav_button')
    <div class="d-flex gap-2">
        <a href="{{ route('cms.carousel.create') }}" class="btn btn-primary d-none d-sm-inline-block" data-toggle="ajaxModal"
            data-title="Carousel | Add New" data-size="xl">
            <i class="fas fa-plus-circle me-2"></i>Add New
        </a>
        <a href="{{ route('cms.carousel.preview') }}" class="btn btn-warning">
            <i class="fas fa-eye me-2"></i>Preview
        </a>
    </div>
@endsection
@section('content')
    <div class="row g-4" id="sortable-carousel">
        @forelse($carousels ?? [] as $carousel)
            <div class="col-12 col-lg-6 col-xl-4" data-id="{{ $carousel->id }}">
                <div class="card carousel-card shadow-sm h-100 position-relative">
                    <div class="order-badge mb-2">
                        <span class="badge bg-dark">#{{ $carousel->order }}</span>
                    </div>

                    <div class="card-img-wrapper">
                        <img src="{{ asset('storage/' . $carousel->background_image) }}" class="card-img-top"
                            alt="{{ $carousel->title }}">

                        @if ($carousel->foreground_image)
                            <div class="foreground-overlay">
                                <img src="{{ asset('storage/' . $carousel->foreground_image) }}" alt="Foreground">
                                <span class="badge bg-info">
                                    <i class="fas fa-layer-group me-1"></i>Overlay
                                </span>
                            </div>
                        @endif

                        @if ($carousel->is_active)
                            <div class="active-indicator">
                                <i class="fas fa-circle text-success"></i> Live
                            </div>
                        @else
                            <div class="active-indicator inactive">
                                <i class="fas fa-circle text-muted"></i> Draft
                            </div>
                        @endif
                    </div>

                    <div class="card-body">
                        <h5 class="card-title fw-bold mb-2">{{ $carousel->title }}</h5>

                        @if ($carousel->subtitle)
                            <p class="card-text text-muted mb-3">
                                <i class="fas fa-quote-left me-1"></i>
                                {{ Str::limit($carousel->subtitle, 60) }}
                            </p>
                        @endif

                        @if ($carousel->button_text)
                            <div class="mb-3 p-2 bg-light rounded">
                                <small class="text-muted d-block mb-1">
                                    <i class="fas fa-mouse-pointer me-1"></i>Button Action:
                                </small>
                                <strong class="d-block">{{ $carousel->button_text }}</strong>
                                @if ($carousel->button_url)
                                    <small class="text-primary">
                                        <i class="fas fa-link me-1"></i>{{ Str::limit($carousel->button_url, 30) }}
                                    </small>
                                @endif
                            </div>
                        @endif

                        <div class="d-flex justify-content-between align-items-center mb-3 text-muted small">
                            <span>
                                <i class="far fa-calendar me-1"></i>
                                {{ $carousel->created_at->format('d M Y') }}
                            </span>
                            <span>
                                <i class="far fa-clock me-1"></i>
                                {{ $carousel->updated_at->diffForHumans() }}
                            </span>
                        </div>

                        <div class="d-grid gap-2">
                            <a href="{{ route('cms.carousel.show', $carousel->id) }}" class="btn btn-outline-info"
                                data-toggle="ajaxModal" data-title="Carousel | Preview" data-size="xl">
                                <i class="fas fa-eye me-2"></i>Preview Carousel
                            </a>
                            <div class="d-flex gap-2">
                                <a href="{{ route('cms.carousel.edit', $carousel->id) }}"
                                    class="btn btn-outline-warning flex-fill rounded-start" data-toggle="ajaxModal"
                                    data-title="Carousel | Edit" data-size="xl">
                                    <i class="fas fa-edit me-1"></i>Edit
                                </a>
                                <form action="{{ route('cms.carousel.destroy', $carousel->id) }}" method="POST"
                                    class="m-0 p-0 flex-fill">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger w-100 rounded-end btn-delete">
                                        <i class="fas fa-trash-alt me-1"></i>Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="text-center py-5">
                    <div class="empty-state">
                        <i class="fas fa-images fa-5x text-muted mb-4"></i>
                        <h3 class="text-muted mb-3">Belum Ada Carousel</h3>
                        <p class="text-muted mb-4">Mulai buat carousel pertama Anda dengan klik tombol di bawah ini</p>
                        <a href="{{ route('cms.carousel.create') }}" class="btn btn-primary btn-lg" data-toggle="ajaxModal"
                            data-title="Carousel | Add New" data-size="xl">
                            <i class="fas fa-plus-circle me-2"></i>Add New
                        </a>
                    </div>
                </div>
            </div>
        @endforelse
    </div>
@endsection
