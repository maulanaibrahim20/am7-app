@extends('layouts.admin.main')
@push('css')
    <link rel="stylesheet" href="{{ url('/template') }}/vendor/libs/datatables-bs5/datatables.bootstrap5.css">
    <link rel="stylesheet" href="{{ url('/template') }}/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css">
    <style>
        .category-item {
            cursor: pointer;
            transition: all 0.2s;
            padding: 0.75rem 1rem;
            border-left: 3px solid transparent;
        }

        .category-item:hover {
            background-color: #f8f9fa;
        }

        .category-item.active {
            background-color: #e7f3ff;
            border-left-color: #696cff;
        }

        .product-detail-section {
            display: none;
        }

        .product-detail-section.active {
            display: block;
        }
    </style>
@endpush

@section('content')
    <div class="row">
        <!-- Sidebar Categories -->
        <div class="col-lg-3 col-md-4">
            <div class="card mb-4">
                <div class="card-body">
                    <button class="btn btn-primary w-100 mb-3" data-bs-toggle="modal" data-bs-target="#addProductModal">
                        <i class="ti ti-plus me-1"></i> Add Product
                    </button>

                    <h6 class="text-uppercase text-muted mb-3" style="font-size: 0.75rem;">Categories</h6>

                    <!-- All Products -->
                    <a href="{{ url('products.index') }}"
                        class="category-item d-flex justify-content-between align-items-center text-decoration-none text-body {{ !request('category') ? 'active' : '' }}">
                        <div>
                            <i class="ti ti-package me-2"></i>
                            <span>All Products</span>
                        </div>
                        <span class="badge bg-label-primary rounded-pill">
                            {{ $category->sum(fn($c) => $c->products->count()) }}
                        </span>
                    </a>

                    <hr class="my-3">

                    <!-- Categories List -->
                    @forelse($category as $cat)
                        <a href="{{ url('products.index', ['category' => $cat->id]) }}"
                            class="category-item d-flex justify-content-between align-items-center text-decoration-none text-body {{ request('category') == $cat->id ? 'active' : '' }}">
                            <div>
                                <i class="ti ti-tag me-2"></i>
                                <span>{{ $cat->name }}</span>
                            </div>
                            <span class="badge bg-label-secondary rounded-pill">
                                {{ $cat->products->count() }}
                            </span>
                        </a>
                    @empty
                        <p class="text-muted text-center small">No categories</p>
                    @endforelse
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title mb-3">Quick Stats</h6>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Low Stock</span>
                        <span class="badge bg-warning">12</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Out of Stock</span>
                        <span class="badge bg-danger">3</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Total Value</span>
                        <span class="fw-semibold">Rp 45.5M</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-lg-9 col-md-8">
            <!-- Products Table -->
            <div class="card mb-4 product-list-section">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        @if (request('category'))
                            {{ $category->find(request('category'))->name ?? 'Products' }}
                        @else
                            All Products
                        @endif
                    </h5>
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-sm btn-outline-secondary" id="filterAll">All</button>
                        <button type="button" class="btn btn-sm btn-outline-warning" id="filterLowStock">Low Stock</button>
                        <button type="button" class="btn btn-sm btn-outline-danger" id="filterOutStock">Out Stock</button>
                    </div>
                </div>
                <div class="card-body">
                    <table id="productsTable" class="table table-hover">
                        <thead>
                            <tr>
                                <th>SKU</th>
                                <th>Product Name</th>
                                <th>Category</th>
                                <th>Brand</th>
                                <th>Stock</th>
                                <th>Unit</th>
                                <th>Purchase Price</th>
                                <th>Selling Price</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($category->flatMap->products->where('is_active', true) as $product)
                                @if (!request('category') || request('category') == $product->category_id)
                                    <tr data-stock="{{ $product->stock_quantity }}" data-min="{{ $product->min_stock }}">
                                        <td><code>{{ $product->sku }}</code></td>
                                        <td>
                                            <strong>{{ $product->name }}</strong>
                                            @if ($product->description)
                                                <br><small
                                                    class="text-muted">{{ Str::limit($product->description, 50) }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-label-primary">{{ $product->category->name }}</span>
                                        </td>
                                        <td>{{ $product->brand ?? '-' }}</td>
                                        <td>
                                            @if ($product->stock_quantity == 0)
                                                <span class="badge bg-danger">{{ $product->stock_quantity }}</span>
                                            @elseif($product->stock_quantity <= $product->min_stock)
                                                <span class="badge bg-warning">{{ $product->stock_quantity }}</span>
                                            @else
                                                <span class="badge bg-success">{{ $product->stock_quantity }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $product->unit }}</td>
                                        <td>Rp {{ number_format($product->purchase_price, 0, ',', '.') }}</td>
                                        <td>Rp {{ number_format($product->selling_price, 0, ',', '.') }}</td>
                                        <td>
                                            <button class="btn btn-sm btn-icon btn-primary view-detail"
                                                data-id="{{ $product->id }}">
                                                <i class="ti ti-eye"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Product Detail Section (Hidden by default) -->
            <div class="card product-detail-section" id="productDetailCard">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <button class="btn btn-sm btn-icon me-2" id="backToList">
                            <i class="ti ti-arrow-left"></i>
                        </button>
                        Product Details
                    </h5>
                    <button class="btn btn-sm btn-label-danger" id="deleteProduct">
                        <i class="ti ti-trash me-1"></i> Delete
                    </button>
                </div>
                <div class="card-body" id="productDetailContent">
                    <!-- Content will be loaded here -->
                </div>
            </div>
        </div>
    </div>

    <!-- Add Product Modal -->
    <div class="modal fade" id="addProductModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ url('products.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Category <span class="text-danger">*</span></label>
                                <select class="form-select" name="category_id" required>
                                    <option value="">Select Category</option>
                                    @foreach ($category as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">SKU <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="sku" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Product Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="name" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Description</label>
                                <textarea class="form-control" name="description" rows="3"></textarea>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Unit</label>
                                <input type="text" class="form-control" name="unit" value="pcs">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Purchase Price <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="purchase_price" step="0.01"
                                    required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Selling Price <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="selling_price" step="0.01" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Initial Stock</label>
                                <input type="number" class="form-control" name="stock_quantity" value="0">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Min Stock</label>
                                <input type="number" class="form-control" name="min_stock" value="5">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Max Stock</label>
                                <input type="number" class="form-control" name="max_stock" value="100">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Reorder Point</label>
                                <input type="number" class="form-control" name="reorder_point" value="10">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Brand</label>
                                <input type="text" class="form-control" name="brand">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Compatible Vehicles</label>
                                <input type="text" class="form-control" name="compatible_vehicles">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-plus me-1"></i> Add Product
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="{{ url('/template') }}/vendor/libs/datatables-bs5/datatables-bootstrap5.js"></script>
    <script>
        $(document).ready(function() {
            // Initialize DataTable
            const table = $('#productsTable').DataTable({
                responsive: true,
                order: [
                    [1, 'asc']
                ],
                pageLength: 25,
                language: {
                    search: "Search:",
                    lengthMenu: "Show _MENU_ products",
                    info: "Showing _START_ to _END_ of _TOTAL_ products"
                }
            });

            // Stock filters
            $('#filterAll').on('click', function() {
                table.search('').draw();
                $('.btn-group button').removeClass('active');
                $(this).addClass('active');
            });

            $('#filterLowStock').on('click', function() {
                $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                    const row = table.row(dataIndex).node();
                    const stock = parseInt($(row).data('stock'));
                    const minStock = parseInt($(row).data('min'));
                    return stock > 0 && stock <= minStock;
                });
                table.draw();
                $.fn.dataTable.ext.search.pop();
                $('.btn-group button').removeClass('active');
                $(this).addClass('active');
            });

            $('#filterOutStock').on('click', function() {
                $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                    const row = table.row(dataIndex).node();
                    const stock = parseInt($(row).data('stock'));
                    return stock === 0;
                });
                table.draw();
                $.fn.dataTable.ext.search.pop();
                $('.btn-group button').removeClass('active');
                $(this).addClass('active');
            });

            // View detail
            $(document).on('click', '.view-detail', function() {
                const productId = $(this).data('id');
                loadProductDetail(productId);
            });

            // Back to list
            $('#backToList').on('click', function() {
                $('.product-list-section').show();
                $('.product-detail-section').removeClass('active');
            });

            // Load product detail
            function loadProductDetail(productId) {
                $('.product-list-section').hide();
                $('.product-detail-section').addClass('active');

                // Load via AJAX
                $.get(`/admin/products/${productId}/detail`, function(data) {
                    $('#productDetailContent').html(data);
                }).fail(function() {
                    $('#productDetailContent').html(`
                        <div class="alert alert-danger">Failed to load product details</div>
                    `);
                });
            }
        });
    </script>
@endpush
