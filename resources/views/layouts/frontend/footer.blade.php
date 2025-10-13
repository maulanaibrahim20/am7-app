@php
    use App\Models\SiteSetting;
    use App\Models\Service;

    $siteSetting = SiteSetting::first();
    $data = Service::where('is_active', true)->orderBy('id', 'desc')->take(5)->get();
@endphp

<div class="container-fluid bg-dark text-light footer pt-5 mt-5 wow fadeIn" data-wow-delay="0.1s">
    <div class="container py-5">
        <div class="row g-5">
            <div class="col-lg-4 col-md-6">
                <h4 class="text-light mb-4">Address</h4>
                <p class="mb-2">
                    <i class="fa fa-map-marker-alt me-3"></i>{{ $siteSetting->address ?? 'Your address here' }}
                </p>
                <p class="mb-2">
                    <i class="fa fa-phone-alt me-3"></i>{{ $siteSetting->phone ?? 'Your phone here' }}
                </p>
                <div class="d-flex pt-2">
                    @if ($siteSetting->twitter_url)
                        <a class="btn btn-outline-light btn-social" href="{{ $siteSetting->twitter_url }}">
                            <i class="fab fa-twitter"></i>
                        </a>
                    @endif
                    @if ($siteSetting->facebook_url)
                        <a class="btn btn-outline-light btn-social" href="{{ $siteSetting->facebook_url }}">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                    @endif
                    @if ($siteSetting->linkedin_url)
                        <a class="btn btn-outline-light btn-social" href="{{ $siteSetting->linkedin_url }}">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                    @endif
                    @if ($siteSetting->instagram_url)
                        <a class="btn btn-outline-light btn-social" href="{{ $siteSetting->instagram_url }}">
                            <i class="fab fa-instagram"></i>
                        </a>
                    @endif
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <h4 class="text-light mb-4">Opening Hours</h4>
                <h6 class="text-light">Setiap Hari {{ $siteSetting->open_hours ?? '08:00 - 17:00' }}</h6>
            </div>

            <div class="col-lg-4 col-md-6">
                <h4 class="text-light mb-4">Services</h4>
                @forelse ($data as $ser)
                    <a class="btn btn-link" href="#">{{ $ser->name }}</a>
                @empty
                    <p>No services added yet</p>
                @endforelse
            </div>
        </div>
    </div>

    <div class="container">
        <div class="copyright">
            <div class="row">
                <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                    &copy; <a class="border-bottom" href="#">AM7-APP</a>, All Rights Reserved.
                    Designed by <a class="border-bottom" href="https://htmlcodex.com">Team AM7</a>
                    <br>Distributed by: <a class="border-bottom" href="#" target="_blank">AM7</a>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <div class="footer-menu">
                        <a href="#">Home</a>
                        <a href="#">Cookies</a>
                        <a href="#">Help</a>
                        <a href="#">FAQs</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
