<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Pedido #{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #333; line-height: 1.5; font-size: 14px; margin: 0; padding: 20px; }
        .header { border-bottom: 2px solid #2855d9; padding-bottom: 20px; margin-bottom: 30px; }
        .header table { width: 100%; }
        .header td { vertical-align: top; }
        .store-info { text-align: right; }
        .store-name { font-size: 24px; font-weight: bold; color: #2855d9; margin: 0; }
        .title { font-size: 20px; margin: 0 0 5px 0; color: #1f2937; }
        .subtitle { font-size: 14px; color: #6b7280; margin: 0; }
        
        .info-section { margin-bottom: 30px; }
        .info-table { width: 100%; border-collapse: collapse; }
        .info-table td { padding: 5px 0; }
        .info-label { font-weight: bold; color: #4b5563; width: 150px; }
        
        .items-table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        .items-table th { background-color: #f3f4f6; color: #374151; text-align: left; padding: 10px; border-bottom: 1px solid #e5e7eb; }
        .items-table td { padding: 10px; border-bottom: 1px solid #e5e7eb; vertical-align: top; }
        .items-table .text-right { text-align: right; }
        .items-table .font-bold { font-weight: bold; }
        
        .totals { width: 300px; float: right; }
        .totals table { width: 100%; border-collapse: collapse; }
        .totals td { padding: 8px 0; border-bottom: 1px solid #f3f4f6; }
        .totals .total-row td { font-size: 18px; font-weight: bold; color: #111827; border-bottom: none; border-top: 2px solid #e5e7eb; padding-top: 15px; }
        
        .notes { clear: both; margin-top: 50px; padding: 15px; background-color: #f9fafb; border-left: 4px solid #6366f1; border-radius: 4px; }
        .notes h4 { margin-top: 0; margin-bottom: 5px; color: #4338ca; font-size: 14px; }
        .notes p { margin: 0; font-size: 13px; color: #374151; }
        
        .footer { position: fixed; bottom: -20px; left: 0px; right: 0px; height: 50px; text-align: center; color: #9ca3af; font-size: 12px; border-top: 1px solid #e5e7eb; padding-top: 15px; }
    </style>
</head>
<body>
    @php
        $orderDetails = $order->details ?? [];
        $orderItems = $orderDetails['items'] ?? $orderDetails;
        $storeName = $settings['store_name'] ?? 'PORTÁTILES PERÚ / MOYO TECH';
    @endphp

    <div class="header">
        <table>
            <tr>
                <td>
                    <h1 class="title">ORDEN DE PEDIDO</h1>
                    <p class="subtitle">N°: <strong>{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</strong></p>
                    <p class="subtitle">Fecha: {{ $order->created_at->format('d/m/Y h:i A') }}</p>
                    <p class="subtitle">Estado: <strong>{{ strtoupper($order->status) }}</strong></p>
                </td>
                <td class="store-info">
                    <h2 class="store-name">{{ $storeName }}</h2>
                    <p class="subtitle">WhatsApp: {{ $settings['whatsapp_number'] ?? '-' }}</p>
                    <p class="subtitle">www.catalogo-virtual-portatiles-peru.test</p>
                </td>
            </tr>
        </table>
    </div>

    <div class="info-section">
        <table class="info-table">
            <tr>
                <td class="info-label">CLIENTE:</td>
                <td>{{ $order->client->name ?? 'Desconocido' }}</td>
            </tr>
            <tr>
                <td class="info-label">TELÉFONO:</td>
                <td>{{ $order->client->phone ?? '-' }}</td>
            </tr>
            <tr>
                <td class="info-label">COMPROBANTE:</td>
                <td>{{ isset($orderDetails['receipt_type']) ? ucfirst($orderDetails['receipt_type']) : 'Boleta' }}{{ !empty($orderDetails['tax_id']) ? ' - RUC: '.$orderDetails['tax_id'] : '' }}</td>
            </tr>
        </table>
    </div>

    <table class="items-table">
        <thead>
            <tr>
                <th>DESCRIPCIÓN</th>
                <th class="text-right">CANT.</th>
                <th class="text-right">P. UNIT (S/)</th>
                <th class="text-right">IMPORTE (S/)</th>
            </tr>
        </thead>
        <tbody>
            @if(is_array($orderItems) && count($orderItems) > 0)
                @foreach($orderItems as $item)
                @continue(!is_array($item) || !isset($item['name']))
                @php
                    $unitPrice = $item['unit_price'] ?? $item['price'] ?? 0;
                    $quantity = $item['quantity'] ?? 1;
                    $subtotal = $item['subtotal'] ?? ($unitPrice * $quantity);
                @endphp
                <tr>
                    <td>{{ $item['name'] ?? 'Producto' }}</td>
                    <td class="text-right">{{ $quantity }}</td>
                    <td class="text-right">{{ number_format($unitPrice, 2) }}</td>
                    <td class="text-right font-bold">{{ number_format($subtotal, 2) }}</td>
                </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="4" style="text-align: center; color: #9ca3af;">No hay productos detallados.</td>
                </tr>
            @endif
        </tbody>
    </table>

    <div class="totals">
        <table>
            <tr class="total-row">
                <td>TOTAL A PAGAR:</td>
                <td class="text-right">S/ {{ number_format($order->total_amount, 2) }}</td>
            </tr>
        </table>
    </div>

    @if(!empty($orderDetails['sale_note']))
    <div class="notes">
        <h4>NOTA DEL CLIENTE</h4>
        <p>{{ $orderDetails['sale_note'] }}</p>
    </div>
    @endif

    <div class="footer">
        Gracias por su preferencia. Documento de carácter informativo.
    </div>
</body>
</html>
