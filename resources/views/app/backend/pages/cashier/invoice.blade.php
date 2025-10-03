@extends('layouts.admin.main')
@section('breadcrumb', 'Invoice')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-4">
                            <h4>Invoice Details</h4>
                            <div class="d-flex gap-2">
                                <a href="{{ route('cashier.printInvoice', $sale->id) }}" class="btn btn-primary"
                                    target="_blank">
                                    <i class="ti ti-printer"></i> Print Invoice
                                </a>
                                <a href="{{ route('cashier') }}" class="btn btn-success">
                                    <i class="ti ti-plus"></i> New Transaction
                                </a>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h5>INVOICE</h5>
                                <p class="mb-1"><strong>Invoice No:</strong> {{ $sale->invoice_number }}</p>
                                <p class="mb-1"><strong>Date:</strong> {{ $sale->created_at->format('d M Y H:i') }}</p>
                                <p class="mb-1"><strong>Cashier:</strong> {{ $sale->cashier->name }}</p>
                                <p class="mb-1">
                                    <strong>Payment:</strong>
                                    <span class="badge bg-success">{{ strtoupper($sale->payment_method) }}</span>
                                </p>
                            </div>
                            <div class="col-md-6 text-end">
                                <h6>Customer Information</h6>
                                <p class="mb-1"><strong>{{ $sale->customer->name }}</strong></p>
                                <p class="mb-1">{{ $sale->customer->phone }}</p>
                                @if ($sale->customer->email)
                                    <p class="mb-1">{{ $sale->customer->email }}</p>
                                @endif
                                @if ($sale->customer->address)
                                    <p class="mb-1">{{ $sale->customer->address }}</p>
                                @endif
                            </div>
                        </div>

                        <div class="table-responsive mb-4">
                            <table class="table table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th width="50">#</th>
                                        <th>Item Description</th>
                                        <th width="100" class="text-center">Qty</th>
                                        <th width="150" class="text-end">Unit Price</th>
                                        <th width="100" class="text-center">Disc %</th>
                                        <th width="150" class="text-end">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($sale->saleItems as $index => $item)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>
                                                <strong>{{ $item->item_name }}</strong><br>
                                                <small class="text-muted">
                                                    {{ $item->saleable_type == 'App\Models\Product' ? 'Product' : 'Service' }}
                                                </small>
                                            </td>
                                            <td class="text-center">{{ $item->quantity }}</td>
                                            <td class="text-end">{{ Number::currency($item->unit_price, 'IDR', 'id', 0) }}
                                            </td>
                                            <td class="text-center">{{ $item->discount_percent }}%</td>
                                            <td class="text-end">{{ Number::currency($item->subtotal, 'IDR', 'id', 0) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="5" class="text-end"><strong>Subtotal:</strong></td>
                                        <td class="text-end">{{ Number::currency($sale->subtotal, 'IDR', 'id', 0) }}</td>
                                    </tr>
                                    @if ($sale->discount_amount > 0)
                                        <tr>
                                            <td colspan="5" class="text-end"><strong>Discount:</strong></td>
                                            <td class="text-end text-danger">
                                                -{{ Number::currency($sale->discount_amount, 'IDR', 'id', 0) }}
                                            </td>
                                        </tr>
                                    @endif
                                    @if ($sale->tax_amount > 0)
                                        <tr>
                                            <td colspan="5" class="text-end"><strong>Tax:</strong></td>
                                            <td class="text-end">{{ Number::currency($sale->tax_amount, 'IDR', 'id', 0) }}
                                            </td>
                                        </tr>
                                    @endif>
                                    <tr class="table-light">
                                        <td colspan="5" class="text-end"><strong>TOTAL:</strong></td>
                                        <td class="text-end">
                                            <strong
                                                class="fs-5">{{ Number::currency($sale->total_amount, 'IDR', 'id', 0) }}</strong>
                                        </td>
                                    </tr>
                                    @if ($sale->payment_method === 'cash')
                                        <tr>
                                            <td colspan="5" class="text-end">Paid Amount:</td>
                                            <td class="text-end">{{ Number::currency($sale->paid_amount, 'IDR', 'id', 0) }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="5" class="text-end">Change:</td>
                                            <td class="text-end">
                                                {{ Number::currency($sale->change_amount, 'IDR', 'id', 0) }}</td>
                                        </tr>
                                    @endif
                                </tfoot>
                            </table>
                        </div>

                        @if ($sale->notes)
                            <div class="alert alert-info">
                                <strong>Notes:</strong> {{ $sale->notes }}
                            </div>
                        @endif

                        <div class="text-center mt-4">
                            <p class="text-muted">Thank you for your business!</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
