<!DOCTYPE html>
<html lang="es-PE">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Acceso administrativo — {{ \App\Models\Setting::where('key', 'store_name')->value('value') ?? 'MPC Antigravity' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Outfit:wght@500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script>tailwind = { config: { theme: { extend: { fontFamily: { sans: ['Inter', 'sans-serif'], display: ['Outfit', 'sans-serif'] }, colors: { brand: { blue: '#3157dc', bg: '#f4f6fa', border: '#dce3f0' } } } } };</script>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-[#f4f6fa] font-sans text-slate-700 antialiased">
    <main class="mx-auto flex min-h-screen max-w-6xl items-center justify-center p-4 sm:p-8">
        <div class="grid w-full overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-2xl shadow-blue-950/10 lg:min-h-[610px] lg:grid-cols-[1.05fr_.95fr]">
            <section class="relative hidden flex-col justify-between overflow-hidden bg-gradient-to-br from-[#0b1730] via-[#173a86] to-[#5130b5] p-10 text-white lg:flex xl:p-14">
                <div class="absolute -right-20 top-10 h-72 w-72 rounded-full bg-indigo-400/30 blur-3xl"></div><div class="absolute -bottom-20 -left-10 h-72 w-72 rounded-full bg-cyan-400/20 blur-3xl"></div>
                <a href="{{ route('home') }}" class="relative z-10 flex items-center gap-3"><span class="flex h-12 w-12 items-center justify-center rounded-xl bg-white text-xl font-black italic text-indigo-700">M</span><span><span class="block font-display text-base font-extrabold">MPC ANTIGRAVITY</span><span class="mt-1 block text-[9px] tracking-[.18em] text-blue-100/70">PANEL DE ADMINISTRACIÓN</span></span></a>
                <div class="absolute inset-x-7 top-[28%] flex h-[48%] items-center justify-center opacity-90" aria-hidden="true">
                    <svg viewBox="0 0 600 340" class="h-full w-full max-w-xl drop-shadow-2xl" role="img">
                        <defs><linearGradient id="deviceScreen" x1="0" y1="0" x2="1" y2="1"><stop stop-color="#4dd4ff"/><stop offset=".52" stop-color="#4368f5"/><stop offset="1" stop-color="#9b5cff"/></linearGradient><linearGradient id="deviceBody" x1="0" y1="0" x2="1" y2="1"><stop stop-color="#f8fbff"/><stop offset="1" stop-color="#a8b8df"/></linearGradient></defs>
                        <ellipse cx="298" cy="295" rx="215" ry="24" fill="#07142f" opacity=".48"/>
                        <g transform="translate(73 35) rotate(-4 220 130)"><rect x="88" y="26" width="332" height="215" rx="17" fill="#dfe8ff" opacity=".18"/><rect x="99" y="35" width="310" height="193" rx="12" fill="#08152e" stroke="#a5c3ff" stroke-width="2"/><rect x="111" y="47" width="286" height="168" rx="7" fill="url(#deviceScreen)"/><circle cx="253" cy="54" r="2.2" fill="#dbeafe"/><path d="M124 180c51-74 82-56 113-89 30 40 68 36 146-25v149H124z" fill="#101e49" opacity=".58"/><path d="M72 241h365l38 30c5 4 2 10-5 10H39c-8 0-10-7-3-11z" fill="url(#deviceBody)"/><path d="M199 246h115l-14 13h-87z" fill="#8ea0c5"/></g>
                        <g transform="translate(365 25)"><rect width="140" height="87" rx="14" fill="#fff" opacity=".96"/><circle cx="28" cy="29" r="14" fill="#e9eaff"/><path d="M22 29h12m-6-6v12" stroke="#5b52df" stroke-width="2.5" stroke-linecap="round"/><rect x="52" y="21" width="68" height="7" rx="3.5" fill="#a8b2ca"/><rect x="52" y="35" width="48" height="6" rx="3" fill="#e1e6f2"/><rect x="19" y="58" width="103" height="13" rx="6.5" fill="#eaf0ff"/><rect x="20" y="59" width="71" height="11" rx="5.5" fill="#5a66e9"/></g>
                        <g transform="translate(60 80)"><circle cx="36" cy="36" r="28" fill="#21c894"/><path d="m23 36 9 9 18-20" fill="none" stroke="#fff" stroke-width="6" stroke-linecap="round" stroke-linejoin="round"/></g>
                    </svg>
                </div>
                <div class="relative z-10 max-w-lg"><p class="text-[9px] font-bold uppercase tracking-[.2em] text-indigo-200">Gestión de tienda</p><h1 class="mt-4 font-display text-4xl font-extrabold leading-tight xl:text-5xl">Todo tu catálogo, en un solo lugar.</h1><p class="mt-4 max-w-md text-sm leading-6 text-blue-100/75">Administra productos, pedidos y contenido de MPC Antigravity.</p></div>
                <div class="relative z-10 flex flex-wrap gap-x-6 gap-y-3 text-[10px] font-medium text-blue-100/80"><span><i class="fa-solid fa-box-open mr-2 text-cyan-200"></i>Catálogo</span><span><i class="fa-brands fa-whatsapp mr-2 text-emerald-300"></i>Pedidos WhatsApp</span><span><i class="fa-solid fa-chart-line mr-2 text-violet-200"></i>Gestión</span></div>
            </section>

            <section class="flex items-center justify-center px-6 py-10 sm:px-12">
                <div class="w-full max-w-sm">
                    <div class="mb-6 overflow-hidden rounded-2xl bg-gradient-to-br from-[#101c3b] via-[#2549ae] to-[#693fe0] p-4 text-white lg:hidden">
                        <div class="flex items-center justify-between"><div><p class="text-[9px] font-bold uppercase tracking-[.16em] text-indigo-200">Tu tienda, en control</p><p class="mt-1 font-display text-sm font-extrabold">Todo tu catálogo en un solo lugar.</p></div><span class="flex h-12 w-12 items-center justify-center rounded-xl bg-white/15 text-xl"><i class="fa-solid fa-laptop"></i></span></div>
                        <div class="mt-4 flex items-end gap-1.5"><span class="h-5 flex-1 rounded-t bg-cyan-200/80"></span><span class="h-8 flex-1 rounded-t bg-white/80"></span><span class="h-6 flex-1 rounded-t bg-indigo-200/90"></span><span class="h-11 flex-1 rounded-t bg-white"></span><span class="h-9 flex-1 rounded-t bg-cyan-200/80"></span><span class="h-14 flex-1 rounded-t bg-violet-200"></span></div>
                    </div>
                    <a href="{{ route('home') }}" class="mb-8 flex w-fit items-center gap-3 lg:hidden"><span class="flex h-11 w-11 items-center justify-center rounded-xl bg-gradient-to-br from-blue-600 to-violet-600 text-lg font-black italic text-white">M</span><span><span class="block font-display text-sm font-extrabold text-[#142143]">MPC ANTIGRAVITY</span><span class="mt-1 block text-[9px] tracking-[.15em] text-slate-400">PANEL DE ADMINISTRACIÓN</span></span></a>
                    <p class="text-[10px] font-extrabold uppercase tracking-[.18em] text-violet-600">Bienvenido de nuevo</p>
                    <h2 class="mt-2 font-display text-3xl font-extrabold tracking-tight text-[#142143]">Inicia sesión</h2>
                    <p class="mt-2 text-xs leading-5 text-slate-500">Ingresa con tu cuenta para continuar al panel.</p>

                    @if ($errors->any())
                        <div role="alert" class="mt-5 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-xs font-medium text-rose-700">Credenciales incorrectas. Verifica tus datos e inténtalo otra vez.</div>
                    @endif

                    <form method="POST" action="{{ route('login') }}" class="mt-7 space-y-5">
                        @csrf
                        <div><label for="email" class="mb-2 block text-xs font-bold text-slate-700">Correo electrónico</label><div class="flex h-12 items-center gap-3 rounded-xl border border-slate-200 bg-slate-50 px-4 focus-within:border-indigo-400 focus-within:bg-white focus-within:ring-4 focus-within:ring-indigo-100"><i class="fa-regular fa-envelope text-sm text-slate-400"></i><input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="nombre@empresa.com" class="min-w-0 flex-1 bg-transparent text-sm outline-none placeholder:text-slate-400"></div></div>
                        <div><label for="password" class="mb-2 block text-xs font-bold text-slate-700">Contraseña</label><div class="flex h-12 items-center gap-3 rounded-xl border border-slate-200 bg-slate-50 px-4 focus-within:border-indigo-400 focus-within:bg-white focus-within:ring-4 focus-within:ring-indigo-100"><i class="fa-solid fa-lock text-sm text-slate-400"></i><input id="password" type="password" name="password" required autocomplete="current-password" placeholder="Tu contraseña" class="min-w-0 flex-1 bg-transparent text-sm outline-none placeholder:text-slate-400"></div></div>
                        <div class="flex items-center justify-between gap-3 text-[11px]"><label for="remember_me" class="flex cursor-pointer items-center gap-2 text-slate-500"><input id="remember_me" type="checkbox" name="remember" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"><span>Recordarme</span></label>@if(Route::has('password.request'))<a class="font-semibold text-blue-700 hover:text-violet-700" href="{{ route('password.request') }}">¿Olvidaste tu contraseña?</a>@endif</div>
                        <button type="submit" class="flex h-12 w-full items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-[#3157dc] to-[#673de6] text-sm font-extrabold text-white shadow-lg shadow-indigo-700/20 transition hover:from-[#2549c2] hover:to-[#5430c7]">Iniciar sesión <i class="fa-solid fa-arrow-right"></i></button>
                    </form>
                    <p class="mt-6 text-center text-[10px] text-slate-400"><i class="fa-solid fa-shield-halved mr-1 text-indigo-500"></i> Acceso seguro al panel administrativo</p>
                </div>
            </section>
        </div>
    </main>
</body>
</html>
