<!-- Sale Items & Stock Movement -->
@if (in_array($sale->status ?? 'completed', ['in_progress', 'completed', 'paid', 'partial', 'pending']))
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <h6 class="mb-0">
                <i class="fas fa-receipt me-2"></i>Sale Details
            </h6>
            <span class="badge bg-primary">Invoice: {{ $sale->invoice_number }}</span>
        </div>

        <div class="card-body">
            @if ($sale->saleItems->count() > 0)
                <ul class="nav nav-tabs mb-3" id="saleTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="sale-items-tab" data-bs-toggle="tab"
                            data-bs-target="#sale-items" type="button" role="tab">
                            <i class="fas fa-list me-1"></i>Sale Items
                        </button>
                    </li>
                    @if (isset($stockMovements) && count($stockMovements) > 0)
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="stock-tab" data-bs-toggle="tab"
                                data-bs-target="#stock-movements" type="button" role="tab">
                                <i class="fas fa-boxes me-1"></i>Stock Movements
                            </button>
                        </li>
                    @endif
                </ul>

                <div class="tab-content" id="saleTabsContent">
                    <!-- Sale Items Tab -->
                    <div class="tab-pane fade show active" id="sale-items" role="tabpanel">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Item</th>
                                        <th>Type</th>
                                        <th>Quantity</th>
                                        <th class="text-end">Unit Price</th>
                                        <th class="text-end">Discount</th>
                                        <th class="text-end">Subtotal</th>
                                        <th>Mechanic</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($sale->saleItems as $item)
                                        <tr>
                                            <td>{{ $item->item_name }}</td>
                                            <td>
                                                <span class="badge bg-info text-dark">
                                                    {{ class_basename($item->saleable_type) }}
                                                </span>
                                            </td>
                                            <td>{{ $item->quantity }}</td>
                                            <td class="text-end">Rp {{ number_format($item->unit_price, 0, ',', '.') }}
                                            </td>
                                            <td class="text-end">
                                                {{ $item->discount_percent > 0 ? $item->discount_percent . '%' : '-' }}
                                            </td>
                                            <td class="text-end fw-semibold">
                                                Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                            </td>
                                            <td>{{ $item->mechanic->name ?? '-' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="5" class="text-end">Subtotal</th>
                                        <th class="text-end">Rp {{ number_format($sale->subtotal, 0, ',', '.') }}</th>
                                        <th></th>
                                    </tr>
                                    <tr>
                                        <th colspan="5" class="text-end">Discount</th>
                                        <th class="text-end">Rp {{ number_format($sale->discount_amount, 0, ',', '.') }}
                                        </th>
                                        <th></th>
                                    </tr>
                                    <tr>
                                        <th colspan="5" class="text-end">Tax</th>
                                        <th class="text-end">Rp {{ number_format($sale->tax_amount, 0, ',', '.') }}
                                        </th>
                                        <th></th>
                                    </tr>
                                    <tr>
                                        <th colspan="5" class="text-end text-primary">Total</th>
                                        <th class="text-end text-primary fs-5">Rp
                                            {{ number_format($sale->total_amount, 0, ',', '.') }}</th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    <!-- Stock Movement Tab -->
                    @if (isset($stockMovements) && count($stockMovements) > 0)
                        <div class="tab-pane fade" id="stock-movements" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Product</th>
                                            <th>Type</th>
                                            <th>Quantity</th>
                                            <th>Before</th>
                                            <th>After</th>
                                            <th>Cost</th>
                                            <th>Reason</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($stockMovements as $move)
                                            <tr>
                                                <td>{{ $move->product->name ?? '-' }}</td>
                                                <td>
                                                    <span
                                                        class="badge {{ $move->type == 'out' ? 'bg-danger' : ($move->type == 'in' ? 'bg-success' : 'bg-secondary') }}">
                                                        {{ ucfirst($move->type) }}
                                                    </span>
                                                </td>
                                                <td>{{ $move->quantity }}</td>
                                                <td>{{ $move->stock_before }}</td>
                                                <td>{{ $move->stock_after }}</td>
                                                <td>Rp {{ number_format($move->unit_cost, 0, ',', '.') }}</td>
                                                <td>{{ $move->reason ?? '-' }}</td>
                                                <td>{{ $move->created_at->format('d M Y H:i') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif
                </div>
            @else
                <div class="text-center text-muted py-4">
                    <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                    <p>No sale items or stock movement data available</p>
                </div>
            @endif
        </div>
    </div>
@endif
