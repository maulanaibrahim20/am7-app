@extends('layouts.admin.main')
@section('breadcrumb', 'Category')
@section('page_nav_button')
    <a href="{{ route('master.category.create') }}" class="btn btn-primary d-none d-sm-inline-block" data-toggle="ajaxModal"
        data-title="Category | Add New">
        Add New
    </a>
@endsection
@section('content')
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <table id="dataTbl" class="table table-responsive" data-ajax="{{ route('master.category.getData') }}"
                        data-processing="true" data-server-side="true" data-length-menu="[10, 25, 50, 75, 100]"
                        data-ordering="true" data-col-reorder="true">
                        <thead>
                            <tr>
                                <th data-data="DT_RowIndex" data-orderable="false" data-searchable="false">No</th>
                                <th data-data="name" data-default-content="-">Name</th>
                                <th data-data="slug" data-default-content="-">Slug</th>
                                <th data-data="description" data-default-content="-">Description</th>
                                <th data-data="type">Type</th>
                                <th data-data="is_active">Status</th>
                                <th data-data="created_at">Created At</th>
                                <th data-data="action" data-class-name="text-center" data-orderable="false"
                                    data-searchable="false">
                                    Action
                                </th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
