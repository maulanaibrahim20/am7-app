@extends('layouts.admin.main')
@section('breadcrumb', 'Customer')
@section('page_nav_button')
    <a href="{{ route('customer.create') }}" class="btn btn-primary d-none d-sm-inline-block" data-toggle="ajaxModal"
        data-title="Customer | Add New">
        Add New
    </a>
@endsection
@section('content')
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <table id="dataTbl" class="table table-responsive" data-ajax="{{ route('customer.getData') }}"
                        data-processing="true" data-server-side="true" data-length-menu="[10, 25, 50, 75, 100]"
                        data-ordering="true" data-col-reorder="true">
                        <thead>
                            <tr>
                                <th data-data="DT_RowIndex" data-orderable="false" data-searchable="false">No</th>
                                <th data-data="name" data-default-content="-">Name</th>
                                <th data-data="phone" data-default-content="-">Phone</th>
                                <th data-data="email" data-default-content="-">Email</th>
                                <th data-data="address" data-default-content="-">Address</th>
                                <th data-data="vehicle_number" data-default-content="-">Vehicle No</th>
                                <th data-data="vehicle_type" data-default-content="-">Vehicle Type</th>
                                <th data-data="total_spent">Total Spent</th>
                                <th data-data="visit_count">Visits</th>
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
