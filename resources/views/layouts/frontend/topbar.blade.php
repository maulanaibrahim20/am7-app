@php
    use App\Models\SiteSetting;
    use Illuminate\Support\Str;

    $siteSetting = SiteSetting::first();
@endphp

<div class="container-fluid bg-light p-0">
    <div class="row gx-0 d-none d-lg-flex">
        <div class="col-lg-7 px-5 text-start">
            <div class="h-100 d-inline-flex align-items-center py-3 me-4">
                <small class="fa fa-map-marker-alt text-primary me-2"></small>
                <small>
                    {{ isset($siteSetting->address) ? Str::limit($siteSetting->address, 40, '...') : 'Your address here' }}
                </small>
            </div>
            <div class="h-100 d-inline-flex align-items-center py-3">
                <small class="far fa-clock text-primary me-2"></small>
                <small>{{ $siteSetting->open_hours ?? 'Open hours' }}</small>
            </div>
        </div>
        <div class="col-lg-5 px-5 text-end">
            <div class="h-100 d-inline-flex align-items-center py-3 me-4">
                <small class="fa fa-phone-alt text-primary me-2"></small>
                <small>{{ $siteSetting->phone ?? 'Your phone' }}</small>
            </div>
            <div class="h-100 d-inline-flex align-items-center">
                @if ($siteSetting->facebook_url)
                    <a class="btn btn-sm-square bg-white text-primary me-1" href="{{ $siteSetting->facebook_url }}">
                        <i class="fab fa-facebook"></i>
                    </a>
                @endif
                @if ($siteSetting->twitter_url)
                    <a class="btn btn-sm-square bg-white text-primary me-1" href="{{ $siteSetting->twitter_url }}">
                        <i class="fab fa-twitter"></i>
                    </a>
                @endif
                @if ($siteSetting->linkedin_url)
                    <a class="btn btn-sm-square bg-white text-primary me-1" href="{{ $siteSetting->linkedin_url }}">
                        <i class="fab fa-linkedin-in"></i>
                    </a>
                @endif
                @if ($siteSetting->instagram_url)
                    <a class="btn btn-sm-square bg-white text-primary me-0" href="{{ $siteSetting->instagram_url }}">
                        <i class="fab fa-instagram"></i>
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>
