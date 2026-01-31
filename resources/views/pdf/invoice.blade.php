<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice #{{ $invoice['inv_no'] }}</title>

    <style>
        body {
            margin: 0;
            padding: 0;
            background: #f2f4f8;
            font-family: Arial, sans-serif;
        }

        .page {
            width: 210mm;
            min-height: 297mm;
            padding: 20mm;
            margin: auto;
            background: white;
            box-shadow: 0 0 8px rgba(0,0,0,0.1);
        }

        .top-bar {
            height: 6px;
            background: #1a73e8;
            border-radius: 4px;
            margin-bottom: 20px;
        }

        /* HEADER */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            text-align: center;
        }

        .company-block h2 {
            margin: 0;
            font-size: 22px;
            font-weight: bold;
        }

        .company-details {
            font-size: 13px;
            color: #666;
            margin-top: 8px;
            line-height: 1.4;
        }

        .invoice-number {
            font-size: 40px;
            color: #c4c9d0;
            font-weight: 300;
            text-align: right;
            margin-bottom: 10px;
        }

        .invoice-details {
            text-align: right;
            font-size: 13px;
            color: #555;
            line-height: 1.5;
        }

        /* BILL TO */
        .bill-section {
            background: #f7f9fc;
            border: 1px solid #e3e7ef;
            padding: 18px;
            border-radius: 10px;
            margin-top: 30px;
        }

        .bill-title {
            font-size: 12px;
            font-weight: bold;
            color: #1a73e8;
            margin-bottom: 8px;
        }

        .bill-details p {
            margin: 2px 0;
            font-size: 13px;
        }

        /* TABLE */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 25px;
        }

        table thead th {
            text-align: left;
            padding: 10px 0;
            font-size: 12px;
            color: #777;
            border-bottom: 1px solid #e3e3e3;
        }

        table tbody td {
            padding: 12px 0;
            border-bottom: 1px solid #e6e6e6;
            vertical-align: top;
        }

        .item-title {
            font-size: 14px;
            font-weight: bold;
        }

        .item-desc {
            font-size: 12px;
            color: #777;
            margin-top: 3px;
        }

        .amount-bold {
            font-weight: bold;
        }
.invoice-header-right {
    margin-right: auto;
    text-align: right;
}

        /* TOTALS BOX */
        .totals-box {
            width: 260px;
            margin-right: 50px;
            margin-top: 30px;
            font-size: 14px;
            text-align: right;
            float: right;
        }

        .totals-row {
            display: flex;
            justify-content: space-between;
            padding: 6px 0;
        }

        .totals-row strong {
            font-weight: bold;
        }

        .amount-due {
            text-align: right;
            font-size: 22px;
            font-weight: bold;
            color: #1a73e8;
            margin-top: 10px;
        }

        /* TERMS */
        .section-title {
            text-transform: uppercase;
            color: #6b7280;
            font-size: 13px;
            margin-top: 40px;
            margin-bottom: 6px;
            font-weight: bold;
        }

        .footer {
            margin-top: 40px;
            font-size: 11px;
            text-align: center;
            color: #999;
        }

        /* RESPONSIVE */
        @media (max-width: 900px) {
            .page {
                width: 100%;
                padding: 20px;
                box-shadow: none;
            }

            .header {
                flex-direction: column;
                align-items: flex-start;
            }

            .invoice-details,
            .invoice-number {
                text-align: right;
                margin-top: 10px;
            }

            .totals-box {
                width: 100%;
            }
        }
    </style>
</head>

<body>

<div class="page">

    <div class="top-bar"></div>

    <!-- HEADER -->
    <div class="header">

        <div class="company-block">
            <h2>{{ $invoice['company_name'] }}</h2>
            <div class="company-details">
                {{ $invoice['company_address'] }}<br>
               Phone: {{ $invoice['company_phone'] }}<br>
               Email: {{ $invoice['company_email'] }}
            </div>
        </div>

        <div class="invoice-header-right">
            <div class="invoice-number">INVOICE</div>

            <div class="invoice-details">
                <strong>Invoice #:</strong> {{ $invoice['inv_no'] }}<br>
                <strong>Date:</strong> {{ $invoice['created_at'] }}
            </div>
        </div>

    </div>

    <!-- BILL TO -->
    <div class="bill-section">
        <div class="bill-title">BILL TO</div>
        <div class="bill-details">
            <p><strong>{{ $invoice['cust_name'] }}</strong></p>
            <p>{{ $invoice['cust_address'] }}</p>
            <p>{{ $invoice['cust_ph'] }}</p>
            <p>{{ $invoice['cust_email'] }}</p>
        </div>
    </div>

    <!-- PRODUCT TABLE -->
    <table>
        <thead>
        <tr>
            <th>Description</th>
            <th style="width:60px;">Qty</th>
            <th style="width:80px;">Unit</th>
            <th style="width:120px;">Unit Price</th>
            <th style="width:120px;">Amount</th>
        </tr>
        </thead>

        <tbody>
        @foreach ($invoicedes as $item)
            <tr>
                <td>
                    <div class="item-title">{{ $item['prod_name'] }}</div>
                    <div class="item-desc">{{ $item['description'] ?? '' }}</div>
                </td>
                <td>{{ $item['quantity'] }}</td>
                <td>{{ $item['unit'] }}</td>
                <td>{{ number_format($item['price'], 2) }}</td>
                <td class="amount-bold">{{ number_format($item['total_price'], 2) }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <!-- TOTALS -->
    <div class="totals-box">
        <div class="totals-row">
            <span>Subtotal</span>
            <strong>{{ number_format($invoice['inv_subtotal'], 2) }}</strong>
        </div>

        <div class="totals-row">
            <span>Discount</span>
            <strong>{{ number_format($invoice['inv_dis'], 2) }}</strong>
        </div>

        <div class="totals-row">
            <span>Tax</span>
            <strong>{{ number_format($invoice['inv_tax'], 2) }}</strong>
        </div>

        <div class="totals-row">
            <strong>Total</strong>
            <strong>{{ number_format($invoice['inv_total'], 2) }}</strong>
        </div>

        <div class="amount-due">
            Amount Due: {{ number_format($invoice['inv_total'], 2) }}
        </div>
    </div>

    <!-- TERMS -->
    <div class="section-title">Terms & Conditions</div>
    <div style="font-size:13px;">
        {!! nl2br($invoice['terms']) !!}

    </div>

    <!-- FOOTER -->
    <div class="footer">
        © {{ date('Y') }} {{ $invoice['company_name'] }}. All rights reserved.
    </div>

</div>

</body>
</html>
