@extends('layouts.admin.main')
@push('css')
    <link rel="stylesheet" href="{{ url('/template') }}/vendor/css/pages/cards-advance.css" />
@endpush
@section('content')
    <div class="row">
        <!-- Revenue Overview Cards -->
        <div class="col-lg-3 col-sm-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div class="card-info">
                            <p class="card-text text-muted mb-1">Total Revenue Today</p>
                            <div class="d-flex align-items-end mb-2">
                                <h4 class="card-title mb-0 me-2">Rp {{ number_format($todayRevenue, 0, ',', '.') }}</h4>
                                <small
                                    class="text-success">{{ $revenueGrowth > 0 ? '+' : '' }}{{ number_format($revenueGrowth, 1) }}%</small>
                            </div>
                        </div>
                        <div class="card-icon">
                            <span class="badge bg-label-success rounded-pill p-2">
                                <i class="ti ti-currency-dollar ti-sm"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-sm-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div class="card-info">
                            <p class="card-text text-muted mb-1">Total Transactions</p>
                            <div class="d-flex align-items-end mb-2">
                                <h4 class="card-title mb-0 me-2">{{ $totalTransactions }}</h4>
                                <small class="text-muted">Today</small>
                            </div>
                        </div>
                        <div class="card-icon">
                            <span class="badge bg-label-primary rounded-pill p-2">
                                <i class="ti ti-receipt ti-sm"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-sm-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div class="card-info">
                            <p class="card-text text-muted mb-1">Active Booking</p>
                            <div class="d-flex align-items-end mb-2">
                                <h4 class="card-title mb-0 me-2">{{ $activeBookings }}</h4>
                                <small class="text-warning">Waiting & Progress</small>
                            </div>
                        </div>
                        <div class="card-icon">
                            <span class="badge bg-label-warning rounded-pill p-2">
                                <i class="ti ti-calendar-event ti-sm"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-sm-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div class="card-info">
                            <p class="card-text text-muted mb-1">Low Product Stock</p>
                            <div class="d-flex align-items-end mb-2">
                                <h4 class="card-title mb-0 me-2">{{ $lowStockCount }}</h4>
                                <small class="text-danger">Attention Required</small>
                            </div>
                        </div>
                        <div class="card-icon">
                            <span class="badge bg-label-danger rounded-pill p-2">
                                <i class="ti ti-alert-triangle ti-sm"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Revenue Chart -->
        <div class="col-lg-8 mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div>
                        <h5 class="card-title mb-0">Income Chart</h5>
                        <small class="text-muted">7 The Last Day</small>
                    </div>
                    <div class="dropdown">
                        <button class="btn p-0" type="button" id="revenueChart" data-bs-toggle="dropdown">
                            <i class="ti ti-dots-vertical ti-sm text-muted"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end">
                            <a class="dropdown-item" href="javascript:void(0);">7 Days</a>
                            <a class="dropdown-item" href="javascript:void(0);">30 Days</a>
                            <a class="dropdown-item" href="javascript:void(0);">90 Days</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div id="revenueChartContainer"></div>
                </div>
            </div>
        </div>

        <!-- Status Booking -->
        <div class="col-lg-4 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="card-title mb-0">Status Booking</h5>
                    <small class="text-muted">Status Summary</small>
                </div>
                <div class="card-body">
                    <div id="bookingStatusChart"></div>
                    <div class="mt-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Pending</span>
                            <span class="fw-semibold">{{ $bookingStatus['pending'] ?? 0 }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Approved</span>
                            <span class="fw-semibold">{{ $bookingStatus['approved'] ?? 0 }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>In Progress</span>
                            <span class="fw-semibold">{{ $bookingStatus['in_progress'] ?? 0 }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Completed</span>
                            <span class="fw-semibold">{{ $bookingStatus['completed'] ?? 0 }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Products -->
        <div class="col-lg-6 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between">
                    <div>
                        <h5 class="card-title mb-0">Best-Selling Products</h5>
                        <small class="text-muted">This Month</small>
                    </div>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        @foreach ($topProducts as $product)
                            <li class="d-flex align-items-center mb-3 pb-1">
                                <div class="badge bg-label-primary rounded p-2 me-3">
                                    <i class="ti ti-package ti-sm"></i>
                                </div>
                                <div class="d-flex justify-content-between w-100 flex-wrap">
                                    <div class="me-2">
                                        <h6 class="mb-0">{{ $product->name }}</h6>
                                        <small class="text-muted">{{ $product->sku }}</small>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <p class="mb-0 fw-semibold">{{ $product->total_sold }} terjual</p>
                                        <p class="ms-3 mb-0 text-success">Rp
                                            {{ number_format($product->total_revenue, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        <!-- Top Services -->
        <div class="col-lg-6 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between">
                    <div>
                        <h5 class="card-title mb-0">Best-Selling Services</h5>
                        <small class="text-muted">This Month</small>
                    </div>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        @foreach ($topServices as $service)
                            <li class="d-flex align-items-center mb-3 pb-1">
                                <div class="badge bg-label-info rounded p-2 me-3">
                                    <i class="ti ti-tool ti-sm"></i>
                                </div>
                                <div class="d-flex justify-content-between w-100 flex-wrap">
                                    <div class="me-2">
                                        <h6 class="mb-0">{{ $service->name }}</h6>
                                        <small class="text-muted">{{ $service->code }}</small>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <p class="mb-0 fw-semibold">{{ $service->total_sold }} kali</p>
                                        <p class="ms-3 mb-0 text-success">Rp
                                            {{ number_format($service->total_revenue, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        <!-- Low Stock Alert -->
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div>
                        <h5 class="card-title mb-0">Low Stock Alert</h5>
                        <small class="text-muted">Products that need to be restocked</small>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="lowStockTbl" class="table table-sm table-responsive"
                            data-ajax="{{ route('dashboard.getLowStockProducts') }}" data-processing="true"
                            data-server-side="true" data-length-menu="[5, 10, 25, 50]" data-ordering="true"
                            data-col-reorder="true">
                            <thead>
                                <tr>
                                    <th data-data="DT_RowIndex" data-orderable="false" data-searchable="false">No</th>
                                    <th data-data="name" data-default-content="-">Produk</th>
                                    <th data-data="sku" data-default-content="-">SKU</th>
                                    <th data-data="stock_quantity" data-default-content="-">Stok</th>
                                    <th data-data="min_stock" data-default-content="-">Min Stok</th>
                                    <th data-data="status" data-default-content="-">Status</th>
                                </tr>
                            </thead>
                        </table>

                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Bookings -->
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div>
                        <h5 class="card-title mb-0">Latest Bookings</h5>
                        <small class="text-muted">Book today</small>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="recentBookingsTbl" class="table table-responsive"
                            data-ajax="{{ route('dashboard.getRecentBookings') }}" data-processing="true"
                            data-server-side="true" data-length-menu="[5, 10, 25, 50]" data-ordering="true"
                            data-col-reorder="true">
                            <thead>
                                <tr>
                                    <th data-data="DT_RowIndex" data-orderable="false" data-searchable="false">No</th>
                                    <th data-data="booking_code" data-default-content="-">Kode</th>
                                    <th data-data="customer" data-default-content="-">Customer</th>
                                    <th data-data="booking_time" data-default-content="-">Time</th>
                                    <th data-data="status" data-default-content="-">Status</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Customers -->
        <div class="col-lg-12 mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div>
                        <h5 class="card-title mb-0">Top Customers</h5>
                        <small class="text-muted">Based on total purchases</small>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="dataTbl" class="table table-responsive"
                            data-ajax="{{ route('dashboard.getDataTopCustomer') }}" data-processing="true"
                            data-server-side="true" data-length-menu="[10, 25, 50, 75, 100]" data-ordering="true"
                            data-col-reorder="true">

                            <thead>
                                <tr>
                                    <th data-data="DT_RowIndex" data-orderable="false" data-searchable="false">No</th>
                                    <th data-data="name" data-default-content="-">Name</th>
                                    <th data-data="phone" data-default-content="-">Phone</th>
                                    <th data-data="visit_count" data-default-content="-">Total Visits</th>
                                    <th data-data="total_spent" data-default-content="-">Total Purchases</th>
                                    <th data-data="vehicle_number" data-default-content="-">Vehicle</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script>
        $('#recentBookingsTbl').DataTable({
            processing: true,
            serverSide: true,
            ajax: $('#recentBookingsTbl').data('ajax'),
            columns: [{
                    data: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'booking_code'
                },
                {
                    data: 'customer'
                },
                {
                    data: 'booking_time'
                },
                {
                    data: 'status'
                }
            ]
        });
        $('#lowStockTbl').DataTable({
            processing: true,
            serverSide: true,
            ajax: $('#lowStockTbl').data('ajax'),
            columns: [{
                    data: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'name'
                },
                {
                    data: 'sku'
                },
                {
                    data: 'stock_quantity'
                },
                {
                    data: 'min_stock'
                },
                {
                    data: 'status'
                }
            ]
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Revenue Chart
            Highcharts.chart('revenueChartContainer', {
                chart: {
                    type: 'area'
                },
                title: {
                    text: null
                },
                xAxis: {
                    categories: @json($revenueChartData['dates'])
                },
                yAxis: {
                    title: {
                        text: 'Income (Rp)'
                    }
                },
                series: [{
                    name: 'Income (Rp)',
                    data: @json($revenueChartData['amounts']),
                    color: '#7367f0'
                }],
                credits: {
                    enabled: false
                },
                plotOptions: {
                    area: {
                        fillColor: {
                            linearGradient: {
                                x1: 0,
                                y1: 0,
                                x2: 0,
                                y2: 1
                            },
                            stops: [
                                [0, 'rgba(115, 103, 240, 0.3)'],
                                [1, 'rgba(115, 103, 240, 0.05)']
                            ]
                        }
                    }
                }
            });

            // Booking Status Chart
            Highcharts.chart('bookingStatusChart', {
                chart: {
                    type: 'pie',
                    height: 250
                },
                title: {
                    text: null
                },
                plotOptions: {
                    pie: {
                        innerSize: '60%',
                        dataLabels: {
                            enabled: true,
                            format: '{point.name}: {point.y}'
                        }
                    }
                },
                series: [{
                    name: 'Booking',
                    data: [{
                            name: 'Pending',
                            y: {{ $bookingStatus['pending'] ?? 0 }},
                            color: '#ffab00'
                        },
                        {
                            name: 'Approved',
                            y: {{ $bookingStatus['approved'] ?? 0 }},
                            color: '#00cfe8'
                        },
                        {
                            name: 'In Progress',
                            y: {{ $bookingStatus['in_progress'] ?? 0 }},
                            color: '#7367f0'
                        },
                        {
                            name: 'Completed',
                            y: {{ $bookingStatus['completed'] ?? 0 }},
                            color: '#28c76f'
                        }
                    ]
                }],
                credits: {
                    enabled: false
                }
            });
        });
    </script>
@endpush
