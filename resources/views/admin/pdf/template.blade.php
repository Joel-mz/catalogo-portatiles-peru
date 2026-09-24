<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Catálogo - {{ $settings['store_name'] ?? 'PORTÁTILES PERÚ' }}</title>
    <style>
        body { 
            font-family: 'Helvetica', 'Arial', sans-serif; 
            font-size: 12px; 
            color: #334155; 
            background: #ffffff;
            margin: 0;
            padding: 10px;
        }
        .header { 
            text-align: center; 
            margin-bottom: 30px; 
            background: #1e293b;
            color: #fff;
            padding: 20px;
            border-radius: 8px;
        }
        .header h1 { 
            color: #fff; 
            margin: 0 0 5px 0; 
            font-size: 26px; 
            text-transform: uppercase;
        }
        .header p { 
            margin: 0 0 10px 0; 
            color: #94a3b8; 
            font-size: 13px;
        }
        .header-contact {
            background: #2563eb;
            color: white;
            padding: 5px 15px;
            border-radius: 15px;
            font-weight: bold;
            display: inline-block;
        }
        table.product-card {
            width: 100%;
            border: 1px solid #e2e8f0;
            border-collapse: collapse;
            margin-bottom: 20px;
            page-break-inside: avoid;
        }
        table.product-card td {
            padding: 15px;
            vertical-align: top;
        }
        .td-image {
            width: 160px;
            border-right: 1px solid #f1f5f9;
            background: #f8fafc;
            text-align: center;
        }
        .td-image img {
            max-width: 150px;
            max-height: 150px;
        }
        .td-content {
            background: #ffffff;
        }
        .product-title { 
            font-size: 18px; 
            font-weight: bold; 
            color: #0f172a; 
            margin: 0 0 5px 0; 
        }
        .product-meta {
            font-size: 11px;
            color: #64748b;
            margin-bottom: 10px;
            padding-bottom: 8px;
            border-bottom: 1px solid #f1f5f9;
        }
        .badge-brand {
            background: #f1f5f9;
            padding: 2px 6px;
            border-radius: 4px;
            color: #475569;
            font-weight: bold;
        }
        .pricing-box {
            background: #f0f9ff;
            border: 1px solid #bae6fd;
            padding: 8px 12px;
            margin-bottom: 10px;
            border-radius: 6px;
            display: inline-block;
        }
        .product-price { 
            font-size: 18px; 
            color: #0284c7; 
            font-weight: bold; 
            margin: 0;
        }
        .product-offer { 
            font-size: 11px; 
            color: #ef4444; 
            text-decoration: line-through;
            margin-left: 8px;
        }
        .product-specs { 
            font-size: 11px; 
            color: #475569; 
            line-height: 1.4;
        }
        .product-specs ul {
            margin: 0; 
            padding-left: 15px; 
        }
        .badge-offer {
            background: #ef4444;
            color: white;
            font-size: 9px;
            padding: 2px 4px;
            border-radius: 3px;
            font-weight: bold;
            vertical-align: super;
        }
        .footer { 
            position: fixed; 
            bottom: -20px; 
            left: 0;
            width: 100%; 
            text-align: center; 
            font-size: 10px; 
            color: #94a3b8; 
            border-top: 1px solid #e2e8f0; 
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $settings['store_name'] ?? 'PORTÁTILES PERÚ' }}</h1>
        <p>Catálogo Oficial de Productos - {{ date('d/m/Y') }}</p>
        <div class="header-contact">WhatsApp: {{ $settings['whatsapp_number'] ?? 'No especificado' }}</div>
    </div>

    @foreach($products as $product)
    <table class="product-card">
        <tr>
            <td class="td-image">
                @php 
                    $mainImg = $product->images->firstWhere('is_main', true) ?? $product->images->first(); 
                    $imgSrc = null;
                    if($mainImg) {
                        if(filter_var($mainImg->image_path, FILTER_VALIDATE_URL)) {
                            $imgSrc = $mainImg->image_path;
                        } else {
                            $path = storage_path('app/public/' . $mainImg->image_path);
                            if(file_exists($path)) {
                                $type = pathinfo($path, PATHINFO_EXTENSION);
                                $data = file_get_contents($path);
                                $imgSrc = 'data:image/' . $type . ';base64,' . base64_encode($data);
                            }
                        }
                    }
                @endphp
                
                @if($imgSrc)
                    <img src="{{ $imgSrc }}" alt="Imagen">
                @else
                    <div style="padding-top:50px; color:#cbd5e1; font-weight:bold;">SIN IMAGEN</div>
                @endif
            </td>
            <td class="td-content">
                <h2 class="product-title">
                    {{ $product->name }}
                    @if($product->is_offer)
                        <span class="badge-offer">Oferta</span>
                    @endif
                </h2>
                <div class="product-meta">
                    <span class="badge-brand">{{ $product->brand->name ?? 'Variados' }}</span> 
                    &nbsp; | &nbsp; P/N: {{ $product->code }}
                </div>
                
                <div class="pricing-box">
                    <p class="product-price">
                        S/ {{ number_format($product->price, 2) }}
                        @if($product->is_offer)
                            <span class="product-offer">Normal: S/ {{ number_format($product->offer_price, 2) }}</span>
                        @endif
                    </p>
                </div>
                
                <div class="product-specs">
                    @if(is_array($product->technical_specs) && count($product->technical_specs) > 0)
                        <ul>
                        @foreach($product->technical_specs as $k => $v)
                            <li><strong>{{ $k }}:</strong> {{ $v }}</li>
                        @endforeach
                        </ul>
                    @else
                        <p style="margin:0;">{{ $product->description ?: 'Sin descripción detallada.' }}</p>
                    @endif
                </div>
            </td>
        </tr>
    </table>
    @endforeach
    
    <div class="footer">
        Generado el {{ date('d/m/Y H:i:s') }} - Catálogo exclusivo de {{ $settings['store_name'] ?? 'PORTÁTILES PERÚ' }}
    </div>
</body>
</html>
