<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Price Change Alert — {{ ucwords($product->name) }}</title>
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
            color: #ff9f43;
        }
        ul {
            margin: 10px 0 15px 15px;
            padding: 0;
        }
        li {
            margin-bottom: 6px;
        }
        .highlight {
            background-color: #e8f4fd;
            border: 1px solid #b3d7ff;
            padding: 10px;
            border-radius: 5px;
            color: #004085;
            margin-bottom: 15px;
        }
        .footer {
            font-size: 12px;
            color: #888;
            margin-top: 25px;
            text-align: center;
        }
        .btn {
            display: inline-block;
            padding: 10px 16px;
            color: #fff;
            background-color: #0d6efd;
            text-decoration: none;
            border-radius: 5px;
            font-weight: 500;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>🔔 Price Change Alert</h2>

        <p>Hello <strong>Admin</strong>,</p>

        <div class="highlight">
            <strong>Notification:</strong> The pricing structure for the following product has been updated.
        </div>

        <p><strong>Product:</strong> {{ ucwords($product->name) }}</p>
        <p><strong>SKU:</strong> {{ $product->sku ?? 'N/A' }}</p>
        <p><strong>Store:</strong> {{ optional($product->store)->name ?? 'N/A' }}</p>

        <p><strong>Price Adjustments:</strong></p>
        <ul>
            @if($oldPrice != $newPrice)
                <li><strong>Selling Price:</strong> <del>₹{{ number_format($oldPrice, 2) }}</del> ➔ <strong style="color: #28c76f;">₹{{ number_format($newPrice, 2) }}</strong></li>
            @endif
            @if($oldCost != $newCost)
                <li><strong>Purchase Price (Cost):</strong> <del>₹{{ number_format($oldCost, 2) }}</del> ➔ <strong style="color: #ea5455;">₹{{ number_format($newCost, 2) }}</strong></li>
            @endif
            <li><strong>Last Updated:</strong> {{ $product->updated_at->format('d M Y, h:i A') }}</li>
        </ul>

        <p>Thank you for keeping your product ledger up to date.</p>

        <div class="footer">
            © {{ date('Y') }} Sandal Multi Store. All rights reserved.
        </div>
    </div>
</body>
</html>
