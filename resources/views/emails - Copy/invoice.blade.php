<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice {{ $order->invoice_no }}</title>
</head>
<body style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #f4f6f9; margin:0; padding:20px;">
    <table width="100%" cellpadding="0" cellspacing="0" style="max-width:600px; margin:0 auto; background:#fff; border-radius:8px; overflow:hidden; box-shadow:0 2px 6px rgba(0,0,0,.1);">
        <tr>
            <td style="background:#4f46e5; padding:20px; text-align:center; color:#fff;">
                <h1 style="margin:0; font-size:22px;">{{ config('app.name') }}</h1>
                <p style="margin:0;">Your Invoice</p>
            </td>
        </tr>
        <tr>
            <td style="padding:20px;">
                <h2 style="margin-top:0;">Hello {{ $order->customer_name }},</h2>
                <p>Thank you for your purchase! Here are your invoice details:</p>

                <table width="100%" cellpadding="0" cellspacing="0" style="margin-top:15px; border-collapse:collapse;">
                    <tr>
                        <td style="padding:8px; border:1px solid #ddd;">Invoice No:</td>
                        <td style="padding:8px; border:1px solid #ddd;">{{ $order->invoice_no }}</td>
                    </tr>
                    <tr>
                        <td style="padding:8px; border:1px solid #ddd;">Date:</td>
                        <td style="padding:8px; border:1px solid #ddd;">{{ $order->invoice_date }}</td>
                    </tr>
                    <tr>
                        <td style="padding:8px; border:1px solid #ddd;">Product:</td>
                        <td style="padding:8px; border:1px solid #ddd;">{{ $order->product->name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td style="padding:8px; border:1px solid #ddd;">Price:</td>
                        <td style="padding:8px; border:1px solid #ddd;">₹{{ number_format($order->price,2) }}</td>
                    </tr>
                    <tr>
                        <td style="padding:8px; border:1px solid #ddd;">Quantity:</td>
                        <td style="padding:8px; border:1px solid #ddd;">{{ $order->quantity }}</td>
                    </tr>
                    <tr>
                        <td style="padding:8px; border:1px solid #ddd; font-weight:bold;">Total:</td>
                        <td style="padding:8px; border:1px solid #ddd; font-weight:bold;">₹{{ number_format($order->price * $order->quantity, 2) }}</td>
                    </tr>
                </table>

                <p style="margin-top:20px;">We appreciate your business!</p>
                <p style="margin:0;">– Sandal Multi Store</p>
            </td>
        </tr>
        <tr>
            <td style="background:#f4f6f9; padding:15px; text-align:center; font-size:12px; color:#888;">
                © {{ date('Y') }} Sandal Multi Store. All rights reserved.
            </td>
        </tr>
    </table>
</body>
</html>
