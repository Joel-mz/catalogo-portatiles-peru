<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
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
                    screens: {
                        'xs': '480px',
                    }
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
            min-height: 100vh;
            overflow-x: hidden;
        }

        ::-webkit-scrollbar { width: 5px; height: 5px; }
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
            z-index: 30;
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
            min-height: 64px;
            background: #ffffff;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            padding: 0 16px;
            justify-content: space-between;
            flex-shrink: 0;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
            position: relative;
            z-index: 20;
        }
        @media (min-width: 768px) {
            .admin-topbar { padding: 0 24px; }
        }

        .topbar-search {
            position: relative;
            width: 100%;
            max-width: 440px;
        }
        .topbar-search input {
            width: 100%;
            height: 40px;
            background: #f8fafc;
            border: 1.5px solid #e2e8f0;
            border-radius: 12px;
            padding: 0 44px 0 40px;
            font-size: 13px;
            color: #374151;
            outline: none;
            transition: border-color 0.2s, background 0.2s, box-shadow 0.2s;
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
            font-size: 13px;
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
            flex-wrap: wrap;
        }
        .page-title {
            font-family: 'Outfit', sans-serif;
            font-size: 20px;
            font-weight: 800;
            color: #0f172a;
            line-height: 1.2;
        }
        @media(min-width: 640px) {
            .page-title { font-size: 22px; }
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
            padding: 16px;
            transition: all 0.2s;
            position: relative;
            overflow: hidden;
        }
        @media (min-width: 640px) {
            .kpi-card { padding: 20px; }
        }
        .kpi-card:hover {
            box-shadow: 0 6px 20px rgba(0,0,0,0.07);
            transform: translateY(-1px);
        }
        .kpi-icon {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            flex-shrink: 0;
        }
        @media(min-width: 640px) {
            .kpi-icon { width: 44px; height: 44px; font-size: 18px; }
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

        /* Themes */
        @php
            $theme = $global_settings['system_theme'] ?? 'light';
        @endphp
        
        @if($theme === 'dark')
            :root { --admin-blue: #3b82f6; --admin-violet: #8b5cf6; --admin-navy: #0f172a; --bg-body: #0f172a; }
            body, main.fade-in { background: var(--bg-body) !important; color: #f1f5f9 !important; }
            .admin-sidebar { background: #1e293b; border-right-color: #334155; }
            .sidebar-logo { border-bottom-color: rgba(255,255,255,.05); }
            .nav-item { color: #94a3b8; }
            .nav-item.active { background: #3b82f6; color: #fff; box-shadow: none; }
            .nav-item:hover { background: rgba(255,255,255,.05); }
            .sidebar-section-label { color: #64748b; }
            .admin-topbar { background: #1e293b; border-bottom-color: #334155; color: #f1f5f9; }
            .topbar-search input { background: #0f172a; border-color: #334155; color: #f1f5f9; }
            .soft-card, .kpi-card, .bg-white { background: #1e293b !important; border-color: #334155 !important; color: #f1f5f9 !important; }
            .admin-table thead tr { background: #0f172a; border-bottom-color: #334155; }
            .admin-table tbody tr { border-bottom-color: #1e293b; }
            .admin-table tbody tr:hover { background: #334155; }
            .admin-table tbody td { color: #e2e8f0; }
            .page-title, .text-slate-800, .text-slate-700, .text-slate-900 { color: #f8fafc !important; }
            .text-slate-500, .text-slate-600 { color: #94a3b8 !important; }
            input.bg-transparent, textarea.bg-transparent, select.bg-transparent { border-color: #334155 !important; color: #f8fafc !important; }
            .bg-\[\#fdffec\]\/40 { background: #1e293b !important; border-color: #334155 !important; }
        @elseif($theme === 'indigo')
            :root { --admin-blue: #3157dc; --admin-violet: #653fe0; --admin-navy: #0d1b3b; --bg-body: #f4f6fa; }
            body, main.fade-in { background: var(--bg-body); color: #1e293b; }
            .admin-sidebar { background: linear-gradient(180deg, #0b1730 0%, #142650 100%); border-right-color: #213765; }
            .nav-item { color: #c7d2fe; }
            .nav-item .nav-icon { color: #a5b4fc; }
            .nav-item.active { background: linear-gradient(100deg, var(--admin-blue), var(--admin-violet)); box-shadow: 0 8px 20px rgba(49,87,220,.3); color: white; }
            .nav-item:hover { color: #ffffff; background: rgba(255,255,255,0.1); }
            .sidebar-section-label { color: #818cf8; }
            .admin-sidebar .text-slate-500 { color: #a5b4fc !important; }
            .admin-topbar { background: rgba(255,255,255,.97); border-bottom-color: #e4e9f2; }
            .btn-primary { background: linear-gradient(110deg, var(--admin-blue), var(--admin-violet)); color: white; }
        @elseif($theme === 'nature')
            :root { --admin-blue: #059669; --admin-violet: #10b981; --admin-navy: #064e3b; --bg-body: #f0fdf4; }
            body, main.fade-in { background: var(--bg-body); color: #064e3b; }
            .admin-sidebar { background: #064e3b; border-right-color: #065f46; }
            .nav-item { color: #a7f3d0; }
            .nav-item .nav-icon { color: #6ee7b7; }
            .nav-item.active { background: #059669; box-shadow: 0 8px 20px rgba(5,150,105,.3); color: white; }
            .nav-item:hover { color: #ffffff; background: rgba(255,255,255,0.1); }
            .sidebar-section-label { color: #34d399; }
            .admin-sidebar .text-slate-500 { color: #6ee7b7 !important; }
            .admin-topbar { background: #ffffff; border-bottom-color: #d1fae5; }
            .btn-primary { background: #059669; color: white; }
        @elseif($theme === 'ocean')
            :root { --admin-blue: #0284c7; --admin-violet: #0369a1; --admin-navy: #082f49; --bg-body: #f0f9ff; }
            body, main.fade-in { background: var(--bg-body); color: #082f49; }
            .admin-sidebar { background: linear-gradient(180deg, #082f49 0%, #0c4a6e 100%); border-right-color: #075985; }
            .nav-item { color: #bae6fd; }
            .nav-item .nav-icon { color: #7dd3fc; }
            .nav-item.active { background: #0284c7; box-shadow: 0 8px 20px rgba(2,132,199,.3); color: white; }
            .nav-item:hover { color: #ffffff; background: rgba(255,255,255,0.1); }
            .sidebar-section-label { color: #38bdf8; }
            .admin-sidebar .text-slate-500 { color: #7dd3fc !important; }
            .admin-topbar { background: #ffffff; border-bottom-color: #e0f2fe; }
            .btn-primary { background: #0284c7; color: white; }
        @elseif($theme === 'sunset')
            :root { --admin-blue: #ea580c; --admin-violet: #dc2626; --admin-navy: #431407; --bg-body: #fff7ed; }
            body, main.fade-in { background: var(--bg-body); color: #431407; }
            .admin-sidebar { background: linear-gradient(180deg, #431407 0%, #7c2d12 100%); border-right-color: #9a3412; }
            .nav-item { color: #fed7aa; }
            .nav-item .nav-icon { color: #fdba74; }
            .nav-item.active { background: linear-gradient(100deg, #ea580c, #dc2626); box-shadow: 0 8px 20px rgba(234,88,12,.3); color: white; }
            .nav-item:hover { color: #ffffff; background: rgba(255,255,255,0.1); }
            .sidebar-section-label { color: #fb923c; }
            .admin-sidebar .text-slate-500 { color: #fdba74 !important; }
            .admin-topbar { background: #ffffff; border-bottom-color: #ffedd5; }
            .btn-primary { background: #ea580c; color: white; }
        @elseif($theme === 'rose')
            :root { --admin-blue: #e11d48; --admin-violet: #be123c; --admin-navy: #4c0519; --bg-body: #fff1f2; }
            body, main.fade-in { background: var(--bg-body); color: #4c0519; }
            .admin-sidebar { background: #4c0519; border-right-color: #881337; }
            .nav-item { color: #fecdd3; }
            .nav-item .nav-icon { color: #fda4af; }
            .nav-item.active { background: #e11d48; box-shadow: 0 8px 20px rgba(225,29,72,.3); color: white; }
            .nav-item:hover { color: #ffffff; background: rgba(255,255,255,0.1); }
            .sidebar-section-label { color: #fb7185; }
            .admin-sidebar .text-slate-500 { color: #fda4af !important; }
            .admin-topbar { background: #ffffff; border-bottom-color: #ffe4e6; }
            .btn-primary { background: #e11d48; color: white; }
        @elseif($theme === 'monochrome')
            :root { --admin-blue: #475569; --admin-violet: #334155; --admin-navy: #0f172a; --bg-body: #f8fafc; }
            body, main.fade-in { background: var(--bg-body); color: #0f172a; }
            .admin-sidebar { background: #1e293b; border-right-color: #334155; }
            .nav-item { color: #cbd5e1; }
            .nav-item .nav-icon { color: #94a3b8; }
            .nav-item.active { background: #475569; box-shadow: 0 8px 20px rgba(71,85,105,.3); color: white; }
            .nav-item:hover { color: #ffffff; background: rgba(255,255,255,0.1); }
            .sidebar-section-label { color: #64748b; }
            .admin-sidebar .text-slate-500 { color: #94a3b8 !important; }
            .admin-topbar { background: #ffffff; border-bottom-color: #f1f5f9; }
            .btn-primary { background: #334155; color: white; }
        @elseif($theme === 'neon')
            :root { --admin-blue: #c026d3; --admin-violet: #a21caf; --admin-navy: #000000; --bg-body: #000000; }
            body, main.fade-in { background: var(--bg-body) !important; color: #fdf4ff !important; }
            .admin-sidebar { background: #000000; border-right: 1px solid #c026d3; }
            .sidebar-logo { border-bottom-color: #c026d3; }
            .nav-item { color: #f0abfc; }
            .nav-item.active { background: transparent; border: 1px solid #c026d3; color: #fdf4ff; box-shadow: 0 0 15px rgba(192,38,211,.5); }
            .admin-topbar { background: #000000; border-bottom: 1px solid #c026d3; color: #fdf4ff; }
            .soft-card, .kpi-card, .bg-white { background: #000000 !important; border: 1px solid #a21caf !important; color: #fdf4ff !important; box-shadow: 0 0 20px rgba(162,28,175,.15) !important; }
            .admin-table thead tr { background: #11001c; border-bottom: 1px solid #c026d3; }
            .admin-table tbody tr { border-bottom-color: #2e004f; }
            .admin-table tbody td { color: #fdf4ff; }
            .page-title, .text-slate-800, .text-slate-700, .text-slate-900 { color: #fdf4ff !important; text-shadow: 0 0 10px rgba(253,244,255,0.5); }
            .text-slate-500, .text-slate-600 { color: #f5d0fe !important; }
            input.bg-transparent, textarea.bg-transparent, select.bg-transparent { border-color: #c026d3 !important; color: #fdf4ff !important; }
            .bg-\[\#fdffec\]\/40 { background: #000000 !important; border-color: #c026d3 !important; }
        @elseif($theme === 'luxury')
            :root { --admin-blue: #d97706; --admin-violet: #b45309; --admin-navy: #18181b; --bg-body: #18181b; }
            body, main.fade-in { background: var(--bg-body) !important; color: #fde68a !important; }
            .admin-sidebar { background: linear-gradient(180deg, #18181b 0%, #27272a 100%); border-right-color: #d97706; }
            .sidebar-logo { border-bottom-color: rgba(217,119,6,.3); }
            .nav-item { color: #fcd34d; }
            .nav-item.active { background: linear-gradient(100deg, #d97706, #b45309); color: #fff; box-shadow: 0 4px 15px rgba(217,119,6,.4); }
            .admin-topbar { background: #18181b; border-bottom-color: #d97706; color: #fef3c7; }
            .soft-card, .kpi-card, .bg-white { background: #27272a !important; border-color: #d97706 !important; color: #fef3c7 !important; }
            .admin-table thead tr { background: #18181b; border-bottom-color: #d97706; color: #fcd34d;}
            .admin-table tbody tr { border-bottom-color: #3f3f46; }
            .admin-table tbody td { color: #fef3c7; }
            .page-title, .text-slate-800, .text-slate-700, .text-slate-900 { color: #fef3c7 !important; }
            .text-slate-500, .text-slate-600 { color: #fde68a !important; }
            input.bg-transparent, textarea.bg-transparent, select.bg-transparent { border-color: #d97706 !important; color: #fef3c7 !important; }
            .bg-\[\#fdffec\]\/40 { background: #27272a !important; border-color: #d97706 !important; }
        @else
            /* Default Light */
            :root { --bg-body: #f4f6fa; }
        @endif
    </style>
</head>
<body class="antialiased min-h-screen flex flex-col lg:flex-row overflow-x-hidden" 
      x-data="{ 
          sidebarOpen: true, 
          mobileSidebarOpen: false, 
          mobileSearchOpen: false 
      }"
      @keydown.escape.window="mobileSidebarOpen = false; mobileSearchOpen = false">

    @php
        $logo = \App\Models\Setting::where('key', 'store_logo')->value('value');
        $storeName = \App\Models\Setting::where('key', 'store_name')->value('value') ?? 'PORTÁTILES PERÚ';
    @endphp

    <!-- ════════════════════════════════════
         MOBILE / TABLET DRAWER OVERLAY
    ════════════════════════════════════ -->
    <div x-show="mobileSidebarOpen" 
         x-cloak 
         class="fixed inset-0 z-50 lg:hidden flex" 
         role="dialog" 
         aria-modal="true">
        <!-- Backdrop -->
        <div x-show="mobileSidebarOpen" 
             x-transition:enter="transition-opacity ease-linear duration-300" 
             x-transition:enter-start="opacity-0" 
             x-transition:enter-end="opacity-100" 
             x-transition:leave="transition-opacity ease-linear duration-300" 
             x-transition:leave-start="opacity-100" 
             x-transition:leave-end="opacity-0" 
             class="fixed inset-0 bg-slate-900/70 backdrop-blur-sm" 
             @click="mobileSidebarOpen = false"></div>

        <!-- Sliding Off-Canvas Drawer -->
        <div x-show="mobileSidebarOpen" 
             x-transition:enter="transition ease-in-out duration-300 transform" 
             x-transition:enter-start="-translate-x-full" 
             x-transition:enter-end="translate-x-0" 
             x-transition:leave="transition ease-in-out duration-300 transform" 
             x-transition:leave-start="translate-x-0" 
             x-transition:leave-end="-translate-x-full" 
             class="relative flex-1 flex flex-col max-w-xs w-full bg-[#0f172a] text-white shadow-2xl z-50 h-full">
            
            <!-- Drawer Header -->
            <div class="h-16 flex items-center justify-between px-5 border-b border-white/10">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 min-w-0" @click="mobileSidebarOpen = false">
                    @if($logo)
                        <img src="{{ Storage::url($logo) }}" alt="Logo" class="h-8 w-8 rounded-xl object-contain bg-white p-1 flex-shrink-0">
                    @else
                        <div class="w-8 h-8 rounded-xl flex items-center justify-center font-display font-black text-white text-sm italic flex-shrink-0"
                             style="background: linear-gradient(135deg, #2563eb, #7c3aed);">{{ substr($storeName, 0, 1) }}</div>
                    @endif
                    <div class="leading-tight overflow-hidden">
                        <div class="font-display font-black text-white text-sm tracking-wide truncate">{{ $storeName }}</div>
                        <div class="text-[9px] text-slate-400 tracking-widest font-mono">Panel Administrador</div>
                    </div>
                </a>
                <button type="button" @click="mobileSidebarOpen = false" class="text-slate-400 hover:text-white p-2 rounded-lg hover:bg-white/10 transition">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <!-- Drawer Scrollable Navigation -->
            <nav class="flex-1 overflow-y-auto p-3 space-y-1">
                <p class="sidebar-section-label">Principal</p>

                <a href="{{ route('dashboard') }}"
                   class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}" @click="mobileSidebarOpen = false">
                    <i class="fa-solid fa-house-chimney nav-icon"></i>
                    <span>Inicio</span>
                </a>

                <div x-data="{ expanded: {{ request()->routeIs('admin.products.*') || request()->routeIs('admin.categories.*') || request()->routeIs('admin.subcategories.*') || request()->routeIs('admin.brands.*') || request()->routeIs('admin.models.*') ? 'true' : 'false' }} }">
                    <button type="button" @click="expanded = !expanded" class="w-full nav-item {{ request()->routeIs('admin.products.*') || request()->routeIs('admin.categories.*') || request()->routeIs('admin.subcategories.*') || request()->routeIs('admin.brands.*') || request()->routeIs('admin.models.*') ? 'active' : '' }}">
                        <div class="flex items-center gap-2.5 flex-1">
                            <i class="fa-solid fa-box-open nav-icon"></i>
                            <span>Productos</span>
                        </div>
                        <i class="fa-solid fa-chevron-down text-[10px] transition-transform" :class="expanded ? 'rotate-180' : ''"></i>
                    </button>
                    <div x-show="expanded" class="pl-4 mt-1 space-y-0.5" x-cloak>
                        <a href="{{ route('admin.products.create') }}" class="nav-item {{ request()->routeIs('admin.products.create') ? 'bg-white/10 text-white font-bold' : '' }}" @click="mobileSidebarOpen = false">
                            <i class="fa-solid fa-pen-to-square nav-icon text-xs"></i>
                            <span>Registrar Producto</span>
                        </a>
                        <a href="{{ route('admin.products.index') }}" class="nav-item {{ request()->routeIs('admin.products.index') && !request()->routeIs('admin.products.create') ? 'bg-white/10 text-white font-bold' : '' }}" @click="mobileSidebarOpen = false">
                            <i class="fa-solid fa-list nav-icon text-xs"></i>
                            <span>Lista de Productos</span>
                        </a>
                        <a href="{{ route('admin.categories.index') }}" class="nav-item {{ request()->routeIs('admin.categories.*') ? 'bg-white/10 text-white font-bold' : '' }}" @click="mobileSidebarOpen = false">
                            <i class="fa-solid fa-layer-group nav-icon text-xs"></i>
                            <span>Categorías</span>
                        </a>
                        <a href="{{ route('admin.subcategories.index') }}" class="nav-item {{ request()->routeIs('admin.subcategories.*') ? 'bg-white/10 text-white font-bold' : '' }}" @click="mobileSidebarOpen = false">
                            <i class="fa-solid fa-sitemap nav-icon text-xs"></i>
                            <span>Subcategorías</span>
                        </a>
                        <a href="{{ route('admin.brands.index') }}" class="nav-item {{ request()->routeIs('admin.brands.*') ? 'bg-white/10 text-white font-bold' : '' }}" @click="mobileSidebarOpen = false">
                            <i class="fa-solid fa-tags nav-icon text-xs"></i>
                            <span>Marcas</span>
                        </a>
                        <a href="{{ route('admin.models.index') }}" class="nav-item {{ request()->routeIs('admin.models.*') ? 'bg-white/10 text-white font-bold' : '' }}" @click="mobileSidebarOpen = false">
                            <i class="fa-solid fa-microchip nav-icon text-xs"></i>
                            <span>Modelos</span>
                        </a>
                    </div>
                </div>

                <p class="sidebar-section-label">Ventas</p>

                <a href="{{ route('admin.clients.index') }}"
                   class="nav-item {{ request()->routeIs('admin.clients.*') ? 'active' : '' }}" @click="mobileSidebarOpen = false">
                    <i class="fa-solid fa-users nav-icon"></i>
                    <span>Clientes</span>
                </a>

                <a href="{{ route('admin.orders.index') }}"
                   class="nav-item {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}" @click="mobileSidebarOpen = false">
                    <i class="fa-brands fa-whatsapp nav-icon" style="{{ request()->routeIs('admin.orders.*') ? '' : 'color:#22c55e' }}"></i>
                    <span>Ventas (WhatsApp)</span>
                    @php $pendingOrdersMobile = \App\Models\Order::where('status', 'Pendiente')->count(); @endphp
                    @if($pendingOrdersMobile > 0)
                        <span class="ml-auto text-[9px] font-black bg-red-500 text-white px-1.5 py-0.5 rounded-full">{{ $pendingOrdersMobile }}</span>
                    @endif
                </a>

                <p class="sidebar-section-label">Marketing</p>

                <a href="{{ route('admin.publicidad.index') }}"
                   class="nav-item {{ request()->routeIs('admin.publicidad.*') ? 'active' : '' }}" @click="mobileSidebarOpen = false">
                    <i class="fa-solid fa-bullhorn nav-icon"></i>
                    <span>Publicidad</span>
                </a>

                <a href="{{ route('admin.sliders.index') }}"
                   class="nav-item {{ request()->routeIs('admin.sliders.*') ? 'active' : '' }}" @click="mobileSidebarOpen = false">
                    <i class="fa-solid fa-image nav-icon"></i>
                    <span>Sliders</span>
                </a>

                <a href="{{ route('admin.banners.index') }}"
                   class="nav-item {{ request()->routeIs('admin.banners.*') ? 'active' : '' }}" @click="mobileSidebarOpen = false">
                    <i class="fa-solid fa-rectangle-ad nav-icon"></i>
                    <span>Banners</span>
                </a>

                <p class="sidebar-section-label">Sistema</p>

                <a href="{{ route('admin.users.index') }}"
                   class="nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" @click="mobileSidebarOpen = false">
                    <i class="fa-regular fa-user nav-icon"></i>
                    <span>Usuarios</span>
                </a>

                <a href="{{ route('admin.settings.index') }}"
                   class="nav-item {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}" @click="mobileSidebarOpen = false">
                    <i class="fa-solid fa-gear nav-icon"></i>
                    <span>Configuración</span>
                </a>

                <a href="{{ route('admin.backups.index') }}"
                   class="nav-item {{ request()->routeIs('admin.backups.*') ? 'active' : '' }}" @click="mobileSidebarOpen = false">
                    <i class="fa-solid fa-database nav-icon" style="{{ request()->routeIs('admin.backups.*') ? '' : 'color:#f59e0b' }}"></i>
                    <span>Backups / Historial</span>
                </a>

                <a href="{{ route('admin.imports.index') }}"
                   class="nav-item {{ request()->routeIs('admin.imports.*') ? 'active' : '' }}" @click="mobileSidebarOpen = false">
                    <i class="fa-solid fa-file-excel nav-icon" style="{{ request()->routeIs('admin.imports.*') ? '' : 'color:#22c55e' }}"></i>
                    <span>Importar Excel</span>
                </a>

                <a href="{{ route('admin.pdf.index') }}"
                   class="nav-item {{ request()->routeIs('admin.pdf.*') ? 'active' : '' }}" @click="mobileSidebarOpen = false">
                    <i class="fa-solid fa-file-pdf nav-icon" style="{{ request()->routeIs('admin.pdf.*') ? '' : 'color:#ef4444' }}"></i>
                    <span>Generar PDF</span>
                </a>

                <div class="border-t border-white/10 my-2 pt-2"></div>

                <a href="{{ route('home') }}" target="_blank" class="nav-item">
                    <i class="fa-solid fa-store nav-icon" style="color:#60a5fa"></i>
                    <span>Ver Tienda Pública</span>
                    <i class="fa-solid fa-external-link text-[9px] ml-auto text-slate-500"></i>
                </a>
            </nav>

            <!-- Drawer Footer Logout -->
            <div class="p-3 border-t border-white/10">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium text-slate-400 hover:text-red-400 hover:bg-red-500/10 transition-all">
                        <i class="fa-solid fa-right-from-bracket w-4 text-center"></i>
                        <span>Cerrar Sesión</span>
                    </button>
                </form>
            </div>
        </div>
    </div>


    <!-- ════════════════════════════════════
         DESKTOP SIDEBAR (>= 1024px)
    ════════════════════════════════════ -->
    <aside class="admin-sidebar hidden lg:flex transition-all duration-300" :style="sidebarOpen ? 'width: 240px' : 'width: 70px'">

        <!-- Logo -->
        <div class="sidebar-logo justify-center">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 w-full" :class="!sidebarOpen ? 'justify-center' : ''">
                @if($logo)
                    <img src="{{ Storage::url($logo) }}" alt="Logo" class="h-9 w-9 rounded-xl object-contain bg-white p-1 flex-shrink-0">
                @else
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center font-display font-black text-white text-base italic flex-shrink-0"
                         style="background: linear-gradient(135deg, #2563eb, #7c3aed);">{{ substr($storeName, 0, 1) }}</div>
                @endif
                <div class="leading-tight overflow-hidden" x-show="sidebarOpen" x-transition.opacity>
                    <div class="font-display font-black text-white text-sm tracking-wide truncate">{{ $storeName }}</div>
                    <div class="text-[9px] text-slate-500 tracking-widest font-mono">Panel de Administración</div>
                </div>
            </a>
        </div>

        <!-- Navigation -->
        <nav class="sidebar-nav">

            <p class="sidebar-section-label whitespace-nowrap overflow-hidden" x-show="sidebarOpen">Principal</p>

            <a href="{{ route('dashboard') }}"
               class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}" :class="!sidebarOpen ? 'justify-center px-0' : ''" title="Inicio">
                <i class="fa-solid fa-house-chimney nav-icon"></i>
                <span x-show="sidebarOpen" class="whitespace-nowrap">Inicio</span>
            </a>

            <div x-data="{ expanded: {{ request()->routeIs('admin.products.*') || request()->routeIs('admin.categories.*') || request()->routeIs('admin.subcategories.*') || request()->routeIs('admin.brands.*') || request()->routeIs('admin.models.*') ? 'true' : 'false' }} }">
                <button type="button" @click="expanded = !expanded;" class="w-full nav-item {{ request()->routeIs('admin.products.*') || request()->routeIs('admin.categories.*') || request()->routeIs('admin.subcategories.*') || request()->routeIs('admin.brands.*') || request()->routeIs('admin.models.*') ? 'active' : '' }}" :class="!sidebarOpen ? 'justify-center px-0' : ''" title="Productos">
                    <div class="flex items-center gap-2.5 flex-1" :class="!sidebarOpen ? 'justify-center' : ''">
                        <i class="fa-solid fa-box-open nav-icon"></i>
                        <span x-show="sidebarOpen" class="whitespace-nowrap">Productos</span>
                    </div>
                    <i x-show="sidebarOpen" class="fa-solid fa-chevron-down text-[10px] transition-transform" :class="expanded ? 'rotate-180' : ''"></i>
                </button>
                <div x-show="expanded && sidebarOpen" class="pl-3 mt-1 space-y-0.5" x-cloak>
                    <a href="{{ route('admin.products.create') }}" class="nav-item {{ request()->routeIs('admin.products.create') ? 'bg-white/10 text-white' : '' }}">
                        <i class="fa-solid fa-pen-to-square nav-icon text-xs"></i>
                        <span class="whitespace-nowrap">Registrar Producto</span>
                    </a>
                    <a href="{{ route('admin.products.index') }}" class="nav-item {{ request()->routeIs('admin.products.index') && !request()->routeIs('admin.products.create') ? 'bg-white/10 text-white' : '' }}">
                        <i class="fa-solid fa-list nav-icon text-xs"></i>
                        <span class="whitespace-nowrap">Lista de Productos</span>
                    </a>
                    <a href="{{ route('admin.categories.index') }}" class="nav-item {{ request()->routeIs('admin.categories.*') ? 'bg-white/10 text-white' : '' }}">
                        <i class="fa-solid fa-layer-group nav-icon text-xs"></i>
                        <span class="whitespace-nowrap">Categorías</span>
                    </a>
                    <a href="{{ route('admin.subcategories.index') }}" class="nav-item {{ request()->routeIs('admin.subcategories.*') ? 'bg-white/10 text-white' : '' }}">
                        <i class="fa-solid fa-sitemap nav-icon text-xs"></i>
                        <span class="whitespace-nowrap">Subcategorías</span>
                    </a>
                    <a href="{{ route('admin.brands.index') }}" class="nav-item {{ request()->routeIs('admin.brands.*') ? 'bg-white/10 text-white' : '' }}">
                        <i class="fa-solid fa-tags nav-icon text-xs"></i>
                        <span class="whitespace-nowrap">Marcas</span>
                    </a>
                    <a href="{{ route('admin.models.index') }}" class="nav-item {{ request()->routeIs('admin.models.*') ? 'bg-white/10 text-white' : '' }}">
                        <i class="fa-solid fa-microchip nav-icon text-xs"></i>
                        <span class="whitespace-nowrap">Modelos</span>
                    </a>
                </div>
            </div>

            <p class="sidebar-section-label whitespace-nowrap overflow-hidden" x-show="sidebarOpen">Ventas</p>

            <a href="{{ route('admin.clients.index') }}"
               class="nav-item {{ request()->routeIs('admin.clients.*') ? 'active' : '' }}" :class="!sidebarOpen ? 'justify-center px-0' : ''" title="Clientes">
                <i class="fa-solid fa-users nav-icon"></i>
                <span x-show="sidebarOpen" class="whitespace-nowrap">Clientes</span>
            </a>

            <a href="{{ route('admin.orders.index') }}"
               class="nav-item {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}" :class="!sidebarOpen ? 'justify-center px-0' : ''" title="Ventas">
                <i class="fa-brands fa-whatsapp nav-icon" style="{{ request()->routeIs('admin.orders.*') ? '' : 'color:#22c55e' }}"></i>
                <span x-show="sidebarOpen" class="whitespace-nowrap">Ventas (WhatsApp)</span>
                @php $pendingOrders = \App\Models\Order::where('status', 'Pendiente')->count(); @endphp
                @if($pendingOrders > 0)
                <span x-show="sidebarOpen" class="ml-auto text-[9px] font-black bg-red-500 text-white px-1.5 py-0.5 rounded-full">{{ $pendingOrders }}</span>
                @endif
            </a>

            <p class="sidebar-section-label whitespace-nowrap overflow-hidden" x-show="sidebarOpen">Marketing</p>

            <a href="{{ route('admin.publicidad.index') }}"
               class="nav-item {{ request()->routeIs('admin.publicidad.*') ? 'active' : '' }}" :class="!sidebarOpen ? 'justify-center px-0' : ''" title="Publicidad">
                <i class="fa-solid fa-bullhorn nav-icon"></i>
                <span x-show="sidebarOpen" class="whitespace-nowrap">Publicidad</span>
            </a>

            <a href="{{ route('admin.sliders.index') }}"
               class="nav-item {{ request()->routeIs('admin.sliders.*') ? 'active' : '' }}" :class="!sidebarOpen ? 'justify-center px-0' : ''" title="Sliders">
                <i class="fa-solid fa-image nav-icon"></i>
                <span x-show="sidebarOpen" class="whitespace-nowrap">Sliders</span>
            </a>

            <a href="{{ route('admin.banners.index') }}"
               class="nav-item {{ request()->routeIs('admin.banners.*') ? 'active' : '' }}" :class="!sidebarOpen ? 'justify-center px-0' : ''" title="Banners">
                <i class="fa-solid fa-rectangle-ad nav-icon"></i>
                <span x-show="sidebarOpen" class="whitespace-nowrap">Banners</span>
            </a>

            <p class="sidebar-section-label whitespace-nowrap overflow-hidden" x-show="sidebarOpen">Sistema</p>

            <a href="{{ route('admin.users.index') }}"
               class="nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" :class="!sidebarOpen ? 'justify-center px-0' : ''" title="Usuarios">
                <i class="fa-regular fa-user nav-icon"></i>
                <span x-show="sidebarOpen" class="whitespace-nowrap">Usuarios</span>
            </a>

            <a href="{{ route('admin.settings.index') }}"
               class="nav-item {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}" :class="!sidebarOpen ? 'justify-center px-0' : ''" title="Configuración">
                <i class="fa-solid fa-gear nav-icon"></i>
                <span x-show="sidebarOpen" class="whitespace-nowrap">Configuración</span>
            </a>

            <a href="{{ route('admin.backups.index') }}"
               class="nav-item {{ request()->routeIs('admin.backups.*') ? 'active' : '' }}" :class="!sidebarOpen ? 'justify-center px-0' : ''" title="Backups">
                <i class="fa-solid fa-database nav-icon" style="{{ request()->routeIs('admin.backups.*') ? '' : 'color:#f59e0b' }}"></i>
                <span x-show="sidebarOpen" class="whitespace-nowrap">Backups / Historial</span>
            </a>

            <a href="{{ route('admin.imports.index') }}"
               class="nav-item {{ request()->routeIs('admin.imports.*') ? 'active' : '' }}" :class="!sidebarOpen ? 'justify-center px-0' : ''" title="Importar Excel">
                <i class="fa-solid fa-file-excel nav-icon" style="{{ request()->routeIs('admin.imports.*') ? '' : 'color:#22c55e' }}"></i>
                <span x-show="sidebarOpen" class="whitespace-nowrap">Importar Excel</span>
            </a>

            <a href="{{ route('admin.pdf.index') }}"
               class="nav-item {{ request()->routeIs('admin.pdf.*') ? 'active' : '' }}" :class="!sidebarOpen ? 'justify-center px-0' : ''" title="Generar PDF">
                <i class="fa-solid fa-file-pdf nav-icon" style="{{ request()->routeIs('admin.pdf.*') ? '' : 'color:#ef4444' }}"></i>
                <span x-show="sidebarOpen" class="whitespace-nowrap">Generar PDF</span>
            </a>

            <!-- Divider -->
            <div style="border-top: 1px solid rgba(255,255,255,0.05); margin: 10px 6px;"></div>

            <a href="{{ route('home') }}" target="_blank"
               class="nav-item" :class="!sidebarOpen ? 'justify-center px-0' : ''" title="Ver Tienda">
                <i class="fa-solid fa-store nav-icon" style="color:#60a5fa"></i>
                <span x-show="sidebarOpen" class="whitespace-nowrap">Ver Tienda Pública</span>
                <i x-show="sidebarOpen" class="fa-solid fa-external-link text-[9px] ml-auto text-slate-600"></i>
            </a>
        </nav>

        <!-- Logout -->
        <div class="p-3" style="border-top: 1px solid rgba(255,255,255,0.05);">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="w-full flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium text-slate-500 hover:text-red-400 hover:bg-red-500/10 transition-all"
                        :class="!sidebarOpen ? 'justify-center px-0' : ''" title="Cerrar Sesión">
                    <i class="fa-solid fa-right-from-bracket w-4 text-center"></i>
                    <span x-show="sidebarOpen" class="whitespace-nowrap">Cerrar Sesión</span>
                </button>
            </form>
        </div>
    </aside>


    <!-- ════════════════════════════════════
         MAIN CONTENT AREA
    ════════════════════════════════════ -->
    <div class="flex-1 flex flex-col min-w-0 h-screen overflow-hidden" 
         x-data="globalScanner()" 
         style="background:var(--bg-body, transparent);">

        <!-- Top Header Bar -->
        <header class="admin-topbar">
            
            <!-- Left Header: Mobile Toggle, Desktop Toggle, and Branding/Search -->
            <div class="flex items-center gap-2 sm:gap-3 flex-1 min-w-0">
                
                <!-- Mobile Hamburger Button (< 1024px) -->
                <button type="button" 
                        @click="mobileSidebarOpen = true" 
                        class="lg:hidden flex items-center justify-center h-10 w-10 rounded-xl text-slate-600 hover:text-blue-600 hover:bg-slate-100 transition-colors flex-shrink-0"
                        aria-label="Abrir Menú">
                    <i class="fa-solid fa-bars text-lg"></i>
                </button>

                <!-- Desktop Sidebar Collapse Toggle (>= 1024px) -->
                <button type="button" 
                        @click="sidebarOpen = !sidebarOpen" 
                        class="hidden lg:flex items-center justify-center h-10 w-10 rounded-xl text-slate-500 hover:text-blue-600 hover:bg-slate-100 transition-colors flex-shrink-0"
                        title="Alternar Menú Lateral">
                    <i class="fa-solid fa-bars text-lg"></i>
                </button>

                <!-- Mobile Store Name / Header Title -->
                <div class="lg:hidden flex items-center gap-2 min-w-0 mr-1 sm:mr-2">
                    <div class="w-7 h-7 rounded-lg flex items-center justify-center font-display font-black text-white text-xs italic flex-shrink-0"
                         style="background: linear-gradient(135deg, #2563eb, #7c3aed);">{{ substr($storeName, 0, 1) }}</div>
                    <span class="font-display font-extrabold text-slate-900 text-sm truncate max-w-[130px] xs:max-w-[180px] sm:max-w-none">
                        @yield('header_title', 'Panel')
                    </span>
                </div>

                <!-- Desktop / Tablet Inline Search Bar (>= 640px) -->
                <div class="topbar-search hidden sm:block flex-1 max-w-md ml-2">
                    <div class="relative w-full">
                        <i class="fa-solid fa-magnifying-glass search-icon"></i>
                        <input type="text" 
                               placeholder="Buscar en categorías, marcas, productos..." 
                               @keydown.enter="handleSearch($event.target.value)">
                        <button type="button" 
                                @click="startScanner()" 
                                class="absolute right-2.5 top-1/2 -translate-y-1/2 text-blue-600 hover:text-blue-800 p-1.5 rounded-lg hover:bg-blue-50 transition" 
                                title="Escanear Código de Barras o QR">
                            <i class="fa-solid fa-barcode text-base"></i>
                        </button>
                    </div>
                </div>

            </div>

            <!-- Right Actions (Scanner button for mobile, Notifications, User profile) -->
            <div class="flex items-center gap-1.5 sm:gap-2 flex-shrink-0">

                <!-- Mobile Quick Search / Barcode Action Buttons (< 640px) -->
                <div class="flex sm:hidden items-center gap-1">
                    <button type="button" 
                            @click="mobileSearchOpen = !mobileSearchOpen" 
                            class="flex h-9 w-9 items-center justify-center rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-100 transition" 
                            title="Buscar Productos">
                        <i class="fa-solid fa-magnifying-glass text-xs"></i>
                    </button>
                    
                    <button type="button" 
                            @click="startScanner()" 
                            class="flex h-9 w-9 items-center justify-center rounded-xl bg-blue-50 border border-blue-200 text-blue-600 hover:bg-blue-100 transition" 
                            title="Escanear Código">
                        <i class="fa-solid fa-barcode text-sm"></i>
                    </button>
                </div>

                <!-- Notifications -->
                @php
                    $notificationOrders = \App\Models\Order::with('client')->where('status', 'Pendiente')->latest()->take(5)->get();
                    $pendingOrdersCount = \App\Models\Order::where('status', 'Pendiente')->count();
                @endphp
                <div class="relative">
                    <button type="button" 
                            data-notifications-toggle 
                            aria-expanded="false" 
                            aria-controls="admin-notifications" 
                            aria-label="Notificaciones" 
                            class="relative flex h-9 w-9 sm:h-10 sm:w-10 items-center justify-center rounded-xl border border-slate-200 sm:border-transparent text-slate-600 hover:border-blue-100 hover:bg-blue-50 hover:text-blue-600 transition">
                        <i class="fa-regular fa-bell text-sm sm:text-base"></i>
                        @if($pendingOrdersCount > 0)
                            <span class="absolute -right-1 -top-1 flex h-4 min-w-4 sm:h-5 sm:min-w-5 items-center justify-center rounded-full border-2 border-white bg-rose-500 px-1 text-[9px] font-extrabold text-white">{{ $pendingOrdersCount > 99 ? '99+' : $pendingOrdersCount }}</span>
                        @endif
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
                <div class="w-px h-6 sm:h-8 bg-slate-200 mx-0.5 sm:mx-1"></div>

                <!-- User Profile Dropdown -->
                <div class="relative" x-data="{ open: false }">
                    <div @click="open = !open" @click.away="open = false" class="flex items-center gap-2 p-1 sm:px-3 sm:py-2 rounded-xl hover:bg-slate-50 transition-all cursor-pointer border border-transparent hover:border-slate-200 select-none">
                        <div class="w-8 h-8 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold text-xs sm:text-sm shadow-sm flex-shrink-0">
                            {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
                        </div>
                        <div class="hidden md:flex flex-col text-left">
                            <span class="text-xs sm:text-sm font-bold text-slate-800 leading-none truncate max-w-[120px]">{{ Auth::user()->name ?? 'Administrador' }}</span>
                            <span class="text-[10px] text-slate-400 mt-0.5">Administrador</span>
                        </div>
                        <i class="fa-solid fa-chevron-down text-[10px] text-slate-400 hidden md:block" :class="{'rotate-180': open}"></i>
                    </div>

                    <!-- Dropdown Menu -->
                    <div x-show="open" x-transition x-cloak class="absolute right-0 mt-2 w-48 bg-white border border-slate-200 rounded-xl shadow-xl z-50 py-2">
                        <div class="px-4 py-2 border-b border-slate-100 md:hidden">
                            <p class="text-xs font-bold text-slate-800 truncate">{{ Auth::user()->name ?? 'Administrador' }}</p>
                            <p class="text-[10px] text-slate-400">Administrador</p>
                        </div>
                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-xs sm:text-sm text-slate-700 hover:bg-slate-50 hover:text-blue-600">
                            <i class="fa-regular fa-user mr-2 text-slate-400"></i> Mi Perfil
                        </a>
                        <a href="{{ route('admin.settings.index') }}" class="block px-4 py-2 text-xs sm:text-sm text-slate-700 hover:bg-slate-50 hover:text-blue-600">
                            <i class="fa-solid fa-gear mr-2 text-slate-400"></i> Configuración
                        </a>
                        <div class="border-t border-slate-100 my-1"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2 text-xs sm:text-sm text-red-600 hover:bg-red-50 font-medium">
                                <i class="fa-solid fa-right-from-bracket mr-2"></i> Cerrar Sesión
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </header>

        <!-- Mobile Expandable Search Bar Sub-Row (< 640px) -->
        <div x-show="mobileSearchOpen" 
             x-cloak 
             x-transition 
             class="sm:hidden bg-white border-b border-slate-200 p-3 shadow-inner">
            <div class="relative w-full">
                <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                <input type="text" 
                       placeholder="Buscar categoría, marca, código..." 
                       class="w-full h-10 bg-slate-50 border border-slate-200 rounded-xl pl-9 pr-10 text-xs text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none"
                       @keydown.enter="handleSearch($event.target.value)">
                <button type="button" 
                        @click="startScanner()" 
                        class="absolute right-2 top-1/2 -translate-y-1/2 text-blue-600 hover:text-blue-800 p-1.5 rounded-lg hover:bg-blue-50 transition" 
                        title="Escanear">
                    <i class="fa-solid fa-barcode text-sm"></i>
                </button>
            </div>
        </div>

        <!-- Global Scanner Modal -->
        <div class="fixed inset-0 z-[200] bg-slate-950/85 backdrop-blur-md flex flex-col items-center justify-center p-3 sm:p-4" 
             x-show="showScanner" 
             x-cloak 
             x-transition.opacity
             @keydown.window.escape="stopScanner()">
            <div class="w-full max-w-lg bg-slate-900 border border-slate-700/70 rounded-3xl overflow-hidden shadow-2xl relative text-white">
                
                <!-- Modal Header -->
                <div class="px-5 py-3.5 bg-slate-800/80 border-b border-slate-700 flex justify-between items-center">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-xl bg-blue-500/20 text-blue-400 flex items-center justify-center">
                            <i class="fa-solid fa-barcode text-base"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-sm text-slate-100">Escáner de Código de Barras / QR</h3>
                            <p class="text-[11px] text-slate-400">Búsqueda rápida en tiempo real</p>
                        </div>
                    </div>
                    <button type="button" @click="stopScanner()" class="w-8 h-8 rounded-xl text-slate-400 hover:text-white hover:bg-slate-700 flex items-center justify-center transition">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>

                <!-- Scanner Viewport Area -->
                <div class="p-4 sm:p-5 bg-black relative flex flex-col items-center justify-center min-h-[300px]">
                    
                    <!-- Loading State -->
                    <div x-show="isLoading" class="absolute inset-0 bg-black/80 z-20 flex flex-col items-center justify-center gap-3">
                        <div class="w-10 h-10 border-3 border-blue-500 border-t-transparent rounded-full animate-spin"></div>
                        <span class="text-xs text-slate-300 font-medium">Iniciando cámara...</span>
                    </div>

                    <!-- Permission Denied / Camera Error Screen -->
                    <div x-show="cameraError" x-cloak class="w-full max-w-md bg-slate-800/90 border border-rose-500/30 rounded-2xl p-5 text-center my-auto z-10">
                        <div class="w-12 h-12 rounded-2xl bg-rose-500/10 text-rose-400 flex items-center justify-center mx-auto mb-3">
                            <i class="fa-solid fa-camera-slash text-xl"></i>
                        </div>
                        <h4 class="text-sm font-bold text-slate-100 mb-1">
                            <template x-if="cameraError === 'permission_denied'">Permiso de cámara bloqueado</template>
                            <template x-if="cameraError === 'no_camera'">No se detectó cámara</template>
                            <template x-if="cameraError === 'generic_error'">No se pudo iniciar la cámara</template>
                        </h4>
                        <p class="text-xs text-slate-300 mb-4 leading-relaxed">
                            <template x-if="cameraError === 'permission_denied'">
                                <span>Para escanear, toca el <strong>ícono de candado 🔒</strong> o ajustes en la barra de direcciones de tu navegador y activa el permiso de <strong>Cámara</strong>.</span>
                            </template>
                            <template x-if="cameraError !== 'permission_denied'">
                                <span>No pudimos acceder a tu cámara. Puedes subir una imagen o foto con el código o escribirlo manualmente.</span>
                            </template>
                        </p>

                        <div class="flex flex-col sm:flex-row gap-2 justify-center">
                            <button type="button" 
                                    @click="startCamera()" 
                                    class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-500 text-white font-semibold text-xs transition shadow-lg shadow-blue-500/20">
                                <i class="fa-solid fa-rotate-right"></i> Reintentar Cámara
                            </button>
                            <label class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-slate-700 hover:bg-slate-600 text-slate-200 font-semibold text-xs transition cursor-pointer">
                                <i class="fa-solid fa-image"></i> Subir Foto / Imagen
                                <input type="file" accept="image/*" class="hidden" @change="scanFromFile($event)">
                            </label>
                        </div>
                    </div>

                    <!-- Live Viewport Container -->
                    <div id="global-reader" 
                         x-show="!cameraError" 
                         class="w-full overflow-hidden rounded-2xl bg-black min-h-[260px] max-w-sm"></div>

                    <!-- Camera Switcher (If multiple cameras available) -->
                    <div x-show="cameras.length > 1 && !cameraError && isScanning" class="mt-3 flex items-center gap-2 z-10">
                        <i class="fa-solid fa-video text-xs text-slate-400"></i>
                        <select @change="changeCamera($event.target.value)" 
                                class="bg-slate-800 text-xs text-slate-200 border border-slate-700 rounded-lg px-2.5 py-1 focus:outline-none focus:border-blue-500">
                            <template x-for="cam in cameras" :key="cam.id">
                                <option :value="cam.id" :selected="cam.id === selectedCameraId" x-text="cam.label || 'Cámara ' + cam.id"></option>
                            </template>
                        </select>
                    </div>

                </div>

                <!-- Footer & Alternatives -->
                <div class="px-5 py-4 bg-slate-900 border-t border-slate-800 space-y-3">
                    
                    <!-- File upload & action bar -->
                    <div class="flex items-center justify-between gap-2 text-xs">
                        <span class="text-slate-400 flex items-center gap-1.5">
                            <i class="fa-solid fa-qrcode text-blue-400"></i> EAN-13, QR, CODE-128
                        </span>
                        <label class="text-blue-400 hover:text-blue-300 font-medium cursor-pointer inline-flex items-center gap-1">
                            <i class="fa-solid fa-file-arrow-up"></i>
                            <span>Escanear desde archivo</span>
                            <input type="file" accept="image/*" class="hidden" @change="scanFromFile($event)">
                        </label>
                    </div>

                    <!-- Manual code lookup fallback -->
                    <div class="pt-2 border-t border-slate-800/80">
                        <form @submit.prevent="if(manualInput.trim()) { stopScanner(); lookupProduct(manualInput.trim()); }" class="flex gap-2">
                            <input type="text" 
                                   x-model="manualInput" 
                                   placeholder="O escribe el código / serie manualmente..." 
                                   class="flex-1 bg-slate-800/90 border border-slate-700 rounded-xl px-3.5 py-2 text-xs text-slate-100 placeholder-slate-500 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                            <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white font-semibold text-xs rounded-xl transition">
                                Buscar
                            </button>
                        </form>
                    </div>

                </div>
            </div>
        </div>

        <!-- Main Content Area -->
        <main class="flex-1 overflow-y-auto p-3.5 sm:p-5 lg:p-6 fade-in">
            @yield('content')
        </main>
    </div>

    @stack('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/html5-qrcode"></script>
    <script>
        function globalScanner() {
            return {
                showScanner: false,
                html5QrCode: null,
                isScanning: false,
                isLoading: false,
                cameraError: null,
                cameras: [],
                selectedCameraId: null,
                manualInput: '',

                startScanner() {
                    this.showScanner = true;
                    this.manualInput = '';
                    this.cameraError = null;
                    setTimeout(() => {
                        this.startCamera();
                    }, 150);
                },

                async startCamera() {
                    this.isLoading = true;
                    this.cameraError = null;

                    if (!this.html5QrCode) {
                        this.html5QrCode = new Html5Qrcode("global-reader");
                    }

                    // If already scanning, stop first
                    if (this.isScanning) {
                        try {
                            await this.html5QrCode.stop();
                            this.isScanning = false;
                        } catch (e) {}
                    }

                    const config = {
                        fps: 10,
                        qrbox: { width: 250, height: 160 },
                        aspectRatio: 1.333334
                    };

                    try {
                        const cameraConfig = this.selectedCameraId 
                            ? { deviceId: { exact: this.selectedCameraId } }
                            : { facingMode: "environment" };

                        await this.html5QrCode.start(
                            cameraConfig,
                            config,
                            (decodedText) => {
                                this.stopScanner();
                                this.lookupProduct(decodedText);
                            },
                            (errorMessage) => {
                                // Ignore frame scan misses
                            }
                        );

                        this.isScanning = true;
                        this.isLoading = false;
                        this.cameraError = null;

                        // Fetch camera list for selector
                        try {
                            const devices = await Html5Qrcode.getCameras();
                            if (devices && devices.length > 0) {
                                this.cameras = devices;
                                if (!this.selectedCameraId) {
                                    this.selectedCameraId = devices[0].id;
                                }
                            }
                        } catch (e) {}

                    } catch (err) {
                        this.isLoading = false;
                        this.isScanning = false;
                        console.warn("Scanner error:", err);
                        const errStr = String(err).toLowerCase();
                        if (errStr.includes('notallowederror') || errStr.includes('permission') || errStr.includes('denied')) {
                            this.cameraError = 'permission_denied';
                        } else if (errStr.includes('notfounderror') || errStr.includes('no camera') || errStr.includes('devicesnotfound')) {
                            this.cameraError = 'no_camera';
                        } else {
                            this.cameraError = 'generic_error';
                        }
                    }
                },

                async changeCamera(cameraId) {
                    this.selectedCameraId = cameraId;
                    await this.startCamera();
                },

                async scanFromFile(event) {
                    const file = event.target.files && event.target.files[0];
                    if (!file) return;

                    if (!this.html5QrCode) {
                        this.html5QrCode = new Html5Qrcode("global-reader");
                    }

                    this.isLoading = true;

                    if (this.isScanning) {
                        try {
                            await this.html5QrCode.stop();
                            this.isScanning = false;
                        } catch (e) {}
                    }

                    try {
                        const decodedText = await this.html5QrCode.scanFile(file, true);
                        this.isLoading = false;
                        this.stopScanner();
                        this.lookupProduct(decodedText);
                    } catch (err) {
                        this.isLoading = false;
                        Swal.fire({
                            icon: 'warning',
                            title: 'No se detectó código',
                            text: 'No se pudo leer ningún código de barras o QR en la imagen seleccionada. Asegúrate de que el código esté nítido y bien iluminado.',
                            confirmButtonColor: '#2563eb'
                        });
                    } finally {
                        event.target.value = '';
                    }
                },

                stopScanner() {
                    if (this.html5QrCode && this.isScanning) {
                        this.html5QrCode.stop().then(() => {
                            this.isScanning = false;
                        }).catch(e => {
                            this.isScanning = false;
                        });
                    }
                    this.showScanner = false;
                    this.cameraError = null;
                    this.isLoading = false;
                },

                handleSearch(val) {
                    if (val) this.lookupProduct(val);
                },

                async lookupProduct(code) {
                    Swal.fire({
                        title: 'Buscando...',
                        text: 'Verificando código: ' + code,
                        allowOutsideClick: false,
                        didOpen: () => { Swal.showLoading(); }
                    });

                    try {
                        const response = await fetch(`{{ route('admin.products.searchByCode') }}?code=${encodeURIComponent(code)}`);
                        const data = await response.json();

                        if (data.found && data.product) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Producto Encontrado',
                                html: `
                                    <div class="text-left mt-4 text-sm space-y-1">
                                        <p><strong>Nombre:</strong> ${data.product.name}</p>
                                        <p><strong>Código:</strong> ${data.product.code}</p>
                                        <p><strong>Precio Normal:</strong> <span class="${data.product.is_offer ? 'line-through text-red-500' : ''}">S/ ${parseFloat(data.product.price).toFixed(2)}</span></p>
                                        ${data.product.is_offer ? `<p><strong>Precio Oferta:</strong> <span class="font-bold text-green-600">S/ ${parseFloat(data.product.offer_price).toFixed(2)}</span></p>` : ''}
                                        <p><strong>Stock:</strong> ${data.product.stock} un.</p>
                                    </div>
                                `,
                                confirmButtonText: 'Ver Catálogo',
                                confirmButtonColor: '#2563eb',
                                showCancelButton: true,
                                cancelButtonText: 'Cerrar'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.href = "{{ route('admin.products.index') }}";
                                }
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'No encontrado',
                                text: 'No existe ningún producto con el código: ' + code,
                                confirmButtonColor: '#2563eb'
                            });
                        }
                    } catch (error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error de Conexión',
                            text: 'No se pudo verificar el producto.',
                            confirmButtonColor: '#2563eb'
                        });
                    }
                }
            }
        }

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
