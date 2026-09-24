<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ \App\Models\Setting::where('key', 'store_name')->value('value') ?? 'MOYO TECH' }} - Panel</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700|outfit:400,500,600,700,800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Tailwind CSS (via CDN) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        display: ['Outfit', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            bg: '#040914',
                            surface: '#0f172a',
                            border: '#1e293b',
                            blue: '#0066ff',
                            green: '#10b981',
                        }
                    }
                }
            }
        }
    </script>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #f8fafc; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        
        .soft-card {
            background-color: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 1rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
        }
        .form-input {
            width: 100%;
            border-radius: 0.5rem;
            border-color: #e2e8f0;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            font-size: 0.875rem;
        }
        .form-input:focus {
            border-color: #3b82f6;
            outline: none;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.5);
        }
    </style>
</head>
<body class="font-sans antialiased bg-slate-50 text-slate-700 min-h-screen flex overflow-hidden">
    
    <!-- Sidebar -->
    <aside class="w-64 bg-brand-bg border-r border-brand-border flex-shrink-0 flex flex-col h-screen transition-all duration-300 relative z-20">
        <!-- Logo -->
        <div class="h-16 flex items-center px-6">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                <div class="font-display font-extrabold text-2xl italic tracking-tighter">
                    <span class="text-blue-500">M</span>
                </div>
                <div class="flex flex-col">
                    <span class="font-display font-bold text-lg tracking-wide leading-none text-white">MOYO TECH</span>
                    <span class="text-[9px] text-slate-400 font-medium tracking-widest mt-1">Panel de Administración</span>
                </div>
            </a>
        </div>

        <!-- Navigation Links -->
        <nav class="flex-1 overflow-y-auto py-6 px-3 space-y-1">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium transition-all group {{ request()->routeIs('dashboard') ? 'bg-brand-blue text-white shadow-lg shadow-blue-500/20' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                <i class="fa-solid fa-house-chimney w-5 text-center text-lg {{ request()->routeIs('dashboard') ? '' : 'group-hover:text-blue-400 transition-colors' }}"></i>
                <span class="text-sm">Inicio</span>
            </a>
            
            <a href="{{ route('admin.products.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium transition-all group {{ request()->routeIs('admin.products.*') ? 'bg-brand-blue text-white shadow-lg shadow-blue-500/20' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                <i class="fa-solid fa-box-open w-5 text-center text-lg {{ request()->routeIs('admin.products.*') ? '' : 'group-hover:text-blue-400 transition-colors' }}"></i>
                <span class="text-sm">Productos</span>
            </a>
            
            <a href="{{ route('admin.categories.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium transition-all group {{ request()->routeIs('admin.categories.*') ? 'bg-brand-blue text-white shadow-lg shadow-blue-500/20' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                <i class="fa-solid fa-layer-group w-5 text-center text-lg {{ request()->routeIs('admin.categories.*') ? '' : 'group-hover:text-blue-400 transition-colors' }}"></i>
                <span class="text-sm">Categorías</span>
            </a>
            
            <a href="{{ route('admin.brands.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium transition-all group {{ request()->routeIs('admin.brands.*') ? 'bg-brand-blue text-white shadow-lg shadow-blue-500/20' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                <i class="fa-solid fa-tags w-5 text-center text-lg {{ request()->routeIs('admin.brands.*') ? '' : 'group-hover:text-blue-400 transition-colors' }}"></i>
                <span class="text-sm">Marcas</span>
            </a>
            
            <a href="{{ route('admin.clients.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium transition-all group {{ request()->routeIs('admin.clients.*') ? 'bg-brand-blue text-white shadow-lg shadow-blue-500/20' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                <i class="fa-solid fa-users w-5 text-center text-lg {{ request()->routeIs('admin.clients.*') ? '' : 'group-hover:text-blue-400 transition-colors' }}"></i>
                <span class="text-sm">Clientes</span>
            </a>
            
            <a href="{{ route('admin.orders.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium transition-all group {{ request()->routeIs('admin.orders.*') ? 'bg-brand-blue text-white shadow-lg shadow-blue-500/20' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                <i class="fa-brands fa-whatsapp w-5 text-center text-lg {{ request()->routeIs('admin.orders.*') ? 'text-white' : 'group-hover:text-brand-green transition-colors' }}"></i>
                <span class="text-sm">Ventas (WhatsApp)</span>
            </a>
            
            <a href="{{ route('admin.sliders.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium transition-all group {{ request()->routeIs('admin.sliders.*') ? 'bg-brand-blue text-white shadow-lg shadow-blue-500/20' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                <i class="fa-solid fa-image w-5 text-center text-lg {{ request()->routeIs('admin.sliders.*') ? '' : 'group-hover:text-blue-400 transition-colors' }}"></i>
                <span class="text-sm">Publicidad / Slider</span>
            </a>
            
            <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium transition-all group {{ request()->routeIs('admin.users.*') ? 'bg-brand-blue text-white shadow-lg shadow-blue-500/20' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                <i class="fa-regular fa-user w-5 text-center text-lg {{ request()->routeIs('admin.users.*') ? '' : 'group-hover:text-blue-400 transition-colors' }}"></i>
                <span class="text-sm">Usuarios</span>
            </a>
            
            <a href="{{ route('admin.settings.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium transition-all group {{ request()->routeIs('admin.settings.*') ? 'bg-brand-blue text-white shadow-lg shadow-blue-500/20' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                <i class="fa-solid fa-gear w-5 text-center text-lg {{ request()->routeIs('admin.settings.*') ? '' : 'group-hover:text-blue-400 transition-colors' }}"></i>
                <span class="text-sm">Configuración</span>
            </a>
        </nav>
        
        <div class="p-4 border-t border-brand-border/50">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center px-4 py-3 border border-brand-border rounded-xl text-sm font-medium text-slate-400 hover:text-white hover:bg-red-500/10 hover:border-red-500/50 transition-colors">
                    <i class="fa-solid fa-sign-out-alt mr-2"></i> Cerrar Sesión
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Wrapper -->
    <div class="flex-1 flex flex-col h-screen overflow-hidden bg-slate-50">
        
        <!-- Top Navbar -->
        <header class="h-16 bg-white border-b border-slate-200 flex-shrink-0 flex items-center justify-between px-6 z-10">
            
            <div class="flex items-center flex-1 gap-6">
                <div class="relative w-96 hidden md:block">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fa-solid fa-magnifying-glass text-slate-400 text-sm"></i>
                    </span>
                    <input type="text" placeholder="Buscar en categorías, marcas, productos..." class="form-input pl-10 h-10 bg-slate-50 border-none focus:bg-white focus:ring-1 focus:ring-slate-300">
                </div>
            </div>

            <!-- Right: Actions & User -->
            <div class="flex items-center gap-4">
                <button class="relative text-slate-400 hover:text-blue-600 transition-colors h-10 w-10 flex items-center justify-center rounded-full hover:bg-slate-100">
                    <i class="fa-regular fa-bell text-lg"></i>
                    <span class="absolute top-2 right-2 h-2 w-2 rounded-full bg-red-500 border border-white"></span>
                </button>
                
                <div class="flex items-center gap-3 cursor-pointer group hover:bg-slate-50 p-2 rounded-lg transition-colors border border-transparent hover:border-slate-200">
                    <div class="w-8 h-8 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold shadow-sm">
                        {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
                    </div>
                    <div class="hidden md:flex flex-col text-left">
                        <span class="text-sm font-bold text-slate-800">{{ Auth::user()->name ?? 'Administrador' }}</span>
                        <span class="text-[10px] text-slate-500">Administrador</span>
                    </div>
                    <i class="fa-solid fa-chevron-down text-xs text-slate-400 ml-1"></i>
                </div>
            </div>
            
        </header>

        <!-- Main Content Scrollable Area -->
        <main class="flex-1 overflow-y-auto p-6 relative">
            <div class="absolute top-[-20%] left-[20%] w-[40%] h-[40%] bg-blue-600/5 blur-[120px] pointer-events-none"></div>
            
            <div class="relative z-10">
                {{ $slot }}
            </div>
        </main>
        
    </div>

    @stack('scripts')
</body>
</html>
