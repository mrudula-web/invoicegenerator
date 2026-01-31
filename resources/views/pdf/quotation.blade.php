<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 13px;
            margin: 0;
            padding: 0;
        }

        .page {
            width: 100%;
            padding: 20px 30px;
        }

        .title {
            color: #1a73e8;
            font-size: 26px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        /* HEADER TABLE */
        .header-table {
            width: 100%;
            border-collapse: collapse;
        }

        .header-table td {
            vertical-align: top;
        }

        .logo-box {
            width: 150px;
            height: 120px;
            border: 1px solid #ccc;
            text-align: center;
            padding-top: 45px;
            font-size: 12px;
            color: #777;
            margin-left: auto;
        }

        /* BILLING TABLE */
        .bill-table {
            width: 100%;
            margin-top: 30px;
            border-collapse: collapse;
        }

        .bill-table td {
            vertical-align: top;
            padding: 5px 0;
        }

        /* ITEM TABLE */
        .items {
            width: 100%;
            margin-top: 30px;
            border-collapse: collapse;
        }

        .items th {
            background: #1a73e8;
            color: #fff;
            padding: 8px;
            text-align: left;
            font-size: 13px;
        }

        .items td {
            border-bottom: 1px solid #ddd;
            padding: 8px;
        }

        /* TOTALS */
        .totals {
            width: 150px;
            margin-left: 67%;
            margin-top: 20px;
            font-size: 12px;
        }

        .totals td {
            padding: 3px 0;
        }

        .totals .label {
            text-align: left;
        }

        .totals .value {
            text-align: right;
        }

        .grand-total {
            font-weight: bold;
            font-size: 16px;
        }

        /* TERMS */
        .terms {
            margin-top: 30px;
            font-size: 12px;
            line-height: 18px;
        }

        .terms-title {
            font-weight: bold;
            margin-bottom: 10px;
        }
    </style>
</head>

<body>
<div class="page">

    <div class="title">Quotation</div>

    <!-- HEADER -->
    <table class="header-table">
        <tr>
            <td>
                <strong>{{ $quotation['company_name'] }}</strong><br>
                {{ $quotation['company_address'] }}<br>
                {{ $quotation['company_phone'] }}<br>
                {{ $quotation['company_email'] }}
            </td>

            <td align="right">
                <div class="logo-box">Logo</div>
                <br>
                <strong>Quotation Date:</strong> {{ $quotation['created_at'] }}<br>
                <strong>Quotation No:</strong> {{ $quotation['quote_no'] }}
            </td>
        </tr>
    </table>

    <!-- BILLING -->
    <table class="bill-table">
        <tr>
            <td>
                <strong>Bill To:</strong><br>
                {{ $quotation['cust_name'] }}<br>
                {{ $quotation['cust_address'] }}<br>
                {{ $quotation['cust_ph'] }}<br>
                {{ $quotation['cust_email'] }}
            </td>

            
        </tr>
    </table>

    <!-- ITEM TABLE -->
    <table class="items">
        <thead>
            <tr>
                <th width="50%">Item Description</th>
                <th width="10%">Qty</th>
                <th width="10%">Unit</th>
                <th width="20%">Price</th>
                <th width="20%">Amount</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($quote_items as $item)
            <tr>
                <td>{{ $item['prod_name'] }}</td>
                <td>{{ $item['quantity'] }}</td>
                <td>{{ $item['unit'] }}</td>
                <td>{{ $item['price'] }}</td>
                <td>{{ $item['total_price'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- TOTALS -->
    <table class="totals">
        <tr>
            <td class="label">Sub Total:</td>
            <td class="value">{{ $quotation['quoteinv_subtotal'] }}</td>
        </tr>
        <tr>
            <td class="label">Tax:</td>
            <td class="value">{{ $quotation['quoteinv_tax'] }}</td>
        </tr>
        <tr>
            <td class="label">Discount:</td>
            <td class="value">{{ $quotation['quoteinv_dis'] }}</td>
        </tr>

        <tr class="grand-total">
            <td class="label">Total:</td>
            <td class="value">{{ $quotation['quoteinv_total'] }}</td>
        </tr>
    </table>



    <!-- TERMS -->
    <div class="terms">
        <div class="terms-title">Terms and Conditions</div>
         {!! nl2br($quotation['quote_terms']) !!}<br><br>

        <div class="terms-title">Additional Notes</div>
        • Timelines depend on client approval cycles.<br>
        • Work starts after advance payment.<br>
    </div>

</div>
</body>
</html>
