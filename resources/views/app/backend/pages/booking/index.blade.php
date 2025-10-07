@extends('layouts.admin.main')
@section('breadcrumb', 'Bookings')
@section('content')
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0">Booking Data</h5>
                        <a href="{{ route('booking.filter') }}" data-toggle="ajaxOffcanvas" data-title="Filter | Bookings"
                            data-size="end" class="fas fa-filter text-secondary fs-4">
                        </a>
                    </div>
                    <table id="dataTbl" class="table table-responsive" data-ajax="{{ route('booking.getData') }}"
                        data-processing="true" data-server-side="true" data-length-menu="[10, 25, 50, 75, 100]"
                        data-ordering="true" data-col-reorder="true">
                        <thead>
                            <tr>
                                <th data-data="DT_RowIndex" data-orderable="false" data-searchable="false">No</th>
                                <th data-data="booking_code">Booking Code</th>
                                <th data-data="customer_name">Customer</th>
                                <th data-data="customer_phone">Phone</th>
                                <th data-data="vehicle_type">Vehicle Type</th>
                                <th data-data="vehicle_number">Plate No</th>
                                <th data-data="booking_date">Date</th>
                                <th data-data="booking_time">Time</th>
                                <th data-data="status">Status</th>
                                <th data-data="created_at">Created At</th>
                                <th data-data="action" data-class-name="text-center" data-orderable="false"
                                    data-searchable="false" data-default-content="-">
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
