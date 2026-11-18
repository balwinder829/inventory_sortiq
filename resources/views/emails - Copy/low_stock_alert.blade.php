<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Low Stock Alert — {{ ucwords($product->name) }}</title>
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
            color: #d9534f;
        }
        ul {
            margin: 10px 0 15px 15px;
            padding: 0;
        }
        li {
            margin-bottom: 6px;
        }
        .highlight {
            background-color: #fff3cd;
            border: 1px solid #ffeeba;
            padding: 10px;
            border-radius: 5px;
            color: #856404;
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
            transition: background 0.2s ease;
        }
        .btn:hover {
            background-color: #0b5ed7;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>⚠️ Low Stock Alert</h2>

        <p>Hello <strong>{{ ucwords($user->name ?? 'Admin') }}</strong>,</p>

        <div class="highlight">
            <strong>Attention:</strong> The following product has reached a low stock level.
        </div>

        <p><strong>Product Details:</strong></p>
        <ul>
            <li><strong>Product Name:</strong> {{ ucwords($product->name) }}</li>
            <li><strong>SKU:</strong> {{ $product->sku ?? 'N/A' }}</li>
            <li><strong>Store:</strong> {{ optional($product->store)->name ?? 'N/A' }}</li>
            <li><strong>Current Quantity:</strong> {{ $product->quantity }}</li>
            <li><strong>Reorder Level:</strong> {{ $product->reorder_level ?? 'Not Set' }}</li>
            <li><strong>Last Updated:</strong> {{ $product->updated_at->format('d M Y, h:i A') }}</li>
        </ul>

        <p>We recommend restocking this item soon to avoid running out of inventory.</p>

        <p>
            <a href="{{ route('products.show', $product->id) }}" class="btn">View Product</a>
        </p>

        <p>Thank you for keeping your inventory up to date.</p>

        <div class="footer">
            © {{ date('Y') }} Sandal Multi Store. All rights reserved.
        </div>
    </div>
</body>
</html>
