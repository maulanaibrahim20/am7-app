@extends('layouts.admin.main')

@section('breadcrumb', 'Features Management')

@section('page_nav_button')
    @if ($canAddMore)
        <a href="{{ route('cms.feature.create') }}" class="btn btn-primary" data-toggle="ajaxModal"
            data-title="Carousel | Add New" data-size="xl">
            <i class="ti ti-plus me-2"></i>Add Feature
        </a>
    @else
        <button type="button" class="btn btn-secondary" disabled>
            <i class="ti ti-info-circle me-2"></i>Max 3 Features
        </button>
    @endif
@endsection

@section('content')
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-primary">
                <div class="card-header bg-primary mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 text-white">
                            <i class="ti ti-show me-2"></i>Features Preview
                        </h5>
                        <span class="badge text-primary">Landing Page View</span>
                    </div>
                </div>
                <div class="card-body">
                    @if ($features->where('is_active', true)->count() > 0)
                        <div class="row g-4">
                            @foreach ($features->where('is_active', true) as $feature)
                                <div class="col-md-4">
                                    <div class="card h-100 {{ $feature->background_style }}">
                                        <div class="card-body text-center">
                                            <div class="mb-3">
                                                <i class="{{ $feature->icon }} text-danger" style="font-size: 3rem; "></i>
                                            </div>
                                            <h5 class="card-title">{{ $feature->title }}</h5>
                                            <p class="card-text text-muted">{{ $feature->description }}</p>
                                            {{-- @if ($feature->link_url && $feature->link_text)
                                                <a href="{{ $feature->link_url }}" class="btn btn-outline-primary"
                                                    target="_blank">
                                                    {{ $feature->link_text }} <i class="ti ti-right-arrow-alt"></i>
                                                </a>
                                            @endif --}}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="ti ti-info-circle" style="font-size: 4rem; color: #ddd;"></i>
                            <p class="text-muted mt-3">No active features to display</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="ti ti-list-ul me-2"></i>Manage Features
                    </h5>
                    <small class="text-muted">Maximum 3 features allowed ({{ $features->count() }}/3)</small>
                </div>
                <div class="card-body">
                    @if ($features->count() > 0)
                        <div class="table-responsive">
                            <table id="dataTbl" class="table table-responsive"
                                data-ajax="{{ route('cms.feature.getData') }}" data-processing="true"
                                data-server-side="true" data-length-menu="[10, 25, 50, 75, 100]" data-ordering="true"
                                data-col-reorder="true">
                                <thead>
                                    <tr>
                                        <th data-data="DT_RowIndex" data-orderable="false" data-searchable="false"
                                            width="30">No</th>
                                        <th data-data="icon" data-orderable="false" data-searchable="false" width="80">
                                            Icon</th>
                                        <th data-data="title">Title</th>
                                        <th data-data="description">Description</th>
                                        <th data-data="order" width="50">Order</th>
                                        <th data-data="is_active" width="100">Status</th>
                                        <th data-data="action" data-class-name="text-center" data-orderable="false"
                                            data-searchable="false" width="120">
                                            Action
                                        </th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="ti ti-package" style="font-size: 4rem; color: #ddd;"></i>
                            <p class="text-muted mt-3">No features created yet</p>
                            <a href="{{ route('cms.feature.create') }}" class="btn btn-primary" data-toggle="ajaxModal"
                                data-title="Carousel | Add New" data-size="xl">
                                <i class="ti ti-plus me-2"></i>Create First Feature
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
