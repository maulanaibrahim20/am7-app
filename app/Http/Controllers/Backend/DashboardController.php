<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\{Booking, Customer, Product, Sale, SaleItem};
use Carbon\Carbon;
use Illuminate\Support\Number;
use Yajra\DataTables\DataTables;

class DashboardController extends Controller
{
    public function index()
    {
        $todayRevenue = Sale::whereDate('created_at', today())
            ->where('payment_status', 'paid')
            ->sum('total_amount');

        $yesterdayRevenue = Sale::whereDate('created_at', today()->subDay())
            ->where('payment_status', 'paid')
            ->sum('total_amount');

        $revenueGrowth = $yesterdayRevenue > 0
            ? (($todayRevenue - $yesterdayRevenue) / $yesterdayRevenue) * 100
            : 0;

        $totalTransactions = Sale::whereDate('created_at', today())->count();

        $activeBookings = Booking::whereIn('status', ['pending', 'approved', 'in_progress'])->count();

        $lowStockCount = Product::whereRaw('stock_quantity <= min_stock')->count();

        $revenueChartData = $this->getRevenueChartData();

        $bookingStatus = Booking::select('status', DB::raw('count(*) as total'))
            ->whereIn('status', ['pending', 'approved', 'in_progress', 'completed'])
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        $topProducts = $this->getTopProducts();

        $topServices = $this->getTopServices();

        return view('app.backend.pages.dashboard.index', compact(
            'todayRevenue',
            'revenueGrowth',
            'totalTransactions',
            'activeBookings',
            'lowStockCount',
            'revenueChartData',
            'bookingStatus',
            'topProducts',
            'topServices',
        ));
    }

    public function getDataTopCustomer(Request $request)
    {
        $rows = Customer::orderBy('total_spent', 'desc')
            ->limit(10)
            ->get();

        return DataTables::of($rows)
            ->addIndexColumn()
            ->editColumn('total_spent', function ($row) {
                return Number::currency($row->total_spent, 'IDR', 'id', 0);
            })
            ->editColumn('visit_count', function ($row) {
                return $row->visit_count . ' X';
            })
            ->addColumn('vehicle_number', function ($row) {
                if ($row->vehicle_number) {
                    return $row->vehicle_number . ' (' . $row->vehicle_type . ')';
                }
                return '-';
            })
            ->rawColumns(['total_spent', 'vehicle_number'])
            ->make(true);
    }

    public function getRecentBookings(Request $request)
    {
        $rows = Booking::whereDate('booking_date', today())
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return DataTables::of($rows)
            ->addIndexColumn()
            ->addColumn('customer', function ($row) {
                return '<div>' . e($row->customer_name) . '</div>
                    <small class="text-muted">' . e($row->customer_phone) . '</small>';
            })
            ->editColumn('booking_time', function ($row) {
                return Carbon::parse($row->booking_time)->format('H:i');
            })
            ->editColumn('status', function ($row) {
                switch ($row->status) {
                    case 'pending':
                        return '<span class="badge bg-warning">Pending</span>';
                    case 'approved':
                        return '<span class="badge bg-info">Approved</span>';
                    case 'in_progress':
                        return '<span class="badge bg-primary">In Progress</span>';
                    case 'completed':
                        return '<span class="badge bg-success">Completed</span>';
                    default:
                        return '<span class="badge bg-secondary">' . ucfirst($row->status) . '</span>';
                }
            })
            ->rawColumns(['customer', 'status'])
            ->make(true);
    }

    public function getLowStockProducts(Request $request)
    {
        $rows = Product::whereColumn('stock_quantity', '<=', 'min_stock')
            ->orderBy('stock_quantity', 'asc')
            ->limit(5)
            ->get();

        return DataTables::of($rows)
            ->addIndexColumn()
            ->editColumn('status', function ($row) {
                if ($row->stock_quantity <= 0) {
                    return '<span class="badge bg-danger">Habis</span>';
                } elseif ($row->stock_quantity <= $row->reorder_point) {
                    return '<span class="badge bg-warning">Reorder</span>';
                } else {
                    return '<span class="badge bg-info">Rendah</span>';
                }
            })
            ->rawColumns(['status'])
            ->make(true);
    }


    private function getRevenueChartData()
    {
        $dates = [];
        $amounts = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = today()->subDays($i);
            $dates[] = $date->format('d M');

            $revenue = Sale::whereDate('created_at', $date)
                ->where('payment_status', 'paid')
                ->sum('total_amount');

            $amounts[] = (float) $revenue;
        }

        return [
            'dates' => $dates,
            'amounts' => $amounts
        ];
    }

    private function getTopProducts()
    {
        return SaleItem::where('saleable_type', 'App\Models\Product')
            ->whereHas('sale', function ($query) {
                $query->whereMonth('created_at', date('m'))
                    ->whereYear('created_at', date('Y'));
            })
            ->select(
                'saleable_id',
                'item_name as name',
                DB::raw('MAX(saleable_id) as product_id'),
                DB::raw('SUM(quantity) as total_sold'),
                DB::raw('SUM(subtotal) as total_revenue')
            )
            ->groupBy('saleable_id', 'item_name')
            ->orderBy('total_revenue', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($item) {
                $product = Product::find($item->product_id);
                $item->sku = $product ? $product->sku : '-';
                return $item;
            });
    }

    private function getTopServices()
    {
        return SaleItem::where('saleable_type', 'App\Models\Service')
            ->whereHas('sale', function ($query) {
                $query->whereMonth('created_at', date('m'))
                    ->whereYear('created_at', date('Y'));
            })
            ->select(
                'saleable_id',
                'item_name as name',
                DB::raw('MAX(saleable_id) as service_id'),
                DB::raw('COUNT(*) as total_sold'),
                DB::raw('SUM(subtotal) as total_revenue')
            )
            ->groupBy('saleable_id', 'item_name')
            ->orderBy('total_revenue', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($item) {
                // Get code from services table
                $service = \App\Models\Service::find($item->service_id);
                $item->code = $service ? $service->code : '-';
                return $item;
            });
    }
}
