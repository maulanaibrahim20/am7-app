@extends('layouts.admin.main')

@section('breadcrumb', 'About Section')

@section('content')
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-primary">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="text-white mb-0">
                            <i class="ti ti-info-octagon me-2"></i>About Section Preview
                        </h5>
                        <span class="badge bg-white text-primary">Landing Page View</span>
                    </div>
                </div>
                <div class="card-body py-5">
                    <div class="row g-5">
                        <div class="col-lg-6 pt-4" style="min-height: 400px;">
                            <div class="position-relative h-100">
                                @if ($aboutSection->image)
                                    <img class="position-absolute img-fluid w-100 h-100"
                                        src="{{ Storage::url($aboutSection->image) }}"
                                        style="object-fit: cover; border-radius: 8px;" alt="About Image">

                                    @if ($aboutSection->experience_years > 0)
                                        <div class="position-absolute top-0 end-0 mt-n4 me-n4 py-4 px-5"
                                            style="background: rgba(0, 0, 0, .08); border-radius: 8px;">
                                            <h1 class="display-4 text-white mb-0">
                                                {{ $aboutSection->experience_years }}
                                                <span class="fs-4">{{ $aboutSection->experience_label }}</span>
                                            </h1>
                                        </div>
                                    @endif
                                @else
                                    <div
                                        class="position-absolute w-100 h-100 bg-light rounded d-flex align-items-center justify-content-center">
                                        <div class="text-center">
                                            <i class="ti ti-photo-plus" style="font-size: 4rem; color: #ddd;"></i>
                                            <p class="text-muted mt-3">No image uploaded</p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="col-lg-6">
                            @if ($aboutSection->subtitle)
                                <h6 class="text-primary text-uppercase mb-2">{{ $aboutSection->subtitle }}</h6>
                            @endif

                            @if ($aboutSection->title)
                                <h1 class="mb-4">{!! $aboutSection->title !!}</h1>
                            @endif

                            @if ($aboutSection->description)
                                <p class="mb-4">{{ $aboutSection->description }}</p>
                            @endif

                            @if ($aboutSection->features && $aboutSection->features->count() > 0)
                                <div class="row g-4 mb-3 pb-3">
                                    @foreach ($aboutSection->features->sortBy('order') as $index => $feature)
                                        <div class="col-12">
                                            <div class="d-flex">
                                                <div class="bg-light d-flex flex-shrink-0 align-items-center justify-content-center mt-1"
                                                    style="width: 45px; height: 45px; border-radius: 4px;">
                                                    <span class="fw-bold text-secondary">
                                                        {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                                                    </span>
                                                </div>
                                                <div class="ps-3">
                                                    <h6 class="mb-1">{{ $feature->title }}</h6>
                                                    <span class="text-muted">{{ $feature->description }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            {{-- @if ($aboutSection->button_text && $aboutSection->button_url)
                                <a href="{{ $aboutSection->button_url }}" class="btn btn-primary py-3 px-5" target="_blank">
                                    {{ $aboutSection->button_text }}
                                    <i class="fa fa-arrow-right ms-3"></i>
                                </a>
                            @endif --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main About Section Form -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="ti ti-edit me-2"></i>Edit About Section
                    </h5>
                </div>
                <div class="card-body">
                    <form id="ajxForm" action="{{ route('cms.about.update', $aboutSection->id) }}" method="post"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="subtitle" class="form-label">Subtitle</label>
                                <input type="text" class="form-control" id="subtitle" name="subtitle"
                                    placeholder="e.g., // About Us //" value="{{ $aboutSection->subtitle }}">
                                <div class="invalid-feedback"></div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="title" class="form-label">Title</label>
                                <input type="text" class="form-control" id="title" name="title"
                                    placeholder="e.g., We Are The Best Place" value="{{ $aboutSection->title }}">
                                <div class="invalid-feedback"></div>
                            </div>

                            <div class="col-12 mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="4"
                                    placeholder="Enter detailed description">{{ $aboutSection->description }}</textarea>
                                <div class="invalid-feedback"></div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="image" class="form-label">Image</label>
                                <input type="file" class="form-control" id="image" name="image" accept="image/*">
                                <small class="text-muted">Max 2MB (JPEG, PNG, JPG, GIF, SVG)</small>
                                @if ($aboutSection->image)
                                    <div class="mt-2">
                                        <small class="text-success">
                                            <i class="ti ti-check-circle"></i> Current:
                                            {{ basename($aboutSection->image) }}
                                        </small>
                                    </div>
                                @endif
                                <div class="invalid-feedback"></div>
                            </div>

                            <div class="col-md-3 mb-3">
                                <label for="experience_years" class="form-label">Experience Years</label>
                                <input type="number" class="form-control" id="experience_years" name="experience_years"
                                    placeholder="25" value="{{ $aboutSection->experience_years ?? 0 }}">
                                <div class="invalid-feedback"></div>
                            </div>

                            <div class="col-md-3 mb-3">
                                <label for="experience_label" class="form-label">Experience Label</label>
                                <input type="text" class="form-control" id="experience_label" name="experience_label"
                                    placeholder="Experience"
                                    value="{{ $aboutSection->experience_label ?? 'Experience' }}">
                                <div class="invalid-feedback"></div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="button_text" class="form-label">Button Text</label>
                                <input type="text" class="form-control" name="button_text" placeholder="Read More"
                                    value="{{ $aboutSection->button_text }}">
                                <div class="invalid-feedback"></div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="button_url" class="form-label">Button URL</label>
                                <input type="url" class="form-control" name="button_url"
                                    placeholder="https://example.com" value="{{ $aboutSection->button_url }}">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>

                        <div class="mt-3 text-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="ti ti-device-floppy me-2"></i>Save About Section
                            </button>
                            <button type="button" class="btn btn-label-secondary" onclick="window.location.reload()">
                                <i class="ti ti-restore me-2"></i>Reset
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- About Features Section -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0">
                            <i class="ti ti-list-check me-2"></i>About Features
                        </h5>
                        <small class="text-muted">Add key features or highlights</small>
                    </div>
                    @if ($canAddMore)
                        <a href="{{ route('cms.about.createFeature') }}" class="btn btn-primary" data-toggle="ajaxModal"
                            data-title="About Feature | Add New">
                            <i class="ti ti-plus me-1"></i>Add Feature
                        </a>
                    @else
                        <button type="button" class="btn btn-secondary" disabled>
                            <i class="ti ti-info-circle me-2"></i>Max 3 Features
                        </button>
                    @endif
                </div>
                <div class="card-body">
                    @if ($aboutSection->features && $aboutSection->features->count() > 0)
                        <table id="dataTbl" class="table table-responsive"
                            data-ajax="{{ route('cms.about.getData') }}" data-processing="true" data-server-side="true"
                            data-length-menu="[10, 25, 50, 75, 100]" data-ordering="true" data-col-reorder="true">
                            <thead>
                                <tr>
                                    <th data-data="DT_RowIndex" data-orderable="false" data-searchable="false"
                                        width="50">No</th>
                                    <th data-data="title" data-default-content="-">Title</th>
                                    <th data-data="description" data-default-content="-">Description</th>
                                    <th data-data="order" width="60">Order</th>
                                    <th data-data="action" data-class-name="text-center" data-orderable="false"
                                        data-searchable="false" width="120">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                        </table>
                    @else
                        <div class="text-center py-5">
                            <i class="ti ti-list-check" style="font-size: 4rem; color: #ddd;"></i>
                            <p class="text-muted mt-3">No features added yet</p>
                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                data-bs-target="#featureModal" onclick="openCreateFeatureModal()">
                                <i class="ti ti-plus me-1"></i>Add First Feature
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

@endsection
{{-- @push('js')
    <script>
        const features = @json($aboutSection->features ?? []);

        $(document).ready(function() {
            initTooltips();
        });

        function initTooltips() {
            $('[data-bs-toggle="tooltip"]').tooltip();
        }

        // About Section Form Submit
        $('#aboutSectionForm').on('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const submitBtn = $('#submitBtn');
            const originalText = submitBtn.html();

            submitBtn.prop('disabled', true).html(
                '<span class="spinner-border spinner-border-sm me-2"></span>Saving...');
            clearErrors('#aboutSectionForm');

            $.ajax({
                url: '{{ url('admin.about-section.update') }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: response.message,
                            showConfirmButton: false,
                            timer: 2000
                        }).then(() => {
                            window.location.reload();
                        });
                    }
                },
                error: function(xhr) {
                    handleAjaxError(xhr, '#aboutSectionForm');
                },
                complete: function() {
                    submitBtn.prop('disabled', false).html(originalText);
                }
            });
        });

        // Feature Modal Functions
        function openCreateFeatureModal() {
            $('#featureModalTitle').text('Add Feature');
            $('#featureForm')[0].reset();
            $('#featureId').val('');
            $('#featureMethod').val('POST');
            clearErrors('#featureForm');
        }

        function openEditFeatureModal(id) {
            const feature = features.find(f => f.id === id);
            if (!feature) return;

            $('#featureModalTitle').text('Edit Feature');
            $('#featureId').val(feature.id);
            $('#featureMethod').val('PUT');
            $('#feature_icon').val(feature.icon);
            $('#feature_title').val(feature.title);
            $('#feature_description').val(feature.description);

            clearErrors('#featureForm');
            $('#featureModal').modal('show');
        }

        // Feature Form Submit
        $('#featureForm').on('submit', function(e) {
            e.preventDefault();

            const formData = $(this).serializeArray();
            const data = {};
            formData.forEach(item => {
                data[item.name] = item.value;
            });

            const method = $('#featureMethod').val();
            const id = $('#featureId').val();
            let url = '{{ url('admin.about-section.store-feature') }}';

            if (method === 'PUT') {
                url = `{{ url('admin/about-section/features') }}/${id}`;
                data._method = 'PUT';
            }

            const submitBtn = $('#featureSubmitBtn');
            const originalText = submitBtn.html();
            submitBtn.prop('disabled', true).html(
                '<span class="spinner-border spinner-border-sm me-2"></span>Saving...');

            clearErrors('#featureForm');

            $.ajax({
                url: url,
                type: 'POST',
                data: data,
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: response.message,
                            showConfirmButton: false,
                            timer: 2000
                        }).then(() => {
                            window.location.reload();
                        });
                    }
                },
                error: function(xhr) {
                    handleAjaxError(xhr, '#featureForm');
                },
                complete: function() {
                    submitBtn.prop('disabled', false).html(originalText);
                }
            });
        });

        // Delete Feature
        function deleteFeature(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `{{ url('admin/about-section/features') }}/${id}`,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Deleted!',
                                    text: response.message,
                                    showConfirmButton: false,
                                    timer: 1500
                                }).then(() => {
                                    window.location.reload();
                                });
                            }
                        }
                    });
                }
            });
        }

        // Helper Functions
        function clearErrors(formSelector) {
            $(`${formSelector} .is-invalid`).removeClass('is-invalid');
            $(`${formSelector} .invalid-feedback`).text('');
        }

        function handleAjaxError(xhr, formSelector) {
            if (xhr.status === 422) {
                const errors = xhr.responseJSON.errors;

                $.each(errors, function(key, value) {
                    const input = $(`${formSelector} [name="${key}"]`);
                    input.addClass('is-invalid');
                    input.siblings('.invalid-feedback').text(value[0]);
                });

                Swal.fire({
                    icon: 'error',
                    title: 'Validation Error',
                    text: 'Please check the form and try again.',
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: xhr.responseJSON?.message || 'Something went wrong. Please try again.',
                });
            }
        }

        // Clear errors on input
        $('input, textarea, select').on('input change', function() {
            $(this).removeClass('is-invalid');
            $(this).siblings('.invalid-feedback').text('');
        });
    </script>
@endpush --}}
