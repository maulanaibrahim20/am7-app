@extends('layouts.frontend.main')
@section('content')
    <!-- Carousel Start -->
    <div class="container-fluid p-0 mb-5">
        <div id="header-carousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                @foreach ($carousels as $index => $carousel)
                    <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                        <img class="w-100" src="{{ Storage::url($carousel->background_image) }}" alt="Image">
                        <div class="carousel-caption d-flex align-items-center">
                            <div class="container">
                                <div class="row align-items-center justify-content-center justify-content-lg-start">
                                    <div class="col-10 col-lg-7 text-center text-lg-start">
                                        @if ($carousel->subtitle)
                                            <h6 class="text-white text-uppercase mb-3 animated slideInDown">
                                                {{ $carousel->subtitle }}
                                            </h6>
                                        @endif
                                        <h1 class="display-3 text-white mb-4 pb-3 animated slideInDown">
                                            {{ $carousel->title }}
                                        </h1>
                                        @if ($carousel->button_text)
                                            <a href="{{ $carousel->button_url ?? '#' }}"
                                                class="btn btn-primary py-3 px-5 animated slideInDown">
                                                {{ $carousel->button_text }}
                                                <i class="fa fa-arrow-right ms-3"></i>
                                            </a>
                                        @endif
                                    </div>

                                    @if ($carousel->foreground_image)
                                        <div class="col-lg-5 d-none d-lg-flex animated zoomIn">
                                            <img class="img-fluid" src="{{ asset($carousel->foreground_image) }}"
                                                alt="">
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <button class="carousel-control-prev" type="button" data-bs-target="#header-carousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>

            <button class="carousel-control-next" type="button" data-bs-target="#header-carousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </div>
    <!-- Carousel End -->


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
                                @if ($feature->link_text)
                                    <a class="text-secondary border-bottom" href="{{ $feature->link_url ?? '#' }}">
                                        {{ $feature->link_text }}
                                    </a>
                                @endif
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
                        <img class="position-absolute img-fluid w-100 h-100" src="{{ asset($about->image) }}"
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

                    @if ($about->button_text)
                        <a href="{{ $about->button_url ?? '#' }}" class="btn btn-primary py-3 px-5">
                            {{ $about->button_text }}
                            <i class="fa fa-arrow-right ms-3"></i>
                        </a>
                    @endif
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


    <!-- Service Start -->
    <div class="container-xxl service py-5">
        <div class="container">
            <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
                <h6 class="text-primary text-uppercase">Our Services</h6>
                <h1 class="mb-5">Explore Our Services</h1>
            </div>

            <div class="accordion" id="servicesAccordion">
                @foreach ($services as $index => $service)
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="heading-{{ $service->id }}">
                            <button class="accordion-button {{ $index != 0 ? 'collapsed' : '' }}" type="button"
                                data-bs-toggle="collapse" data-bs-target="#collapse-{{ $service->id }}"
                                aria-expanded="{{ $index == 0 ? 'true' : 'false' }}"
                                aria-controls="collapse-{{ $service->id }}">
                                <i class="fa fa-cogs fa-lg me-2 text-primary"></i>
                                {{ $service->name }}
                            </button>
                        </h2>
                        <div id="collapse-{{ $service->id }}"
                            class="accordion-collapse collapse {{ $index == 0 ? 'show' : '' }}"
                            aria-labelledby="heading-{{ $service->id }}" data-bs-parent="#servicesAccordion">
                            <div class="accordion-body">
                                <div class="row g-4">
                                    <div class="col-md-6" style="min-height: 250px;">
                                        <div class="position-relative h-100">
                                            <img class="position-absolute img-fluid w-100 h-100"
                                                src="{{ asset('landing/img/service-' . (($index % 4) + 1) . '.jpg') }}"
                                                style="object-fit: cover;" alt="{{ $service->name }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <h3 class="mb-3">{{ $service->name }}</h3>
                                        <p class="mb-4">
                                            {{ $service->description ?? 'No description available.' }}
                                        </p>
                                        <p><i class="fa fa-check text-success me-3"></i>Base Price: Rp
                                            {{ number_format($service->base_price, 0, ',', '.') }}</p>
                                        <p><i class="fa fa-check text-success me-3"></i>Estimated Duration:
                                            {{ $service->estimated_duration }} minutes</p>
                                        <p><i class="fa fa-check text-success me-3"></i>Vehicle Type:
                                            {{ ucfirst($service->vehicle_type) }}</p>
                                        <a href="{{ route('landing.booking.create', ['service_id' => $service->id]) }}"
                                            class="btn btn-primary py-3 px-5 mt-3" data-toggle="ajaxModal"
                                            data-title="Booking " data-size="lg">
                                            Book Now <i class="fa fa-arrow-right ms-3"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <!-- Service End -->

    <!-- Booking Start -->
    <div class="container-fluid bg-secondary booking my-5 wow fadeInUp" data-wow-delay="0.1s">
        <div class="container">
            <div class="row gx-5">
                <div class="col-lg-6 py-5">
                    <div class="py-5">
                        <h1 class="text-white mb-4">Certified and Award Winning Car Repair Service Provider</h1>
                        <p class="text-white mb-0">
                            Eirmod sed tempor lorem ut dolores. Aliquyam sit sadipscing kasd ipsum.
                            Dolor ea et dolore et at sea ea at dolor, justo ipsum duo rebum sea invidunt
                            voluptua. Eos vero eos vero ea et dolore eirmod et. Dolores diam duo invidunt lorem.
                            Elitr ut dolores magna sit. Sea dolore sanctus sed et. Takimata takimata sanctus sed.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Booking End -->


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
    <!-- Team End -->
@endsection
