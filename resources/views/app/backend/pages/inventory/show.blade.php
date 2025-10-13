<div class="row">
    <!-- Alert Information -->
    <div class="col-md-6 mb-3">
        <div class="card bg-light">
            <div class="card-header">
                <h5 class="mb-0">Alert Information</h5>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr>
                        <td class="text-muted" width="40%">Alert Type</td>
                        <td>
                            @php
                                $badges = [
                                    'low_stock' => '<span class="badge bg-danger">🔴 Low Stock</span>',
                                    'reorder_point' => '<span class="badge bg-warning">🟡 Reorder Point</span>',
                                    'max_stock' => '<span class="badge bg-info">🟠 Overstock</span>',
                                    'expiry' => '<span class="badge bg-secondary">⏰ Expiry</span>',
                                ];
                            @endphp
                            {!! $badges[$alert->alert_type] ?? '<span class="badge bg-secondary">' . $alert->alert_type . '</span>' !!}
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">Status</td>
                        <td>
                            @if ($alert->is_resolved)
                                <span class="badge bg-success">✅ Resolved</span>
                            @else
                                <span class="badge bg-danger">🔴 Unresolved</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">Message</td>
                        <td>{{ $alert->message }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Created At</td>
                        <td>{{ $alert->created_at ? $alert->created_at->format('d M Y H:i') : '-' }}</td>
                    </tr>
                    @if ($alert->is_resolved)
                        <tr>
                            <td class="text-muted">Resolved By</td>
                            <td>{{ $alert->resolvedBy ? $alert->resolvedBy->name : 'System' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Resolved At</td>
                            <td>{{ $alert->resolved_at ? $alert->resolved_at->format('d M Y H:i') : '-' }}</td>
                        </tr>
                    @endif
                </table>
            </div>
        </div>
    </div>

    <!-- Product Information -->
    <div class="col-md-6 mb-3">
        <div class="card bg-light">
            <div class="card-header">
                <h5 class="mb-0">Product Information</h5>
            </div>
            <div class="card-body">
                @if ($alert->product)
                    <table class="table table-sm">
                        <tr>
                            <td class="text-muted" width="40%">Product Name</td>
                            <td><strong>{{ $alert->product->name }}</strong></td>
                        </tr>
                        <tr>
                            <td class="text-muted">SKU</td>
                            <td><code>{{ $alert->product->sku }}</code></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Current Stock</td>
                            <td>
                                @php
                                    $stock = $alert->product->stock_quantity;
                                    $min = $alert->product->min_stock;
                                    $reorder = $alert->product->reorder_point;
                                    $badgeClass = 'success';
                                    if ($stock <= $min) {
                                        $badgeClass = 'danger';
                                    } elseif ($stock <= $reorder) {
                                        $badgeClass = 'warning';
                                    }
                                @endphp
                                <span class="badge bg-{{ $badgeClass }}">{{ $stock }}
                                    {{ $alert->product->unit }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted">Min Stock</td>
                            <td>{{ $alert->product->min_stock }} {{ $alert->product->unit }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Reorder Point</td>
                            <td>{{ $alert->product->reorder_point }} {{ $alert->product->unit }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Max Stock</td>
                            <td>{{ $alert->product->max_stock }} {{ $alert->product->unit }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Category</td>
                            <td>{{ $alert->product->category ? $alert->product->category->name : '-' }}</td>
                        </tr>
                    </table>

                    <!-- Action Buttons -->
                    <div class="mt-3">
                        @if (!$alert->is_resolved)
                            <form action="{{ url('inventory-alert.resolve', $alert->id) }}" method="POST"
                                id="ajxForm" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm">
                                    <i class="fas fa-check"></i> Mark as Resolved
                                </button>
                            </form>
                        @endif

                        <a href="{{ route('product.edit', $alert->product->id) }}" class="btn btn-primary "
                            data-toggle="ajaxModal" data-title="Product | Edit" data-size="xl">
                            <i class="fas fa-edit me-1"></i> Edit Product
                        </a>
                    </div>
                @else
                    <div class="alert alert-warning">
                        Product not found or has been deleted.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Stock History (Optional - if you want to show recent movements) -->
@if ($alert->product)
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Recent Stock Movements</h5>
                </div>
                <div class="card-body">
                    @php
                        $recentMovements = \App\Models\StockMovement::where('product_id', $alert->product->id)
                            ->with('createdBy')
                            ->orderBy('created_at', 'desc')
                            ->limit(5)
                            ->get();
                    @endphp

                    @if ($recentMovements->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Type</th>
                                        <th>Quantity</th>
                                        <th>Before</th>
                                        <th>After</th>
                                        <th>By</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($recentMovements as $movement)
                                        <tr>
                                            <td>{{ $movement->created_at->format('d M Y H:i') }}</td>
                                            <td>
                                                @php
                                                    $typeBadges = [
                                                        'in' => '<span class="badge bg-success">IN</span>',
                                                        'out' => '<span class="badge bg-danger">OUT</span>',
                                                        'adjustment' => '<span class="badge bg-warning">ADJUST</span>',
                                                        'return' => '<span class="badge bg-info">RETURN</span>',
                                                    ];
                                                @endphp
                                                {!! $typeBadges[$movement->type] ?? $movement->type !!}
                                            </td>
                                            <td>
                                                @if ($movement->type == 'in' || $movement->type == 'return')
                                                    <span class="text-success">+{{ $movement->quantity }}</span>
                                                @else
                                                    <span class="text-danger">-{{ abs($movement->quantity) }}</span>
                                                @endif
                                            </td>
                                            <td>{{ $movement->stock_before }}</td>
                                            <td>{{ $movement->stock_after }}</td>
                                            <td>{{ $movement->createdBy ? $movement->createdBy->name : '-' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">No stock movements recorded yet.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endif
