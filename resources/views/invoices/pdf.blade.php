<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice {{ $invoice->invoice_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', 'Arial', 'Helvetica', sans-serif;
            font-size: 14px;
            color: #333;
            line-height: 1.6;
            padding: 40px;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
        }

        .header {
            display: table;
            width: 100%;
            margin-bottom: 40px;
        }

        .header-left, .header-right {
            display: table-cell;
            vertical-align: top;
            width: 50%;
        }

        .header-right {
            text-align: right;
        }

        .logo {
            max-height: 60px;
            margin-bottom: 15px;
        }

        .company-name {
            font-size: 24px;
            font-weight: bold;
            color: #1a1a1a;
            margin-bottom: 10px;
        }

        .company-details {
            font-size: 12px;
            color: #666;
            line-height: 1.8;
        }

        .invoice-title {
            font-size: 36px;
            font-weight: bold;
            color: #000000;
            margin-bottom: 15px;
        }

        .invoice-meta {
            font-size: 12px;
            color: #666;
            line-height: 1.8;
        }

        .invoice-meta strong {
            color: #333;
        }

        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: bold;
            margin-top: 8px;
        }

        .status-paid {
            background-color: #dcfce7;
            color: #166534;
        }

        .status-sent {
            background-color: #dbeafe;
            color: #1e40af;
        }

        .status-draft {
            background-color: #f3f4f6;
            color: #374151;
        }

        .status-cancelled {
            background-color: #fee2e2;
            color: #991b1b;
        }

        .bill-to {
            background-color: #f9fafb;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
        }

        .bill-to-label {
            font-size: 11px;
            color: #6b7280;
            text-transform: uppercase;
            font-weight: 600;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
        }

        .bill-to-name {
            font-size: 18px;
            font-weight: bold;
            color: #1a1a1a;
            margin-bottom: 5px;
        }

        .bill-to-details {
            font-size: 12px;
            color: #666;
            line-height: 1.8;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        .items-table thead {
            background-color: #f9fafb;
        }

        .items-table th {
            padding: 12px 15px;
            text-align: left;
            font-size: 11px;
            color: #6b7280;
            text-transform: uppercase;
            font-weight: 600;
            letter-spacing: 0.5px;
            border-bottom: 2px solid #e5e7eb;
        }

        .items-table th.text-right {
            text-align: right;
        }

        .items-table td {
            padding: 15px;
            border-bottom: 1px solid #f3f4f6;
            font-size: 13px;
        }

        .items-table td.text-right {
            text-align: right;
        }

        .items-table tbody tr:last-child td {
            border-bottom: 2px solid #e5e7eb;
        }

        .totals-section {
            display: table;
            width: 100%;
            margin-bottom: 30px;
        }

        .totals-spacer {
            display: table-cell;
            width: 55%;
        }

        .totals {
            display: table-cell;
            width: 45%;
        }

        .total-row {
            display: table;
            width: 100%;
            padding: 10px 0;
            border-bottom: 1px solid #e5e7eb;
        }

        .total-row.grand-total {
            background-color: #f3f4f6;
            padding: 15px;
            border-radius: 8px;
            border: 2px solid #000000;
            margin-top: 10px;
        }

        .total-label, .total-value {
            display: table-cell;
            vertical-align: middle;
        }

        .total-label {
            font-size: 13px;
            color: #666;
        }

        .total-value {
            text-align: right;
            font-size: 14px;
            font-weight: 600;
            color: #1a1a1a;
        }

        .grand-total .total-label {
            font-size: 16px;
            font-weight: bold;
            color: #1a1a1a;
        }

        .grand-total .total-value {
            font-size: 22px;
            font-weight: bold;
            color: #000000;
        }

        .discount-value {
            color: #dc2626 !important;
        }

        .notes-section {
            display: table;
            width: 100%;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px solid #e5e7eb;
        }

        .notes-column {
            display: table-cell;
            width: 50%;
            vertical-align: top;
            padding-right: 2%;
        }
        
        .notes-column.full-width {
            width: 100%;
            padding-right: 0;
        }

        .notes-column:last-child {
            padding-right: 0;
            padding-left: 2%;
        }

        .notes-title {
            font-size: 13px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 8px;
        }

        .notes-content {
            font-size: 11px;
            color: #666;
            line-height: 1.6;
            white-space: pre-line;
        }
        
        .notes-content a {
            color: #2563eb;
            text-decoration: underline;
        }

        .footer {
            margin-top: 50px;
            padding-top: 20px;
            border-top: 2px solid #e5e7eb;
            text-align: center;
        }

        .footer-message {
            font-size: 13px;
            color: #666;
            margin-bottom: 5px;
        }

        .footer-timestamp {
            font-size: 11px;
            color: #9ca3af;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="header-left">
                <img src="{{ public_path('black-logo.png') }}" alt="Ryven Global LLC" class="logo">
                <div class="company-name">Ryven Global LLC</div>
                <div class="company-details">
                    +19299889564<br>
                    hello@ryven.co<br>
                    30 N Gould St Ste R<br>
                    Sheridan, WY 82801
                </div>
            </div>
            <div class="header-right">
                <div class="invoice-title">INVOICE</div>
                <div class="invoice-meta">
                    <strong>Invoice #:</strong> {{ $invoice->invoice_number }}<br>
                    <strong>Date:</strong> {{ $invoice->invoice_date->format('M d, Y') }}<br>
                    <strong>Due Date:</strong> {{ $invoice->due_date->format('M d, Y') }}
                    <br>
                    @if($invoice->status === 'paid')
                        <span class="status-badge status-paid">PAID</span>
                    @elseif($invoice->status === 'sent')
                        <span class="status-badge status-sent">SENT</span>
                    @elseif($invoice->status === 'cancelled')
                        <span class="status-badge status-cancelled">CANCELLED</span>
                    @else
                        <span class="status-badge status-draft">DRAFT</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Paid To -->
        <div class="bill-to">
            <div class="bill-to-label">Paid To</div>
            <div class="bill-to-name">{{ $invoice->client_name }}</div>
            <div class="bill-to-details">
                @if($invoice->client_email)
                    {{ $invoice->client_email }}<br>
                @endif
                @if($invoice->client_phone)
                    {{ $invoice->client_phone }}<br>
                @endif
                @if($invoice->client_address)
                    {{ $invoice->client_address }}
                @endif
            </div>
        </div>

        <!-- Items Table -->
        <table class="items-table">
            <thead>
                <tr>
                    <th>Description</th>
                    <th class="text-right">Quantity</th>
                    <th class="text-right">Rate</th>
                    <th class="text-right">Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoice->items as $item)
                    <tr>
                        <td>{{ $item['description'] }}</td>
                        <td class="text-right">{{ $item['quantity'] }}</td>
                        <td class="text-right">{{ $invoice->currency }} {{ number_format($item['rate'], 2) }}</td>
                        <td class="text-right" style="font-weight: 600;">
                            {{ $invoice->currency }} {{ number_format($item['quantity'] * $item['rate'], 2) }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Totals -->
        <div class="totals-section">
            <div class="totals-spacer"></div>
            <div class="totals">
                <div class="total-row">
                    <div class="total-label">Subtotal:</div>
                    <div class="total-value">{{ $invoice->currency }} {{ number_format($invoice->subtotal, 2) }}</div>
                </div>

                @if($invoice->tax_percentage > 0)
                    <div class="total-row">
                        <div class="total-label">Tax ({{ number_format($invoice->tax_percentage, 2) }}%):</div>
                        <div class="total-value">{{ $invoice->currency }} {{ number_format($invoice->tax_amount, 2) }}</div>
                    </div>
                @endif

                @if($invoice->discount_percentage > 0)
                    <div class="total-row">
                        <div class="total-label">Discount ({{ number_format($invoice->discount_percentage, 2) }}%):</div>
                        <div class="total-value discount-value">-{{ $invoice->currency }} {{ number_format($invoice->discount_amount, 2) }}</div>
                    </div>
                @endif

                <div class="total-row grand-total">
                    <div class="total-label">Total:</div>
                    <div class="total-value">{{ $invoice->currency }} {{ number_format($invoice->total, 2) }}</div>
                </div>
            </div>
        </div>

        <!-- Notes and Terms -->
        @if($invoice->notes || $invoice->terms)
            <div class="notes-section">
                @if($invoice->notes && $invoice->terms)
                    @if($invoice->notes)
                        <div class="notes-column">
                            <div class="notes-title">Notes:</div>
                            <div class="notes-content">{{ $invoice->notes }}</div>
                        </div>
                    @endif

                    @if($invoice->terms)
                        <div class="notes-column">
                            <div class="notes-title">Terms & Conditions:</div>
                            <div class="notes-content">{{ $invoice->terms }}</div>
                        </div>
                    @endif
                @elseif($invoice->terms)
                    <div class="notes-column full-width">
                        <div class="notes-title">Terms & Conditions:</div>
                        <div class="notes-content">{{ $invoice->terms }}</div>
                    </div>
                @elseif($invoice->notes)
                    <div class="notes-column full-width">
                        <div class="notes-title">Notes:</div>
                        <div class="notes-content">{{ $invoice->notes }}</div>
                    </div>
                @endif
            </div>
        @endif

        <!-- Footer -->
        <div class="footer">
            <div class="footer-message">Thank you for your business!</div>
            <div class="footer-timestamp">Invoice Date: {{ $invoice->invoice_date->format('M d, Y') }}</div>
        </div>
    </div>
</body>
</html>

