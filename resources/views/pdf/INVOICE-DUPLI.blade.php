<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice #{{ $invoice['inv_no'] }}</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f3f5f9;
            margin: 0;
            padding: 40px 0;
        }

        .invoice-wrapper {
            width: 850px;
            max-width: 95%;
            margin: auto;
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 4px 14px rgba(0,0,0,0.1);
            padding: 40px 50px;
            box-sizing: border-box;
        }

        .top-bar {
            height: 6px;
            background: #1a73e8;
            border-radius: 6px 6px 0 0;
            margin: -40px -50px 30px -50px;
        }

        /* HEADER */
        .header {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 30px;
        }

        .company-block h2 {
            margin: 5px 0 10px;
            font-size: 20px;
            font-weight: bold;
        }

        .company-details,
        .invoice-details {
            font-size: 13px;
            color: #555;
            line-height: 1.5;
        }

        .invoice-title {
            font-size: 40px;
            font-weight: 300;
            color: #c4c9d0;
            text-transform: uppercase;
            margin-bottom: 10px;
        }

        .bill-box {
            background: #f7f9fc;
            border: 1px solid #e1e6ef;
            padding: 20px;
            border-radius: 10px;
            margin-top: 40px;
        }

        .bill-title {
            color: #1a73e8;
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .bill-details p {
            margin: 0;
            font-size: 13px;
        }

        /* TABLE */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 25px;
            table-layout: fixed;
            word-wrap: break-word;
        }

        table thead th {
            text-align: left;
            padding: 12px;
            font-size: 12px;
            color: #6b7280;
        }

        table tbody td {
            padding: 15px 12px;
            border-top: 1px solid #e5e7eb;
            font-size: 13px;
        }

        .item-title {
            font-weight: bold;
            font-size: 14px;
        }

        .item-desc {
            font-size: 12px;
            color: #6b7280;
        }

        /* TOTALS */
        .totals {
            width: 260px;
            margin-left: auto;
            margin-top: 25px;
            font-size: 14px;
        }

        .totals-row {
            display: flex;
            justify-content: space-between;
            padding: 6px 0;
        }

        .amount-due {
            font-size: 20px;
            font-weight: bold;
            color: #1a73e8;
            text-align: right;
            margin-top: 10px;
        }

        /* PAYMENT INFO */
        .payment-info {
            margin-top: 60px;
            font-size: 13px;
        }

        .payment-info h4 {
            margin-bottom: 8px;
            text-transform: uppercase;
            font-size: 12px;
            color: #6b7280;
        }

        .pay-button {
            display: inline-block;
            padding: 10px 18px;
            background: #1a73e8;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-size: 13px;
            margin-top: 10px;
        }

        .footer {
            text-align: center;
            margin-top: 50px;
            font-size: 11px;
            color: #999;
        }

        /* ---------- RESPONSIVE CSS ---------- */

        @media (max-width: 768px) {

            .invoice-title {
                font-size: 28px;
            }

            .header {
                flex-direction: column;
                align-items: flex-start;
            }

            .invoice-wrapper {
                padding: 20px;
            }

            table thead {
                display: none;
            }

            table tbody tr {
                display: block;
                margin-bottom: 15px;
                border-bottom: 1px solid #eee;
                padding-bottom: 10px;
            }

            table tbody td {
                display: flex;
                justify-content: space-between;
                padding: 8px 5px;
            }

            .totals {
                width: 100%;
            }

            .amount-due {
                text-align: left;
            }

        }

    </style>
</head>

<body>

<div class="invoice-wrapper">

    <div class="top-bar"></div>

    <!-- HEADER -->
    <div class="header">

        <div class="company-block">
            <h2>{{ $invoice['company_name'] }}</h2>
            <div class="company-details">
                {{ $invoice['company_address'] }}<br>
                {{ $invoice['company_phone'] }}<br>
                {{ $invoice['company_email'] }}
            </div>
        </div>

        <div class="invoice-right">
            <div class="invoice-title">INVOICE</div>

            <div class="invoice-details">
                <strong>Invoice No:</strong> {{ $invoice['inv_no'] }}<br>
                <strong>Issue Date:</strong> {{ $invoice['created_at'] }}<br>
            </div>
        </div>

    </div>


    <!-- BILL TO -->
    <div class="bill-box">
        <div class="bill-title">BILL TO</div>
        <div class="bill-details">
            <p><strong>{{ $invoice['cust_name'] }}</strong></p>
            <p>{{ $invoice['cust_address'] }}</p>
            <p>{{ $invoice['cust_ph'] }}</p>
            <p>{{ $invoice['cust_email'] }}</p>
        </div>
    </div>


    <!-- ITEMS TABLE -->
    <table>
        <thead>
        <tr>
            <th>Description</th>
            <th>Qty</th>
            <th>Unit</th>
            <th>Unit Price</th>
            <th>Amount</th>
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
    <div class="totals">
        <div class="totals-row">
            <span>Subtotal</span>
            <strong>{{ number_format($invoice['inv_subtotal'], 2) }}</strong>
        </div>

        <div class="totals-row">
            <span>Tax ({{ $invoice['inv_tax'] }}%)</span>
            <strong>{{ number_format($invoice['inv_tax'], 2) }}</strong>
        </div>

        <div class="totals-row">
            <span><strong>Total</strong></span>
            <strong>{{ number_format($invoice['inv_total'], 2) }}</strong>
        </div>

        <div class="amount-due">
            AMOUNT DUE: {{ number_format($invoice['inv_total'], 2) }}
        </div>
    </div>


    <!-- PAYMENT INFO -->
    <div class="payment-info">
        <h4>Payment Information</h4>
        Bank: XYZ<br>
        Account: 3333<br>
        SWIFT: SSDDSA<br>
        IBAN: DFFDF343434<br>

        <a class="pay-button">Pay Online Now</a>
    </div>

    <!-- TERMS -->
    <div class="payment-info">
        <h4>Terms & Conditions</h4>
        {{ $invoice['terms'] }}
    </div>

    <!-- FOOTER -->
    <div class="footer">
        © {{ date('Y') }} {{ $invoice['company_name'] }}. Generated Invoice.
    </div>

</div>

</body>
</html>
