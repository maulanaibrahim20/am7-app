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
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Produk</th>
                                    <th>SKU</th>
                                    <th>Stok</th>
                                    <th>Min Stok</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($lowStockProducts as $product)
                                    <tr>
                                        <td>{{ $product->name }}</td>
                                        <td>{{ $product->sku }}</td>
                                        <td>{{ $product->stock_quantity }}</td>
                                        <td>{{ $product->min_stock }}</td>
                                        <td>
                                            @if ($product->stock_quantity <= 0)
                                                <span class="badge bg-danger">Habis</span>
                                            @elseif($product->stock_quantity <= $product->reorder_point)
                                                <span class="badge bg-warning">Reorder</span>
                                            @else
                                                <span class="badge bg-info">Rendah</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr></tr>
                                    <td colspan="5" class="text-center">Tidak ada data</td>
                                    </tr>
                                @endforelse
                            </tbody>
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
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Kode</th>
                                    <th>Customer</th>
                                    <th>Time</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($recentBookings as $booking)
                                    <tr>
                                        <td>{{ $booking->booking_code }}</td>
                                        <td>
                                            <div>{{ $booking->customer_name }}</div>
                                            <small class="text-muted">{{ $booking->customer_phone }}</small>
                                        </td>
                                        <td>{{ $booking->booking_time }}</td>
                                        <td>
                                            @switch($booking->status)
                                                @case('pending')
                                                    <span class="badge bg-warning">Pending</span>
                                                @break

                                                @case('approved')
                                                    <span class="badge bg-info">Approved</span>
                                                @break

                                                @case('in_progress')
                                                    <span class="badge bg-primary">In Progress</span>
                                                @break

                                                @case('completed')
                                                    <span class="badge bg-success">Completed</span>
                                                @break

                                                @default
                                                    <span class="badge bg-secondary">{{ ucfirst($booking->status) }}</span>
                                            @endswitch
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
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
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nama</th>
                                    <th>Telepon</th>
                                    <th>Total Visits</th>
                                    <th>Total Purchases</th>
                                    <th>Vehicle</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($topCustomers as $index => $customer)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $customer->name }}</td>
                                        <td>{{ $customer->phone }}</td>
                                        <td>{{ $customer->visit_count }} kali</td>
                                        <td class="fw-semibold">Rp
                                            {{ number_format($customer->total_spent, 0, ',', '.') }}</td>
                                        <td>
                                            @if ($customer->vehicle_number)
                                                {{ $customer->vehicle_number }} ({{ $customer->vehicle_type }})
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
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
