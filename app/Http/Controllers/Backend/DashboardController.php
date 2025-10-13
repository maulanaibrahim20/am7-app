<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\{Booking, Customer, Product, Sale, SaleItem};

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

        $lowStockProducts = Product::whereRaw('stock_quantity <= min_stock')
            ->orderBy('stock_quantity', 'asc')
            ->limit(5)
            ->get();

        $recentBookings = Booking::whereDate('booking_date', today())
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $topCustomers = Customer::orderBy('total_spent', 'desc')
            ->limit(10)
            ->get();

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
            'lowStockProducts',
            'recentBookings',
            'topCustomers'
        ));
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
