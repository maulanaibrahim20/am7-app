@extends('layouts.admin.main')
@section('breadcrumb', 'Purchase Orders')
@section('page_nav_button')
    <a href="{{ route('purchase-order.create') }}" class="btn btn-primary d-none d-sm-inline-block">
        Add New
    </a>
@endsection
@section('content')
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0">Purchase Orders</h5>
                        <a href="{{ route('purchase-order.filter') }}" data-toggle="ajaxOffcanvas"
                            data-title="Filter | Purchase Orders" data-size="end" class="text-secondary fs-4">
                            <i class="fas fa-filter"></i>
                        </a>
                    </div>

                    <table id="dataTbl" class="table table-responsive" data-ajax="{{ route('purchase-order.getData') }}"
                        data-processing="true" data-server-side="true" data-length-menu="[10, 25, 50, 100]"
                        data-ordering="true">
                        <thead>
                            <tr>
                                <th data-data="DT_RowIndex" data-orderable="false" data-searchable="false"
                                    class="text-center">No</th>
                                <th data-data="po_number">PO Number</th>
                                <th data-data="supplier_name">Supplier</th>
                                <th data-data="total_amount">Total</th>
                                <th data-data="status">Status</th>
                                <th data-data="order_date">Order Date</th>
                                <th data-data="expected_date">Expected Date</th>
                                <th data-data="created_by_name">Created By</th>
                                <th data-data="action" data-orderable="false" data-searchable="false" class="text-center">
                                    Action</th>
                            </tr>
                        </thead>
                    </table>

                </div>
            </div>
        </div>
    </div>
@endsection
