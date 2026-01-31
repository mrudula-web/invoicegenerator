<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>Receipt</title>
<style>
    body {
        font-family: Arial, sans-serif;
        margin: 40px;
        color: #333;
    }

    .invoice-container {
        width: 100%;
    }

    /* HEADER */
    .header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        border-bottom: 2px solid #000;
        padding-bottom: 20px;
        margin-bottom: 20px;
    }

    .company-info {
        font-size: 14px;
        line-height: 20px;
        text-align: center;
    }

    .invoice-info {
        text-align: right;
        font-size: 14px;
        line-height: 22px;
    }

    .invoice-info .title {
        font-size: 26px;
        font-weight: bold;
        margin-bottom: 10px;
    }

    /* CUSTOMER + INVOICE DETAILS SECTION */
    .details {
        width: 100%;
        display: flex;
        justify-content: space-between;
        margin-top: 20px;
    }

    .details div {
        width: 48%;
        font-size: 14px;
        line-height: 20px;
    }

    .details b {
        display: inline-block;
        width: 120px;
    }

    /* ITEMS TABLE */
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 25px;
        font-size: 14px;
    }

    table th {
        text-align: left;
        padding: 10px;
        border-bottom: 2px solid #000;
    }

    table td {
        padding: 10px;
        border-bottom: 1px solid #ddd;
    }

    table td.num {
        text-align: left;
    }

    /* TOTALS SECTION */
    .totals-block {
        width: 300px;
        margin-left: auto;
        margin-top: 30px;
        font-size: 14px;
    }

    .totals-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 6px;
    }

    .totals-row .label {
        text-align: left;
    }

    .totals-row .value {
        text-align: right;
        min-width: 100px;
    }

    .amount-due {
        font-size: 18px;
        font-weight: bold;
        color: #0066cc;
        border-top: 2px solid #000;
        margin-top: 12px;
        padding-top: 12px;
    }

    /* FOOTER NOTES */
    .notes {
        margin-top: 40px;
        font-size: 14px;
        line-height: 20px;
    }
     .footer {
            margin-top: 40px;
            font-size: 11px;
            text-align: center;
            color: #999;
        }
</style>
</head>

<body>

<div class="invoice-container">

    <!-- HEADER -->
    <div class="header">
        <div class="company-info">
            <h2>{{ $receipt['company_name'] }}</h2>
            {{ $receipt['company_address'] }}<br>
            {{ $receipt['company_email'] }}<br>
            Phone: {{ $receipt['company_phone'] }}
        </div>

        <div class="invoice-info">
            <div class="title">Receipt</div>
            <b>Receipt Date:</b> {{ date('d-M-Y', strtotime($receipt['created_at'])) }}<br>
        </div>
    </div>

    <!-- DETAILS -->
    <div class="details">
        <div>
            <h3>Customer Details</h3>
            {{ $receipt['cust_name'] }}<br>
            {{ $receipt['cust_address'] }}<br>
            Phone: {{ $receipt['cust_ph'] }}
        </div>
    </div>

    <!-- ITEMS TABLE -->
    <table>
        <tr>
            <th>Amount Received</th>
            <th>Invoice Number</th>
            <th>Date</th>
        </tr>

        <tr>
            <td>{{ $receipt['rec_amount'] }}</td>
            <td class="num">{{ $receipt['inv_no'] }}</td>
            <td class="num">{{ date('d-M-Y', strtotime($receipt['created_at'])) }}</td>
           
        </tr>
    </table>
<div class="footer">
        © {{ date('Y') }} {{ $receipt['company_name'] }}. All rights reserved.
    </div>


</div>

</body>
</html>
