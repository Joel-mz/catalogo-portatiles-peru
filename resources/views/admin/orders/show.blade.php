@extends('layouts.admin')

@section('header_title', 'Detalle del Pedido')

@section('content')
@php
    $orderDetails = $order->details ?? [];
    $orderItems = $orderDetails['items'] ?? $orderDetails;
@endphp
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.orders.index') }}" class="text-slate-400 hover:text-slate-600 transition-colors">
            <i class="fa-solid fa-arrow-left text-xl"></i>
        </a>
        <h2 class="text-2xl font-semibold text-slate-800">Pedido #{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</h2>
        <span class="px-3 py-1 text-sm font-semibold rounded-full 
            {{ $order->status === 'Pendiente' ? 'bg-yellow-100 text-yellow-800' : '' }}
            {{ $order->status === 'Contactado' ? 'bg-blue-100 text-blue-800' : '' }}
            {{ $order->status === 'Completado' ? 'bg-green-100 text-green-800' : '' }}
            {{ $order->status === 'Cancelado' ? 'bg-red-100 text-red-800' : '' }}
        ">
            {{ $order->status }}
        </span>
        
        <a href="{{ route('admin.orders.pdf', $order) }}" class="ml-auto flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-semibold transition">
            <i class="fa-solid fa-file-pdf"></i> Descargar PDF
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Detalles del Cliente -->
        <div class="soft-card p-6 md:col-span-1">
            <h3 class="text-lg font-medium text-slate-900 mb-4 border-b border-slate-100 pb-2">Información del Cliente</h3>
            <div class="space-y-3">
                <div>
                    <span class="block text-xs text-slate-500 font-medium">Nombre</span>
                    <span class="text-sm text-slate-900 font-medium">{{ $order->client->name ?? 'Desconocido' }}</span>
                </div>
                <div>
                    <span class="block text-xs text-slate-500 font-medium">Teléfono / WhatsApp</span>
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $order->client->phone) }}" target="_blank" class="text-sm text-blue-600 hover:underline">
                        {{ $order->client->phone ?? '-' }} <i class="fa-brands fa-whatsapp"></i>
                    </a>
                </div>
                <div>
                    <span class="block text-xs text-slate-500 font-medium">Correo</span>
                    <span class="text-sm text-slate-900">{{ $order->client->email ?? '-' }}</span>
                </div>
                <div>
                    <span class="block text-xs text-slate-500 font-medium">Fecha del Pedido</span>
                    <span class="text-sm text-slate-900">{{ $order->created_at->format('d/m/Y h:i A') }}</span>
                </div>
                @if(isset($orderDetails['receipt_type']))
                    <div><span class="block text-xs text-slate-500 font-medium">Comprobante</span><span class="text-sm text-slate-900">{{ ucfirst($orderDetails['receipt_type']) }}{{ !empty($orderDetails['tax_id']) ? ' · RUC '.$orderDetails['tax_id'] : '' }}</span></div>
                @endif
            </div>
            
            <div class="mt-6 pt-4 border-t border-slate-100">
                <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <label class="block text-xs text-slate-500 font-medium mb-1">Cambiar Estado</label>
                    <div class="flex gap-2">
                        <select name="status" class="flex-1 text-sm rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="Pendiente" {{ $order->status === 'Pendiente' ? 'selected' : '' }}>Pendiente</option>
                            <option value="Contactado" {{ $order->status === 'Contactado' ? 'selected' : '' }}>Contactado</option>
                            <option value="Completado" {{ $order->status === 'Completado' ? 'selected' : '' }}>Completado</option>
                            <option value="Cancelado" {{ $order->status === 'Cancelado' ? 'selected' : '' }}>Cancelado</option>
                        </select>
                        <button type="submit" class="px-3 py-2 bg-slate-800 text-white text-sm rounded-md hover:bg-slate-900">Actualizar</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Productos del Pedido -->
        <div class="soft-card p-6 md:col-span-2">
            <h3 class="text-lg font-medium text-slate-900 mb-4 border-b border-slate-100 pb-2">Productos Solicitados</h3>
            
            <div class="space-y-4">
                @if(is_array($orderItems) && count($orderItems) > 0)
                    @foreach($orderItems as $item)
                    @continue(!is_array($item) || !isset($item['name']))
                    @php
                        $unitPrice = $item['unit_price'] ?? $item['price'] ?? 0;
                        $quantity = $item['quantity'] ?? 1;
                        $subtotal = $item['subtotal'] ?? ($unitPrice * $quantity);
                    @endphp
                    <div class="flex items-center gap-4 py-2 border-b border-slate-50 last:border-0">
                        <div class="w-16 h-16 bg-slate-100 rounded-md flex-shrink-0 flex items-center justify-center overflow-hidden">
                            @if(isset($item['image']))
                                <img src="{{ Storage::url($item['image']) }}" class="w-full h-full object-cover">
                            @else
                                <i class="fa-solid fa-laptop text-slate-300 text-xl"></i>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-semibold text-slate-900 truncate">{{ $item['name'] ?? 'Producto' }}</h4>
                            <p class="text-xs text-slate-500">Precio Unitario: S/ {{ number_format($unitPrice, 2) }}</p>
                        </div>
                        <div class="text-right">
                            <div class="text-sm font-medium text-slate-900">x{{ $quantity }}</div>
                            <div class="text-sm font-bold text-blue-600">S/ {{ number_format($subtotal, 2) }}</div>
                        </div>
                    </div>
                    @endforeach
                @else
                    <p class="text-sm text-slate-500 italic">No hay detalles de productos legibles en este pedido.</p>
                @endif
            </div>

            @if(!empty($orderDetails['sale_note']))
                <div class="mt-4 rounded-xl border border-indigo-100 bg-indigo-50 p-4"><p class="text-xs font-bold text-indigo-900">Nota para la venta</p><p class="mt-1 whitespace-pre-line text-sm text-indigo-800">{{ $orderDetails['sale_note'] }}</p></div>
            @endif

            <div class="mt-6 pt-4 border-t border-slate-200 flex justify-between items-center">
                <span class="text-sm font-medium text-slate-500">Total del Pedido</span>
                <span class="text-2xl font-bold text-slate-900">S/ {{ number_format($order->total_amount, 2) }}</span>
            </div>
        </div>
    </div>
</div>
@endsection
