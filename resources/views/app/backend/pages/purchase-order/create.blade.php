@extends('layouts.admin.main')

@section('breadcrumb', 'Purchase Orders / Add New')

@section('page_nav_button')
    <a href="{{ route('purchase-order.index') }}" class="btn btn-secondary d-none d-sm-inline-block">
        <i class="fas fa-arrow-left me-1"></i> Back
    </a>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <form action="{{ route('purchase-order.store') }}" method="post" id="ajxForm" data-ajxForm-reset="true">
                @csrf
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Purchase Order Details</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Supplier <span class="text-danger">*</span></label>
                                <select class="form-select" name="supplier_id" required>
                                    <option value="">Select Supplier</option>
                                    @foreach ($suppliers as $sup)
                                        <option value="{{ $sup->id }}">{{ $sup->name }} - {{ $sup->contact_person }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Order Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" name="order_date" value="{{ date('Y-m-d') }}"
                                    required>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Expected Date</label>
                                <input type="date" class="form-control" name="expected_date">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Status</label>
                                <select name="status" class="form-select">
                                    <option value="draft">Draft</option>
                                    <option value="ordered">Ordered</option>
                                    <option value="partial">Partial</option>
                                    <option value="received">Received</option>
                                    <option value="cancelled">Cancelled</option>
                                </select>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold">Notes</label>
                                <textarea class="form-control" name="notes" rows="3" placeholder="Additional notes..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ITEMS SECTION --}}
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
                                    <tr>
                                        <td>
                                            <select name="items[0][product_id]" class="form-select product-select" required>
                                                <option value="">Select Product</option>
                                                @foreach ($products as $product)
                                                    <option value="{{ $product->id }}">{{ $product->sku }} -
                                                        {{ $product->name }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td><input type="number" class="form-control price-input text-end"
                                                name="items[0][unit_price]" step="0.01" value="0"></td>
                                        <td><input type="number" class="form-control qty-input text-end"
                                                name="items[0][quantity_ordered]" min="1" value="1"></td>
                                        <td><input type="number" class="form-control subtotal-input text-end"
                                                name="items[0][subtotal]" step="0.01" value="0" readonly></td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-danger btn-sm remove-item">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="text-end mt-3">
                            <label class="fw-bold me-2">Total Amount:</label>
                            <input type="number"
                                class="form-control d-inline-block w-auto text-end fw-bold border-0 bg-light"
                                id="total_amount" name="total_amount" step="0.01" value="0" readonly>
                        </div>
                    </div>

                    <div class="card-footer text-end">
                        <button type="reset" class="btn btn-warning me-2">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Submit
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
            let rowIndex = 1;

            // Tambah item
            document.getElementById('add-item').addEventListener('click', function() {
                const tableBody = document.querySelector('#items-table tbody');
                const newRow = document.createElement('tr');
                newRow.innerHTML = `
            <td>
                <select name="items[${rowIndex}][product_id]" class="form-select product-select" required>
                    <option value="">Select Product</option>
                    @foreach ($products as $product)
                        <option value="{{ $product->id }}">{{ $product->sku }} - {{ $product->name }}</option>
                    @endforeach
                </select>
            </td>
            <td><input type="number" class="form-control qty-input text-end" name="items[${rowIndex}][quantity_ordered]" min="1" value="1"></td>
            <td><input type="number" class="form-control price-input text-end" name="items[${rowIndex}][unit_price]" step="0.01" value="0"></td>
            <td><input type="number" class="form-control subtotal-input text-end" name="items[${rowIndex}][subtotal]" step="0.01" value="0" readonly></td>
            <td class="text-center"><button type="button" class="btn btn-danger btn-sm remove-item"><i class="fas fa-times"></i></button></td>
        `;
                tableBody.appendChild(newRow);
                rowIndex++;
            });

            // Hapus item
            document.querySelector('#items-table').addEventListener('click', function(e) {
                if (e.target.closest('.remove-item')) {
                    e.target.closest('tr').remove();
                    calculateTotal();
                }
            });

            // Hitung subtotal otomatis
            document.querySelector('#items-table').addEventListener('input', function(e) {
                if (e.target.classList.contains('qty-input') || e.target.classList.contains(
                        'price-input')) {
                    const row = e.target.closest('tr');
                    const qty = parseFloat(row.querySelector('.qty-input').value) || 0;
                    const price = parseFloat(row.querySelector('.price-input').value) || 0;
                    const subtotal = qty * price;
                    row.querySelector('.subtotal-input').value = subtotal.toFixed(2);
                    calculateTotal();
                }
            });

            // Hitung total keseluruhan
            function calculateTotal() {
                let total = 0;
                document.querySelectorAll('.subtotal-input').forEach(el => {
                    total += parseFloat(el.value) || 0;
                });
                document.getElementById('total_amount').value = total.toFixed(2);
            }
        });
    </script>
@endpush
