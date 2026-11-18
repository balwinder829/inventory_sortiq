<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice {{ $order->invoice_no }}</title>
</head>

<body style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #f4f6f9; margin:0; padding:20px;">

    <table width="100%" cellpadding="0" cellspacing="0" 
        style="max-width:600px; margin:0 auto; background:#fff; border-radius:8px; 
        overflow:hidden; box-shadow:0 2px 6px rgba(0,0,0,.1);">

        <!-- HEADER -->
        <tr>
            <td style="background:#4f46e5; padding:20px; text-align:center; color:#fff;">
                <h1 style="margin:0; font-size:22px;">{{ config('app.name') }}</h1>
                <p style="margin:0;">Your Invoice</p>
            </td>
        </tr>

        <!-- BODY -->
        <tr>
            <td style="padding:20px;">

                <h2 style="margin-top:0;">Hello {{ $order->customer_name }},</h2>
                <p>Thank you for your purchase! Here are your invoice details:</p>

                <!-- Basic Invoice Info -->
                <table width="100%" cellpadding="0" cellspacing="0" 
                    style="margin-top:15px; border-collapse:collapse;">

                    <tr>
                        <td style="padding:8px; border:1px solid #ddd;">Invoice No:</td>
                        <td style="padding:8px; border:1px solid #ddd;">{{ $order->invoice_no }}</td>
                    </tr>

                    <tr>
                        <td style="padding:8px; border:1px solid #ddd;">Date:</td>
                        <td style="padding:8px; border:1px solid #ddd;">{{ $order->invoice_date }}</td>
                    </tr>

                    <tr>
                        <td style="padding:8px; border:1px solid #ddd;">Payment Mode:</td>
                        <td style="padding:8px; border:1px solid #ddd;">{{ ucfirst($order->payment_mode) }}</td>
                    </tr>
                </table>

                <!-- PRODUCT TABLE -->
                <h3 style="margin-top:25px;">Products</h3>

                <table width="100%" cellpadding="0" cellspacing="0"
                    style="margin-top:10px; border-collapse:collapse;">

                    <thead>
                        <tr>
                            <th style="padding:8px; border:1px solid #ddd; background:#f4f4f4;">Product</th>
                            <th style="padding:8px; border:1px solid #ddd; background:#f4f4f4;">Price (₹)</th>
                            <th style="padding:8px; border:1px solid #ddd; background:#f4f4f4;">Qty</th>
                            <th style="padding:8px; border:1px solid #ddd; background:#f4f4f4;">Subtotal (₹)</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($order->items as $item)
                        <tr>
                            <td style="padding:8px; border:1px solid #ddd;">
                                {{ $item->product->name }}
                            </td>
                            <td style="padding:8px; border:1px solid #ddd;">
                                ₹{{ number_format($item->price,2) }}
                            </td>
                            <td style="padding:8px; border:1px solid #ddd;">
                                {{ $item->quantity }}
                            </td>
                            <td style="padding:8px; border:1px solid #ddd;">
                                ₹{{ number_format($item->subtotal,2) }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>

                </table>

                <!-- TOTAL -->
                <table width="100%" cellpadding="0" cellspacing="0" 
                    style="margin-top:15px; border-collapse:collapse;">
                    <tr>
                        <td style="padding:10px; border:1px solid #ddd; font-weight:bold;">
                            Total Amount:
                        </td>
                        <td style="padding:10px; border:1px solid #ddd; font-weight:bold;">
                            ₹{{ number_format($order->total_amount,2) }}
                        </td>
                    </tr>
                </table>

                <p style="margin-top:20px;">We appreciate your business!</p>
                <p style="margin:0;">– Sandal Multi Store</p>
            </td>
        </tr>

        <!-- FOOTER -->
        <tr>
            <td style="background:#f4f6f9; padding:15px; text-align:center; 
                       font-size:12px; color:#888;">
                © {{ date('Y') }} Sandal Multi Store. All rights reserved.
            </td>
        </tr>

    </table>

</body>
</html>
