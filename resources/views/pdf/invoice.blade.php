<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $invoice->invoice_number }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Helvetica', sans-serif; font-size: 13px; color: #1e293b; background: #fff; }

        .container { padding: 40px; }

        /* Header */
        .header { display: flex; justify-content: space-between; margin-bottom: 40px; }
        .brand { display: flex; align-items: center; gap: 10px; }
        .brand-icon { background: #2563eb; color: white; padding: 8px; border-radius: 8px; font-size: 18px; font-weight: bold; }
        .brand-name { font-size: 22px; font-weight: 700; color: #1e293b; }
        .invoice-title { text-align: right; }
        .invoice-title h1 { font-size: 32px; font-weight: 800; color: #1e293b; letter-spacing: 2px; }
        .invoice-title p { color: #2563eb; font-weight: 600; font-size: 15px; }

        /* Info Box */
        .info-box { background: #f8fafc; border-radius: 10px; padding: 24px; margin-bottom: 32px; display: flex; justify-content: space-between; }
        .info-section h4 { font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #94a3b8; margin-bottom: 8px; }
        .info-section p { font-size: 13px; color: #475569; line-height: 1.6; }
        .info-section .name { font-weight: 700; color: #1e293b; font-size: 14px; }

        /* Table */
        table { width: 100%; border-collapse: collapse; margin-bottom: 32px; }
        thead tr { border-bottom: 2px solid #e2e8f0; }
        thead th { padding: 10px 12px; text-align: left; font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #94a3b8; }
        thead th.right { text-align: right; }
        tbody tr { border-bottom: 1px solid #f1f5f9; }
        tbody td { padding: 14px 12px; color: #475569; }
        tbody td.right { text-align: right; }
        tbody td.name { font-weight: 600; color: #1e293b; }

        /* Totals */
        .totals { display: flex; justify-content: flex-end; margin-bottom: 32px; }
        .totals-box { width: 280px; }
        .totals-row { display: flex; justify-content: space-between; padding: 6px 0; font-size: 13px; }
        .totals-row span:first-child { color: #64748b; }
        .totals-row span:last-child { font-weight: 500; }
        .totals-divider { border-top: 2px solid #e2e8f0; margin: 8px 0; }
        .totals-total { display: flex; justify-content: space-between; padding: 8px 0; }
        .totals-total span:first-child { font-weight: 700; font-size: 15px; color: #1e293b; }
        .totals-total span:last-child { font-weight: 800; font-size: 18px; color: #2563eb; }

        /* Status Badge */
        .status { display: inline-block; padding: 4px 12px; border-radius: 999px; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; }
        .status-paid    { background: #dcfce7; color: #166534; }
        .status-sent    { background: #dbeafe; color: #1e40af; }
        .status-overdue { background: #fee2e2; color: #991b1b; }
        .status-draft   { background: #f1f5f9; color: #475569; }

        /* Notes */
        .notes { border-top: 1px solid #e2e8f0; padding-top: 24px; }
        .notes h4 { font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #94a3b8; margin-bottom: 8px; }
        .notes p { color: #64748b; line-height: 1.6; }

        /* Footer */
        .footer { margin-top: 40px; text-align: center; color: #94a3b8; font-size: 11px; border-top: 1px solid #f1f5f9; padding-top: 20px; }
    </style>
</head>
<body>
<div class="container">

    {{-- Header --}}
    <div class="header">
        <div class="brand">
            <div class="brand-icon">INV</div>
            <div class="brand-name">Invoicio</div>
        </div>
        <div class="invoice-title">
            <h1>INVOICE</h1>
            <p>#{{ $invoice->invoice_number }}</p>
            <div style="margin-top: 8px;">
                <span class="status status-{{ $invoice->status }}">{{ ucfirst($invoice->status) }}</span>
            </div>
        </div>
    </div>

    {{-- Info Box --}}
    <div class="info-box">
        <div class="info-section">
            <h4>Bill To</h4>
            <p class="name">{{ $invoice->client->name }}</p>
            @if($invoice->client->company)
                <p>{{ $invoice->client->company }}</p>
            @endif
            @if($invoice->client->email)
                <p>{{ $invoice->client->email }}</p>
            @endif
            @if($invoice->client->phone)
                <p>{{ $invoice->client->phone }}</p>
            @endif
            @if($invoice->client->address)
                <p>{{ $invoice->client->address }}</p>
            @endif
        </div>

        <div class="info-section">
            <h4>From</h4>
            <p class="name">{{ auth()->user()->name }}</p>
            <p>{{ auth()->user()->email }}</p>
        </div>

        <div class="info-section">
            <h4>Issue Date</h4>
            <p class="name">{{ $invoice->issue_date->format('M d, Y') }}</p>
        </div>

        <div class="info-section">
            <h4>Due Date</h4>
            <p class="name">{{ $invoice->due_date->format('M d, Y') }}</p>
        </div>
    </div>

    {{-- Line Items --}}
    <table>
        <thead>
            <tr>
                <th>Product</th>
                <th>Description</th>
                <th class="right">Price</th>
                <th class="right">Qty</th>
                <th class="right">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoice->items as $item)
            <tr>
                <td class="name">{{ $item->name }}</td>
                <td>{{ $item->description ?? '—' }}</td>
                <td class="right">${{ number_format($item->price, 2) }}</td>
                <td class="right">{{ $item->quantity }}</td>
                <td class="right">${{ number_format($item->total, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Totals --}}
    <div class="totals">
        <div class="totals-box">
            <div class="totals-row">
                <span>Subtotal</span>
                <span>${{ number_format($invoice->subtotal, 2) }}</span>
            </div>
            <div class="totals-row">
                <span>Tax ({{ $invoice->tax_rate }}%)</span>
                <span>${{ number_format($invoice->tax_amount, 2) }}</span>
            </div>
            <div class="totals-divider"></div>
            <div class="totals-total">
                <span>Total</span>
                <span>${{ number_format($invoice->total, 2) }}</span>
            </div>
        </div>
    </div>

    {{-- Notes --}}
    @if($invoice->notes)
    <div class="notes">
        <h4>Notes</h4>
        <p>{{ $invoice->notes }}</p>
    </div>
    @endif

    {{-- Footer --}}
    <div class="footer">
        <p>Thank you for your business! — Generated by Invoicio</p>
    </div>

</div>
</body>
</html>