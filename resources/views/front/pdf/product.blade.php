<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ficha Técnica - {{ $product->name }}</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #111827; line-height: 1.5; margin: 0; padding: 20px; font-size: 14px; }
        .header { text-align: center; border-bottom: 3px solid #2855d9; padding-bottom: 20px; margin-bottom: 30px; }
        .store-name { font-size: 28px; font-weight: bold; color: #2855d9; margin: 0 0 5px 0; text-transform: uppercase; }
        .contact { font-size: 13px; color: #6b7280; margin: 0; }
        
        .product-title { font-size: 24px; font-weight: bold; margin-bottom: 5px; color: #111827; }
        .product-subtitle { font-size: 14px; color: #4f46e5; font-weight: bold; margin-top: 0; margin-bottom: 20px; text-transform: uppercase; }
        
        .grid { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        .grid td { vertical-align: top; }
        
        .image-col { width: 40%; text-align: center; padding-right: 20px; }
        .image-col img { max-width: 100%; max-height: 250px; }
        .info-col { width: 60%; }
        
        .price-box { background-color: #f3f4f6; padding: 15px; border-radius: 8px; margin-bottom: 20px; border-left: 4px solid #2855d9; }
        .price-label { font-size: 12px; color: #6b7280; font-weight: bold; text-transform: uppercase; margin: 0 0 5px 0; }
        .price-value { font-size: 26px; font-weight: bold; color: #111827; margin: 0; }
        .price-original { font-size: 14px; color: #9ca3af; text-decoration: line-through; margin-left: 10px; }
        
        .desc { font-size: 14px; color: #4b5563; margin-bottom: 20px; }
        
        .specs { width: 100%; border-collapse: collapse; }
        .specs th { background-color: #1f2937; color: white; text-align: left; padding: 10px; font-size: 14px; }
        .specs td { padding: 10px; border-bottom: 1px solid #e5e7eb; font-size: 13px; }
        .specs .label { font-weight: bold; color: #4b5563; width: 35%; }
        
        .footer { position: fixed; bottom: -20px; left: 0px; right: 0px; height: 50px; text-align: center; color: #9ca3af; font-size: 12px; border-top: 1px solid #e5e7eb; padding-top: 15px; }
    </style>
</head>
<body>
    @php
        $storeName = $settings['store_name'] ?? 'PORTÁTILES PERÚ';
        $price = $product->is_offer && $product->offer_price ? $product->offer_price : $product->price;
        $imgSrc = \App\Services\PdfService::getProductMainImageBase64($product);
    @endphp

    <div class="header">
        <h1 class="store-name">{{ $storeName }}</h1>
        <p class="contact">WhatsApp: {{ $settings['whatsapp_number'] ?? '-' }} | Ventas a todo el Perú</p>
    </div>

    <table class="grid">
        <tr>
            <td class="image-col">
                @if($imgSrc)
                    <img src="{{ $imgSrc }}" style="max-width: 230px; max-height: 230px; border-radius: 8px; border: 1px solid #e5e7eb; padding: 4px; background: #ffffff;">
                @else
                    <div style="width:100%; height:180px; background:#f8fafc; text-align:center; line-height:180px; color:#94a3b8; border: 1px dashed #cbd5e1; border-radius:8px; font-size: 13px; font-weight: bold;">Sin Imagen</div>
                @endif
            </td>
            <td class="info-col">
                <h2 class="product-title">{{ $product->name }}</h2>
                <p class="product-subtitle">{{ $product->brand->name ?? 'Tecnología' }} | COD: {{ $product->code }}</p>
                
                <div class="price-box">
                    <p class="price-label">Precio al Contado</p>
                    <p class="price-value">S/ {{ number_format((float) $price, 2) }} 
                        @if($product->is_offer && $product->offer_price)
                        <span class="price-original">S/ {{ number_format((float) $product->price, 2) }}</span>
                        @endif
                    </p>
                </div>
                
                <p class="desc">{{ $product->description ?: 'Consulte las especificaciones completas con nuestro equipo de ventas.' }}</p>
            </td>
        </tr>
    </table>

    <table class="specs">
        <thead>
            <tr>
                <th colspan="2">Especificaciones Técnicas</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="label">Marca</td>
                <td>{{ $product->brand->name ?? '—' }}</td>
            </tr>
            <tr>
                <td class="label">Categoría</td>
                <td>{{ $product->category->name ?? '—' }}</td>
            </tr>
            @if($product->deviceModel)
            <tr>
                <td class="label">Modelo Comercial</td>
                <td>{{ $product->deviceModel->name }}</td>
            </tr>
            @endif
            @if(is_array($product->technical_specs))
                @foreach($product->technical_specs as $key => $value)
                <tr>
                    <td class="label">{{ $key }}</td>
                    <td>{{ $value }}</td>
                </tr>
                @endforeach
            @endif
            <tr>
                <td class="label">Garantía</td>
                <td>{{ $product->warranty ?: 'Consultar' }}</td>
            </tr>
            <tr>
                <td class="label">Disponibilidad</td>
                <td>{{ $product->stock > 0 ? 'En Stock' : 'A Pedido / Consultar' }}</td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        Ficha técnica generada el {{ date('d/m/Y') }}. Los precios y la disponibilidad están sujetos a cambios sin previo aviso.<br>
        Para confirmar su compra, comuníquese con nosotros por WhatsApp.
    </div>
</body>
</html>
