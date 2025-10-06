<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class SaleController extends Controller
{
    public function index()
    {
        return view('app.backend.pages.sale.index');
    }

    public function getData(Request $request)
    {
        $rows = Sale::with(['customer', 'cashier']);

        // 🔹 Filter berdasarkan tanggal
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $rows->whereBetween('created_at', [$request->start_date, $request->end_date]);
        } elseif ($request->filled('start_date')) {
            $rows->whereDate('created_at', '>=', $request->start_date);
        } elseif ($request->filled('end_date')) {
            $rows->whereDate('created_at', '<=', $request->end_date);
        } else {
            $rows->whereDate('created_at', Carbon::today());
        }

        if ($request->filled('payment_status')) {
            $rows->where('payment_status', $request->payment_status);
        }

        $rows->orderByDesc('id');

        return DataTables::of($rows)
            ->addIndexColumn()
            ->editColumn('invoice_number', function ($row) {
                $url = url('sales.show', $row->id);
                return '<a href="' . $url . '" class="text-primary text-decoration-none"
                data-toggle="ajaxModal" data-size="xl"
                data-title="Invoice ' . e($row->invoice_number) . '">' . e($row->invoice_number) . '</a>';
            })
            ->addColumn('customer_name', fn($r) => $r->customer->name ?? '-')
            ->addColumn('cashier_name', fn($r) => $r->cashier->name ?? '-')
            ->editColumn('total_amount', fn($r) => number_format($r->total_amount, 2))
            ->editColumn('paid_amount', fn($r) => number_format($r->paid_amount, 2))
            ->editColumn('change_amount', fn($r) => number_format($r->change_amount, 2))
            ->editColumn('payment_method', function ($r) {
                return ucfirst($r->payment_method);
            })
            ->editColumn('payment_status', function ($r) {
                $color = [
                    'paid' => 'success',
                    'pending' => 'warning',
                    'partial' => 'info',
                ][$r->payment_status] ?? 'secondary';
                return '<span class="badge bg-' . $color . '">' . ucfirst($r->payment_status) . '</span>';
            })
            ->editColumn('created_at', fn($r) => $r->created_at?->format('d M Y H:i') ?? '-')
            ->addColumn('action', function ($r) {
                if ($r->payment_status !== 'paid') {
                    $url = url('sales.edit', $r->id);
                    return '<a href="' . $url . '" class="btn btn-sm btn-info" data-toggle="ajaxModal"
                    data-title="Update Payment ' . e($r->invoice_number) . '"><i class="fas fa-cash-register me-1"></i> Pay</a>';
                }
                return '<span class="badge bg-secondary">No Action</span>';
            })
            ->rawColumns(['invoice_number', 'payment_status', 'action'])
            ->make();
    }
}
