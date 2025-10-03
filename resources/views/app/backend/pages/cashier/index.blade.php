@extends('layouts.admin.main')
@section('breadcrumb', 'Cashier')
@push('css')
    <style>
        .card-hover {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            cursor: pointer;
        }

        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-md-4 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-4">Filters</h5>

                        <!-- Multi Range -->
                        <div class="mb-4">
                            <h6 class="mb-3">Multi Range</h6>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="multiRange" id="rangeAll" checked>
                                <label class="form-check-label" for="rangeAll">
                                    All
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="multiRange" id="range10">
                                <label class="form-check-label" for="range10">
                                    &lt;=$10
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="multiRange" id="range100">
                                <label class="form-check-label" for="range100">
                                    $10 - $100
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="multiRange" id="range500">
                                <label class="form-check-label" for="range500">
                                    $100 - $500
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="multiRange" id="range500plus">
                                <label class="form-check-label" for="range500plus">
                                    &gt;= $500
                                </label>
                            </div>
                        </div>

                        <hr>

                        <!-- Brands -->
                        <div class="mb-4">
                            <h6 class="mb-3">Brands</h6>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="brandInsignia">
                                <label class="form-check-label" for="brandInsignia">
                                    Insignia™ <span class="text-muted">(746)</span>
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="brandSamsung" checked>
                                <label class="form-check-label" for="brandSamsung">
                                    Samsung <span class="text-muted">(633)</span>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="brandMetra">
                                <label class="form-check-label" for="brandMetra">
                                    Metra <span class="text-muted">(591)</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-9 col-md-8">
                <div class="row mb-2">
                    <div class="col-12">
                        <div class="card bg-primary text-white mb-4">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                                    <div class="flex-grow-1">
                                        <h5 class="text-white mb-2">
                                            <i class="ti ti-shopping-cart me-2"></i>Current Cart
                                        </h5>

                                        @if ($currentCart)
                                            <div class="customer-info-box">
                                                <div class="d-flex justify-content-between align-items-start">
                                                    <div>
                                                        <p class="mb-1">
                                                            <strong>Session:</strong> {{ $currentCart->session_code }}
                                                        </p>
                                                        <p class="mb-1">
                                                            <strong>Customer:</strong> {{ $currentCart->customer->name }}
                                                            ({{ $currentCart->customer->phone }})
                                                        </p>
                                                        <p class="mb-0">
                                                            <strong>Items:</strong>
                                                            {{ $currentCart->items->sum('quantity') }} |
                                                            <strong>Total:</strong>
                                                            {{ Number::currency($currentCart->getTotalAmount(), 'IDR', 'id', 0) }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <p class="mb-0">
                                                <i class="ti ti-info-circle me-1"></i>
                                                No active cart - Please select a customer to start
                                            </p>
                                        @endif
                                    </div>

                                    <div class="d-flex flex-wrap gap-2">
                                        @if ($currentCart)
                                            <a href="{{ route('cashier.getCart') }}" data-toggle="ajaxModal"
                                                data-title="View Cart" class="btn btn-light" data-size="xl">
                                                <i class="ti ti-shopping-cart me-1"></i> View Cart
                                            </a>
                                            <a href="{{ route('cashier.holdModal') }}" class="btn btn-warning text-dark"
                                                data-toggle="ajaxModal" data-title="Hold">
                                                <i class="ti ti-clock me-1"></i> Hold
                                            </a>
                                            <a href="{{ route('cashier.checkout') }}" class="btn btn-success">
                                                <i class="ti ti-cash me-1"></i> Checkout
                                            </a>
                                        @else
                                            <a href="{{ route('cashier.getSelect-customer') }}" class="btn btn-light"
                                                data-toggle="ajaxModal" data-title="Search Customer">
                                                <i class="ti ti-user-plus me-1"></i> Select Customer
                                            </a>
                                        @endif
                                        <a href="{{ route('cashier.held-carts') }}" class="btn btn-outline-light"
                                            data-toggle="ajaxModal" data-title="Hold" data-size="xl">
                                            <i class="ti ti-list"></i> Held ({{ $heldCartsCount }})
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mb-4">
                    <input type="text" class="form-control" placeholder="Search Product">
                </div>

                <div class="nav-align-top mb-4">
                    <ul class="nav nav-pills mb-3 nav-fill" role="tablist">
                        <li class="nav-item">
                            <button type="button"
                                class="nav-link {{ request('type', 'product') == 'product' ? 'active' : '' }}"
                                role="tab" data-bs-toggle="tab" data-bs-target="#navs-pills-justified-home"
                                aria-controls="navs-pills-justified-home"
                                aria-selected="{{ request('type', 'product') == 'product' ? 'true' : 'false' }}">
                                <i class="tf-icons ti ti-box me-1"></i> Product
                            </button>

                        </li>
                        <li class="nav-item">
                            <button type="button" class="nav-link {{ request('type') == 'service' ? 'active' : '' }}"
                                role="tab" data-bs-toggle="tab" data-bs-target="#navs-pills-justified-profile"
                                aria-controls="navs-pills-justified-profile"
                                aria-selected="{{ request('type') == 'service' ? 'true' : 'false' }}">
                                <i class="tf-icons ti ti-settings me-1"></i> Services
                            </button>

                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane fade {{ request('type', 'product') == 'product' ? 'show active' : '' }}"
                            id="navs-pills-justified-home" role="tabpanel">
                            @if (!$currentCart)
                                <div class="alert alert-warning">
                                    <i class="ti ti-alert-circle"></i>
                                    Please select a customer first before adding product.
                                </div>
                            @endif
                            <div class="row g-4">
                                @forelse ($product as $prod)
                                    <div class="col-lg-4 col-md-6">
                                        <div class="card h-100 card-hover position-relative">
                                            <div class="card-body text-center">
                                                <div class="d-flex justify-content-center mb-3">
                                                    <img src="{{ url('/img') }}/box.png" class="img-fluid"
                                                        alt="{{ $prod->name }}" style="max-height: 200px;">
                                                </div>
                                                <h5 class="card-title mb-2">
                                                    {{ Number::currency($prod->selling_price, 'IDR', 'id', 0) }}
                                                </h5>
                                                <h6 class="mb-2">
                                                    <a href="{{ route('cashier.show', ['id' => $prod->id, 'type' => 'product']) }}"
                                                        data-toggle="ajaxModal" data-title="Detail Product"
                                                        data-size="lg" class="text-decoration-none">
                                                        {{ $prod->name }}
                                                    </a>
                                                </h6>
                                                <p class="text-muted small mb-3">
                                                    {{ $prod->description }}
                                                </p>
                                                <div class="d-flex gap-2 justify-content-center">
                                                    @if ($currentCart)
                                                        <form action="{{ route('cashier.addToCart') }}" method="post"
                                                            id="ajxForm" data-ajxForm-reset="true">
                                                            @csrf
                                                            <input type="hidden" name="item_type" value="product">
                                                            <input type="hidden" name="item_id"
                                                                value="{{ $prod->id }}">

                                                            <div class="input-group input-group-sm mb-2">
                                                                <span class="input-group-text">Qty</span>
                                                                <input type="number" class="form-control"
                                                                    name="quantity" value="1" min="1"
                                                                    max="{{ $prod->stock_quantity }}">
                                                            </div>

                                                            <button type="submit" class="btn btn-primary btn-sm w-100">
                                                                <i class="ti ti-shopping-cart-plus"></i> Add to Cart
                                                            </button>
                                                        </form>
                                                    @else
                                                        <p class="text-muted">Keranjang kosong, silakan tambah customer
                                                            terlebih dahulu.</p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-center">No Product Found</p>
                                @endforelse
                            </div>
                            <div class="mt-4">
                                {{ $product->links() }}
                            </div>
                        </div>

                        <div class="tab-pane fade {{ request('type') == 'service' ? 'show active' : '' }}"
                            id="navs-pills-justified-profile" role="tabpanel">
                            @if (!$currentCart)
                                <div class="alert alert-warning">
                                    <i class="ti ti-alert-circle"></i>
                                    Please select a customer first before adding services.
                                </div>
                            @endif
                            <div class="row g-4">
                                @forelse ($services as $ser)
                                    <div class="col-lg-4 col-md-6">
                                        <div class="card h-100 card-hover position-relative">
                                            <div class="card-body text-center">
                                                <div class="d-flex justify-content-center mb-3">
                                                    <img src="{{ url('/img') }}/technical-support.png"
                                                        class="img-fluid" alt="{{ $ser->name }}"
                                                        style="max-height: 200px;">
                                                </div>
                                                <h5 class="card-title mb-2">
                                                    {{ Number::currency($ser->base_price, 'IDR', 'id', 0) }}
                                                </h5>
                                                <h6 class="mb-2">
                                                    <a href="{{ route('cashier.show', ['id' => $ser->id, 'type' => 'services']) }}"
                                                        data-toggle="ajaxModal" data-title="Detail Service"
                                                        data-size="lg" class="text-decoration-none">
                                                        {{ $ser->name }}
                                                    </a>
                                                </h6>
                                                <p class="text-muted small mb-3">
                                                    {{ $ser->description }}
                                                </p>
                                                <div class="d-flex gap-2 justify-content-center">
                                                    @if ($currentCart)
                                                        <form action="{{ route('cashier.addToCart') }}" method="post"
                                                            id="ajxForm" data-ajxForm-reset="true">
                                                            @csrf
                                                            <input type="hidden" name="item_type" value="service">
                                                            <input type="hidden" name="item_id"
                                                                value="{{ $ser->id }}">
                                                            <input type="hidden" name="quantity" value="1">

                                                            <button type="submit" class="btn btn-primary btn-sm w-100">
                                                                <i class="ti ti-plus"></i> Add Service
                                                            </button>

                                                        </form>
                                                    @else
                                                        <p class="text-muted">Keranjang kosong, silakan tambah customer
                                                            terlebih dahulu.</p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-center">No Service Found</p>
                                @endforelse
                            </div>
                            <div class="mt-4">
                                {{ $services->links() }}
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
@endsection
@push('js')
    <script>
        document.getElementById('searchProduct')?.addEventListener('keyup', function() {
            const searchTerm = this.value.toLowerCase();
            const cards = document.querySelectorAll('.card-hover');

            cards.forEach(card => {
                const text = card.textContent.toLowerCase();
                if (text.includes(searchTerm)) {
                    card.closest('.col-lg-4').style.display = '';
                } else {
                    card.closest('.col-lg-4').style.display = 'none';
                }
            });
        });
    </script>
@endpush
