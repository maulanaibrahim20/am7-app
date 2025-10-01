<ul class="nav nav-tabs mb-3" id="productTab" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="overview-tab" data-bs-toggle="tab" data-bs-target="#overview" type="button"
            role="tab" aria-controls="overview" aria-selected="true">
            Overview
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="stock-tab" data-bs-toggle="tab" data-bs-target="#stock" type="button"
            role="tab" aria-controls="stock" aria-selected="false">
            Stock & Pricing
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="supplier-tab" data-bs-toggle="tab" data-bs-target="#supplier" type="button"
            role="tab" aria-controls="supplier" aria-selected="false">
            Suppliers
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="other-tab" data-bs-toggle="tab" data-bs-target="#other" type="button"
            role="tab" aria-controls="other" aria-selected="false">
            Other Info
        </button>
    </li>
</ul>

<div class="tab-content" id="productTabContent">
    <!-- Overview -->
    <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview-tab">
        <div class="card shadow-sm border-0 rounded-3">
            <div class="card-body">
                <h4 class="mb-3 text-primary fw-bold">{{ $product->name }}</h4>
                <p class="text-muted mb-3">{{ $product->description ?? '-' }}</p>

                <table class="table table-sm table-striped align-middle">
                    <tbody>
                        <tr>
                            <th width="30%">Category</th>
                            <td>{{ $product->category->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>SKU</th>
                            <td><span class="badge bg-dark">{{ $product->sku }}</span></td>
                        </tr>
                        <tr>
                            <th>Unit</th>
                            <td>{{ $product->unit }}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                @if ($product->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-danger">Inactive</span>
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Stock & Pricing -->
    <div class="tab-pane fade" id="stock" role="tabpanel" aria-labelledby="stock-tab">
        <div class="card shadow-sm border-0 rounded-3">
            <div class="card-body">
                <h5 class="mb-3 fw-bold text-secondary">Stock & Pricing</h5>
                <table class="table table-sm table-striped align-middle">
                    <tbody>
                        <tr>
                            <th width="30%">Purchase Price</th>
                            <td>Rp {{ number_format($product->purchase_price, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <th>Selling Price</th>
                            <td class="text-success fw-bold">Rp
                                {{ number_format($product->selling_price, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <th>Stock</th>
                            <td>
                                {{ $product->stock_quantity }}
                                <small class="text-muted">
                                    (Min: {{ $product->min_stock }}, Max: {{ $product->max_stock }})
                                </small>
                            </td>
                        </tr>
                        <tr>
                            <th>Reorder Point</th>
                            <td>{{ $product->reorder_point }} (Qty: {{ $product->reorder_quantity }})</td>
                        </tr>
                        <tr>
                            <th>Avg Daily Usage</th>
                            <td>{{ $product->avg_daily_usage }}</td>
                        </tr>
                        <tr>
                            <th>Lead Time (days)</th>
                            <td>{{ $product->lead_time_days }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="tab-pane fade" id="supplier" role="tabpanel" aria-labelledby="supplier-tab">
        <div class="card shadow-sm border-0 rounded-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold text-secondary mb-0">Suppliers</h5>
                    <button class="btn btn-sm btn-primary" type="button" id="addSupplier">
                        <i class="bi bi-plus-lg"></i> Add Supplier
                    </button>
                </div>

                {{-- Form Tambah Supplier --}}
                <div class="hidden mb-4" id="addSupplierForm">
                    <div class="card card-body border shadow-sm">
                        <form action="{{ route('product.addSupplier', $product->id) }}" method="post" id="ajxForm"
                            data-ajxForm-reset="true">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Supplier</label>
                                    <select name="supplier_id" class="form-select" required>
                                        <option value="">-- Select Supplier --</option>
                                        @foreach ($suppliers as $supplier)
                                            <option value="{{ $supplier->id }}"
                                                @if ($product->suppliers->contains($supplier->id)) disabled @endif>
                                                {{ $supplier->name }} ({{ $supplier->contact_person }})
                                                @if ($product->suppliers->contains($supplier->id))
                                                    - Already Assigned
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>

                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Supplier Price</label>
                                    <input type="number" class="form-control" name="supplier_price" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Min Order Qty</label>
                                    <input type="number" class="form-control" name="min_order_qty" required>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="is_primary"
                                            value="1" id="isPrimary">
                                        <label class="form-check-label" for="isPrimary">
                                            Set as Primary Supplier
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="text-end mt-3">
                                <button type="submit" class="btn btn-success">Save Supplier</button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Tabel Supplier --}}
                @if ($product->suppliers->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped align-middle">
                            <thead>
                                <tr>
                                    <th>Supplier</th>
                                    <th>Contact</th>
                                    <th>Price</th>
                                    <th>Min Order Qty</th>
                                    <th>Primary</th>
                                    <th>Lead Time (days)</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($product->suppliers as $supplier)
                                    <tr>
                                        <td>{{ $supplier->name }}</td>
                                        <td>
                                            {{ $supplier->contact_person ?? '-' }}<br>
                                            <small class="text-muted">{{ $supplier->phone }}</small>
                                        </td>
                                        <td>Rp {{ number_format($supplier->pivot->supplier_price, 0, ',', '.') }}</td>
                                        <td>{{ $supplier->pivot->min_order_qty }}</td>
                                        <td>
                                            @if ($supplier->pivot->is_primary)
                                                <span class="badge bg-success">Yes</span>
                                            @else
                                                <span class="badge bg-secondary">No</span>
                                            @endif
                                        </td>
                                        <td>{{ $supplier->lead_time_days }}</td>
                                        <td>
                                            @if ($supplier->is_active)
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-danger">Inactive</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="alert alert-warning mb-0">
                        No suppliers assigned yet. Please add one.
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Other Info -->
    <div class="tab-pane fade" id="other" role="tabpanel" aria-labelledby="other-tab">
        <div class="card shadow-sm border-0 rounded-3">
            <div class="card-body">
                <h5 class="mb-3 fw-bold text-secondary">Other Information</h5>
                <table class="table table-sm table-striped align-middle">
                    <tbody>
                        <tr>
                            <th width="30%">Brand</th>
                            <td>{{ $product->brand ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Compatible Vehicles</th>
                            <td>{{ $product->compatible_vehicles ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Created At</th>
                            <td>{{ $product->created_at->format('d M Y H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Updated At</th>
                            <td>{{ $product->updated_at->format('d M Y H:i') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('#addSupplier').on('click', function() {
            if ($('#addSupplierForm').hasClass('hidden')) {

                $('#addSupplierForm').slideToggle(300);
                $('#addSupplierForm').removeClass('hidden');
            } else {
                $('#addSupplierForm').slideToggle(300);
                $('#addSupplierForm').addClass('hidden');
            }
        });
    });
</script>
