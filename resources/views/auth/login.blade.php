<!DOCTYPE html>
<html lang="es-PE">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @php
        $settings = \App\Models\Setting::pluck('value', 'key');
        $storeName = $settings['store_name'] ?? 'Portátiles Perú / Moyo Tech';
        $primaryColor = $settings['primary_color'] ?? '#2855d9';
    @endphp
    <title>Acceso Administrativo — {{ $storeName }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Outfit:wght@500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script>
        tailwind = { 
            config: { 
                theme: { 
                    extend: { 
                        fontFamily: { 
                            sans: ['Inter', 'sans-serif'], 
                            display: ['Outfit', 'sans-serif'] 
                        },
                        colors: {
                            brand: {
                                blue: '{{ $primaryColor }}',
                                navy: '#0b1730'
                            }
                        }
                    } 
                } 
            } 
        };
    </script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="relative min-h-screen font-sans text-slate-700 antialiased flex items-center justify-center p-4 sm:p-6 lg:p-8">
    
    <!-- Background Tech Wallpaper Image with Soft Overlay -->
    <div class="fixed inset-0 z-0 overflow-hidden">
        <img src="https://images.unsplash.com/photo-1519389950473-47ba0277781c?auto=format&fit=crop&w=2000&q=80" 
             alt="Tech background" 
             class="h-full w-full object-cover scale-105 filter blur-[2px] brightness-[0.75]">
        <div class="absolute inset-0 bg-gradient-to-br from-[#0b1730]/85 via-[#111c36]/75 to-[#1e1b4b]/80 backdrop-blur-[3px]"></div>
    </div>

    <!-- Main Container -->
    <main class="relative z-10 w-full max-w-5xl">
        <div class="overflow-hidden rounded-3xl border border-white/20 bg-white shadow-2xl shadow-black/30 grid lg:grid-cols-[1.05fr_0.95fr]">
            
            <!-- Left Panel: Antixor Dark Navy Style (Same as Home Hero) -->
            <section class="relative hidden lg:flex flex-col justify-between overflow-hidden bg-gradient-to-br from-[#0a0f1d] via-[#111a33] to-[#1c1836] p-10 xl:p-12 text-white border-r border-slate-100">
                <!-- Glowing Ambient Accent -->
                <div class="pointer-events-none absolute -right-20 top-0 h-72 w-72 rounded-full bg-indigo-500/20 blur-3xl"></div>
                <div class="pointer-events-none absolute -bottom-20 -left-10 h-72 w-72 rounded-full bg-cyan-500/15 blur-3xl"></div>
                
                <!-- Brand Header -->
                <div class="relative z-10 flex items-center justify-between">
                    <a href="{{ route('home') }}" class="group flex items-center gap-3">
                        <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-gradient-to-tr from-indigo-600 to-violet-600 text-xl font-black italic text-white shadow-lg shadow-indigo-600/30 transition group-hover:scale-105">
                            {{ substr($storeName, 0, 1) }}
                        </div>
                        <div>
                            <span class="block font-display text-sm font-black uppercase tracking-tight text-white">{{ $storeName }}</span>
                            <span class="block text-[9px] font-bold tracking-[0.18em] text-indigo-300 uppercase">Panel de Control</span>
                        </div>
                    </a>
                    <a href="{{ route('home') }}" class="rounded-xl border border-white/20 bg-white/10 px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-white/20 flex items-center gap-1.5">
                        <i class="fa-solid fa-arrow-left text-[10px]"></i> Ver Catálogo
                    </a>
                </div>

                <!-- Center Showcase Graphic -->
                <div class="relative z-10 my-6 flex flex-col items-center justify-center">
                    <div class="relative w-full max-w-sm flex items-center justify-center">
                        
                        <!-- Floating Badge: WhatsApp -->
                        <div class="absolute -top-3 -left-2 z-20 flex items-center gap-2.5 rounded-2xl border border-white/15 bg-[#0b1730]/90 px-3.5 py-2 shadow-xl backdrop-blur-md">
                            <span class="flex h-7 w-7 items-center justify-center rounded-xl bg-emerald-500 text-white text-xs">
                                <i class="fa-brands fa-whatsapp"></i>
                            </span>
                            <div>
                                <span class="block text-[9px] font-bold text-slate-300">Pedidos WhatsApp</span>
                                <span class="block text-xs font-extrabold text-emerald-400">En línea</span>
                            </div>
                        </div>

                        <!-- Floating Badge: Stock -->
                        <div class="absolute -bottom-3 -right-2 z-20 flex items-center gap-2.5 rounded-2xl border border-white/15 bg-[#0b1730]/90 px-3.5 py-2 shadow-xl backdrop-blur-md">
                            <span class="flex h-7 w-7 items-center justify-center rounded-xl bg-indigo-500 text-white text-xs">
                                <i class="fa-solid fa-laptop"></i>
                            </span>
                            <div>
                                <span class="block text-[9px] font-bold text-slate-300">Gestión de Stock</span>
                                <span class="block text-xs font-extrabold text-indigo-300">Actualizado</span>
                            </div>
                        </div>

                        <!-- Illustrated Laptop Box -->
                        <div class="relative flex h-48 w-64 items-center justify-center rounded-3xl border border-white/10 bg-gradient-to-tr from-white/10 to-white/5 p-6 shadow-2xl backdrop-blur-md">
                            <div class="flex flex-col items-center text-center">
                                <div class="mb-2 flex h-16 w-16 items-center justify-center rounded-2xl bg-indigo-600 text-3xl text-white shadow-lg shadow-indigo-600/40">
                                    <i class="fa-solid fa-store"></i>
                                </div>
                                <h3 class="font-display text-base font-black text-white">Catálogo Virtual</h3>
                                <p class="mt-1 text-[11px] text-slate-300">Control de productos y precios</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bottom Text -->
                <div class="relative z-10 border-t border-white/10 pt-5">
                    <h2 class="font-display text-2xl font-black text-white leading-tight">
                        Todo tu catálogo en <span class="bg-gradient-to-r from-indigo-300 via-purple-300 to-pink-300 bg-clip-text text-transparent">un solo lugar</span>.
                    </h2>
                    <p class="mt-1.5 text-xs text-slate-300 leading-relaxed">
                        Administra productos, marcas, ofertas y banners de tu tienda virtual.
                    </p>
                    <div class="mt-3 flex items-center gap-4 text-[10px] font-bold text-slate-300">
                        <span class="flex items-center gap-1.5"><i class="fa-solid fa-circle-check text-emerald-400"></i> Catálogo Activo</span>
                        <span class="flex items-center gap-1.5"><i class="fa-solid fa-circle-check text-indigo-400"></i> Pedidos Directos</span>
                        <span class="flex items-center gap-1.5"><i class="fa-solid fa-circle-check text-purple-400"></i> Reportes PDF</span>
                    </div>
                </div>
            </section>

            <!-- Right Panel: Clean White Form (Matching Inicio/Store) -->
            <section class="flex flex-col justify-center p-8 sm:p-12 lg:p-14 bg-white">
                <div class="w-full max-w-sm mx-auto">
                    
                    <!-- Mobile Brand Header -->
                    <div class="mb-8 flex items-center justify-between lg:hidden">
                        <a href="{{ route('home') }}" class="flex items-center gap-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-600 text-lg font-black text-white">
                                {{ substr($storeName, 0, 1) }}
                            </div>
                            <span class="font-display text-sm font-black uppercase text-slate-900">{{ $storeName }}</span>
                        </a>
                        <a href="{{ route('home') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-700">
                            <i class="fa-solid fa-arrow-left mr-1"></i> Catálogo
                        </a>
                    </div>

                    <!-- Header -->
                    <div>
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-indigo-50 px-3 py-1 text-[10px] font-black uppercase tracking-wider text-indigo-600 border border-indigo-100">
                            <i class="fa-solid fa-lock text-[9px]"></i>
                            PANEL ADMINISTRATIVO
                        </span>
                        <h1 class="mt-3 font-display text-3xl font-black tracking-tight text-slate-900">
                            Iniciar sesión
                        </h1>
                        <p class="mt-1.5 text-xs text-slate-500">
                            Ingresa tus credenciales para administrar la tienda.
                        </p>
                    </div>

                    <!-- Error Alert -->
                    @if ($errors->any())
                        <div role="alert" class="mt-5 rounded-2xl border border-rose-200 bg-rose-50 p-3.5 text-xs font-medium text-rose-700 flex items-center gap-2.5">
                            <i class="fa-solid fa-circle-exclamation text-rose-500 text-base shrink-0"></i>
                            <span>Credenciales incorrectas. Verifica tus datos e inténtalo otra vez.</span>
                        </div>
                    @endif

                    <!-- Login Form -->
                    <form method="POST" action="{{ route('login') }}" class="mt-6 space-y-4">
                        @csrf
                        
                        <!-- Email Input -->
                        <div>
                            <label for="email" class="mb-1.5 block text-xs font-bold text-slate-700">
                                Correo electrónico
                            </label>
                            <div class="flex h-12 items-center gap-3 rounded-xl border border-slate-200 bg-slate-50 px-4 transition focus-within:border-indigo-500 focus-within:bg-white focus-within:ring-4 focus-within:ring-indigo-100">
                                <i class="fa-regular fa-envelope text-sm text-slate-400"></i>
                                <input id="email" 
                                       type="email" 
                                       name="email" 
                                       value="{{ old('email') }}" 
                                       required 
                                       autofocus 
                                       autocomplete="username" 
                                       placeholder="admin@portatilesperu.com" 
                                       class="min-w-0 flex-1 bg-transparent text-sm text-slate-800 outline-none placeholder:text-slate-400">
                            </div>
                        </div>

                        <!-- Password Input -->
                        <div>
                            <div class="mb-1.5 flex items-center justify-between">
                                <label for="password" class="block text-xs font-bold text-slate-700">
                                    Contraseña
                                </label>
                                @if(Route::has('password.request'))
                                    <a href="{{ route('password.request') }}" class="text-[11px] font-bold text-indigo-600 hover:text-indigo-700 transition">
                                        ¿Olvidaste tu contraseña?
                                    </a>
                                @endif
                            </div>
                            <div class="flex h-12 items-center gap-3 rounded-xl border border-slate-200 bg-slate-50 px-4 transition focus-within:border-indigo-500 focus-within:bg-white focus-within:ring-4 focus-within:ring-indigo-100" x-data="{ showPassword: false }">
                                <i class="fa-solid fa-lock text-sm text-slate-400"></i>
                                <input id="password" 
                                       :type="showPassword ? 'text' : 'password'" 
                                       type="password"
                                       name="password" 
                                       required 
                                       autocomplete="current-password" 
                                       placeholder="••••••••••••" 
                                       class="min-w-0 flex-1 bg-transparent text-sm text-slate-800 outline-none placeholder:text-slate-400">
                                <button type="button" 
                                        @click="showPassword = !showPassword" 
                                        class="text-slate-400 hover:text-slate-600 focus:outline-none p-1 transition"
                                        title="Mostrar/Ocultar contraseña">
                                    <i class="fa-regular text-xs" :class="showPassword ? 'fa-eye-slash text-indigo-600' : 'fa-eye'"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Remember Me -->
                        <div class="flex items-center pt-1">
                            <label for="remember_me" class="flex cursor-pointer items-center gap-2.5 text-xs text-slate-600 hover:text-slate-800">
                                <input id="remember_me" 
                                       type="checkbox" 
                                       name="remember" 
                                       class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                                <span>Recordar mi sesión</span>
                            </label>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="focus-ring mt-2 flex h-12 w-full items-center justify-center gap-2 rounded-xl bg-indigo-600 text-sm font-extrabold text-white shadow-lg shadow-indigo-600/30 transition duration-200 hover:bg-indigo-700 active:scale-[0.98]">
                            <span>Ingresar al panel</span>
                            <i class="fa-solid fa-arrow-right text-xs"></i>
                        </button>
                    </form>

                    <!-- Security Footer Notice -->
                    <div class="mt-8 border-t border-slate-100 pt-5 text-center">
                        <p class="text-[10px] text-slate-400 flex items-center justify-center gap-1.5 font-medium">
                            <i class="fa-solid fa-shield-halved text-emerald-500"></i>
                            <span>Conexión cifrada y segura con SSL</span>
                        </p>
                    </div>
                </div>
            </section>
        </div>
    </main>
</body>
</html>

