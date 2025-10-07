@extends('layouts.admin.main')
@section('breadcrumb', 'Sales')
@section('content')
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0">Sales Data</h5>
                        <a href="{{ route('sale.filter') }}" data-toggle="ajaxOffcanvas" data-title="Filter | Sales"
                            data-size="end" class="text-secondary fs-4">
                            <i class="fas fa-filter"></i>
                        </a>
                    </div>
                    <table id="dataTbl" class="table table-responsive " data-ajax="{{ route('sale.getData') }}"
                        data-processing="true" data-server-side="true" data-length-menu="[10, 25, 50, 100]"
                        data-ordering="true">
                        <thead>
                            <tr>
                                <th data-data="DT_RowIndex" data-orderable="false" data-searchable="false">No</th>
                                <th data-data="invoice_number">Invoice</th>
                                <th data-data="customer_name">Customer</th>
                                <th data-data="cashier_name">Cashier</th>
                                <th data-data="payment_method">Method</th>
                                <th data-data="total_amount">Total</th>
                                <th data-data="paid_amount">Paid</th>
                                <th data-data="change_amount">Change</th>
                                <th data-data="payment_status">Status</th>
                                <th data-data="created_at">Created At</th>
                                <th data-data="action" data-orderable="false" data-searchable="false"
                                    data-class-name="text-center">
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
