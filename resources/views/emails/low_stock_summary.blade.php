<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Low Stock Summary — {{ $items->count() }} Item(s) Low</title>
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
            max-width: 700px;
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
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px 10px;
            text-align: left;
        }
        th {
            background-color: #f8f9fa;
            font-weight: 600;
        }
        tr:nth-child(even) {
            background-color: #fdfdfd;
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
        <h2>⚠️ Low Stock Summary</h2>

        <p>Hello <strong>{{ ucwords($adminName ?? 'Admin') }}</strong>,</p>

        <div class="highlight">
            <strong>Attention:</strong> The following {{ $items->count() }} item(s) are running low on stock.
        </div>

        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Item Name</th>
                    <th>SKU</th>
                    <th>Store</th>
                    <th>Quantity</th>
                    <th>Last Updated</th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ ucwords($item->name) }}</td>
                        <td>{{ $item->sku ?? 'N/A' }}</td>
                        <td>{{ optional($item->store)->name ?? 'N/A' }}</td>
                        <td><strong style="color:#d9534f;">{{ $item->quantity }}</strong></td>
                        <td>{{ $item->updated_at?->format('d M Y, h:i A') ?? 'N/A' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <p style="margin-top:15px;">Please review and restock these items soon to avoid shortages.</p>

        <p>
            <a href="{{ route('admin.low_stock.index') }}" class="btn">View All Low-Stock Items</a>
        </p>

        <p>Thank you for keeping your inventory up to date.</p>

        <div class="footer">
            © {{ date('Y') }} Sandal Multi Store. All rights reserved.
        </div>
    </div>
</body>
</html>
