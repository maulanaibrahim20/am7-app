{{-- resources/views/app/backend/pages/cashier/print-invoice.blade.php --}}

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Invoice {{ $sale->invoice_number }}</title>
    <style>
        @media print {
            @page {
                margin: 0;
            }

            body {
                margin: 10mm;
            }
        }

        body {
            font-family: 'Courier New', monospace;
            font-size: 12px;
            max-width: 80mm;
            margin: 0 auto;
            padding: 10px;
        }

        .header {
            text-align: center;
            margin-bottom: 15px;
            border-bottom: 2px dashed #000;
            padding-bottom: 10px;
        }

        .header h2 {
            margin: 5px 0;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin: 3px 0;
        }

        .items-table {
            width: 100%;
            margin: 15px 0;
            border-top: 1px dashed #000;
            border-bottom: 1px dashed #000;
            padding: 10px 0;
        }

        .item-row {
            margin: 5px 0;
        }

        .item-name {
            font-weight: bold;
        }

        .item-details {
            display: flex;
            justify-content: space-between;
            font-size: 11px;
        }

        .totals {
            margin-top: 10px;
            padding-top: 10px;
            border-top: 1px dashed #000;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            margin: 5px 0;
        }

        .grand-total {
            font-size: 14px;
            font-weight: bold;
            border-top: 2px solid #000;
            padding-top: 5px;
            margin-top: 5px;
        }

        .footer {
            text-align: center;
            margin-top: 15px;
            padding-top: 10px;
            border-top: 2px dashed #000;
            font-size: 11px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h2>{{ config('app.name') }}</h2>
        <div>Jl. Workshop Address</div>
        <div>Telp: 0812-3456-7890</div>
    </div>

    <div class="info-row">
        <span>Invoice:</span>
        <strong>{{ $sale->invoice_number }}</strong>
    </div>
    <div class="info-row">
        <span>Date:</span>
        <span>{{ $sale->created_at->format('d/m/Y H:i') }}</span>
    </div>
    <div class="info-row">
        <span>Cashier:</span>
        <span>{{ $sale->cashier->name }}</span>
    </div>
    <div class="info-row">
        <span>Customer:</span>
        <span>{{ $sale->customer->name }}</span>
    </div>
    <div class="info-row">
        <span>Phone:</span>
        <span>{{ $sale->customer->phone }}</span>
    </div>

    <div class="items-table">
        @foreach ($sale->saleItems as $item)
            <div class="item-row">
                <div class="item-name">{{ $item->item_name }}</div>
                <div class="item-details">
                    <span>{{ $item->quantity }} x {{ number_format($item->unit_price, 0, ',', '.') }}</span>
                    <span>{{ number_format($item->subtotal, 0, ',', '.') }}</span>
                </div>
                @if ($item->discount_percent > 0)
                    <div class="item-details">
                        <span>Discount {{ $item->discount_percent }}%</span>
                        <span>-{{ number_format($item->discount_amount, 0, ',', '.') }}</span>
                    </div>
                @endif
            </div>
        @endforeach
    </div>

    <div class="totals">
        <div class="total-row">
            <span>Subtotal:</span>
            <span>Rp {{ number_format($sale->subtotal, 0, ',', '.') }}</span>
        </div>

        @if ($sale->discount_amount > 0)
            <div class="total-row">
                <span>Total Discount:</span>
                <span>-Rp {{ number_format($sale->discount_amount, 0, ',', '.') }}</span>
            </div>
        @endif>

        @if ($sale->tax_amount > 0)
            <div class="total-row">
                <span>Tax:</span>
                <span>Rp {{ number_format($sale->tax_amount, 0, ',', '.') }}</span>
            </div>
        @endif

        <div class="total-row grand-total">
            <span>TOTAL:</span>
            <span>Rp {{ number_format($sale->total_amount, 0, ',', '.') }}</span>
        </div>

        @if ($sale->payment_method === 'cash')
            <div class="total-row">
                <span>Paid:</span>
                <span>Rp {{ number_format($sale->paid_amount, 0, ',', '.') }}</span>
            </div>
            <div class="total-row">
                <span>Change:</span>
                <span>Rp {{ number_format($sale->change_amount, 0, ',', '.') }}</span>
            </div>
        @endif

        <div class="total-row">
            <span>Payment Method:</span>
            <span>{{ strtoupper($sale->payment_method) }}</span>
        </div>
    </div>

    @if ($sale->notes)
        <div style="margin-top: 10px; font-size: 11px;">
            Notes: {{ $sale->notes }}
        </div>
    @endif

    <div class="footer">
        <div>Thank You!</div>
        <div>Please come again</div>
    </div>

    <script>
        window.onload = function() {
            window.print();
        };
    </script>
</body>

</html>
