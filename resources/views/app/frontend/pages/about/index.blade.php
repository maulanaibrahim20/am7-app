@extends('layouts.frontend.main')
@section('content')
    <!-- Page Header Start -->
    <div class="container-fluid page-header mb-5 p-0"
        style="background-image: url({{ url('/landing') }}/img/carousel-bg-1.jpg);">
        <div class="container-fluid page-header-inner py-5">
            <div class="container text-center">
                <h1 class="display-3 text-white mb-3 animated slideInDown">About Us</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-center text-uppercase">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item"><a href="#">Pages</a></li>
                        <li class="breadcrumb-item text-white active" aria-current="page">About</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <!-- Page Header End -->


    <!-- Service Start -->
    <div class="container-xxl py-5">
        <div class="container">
            <div class="row g-4">
                @foreach ($features as $index => $feature)
                    <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="{{ 0.1 + $index * 0.2 }}s">
                        <div class="d-flex {{ $feature->background_style }} py-5 px-4">
                            <i class="{{ $feature->icon }} fa-3x text-primary flex-shrink-0"></i>
                            <div class="ps-4">
                                <h5 class="mb-3">{{ $feature->title }}</h5>
                                <p>{{ $feature->description }}</p>
                                {{-- @if ($feature->link_text)
                                    <a class="text-secondary border-bottom" href="{{ $feature->link_url ?? '#' }}">
                                        {{ $feature->link_text }}
                                    </a>
                                @endif --}}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <!-- Service End -->

    <!-- About Start -->
    <div class="container-xxl py-5">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-6 pt-4" style="min-height: 400px;">
                    <div class="position-relative h-100 wow fadeIn" data-wow-delay="0.1s">
                        <img class="position-absolute img-fluid w-100 h-100" src="{{ Storage::url($about->image) }}"
                            style="object-fit: cover;" alt="">
                        <div class="position-absolute top-0 end-0 mt-n4 me-n4 py-4 px-5"
                            style="background: rgba(0, 0, 0, .08);">
                            <h1 class="display-4 text-white mb-0">
                                {{ $about->experience_years }}
                                <span class="fs-4">{{ $about->experience_label }}</span>
                            </h1>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <h6 class="text-primary text-uppercase">{{ $about->subtitle }}</h6>
                    <h1 class="mb-4">{!! $about->title !!}</h1>
                    <p class="mb-4">{{ $about->description }}</p>

                    <div class="row g-4 mb-3 pb-3">
                        @foreach ($about->features as $index => $feature)
                            <div class="col-12 wow fadeIn" data-wow-delay="{{ 0.1 + $index * 0.2 }}s">
                                <div class="d-flex">
                                    <div class="bg-light d-flex flex-shrink-0 align-items-center justify-content-center mt-1"
                                        style="width: 45px; height: 45px;">
                                        <span
                                            class="fw-bold text-secondary">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                                    </div>
                                    <div class="ps-3">
                                        <h6>{{ $feature->title }}</h6>
                                        <span>{{ $feature->description }}</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- @if ($about->button_text)
                        <a href="{{ $about->button_url ?? '#' }}" class="btn btn-primary py-3 px-5">
                            {{ $about->button_text }}
                            <i class="fa fa-arrow-right ms-3"></i>
                        </a>
                    @endif --}}
                </div>
            </div>
        </div>
    </div>
    <!-- About End -->

    <!-- Fact Start -->
    <div class="container-fluid fact bg-dark my-5 py-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-6 col-lg-3 text-center wow fadeIn" data-wow-delay="0.1s">
                    <i class="fa fa-check fa-2x text-white mb-3"></i>
                    <h2 class="text-white mb-2" data-toggle="counter-up">1234</h2>
                    <p class="text-white mb-0">Years Experience</p>
                </div>
                <div class="col-md-6 col-lg-3 text-center wow fadeIn" data-wow-delay="0.3s">
                    <i class="fa fa-users-cog fa-2x text-white mb-3"></i>
                    <h2 class="text-white mb-2" data-toggle="counter-up">1234</h2>
                    <p class="text-white mb-0">Expert Technicians</p>
                </div>
                <div class="col-md-6 col-lg-3 text-center wow fadeIn" data-wow-delay="0.5s">
                    <i class="fa fa-users fa-2x text-white mb-3"></i>
                    <h2 class="text-white mb-2" data-toggle="counter-up">1234</h2>
                    <p class="text-white mb-0">Satisfied Clients</p>
                </div>
                <div class="col-md-6 col-lg-3 text-center wow fadeIn" data-wow-delay="0.7s">
                    <i class="fa fa-car fa-2x text-white mb-3"></i>
                    <h2 class="text-white mb-2" data-toggle="counter-up">1234</h2>
                    <p class="text-white mb-0">Compleate Projects</p>
                </div>
            </div>
        </div>
    </div>
    <!-- Fact End -->

    <!-- Team Start -->
    <div class="container-xxl py-5">
        <div class="container">
            <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
                <h6 class="text-primary text-uppercase">Our Technicians</h6>
                <h1 class="mb-5">Our Expert Technicians</h1>
            </div>
            <div class="row g-4">
                @forelse ($mechanic as $mech)
                    <div class="col-lg-3 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                        <div class="team-item">
                            <div class="position-relative overflow-hidden">
                                <img class="img-fluid" src="{{ url('/landing') }}/img/team-1.jpg" alt="">
                            </div>
                            <div class="bg-light text-center p-4">
                                <h5 class="fw-bold mb-0">{{ $mech->name }}</h5>
                                <small>{{ $mech->roles->first()->name }}</small>
                            </div>
                        </div>
                    </div>
                @empty
                    <p> No Technician Available</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection
