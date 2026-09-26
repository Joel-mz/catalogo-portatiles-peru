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
            text-align: left; 
            margin-bottom: 30px; 
            background: #dc2626; /* red-600 */
            color: #fff;
            padding: 20px 30px;
        }
        .header h1 { 
            color: #fff; 
            margin: 0 0 5px 0; 
            font-size: 24px; 
            text-transform: uppercase;
            font-weight: 900;
            letter-spacing: 1px;
        }
        .header p { 
            margin: 0; 
            color: #fee2e2; /* red-100 */
            font-size: 12px;
        }
        .header-contact {
            float: right;
            margin-top: -30px;
            color: white;
            font-weight: bold;
        }
        table.product-card {
            width: 100%;
            border: 1px solid #e2e8f0;
            border-collapse: collapse;
            margin-bottom: 15px;
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
            position: relative;
        }
        .product-title { 
            font-size: 16px; 
            font-weight: bold; 
            color: #1e293b; 
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
            margin-bottom: 10px;
            display: inline-block;
        }
        .product-price { 
            font-size: 18px; 
            color: #dc2626; /* red-600 */
            font-weight: 900; 
            margin: 0;
        }
        .product-offer { 
            font-size: 11px; 
            color: #94a3b8; /* slate-400 */
            text-decoration: line-through;
            margin-right: 8px;
        }
        .product-specs { 
            font-size: 11px; 
            color: #64748b; 
            line-height: 1.4;
        }
        .product-specs ul {
            margin: 0; 
            padding-left: 15px; 
        }
        .badge-offer {
            background: #facc15; /* yellow-400 */
            color: #713f12; /* yellow-900 */
            font-size: 10px;
            padding: 3px 6px;
            border-radius: 0 0 0 4px;
            font-weight: bold;
            position: absolute;
            top: 0;
            right: 0;
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
        <h1>{{ $options['custom_title'] ?? ($settings['store_name'] ?? 'PORTÁTILES PERÚ') }}</h1>
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
                
                @if($options['show_price'])
                <div class="pricing-box">
                    <p class="product-price">
                        @if($product->is_offer)
                            <span class="product-offer">S/ {{ number_format($product->price, 2) }}</span>
                            S/ {{ number_format($product->offer_price, 2) }}
                        @else
                            S/ {{ number_format($product->price, 2) }}
                        @endif
                    </p>
                </div>
                @endif
                
                @if($options['show_specs'])
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
                @endif
            </td>
        </tr>
    </table>
    @endforeach
    
    @if(!empty($options['bank_accounts']) || !empty($options['yape_plin']) || !empty($options['store_address']))
    <div style="page-break-inside: avoid; border: 2px dashed #cbd5e1; padding: 20px; border-radius: 8px; margin-top: 30px; background: #f8fafc;">
        <h3 style="margin-top: 0; color: #1e293b; text-align: center; text-transform: uppercase; font-size: 14px;">Información de Pagos y Contacto</h3>
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                @if(!empty($options['bank_accounts']))
                <td style="width: 33%; vertical-align: top; padding: 10px; border-right: 1px solid #e2e8f0;">
                    <strong style="color: #2563eb; display: block; margin-bottom: 5px;">Cuentas Bancarias</strong>
                    {!! nl2br(e($options['bank_accounts'])) !!}
                </td>
                @endif
                
                @if(!empty($options['yape_plin']))
                <td style="width: 33%; vertical-align: top; padding: 10px; border-right: 1px solid #e2e8f0;">
                    <strong style="color: #10b981; display: block; margin-bottom: 5px;">Yape / Plin</strong>
                    {!! nl2br(e($options['yape_plin'])) !!}
                </td>
                @endif
                
                @if(!empty($options['store_address']))
                <td style="width: 33%; vertical-align: top; padding: 10px;">
                    <strong style="color: #ef4444; display: block; margin-bottom: 5px;">Nuestra Tienda</strong>
                    {{ $options['store_address'] }}
                </td>
                @endif
            </tr>
        </table>
    </div>
    @endif

    <div class="footer">
        Generado el {{ date('d/m/Y H:i:s') }} - Catálogo exclusivo de {{ $settings['store_name'] ?? 'PORTÁTILES PERÚ' }}
    </div>
</body>
</html>
