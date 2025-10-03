<div class="card">
    <div class="card-header bg-primary text-white mb-3">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0 text-white">
                <i class="ti ti-shopping-cart"></i> Cart Items
            </h5>
        </div>
    </div>
    <div class="card-body">
        <div class="alert alert-info mb-3">
            <div class="row">
                <div class="col-md-6">
                    <strong>Session:</strong> {{ $cartSession->session_code }}<br>
                    <strong>Customer:</strong> {{ $cartSession->customer->name }}
                </div>
                <div class="col-md-6">
                    <strong>Phone:</strong> {{ $cartSession->customer->phone }}<br>
                    <strong>Email:</strong> {{ $cartSession->customer->email ?? '-' }}
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>Item</th>
                        <th width="150" class="text-center">Quantity</th>
                        <th width="150" class="text-end">Unit Price</th>
                        {{-- <th width="120" class="text-center">Discount (%)</th> --}}
                        <th width="150" class="text-end">Subtotal</th>
                        <th width="100" class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($cartSession->items as $item)
                        <tr>
                            <td>
                                <strong>{{ $item->item_name }}</strong><br>
                                <small class="text-muted">
                                    {{ $item->cartable_type == 'App\Models\Product' ? 'Product' : 'Service' }}
                                </small>
                            </td>
                            <td>
                                <form action="{{ route('cashier.updateCart', $item->id) }}" method="POST"
                                    class="d-inline">
                                    @csrf
                                    <div class="input-group input-group-sm">
                                        <input type="number" class="form-control text-center" name="quantity"
                                            value="{{ $item->quantity }}" min="1"
                                            @if ($item->cartable_type == 'App\Models\Product') max="{{ $item->cartable->stock_quantity }}" @endif
                                            onchange="this.form.submit()">
                                        <input type="hidden" name="discount_percent"
                                            value="{{ $item->discount_percent }}">
                                    </div>
                                </form>
                            </td>
                            <td class="text-end">
                                {{ Number::currency($item->unit_price, 'IDR', 'id', 0) }}
                            </td>
                            {{-- <td>
                                <form action="{{ route('cashier.updateCart', $item->id) }}" method="POST"
                                    class="d-inline">
                                    @csrf
                                    <input type="hidden" name="quantity" value="{{ $item->quantity }}">
                                    <div class="input-group input-group-sm">
                                        <input type="number" class="form-control text-center" name="discount_percent"
                                            value="{{ $item->discount_percent }}" min="0" max="100"
                                            step="0.1" onchange="this.form.submit()">
                                        <span class="input-group-text">%</span>
                                    </div>
                                </form>
                            </td> --}}
                            <td class="text-end">
                                <strong>{{ Number::currency($item->subtotal, 'IDR', 'id', 0) }}</strong>
                            </td>
                            <td class="text-center">
                                <form action="{{ route('cashier.deleteCart', $item->id) }}" method="POST"
                                    onsubmit="return confirm('Remove this item?')">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="ti ti-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">
                                Cart is empty
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot class="table-light">
                    <tr>
                        <td colspan="4" class="text-end"><strong>Total:</strong></td>
                        <td class="text-end">
                            <h4 class="mb-0">
                                {{ Number::currency($cartSession->getTotalAmount(), 'IDR', 'id', 0) }}
                            </h4>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="d-flex justify-content-between mt-4">
            <div>
                <button type="button" class="close btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">
                    <i class="ti ti-plus"></i> Add More Items
                </button>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('cashier.holdModal') }}" class="btn btn-warning text-dark" data-toggle="ajaxModal"
                    data-title="Hold">
                    <i class="ti ti-clock me-1"></i> Hold Transaction
                </a>
                <a href="{{ route('cashier.checkout') }}" class="btn btn-success btn-lg">
                    <i class="ti ti-cash"></i> Proceed to Checkout
                </a>
            </div>
        </div>
    </div>
</div>
