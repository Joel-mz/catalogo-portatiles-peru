<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Catálogo - {{ $settings['store_name'] ?? 'PORTÁTILES PERÚ' }}</title>
    @php
        $theme = $options['color_theme'] ?? 'red';
        $themeColors = [
            'red'    => ['header' => '#dc2626', 'header_text' => '#fee2e2', 'price' => '#dc2626', 'accent' => '#fca5a5'],
            'blue'   => ['header' => '#2563eb', 'header_text' => '#dbeafe', 'price' => '#1d4ed8', 'accent' => '#93c5fd'],
            'green'  => ['header' => '#16a34a', 'header_text' => '#dcfce7', 'price' => '#15803d', 'accent' => '#86efac'],
            'purple' => ['header' => '#7c3aed', 'header_text' => '#ede9fe', 'price' => '#6d28d9', 'accent' => '#c4b5fd'],
            'orange' => ['header' => '#ea580c', 'header_text' => '#ffedd5', 'price' => '#c2410c', 'accent' => '#fdba74'],
            'dark'   => ['header' => '#0f172a', 'header_text' => '#cbd5e1', 'price' => '#f59e0b', 'accent' => '#475569'],
        ];
        $c = $themeColors[$theme] ?? $themeColors['red'];
    @endphp
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 12px;
            color: #334155;
            background: #ffffff;
            margin: 0;
            padding: 0;
        }
        .header {
            background: {{ $c['header'] }};
            color: #fff;
            padding: 18px 28px;
            margin-bottom: 20px;
        }
        .header-inner {
            width: 100%;
        }
        .header h1 {
            color: #fff;
            margin: 0 0 4px 0;
            font-size: 22px;
            text-transform: uppercase;
            font-weight: 900;
            letter-spacing: 1px;
        }
        .header p {
            margin: 0;
            color: {{ $c['header_text'] }};
            font-size: 11px;
        }
        .header-right {
            color: {{ $c['header_text'] }};
            font-size: 11px;
            font-weight: bold;
            text-align: right;
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
        }
        table.product-card {
            width: 100%;
            border: 1px solid #e2e8f0;
            border-collapse: collapse;
            margin-bottom: 12px;
            page-break-inside: avoid;
        }
        table.product-card td {
            padding: 12px 14px;
            vertical-align: top;
        }
        .td-image {
            width: 150px;
            border-right: 1px solid #f1f5f9;
            background: #f8fafc;
            text-align: center;
            vertical-align: middle;
        }
        .td-image img {
            max-width: 140px;
            max-height: 140px;
        }
        .td-image .no-img {
            padding: 40px 0;
            color: #cbd5e1;
            font-weight: bold;
            font-size: 10px;
        }
        .td-content {
            background: #ffffff;
            position: relative;
        }
        .product-title {
            font-size: 14px;
            font-weight: bold;
            color: #1e293b;
            margin: 0 0 4px 0;
            padding-right: 50px;
        }
        .product-meta {
            font-size: 10px;
            color: #64748b;
            margin-bottom: 8px;
            padding-bottom: 6px;
            border-bottom: 1px solid #f1f5f9;
        }
        .badge-brand {
            background: #f1f5f9;
            padding: 2px 6px;
            border-radius: 3px;
            color: #475569;
            font-weight: bold;
        }
        .pricing-row {
            margin-bottom: 6px;
        }
        .product-price {
            font-size: 16px;
            color: {{ $c['price'] }};
            font-weight: 900;
            margin: 0;
        }
        .product-offer {
            font-size: 10px;
            color: #94a3b8;
            text-decoration: line-through;
            margin-right: 6px;
        }
        .min-price-label {
            font-size: 10px;
            color: #059669;
            font-weight: bold;
            margin-bottom: 4px;
        }
        .product-description {
            font-size: 10px;
            color: #475569;
            line-height: 1.5;
            margin-bottom: 6px;
        }
        .product-specs {
            font-size: 10px;
            color: #64748b;
            line-height: 1.4;
            margin-bottom: 6px;
        }
        .product-specs ul {
            margin: 0;
            padding-left: 14px;
        }
        .stock-badge {
            display: inline-block;
            font-size: 9px;
            font-weight: bold;
            padding: 2px 7px;
            border-radius: 3px;
        }
        .stock-ok  { background: #dcfce7; color: #166534; }
        .stock-low { background: #fef9c3; color: #713f12; }
        .stock-no  { background: #fee2e2; color: #991b1b; }
        .badge-offer {
            background: #fde047;
            color: #713f12;
            font-size: 9px;
            padding: 2px 6px;
            border-radius: 0 0 0 4px;
            font-weight: bold;
            position: absolute;
            top: 0;
            right: 0;
        }
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            text-align: center;
            font-size: 9px;
            color: #94a3b8;
            border-top: 1px solid #e2e8f0;
            padding: 6px 20px;
            background: #fff;
        }
        .contact-box {
            page-break-inside: avoid;
            border: 1.5px dashed #cbd5e1;
            padding: 16px 20px;
            border-radius: 6px;
            margin-top: 24px;
            background: #f8fafc;
        }
        .contact-box h3 {
            margin: 0 0 10px 0;
            color: #1e293b;
            text-align: center;
            text-transform: uppercase;
            font-size: 12px;
        }
        .contact-table { width: 100%; border-collapse: collapse; }
        .contact-table td { vertical-align: top; padding: 8px 10px; }
    </style>
</head>
<body>

    {{-- ── ENCABEZADO ── --}}
    <div class="header">
        <table class="header-table">
            <tr>
                <td style="vertical-align:middle; padding:0;">
                    <h1>{{ !empty($options['custom_title']) ? $options['custom_title'] : ($settings['store_name'] ?? 'PORTÁTILES PERÚ') }}</h1>
                    <p>Catálogo Oficial de Productos · {{ date('d/m/Y') }}</p>
                </td>
                <td class="header-right" style="vertical-align:middle; padding:0; white-space:nowrap;">
                    @if(!empty($settings['whatsapp_number']))
                        WhatsApp: {{ $settings['whatsapp_number'] }}
                    @endif
                </td>
            </tr>
        </table>
    </div>

    {{-- ── PRODUCTOS ── --}}
    @foreach($products as $product)
    <table class="product-card">
        <tr>
            {{-- Imagen --}}
            <td class="td-image">
                @php
                    $imgSrc = \App\Services\PdfService::getProductMainImageBase64($product);
                @endphp
                @if($imgSrc)
                    <img src="{{ $imgSrc }}" alt="{{ $product->name }}">
                @else
                    <div class="no-img">SIN<br>IMAGEN</div>
                @endif
            </td>

            {{-- Contenido --}}
            <td class="td-content">
                <h2 class="product-title">
                    {{ $product->name }}
                    @if($product->is_offer)
                        <span class="badge-offer">OFERTA</span>
                    @endif
                </h2>

                <div class="product-meta">
                    <span class="badge-brand">{{ $product->brand->name ?? 'Variados' }}</span>
                    &nbsp;|&nbsp; Código: {{ $product->code }}
                    @if($product->category)
                        &nbsp;|&nbsp; {{ $product->category->name }}
                    @endif
                </div>

                {{-- Precio de venta --}}
                @if(!empty($options['show_price']))
                <div class="pricing-row">
                    <p class="product-price">
                        @if($product->is_offer && $product->offer_price)
                            <span class="product-offer">S/ {{ number_format((float)$product->price, 2) }}</span>
                            S/ {{ number_format((float)$product->offer_price, 2) }}
                        @else
                            S/ {{ number_format((float)$product->price, 2) }}
                        @endif
                    </p>
                </div>
                @endif

                {{-- Precio mínimo / mayorista --}}
                @if(!empty($options['show_min_price']) && $product->min_price)
                <div class="min-price-label">
                    Precio mín. (mayorista): S/ {{ number_format((float)$product->min_price, 2) }}
                </div>
                @endif

                {{-- Descripción --}}
                @if(!empty($options['show_description']) && $product->description)
                <div class="product-description">{{ $product->description }}</div>
                @endif

                {{-- Especificaciones técnicas --}}
                @if(!empty($options['show_specs']) && is_array($product->technical_specs) && count($product->technical_specs) > 0)
                <div class="product-specs">
                    <ul>
                    @foreach($product->technical_specs as $k => $v)
                        <li><strong>{{ $k }}:</strong> {{ $v }}</li>
                    @endforeach
                    </ul>
                </div>
                @endif

                {{-- Stock / Disponibilidad --}}
                @if(!empty($options['show_stock']))
                    @php
                        $stockClass = $product->stock > 5 ? 'stock-ok' : ($product->stock > 0 ? 'stock-low' : 'stock-no');
                        $stockLabel = $product->stock > 5
                            ? '✓ En Stock (' . $product->stock . ' unid.)'
                            : ($product->stock > 0 ? '⚠ Stock bajo (' . $product->stock . ' unid.)' : '✕ A Pedido / Consultar');
                    @endphp
                    <span class="stock-badge {{ $stockClass }}">{{ $stockLabel }}</span>
                @endif
            </td>
        </tr>
    </table>
    @endforeach

    {{-- ── SECCIÓN CONTACTO / PAGOS ── --}}
    @if(!empty($options['bank_accounts']) || !empty($options['yape_plin']) || !empty($options['store_address']))
    <div class="contact-box">
        <h3>Información de Pagos y Contacto</h3>
        <table class="contact-table">
            <tr>
                @if(!empty($options['bank_accounts']))
                <td style="{{ !empty($options['yape_plin']) || !empty($options['store_address']) ? 'border-right:1px solid #e2e8f0;' : '' }}">
                    <strong style="color:#2563eb; display:block; margin-bottom:4px;">Cuentas Bancarias</strong>
                    {!! nl2br(e($options['bank_accounts'])) !!}
                </td>
                @endif
                @if(!empty($options['yape_plin']))
                <td style="{{ !empty($options['store_address']) ? 'border-right:1px solid #e2e8f0;' : '' }}">
                    <strong style="color:#10b981; display:block; margin-bottom:4px;">Yape / Plin</strong>
                    {!! nl2br(e($options['yape_plin'])) !!}
                </td>
                @endif
                @if(!empty($options['store_address']))
                <td>
                    <strong style="color:{{ $c['price'] }}; display:block; margin-bottom:4px;">Nuestra Tienda</strong>
                    {{ $options['store_address'] }}
                </td>
                @endif
            </tr>
        </table>
    </div>
    @endif

    {{-- ── PIE DE PÁGINA ── --}}
    <div class="footer">
        Generado el {{ date('d/m/Y H:i') }} · Catálogo exclusivo de {{ $settings['store_name'] ?? 'PORTÁTILES PERÚ' }}
    </div>

</body>
</html>
