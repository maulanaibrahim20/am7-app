<div class="mb-3">
    <h5 class="fw-bold mb-2">Purchase Order Details</h5>
    <table class="table table-sm">
        <tr>
            <th width="25%">PO Number</th>
            <td>{{ $purchaseOrder->po_number }}</td>
        </tr>
        <tr>
            <th>Supplier</th>
            <td>{{ $purchaseOrder->supplier->name ?? '-' }}</td>
        </tr>
        <tr>
            <th>Order Date</th>
            <td>{{ $purchaseOrder->order_date->format('d M Y') }}</td>
        </tr>
        <tr>
            <th>Expected Date</th>
            <td>{{ optional($purchaseOrder->expected_date)->format('d M Y') ?? '-' }}</td>
        </tr>
        <tr>
            <th>Status</th>
            <td>
                <form action="{{ route('purchase-order.updateStatus', $purchaseOrder->id) }}" method="POST"
                    class="d-flex align-items-center gap-2" id="ajxForm" data-ajxForm-reset="false">
                    @csrf
                    @method('PUT')
                    <select name="status" class="form-select form-select-sm w-auto"
                        {{ $purchaseOrder->status === 'received' || $purchaseOrder->status === 'cancelled' ? 'disabled' : '' }}>
                        <option value="draft" {{ $purchaseOrder->status === 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="ordered" {{ $purchaseOrder->status === 'ordered' ? 'selected' : '' }}>Ordered
                        </option>
                        <option value="partial" {{ $purchaseOrder->status === 'partial' ? 'selected' : '' }} disabled>
                            Partial
                        </option>
                        <option value="received" {{ $purchaseOrder->status === 'received' ? 'selected' : '' }} disabled>
                            Received
                        </option>
                        <option value="cancelled" {{ $purchaseOrder->status === 'cancelled' ? 'selected' : '' }}>
                            Cancelled</option>
                    </select>
                    @if (!in_array($purchaseOrder->status, ['received', 'cancelled']))
                        <button type="submit" class="btn btn-sm btn-primary">Update</button>
                    @endif
                </form>
            </td>
        </tr>
    </table>
</div>

<h5 class="fw-bold mb-2">Order Items</h5>
<table class="table table-bordered align-middle">
    <thead class="table-light text-center">
        <tr>
            <th>No</th>
            <th>Product</th>
            <th>Qty Ordered</th>
            <th>Qty Received</th>
            <th>Unit Price</th>
            <th>Subtotal</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($purchaseOrder->items as $i => $item)
            <tr>
                <td class="text-center">{{ $i + 1 }}</td>
                <td>{{ $item->product->sku ?? '' }} - {{ $item->product->name ?? '' }}</td>
                <td class="text-center">{{ $item->quantity_ordered }}</td>
                <td class="text-center">{{ $item->quantity_received }}</td>
                <td class="text-end">{{ number_format($item->unit_price, 2) }}</td>
                <td class="text-end">{{ number_format($item->subtotal, 2) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

<div class="text-end fw-bold fs-5">
    Total: {{ number_format($purchaseOrder->total_amount, 2) }}
</div>

@if ($purchaseOrder->notes)
    <div class="mt-3">
        <h6 class="fw-bold">Notes:</h6>
        <p>{{ $purchaseOrder->notes }}</p>
    </div>
@endif
