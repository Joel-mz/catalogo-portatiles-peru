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
    <title>Verificación de Dos Pasos (2FA) — {{ $storeName }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Outfit:wght@500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
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
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="relative min-h-screen font-sans text-slate-700 antialiased flex items-center justify-center p-4 sm:p-6 lg:p-8" x-data="{ recovery: false }">
    
    <!-- Background Tech Wallpaper Image with Soft Overlay -->
    <div class="fixed inset-0 z-0 overflow-hidden">
        <img src="https://images.unsplash.com/photo-1519389950473-47ba0277781c?auto=format&fit=crop&w=2000&q=80" 
             alt="Tech background" 
             class="h-full w-full object-cover scale-105 filter blur-[2px] brightness-[0.75]">
        <div class="absolute inset-0 bg-gradient-to-br from-[#0b1730]/85 via-[#111c36]/75 to-[#1e1b4b]/80 backdrop-blur-[3px]"></div>
    </div>

    <!-- Main Container -->
    <main class="relative z-10 w-full max-w-md">
        <div class="overflow-hidden rounded-3xl border border-white/20 bg-white shadow-2xl shadow-black/30 p-8 sm:p-10">
            
            <!-- Icon & Header -->
            <div class="text-center">
                <div class="inline-flex h-16 w-16 items-center justify-center rounded-2xl bg-indigo-50 border border-indigo-100 text-indigo-600 text-2xl mb-4 shadow-sm">
                    <i class="fa-solid fa-shield-halved" x-show="!recovery"></i>
                    <i class="fa-solid fa-key" x-show="recovery" x-cloak></i>
                </div>

                <span class="inline-flex items-center gap-1.5 rounded-full bg-indigo-50 px-3 py-1 text-[10px] font-black uppercase tracking-wider text-indigo-600 border border-indigo-100 mb-2">
                    <i class="fa-solid fa-lock text-[9px]"></i>
                    SEGURIDAD REFORZADA 2FA
                </span>

                <h1 class="font-display text-2xl font-black tracking-tight text-slate-900 mt-1">
                    Verificación de Dos Pasos
                </h1>

                <p class="mt-2 text-xs text-slate-500 leading-relaxed" x-show="!recovery">
                    Ingresa el código de 6 dígitos generado por tu aplicación autenticadora (Google Authenticator, Microsoft Authenticator o Authy).
                </p>

                <p class="mt-2 text-xs text-slate-500 leading-relaxed" x-show="recovery" x-cloak>
                    Ingresa uno de tus códigos de recuperación de emergencia para acceder a tu cuenta.
                </p>
            </div>

            <!-- Error Alert -->
            @if ($errors->any())
                <div role="alert" class="mt-5 rounded-2xl border border-rose-200 bg-rose-50 p-3.5 text-xs font-medium text-rose-700 flex items-start gap-2.5">
                    <i class="fa-solid fa-circle-exclamation text-rose-500 text-base shrink-0 mt-0.5"></i>
                    <div>
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- 2FA Form -->
            <form method="POST" action="{{ route('two-factor.login.store') }}" class="mt-6 space-y-4">
                @csrf

                <!-- Code input (TOTP 6 Digits) -->
                <div x-show="!recovery">
                    <label for="code" class="mb-1.5 block text-xs font-bold text-slate-700 text-center">
                        CÓDIGO DE 6 DÍGITOS
                    </label>
                    <div class="relative">
                        <input id="code" 
                               type="text" 
                               name="code" 
                               inputmode="numeric" 
                               pattern="[0-9]*" 
                               maxlength="6" 
                               autofocus 
                               autocomplete="one-time-code" 
                               placeholder="123456" 
                               class="w-full text-center tracking-[0.4em] font-mono text-2xl font-bold py-3 px-4 rounded-xl border border-slate-200 bg-slate-50 text-slate-900 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 outline-none transition">
                    </div>
                </div>

                <!-- Recovery Code input -->
                <div x-show="recovery" x-cloak>
                    <label for="recovery_code" class="mb-1.5 block text-xs font-bold text-slate-700">
                        CÓDIGO DE RECUPERACIÓN
                    </label>
                    <div class="flex h-12 items-center gap-3 rounded-xl border border-slate-200 bg-slate-50 px-4 transition focus-within:border-indigo-500 focus-within:bg-white focus-within:ring-4 focus-within:ring-indigo-100">
                        <i class="fa-solid fa-key text-sm text-slate-400"></i>
                        <input id="recovery_code" 
                               type="text" 
                               name="recovery_code" 
                               autocomplete="off" 
                               placeholder="Ej: a1b2c3d4e5" 
                               class="min-w-0 flex-1 bg-transparent font-mono text-sm text-slate-800 outline-none placeholder:text-slate-400">
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="focus-ring mt-3 flex h-12 w-full items-center justify-center gap-2 rounded-xl bg-indigo-600 text-sm font-extrabold text-white shadow-lg shadow-indigo-600/30 transition duration-200 hover:bg-indigo-700 active:scale-[0.98]">
                    <i class="fa-solid fa-arrow-right-to-bracket text-xs"></i>
                    <span>Verificar e Ingresar</span>
                </button>

                <!-- Toggle Recovery / Code -->
                <div class="pt-2 text-center">
                    <button type="button" 
                            @click="recovery = !recovery; $nextTick(() => { recovery ? document.getElementById('recovery_code').focus() : document.getElementById('code').focus() })" 
                            class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 transition underline underline-offset-2">
                        <span x-show="!recovery">¿No tienes tu teléfono? Usar código de recuperación</span>
                        <span x-show="recovery" x-cloak>Usar código de 6 dígitos de la app</span>
                    </button>
                </div>
            </form>

            <!-- Back to Login -->
            <div class="mt-6 border-t border-slate-100 pt-4 text-center">
                <a href="{{ route('login') }}" class="inline-flex items-center gap-1.5 text-xs text-slate-400 hover:text-slate-600 font-medium transition">
                    <i class="fa-solid fa-arrow-left text-[10px]"></i>
                    <span>Volver a iniciar sesión</span>
                </a>
            </div>

        </div>
    </main>
</body>
</html>
