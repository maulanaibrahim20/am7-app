<div class="card">
    <div class="card-header bg-warning text-dark mb-3">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="ti ti-clock"></i> Held Transactions
            </h5>
            <a href="{{ url('cashier.index') }}" class="btn btn-dark btn-sm">
                <i class="ti ti-arrow-left"></i> Back to Cashier
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Session Code</th>
                        <th>Customer</th>
                        <th class="text-center">Items</th>
                        <th class="text-end">Total</th>
                        <th>Hold Time</th>
                        <th>Expires At</th>
                        <th>Notes</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($heldCarts as $cart)
                        <tr>
                            <td>
                                <span class="badge bg-warning text-dark">
                                    {{ $cart->session_code }}
                                </span>
                            </td>
                            <td>
                                <strong>{{ $cart->customer->name }}</strong><br>
                                <small class="text-muted">{{ $cart->customer->phone }}</small>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-primary">
                                    {{ $cart->items->sum('quantity') }} items
                                </span>
                            </td>
                            <td class="text-end">
                                <strong>{{ Number::currency($cart->getTotalAmount(), 'IDR', 'id', 0) }}</strong>
                            </td>
                            <td>
                                <small>{{ $cart->hold_at->format('d M Y H:i') }}</small><br>
                                <small class="text-muted">{{ $cart->hold_at->diffForHumans() }}</small>
                            </td>
                            <td>
                                @if ($cart->expired_at)
                                    <small class="{{ $cart->expired_at->isPast() ? 'text-danger' : 'text-info' }}">
                                        {{ $cart->expired_at->format('d M Y H:i') }}
                                    </small>
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                <small>{{ $cart->notes ?? '-' }}</small>
                            </td>
                            <td class="text-center">
                                <div class="d-flex gap-1 justify-content-center">
                                    <form action="{{ route('cashier.resume-cart', $cart->session_code) }}"
                                        method="POST" id="ajxForm" data-ajxForm-reset="true">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-primary">
                                            <i class="ti ti-arrow-back"></i> Resume
                                        </button>
                                    </form>
                                    <form action="{{ route('cashier.cancel-cart', $cart->session_code) }}"
                                        method="POST" onsubmit="return confirm('Cancel this transaction?')">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="ti ti-x"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-5">
                                <i class="ti ti-inbox" style="font-size: 48px;"></i>
                                <p class="mt-2">No held transactions</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
