<!DOCTYPE html>
<html lang="es-PE">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'MPC Antigravity') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Outfit:wght@500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: Inter, sans-serif; background: #f4f6fa; }
        .guest-card { border: 1px solid #e2e8f0; border-radius: 22px; box-shadow: 0 24px 60px rgba(21, 39, 83, .12); }
        input:focus { --tw-ring-color: #c7d2fe !important; border-color: #6756dd !important; }
    </style>
</head>
<body class="font-sans antialiased text-slate-800">
    <div class="relative flex min-h-screen flex-col items-center justify-center overflow-hidden bg-[#f4f6fa] px-4 py-8 sm:px-6">
        <div class="pointer-events-none absolute -left-24 top-[-70px] h-72 w-72 rounded-full bg-blue-200/50 blur-3xl"></div>
        <div class="pointer-events-none absolute -right-16 bottom-[-80px] h-80 w-80 rounded-full bg-violet-200/50 blur-3xl"></div>
        <a href="{{ route('home') }}" class="relative mb-6 flex items-center gap-3">
            <span class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-blue-600 to-violet-600 font-display text-xl font-black italic text-white shadow-lg shadow-indigo-700/20">M</span>
            <span><span class="block font-display text-sm font-extrabold tracking-tight text-[#142143]">MPC ANTIGRAVITY</span><span class="mt-1 block text-[9px] font-semibold tracking-[.16em] text-slate-400">CATÁLOGO DE EQUIPOS</span></span>
        </a>
        <main class="guest-card relative w-full max-w-md bg-white px-6 py-7 sm:px-8 sm:py-8">
            {{ $slot }}
        </main>
        <p class="relative mt-5 text-center text-[10px] text-slate-400">¿Buscas un equipo? <a href="{{ route('catalog') }}" class="font-bold text-blue-700 hover:text-violet-700">Explora el catálogo</a></p>
    </div>
</body>
</html>
