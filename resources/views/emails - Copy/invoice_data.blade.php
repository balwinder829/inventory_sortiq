<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice {{ strtoupper($order->invoice_no) }}</title>
    <style>
        body {
            font-family: DejaVu Sans, Helvetica, Arial, sans-serif;
            font-size: 14px;
            color: #333;
            line-height: 1.5;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            border: 1px solid #ddd;
        }
        h2 {
            margin-top: 0;
        }
        p {
            margin: 10px 0;
        }
        .footer {
            font-size: 12px;
            color: #888;
            margin-top: 20px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Hello {{ ucwords($order->customer_name) }},</h2>

        <p>Thank you for your purchase! Please find your invoice attached.</p>

        <p><strong>Invoice Details:</strong></p>
        <ul>
            <li>Invoice No: {{ strtoupper($order->invoice_no) }}</li>
            <li>Date: {{ $order->invoice_date }}</li>
            <li>Payment Mode: {{ ucwords(str_replace('_', ' ', $order->payment_mode ?? 'N/A')) }}</li>
            <li>Total Amount: ₹{{ number_format($order->price * $order->quantity, 2) }}</li>
        </ul>

        <p>If you have any questions regarding your invoice, please feel free to contact us.</p>

        <p>Thank you for choosing Sandal Multi Store!</p>

        <div class="footer">
            © {{ date('Y') }} Sandal Multi Store. All rights reserved.
        </div>
    </div>
</body>
</html>
