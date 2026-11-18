<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print QR Code - {!! $product->name !!}</title>
    <style>
        @page {
            size: auto;
            margin: 0mm;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #ffffff;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .label-container {
            width: 80mm;
            height: 50mm;
            padding: 5mm;
            box-sizing: border-box;
            border: 1px dashed #ccc;
            border-radius: 4px;
            display: flex;
            flex-direction: row;
            align-items: center;
            justify-content: space-between;
            background-color: #fff;
            position: relative;
        }
        .info-side {
            width: 55%;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .store-name {
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            color: #666;
            margin-bottom: 2px;
            letter-spacing: 0.5px;
        }
        .product-name {
            font-size: 14px;
            font-weight: 700;
            color: #111;
            margin: 2px 0;
            line-height: 1.2;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .product-sku {
            font-size: 11px;
            color: #555;
            margin: 4px 0;
            font-family: monospace;
        }
        .product-price {
            font-size: 16px;
            font-weight: 800;
            color: #0d6efd;
            margin-top: 4px;
        }
        .qr-side {
            width: 40%;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .qr-code svg, .qr-code canvas {
            display: block;
            max-width: 100%;
            height: auto;
        }
        .no-print-btn {
            position: absolute;
            bottom: -50px;
            left: 50%;
            transform: translateX(-50%);
            padding: 8px 16px;
            background: #0d6efd;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        @media print {
            .label-container {
                border: none;
            }
            .no-print-btn {
                display: none;
            }
        }
    </style>
</head>
<body>

    <div class="label-container">
        <div class="info-side">
            <div class="store-name">WooCommerce Product</div>
            <div class="product-name">{!! $product->name !!}</div>
            <div class="product-sku">SKU: {{ $product->sku ?? 'N/A' }}</div>
            <div class="product-price">
                @if(!empty($product->sale_price))
                    @currency($product->sale_price)
                @else
                    @currency($product->price)
                @endif
            </div>
        </div>
        <div class="qr-side">
            <div class="qr-code">
                <canvas id="qrcode-canvas"></canvas>
            </div>
        </div>
        
        <button class="no-print-btn" onclick="window.print()">Print Label</button>
    </div>

    <script src="{{ asset('assets/js/qrious.min.js') }}"></script>
    <script>
        // Generate QR code
        (function() {
            new QRious({
                element: document.getElementById('qrcode-canvas'),
                value: "{{ route('wc.product.show', $product->id) }}",
                size: 90,
                padding: 2
            });
        })();

        // Auto trigger print dialog on page load
        window.addEventListener('DOMContentLoaded', () => {
            setTimeout(() => {
                window.print();
            }, 800);
        });
    </script>

</body>
</html>
