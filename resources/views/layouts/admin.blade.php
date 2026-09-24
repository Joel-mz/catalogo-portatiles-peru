<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('header_title', 'Panel') — {{ \App\Models\Setting::where('key', 'store_name')->value('value') ?? 'MPC Antigravity' }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Outfit:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        display: ['Outfit', 'sans-serif'],
                    },
                }
            }
        }
    </script>
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        [x-cloak] { display: none !important; }

        * { box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            background: #f4f6f9;
            color: #1e293b;
        }

        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb { background: #2563eb; border-radius: 4px; }

        /* ─── Sidebar ─── */
        .admin-sidebar {
            background: #0f172a;
            border-right: 1px solid #1e293b;
            width: 240px;
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
            height: 100vh;
            position: relative;
            z-index: 20;
        }

        .sidebar-logo {
            height: 64px;
            display: flex;
            align-items: center;
            padding: 0 20px;
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }

        .sidebar-nav {
            flex: 1;
            overflow-y: auto;
            padding: 12px 8px;
        }
        .sidebar-nav::-webkit-scrollbar { width: 3px; }
        .sidebar-nav::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 14px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 500;
            color: #94a3b8;
            transition: all 0.18s;
            margin-bottom: 2px;
            cursor: pointer;
            text-decoration: none;
        }
        .nav-item:hover {
            background: rgba(255,255,255,0.06);
            color: #e2e8f0;
        }
        .nav-item.active {
            background: #2563eb;
            color: #fff;
            box-shadow: 0 4px 14px rgba(37,99,235,0.35);
        }
        .nav-item.active .nav-icon { color: #fff; }
        .nav-item:hover .nav-icon { color: #60a5fa; }
        .nav-item.active:hover .nav-icon { color: #fff; }

        .nav-icon {
            width: 18px;
            text-align: center;
            font-size: 14px;
            flex-shrink: 0;
            color: #475569;
            transition: color 0.18s;
        }

        .sidebar-section-label {
            font-size: 9px;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: #334155;
            padding: 14px 14px 6px;
        }

        /* ─── Top Header ─── */
        .admin-topbar {
            height: 64px;
            background: #ffffff;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            padding: 0 24px;
            justify-content: space-between;
            flex-shrink: 0;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        }

        .topbar-search {
            position: relative;
            width: 360px;
        }
        .topbar-search input {
            width: 100%;
            height: 40px;
            background: #f8fafc;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            padding: 0 14px 0 40px;
            font-size: 13px;
            color: #374151;
            outline: none;
            transition: border-color 0.2s, background 0.2s;
        }
        .topbar-search input:focus {
            border-color: #2563eb;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(37,99,235,0.08);
        }
        .topbar-search .search-icon {
            position: absolute;
            left: 13px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 12px;
            pointer-events: none;
        }

        /* ─── Cards ─── */
        .soft-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04), 0 1px 2px rgba(0,0,0,0.03);
        }
        .soft-card:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.07);
        }

        /* ─── Form Inputs ─── */
        .form-input {
            width: 100%;
            border-radius: 8px;
            border: 1.5px solid #e2e8f0;
            font-size: 13px;
            padding: 8px 12px;
            color: #374151;
            background: #fff;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .form-input:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37,99,235,0.1);
        }
        select.form-input { padding-right: 32px; }

        /* ─── Buttons ─── */
        .btn-primary {
            background: #2563eb;
            color: #fff;
            border-radius: 8px;
            font-weight: 700;
            font-size: 13px;
            padding: 9px 20px;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 7px;
            border: none;
            cursor: pointer;
        }
        .btn-primary:hover {
            background: #1d4ed8;
            box-shadow: 0 4px 14px rgba(37,99,235,0.35);
        }

        .btn-secondary {
            background: #f8fafc;
            color: #374151;
            border: 1.5px solid #e2e8f0;
            border-radius: 8px;
            font-weight: 600;
            font-size: 13px;
            padding: 8px 18px;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 7px;
            cursor: pointer;
        }
        .btn-secondary:hover {
            border-color: #2563eb;
            color: #2563eb;
            background: #eff6ff;
        }

        .btn-danger {
            background: #fef2f2;
            color: #dc2626;
            border: 1.5px solid #fecaca;
            border-radius: 8px;
            font-weight: 600;
            font-size: 13px;
            padding: 8px 18px;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 7px;
            cursor: pointer;
        }
        .btn-danger:hover {
            background: #dc2626;
            color: #fff;
            border-color: #dc2626;
        }

        /* ─── Tables ─── */
        .admin-table { width: 100%; border-collapse: collapse; }
        .admin-table thead tr {
            background: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
        }
        .admin-table thead th {
            padding: 10px 12px;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: #94a3b8;
            text-align: left;
        }
        .admin-table tbody tr {
            border-bottom: 1px solid #f1f5f9;
            transition: background 0.15s;
        }
        .admin-table tbody tr:hover { background: #f8fafc; }
        .admin-table tbody td { padding: 12px; font-size: 13px; color: #374151; }
        .admin-table tbody tr:last-child { border-bottom: none; }

        /* ─── Badges ─── */
        .badge { display: inline-flex; align-items: center; font-size: 10px; font-weight: 700; padding: 2px 8px; border-radius: 999px; }
        .badge-green { background: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; }
        .badge-blue  { background: #dbeafe; color: #1e40af; border: 1px solid #bfdbfe; }
        .badge-amber { background: #fef3c7; color: #92400e; border: 1px solid #fde68a; }
        .badge-red   { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
        .badge-gray  { background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; }

        /* ─── Page Header ─── */
        .page-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            margin-bottom: 20px;
            gap: 12px;
        }
        .page-title {
            font-family: 'Outfit', sans-serif;
            font-size: 22px;
            font-weight: 800;
            color: #0f172a;
            line-height: 1.2;
        }
        .page-subtitle {
            font-size: 12px;
            color: #94a3b8;
            margin-top: 2px;
        }

        /* ─── KPI Cards ─── */
        .kpi-card {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            padding: 20px;
            transition: all 0.2s;
            position: relative;
            overflow: hidden;
        }
        .kpi-card:hover {
            box-shadow: 0 6px 20px rgba(0,0,0,0.07);
            transform: translateY(-1px);
        }
        .kpi-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            flex-shrink: 0;
        }

        /* ─── Misc ─── */
        .line-clamp-1 { display:-webkit-box;-webkit-line-clamp:1;-webkit-box-orient:vertical;overflow:hidden; }
        .line-clamp-2 { display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden; }
        .hide-scrollbar::-webkit-scrollbar { display:none; }
        .hide-scrollbar { -ms-overflow-style:none; scrollbar-width:none; }

        /* ─── Breadcrumb ─── */
        .breadcrumb {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 11px;
            color: #94a3b8;
            margin-bottom: 4px;
        }
        .breadcrumb a { color: #2563eb; font-weight: 600; transition: opacity 0.15s; }
        .breadcrumb a:hover { opacity: 0.8; }

        /* Animations */
        @keyframes fadeIn { from { opacity: 0; transform: translateY(6px); } to { opacity: 1; transform: translateY(0); } }
        .fade-in { animation: fadeIn 0.25s ease-out; }

        :root { --admin-blue: #3157dc; --admin-violet: #653fe0; --admin-navy: #0d1b3b; }
        body { background: #f4f6fa; }
        .admin-sidebar { background: linear-gradient(180deg, #0b1730 0%, #142650 100%); border-right-color: #213765; }
        .sidebar-logo { border-bottom-color: rgba(255,255,255,.1); }
        .nav-item { border-radius: 11px; }
        .nav-item.active { background: linear-gradient(100deg, var(--admin-blue), var(--admin-violet)); box-shadow: 0 8px 20px rgba(49,87,220,.3); }
        .nav-item:hover { background: rgba(255,255,255,.09); }
        .sidebar-section-label { color: #8192bd; letter-spacing: .13em; }
        .admin-topbar { min-height: 68px; background: rgba(255,255,255,.97); border-bottom-color: #e4e9f2; box-shadow: 0 3px 16px rgba(22,38,78,.045); }
        .topbar-search input { border-radius: 11px; background: #f5f7fb; border-color: #e5e9f2; }
        .topbar-search input:focus { border-color: #7a67e8; box-shadow: 0 0 0 3px rgba(101,63,224,.1); }
        .soft-card, .kpi-card { border-color: #e4e9f2; border-radius: 16px; box-shadow: 0 5px 18px rgba(25,45,91,.045); }
        .soft-card:hover, .kpi-card:hover { border-color: #d8ddf2; box-shadow: 0 12px 28px rgba(25,45,91,.09); }
        .form-input { border-radius: 10px; border-color: #dfe5ef; }
        .form-input:focus { border-color: #7259e6; box-shadow: 0 0 0 3px rgba(101,63,224,.12); }
        .btn-primary { background: linear-gradient(110deg, var(--admin-blue), var(--admin-violet)); box-shadow: 0 5px 14px rgba(49,87,220,.2); }
        .btn-primary:hover { background: linear-gradient(110deg, #2447bf, #5431c5); }
        .btn-secondary { background: #f6f8fc; border-color: #e1e6f0; }
        .btn-secondary:hover { background: #f0edff; border-color: #cfc5fb; color: #583bc7; }
        .admin-table thead tr { background: #f5f7fc; border-bottom-color: #e4e9f2; }
        .admin-table thead th { color: #71809d; }
        .admin-table tbody tr { border-bottom-color: #edf0f6; }
        .admin-table tbody tr:hover { background: #f8f9fe; }
        .page-title { color: #142143; }
        .breadcrumb a { color: #4b55d4; }
        main.fade-in { background: #f4f6fa; }

        @media (max-width: 767px) {
            body.antialiased { display: block; overflow: auto; }
            .admin-sidebar { width: 100%; height: auto; position: relative; }
            .sidebar-logo { height: 58px; padding: 0 14px; }
            .sidebar-nav { display: flex; align-items: center; gap: 4px; overflow-x: auto; padding: 8px 10px; }
            .sidebar-section-label { display: none; }
            .nav-item { flex: 0 0 auto; margin: 0; padding: 9px 11px; font-size: 11px; }
            .admin-sidebar > div:last-child { padding: 7px 10px; }
            .admin-sidebar > div:last-child button { width: auto; padding: 7px 10px; }
            .admin-sidebar > div:last-child button span { display: none; }
            .admin-sidebar > div:last-child { position: absolute; top: 8px; right: 8px; }
            .admin-sidebar > div:last-child form button { font-size: 0; }
            .admin-sidebar > div:last-child form button i { font-size: 14px; }
            .admin-sidebar + div.flex-1 { height: auto; min-height: calc(100vh - 112px); overflow: visible; }
            .admin-topbar { height: auto; min-height: 58px; padding: 10px 14px; }
            main.fade-in { overflow: visible; padding: 16px 12px; }
            .page-header { flex-wrap: wrap; }
        }
    </style>
</head>
<body class="antialiased min-h-screen flex overflow-hidden">

    <!-- ════════════════════════════════════
         SIDEBAR
    ════════════════════════════════════ -->
    <aside class="admin-sidebar">

        <!-- Logo -->
        <div class="sidebar-logo">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center font-display font-black text-white text-base italic flex-shrink-0"
                     style="background: linear-gradient(135deg, #2563eb, #7c3aed);">M</div>
                <div class="leading-tight">
                    <div class="font-display font-black text-white text-sm tracking-wide">MPC <span class="text-blue-400">ANTIGRAVITY</span></div>
                    <div class="text-[9px] text-slate-500 tracking-widest font-mono">Panel de Administración</div>
                </div>
            </a>
        </div>

        <!-- Navigation -->
        <nav class="sidebar-nav">

            <p class="sidebar-section-label">Principal</p>

            <a href="{{ route('dashboard') }}"
               class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="fa-solid fa-house-chimney nav-icon"></i>
                <span>Inicio</span>
            </a>

            <a href="{{ route('admin.products.index') }}"
               class="nav-item {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                <i class="fa-solid fa-box-open nav-icon"></i>
                <span>Productos</span>
            </a>

            <a href="{{ route('admin.categories.index') }}"
               class="nav-item {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                <i class="fa-solid fa-layer-group nav-icon"></i>
                <span>Categorías</span>
            </a>

            <a href="{{ route('admin.brands.index') }}"
               class="nav-item {{ request()->routeIs('admin.brands.*') ? 'active' : '' }}">
                <i class="fa-solid fa-tags nav-icon"></i>
                <span>Marcas</span>
            </a>

            <p class="sidebar-section-label">Ventas</p>

            <a href="{{ route('admin.clients.index') }}"
               class="nav-item {{ request()->routeIs('admin.clients.*') ? 'active' : '' }}">
                <i class="fa-solid fa-users nav-icon"></i>
                <span>Clientes</span>
            </a>

            <a href="{{ route('admin.orders.index') }}"
               class="nav-item {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                <i class="fa-brands fa-whatsapp nav-icon" style="{{ request()->routeIs('admin.orders.*') ? '' : 'color:#22c55e' }}"></i>
                <span>Ventas (WhatsApp)</span>
                @php $pendingOrders = \App\Models\Order::where('status', 'Pendiente')->count(); @endphp
                @if($pendingOrders > 0)
                <span class="ml-auto text-[9px] font-black bg-red-500 text-white px-1.5 py-0.5 rounded-full">{{ $pendingOrders }}</span>
                @endif
            </a>

            <p class="sidebar-section-label">Marketing</p>

            <a href="{{ route('admin.sliders.index') }}"
               class="nav-item {{ request()->routeIs('admin.sliders.*') ? 'active' : '' }}">
                <i class="fa-solid fa-image nav-icon"></i>
                <span>Publicidad / Slider</span>
            </a>

            <p class="sidebar-section-label">Sistema</p>

            <a href="{{ route('admin.users.index') }}"
               class="nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <i class="fa-regular fa-user nav-icon"></i>
                <span>Usuarios</span>
            </a>

            <a href="{{ route('admin.settings.index') }}"
               class="nav-item {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                <i class="fa-solid fa-gear nav-icon"></i>
                <span>Configuración</span>
            </a>

            <a href="{{ route('admin.imports.index') }}"
               class="nav-item {{ request()->routeIs('admin.imports.*') ? 'active' : '' }}">
                <i class="fa-solid fa-file-excel nav-icon" style="{{ request()->routeIs('admin.imports.*') ? '' : 'color:#22c55e' }}"></i>
                <span>Importar Excel</span>
            </a>

            <a href="{{ route('admin.pdf.index') }}"
               class="nav-item {{ request()->routeIs('admin.pdf.*') ? 'active' : '' }}">
                <i class="fa-solid fa-file-pdf nav-icon" style="{{ request()->routeIs('admin.pdf.*') ? '' : 'color:#ef4444' }}"></i>
                <span>Generar PDF</span>
            </a>

            <!-- Divider -->
            <div style="border-top: 1px solid rgba(255,255,255,0.05); margin: 10px 6px;"></div>

            <a href="{{ route('home') }}" target="_blank"
               class="nav-item">
                <i class="fa-solid fa-store nav-icon" style="color:#60a5fa"></i>
                <span>Ver Tienda Pública</span>
                <i class="fa-solid fa-external-link text-[9px] ml-auto text-slate-600"></i>
            </a>
        </nav>

        <!-- Logout -->
        <div class="p-3" style="border-top: 1px solid rgba(255,255,255,0.05);">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="w-full flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium text-slate-500 hover:text-red-400 hover:bg-red-500/10 transition-all">
                    <i class="fa-solid fa-right-from-bracket w-4 text-center"></i>
                    <span>Cerrar Sesión</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- ════════════════════════════════════
         MAIN WRAPPER
    ════════════════════════════════════ -->
    <div class="flex-1 flex flex-col h-screen overflow-hidden" style="background:#f4f6f9;">

        <!-- Top Header -->
        <header class="admin-topbar">
            <div class="flex items-center gap-4 flex-1">
                <!-- Search -->
                <div class="topbar-search hidden md:block">
                    <i class="fa-solid fa-magnifying-glass search-icon"></i>
                    <input type="text" placeholder="Buscar en categorías, marcas, productos...">
                </div>

                <!-- Page breadcrumb (mobile) -->
                <div class="md:hidden">
                    <p class="font-display font-bold text-slate-800 text-sm">@yield('header_title', 'Panel')</p>
                </div>
            </div>

            <!-- Right Actions -->
            <div class="flex items-center gap-2">

                <!-- Notifications -->
                @php
                    $notificationOrders = \App\Models\Order::with('client')->where('status', 'Pendiente')->latest()->take(5)->get();
                    $pendingOrdersCount = \App\Models\Order::where('status', 'Pendiente')->count();
                @endphp
                <div class="relative">
                    <button type="button" data-notifications-toggle aria-expanded="false" aria-controls="admin-notifications" aria-label="Notificaciones: {{ $pendingOrdersCount }} pedidos pendientes" class="relative flex h-10 w-10 items-center justify-center rounded-xl border border-transparent text-slate-500 transition-all hover:border-blue-100 hover:bg-blue-50 hover:text-blue-600">
                        <i class="fa-regular fa-bell text-base"></i>
                        @if($pendingOrdersCount > 0)<span class="absolute -right-1 -top-1 flex h-5 min-w-5 items-center justify-center rounded-full border-2 border-white bg-rose-500 px-1 text-[9px] font-extrabold text-white">{{ $pendingOrdersCount > 99 ? '99+' : $pendingOrdersCount }}</span>@endif
                    </button>
                    <section id="admin-notifications" data-notifications-menu hidden class="absolute right-0 top-12 z-50 w-[min(90vw,360px)] overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-2xl shadow-slate-900/15" aria-label="Pedidos pendientes">
                        <div class="flex items-center justify-between border-b border-slate-100 px-4 py-3"><div><h2 class="text-xs font-extrabold text-slate-800">Notificaciones</h2><p class="mt-0.5 text-[10px] text-slate-500">Pedidos que requieren atención</p></div><span class="rounded-full bg-rose-50 px-2 py-1 text-[9px] font-bold text-rose-700">{{ $pendingOrdersCount }} pendientes</span></div>
                        <div class="max-h-72 overflow-y-auto">
                            @forelse($notificationOrders as $notificationOrder)
                                <a href="{{ route('admin.orders.show', $notificationOrder) }}" class="flex items-start gap-3 border-b border-slate-100 px-4 py-3 transition hover:bg-indigo-50/60"><span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600"><i class="fa-brands fa-whatsapp"></i></span><span class="min-w-0 flex-1"><span class="block truncate text-xs font-bold text-slate-800">Pedido #{{ $notificationOrder->id }} · {{ $notificationOrder->client->name ?? 'Cliente' }}</span><span class="mt-1 block text-[10px] text-slate-500">S/ {{ number_format((float) $notificationOrder->total_amount, 2) }} · {{ $notificationOrder->created_at->diffForHumans() }}</span></span><i class="fa-solid fa-chevron-right mt-2 text-[9px] text-slate-400"></i></a>
                            @empty
                                <div class="px-4 py-8 text-center"><i class="fa-regular fa-circle-check text-xl text-emerald-500"></i><p class="mt-2 text-xs font-semibold text-slate-600">No tienes pedidos pendientes</p></div>
                            @endforelse
                        </div>
                        <a href="{{ route('admin.orders.index') }}" class="block bg-slate-50 px-4 py-3 text-center text-[10px] font-extrabold text-indigo-700 hover:bg-indigo-50">Ver todos los pedidos</a>
                    </section>
                </div>

                <!-- Divider -->
                <div class="w-px h-8 bg-slate-200 mx-1"></div>

                <!-- User -->
                <div class="flex items-center gap-2.5 px-3 py-2 rounded-xl hover:bg-slate-50 transition-all cursor-pointer border border-transparent hover:border-slate-200">
                    <div class="w-8 h-8 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold text-sm shadow-sm flex-shrink-0">
                        {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
                    </div>
                    <div class="hidden md:flex flex-col text-left">
                        <span class="text-sm font-bold text-slate-800 leading-none">{{ Auth::user()->name ?? 'Administrador' }}</span>
                        <span class="text-[10px] text-slate-400 mt-0.5">Administrador</span>
                    </div>
                    <i class="fa-solid fa-chevron-down text-[10px] text-slate-400 hidden md:block"></i>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-1 overflow-y-auto p-6 fade-in">
            @yield('content')
        </main>
    </div>

    @stack('scripts')
    <script>
        (() => {
            const toggle = document.querySelector('[data-notifications-toggle]');
            const menu = document.querySelector('[data-notifications-menu]');
            if (!toggle || !menu) return;
            toggle.addEventListener('click', () => {
                const willOpen = menu.hidden;
                menu.hidden = !willOpen;
                toggle.setAttribute('aria-expanded', String(willOpen));
            });
            document.addEventListener('click', (event) => {
                if (!menu.hidden && !event.target.closest('[data-notifications-toggle]') && !menu.contains(event.target)) {
                    menu.hidden = true;
                    toggle.setAttribute('aria-expanded', 'false');
                }
            });
            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape') {
                    menu.hidden = true;
                    toggle.setAttribute('aria-expanded', 'false');
                }
            });
        })();
    </script>
</body>
</html>
