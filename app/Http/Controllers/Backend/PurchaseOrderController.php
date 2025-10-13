<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, DB, Validator};
use App\Facades\Message;
use App\Http\Controllers\Controller;
use App\Models\{Product, PurchaseOrder, PurchaseOrderItem, Supplier};
use Yajra\DataTables\DataTables;

class PurchaseOrderController extends Controller
{
    public function index()
    {
        return view('app.backend.pages.purchase-order.index');
    }

    public function getData(Request $request)
    {
        $rows = PurchaseOrder::with(['supplier', 'createdBy'])->select('purchase_orders.*');

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $rows->whereBetween('order_date', [$request->start_date, $request->end_date]);
        } elseif ($request->filled('start_date')) {
            $rows->whereDate('order_date', '>=', $request->start_date);
        } elseif ($request->filled('end_date')) {
            $rows->whereDate('order_date', '<=', $request->end_date);
        }

        if ($request->filled('status')) {
            $rows->where('status', $request->status);
        }

        $rows->orderByDesc('id');

        return DataTables::of($rows)
            ->addIndexColumn()
            ->filterColumn('supplier_name', function ($query, $keyword) {
                $query->whereHas('supplier', function ($q) use ($keyword) {
                    $q->where('name', 'like', "%{$keyword}%");
                });
            })
            ->filterColumn('created_by_name', function ($query, $keyword) {
                $query->whereHas('createdBy', function ($q) use ($keyword) {
                    $q->where('name', 'like', "%{$keyword}%");
                });
            })
            ->editColumn('po_number', function ($row) {
                $url = route('purchase-order.show', $row->id);
                return '<a href="' . $url . '" class="text-primary text-decoration-none"
                data-toggle="ajaxModal" data-size="xl"
                data-title="Purchase Order ' . e($row->po_number) . '">' . e($row->po_number) . '</a>';
            })
            ->addColumn('supplier_name', fn($r) => $r->supplier->name ?? '-')
            ->addColumn('created_by_name', fn($r) => $r->createdBy->name ?? '-')
            ->editColumn('total_amount', fn($r) => number_format($r->total_amount, 2))
            ->editColumn('status', function ($r) {
                $color = [
                    'draft' => 'secondary',
                    'ordered' => 'primary',
                    'partial' => 'info',
                    'received' => 'success',
                    'cancelled' => 'danger',
                ][$r->status] ?? 'secondary';
                return '<span class="badge bg-' . $color . '">' . ucfirst($r->status) . '</span>';
            })
            ->editColumn('order_date', fn($r) => $r->order_date?->format('d M Y') ?? '-')
            ->editColumn('expected_date', fn($r) => $r->expected_date?->format('d M Y') ?? '-')
            ->addColumn('action', function ($r) {
                $btns = [];

                if (in_array($r->status, ['draft', 'ordered', 'partial'])) {
                    $btns[] = '<a href="' . route('purchase-order.receive', $r->id) . '"
                    class="btn btn-success" data-toggle="ajaxModal"
                    data-title="Receive Items" data-size="xl"><i class="fas fa-box-open"></i></a>';
                    $btns[] = '<a href="' . route('purchase-order.edit', $r->id) . '"
                    class="btn btn-warning" >
                    <i class="fas fa-edit"></i></a>';
                }
                return '<div class="btn-group" role="group">' . implode(' ', $btns) . '</div>';
            })
            ->rawColumns(['po_number', 'status', 'action'])
            ->make(true);
    }

    public function create()
    {
        $data['suppliers'] = Supplier::where('is_active', true)->get();
        $data['products'] = Product::where('is_active', true)->get();

        return view('app.backend.pages.purchase-order.create', $data);
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        $validator = Validator::make($request->all(), [
            'supplier_id'      => 'required|exists:suppliers,id',
            'order_date'       => 'required|date',
            'expected_date'    => 'nullable|date|after_or_equal:order_date',
            'status'           => 'required|in:draft,ordered,partial,received,cancelled',
            'total_amount'     => 'required|numeric|min:0',
            'notes'            => 'nullable|string',
            'items'            => 'required|array|min:1',
            'items.*.product_id'        => 'required|exists:products,id',
            'items.*.quantity_ordered'  => 'required|integer|min:1',
            'items.*.unit_price'        => 'required|numeric|min:0',
            'items.*.subtotal'          => 'required|numeric|min:0',
        ]);

        if (!$validator->passes()) {
            return Message::validator($request, $validator->errors()->first());
        }

        try {
            $poNumber = 'PO-' . now()->format('Ymd') . '-' . str_pad((PurchaseOrder::max('id') + 1), 4, '0', STR_PAD_LEFT);

            $purchaseOrder = PurchaseOrder::create([
                'po_number'     => $poNumber,
                'supplier_id'   => $request->supplier_id,
                'status'        => $request->status,
                'order_date'    => $request->order_date,
                'expected_date' => $request->expected_date,
                'total_amount'  => $request->total_amount,
                'notes'         => $request->notes,
                'created_by'    => Auth::id(),
            ]);

            foreach ($request->items as $item) {
                PurchaseOrderItem::create([
                    'purchase_order_id' => $purchaseOrder->id,
                    'product_id'        => $item['product_id'],
                    'quantity_ordered'  => $item['quantity_ordered'],
                    'quantity_received' => 0,
                    'unit_price'        => $item['unit_price'],
                    'subtotal'          => $item['subtotal'],
                ]);
            }

            DB::commit();

            return Message::created($request, "Purchase order created successfully", [], route('purchase-order.index'));
        } catch (\Exception $e) {
            DB::rollback();
            return Message::exception($request, $e, "Failed to create purchase order. " . $e->getMessage());
        }
    }

    public function show($id)
    {
        $data['purchaseOrder'] = PurchaseOrder::with('items.product', 'supplier')->findOrFail($id);

        return view('app.backend.pages.purchase-order.show', $data);
    }

    public function edit($id)
    {
        $purchaseOrder = PurchaseOrder::with('items.product', 'supplier')->findOrFail($id);

        $data['suppliers'] = Supplier::where('is_active', true)->get();
        $data['products'] = Product::where('is_active', true)->get();
        $data['purchaseOrder'] = $purchaseOrder;

        return view('app.backend.pages.purchase-order.edit', $data);
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();

        $validator = Validator::make($request->all(), [
            'supplier_id'      => 'required|exists:suppliers,id',
            'order_date'       => 'required|date',
            'expected_date'    => 'nullable|date|after_or_equal:order_date',
            'status'           => 'required|in:draft,ordered,partial,received,cancelled',
            'total_amount'     => 'required|numeric|min:0',
            'notes'            => 'nullable|string',
            'items'            => 'required|array|min:1',
            'items.*.product_id'        => 'required|exists:products,id',
            'items.*.quantity_ordered'  => 'required|integer|min:1',
            'items.*.unit_price'        => 'required|numeric|min:0',
            'items.*.subtotal'          => 'required|numeric|min:0',
        ]);

        if (!$validator->passes()) {
            return Message::validator($request, $validator->errors()->first());
        }


        try {
            $purchaseOrder = PurchaseOrder::findOrFail($id);

            $purchaseOrder->update([
                'supplier_id'   => $request->supplier_id,
                'status'        => $request->status,
                'order_date'    => $request->order_date,
                'expected_date' => $request->expected_date,
                'total_amount'  => $request->total_amount,
                'notes'         => $request->notes,
                'updated_by'    => Auth::id(),
            ]);

            $purchaseOrder->items()->delete();

            foreach ($request->items as $item) {
                PurchaseOrderItem::create([
                    'purchase_order_id' => $purchaseOrder->id,
                    'product_id'        => $item['product_id'],
                    'quantity_ordered'  => $item['quantity_ordered'],
                    'quantity_received' => 0,
                    'unit_price'        => $item['unit_price'],
                    'subtotal'          => $item['subtotal'],
                ]);
            }

            DB::commit();

            return Message::updated($request, "Purchase order updated successfully", [], route('purchase-order.index'));
        } catch (\Exception $e) {
            DB::rollback();
            return Message::exception($request, $e, "Failed to update purchase order. " . $e->getMessage());
        }
    }

    public function receive($id)
    {
        $purchaseOrder = PurchaseOrder::with('items.product', 'supplier')->findOrFail($id);

        return view('app.backend.pages.purchase-order.receive', compact('purchaseOrder'));
    }

    public function receiveSubmit(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $purchaseOrder = PurchaseOrder::with('items')->findOrFail($id);

            foreach ($request->items as $itemId => $data) {
                $item = $purchaseOrder->items->firstWhere('id', $itemId);
                if ($item) {
                    $qty = (int) $data['quantity_received'];
                    $item->quantity_received += $qty;
                    $item->save();

                    if ($item->product) {
                        $item->product->updateStock($qty, 'in');
                    }
                }
            }

            $allReceived = $purchaseOrder->items->every(fn($i) => $i->quantity_received >= $i->quantity_ordered);
            $purchaseOrder->status = $allReceived ? 'received' : 'partial';
            $purchaseOrder->save();

            DB::commit();
            return Message::updated($request, "Items received successfully", [], route('purchase-order.index'));
        } catch (\Exception $e) {
            DB::rollback();
            return Message::exception($request, $e, "Failed to receive items.");
        }
    }

    public function updateStatus(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'status' => 'required|in:draft,ordered,partial,received,cancelled',
            ]);

            $purchaseOrder = PurchaseOrder::findOrFail($id);

            $purchaseOrder->status = $request->status;
            $purchaseOrder->save();

            DB::commit();

            return Message::success($request, 'Purchase order status updated successfully!', [], route('purchase-order.index'));
        } catch (\Exception $e) {
            DB::rollback();
            return Message::error($request, 'An unexpected error occurred while updating the status.');
        }
    }
}
