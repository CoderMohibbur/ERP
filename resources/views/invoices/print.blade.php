<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice #{{ $invoice->invoice_number }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            color: #333;
            background-color: #f9f9f9;
            margin: 0;
            padding: 30px;
        }

        .invoice-box {
            max-width: 850px;
            margin: auto;
            background: #fff;
            padding: 40px 50px;
            border: 1px solid #eee;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }

        h1 {
            font-size: 24px;
            color: #2c3e50;
            text-align: center;
            margin-bottom: 30px;
        }

        table {
            width: 100%;
            line-height: 1.6;
            text-align: left;
            border-collapse: collapse;
        }

        th {
            background-color: #f0f0f0;
            padding: 10px;
            font-weight: bold;
            border-bottom: 1px solid #ccc;
        }

        td {
            padding: 10px;
            vertical-align: top;
        }

        .summary td {
            padding: 8px;
            border-top: 1px solid #ccc;
        }

        .total {
            font-weight: bold;
        }

        .meta {
            margin-bottom: 30px;
        }

        .meta td {
            padding: 6px 10px;
        }

        .text-right {
            text-align: right;
        }

        .note {
            margin-top: 30px;
            font-size: 14px;
            color: #555;
        }

        .footer {
            margin-top: 60px;
            font-size: 13px;
            text-align: center;
            color: #999;
        }
    </style>
</head>
<body>
    <div class="invoice-box">
        <h1>Invoice #{{ $invoice->invoice_number }}</h1>

        <table class="meta">
            <tr>
                <td><strong>Client:</strong></td>
                <td>{{ $invoice->client->name }}</td>
                <td><strong>Issue Date:</strong></td>
                <td>{{ $invoice->issue_date->format('d M, Y') }}</td>
            </tr>
            <tr>
                <td><strong>Project:</strong></td>
                <td>{{ $invoice->project->title ?? 'N/A' }}</td>
                <td><strong>Due Date:</strong></td>
                <td>{{ $invoice->due_date->format('d M, Y') }}</td>
            </tr>
            <tr>
                <td><strong>Status:</strong></td>
                <td>{{ ucfirst(str_replace('_', ' ', $invoice->status)) }}</td>
                <td><strong>Currency:</strong></td>
                <td>{{ $invoice->currency }}</td>
            </tr>
        </table>

        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Description</th>
                    <th class="text-right">Qty</th>
                    <th class="text-right">Unit Price</th>
                    <th class="text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($invoice->items as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->item_name }}</td>
                        <td class="text-right">{{ $item->quantity }}</td>
                        <td class="text-right">{{ number_format($item->unit_price, 2) }}</td>
                        <td class="text-right">{{ number_format($item->total_price, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <table class="summary" style="margin-top: 20px;">
            <tr>
                <td colspan="4" class="text-right">Subtotal:</td>
                <td class="text-right">{{ number_format($invoice->sub_total, 2) }}</td>
            </tr>
            @if ($invoice->discount_value)
                <tr>
                    <td colspan="4" class="text-right">Discount ({{ $invoice->discount_type }}):</td>
                    <td class="text-right">-{{ number_format($invoice->discount_value, 2) }}</td>
                </tr>
            @endif
            @if ($invoice->tax_rate)
                <tr>
                    <td colspan="4" class="text-right">Tax ({{ $invoice->tax_rate }}%):</td>
                    <td class="text-right">{{ number_format(($invoice->sub_total - $invoice->discount_value) * ($invoice->tax_rate / 100), 2) }}</td>
                </tr>
            @endif
            <tr class="total">
                <td colspan="4" class="text-right">Total:</td>
                <td class="text-right">{{ number_format($invoice->total_amount, 2) }}</td>
            </tr>
            <tr>
                <td colspan="4" class="text-right">Paid:</td>
                <td class="text-right">{{ number_format($invoice->paid_amount, 2) }}</td>
            </tr>
            <tr>
                <td colspan="4" class="text-right">Due:</td>
                <td class="text-right">{{ number_format($invoice->due_amount, 2) }}</td>
            </tr>
        </table>

        @if($invoice->notes)
            <div class="note">
                <strong>Notes:</strong><br>
                {{ $invoice->notes }}
            </div>
        @endif

        <div class="footer">
            This is a computer-generated invoice. No signature required.
        </div>
    </div>
</body>
</html>
