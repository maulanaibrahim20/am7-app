@extends('layouts.admin.main')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">User Profile /</span> Edit Profile</h4>

        <!-- Header -->
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="user-profile-header-banner">
                        <img src="{{ url('/template') }}/img/pages/profile-banner.png" alt="Banner image"
                            class="rounded-top" />
                    </div>
                    <div class="user-profile-header d-flex flex-column flex-sm-row text-sm-start text-center mb-4">
                        <div class="flex-shrink-0 mt-n2 mx-sm-0 mx-auto">
                            <img src="{{ url('/template') }}/img/avatars/14.png" alt="user image"
                                class="d-block h-auto ms-0 ms-sm-4 rounded user-profile-img" />
                        </div>
                        <div class="flex-grow-1 mt-3 mt-sm-5">
                            <div
                                class="d-flex align-items-md-end align-items-sm-start align-items-center justify-content-md-between justify-content-start mx-4 flex-md-row flex-column gap-4">
                                <div class="user-profile-info">
                                    <h4>{{ $user->name }}</h4>
                                    <ul
                                        class="list-inline mb-0 d-flex align-items-center flex-wrap justify-content-sm-start justify-content-center gap-2">
                                        <li class="list-inline-item"><i class="ti ti-color-swatch"></i>
                                            {{ $user->roles->first()->name }}</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--/ Header -->

        <div class="nav-align-top mb-4">
            {{-- ================= NAV PILLS ================= --}}
            <ul class="nav nav-pills mb-3 nav-fill" role="tablist">
                <li class="nav-item">
                    <button type="button" class="nav-link active" id="tab-edit-profile-tab" role="tab"
                        data-bs-toggle="tab" data-bs-target="#tab-edit-profile" aria-controls="tab-edit-profile"
                        aria-selected="true">
                        <i class="ti ti-settings me-1"></i> Edit Profile
                    </button>
                </li>
                <li class="nav-item">
                    <button type="button" class="nav-link" id="tab-change-password-tab" role="tab" data-bs-toggle="tab"
                        data-bs-target="#tab-change-password" aria-controls="tab-change-password" aria-selected="false">
                        <i class="ti ti-lock me-1"></i> Change Password
                    </button>
                </li>
            </ul>

            {{-- ================= TAB CONTENT ================= --}}
            <div class="tab-content">

                {{-- ================= TAB EDIT PROFILE ================= --}}
                <div class="tab-pane fade show active" id="tab-edit-profile" role="tabpanel"
                    aria-labelledby="tab-edit-profile-tab">
                    <div class="card mb-4">
                        <h5 class="card-header">Edit Profile</h5>
                        <div class="card-body">
                            <form method="POST" action="{{ route('setting.profile.updateProfile') }}"
                                enctype="multipart/form-data" id="ajxForm" data-ajxForm-reset="true">
                                @csrf
                                @method('PUT')

                                <div class="d-flex align-items-start gap-4 mb-4">
                                    <img src="{{ url('/template') }}/img/avatars/14.png" alt="user-avatar"
                                        class="d-block w-px-100 h-px-100 rounded" id="uploadedAvatar" />
                                    <div class="button-wrapper">
                                        <label for="upload" class="btn btn-primary me-2 mb-3" tabindex="0">
                                            <span class="d-none d-sm-block">Upload new photo</span>
                                            <i class="ti ti-upload d-block d-sm-none"></i>
                                            <input type="file" id="upload" name="avatar" class="account-file-input"
                                                hidden accept="image/png, image/jpeg" />
                                        </label>
                                        <button type="button" class="btn btn-label-secondary account-image-reset mb-3">
                                            <i class="ti ti-refresh-dot d-block d-sm-none"></i>
                                            <span class="d-none d-sm-block">Reset</span>
                                        </button>
                                        <div class="text-muted small">Allowed JPG, GIF or PNG. Max size of 800K</div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="name" class="form-label">Full Name <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="name" name="name"
                                            value="{{ $user->name }}" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="email" class="form-label">Email <span
                                                class="text-danger">*</span></label>
                                        <input type="email" class="form-control" id="email" name="email"
                                            value="{{ $user->email }}" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="phone" class="form-label">Phone Number</label>
                                        <input type="text" class="form-control" id="phone" name="phone"
                                            value="{{ $user->phone }}">
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <button type="submit" class="btn btn-primary me-2">
                                        <i class="ti ti-device-floppy me-1"></i> Save Changes
                                    </button>
                                    <button type="reset" class="btn btn-label-secondary">
                                        <i class="ti ti-x me-1"></i> Cancel
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    {{-- ================= DELETE ACCOUNT ================= --}}
                    <div class="card border-danger mt-3">
                        <h5 class="card-header border-bottom border-danger">Delete Account</h5>
                        <div class="card-body">
                            <div class="alert alert-warning mb-3">
                                <h5 class="alert-heading mb-2">Are you sure?</h5>
                                <p class="mb-0">Once you delete your account, there is no going back.</p>
                            </div>
                            <form method="POST" action="{{ route('setting.profile.deleteAccount') }}">
                                @csrf
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" name="confirm_delete"
                                        id="confirm_delete" required>
                                    <label class="form-check-label" for="confirm_delete">
                                        I confirm my account deactivation
                                    </label>
                                </div>
                                <button type="submit" class="btn btn-danger">
                                    <i class="ti ti-trash me-1"></i> Deactivate Account
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- ================= TAB CHANGE PASSWORD ================= --}}
                <div class="tab-pane fade" id="tab-change-password" role="tabpanel"
                    aria-labelledby="tab-change-password-tab">
                    <div class="card">
                        <h5 class="card-header">Change Password</h5>
                        <div class="card-body">
                            <form id="ajxForm" data-ajxForm-reset="true" method="POST"
                                action="{{ route('setting.profile.changePassword') }}">
                                @csrf

                                <div class="alert alert-warning" role="alert">
                                    <h5 class="alert-heading mb-2">Password Requirements</h5>
                                    <span>Minimum 8 characters long, uppercase & symbol</span>
                                </div>

                                <div class="mb-3">
                                    <label for="current_password" class="form-label">Current Password <span
                                            class="text-danger">*</span></label>
                                    <div class="input-group input-group-merge">
                                        <input type="password" class="form-control" id="current_password"
                                            name="current_password" placeholder="············" required>
                                        <span class="input-group-text cursor-pointer" id="toggle-current-password">
                                            <i class="ti ti-eye-off"></i>
                                        </span>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="new_password" class="form-label">New Password <span
                                                class="text-danger">*</span></label>
                                        <div class="input-group input-group-merge">
                                            <input type="password" class="form-control" id="new_password"
                                                name="new_password" placeholder="············" required>
                                            <span class="input-group-text cursor-pointer" id="toggle-new-password">
                                                <i class="ti ti-eye-off"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="confirm_password" class="form-label">Confirm Password <span
                                                class="text-danger">*</span></label>
                                        <div class="input-group input-group-merge">
                                            <input type="password" class="form-control" id="confirm_password"
                                                name="confirm_password" placeholder="············" required>
                                            <span class="input-group-text cursor-pointer" id="toggle-confirm-password">
                                                <i class="ti ti-eye-off"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-3">
                                    <button type="submit" class="btn btn-primary me-2">
                                        <i class="ti ti-lock me-1"></i> Change Password
                                    </button>
                                    <button type="reset" class="btn btn-label-secondary">
                                        <i class="ti ti-x me-1"></i> Cancel
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>


    </div>
@endsection
