@extends('layouts.admin.main')
@section('breadcrumb', 'Cashier / Checkout')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header bg-primary text-white mb-3">
                        <h5 class="mb-0 text-white">
                            <i class="ti ti-cash"></i> Checkout
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info mb-4">
                            <strong>Customer:</strong> {{ $cartSession->customer->name }}<br>
                            <strong>Phone:</strong> {{ $cartSession->customer->phone }}
                        </div>

                        <h6 class="mb-3">Order Summary</h6>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Item</th>
                                        <th class="text-center">Qty</th>
                                        <th class="text-end">Price</th>
                                        <th class="text-end">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($cartSession->items as $item)
                                        <tr>
                                            <td>{{ $item->item_name }}</td>
                                            <td class="text-center">{{ $item->quantity }}</td>
                                            <td class="text-end">{{ Number::currency($item->unit_price, 'IDR', 'id', 0) }}
                                            </td>
                                            <td class="text-end">{{ Number::currency($item->subtotal, 'IDR', 'id', 0) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="table-light">
                                    <tr>
                                        <td colspan="3" class="text-end"><strong>Total:</strong></td>
                                        <td class="text-end">
                                            <strong id="total-amount" data-total="{{ $cartSession->getTotalAmount() }}">
                                                {{ Number::currency($cartSession->getTotalAmount(), 'IDR', 'id', 0) }}
                                            </strong>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">Payment Method</h6>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('cashier.processPayment') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Select Payment Method</label>
                                <select class="form-select" name="payment_method" id="payment_method" required>
                                    <option value="cash">Cash</option>
                                    <option value="transfer">Bank Transfer</option>
                                    <option value="card">Credit/Debit Card</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Total Amount</label>
                                <input type="text" class="form-control"
                                    value="{{ Number::currency($cartSession->getTotalAmount(), 'IDR', 'id', 0) }}" readonly>
                            </div>

                            <div class="mb-3" id="cash-input">
                                <label class="form-label">Nominal Uang Diterima</label>
                                <input type="text" class="form-control" id="cash-received" name="cash_received"
                                    placeholder="Masukkan nominal uang">
                            </div>

                            <div class="mb-3" id="change-container">
                                <label class="form-label">Kembalian</label>
                                <input type="text" class="form-control" id="change" readonly>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 btn-lg">
                                <i class="ti ti-check me-2"></i> Complete Payment
                            </button>

                            <a href="{{ route('cashier') }}" class="btn btn-secondary w-100 mt-2">
                                <i class="ti ti-arrow-left me-2"></i> Back to Cart
                            </a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script>
        $(document).ready(function() {
            const total = parseFloat($("#total-amount").data("total"));
            const $cashInput = $("#cash-input");
            const $cashReceived = $("#cash-received");
            const $changeInput = $("#change");
            const $changeContainer = $("#change-container");
            const $paymentMethod = $("#payment_method");
            const $form = $("#paymentForm");
            const $btnSubmit = $("#btnSubmitPayment");

            function toggleCash() {
                if ($paymentMethod.val() === "cash") {
                    $cashInput.show();
                    $changeContainer.show();
                    $cashReceived.attr('required', true);
                } else {
                    $cashInput.hide();
                    $changeContainer.hide();
                    $cashReceived.attr('required', false);
                    $cashReceived.val('');
                    $changeInput.val('');
                }
            }

            $paymentMethod.on("change", toggleCash);
            toggleCash();

            function formatNumber(num) {
                return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            }

            function parseNumber(str) {
                return parseInt(str.replace(/\./g, "")) || 0;
            }

            $cashReceived.on("input", function() {
                let raw = parseNumber($(this).val());

                // Don't format while typing, only show formatted on blur
                let change = raw - total;

                if (change >= 0) {
                    $changeInput.val(
                        new Intl.NumberFormat('id-ID', {
                            style: 'currency',
                            currency: 'IDR',
                            minimumFractionDigits: 0
                        }).format(change)
                    ).removeClass('text-danger').addClass('text-success');
                } else {
                    $changeInput.val(
                        new Intl.NumberFormat('id-ID', {
                            style: 'currency',
                            currency: 'IDR',
                            minimumFractionDigits: 0
                        }).format(Math.abs(change)) + ' (Kurang)'
                    ).removeClass('text-success').addClass('text-danger');
                }
            });

            // Format on blur
            $cashReceived.on("blur", function() {
                let raw = parseNumber($(this).val());
                if (raw > 0) {
                    $(this).val(formatNumber(raw));
                }
            });

            // Remove format on focus
            $cashReceived.on("focus", function() {
                let raw = parseNumber($(this).val());
                if (raw > 0) {
                    $(this).val(raw);
                }
            });

            // Form validation before submit
            $form.on('submit', function(e) {
                if ($paymentMethod.val() === 'cash') {
                    let cashReceived = parseNumber($cashReceived.val());

                    if (cashReceived < total) {
                        e.preventDefault();
                        alert('Cash received is less than total amount!');
                        $cashReceived.focus();
                        return false;
                    }
                }

                // Disable submit button to prevent double submission
                $btnSubmit.prop('disabled', true).html(
                    '<i class="spinner-border spinner-border-sm me-2"></i> Processing...');
            });
        });
    </script>
@endpush
