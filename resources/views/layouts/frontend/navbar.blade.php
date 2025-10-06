<nav class="navbar navbar-expand-lg bg-white navbar-light shadow sticky-top p-0">
    <a href="{{ route('landing.home') }}" class="navbar-brand d-flex align-items-center px-4 px-lg-5">
        <h2 class="m-0 text-primary"><i class="fas fa-car me-3"></i>AM7-APP</h2>
    </a>
    <button type="button" class="navbar-toggler me-4" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarCollapse">
        <div class="navbar-nav ms-auto p-4 p-lg-0">
            <a href="{{ route('landing.home') }}"
                class="nav-item nav-link {{ Request::routeIs('landing.home') ? 'active' : '' }}">Home</a>
            <a href="{{ route('landing.about') }}"
                class="nav-item nav-link {{ Request::routeIs('landing.about') ? 'active' : '' }}">About</a>
            <a href="{{ route('landing.services') }}"
                class="nav-item nav-link {{ Request::routeIs('landing.services') ? 'active' : '' }}">Services</a>
            {{-- <div class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Pages</a>
                <div class="dropdown-menu fade-up m-0">
                    <a href="booking.html" class="dropdown-item">Booking</a>
                    <a href="team.html" class="dropdown-item">Technicians</a>
                    <a href="testimonial.html" class="dropdown-item">Testimonial</a>
                    <a href="404.html" class="dropdown-item">404 Page</a>
                </div>
            </div> --}}
            <a href="{{ route('landing.contact') }}"
                class="nav-item nav-link {{ Request::routeIs('landing.contact') ? 'active' : '' }}">Contact</a>
        </div>
        <a href="{{ route('login') }}" class="btn btn-primary py-4 px-lg-5 d-none d-lg-block">Login<i
                class="fa fa-arrow-right ms-3"></i></a>
    </div>
</nav>
