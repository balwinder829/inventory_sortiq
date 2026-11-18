<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Stock Decreased Alert — {{ ucwords($product->name) }}</title>
    <style>
        body {
            font-family: DejaVu Sans, Helvetica, Arial, sans-serif;
            font-size: 14px;
            color: #333;
            line-height: 1.5;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }
        .container {
            max-width: 600px;
            margin: 30px auto;
            padding: 25px 30px;
            background: #fff;
            border: 1px solid #e5e5e5;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.05);
        }
        h2 {
            margin-top: 0;
            color: #ea5455;
        }
        ul {
            margin: 10px 0 15px 15px;
            padding: 0;
        }
        li {
            margin-bottom: 6px;
        }
        .highlight {
            background-color: #fff2f2;
            border: 1px solid #ffcccc;
            padding: 10px;
            border-radius: 5px;
            color: #900;
            margin-bottom: 15px;
        }
        .footer {
            font-size: 12px;
            color: #888;
            margin-top: 25px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>📉 Stock Decreased Alert</h2>

        <p>Hello <strong>Admin</strong>,</p>

        <div class="highlight">
            <strong>Notification:</strong> The inventory levels of the following product have decreased.
        </div>

        <p><strong>Product:</strong> {{ ucwords($product->name) }}</p>
        <p><strong>SKU:</strong> {{ $product->sku ?? 'N/A' }}</p>
        <p><strong>Store:</strong> {{ optional($product->store)->name ?? 'N/A' }}</p>

        <p><strong>Stock Level Adjustments:</strong></p>
        <ul>
            <li><strong>Original Stock Level:</strong> {{ $oldQty }} items</li>
            <li><strong>New Stock Level:</strong> <strong style="color: #ea5455;">{{ $newQty }} items</strong></li>
            <li><strong>Net Quantity Change:</strong> -{{ $oldQty - $newQty }} items</li>
            <li><strong>Last Updated:</strong> {{ $product->updated_at->format('d M Y, h:i A') }}</li>
        </ul>

        <p>Thank you for keeping your product ledger up to date.</p>

        <div class="footer">
            © {{ date('Y') }} Sandal Multi Store. All rights reserved.
        </div>
    </div>
</body>
</html>
