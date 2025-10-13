<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, DB};
use App\Facades\Message;
use App\Http\Controllers\Controller;
use App\Models\InventoryAlert;
use Yajra\DataTables\DataTables;

class InventoryController extends Controller
{
    public function index()
    {
        return view('app.backend.pages.inventory.index');
    }

    public function getData(Request $request)
    {
        $rows = InventoryAlert::with(['product', 'resolvedBy'])
            ->select(['inventory_alerts.*'])
            ->orderBy('is_resolved', 'asc')
            ->orderBy('created_at', 'desc');

        if ($request->filled('alert_type')) {
            $rows->where('alert_type', $request->alert_type);
        }

        if ($request->filled('status')) {
            $isResolved = $request->status === 'resolved' ? 1 : 0;
            $rows->where('is_resolved', $isResolved);
        }

        if ($request->filled('date_from')) {
            $rows->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $rows->whereDate('created_at', '<=', $request->date_to);
        }

        return DataTables::of($rows)
            ->addIndexColumn()
            ->editColumn('product.name', function ($row) {
                if (!$row->product) {
                    return '<span class="text-muted">-</span>';
                }

                $stockBadge = '';
                if ($row->product->stock_quantity <= $row->product->min_stock) {
                    $stockBadge = '<span class="badge bg-danger ms-1">' . $row->product->stock_quantity . '</span>';
                } elseif ($row->product->stock_quantity <= $row->product->reorder_point) {
                    $stockBadge = '<span class="badge bg-warning ms-1">' . $row->product->stock_quantity . '</span>';
                } else {
                    $stockBadge = '<span class="badge bg-success ms-1">' . $row->product->stock_quantity . '</span>';
                }

                return '<strong>' . $row->product->name . '</strong>' . $stockBadge;
            })
            ->editColumn('product.sku', function ($row) {
                return $row->product ? '<code>' . $row->product->sku . '</code>' : '-';
            })
            ->editColumn('alert_type', function ($row) {
                $badges = [
                    'low_stock' => '<span class="badge bg-danger">🔴 Low Stock</span>',
                    'reorder_point' => '<span class="badge bg-warning">🟡 Reorder Point</span>',
                    'max_stock' => '<span class="badge bg-info">🟠 Overstock</span>',
                    'expiry' => '<span class="badge bg-secondary">⏰ Expiry</span>',
                ];
                return $badges[$row->alert_type] ?? '<span class="badge bg-secondary">' . $row->alert_type . '</span>';
            })
            ->editColumn('message', function ($row) {
                return '<small>' . $row->message . '</small>';
            })
            ->editColumn('is_resolved', function ($row) {
                if ($row->is_resolved) {
                    $resolvedBy = $row->resolvedBy ? $row->resolvedBy->name : 'System';
                    $resolvedAt = $row->resolved_at ? $row->resolved_at->format('d M Y H:i') : '-';
                    return '
                        <span class="badge bg-success">✅ Resolved</span><br>
                        <small class="text-muted">by ' . $resolvedBy . '</small><br>
                        <small class="text-muted">' . $resolvedAt . '</small>
                    ';
                }
                return '<span class="badge bg-danger">🔴 Unresolved</span>';
            })
            ->editColumn('created_at', function ($row) {
                return $row->created_at ? $row->created_at->format('d M Y H:i') : '-';
            })
            ->addColumn('action', function ($row) {
                $detailUrl = route('inventory.show', $row->id);
                $resolveUrl = route('inventory.resolve', $row->id);

                $actions = '
                    <div class="d-flex justify-content-start gap-1">
                        <a href="' . $detailUrl . '" class="btn btn-info btn-sm"
                           data-toggle="ajaxModal" data-title="Alert Detail" data-size="xl">
                            <i class="fas fa-eye"></i>
                        </a>';

                if (!$row->is_resolved) {
                    $actions .= '
                        <form action="' . $resolveUrl . '" method="POST"
                              id="ajxForm" class="m-0 p-0">
                            ' . csrf_field() . '
                            <button type="submit" class="btn btn-success btn-sm" title="Mark as Resolved">
                                <i class="fas fa-check"></i>
                            </button>
                        </form>';
                }

                $actions .= '
                    </div>
                ';

                return $actions;
            })
            ->rawColumns(['product.name', 'product.sku', 'alert_type', 'message', 'is_resolved', 'action'])
            ->make();
    }

    public function show($id)
    {
        $data['alert'] = InventoryAlert::with(['product', 'resolvedBy'])->findOrFail($id);

        return view('app.backend.pages.inventory.show', $data);
    }

    public function resolve(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            $alert = InventoryAlert::findOrFail($id);

            if ($alert->is_resolved) {
                return Message::notFound($request, "Alert already resolved");
            }

            $alert->markAsResolved(Auth::id());

            DB::commit();
            return Message::updated($request, "Alert marked as resolved successfully");
        } catch (\Exception $e) {
            DB::rollback();
            return Message::exception($request, $e, "Failed to resolve alert: " . $e->getMessage());
        }
    }


    /**
     * Get filter form
     */
    public function filter()
    {
        return view('app.backend.pages.inventory-alert.filter');
    }

    public function getUnresolvedCount()
    {
        return InventoryAlert::where('is_resolved', false)->count();
    }

    public function getTopAlerts($limit = 5)
    {
        return InventoryAlert::with('product')
            ->where('is_resolved', false)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }
}
