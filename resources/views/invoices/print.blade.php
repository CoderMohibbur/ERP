<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Invoice #{{ $invoice->invoice_number }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            color: #333;
            background-color: #f9fafb;
            margin: 0;
            padding: 40px 0;
        }

        .invoice-box {
            max-width: 850px;
            margin: auto;
            background: #fff;
            padding: 40px 50px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #2d3748;
            font-size: 26px;
            margin-bottom: 30px;
        }

        .info,
        .summary,
        .items {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }

        .info td,
        .summary td,
        .items td,
        .items th {
            padding: 10px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 14px;
        }

        .items th {
            background-color: #f0f4f8;
            text-align: left;
        }

        .summary tr:last-child td {
            font-weight: bold;
            font-size: 15px;
            background-color: #f0f4f8;
        }

        .notes {
            margin-top: 30px;
            padding: 15px;
            background: #f7fafc;
            border-left: 5px solid #3182ce;
            font-size: 14px;
        }

        .actions {
            text-align: right;
            margin-top: 30px;
        }

        .btn {
            display: inline-block;
            padding: 10px 18px;
            font-size: 14px;
            color: #fff;
            text-decoration: none;
            border-radius: 6px;
            margin-left: 12px;
        }

        .btn-green {
            background: #38a169;
        }

        .btn-blue {
            background: #3182ce;
        }

        @media print {
            .actions {
                display: none;
            }

            body {
                background: #fff;
                padding: 0;
            }

            .invoice-box {
                box-shadow: none;
                border: none;
            }
        }
    </style>
</head>

<body>
    <div class="invoice-box">
        <h1>Invoice #{{ $invoice->invoice_number }}</h1>

        <table class="info">
            <tr>
                <td><strong>Client:</strong> {{ $invoice->client->name }}</td>
                <td><strong>Project:</strong> {{ $invoice->project->title ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td><strong>Issue Date:</strong> {{ $invoice->issue_date }}</td>
                <td><strong>Due Date:</strong> {{ $invoice->due_date }}</td>
            </tr>
            <tr>
                <td><strong>Status:</strong> {{ ucfirst($invoice->status) }}</td>
                <td><strong>Currency:</strong> {{ $invoice->currency }}</td>
            </tr>
        </table>

        @if ($invoice->items->count())
            <table class="items">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Item Name</th>
                        <th>Qty</th>
                        <th>Unit Price</th>
                        <th>Tax (%)</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($invoice->items as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $item->item_name }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>{{ number_format($item->unit_price, 2) }}</td>
                            <td>{{ number_format($item->tax_percent, 2) }}</td>
                            <td>{{ number_format($item->total, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        <table class="summary">
            <tr>
                <td>Sub Total</td>
                <td align="right">{{ $invoice->sub_total }}</td>
            </tr>
            @if ($invoice->discount_type)
                <tr>
                    <td>Discount ({{ $invoice->discount_type == 'flat' ? '৳' : '%' }})</td>
                    <td align="right">{{ $invoice->discount_value }}</td>
                </tr>
            @endif
            @if ($invoice->tax_rate)
                <tr>
                    <td>Tax ({{ $invoice->tax_rate }}%)</td>
                    <td align="right">{{ number_format(($invoice->sub_total * $invoice->tax_rate) / 100, 2) }}</td>
                </tr>
            @endif
            <tr>
                <td>Total Amount</td>
                <td align="right">{{ $invoice->total_amount }}</td>
            </tr>
            <tr>
                <td>Paid Amount</td>
                <td align="right">{{ $invoice->paid_amount }}</td>
            </tr>
            <tr>
                <td>Due Amount</td>
                <td align="right">{{ $invoice->due_amount }}</td>
            </tr>
        </table>

        @if ($invoice->notes)
            <div class="notes">
                <strong>Notes:</strong>
                <p>{{ $invoice->notes }}</p>
            </div>
        @endif

        <div class="actions">
            <a href="{{ route('invoices.download', $invoice) }}" class="btn btn-green">Download PDF</a>
            <a href="#" onclick="window.print()" class="btn btn-blue">Print</a>
        </div>
    </div>
</body>

</html>
