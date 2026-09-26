<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Códigos QR - Productos</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 20px;
            background: #f4f4f4;
        }
        .page {
            background: white;
            max-width: 210mm; /* A4 width */
            margin: 0 auto;
            padding: 10mm;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            color: #333;
        }
        .controls {
            text-align: center;
            margin-bottom: 20px;
        }
        .btn-print {
            background: #7c3aed;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            font-weight: bold;
            border-radius: 5px;
            cursor: pointer;
        }
        .btn-print:hover {
            background: #6d28d9;
        }
        
        .qr-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
        }
        
        .qr-item {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
            page-break-inside: avoid;
        }
        .qr-item img {
            width: 100px;
            height: 100px;
            margin-bottom: 5px;
        }
        .product-name {
            font-size: 10px;
            font-weight: bold;
            color: #333;
            margin-bottom: 3px;
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
        }
        .product-code {
            font-size: 9px;
            color: #666;
            font-family: monospace;
        }
        .product-price {
            font-size: 11px;
            font-weight: bold;
            color: #b91c1c;
            margin-top: 3px;
        }

        @media print {
            body { background: white; padding: 0; }
            .page { box-shadow: none; margin: 0; padding: 0; max-width: none; }
            .controls { display: none; }
        }
    </style>
</head>
<body>

    <div class="controls">
        <button class="btn-print" onclick="window.print()">Imprimir QRs</button>
    </div>

    <div class="page">
        <div class="header">
            <h1>Códigos QR de Productos</h1>
            <p style="font-size: 12px; color: #666;">Pega estos códigos en tus productos para escanearlos rápidamente.</p>
        </div>

        <div class="qr-grid">
            @forelse($products as $product)
                @php
                    $catName = strtolower($product->category->name ?? '');
                    // Lista de palabras clave para productos "pequeños"
                    $smallKeywords = ['tinta', 'celular', 'smartphone', 'teclado', 'mouse', 'cargador', 'usb', 'accesorio', 'cable', 'memoria', 'funda'];
                    
                    $isSmall = false;
                    foreach ($smallKeywords as $keyword) {
                        if (str_contains($catName, $keyword)) {
                            $isSmall = true;
                            break;
                        }
                    }
                    
                    // Si es pequeño el QR será de 60px, si es grande (laptop, PC) de 100px
                    $qrSize = $isSmall ? 60 : 100;
                @endphp

                <div class="qr-item">
                    <div class="qr-code-container" data-code="{{ $product->code }}" data-size="{{ $qrSize }}" style="margin: 0 auto 5px auto; width: {{ $qrSize }}px; height: {{ $qrSize }}px;"></div>
                    <div class="product-name">{{ $product->name }}</div>
                    <div class="product-code">{{ $product->code }}</div>
                    <div class="product-price">S/ {{ number_format($product->price, 2) }}</div>
                </div>
            @empty
                <div style="grid-column: span 4; text-align: center; padding: 50px; color: #666;">
                    No se encontraron productos en esta categoría.
                </div>
            @endforelse
        </div>
    </div>

    <!-- Librería para generar QRs de forma local sin depender de APIs externas -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var containers = document.querySelectorAll('.qr-code-container');
            containers.forEach(function(container) {
                var code = container.getAttribute('data-code');
                var size = parseInt(container.getAttribute('data-size')) || 100;
                
                if (code) {
                    new QRCode(container, {
                        text: code,
                        width: size,
                        height: size,
                        colorDark : "#000000",
                        colorLight : "#ffffff",
                        correctLevel : QRCode.CorrectLevel.H
                    });
                }
            });
        });
    </script>
</body>
</html>
