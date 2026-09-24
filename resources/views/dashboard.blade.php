@extends('layouts.admin')

@section('header_title', 'Dashboard')

@section('content')
@php
    $productsCount   = \App\Models\Product::where('status', 1)->count();
    $ordersCount     = \App\Models\Order::count();
    $clientsCount    = \App\Models\Client::count();
    $categoriesCount = \App\Models\Category::count();
    $recentOrders    = \App\Models\Order::with('client')->latest()->take(4)->get();
    $recentProds     = \App\Models\Product::with(['category', 'brand'])->latest()->take(5)->get();
    $storeName       = \App\Models\Setting::where('key', 'store_name')->value('value') ?? 'MPC Antigravity';
    $wpNumber        = \App\Models\Setting::where('key', 'whatsapp_number')->value('value') ?? 'No configurado';
@endphp

<div class="flex flex-col xl:flex-row gap-5 pb-8">

    {{-- ══ LEFT / CENTER ══ --}}
    <div class="flex-1 flex flex-col min-w-0 gap-5">

        {{-- Welcome Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl flex items-center justify-center text-white text-xl font-bold shadow-lg"
                     style="background:linear-gradient(135deg,#2563eb,#7c3aed);">
                    <i class="fa-solid fa-chart-pie"></i>
                </div>
                <div>
                    <h1 class="page-title">Bienvenido, {{ Auth::user()->name ?? 'Administrador' }}</h1>
                    <p class="page-subtitle">Resumen y métricas en tiempo real de tu tienda tecnológica.</p>
                </div>
            </div>
            <div class="hidden lg:flex items-center gap-3 text-xs font-medium text-slate-600 bg-white border border-slate-200 px-4 py-2.5 rounded-xl shadow-sm">
                <i class="fa-regular fa-calendar text-blue-500"></i>
                {{ now()->locale('es')->isoFormat('D [de] MMMM, YYYY') }}
                <span class="text-slate-300">|</span>
                <i class="fa-regular fa-clock text-slate-400"></i>
                {{ now()->format('H:i') }}
            </div>
        </div>

        {{-- KPI Cards --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">

            {{-- KPI 1: Products --}}
            <div class="kpi-card hover:border-blue-300">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Productos Activos</span>
                    <div class="kpi-icon bg-blue-50 text-blue-600">
                        <i class="fa-solid fa-box"></i>
                    </div>
                </div>
                <p class="text-3xl font-display font-black text-slate-800 mb-1">{{ $productsCount }}</p>
                <a href="{{ route('admin.products.index') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-700 flex items-center gap-1">
                    Ver catálogo <i class="fa-solid fa-chevron-right text-[9px]"></i>
                </a>
                <div class="absolute bottom-0 right-0 w-16 h-16 rounded-full opacity-5 bg-blue-600" style="transform:translate(25%,25%)"></div>
            </div>

            {{-- KPI 2: Orders --}}
            <div class="kpi-card hover:border-green-300">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Pedidos WhatsApp</span>
                    <div class="kpi-icon bg-green-50 text-green-600">
                        <i class="fa-brands fa-whatsapp"></i>
                    </div>
                </div>
                <p class="text-3xl font-display font-black text-slate-800 mb-1">{{ $ordersCount }}</p>
                <a href="{{ route('admin.orders.index') }}" class="text-xs font-semibold text-green-600 hover:text-green-700 flex items-center gap-1">
                    Ver pedidos <i class="fa-solid fa-chevron-right text-[9px]"></i>
                </a>
                <div class="absolute bottom-0 right-0 w-16 h-16 rounded-full opacity-5 bg-green-600" style="transform:translate(25%,25%)"></div>
            </div>

            {{-- KPI 3: Clients --}}
            <div class="kpi-card hover:border-purple-300">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Clientes Registrados</span>
                    <div class="kpi-icon bg-purple-50 text-purple-600">
                        <i class="fa-regular fa-user"></i>
                    </div>
                </div>
                <p class="text-3xl font-display font-black text-slate-800 mb-1">{{ $clientsCount }}</p>
                <a href="{{ route('admin.clients.index') }}" class="text-xs font-semibold text-purple-600 hover:text-purple-700 flex items-center gap-1">
                    Ver clientes <i class="fa-solid fa-chevron-right text-[9px]"></i>
                </a>
                <div class="absolute bottom-0 right-0 w-16 h-16 rounded-full opacity-5 bg-purple-600" style="transform:translate(25%,25%)"></div>
            </div>

            {{-- KPI 4: Categories --}}
            <div class="kpi-card hover:border-amber-300">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Categorías</span>
                    <div class="kpi-icon bg-amber-50 text-amber-600">
                        <i class="fa-solid fa-layer-group"></i>
                    </div>
                </div>
                <p class="text-3xl font-display font-black text-slate-800 mb-1">{{ $categoriesCount }}</p>
                <a href="{{ route('admin.categories.index') }}" class="text-xs font-semibold text-amber-600 hover:text-amber-700 flex items-center gap-1">
                    Gestionar <i class="fa-solid fa-chevron-right text-[9px]"></i>
                </a>
                <div class="absolute bottom-0 right-0 w-16 h-16 rounded-full opacity-5 bg-amber-500" style="transform:translate(25%,25%)"></div>
            </div>
        </div>

        {{-- Chart + Recent Orders --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

            {{-- Chart --}}
            <div class="lg:col-span-2 soft-card p-5 flex flex-col">
                <div class="flex items-center justify-between mb-5">
                    <div>
                        <h3 class="font-bold text-slate-800 text-sm">Visitas e Interacciones</h3>
                        <p class="text-xs text-slate-400">Estimado semanal de consultas de clientes</p>
                    </div>
                    <div class="flex items-center gap-4 text-xs font-medium">
                        <div class="flex items-center gap-1.5">
                            <span class="w-2.5 h-2.5 rounded-full bg-blue-600 block"></span>
                            <span class="text-slate-500">Visitas</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 block"></span>
                            <span class="text-slate-500">WhatsApp</span>
                        </div>
                    </div>
                </div>
                <div class="flex-1 min-h-[240px] relative">
                    <canvas id="salesChart"></canvas>
                </div>
            </div>

            {{-- Recent Orders --}}
            <div class="soft-card p-5 flex flex-col">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-bold text-slate-800 text-sm flex items-center gap-2">
                        <i class="fa-brands fa-whatsapp text-green-500 text-base"></i>
                        Pedidos Recientes
                    </h3>
                    <a href="{{ route('admin.orders.index') }}" class="text-xs text-blue-600 hover:text-blue-700 font-bold">Ver todos →</a>
                </div>

                <div class="flex flex-col gap-3 flex-1">
                    @forelse($recentOrders as $rOrder)
                    <div class="flex items-center justify-between p-2.5 rounded-xl hover:bg-slate-50 transition-colors border border-transparent hover:border-slate-100">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full bg-blue-50 text-blue-700 font-bold flex items-center justify-center text-sm border border-blue-100 flex-shrink-0">
                                {{ strtoupper(substr($rOrder->client->name ?? 'C', 0, 1)) }}
                            </div>
                            <div>
                                <p class="text-xs font-bold text-slate-800 leading-none">{{ $rOrder->client->name ?? 'Cliente Web' }}</p>
                                <p class="text-[11px] text-slate-500 mt-0.5">S/ {{ number_format($rOrder->total_amount, 2) }}</p>
                            </div>
                        </div>
                        <div class="flex flex-col items-end gap-1">
                            <span class="text-[9px] text-slate-400">{{ $rOrder->created_at->diffForHumans() }}</span>
                            <span class="badge {{ match($rOrder->status) {
                                'Pendiente'  => 'badge-amber',
                                'Contactado' => 'badge-blue',
                                'Completado' => 'badge-green',
                                'Cancelado'  => 'badge-red',
                                default      => 'badge-gray',
                            } }}">{{ $rOrder->status }}</span>
                        </div>
                    </div>
                    @empty
                    <div class="flex-1 flex flex-col items-center justify-center text-center py-8 text-slate-400">
                        <i class="fa-solid fa-inbox text-3xl mb-2 text-slate-200"></i>
                        <p class="text-sm font-medium">No hay pedidos registrados aún</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Recent Products Table --}}
        <div class="soft-card overflow-hidden">
            <div class="p-5 flex items-center justify-between border-b border-slate-100">
                <div>
                    <h3 class="font-bold text-slate-800 text-sm flex items-center gap-2">
                        <i class="fa-solid fa-laptop text-blue-600"></i>
                        Productos Recientes
                    </h3>
                    <p class="text-xs text-slate-400">Últimos artículos registrados en la tienda</p>
                </div>
                <a href="{{ route('admin.products.index') }}" class="text-xs text-blue-600 hover:text-blue-700 font-bold">Ver todos los productos →</a>
            </div>

            <div class="overflow-x-auto">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Categoría / Marca</th>
                            <th>Precio</th>
                            <th>Stock</th>
                            <th>Estado</th>
                            <th class="text-right">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentProds as $prod)
                        <tr>
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-slate-50 rounded-lg border border-slate-200 flex items-center justify-center flex-shrink-0 overflow-hidden">
                                        @if($prod->image)
                                            <img src="{{ asset('storage/' . $prod->image) }}" class="w-full h-full object-contain p-1">
                                        @else
                                            <i class="fa-solid fa-laptop text-slate-300 text-base"></i>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-800 text-xs line-clamp-1">{{ $prod->name }}</p>
                                        <p class="text-[10px] text-slate-400">{{ $prod->code }}</p>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <p class="font-semibold text-slate-700 text-xs">{{ $prod->brand->name ?? '—' }}</p>
                                <p class="text-[10px] text-slate-400">{{ $prod->category->name ?? '—' }}</p>
                            </td>
                            <td class="font-bold text-slate-800 text-sm">S/ {{ number_format($prod->price, 2) }}</td>
                            <td>
                                <span class="badge {{ $prod->stock > 0 ? 'badge-gray' : 'badge-red' }}">
                                    {{ $prod->stock }} un.
                                </span>
                            </td>
                            <td>
                                @if($prod->status)
                                    <span class="badge badge-green">Publicado</span>
                                @else
                                    <span class="badge badge-gray">Oculto</span>
                                @endif
                            </td>
                            <td class="text-right">
                                <a href="{{ route('admin.products.edit', $prod) }}"
                                   class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-blue-600 hover:bg-blue-50 transition-colors border border-transparent hover:border-blue-200"
                                   title="Editar">
                                    <i class="fa-solid fa-pen-to-square text-sm"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-8 text-slate-400 text-sm">
                                <i class="fa-solid fa-box-open text-3xl block mb-2 text-slate-200"></i>
                                No hay productos en catálogo
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    {{-- ══ RIGHT SIDEBAR ══ --}}
    <div class="w-full xl:w-72 flex-shrink-0 flex flex-col gap-5">

        {{-- Quick Actions --}}
        <div class="soft-card p-5">
            <h3 class="font-bold text-slate-800 text-sm mb-4 flex items-center gap-2">
                <i class="fa-solid fa-bolt text-amber-500"></i>
                Acciones Rápidas
            </h3>
            <div class="flex flex-col gap-2">
                <a href="{{ route('admin.products.create') }}"
                   class="flex items-center gap-3 p-3 rounded-xl bg-blue-50 text-blue-700 hover:bg-blue-100 font-semibold text-xs transition-colors border border-blue-100">
                    <div class="w-7 h-7 rounded-lg bg-blue-600 flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid fa-plus text-white text-xs"></i>
                    </div>
                    Registrar Nuevo Producto
                </a>
                <a href="{{ route('admin.imports.index') }}"
                   class="flex items-center gap-3 p-3 rounded-xl bg-slate-50 text-slate-700 hover:bg-green-50 hover:text-green-700 font-semibold text-xs transition-colors border border-slate-200 hover:border-green-200">
                    <div class="w-7 h-7 rounded-lg bg-green-50 flex items-center justify-center flex-shrink-0 border border-green-100">
                        <i class="fa-solid fa-file-excel text-green-600 text-xs"></i>
                    </div>
                    Importar Catálogo Excel
                </a>
                <a href="{{ route('admin.pdf.index') }}"
                   class="flex items-center gap-3 p-3 rounded-xl bg-slate-50 text-slate-700 hover:bg-red-50 hover:text-red-700 font-semibold text-xs transition-colors border border-slate-200 hover:border-red-200">
                    <div class="w-7 h-7 rounded-lg bg-red-50 flex items-center justify-center flex-shrink-0 border border-red-100">
                        <i class="fa-solid fa-file-pdf text-red-600 text-xs"></i>
                    </div>
                    Generar PDF para Clientes
                </a>
                <a href="{{ route('home') }}" target="_blank"
                   class="flex items-center gap-3 p-3 rounded-xl bg-slate-50 text-slate-700 hover:bg-blue-50 hover:text-blue-700 font-semibold text-xs transition-colors border border-slate-200 hover:border-blue-200">
                    <div class="w-7 h-7 rounded-lg bg-blue-50 flex items-center justify-center flex-shrink-0 border border-blue-100">
                        <i class="fa-solid fa-store text-blue-600 text-xs"></i>
                    </div>
                    Ver Catálogo Público
                    <i class="fa-solid fa-external-link text-[9px] ml-auto text-slate-400"></i>
                </a>
            </div>
        </div>

        {{-- Store Summary --}}
        <div class="soft-card p-5">
            <h3 class="font-bold text-slate-800 text-sm mb-4 flex items-center gap-2">
                <i class="fa-solid fa-circle-info text-blue-500"></i>
                Resumen de la Tienda
            </h3>
            <div class="space-y-0 text-xs">
                @foreach([
                    ['label' => 'Nombre de la Tienda',   'value' => $storeName],
                    ['label' => 'WhatsApp Comercial',     'value' => $wpNumber],
                    ['label' => 'Marcas Registradas',     'value' => \App\Models\Brand::count()],
                    ['label' => 'Modelos de Equipos',     'value' => \App\Models\DeviceModel::count()],
                ] as $item)
                <div class="flex justify-between items-center py-2.5 border-b border-slate-100 last:border-0">
                    <span class="text-slate-500">{{ $item['label'] }}</span>
                    <span class="font-bold text-slate-800 text-right max-w-[140px] truncate" title="{{ $item['value'] }}">{{ $item['value'] }}</span>
                </div>
                @endforeach
            </div>
            <div class="mt-4 pt-3 border-t border-slate-100">
                <a href="{{ route('admin.settings.index') }}"
                   class="flex items-center justify-center gap-2 text-xs text-blue-600 hover:text-blue-800 font-bold transition-colors py-2 rounded-lg hover:bg-blue-50">
                    <i class="fa-solid fa-gear"></i> Ajustes de la Tienda
                </a>
            </div>
        </div>

        {{-- Status Overview --}}
        <div class="soft-card p-5">
            <h3 class="font-bold text-slate-800 text-sm mb-4 flex items-center gap-2">
                <i class="fa-solid fa-signal text-green-500"></i>
                Estado del Sistema
            </h3>
            <div class="space-y-3">
                @foreach([
                    ['label' => 'Tienda Online',     'ok' => true,  'val' => 'Activa'],
                    ['label' => 'Base de Datos',     'ok' => true,  'val' => 'Conectada'],
                    ['label' => 'Almacenamiento',    'ok' => true,  'val' => 'Disponible'],
                    ['label' => 'WhatsApp API',      'ok' => (bool)$wpNumber, 'val' => $wpNumber ? 'Configurado' : 'Sin config'],
                ] as $s)
                <div class="flex items-center justify-between text-xs">
                    <span class="text-slate-600">{{ $s['label'] }}</span>
                    <div class="flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full {{ $s['ok'] ? 'bg-green-500' : 'bg-red-400' }} block"></span>
                        <span class="{{ $s['ok'] ? 'text-green-600' : 'text-red-500' }} font-semibold">{{ $s['val'] }}</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

    </div>

</div>
@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById('salesChart');
        if (!ctx) return;

        const chartCtx = ctx.getContext('2d');

        let gradientBlue = chartCtx.createLinearGradient(0, 0, 0, 260);
        gradientBlue.addColorStop(0, 'rgba(37, 99, 235, 0.18)');
        gradientBlue.addColorStop(1, 'rgba(37, 99, 235, 0.01)');

        let gradientGreen = chartCtx.createLinearGradient(0, 0, 0, 260);
        gradientGreen.addColorStop(0, 'rgba(16, 185, 129, 0.15)');
        gradientGreen.addColorStop(1, 'rgba(16, 185, 129, 0.01)');

        new Chart(chartCtx, {
            type: 'line',
            data: {
                labels: ['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'],
                datasets: [
                    {
                        label: 'Visitas',
                        data: [120, 190, 150, 220, 280, 340, 290],
                        borderColor: '#2563eb',
                        backgroundColor: gradientBlue,
                        borderWidth: 2.5,
                        pointBackgroundColor: '#ffffff',
                        pointBorderColor: '#2563eb',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        fill: true,
                        tension: 0.35
                    },
                    {
                        label: 'Pedidos WhatsApp',
                        data: [15, 25, 20, 32, 45, 52, 40],
                        borderColor: '#10b981',
                        backgroundColor: gradientGreen,
                        borderWidth: 2,
                        borderDash: [4, 4],
                        pointBackgroundColor: '#ffffff',
                        pointBorderColor: '#10b981',
                        pointBorderWidth: 2,
                        pointRadius: 3,
                        fill: true,
                        tension: 0.35
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1e293b',
                        titleColor: '#f8fafc',
                        bodyColor: '#cbd5e1',
                        padding: 10,
                        cornerRadius: 8,
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: '#f1f5f9', drawBorder: false },
                        ticks: { color: '#94a3b8', font: { size: 11 } }
                    },
                    x: {
                        grid: { display: false, drawBorder: false },
                        ticks: { color: '#94a3b8', font: { size: 11 } }
                    }
                }
            }
        });
    });
</script>
@endpush
