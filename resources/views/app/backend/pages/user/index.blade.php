@extends('layouts.admin.main')
@section('breadcrumb', 'User Management')
@section('page_nav_button')
    <a href="{{ route('user.create') }}" class="btn btn-primary d-none d-sm-inline-block" data-toggle="ajaxModal"
        data-title="User | Add New">
        Add New
    </a>
@endsection
@section('content')
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <div class="col-12 text-end">
                        <a href="{{ route('user.filter') }}" data-toggle="ajaxOffcanvas" data-title="User | Add New"
                            data-size="end" class="fas fa-filter text-secondary fs-4">
                        </a>
                    </div>
                    <table id="dataTbl" class="table table-responsive" data-ajax="{{ route('user.getData') }}"
                        data-processing="true" data-server-side="true" data-length-menu="[10, 25, 50, 75, 100]"
                        data-ordering="true" data-col-reorder="true">
                        <thead>
                            <tr>
                                <th data-data="DT_RowIndex" data-orderable="false" data-searchable="false">NO</th>
                                <th data-data="name" data-default-content="-">Name</th>
                                <th data-data="phone" data-default-content="-">Phone</th>
                                <th data-data="email">Email</th>
                                <th data-data="is_active">Status</th>
                                <th data-data="is_verified">Verified</th>
                                <th data-data="created_at">Created At</th>
                                <th data-data="action" data-class-name="text-center" data-orderable="false"
                                    data-searchable="false">Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
