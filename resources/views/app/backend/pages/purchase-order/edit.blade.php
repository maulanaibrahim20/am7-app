@extends('layouts.admin.main')

@section('breadcrumb', 'Purchase Orders / Edit')

@section('page_nav_button')
    <a href="{{ route('purchase-order.index') }}" class="btn btn-secondary d-none d-sm-inline-block">
        <i class="fas fa-arrow-left me-1"></i> Back
    </a>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <form action="{{ route('purchase-order.update', $purchaseOrder->id) }}" method="post" id="ajxForm"
                data-ajxForm-reset="true">
                @csrf
                @method('PUT')
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Edit Purchase Order</h5>
                        <span class="text-muted">PO Number: {{ $purchaseOrder->po_number }}</span>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Supplier <span class="text-danger">*</span></label>
                                <select class="form-select" name="supplier_id" required>
                                    <option value="">Select Supplier</option>
                                    @foreach ($suppliers as $sup)
                                        <option value="{{ $sup->id }}"
                                            {{ $purchaseOrder->supplier_id == $sup->id ? 'selected' : '' }}>
                                            {{ $sup->name }} - {{ $sup->contact_person }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Order Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" name="order_date"
                                    value="{{ $purchaseOrder->order_date->format('Y-m-d') }}" required>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Expected Date</label>
                                <input type="date" class="form-control" name="expected_date"
                                    value="{{ $purchaseOrder->expected_date ? $purchaseOrder->expected_date->format('Y-m-d') : '' }}">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Status</label>
                                <select name="status" class="form-select">
                                    @foreach (['draft', 'ordered', 'partial', 'received', 'cancelled'] as $status)
                                        <option value="{{ $status }}"
                                            {{ $purchaseOrder->status == $status ? 'selected' : '' }}>
                                            {{ ucfirst($status) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold">Notes</label>
                                <textarea class="form-control" name="notes" rows="3" placeholder="Additional notes...">{{ $purchaseOrder->notes }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ITEMS --}}
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Purchase Order Items</h5>
                        <button type="button" class="btn btn-secondary btn-sm" id="add-item">
                            <i class="fas fa-plus me-1"></i> Add Item
                        </button>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle" id="items-table">
                                <thead class="table-light">
                                    <tr class="text-center">
                                        <th style="width: 35%">Product</th>
                                        <th style="width: 20%">Unit Price</th>
                                        <th style="width: 15%">Qty Ordered</th>
                                        <th style="width: 20%">Subtotal</th>
                                        <th style="width: 10%">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($purchaseOrder->items as $i => $item)
                                        <tr>
                                            <td>
                                                <select name="items[{{ $i }}][product_id]"
                                                    class="form-select product-select" required>
                                                    <option value="">Select Product</option>
                                                    @foreach ($products as $product)
                                                        <option value="{{ $product->id }}"
                                                            {{ $item->product_id == $product->id ? 'selected' : '' }}>
                                                            {{ $product->sku }} - {{ $product->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td><input type="number" class="form-control price-input text-end"
                                                    name="items[{{ $i }}][unit_price]" step="0.01"
                                                    value="{{ $item->unit_price }}"></td>
                                            <td><input type="number" class="form-control qty-input text-end"
                                                    name="items[{{ $i }}][quantity_ordered]" min="1"
                                                    value="{{ $item->quantity_ordered }}"></td>
                                            <td><input type="number" class="form-control subtotal-input text-end"
                                                    name="items[{{ $i }}][subtotal]" step="0.01"
                                                    value="{{ $item->subtotal }}" readonly></td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-danger btn-sm remove-item"><i
                                                        class="fas fa-times"></i></button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="text-end mt-3">
                            <label class="fw-bold me-2">Total Amount:</label>
                            <input type="number"
                                class="form-control d-inline-block w-auto text-end fw-bold border-0 bg-light"
                                id="total_amount" name="total_amount" step="0.01"
                                value="{{ $purchaseOrder->total_amount }}" readonly>
                        </div>
                    </div>

                    <div class="card-footer text-end">
                        <button type="reset" class="btn btn-warning me-2">Reset</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Update
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let rowIndex = {{ $purchaseOrder->items->count() }};

            function initSelect2() {
                $('.product-select').select2({
                    theme: 'bootstrap-5',
                    width: '100%',
                    dropdownParent: $('#items-table').closest('.card')
                });
            }
            initSelect2();

            // tambah item
            $('#add-item').on('click', function() {
                const newRow = `
                <tr>
                    <td>
                        <select name="items[${rowIndex}][product_id]" class="form-select product-select" required>
                            <option value="">Select Product</option>
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}">{{ $product->sku }} - {{ $product->name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td><input type="number" class="form-control price-input text-end" name="items[${rowIndex}][unit_price]" step="0.01" value="0"></td>
                    <td><input type="number" class="form-control qty-input text-end" name="items[${rowIndex}][quantity_ordered]" min="1" value="1"></td>
                    <td><input type="number" class="form-control subtotal-input text-end" name="items[${rowIndex}][subtotal]" step="0.01" value="0" readonly></td>
                    <td class="text-center"><button type="button" class="btn btn-danger btn-sm remove-item"><i class="fas fa-times"></i></button></td>
                </tr>
            `;
                $('#items-table tbody').append(newRow);
                initSelect2();
                rowIndex++;
            });

            // hapus item
            $('#items-table').on('click', '.remove-item', function() {
                $(this).closest('tr').remove();
                calculateTotal();
            });

            // update subtotal
            $('#items-table').on('input', '.qty-input, .price-input', function() {
                const row = $(this).closest('tr');
                const qty = parseFloat(row.find('.qty-input').val()) || 0;
                const price = parseFloat(row.find('.price-input').val()) || 0;
                const subtotal = qty * price;
                row.find('.subtotal-input').val(subtotal.toFixed(2));
                calculateTotal();
            });

            function calculateTotal() {
                let total = 0;
                $('.subtotal-input').each(function() {
                    total += parseFloat($(this).val()) || 0;
                });
                $('#total_amount').val(total.toFixed(2));
            }
        });
    </script>
@endpush
