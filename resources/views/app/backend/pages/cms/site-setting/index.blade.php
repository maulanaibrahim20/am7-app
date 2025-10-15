@extends('layouts.admin.main')

@section('breadcrumb', 'Site Settings')

@section('content')
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-gradient-primary ">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class=" mb-0">
                            <i class="ti ti-show me-2"></i>Preview
                        </h5>
                        <span class="badge  text-primary">Landing Page View</span>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="card bg-opacity-10 border-0">
                                <div class="card-body">
                                    <h6 class=" mb-3">
                                        <i class="ti ti-map me-2"></i>Contact Information
                                    </h6>

                                    <div class="mb-3">
                                        <small class="d-block mb-1">Address</small>
                                        <p class=" mb-0">
                                            {{ $siteSetting->address ?? 'Not set yet' }}
                                        </p>
                                    </div>

                                    <div class="mb-3">
                                        <small class="d-block mb-1">Open Hours</small>
                                        <p class=" mb-0">
                                            {{ $siteSetting->open_hours ?? 'Not set yet' }}
                                        </p>
                                    </div>

                                    <div>
                                        <small class="d-block mb-1">Phone</small>
                                        <p class=" mb-0">
                                            <i class="ti ti-phone me-1"></i>
                                            {{ $siteSetting->phone ?? 'Not set yet' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card  bg-opacity-10 border-0">
                                <div class="card-body">
                                    <h6 class=" mb-3">
                                        <i class="ti ti-share-alt me-2"></i>Social Media
                                    </h6>

                                    <div class="d-flex flex-wrap gap-2">
                                        @if ($siteSetting->facebook_url)
                                            <a href="{{ $siteSetting->facebook_url }}" target="_blank"
                                                class="btn btn-icon btn-outline-light rounded-circle"
                                                data-bs-toggle="tooltip" title="Facebook">
                                                <i class="ti ti-brand-facebook"></i>
                                            </a>
                                        @else
                                            <button class="btn btn-icon btn-outline-light rounded-circle opacity-50"
                                                disabled>
                                                <i class="ti ti-brand-facebook"></i>
                                            </button>
                                        @endif

                                        @if ($siteSetting->twitter_url)
                                            <a href="{{ $siteSetting->twitter_url }}" target="_blank"
                                                class="btn btn-icon btn-outline-light rounded-circle"
                                                data-bs-toggle="tooltip" title="Twitter">
                                                <i class="ti ti-brand-x"></i>
                                            </a>
                                        @else
                                            <button class="btn btn-icon btn-outline-light rounded-circle opacity-50"
                                                disabled>
                                                <i class="ti ti-brand-x"></i>
                                            </button>
                                        @endif

                                        @if ($siteSetting->linkedin_url)
                                            <a href="{{ $siteSetting->linkedin_url }}" target="_blank"
                                                class="btn btn-icon btn-outline-light rounded-circle"
                                                data-bs-toggle="tooltip" title="LinkedIn">
                                                <i class="ti ti-brand-linkedin"></i>
                                            </a>
                                        @else
                                            <button class="btn btn-icon btn-outline-light rounded-circle opacity-50"
                                                disabled>
                                                <i class="ti ti-brand-linkedin"></i>
                                            </button>
                                        @endif

                                        @if ($siteSetting->instagram_url)
                                            <a href="{{ $siteSetting->instagram_url }}" target="_blank"
                                                class="btn btn-icon btn-outline-light rounded-circle"
                                                data-bs-toggle="tooltip" title="Instagram">
                                                <i class="ti ti-brand-instagram"></i>
                                            </a>
                                        @else
                                            <button class="btn btn-icon btn-outline-light rounded-circle opacity-50"
                                                disabled>
                                                <i class="ti ti-brand-instagram"></i>
                                            </button>
                                        @endif
                                    </div>

                                    @if (
                                        !$siteSetting->facebook_url &&
                                            !$siteSetting->twitter_url &&
                                            !$siteSetting->linkedin_url &&
                                            !$siteSetting->instagram_url)
                                        <p class="small mb-0 mt-3">
                                            <i class="ti ti-info-circle me-1"></i>
                                            No social media links configured yet
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="ti ti-edit me-2"></i>Edit Configuration
                    </h5>
                    <small class="text-muted">Update your website information</small>
                </div>
                <div class="card-body">
                    <form id="ajxForm" action="{{ route('cms.siteSetting.update', $siteSetting->id) }}" method="post">
                        @csrf
                        @method('PUT')
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary mb-3">
                                    <i class="ti ti-info-circle me-2"></i>Contact Information
                                </h6>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="address" class="form-label">Address</label>
                                <textarea class="form-control" id="address" name="address" rows="3" placeholder="Enter complete address">{{ $siteSetting->address }}</textarea>
                                <div class="invalid-feedback"></div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="open_hours" class="form-label">Open Hours</label>
                                <textarea class="form-control" id="open_hours" name="open_hours" rows="3"
                                    placeholder="e.g., Mon - Fri: 9:00 AM - 6:00 PM">{{ $siteSetting->open_hours }}</textarea>
                                <div class="invalid-feedback"></div>
                            </div>

                            <div class="col-md-12 mb-3">
                                <label for="phone" class="form-label">Phone Number</label>
                                <input type="text" class="form-control" id="phone" name="phone"
                                    placeholder="+62 123 4567 8900" value="{{ $siteSetting->phone }}" />
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="row">
                            <div class="col-12">
                                <h6 class="text-primary mb-3">
                                    <i class="ti ti-share me-2"></i>Social Media Links
                                </h6>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="facebook_url" class="form-label">
                                    <i class="ti ti-brand-facebook text-primary me-1"></i>Facebook URL
                                </label>
                                <input type="url" class="form-control" id="facebook_url" name="facebook_url"
                                    placeholder="https://facebook.com/yourpage"
                                    value="{{ $siteSetting->facebook_url }}" />
                                <div class="invalid-feedback"></div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="twitter_url" class="form-label">
                                    <i class="ti ti-brand-x text-info me-1"></i>Twitter URL
                                </label>
                                <input type="url" class="form-control" id="twitter_url" name="twitter_url"
                                    placeholder="https://twitter.com/yourhandle"
                                    value="{{ $siteSetting->twitter_url }}" />
                                <div class="invalid-feedback"></div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="linkedin_url" class="form-label">
                                    <i class="ti ti-brand-linkedin text-primary me-1"></i>LinkedIn URL
                                </label>
                                <input type="url" class="form-control" id="linkedin_url" name="linkedin_url"
                                    placeholder="https://linkedin.com/company/yourcompany"
                                    value="{{ $siteSetting->linkedin_url }}" />
                                <div class="invalid-feedback"></div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="instagram_url" class="form-label">
                                    <i class="ti ti-brand-instagram text-danger me-1"></i>Instagram URL
                                </label>
                                <input type="url" class="form-control" id="instagram_url" name="instagram_url"
                                    placeholder="https://instagram.com/youraccount"
                                    value="{{ $siteSetting->instagram_url }}" />
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>

                        <div class="row mt-4 text-end">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary" id="submitBtn">
                                    <i class="ti ti-save me-2"></i>Save Settings
                                </button>
                                <button type="button" class="btn btn-label-secondary"
                                    onclick="window.location.reload()">
                                    <i class="ti ti-reset me-2"></i>Reset
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
