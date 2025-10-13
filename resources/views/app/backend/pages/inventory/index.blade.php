@extends('layouts.admin.main')
@section('breadcrumb', 'Inventory Alerts')
@section('content')
    @php
        use App\Models\InventoryAlert;
    @endphp
    <div class="row mb-3">
        <!-- Stats Cards -->
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="subheader">Total Unresolved</div>
                    </div>
                    <div class="h1 mb-0">
                        <span class="badge bg-danger fs-3">
                            {{ InventoryAlert::where('is_resolved', false)->count() }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card">
                <div class="card-body">
                    <div class="subheader">Low Stock</div>
                    <div class="h1 mb-0">
                        <span class="badge bg-danger fs-3">
                            {{ InventoryAlert::where('is_resolved', false)->where('alert_type', 'low_stock')->count() }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card">
                <div class="card-body">
                    <div class="subheader">Reorder Point</div>
                    <div class="h1 mb-0">
                        <span class="badge bg-warning fs-3">
                            {{ InventoryAlert::where('is_resolved', false)->where('alert_type', 'reorder_point')->count() }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card">
                <div class="card-body">
                    <div class="subheader">Today's Alerts</div>
                    <div class="h1 mb-0">
                        <span class="badge bg-info fs-3">
                            {{ InventoryAlert::whereDate('created_at', today())->count() }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0">Inventory Alerts Data</h5>
                        <a href="{{ url('inventory-alert.filter') }}" data-toggle="ajaxOffcanvas"
                            data-title="Filter | Inventory Alerts" data-size="end"
                            class="fas fa-filter text-secondary fs-4">
                        </a>
                    </div>
                    <table id="dataTbl" class="table table-responsive" data-ajax="{{ route('inventory.getData') }}"
                        data-processing="true" data-server-side="true" data-length-menu="[10, 25, 50, 75, 100]"
                        data-ordering="true" data-col-reorder="true">
                        <thead>
                            <tr>
                                <th data-data="DT_RowIndex" data-orderable="false" data-searchable="false">No</th>
                                <th data-data="product.name" data-default-content="-">Product</th>
                                <th data-data="product.sku" data-default-content="-">SKU</th>
                                <th data-data="alert_type">Alert Type</th>
                                <th data-data="message">Message</th>
                                <th data-data="is_resolved" data-orderable="false">Status</th>
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
