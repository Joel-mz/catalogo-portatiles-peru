<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Catálogo - {{ $settings['store_name'] ?? 'PORTÁTILES PERÚ' }}</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 14px; color: #333; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #2563eb; padding-bottom: 10px; }
        .header h1 { color: #2563eb; margin: 0; font-size: 28px; }
        .header p { margin: 5px 0 0 0; color: #666; }
        .product-card { border: 1px solid #eee; border-radius: 8px; padding: 15px; margin-bottom: 20px; page-break-inside: avoid; clear: both; overflow: hidden; }
        .product-image { float: left; width: 120px; height: 120px; margin-right: 15px; border: 1px solid #f1f5f9; padding: 5px; text-align: center; }
        .product-image img { max-width: 100%; max-height: 100%; object-fit: contain; }
        .product-content { float: left; width: calc(100% - 150px); }
        .product-title { font-size: 18px; font-weight: bold; color: #1e293b; margin-top: 0; }
        .product-price { font-size: 16px; color: #2563eb; font-weight: bold; }
        .product-offer { font-size: 14px; color: #dc2626; text-decoration: line-through; }
        .product-specs { margin-top: 10px; font-size: 12px; color: #555; }
        .footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 10px; color: #999; border-top: 1px solid #eee; padding-top: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $settings['store_name'] ?? 'PORTÁTILES PERÚ' }}</h1>
        <p>Catálogo Oficial de Productos - {{ date('d/m/Y') }}</p>
        <p>Contacto: {{ $settings['whatsapp_number'] ?? 'No especificado' }}</p>
    </div>

    @foreach($products as $product)
    <div class="product-card">
        <div class="product-image">
            @php $mainImg = $product->images->firstWhere('is_main', true) ?? $product->images->first(); @endphp
            @if($mainImg)
                <img src="{{ filter_var($mainImg->image_path, FILTER_VALIDATE_URL) ? $mainImg->image_path : public_path('storage/' . $mainImg->image_path) }}" alt="Imagen">
            @else
                <div style="padding-top:40px; color:#ccc;">Sin imagen</div>
            @endif
        </div>
        
        <div class="product-content">
            <h2 class="product-title">{{ $product->name }}</h2>
            <p style="margin:5px 0;"><strong>Marca:</strong> {{ $product->brand->name ?? 'N/A' }} | <strong>Código:</strong> {{ $product->code }}</p>
            
            <p class="product-price" style="margin:5px 0;">
                Precio: S/ {{ number_format($product->price, 2) }}
                @if($product->is_offer)
                    <span class="product-offer">(Normal: S/ {{ number_format($product->offer_price, 2) }})</span>
                @endif
            </p>
            
            <div class="product-specs">
                @if(is_array($product->technical_specs) && count($product->technical_specs) > 0)
                    <ul style="margin:5px 0; padding-left: 20px;">
                    @foreach($product->technical_specs as $k => $v)
                        <li><strong>{{ $k }}:</strong> {{ $v }}</li>
                    @endforeach
                    </ul>
                @else
                    <p style="margin:5px 0;">{{ $product->description }}</p>
                @endif
            </div>
        </div>
    </div>
    @endforeach
    
    <div class="footer">
        Generado el {{ date('d/m/Y H:i:s') }} - {{ $settings['store_name'] ?? 'PORTÁTILES PERÚ' }}
    </div>
</body>
</html>
