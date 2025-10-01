@extends('layouts.admin.main')
@section('breadcrumb', 'Service')
@section('page_nav_button')
    <a href="{{ route('service.create') }}" class="btn btn-primary d-none d-sm-inline-block" data-toggle="ajaxModal"
        data-title="Service | Add New" data-size="lg">
        Add New
    </a>
@endsection

@section('content')
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <table id="dataTbl" class="table table-responsive" data-ajax="{{ route('service.getData') }}"
                        data-processing="true" data-server-side="true" data-length-menu="[10, 25, 50, 75, 100]"
                        data-ordering="true" data-col-reorder="true">
                        <thead>
                            <tr>
                                <th data-data="DT_RowIndex" data-orderable="false" data-searchable="false">No</th>
                                <th data-data="code">Code</th>
                                <th data-data="name">Name</th>
                                <th data-data="category_name">Category</th>
                                <th data-data="base_price">Base Price</th>
                                <th data-data="estimated_duration">Est. Duration</th>
                                <th data-data="vehicle_type">Vehicle Type</th>
                                <th data-data="is_active">Status</th>
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
