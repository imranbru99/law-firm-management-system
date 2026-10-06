<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $invoice->invoice_no }}</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 13px; color: #333; line-height: 1.4; padding: 20px; }
        .header { border-bottom: 2px solid #1e293b; padding-bottom: 15px; margin-bottom: 25px; }
        .firm-title { font-size: 22px; font-weight: bold; color: #0f172a; text-transform: uppercase; }
        .tagline { font-size: 11px; color: #64748b; margin-top: 2px; }
        .invoice-title { font-size: 20px; font-weight: bold; color: #1e293b; text-align: right; }
        .grid { width: 100%; margin-bottom: 20px; }
        .grid td { vertical-align: top; }
        .box { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; padding: 12px; }
        table.items { width: 100%; border-collapse: collapse; margin-top: 15px; }
        table.items th { background: #0f172a; color: #ffffff; text-align: left; padding: 8px 10px; font-size: 12px; }
        table.items td { border-bottom: 1px solid #e2e8f0; padding: 10px; }
        .totals { width: 45%; margin-left: auto; margin-top: 15px; }
        .totals td { padding: 4px 8px; }
        .totals td.bold { font-weight: bold; font-size: 14px; border-top: 2px solid #0f172a; }
        .footer { margin-top: 40px; border-top: 1px solid #e2e8f0; padding-top: 10px; font-size: 11px; color: #64748b; text-align: center; }
    </style>
</head>
<body>
    <div class="header">
        <table style="width: 100%;">
            <tr>
                <td>
                    <div class="firm-title">LexVanguard Legal Partners</div>
                    <div class="tagline">Advocates & Legal Consultants | Est. 2024</div>
                    <div style="margin-top: 5px; font-size: 11px; color: #475569;">
                        100 Chancery Lane, Legal Precinct | Phone: +1 (800) 555-LEGAL<br>
                        Email: billing@lexvanguard.law | VAT/Reg: LLP-2024-9842
                    </div>
                </td>
                <td style="text-align: right;">
                    <div class="invoice-title">TAX INVOICE</div>
                    <div style="font-size: 14px; font-weight: bold; color: #2563eb; margin-top: 4px;">#{{ $invoice->invoice_no }}</div>
                    <div style="font-size: 11px; margin-top: 4px;">
                        Date: <strong>{{ $invoice->invoice_date->format('M d, Y') }}</strong><br>
                        Due: <strong>{{ $invoice->due_date?->format('M d, Y') }}</strong><br>
                        Status: <strong style="text-transform: uppercase;">{{ $invoice->payment_status }}</strong>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <table class="grid">
        <tr>
            <td style="width: 50%; padding-right: 15px;">
                <div class="box">
                    <strong style="color: #0f172a; font-size: 12px;">BILLED TO:</strong><br>
                    <strong>{{ $invoice->client?->name }}</strong><br>
                    @if($invoice->client?->company_name)
                        {{ $invoice->client->company_name }}<br>
                    @endif
                    @if($invoice->client?->tax_vat_number)
                        Tax/VAT: {{ $invoice->client->tax_vat_number }}<br>
                    @endif
                    {{ $invoice->client?->email }} | {{ $invoice->client?->mobile }}<br>
                    {{ $invoice->client?->address }}
                </div>
            </td>
            <td style="width: 50%; padding-left: 15px;">
                <div class="box">
                    <strong style="color: #0f172a; font-size: 12px;">MATTER REFERENCE:</strong><br>
                    @if($invoice->case)
                        <strong>{{ $invoice->case->title }}</strong><br>
                        Case No: {{ $invoice->case->case_no ?? 'Pre-litigation' }}<br>
                        Court: {{ $invoice->case->court?->name ?? 'Arbitration / Consultation' }}<br>
                        Lead Advocate: {{ $invoice->case->leadLawyer?->name ?? 'Firm Partner' }}
                    @else
                        General Legal Retainer & Advisory
                    @endif
                </div>
            </td>
        </tr>
    </table>

    <table class="items">
        <thead>
            <tr>
                <th style="width: 5%;">#</th>
                <th style="width: 55%;">Description of Professional Services</th>
                <th style="width: 10%; text-align: right;">Qty</th>
                <th style="width: 15%; text-align: right;">Rate ($)</th>
                <th style="width: 15%; text-align: right;">Amount ($)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoice->items as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->description }}</td>
                    <td style="text-align: right;">{{ number_format($item->qty, 2) }}</td>
                    <td style="text-align: right;">${{ number_format($item->rate, 2) }}</td>
                    <td style="text-align: right;">${{ number_format($item->total, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table class="totals">
        <tr>
            <td>Subtotal:</td>
            <td style="text-align: right;">${{ number_format($invoice->sub_total, 2) }}</td>
        </tr>
        @if($invoice->discount_amount > 0)
            <tr>
                <td>Discount:</td>
                <td style="text-align: right; color: #dc2626;">-${{ number_format($invoice->discount_amount, 2) }}</td>
            </tr>
        @endif
        @if($invoice->tax_amount > 0)
            <tr>
                <td>Tax / VAT:</td>
                <td style="text-align: right;">+${{ number_format($invoice->tax_amount, 2) }}</td>
            </tr>
        @endif
        <tr>
            <td class="bold">Grand Total:</td>
            <td class="bold" style="text-align: right;">${{ number_format($invoice->grand_total, 2) }}</td>
        </tr>
        <tr>
            <td style="color: #16a34a;">Amount Paid:</td>
            <td style="text-align: right; color: #16a34a;">${{ number_format($invoice->paid, 2) }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold; color: #dc2626;">Balance Due:</td>
            <td style="font-weight: bold; text-align: right; color: #dc2626;">${{ number_format($invoice->due, 2) }}</td>
        </tr>
    </table>

    @if($invoice->notes)
        <div style="margin-top: 25px; padding: 10px; background: #f1f5f9; border-radius: 4px; font-size: 11px;">
            <strong>Payment Instructions & Notes:</strong><br>
            {{ $invoice->notes }}
        </div>
    @endif

    <div class="footer">
        Thank you for entrusting your legal affairs to LexVanguard Legal Partners.<br>
        Payments should be wired to LexVanguard Operating / Client Trust Account. All disputes subject to local jurisdiction.
    </div>
</body>
</html>
