<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice {{ strtoupper($order->invoice_no) }}</title>
    <style>
        body {
            font-family: DejaVu Sans, Helvetica, Arial, sans-serif;
            font-size: 12px;
            color: #000;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 700px;
            margin: 0 auto;
            padding: 20px;
        }
        .header, .footer {
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 20px;
        }
        .header p {
            margin: 5px 0 15px 0;
            font-size: 12px;
        }
        .section-title {
            margin-top: 20px;
            margin-bottom: 5px;
            font-weight: bold;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table td, table th {
            padding: 8px;
            border: 1px solid #000;
            font-size: 12px;
        }
        table th {
            font-weight: bold;
        }
        .text-right {
            text-align: right;
        }
        .total-row td {
            font-weight: bold;
        }
        .footer {
            margin-top: 20px;
            font-size: 10px;
            color: #555;
        }
        .from-to td {
            border: 0;
            padding: 5px;
            vertical-align: top;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>{{ ucwords($order->store_name) }}</h1>
            <p>Invoice</p>
        </div>

        <!-- From & To -->
        <table class="from-to">
            <tr>
                <td>
                    <strong>From:</strong><br>
                    {{ ucwords($order->store_name) }}<br>
                    {{ ucwords($order->store_address) }}<br>
                    Phone: {{ $order->store_phone }}<br>
                    Email: {{ $order->store_email }}
                </td>
                <td>
                    <strong>To:</strong><br>
                    {{ ucwords($order->customer_name) }}<br>
                    {{ $order->customer_mobile }}<br>
                    {{ $order->customer_email ?? '' }}
                </td>
            </tr>
        </table>

        <!-- Invoice Details including Payment Mode -->
        <table>
            <tr>
                <th>Invoice No</th>
                <th>Date</th>
                <th>Payment Mode</th>
            </tr>
            <tr>
                <td>{{ $order->invoice_no }}</td>
                <td>{{ $order->invoice_date }}</td>
                <td>{{ ucwords(str_replace('_', ' ', $order->payment_mode ?? 'N/A')) }}</td>
                
            </tr>
        </table>

        <!-- Item Table -->
        <table style="margin-top:20px;">
            <tr>
                <th>Product</th>
                <th class="text-right">Price</th>
                <th class="text-right">Quantity</th>
                <th class="text-right">Total</th>
            </tr>
            <tr>
                <td>{!! ucwords($order->product->name ?? 'N/A') !!}</td>
                <td class="text-right">₹{{ number_format($order->price, 2) }}</td>
                <td class="text-right">{{ $order->quantity }}</td>
                <td class="text-right">₹{{ number_format($order->price * $order->quantity, 2) }}</td>
            </tr>
            <tr class="total-row">
                <td colspan="3" class="text-right">Grand Total</td>
                <td class="text-right">₹{{ number_format($order->price * $order->quantity, 2) }}</td>
            </tr>
        </table>

        <!-- Notes -->
        <p style="margin-top:20px;">Thank you for your business!</p>
        <p>– Sandal Multi Store</p>

        <!-- Footer -->
        <div class="footer">
            © {{ date('Y') }} Sandal Multi Store. All rights reserved.
        </div>
    </div>
</body>
</html>
