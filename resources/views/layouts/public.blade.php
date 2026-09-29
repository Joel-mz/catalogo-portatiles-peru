<!DOCTYPE html>
<html lang="es-PE" class="scroll-smooth">
<head>
    @php
        $settings = \App\Models\Setting::pluck('value', 'key');
        $storeName = $settings['store_name'] ?? 'PORTÁTILES PERÚ';
        $primaryColor = $settings['primary_color'] ?? '#2855d9';
        $topBannerText = $settings['top_banner_text'] ?? '';
        $facebookUrl = $settings['facebook_url'] ?? '';
        $instagramUrl = $settings['instagram_url'] ?? '';
        $tiktokUrl = $settings['tiktok_url'] ?? '';
        $youtubeUrl = $settings['youtube_url'] ?? '';
        $storePhone = $settings['whatsapp_number'] ?? '+51999999999';
        $storeEmail = $settings['store_email'] ?? '';
        $storeLogo = !empty($settings['store_logo']) ? (filter_var($settings['store_logo'], FILTER_VALIDATE_URL) ? $settings['store_logo'] : asset('storage/' . $settings['store_logo'])) : null;
        
        // SEO Defaults
        $defaultSeoTitle = $settings['seo_meta_title'] ?? ($storeName . ' — Catálogo Virtual de Laptops y Tecnología en Perú');
        $defaultSeoDesc = $settings['seo_meta_description'] ?? 'Descubre nuestro catálogo virtual de laptops gamer, computadoras, cámaras de seguridad e impresoras en Perú con los mejores precios, garantía y envíos a todo el país.';
        $defaultSeoKeywords = $settings['seo_keywords'] ?? 'laptops peru, catalogo virtual, computadoras, camaras de seguridad, impresoras, portatiles peru, tecnologia lima peru';
        $defaultOgImage = $storeLogo ?: url('/logo.png');
    @endphp
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- Primary SEO Meta Tags -->
    <title>@yield('title', $defaultSeoTitle) — {{ $storeName }}</title>
    <meta name="title" content="@yield('title', $defaultSeoTitle) — {{ $storeName }}">
    <meta name="description" content="@yield('meta_description', $defaultSeoDesc)">
    <meta name="keywords" content="@yield('meta_keywords', $defaultSeoKeywords)">
    <meta name="robots" content="@yield('meta_robots', 'index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1')">
    <meta name="author" content="{{ $storeName }}">
    <meta name="publisher" content="{{ $storeName }}">
    <meta name="geo.region" content="PE">
    <meta name="geo.placename" content="Perú">
    <link rel="canonical" href="@yield('canonical_url', url()->current())">

    <!-- Open Graph / Facebook / WhatsApp -->
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:locale" content="es_PE">
    <meta property="og:site_name" content="{{ $storeName }}">
    <meta property="og:url" content="@yield('og_url', url()->current())">
    <meta property="og:title" content="@yield('og_title', trim($__env->yieldContent('title')) ? trim($__env->yieldContent('title')) . ' — ' . $storeName : $defaultSeoTitle)">
    <meta property="og:description" content="@yield('og_description', trim($__env->yieldContent('meta_description')) ?: $defaultSeoDesc)">
    <meta property="og:image" content="@yield('og_image', $defaultOgImage)">
    <meta property="og:image:secure_url" content="@yield('og_image', $defaultOgImage)">
    <meta property="og:image:alt" content="@yield('og_image_alt', $storeName)">
    @yield('og_extra')

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('og_title', trim($__env->yieldContent('title')) ? trim($__env->yieldContent('title')) . ' — ' . $storeName : $defaultSeoTitle)">
    <meta name="twitter:description" content="@yield('og_description', trim($__env->yieldContent('meta_description')) ?: $defaultSeoDesc)">
    <meta name="twitter:image" content="@yield('og_image', $defaultOgImage)">

    <!-- Search Console Verifications (Google, Bing, Yahoo) -->
    <meta name="google-site-verification" content="{{ !empty($settings['google_site_verification']) ? $settings['google_site_verification'] : 'google0020d66592336fd4' }}">
    @if(!empty($settings['bing_site_verification']))
        <meta name="msvalidate.01" content="{{ $settings['bing_site_verification'] }}">
    @endif
    <meta name="theme-color" content="{{ $primaryColor }}">
    <meta name="application-name" content="{{ $storeName }}">
    <meta name="apple-mobile-web-app-title" content="{{ $storeName }}">

    <!-- DNS Prefetch & Preconnect for Core Web Vitals (>90 Score) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="dns-prefetch" href="https://fonts.googleapis.com">
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link rel="dns-prefetch" href="https://cdnjs.cloudflare.com">
    <link rel="dns-prefetch" href="https://cdn.tailwindcss.com">

    <!-- JSON-LD Structured Data for Google / Schema.org -->
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@@graph": [
            {
                "@@type": "WebSite",
                "@@id": "{{ url('/') }}/#website",
                "url": "{{ url('/') }}",
                "name": "{{ $storeName }}",
                "description": "{{ $defaultSeoDesc }}",
                "inLanguage": "es-PE",
                "potentialAction": {
                    "@@type": "SearchAction",
                    "target": {
                        "@@type": "EntryPoint",
                        "urlTemplate": "{{ route('catalog') }}?q={search_term_string}"
                    },
                    "query-input": "required name=search_term_string"
                }
            },
            {
                "@@type": "Store",
                "@@id": "{{ url('/') }}/#organization",
                "name": "{{ $storeName }}",
                "url": "{{ url('/') }}",
                @if($storeLogo)
                "logo": "{{ $storeLogo }}",
                "image": "{{ $storeLogo }}",
                @endif
                @if($storePhone)
                "telephone": "{{ $storePhone }}",
                @endif
                @if($storeEmail)
                "email": "{{ $storeEmail }}",
                @endif
                "priceRange": "S/.",
                "address": {
                    "@@type": "PostalAddress",
                    "addressCountry": "PE"
                },
                "sameAs": [
                    @php
                        $socialLinks = array_values(array_filter([$facebookUrl, $instagramUrl, $tiktokUrl, $youtubeUrl]));
                    @endphp
                    @foreach($socialLinks as $idx => $link)
                        "{{ $link }}"{{ $idx < count($socialLinks) - 1 ? ',' : '' }}
                    @endforeach
                ]
            }
        ]
    }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script>
        tailwind = { 
            darkMode: 'class',
            config: { theme: { extend: { fontFamily: { sans: ['Inter', 'sans-serif'], display: ['Outfit', 'sans-serif'] }, colors: { brand: { blue: '{{ $primaryColor }}', violet: '#673de6', navy: '#0b1730' } } } } } 
        };
    </script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
    <style>
        [x-cloak] { display: none !important; }
        body { background: #f4f6fa; color: #172033; font-family: Inter, sans-serif; transition: background-color 0.3s, color 0.3s; }
        html.dark body { background: #0b1120; color: #e2e8f0; }
        html.dark .bg-white { background-color: #1e293b !important; border-color: #334155 !important; color: #f8fafc !important; }
        html.dark .text-slate-800 { color: #f8fafc !important; }
        html.dark .text-slate-700 { color: #f1f5f9 !important; }
        html.dark .text-slate-600 { color: #cbd5e1 !important; }
        html.dark .text-slate-500 { color: #94a3b8 !important; }
        html.dark .border-slate-200, html.dark .border-slate-100 { border-color: #334155 !important; }
        html.dark .bg-slate-50, html.dark .bg-slate-100 { background-color: #0f172a !important; }
        html.dark .bg-white\/95 { background-color: rgba(30, 41, 59, 0.95) !important; }
        html.dark header, html.dark footer { border-color: #334155 !important; }
        .focus-ring:focus-visible { outline: 3px solid var(--admin-blue, #7654e8); outline-offset: 3px; }
        .line-clamp-1 { display: -webkit-box; -webkit-line-clamp: 1; -webkit-box-orient: vertical; overflow: hidden; }
        .line-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        .shop-shadow { box-shadow: 0 14px 36px rgba(25, 45, 91, .08); }

        /* Global Themes injected from Settings */
        @php
            $theme = $settings['system_theme'] ?? 'light';
        @endphp
        
        @if($theme === 'dark')
            :root { --admin-blue: #3b82f6; --admin-violet: #8b5cf6; --admin-navy: #0f172a; --bg-body: #0f172a; }
            body { background: var(--bg-body) !important; color: #f1f5f9 !important; }
            .bg-white, .bg-white\/95 { background-color: #1e293b !important; border-color: #334155 !important; color: #f1f5f9 !important; }
            .text-slate-800, .text-slate-700, .text-slate-900 { color: #f8fafc !important; }
            .text-slate-500, .text-slate-600 { color: #94a3b8 !important; }
            header, footer { border-color: #334155 !important; }
            .bg-slate-50, .bg-slate-100 { background-color: #0f172a !important; border-color: #334155 !important; }
        @elseif($theme === 'indigo')
            :root { --admin-blue: #3157dc; --admin-violet: #653fe0; --admin-navy: #0d1b3b; --bg-body: #f4f6fa; }
            body { background: var(--bg-body); }
        @elseif($theme === 'nature')
            :root { --admin-blue: #059669; --admin-violet: #10b981; --admin-navy: #064e3b; --bg-body: #f0fdf4; }
            body { background: var(--bg-body); }
        @elseif($theme === 'ocean')
            :root { --admin-blue: #0284c7; --admin-violet: #0369a1; --admin-navy: #082f49; --bg-body: #f0f9ff; }
            body { background: var(--bg-body); }
        @elseif($theme === 'sunset')
            :root { --admin-blue: #ea580c; --admin-violet: #dc2626; --admin-navy: #431407; --bg-body: #fff7ed; }
            body { background: var(--bg-body); }
        @elseif($theme === 'rose')
            :root { --admin-blue: #e11d48; --admin-violet: #be123c; --admin-navy: #4c0519; --bg-body: #fff1f2; }
            body { background: var(--bg-body); }
        @elseif($theme === 'monochrome')
            :root { --admin-blue: #475569; --admin-violet: #334155; --admin-navy: #0f172a; --bg-body: #f8fafc; }
            body { background: var(--bg-body); }
        @elseif($theme === 'neon')
            :root { --admin-blue: #c026d3; --admin-violet: #a21caf; --admin-navy: #000000; --bg-body: #000000; }
            body { background: var(--bg-body) !important; color: #fdf4ff !important; }
            .bg-white, .bg-white\/95 { background-color: #000000 !important; border: 1px solid #c026d3 !important; color: #fdf4ff !important; box-shadow: 0 0 20px rgba(192,38,211,.1) !important; }
            .text-slate-800, .text-slate-700, .text-slate-900 { color: #fdf4ff !important; text-shadow: 0 0 10px rgba(253,244,255,0.5); }
            .text-slate-500, .text-slate-600 { color: #f5d0fe !important; }
            header, footer { border-color: #c026d3 !important; }
            .bg-slate-50, .bg-slate-100 { background-color: #000000 !important; border-color: #c026d3 !important; }
        @elseif($theme === 'luxury')
            :root { --admin-blue: #d97706; --admin-violet: #b45309; --admin-navy: #18181b; --bg-body: #18181b; }
            body { background: var(--bg-body) !important; color: #fde68a !important; }
            .bg-white, .bg-white\/95 { background-color: #27272a !important; border-color: #d97706 !important; color: #fef3c7 !important; }
            .text-slate-800, .text-slate-700, .text-slate-900 { color: #fef3c7 !important; }
            .text-slate-500, .text-slate-600 { color: #fde68a !important; }
            header, footer { border-color: #d97706 !important; }
            .bg-slate-50, .bg-slate-100 { background-color: #18181b !important; border-color: #d97706 !important; }
        @endif
    </style>
    @yield('head')
</head>
<body class="min-h-screen antialiased">
    @php
        $wpNum = preg_replace('/[^0-9]/', '', \App\Models\Setting::where('key', 'whatsapp_number')->value('value') ?? '');
    @endphp

    <div class="bg-[#0b1730] text-white/80">
        <div class="mx-auto flex max-w-[1440px] items-center justify-between gap-3 px-4 py-2 text-[10px] sm:px-7 sm:text-xs">
            <span>
                <i class="fa-solid fa-truck-fast mr-2 text-indigo-300" aria-hidden="true"></i>
                {{ $topBannerText ?: 'Envíos a todo el Perú' }}
            </span>
            <a href="https://wa.me/{{ $wpNum }}" target="_blank" rel="noopener noreferrer" class="focus-ring flex items-center gap-2 hover:text-white"><i class="fa-brands fa-whatsapp text-emerald-400" aria-hidden="true"></i> Asesoría por WhatsApp</a>
        </div>
    </div>

    <header class="sticky top-0 z-40 border-b border-slate-200 bg-white/95 backdrop-blur">
        <div class="mx-auto max-w-[1440px] px-4 py-3 sm:px-7">
            <!-- Top Row: Brand & Actions (Mobile) -->
            <div class="flex items-center justify-between gap-4 lg:hidden">
                <div class="flex items-center gap-3">
                    <a href="{{ route('home') }}" class="focus-ring flex items-center gap-2.5">
                        @if($storeLogo)
                            <img src="{{ $storeLogo }}" alt="{{ $storeName }}" class="h-9 w-auto max-w-[120px] object-contain">
                        @else
                            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-[{{ $primaryColor }}] to-[#713ee7] text-lg font-black italic text-white shadow-lg">{{ substr($storeName, 0, 1) }}</span>
                        @endif
                        <span class="block font-display text-sm font-extrabold tracking-tight text-[#111c36] uppercase">{{ $storeName }}</span>
                    </a>
                </div>
                <div class="flex items-center gap-1.5">
                    <button type="button" data-open-wishlist class="focus-ring relative inline-flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 bg-white text-xs font-bold text-slate-700 transition hover:border-rose-200 hover:bg-rose-50 hover:text-rose-600" aria-label="Abrir favoritos" title="Mis Favoritos">
                        <i class="fa-regular fa-heart text-base text-rose-500"></i>
                        <span id="wishlist-count-mobile" style="display: none;" class="absolute -top-1.5 -right-1.5 flex h-5 min-w-5 items-center justify-center rounded-full bg-rose-500 px-1 text-[10px] font-extrabold text-white shadow-sm">0</span>
                    </button>
                    <button type="button" data-open-cart class="focus-ring relative inline-flex h-10 items-center gap-2 rounded-xl border border-slate-200 bg-white px-3 text-xs font-bold text-slate-700 transition hover:border-indigo-200 hover:bg-indigo-50 hover:text-indigo-700" aria-label="Abrir carrito">
                        <i class="fa-solid fa-bag-shopping text-base"></i><span id="cart-count" class="flex h-5 min-w-5 items-center justify-center rounded-full bg-indigo-600 px-1 text-[10px] font-extrabold text-white">0</span>
                    </button>
                </div>
            </div>

            <!-- Search Bar (Mobile & Desktop) & Desktop Layout -->
            <div class="mt-3 flex flex-col gap-3 lg:mt-0 lg:flex-row lg:items-center lg:justify-between">
                
                <!-- Desktop Brand (Links to Home) -->
                <div class="hidden lg:flex shrink-0 items-center gap-3">
                    <a href="{{ route('home') }}" class="focus-ring flex items-center gap-3 group">
                        @if($storeLogo)
                            <img src="{{ $storeLogo }}" alt="{{ $storeName }}" class="h-10 w-auto max-w-[150px] object-contain transition group-hover:scale-105">
                        @else
                            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-[{{ $primaryColor }}] to-[#713ee7] text-xl font-black italic text-white shadow-lg transition group-hover:scale-105">{{ substr($storeName, 0, 1) }}</div>
                        @endif
                        <div class="leading-tight">
                            <span class="block font-display text-base font-extrabold tracking-tight text-[#111c36] uppercase group-hover:text-indigo-600 transition">{{ $storeName }}</span>
                            <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest">Catálogo Oficial</span>
                        </div>
                    </a>
                </div>

                <!-- Search Bar (Styled Form) -->
                <form action="{{ route('catalog') }}" method="GET" role="search" class="flex h-11 flex-1 min-w-0 max-w-xl lg:mx-4 overflow-hidden rounded-2xl border border-slate-200/90 bg-slate-50 shadow-xs focus-within:border-indigo-500 focus-within:bg-white focus-within:ring-4 focus-within:ring-indigo-100 transition-all">
                    <label for="site-search" class="sr-only">Buscar productos</label>
                    <div class="flex items-center pl-3.5 text-slate-400">
                        <i class="fa-solid fa-magnifying-glass text-xs"></i>
                    </div>
                    <input id="site-search" type="search" name="q" value="{{ request('q') }}" placeholder="Busca productos, marcas y modelos..." class="min-w-0 flex-1 bg-transparent px-3 text-xs sm:text-sm text-slate-800 outline-none placeholder:text-slate-400 font-medium">
                    <button type="submit" aria-label="Buscar" style="background-color: {{ $primaryColor }}" class="focus-ring flex w-12 shrink-0 items-center justify-center text-white transition hover:opacity-90"><i class="fa-solid fa-arrow-right text-xs" aria-hidden="true"></i></button>
                </form>

                <!-- Actions (Desktop & Extra Mobile buttons) -->
                <div class="flex items-center gap-2 shrink-0 justify-between lg:justify-end">
                    <button type="button" id="theme-toggle" class="focus-ring flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-600 transition hover:border-indigo-200 hover:bg-indigo-50 hover:text-indigo-700 shadow-xs" aria-label="Cambiar tema" title="Cambiar tema">
                        <i class="fa-solid fa-moon dark:hidden text-sm"></i>
                        <i class="fa-solid fa-sun hidden dark:inline text-sm"></i>
                    </button>

                    <!-- Desktop Wishlist Button -->
                    <button type="button" data-open-wishlist class="focus-ring relative hidden h-10 shrink-0 items-center gap-2 rounded-xl border border-slate-200 bg-white px-3 text-xs font-bold text-slate-700 transition hover:border-rose-200 hover:bg-rose-50 hover:text-rose-600 lg:inline-flex shadow-xs" aria-label="Abrir favoritos" title="Mis Favoritos">
                        <i class="fa-regular fa-heart text-base text-rose-500"></i>
                        <span>Favoritos</span>
                        <span id="wishlist-count-desktop" style="display: none;" class="flex h-5 min-w-5 items-center justify-center rounded-full bg-rose-500 px-1 text-[10px] font-extrabold text-white shadow-sm">0</span>
                    </button>
                    
                    <!-- Desktop Cart -->
                    <button type="button" data-open-cart class="focus-ring relative hidden h-10 shrink-0 items-center gap-2 rounded-xl border border-slate-200 bg-white px-3 text-xs font-bold text-slate-700 transition hover:border-indigo-200 hover:bg-indigo-50 hover:text-indigo-700 lg:inline-flex shadow-xs" aria-label="Abrir carrito">
                        <i class="fa-solid fa-bag-shopping text-base"></i><span>Carrito</span><span id="cart-count-desktop" class="flex h-5 min-w-5 items-center justify-center rounded-full bg-indigo-600 px-1 text-[10px] font-extrabold text-white">0</span>
                    </button>

                    <a href="https://wa.me/{{ $wpNum }}?text={{ urlencode('Hola, quisiera información sobre sus equipos.') }}" target="_blank" rel="noopener noreferrer" class="focus-ring inline-flex h-10 shrink-0 items-center justify-center gap-2 rounded-xl bg-[#25d366] px-4 text-xs font-bold text-white shadow-md shadow-emerald-600/15 hover:bg-[#1fb85a] transition"><i class="fa-brands fa-whatsapp text-base" aria-hidden="true"></i><span>Consultar</span></a>
                </div>
            </div>
        </div>
        <nav aria-label="Navegación principal" class="border-t border-slate-100">
            <div class="hide-scrollbar mx-auto flex max-w-[1440px] items-center gap-2 overflow-x-auto px-4 py-2 sm:px-7">
                <a href="{{ route('catalog') }}" onclick="if(window.location.pathname.includes('/catalogo') && typeof toggleFilterDrawer === 'function' && window.innerWidth < 1024) { toggleFilterDrawer(true); return false; }" style="background-color: {{ $primaryColor }}" class="focus-ring flex shrink-0 items-center gap-2 rounded-lg px-4 py-2 text-xs font-bold text-white"><i class="fa-solid fa-bars" aria-hidden="true"></i>Todo el catálogo</a>
                <a href="{{ route('home') }}" class="focus-ring shrink-0 rounded-lg px-3.5 py-1.5 text-xs font-semibold text-slate-600 hover:bg-slate-100 hover:text-blue-700">Inicio</a>
                <a href="{{ route('catalog') }}" class="focus-ring shrink-0 rounded-lg px-3.5 py-1.5 text-xs font-semibold text-slate-600 hover:bg-slate-100 hover:text-blue-700">Equipos</a>
                <a href="{{ route('catalog', ['offers' => 1]) }}" class="focus-ring shrink-0 rounded-lg px-3.5 py-1.5 text-xs font-semibold text-slate-600 hover:bg-slate-100 hover:text-blue-700"><i class="fa-solid fa-bolt mr-1 text-amber-500" aria-hidden="true"></i>Ofertas</a>
                <a href="{{ route('home') }}#marcas" class="focus-ring shrink-0 rounded-lg px-3.5 py-1.5 text-xs font-semibold text-slate-600 hover:bg-slate-100 hover:text-blue-700">Marcas</a>
            </div>
        </nav>
    </header>

    @php
        $globalSideAds = \App\Models\Advertisement::where('status', true)->get();
        $explicitLeft = $globalSideAds->filter(fn($ad) => in_array($ad->location, ['sidebar_left', 'left']));
        $explicitRight = $globalSideAds->filter(fn($ad) => in_array($ad->location, ['sidebar_right', 'right']));
        $allLocationAds = $globalSideAds->filter(fn($ad) => in_array($ad->location, ['all', 'home', 'catalog']));

        if ($explicitLeft->isNotEmpty() || $explicitRight->isNotEmpty()) {
            $leftSideAds = $explicitLeft->concat($allLocationAds)->unique('id')->take(3);
            $rightSideAds = $explicitRight->concat($allLocationAds)->unique('id')->take(3);
        } else {
            if ($allLocationAds->count() === 1) {
                $leftSideAds = $allLocationAds->take(1);
                $rightSideAds = $allLocationAds->take(1);
            } else {
                $half = (int) ceil($allLocationAds->count() / 2);
                $leftSideAds = $allLocationAds->slice(0, $half)->take(3);
                $rightSideAds = $allLocationAds->slice($half)->take(3);
            }
        }
    @endphp

    <div class="mx-auto flex justify-center items-start w-full px-2 sm:px-4 max-w-[1920px]">
        <!-- Publicidad Lateral Izquierda (Espacio vacío izquierdo) -->
        @if($leftSideAds->isNotEmpty())
        <aside class="hidden xl:flex flex-col gap-4 w-40 2xl:w-48 shrink-0 sticky top-24 pt-3 mr-2 lg:mr-4 z-20" aria-label="Publicidad Lateral Izquierda">
            @foreach($leftSideAds as $ad)
            <a href="{{ $ad->link ?? '#' }}" class="group relative block overflow-hidden rounded-2xl border border-slate-200/90 bg-white p-2.5 shadow-md transition duration-300 hover:shadow-xl hover:scale-[1.03] hover:border-indigo-400">
                <div class="relative w-full h-44 sm:h-52 rounded-xl overflow-hidden bg-slate-50 flex items-center justify-center border border-slate-100 p-1">
                    @if($ad->isVideo())
                        <video src="{{ $ad->media_url }}" autoplay loop muted playsinline class="w-full h-full object-cover rounded-lg pointer-events-none"></video>
                    @else
                        <img src="{{ $ad->media_url }}" alt="{{ $ad->title ?? 'Publicidad' }}" class="w-full h-full object-contain rounded-lg transition-transform duration-300 group-hover:scale-105">
                    @endif
                    <span class="absolute top-1.5 left-1.5 bg-slate-900/60 backdrop-blur-sm text-white text-[8px] font-bold px-1.5 py-0.5 rounded shadow-sm">
                        <i class="fa-solid fa-bullhorn text-[7px] mr-1 text-amber-300"></i> Anuncio
                    </span>
                </div>
                @if($ad->title)
                <span class="block mt-2 text-center text-xs font-bold text-slate-800 group-hover:text-indigo-600 px-1 leading-snug">{{ $ad->title }}</span>
                @endif
            </a>
            @endforeach
        </aside>
        @endif

        <!-- Main Content -->
        <div class="w-full min-w-0 flex-1">
            <main id="contenido">
                @yield('content')
            </main>
        </div>

        <!-- Publicidad Lateral Derecha (Espacio vacío derecho) -->
        @if($rightSideAds->isNotEmpty())
        <aside class="hidden xl:flex flex-col gap-4 w-40 2xl:w-48 shrink-0 sticky top-24 pt-3 ml-2 lg:mr-4 z-20" aria-label="Publicidad Lateral Derecha">
            @foreach($rightSideAds as $ad)
            <a href="{{ $ad->link ?? '#' }}" class="group relative block overflow-hidden rounded-2xl border border-slate-200/90 bg-white p-2.5 shadow-md transition duration-300 hover:shadow-xl hover:scale-[1.03] hover:border-indigo-400">
                <div class="relative w-full h-44 sm:h-52 rounded-xl overflow-hidden bg-slate-50 flex items-center justify-center border border-slate-100 p-1">
                    @if($ad->isVideo())
                        <video src="{{ $ad->media_url }}" autoplay loop muted playsinline class="w-full h-full object-cover rounded-lg pointer-events-none"></video>
                    @else
                        <img src="{{ $ad->media_url }}" alt="{{ $ad->title ?? 'Publicidad' }}" class="w-full h-full object-contain rounded-lg transition-transform duration-300 group-hover:scale-105">
                    @endif
                    <span class="absolute top-1.5 left-1.5 bg-slate-900/60 backdrop-blur-sm text-white text-[8px] font-bold px-1.5 py-0.5 rounded shadow-sm">
                        <i class="fa-solid fa-bullhorn text-[7px] mr-1 text-amber-300"></i> Anuncio
                    </span>
                </div>
                @if($ad->title)
                <span class="block mt-2 text-center text-xs font-bold text-slate-800 group-hover:text-indigo-600 px-1 leading-snug">{{ $ad->title }}</span>
                @endif
            </a>
            @endforeach
        </aside>
        @endif
    </div>

    <dialog id="cart-dialog" class="w-[min(100%-1rem,680px)] max-h-[92vh] overflow-y-auto rounded-3xl border-0 p-0 shadow-2xl backdrop:bg-slate-950/60">
        <div class="sticky top-0 z-10 flex items-center justify-between border-b border-slate-100 bg-white/95 px-5 py-4 backdrop-blur sm:px-7">
            <div><p class="text-[9px] font-extrabold uppercase tracking-[.18em] text-indigo-600">Tu selección</p><h2 class="mt-1 font-display text-xl font-extrabold text-[#142143]">Carrito de compras</h2></div>
            <button type="button" data-close-cart class="focus-ring flex h-10 w-10 items-center justify-center rounded-full bg-slate-100 text-slate-600 hover:bg-slate-200" aria-label="Cerrar carrito"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <div class="p-5 sm:p-7">
            <div id="cart-items" class="space-y-3"></div>
            <p id="cart-empty" class="hidden rounded-2xl bg-slate-50 px-4 py-10 text-center text-sm text-slate-500">Tu carrito está vacío. Agrega un equipo para continuar.</p>
            <div id="cart-checkout" class="mt-5 hidden">
                <div class="flex items-center justify-between border-t border-slate-100 py-4">
                    <div>
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total a pagar</span>
                        <p class="text-[11px] text-slate-400">Sin comisiones adicionales</p>
                    </div>
                    <strong id="cart-total" class="font-display text-2xl font-black text-slate-900">S/ 0.00</strong>
                </div>

                <form id="cart-checkout-form" class="space-y-4" action="{{ route('api.checkout') }}" method="POST">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    
                    <div class="grid gap-3 sm:grid-cols-2">
                        <!-- Nombre -->
                        <div>
                            <label for="checkout-name" class="mb-1.5 block text-xs font-bold text-slate-700">Nombre completo</label>
                            <div class="flex h-11 items-center rounded-xl border border-slate-200 bg-slate-50 focus-within:border-indigo-500 focus-within:bg-white focus-within:ring-4 focus-within:ring-indigo-100 transition shadow-xs overflow-hidden">
                                <span class="flex h-full w-10 shrink-0 items-center justify-center text-slate-400 border-r border-slate-100 bg-slate-50/50">
                                    <i class="fa-solid fa-user text-xs"></i>
                                </span>
                                <input id="checkout-name" name="client_name" required maxlength="255" autocomplete="name" placeholder="Ej: Carlos Mendoza" class="min-w-0 flex-1 bg-transparent px-3 text-xs sm:text-sm font-medium text-slate-800 outline-none placeholder:text-slate-400">
                            </div>
                        </div>

                        <!-- Celular -->
                        <div>
                            <label for="checkout-phone" class="mb-1.5 block text-xs font-bold text-slate-700">Número celular (WhatsApp)</label>
                            <div class="flex h-11 items-center rounded-xl border border-slate-200 bg-slate-50 focus-within:border-indigo-500 focus-within:bg-white focus-within:ring-4 focus-within:ring-indigo-100 transition shadow-xs overflow-hidden">
                                <span class="flex h-full items-center gap-1.5 px-3 text-xs font-bold text-slate-600 border-r border-slate-100 bg-slate-50/50 shrink-0">
                                    <span>🇵🇪</span> +51
                                </span>
                                <input id="checkout-phone" name="client_phone" required inputmode="tel" autocomplete="tel" pattern="[0-9+ ().-]{7,20}" placeholder="987 654 321" class="min-w-0 flex-1 bg-transparent px-3 text-xs sm:text-sm font-medium text-slate-800 outline-none placeholder:text-slate-400">
                            </div>
                        </div>
                    </div>

                    <div class="grid gap-3 sm:grid-cols-2">
                        <!-- Comprobante -->
                        <div>
                            <label for="receipt-type" class="mb-1.5 block text-xs font-bold text-slate-700">Tipo de comprobante</label>
                            <div class="relative flex h-11 items-center rounded-xl border border-slate-200 bg-slate-50 focus-within:border-indigo-500 focus-within:bg-white focus-within:ring-4 focus-within:ring-indigo-100 transition shadow-xs overflow-hidden">
                                <span class="flex h-full w-10 shrink-0 items-center justify-center text-indigo-500 border-r border-slate-100 bg-slate-50/50">
                                    <i class="fa-solid fa-receipt text-xs"></i>
                                </span>
                                <select id="receipt-type" name="receipt_type" required class="min-w-0 flex-1 bg-transparent px-3 text-xs sm:text-sm font-medium text-slate-800 outline-none cursor-pointer">
                                    <option value="boleta">Boleta de Venta</option>
                                    <option value="factura">Factura Comercial</option>
                                </select>
                            </div>
                        </div>

                        <!-- RUC -->
                        <div id="tax-id-wrap" class="hidden">
                            <label for="checkout-tax-id" class="mb-1.5 block text-xs font-bold text-slate-700">RUC para la factura</label>
                            <div class="flex h-11 items-center rounded-xl border border-slate-200 bg-slate-50 focus-within:border-indigo-500 focus-within:bg-white focus-within:ring-4 focus-within:ring-indigo-100 transition shadow-xs overflow-hidden">
                                <span class="flex h-full w-10 shrink-0 items-center justify-center text-amber-500 border-r border-slate-100 bg-slate-50/50">
                                    <i class="fa-solid fa-building text-xs"></i>
                                </span>
                                <input id="checkout-tax-id" name="tax_id" inputmode="numeric" maxlength="11" pattern="[0-9]{11}" placeholder="11 dígitos (20...)" class="min-w-0 flex-1 bg-transparent px-3 text-xs sm:text-sm font-mono text-slate-800 outline-none placeholder:text-slate-400">
                            </div>
                        </div>
                    </div>

                    <!-- Nota de venta -->
                    <details class="group rounded-2xl border border-slate-200 bg-slate-50/80 p-3.5 transition open:bg-white open:shadow-xs">
                        <summary class="focus-ring flex cursor-pointer list-none items-center justify-between text-xs font-bold text-slate-700">
                            <span class="flex items-center gap-2">
                                <i class="fa-regular fa-note-sticky text-indigo-600"></i>
                                ¿Deseas agregar una nota o detalle a tu pedido?
                            </span>
                            <i class="fa-solid fa-chevron-down text-[10px] text-slate-400 transition group-open:rotate-180"></i>
                        </summary>
                        <textarea id="sale-note" name="sale_note" rows="2" maxlength="1000" placeholder="Ej: Entregar por la tarde, coordinar envío a provincia..." class="mt-3 w-full rounded-xl border border-slate-200 bg-white p-3 text-xs text-slate-800 outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition resize-y"></textarea>
                    </details>

                    <p id="cart-error" class="hidden rounded-xl bg-rose-50 px-4 py-3 text-xs font-semibold text-rose-700 border border-rose-200" role="alert"></p>

                    <button id="checkout-submit" type="submit" class="focus-ring flex min-h-12 w-full items-center justify-center gap-2.5 rounded-2xl bg-gradient-to-r from-[#20ba5a] to-[#25d366] hover:from-[#1ca44e] hover:to-[#20ba5a] px-5 py-3 text-sm font-black text-white shadow-lg shadow-emerald-600/25 transition active:scale-[0.98] cursor-pointer">
                        <i class="fa-brands fa-whatsapp text-xl"></i>
                        <span>Confirmar y Enviar por WhatsApp</span>
                    </button>
                    <p class="text-center text-[11px] text-slate-400 font-medium">
                        <i class="fa-solid fa-lock text-[10px] text-slate-300 mr-1"></i> Pedido 100% seguro sin pago previo online
                    </p>
                </form>
            </div>
        </div>
    </dialog>

    <dialog id="wishlist-dialog" class="w-[min(100%-1rem,680px)] max-h-[92vh] overflow-y-auto rounded-3xl border-0 p-0 shadow-2xl backdrop:bg-slate-950/60">
        <div class="sticky top-0 z-10 flex items-center justify-between border-b border-slate-100 bg-white/95 px-5 py-4 backdrop-blur sm:px-7">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-rose-50 text-rose-500">
                    <i class="fa-solid fa-heart text-lg"></i>
                </div>
                <div>
                    <p class="text-[9px] font-extrabold uppercase tracking-[.18em] text-rose-500">Guardados</p>
                    <h2 class="mt-0.5 font-display text-xl font-extrabold text-[#142143]">Mis Favoritos</h2>
                </div>
            </div>
            <button type="button" data-close-wishlist class="focus-ring flex h-10 w-10 items-center justify-center rounded-full bg-slate-100 text-slate-600 hover:bg-slate-200" aria-label="Cerrar favoritos">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <div class="p-5 sm:p-7">
            <div id="wishlist-items" class="space-y-3"></div>
            <div id="wishlist-empty" class="hidden flex-col items-center justify-center rounded-2xl bg-slate-50 px-4 py-12 text-center">
                <div class="flex h-16 w-16 items-center justify-center rounded-full bg-rose-50 text-rose-300 text-3xl mb-3">
                    <i class="fa-regular fa-heart"></i>
                </div>
                <h3 class="text-sm font-bold text-slate-700">No tienes productos en favoritos</h3>
                <p class="mt-1 max-w-xs text-xs text-slate-500">Haz clic en el corazoncito de cualquier producto para guardarlo aquí y consultarlo cuando quieras.</p>
                <a href="{{ route('catalog') }}" class="mt-5 inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-5 py-2.5 text-xs font-bold text-white hover:bg-indigo-700 transition">
                    <i class="fa-solid fa-store"></i> Explorar Catálogo
                </a>
            </div>
        </div>
    </dialog>

    <!-- Cart Added Toast Notification (Smooth & Non-blocking) -->
    <div id="cart-toast" class="fixed bottom-6 right-6 z-50 flex items-center gap-3.5 rounded-2xl bg-slate-900/95 text-white px-4 py-3 shadow-2xl backdrop-blur transition-all duration-300 transform translate-y-20 opacity-0 pointer-events-none border border-white/10">
        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-500/20 text-emerald-400 shrink-0 text-lg">
            <i class="fa-solid fa-cart-shopping"></i>
        </div>
        <div class="pr-1 min-w-0 max-w-[220px]">
            <p class="text-xs font-black leading-tight text-white flex items-center gap-1.5">
                ¡Agregado al Carrito! <span class="inline-block w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
            </p>
            <p id="cart-toast-name" class="text-[11px] text-slate-300 truncate mt-0.5 font-medium"></p>
        </div>
        <button type="button" data-open-cart class="shrink-0 ml-1 px-3 py-1.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-[11px] font-extrabold shadow-sm transition active:scale-95 cursor-pointer">
            Ver Carrito
        </button>
    </div>

    <!-- Wishlist Toast Notification -->
    <div id="wishlist-toast" class="fixed bottom-6 right-6 z-50 flex items-center gap-3 rounded-2xl bg-slate-900/95 text-white px-4 py-3 shadow-2xl backdrop-blur transition-all duration-300 transform translate-y-20 opacity-0 pointer-events-none border border-white/10">
        <div id="wishlist-toast-icon" class="flex h-9 w-9 items-center justify-center rounded-xl bg-rose-500/20 text-rose-400 shrink-0 text-base">
            <i class="fa-solid fa-heart"></i>
        </div>
        <div class="pr-2">
            <p id="wishlist-toast-msg" class="text-xs font-bold leading-tight"></p>
            <p id="wishlist-toast-sub" class="text-[10px] text-slate-400 line-clamp-1 mt-0.5"></p>
        </div>
    </div>

    <footer class="mt-16 bg-[#0b1730] text-white">
        <div class="mx-auto grid max-w-[1440px] gap-8 px-4 py-10 sm:px-7 md:grid-cols-[1.3fr_.7fr_.7fr] md:py-14">
            <div>
                <a href="{{ route('home') }}" class="flex w-fit items-center gap-3"><span class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-[{{ $primaryColor }}] to-violet-600 font-black italic">{{ substr($storeName, 0, 1) }}</span><span class="font-display text-sm font-extrabold uppercase">{{ $storeName }}</span></a>
                <p class="mt-4 max-w-sm text-xs leading-6 text-slate-400">Equipos y tecnología para trabajar, estudiar y crear. Nuestro equipo está listo para ayudarte a elegir.</p>
            </div>
            <div><h2 class="text-xs font-bold text-white">Explora</h2><a href="{{ route('catalog') }}" class="mt-4 block text-xs text-slate-400 hover:text-white">Catálogo completo</a><a href="{{ route('catalog', ['offers' => 1]) }}" class="mt-3 block text-xs text-slate-400 hover:text-white">Ofertas</a></div>
            <div><h2 class="text-xs font-bold text-white">Atención</h2><p class="mt-4 text-xs text-slate-400">Lima, Perú · Envíos nacionales</p><a href="https://wa.me/{{ $wpNum }}" target="_blank" rel="noopener noreferrer" class="mt-3 inline-flex items-center gap-2 text-xs font-semibold text-emerald-400 hover:text-emerald-300"><i class="fa-brands fa-whatsapp"></i> WhatsApp</a>
                @if($facebookUrl || $instagramUrl)
                <div class="mt-4 flex gap-3">
                    @if($facebookUrl)<a href="{{ $facebookUrl }}" target="_blank" class="text-slate-400 hover:text-white"><i class="fa-brands fa-facebook text-lg"></i></a>@endif
                    @if($instagramUrl)<a href="{{ $instagramUrl }}" target="_blank" class="text-slate-400 hover:text-white"><i class="fa-brands fa-instagram text-lg"></i></a>@endif
                </div>
                @endif
            </div>
        </div>
        <div class="border-t border-white/10"><div class="mx-auto max-w-[1440px] px-4 py-4 text-[10px] text-slate-500 sm:px-7">© {{ date('Y') }} {{ $storeName }}. Todos los derechos reservados.</div></div>
    </footer>
    <script>
        (() => {
            const storageKey = 'mpc-shopping-cart';
            const wishlistStorageKey = 'mpc-wishlist';
            const dialog = document.getElementById('cart-dialog');
            const wishlistDialog = document.getElementById('wishlist-dialog');
            const itemsContainer = document.getElementById('cart-items');
            const countBadges = document.querySelectorAll('#cart-count, #cart-count-desktop');
            const checkoutForm = document.getElementById('cart-checkout-form');
            const checkoutPanel = document.getElementById('cart-checkout');
            const emptyMessage = document.getElementById('cart-empty');
            const errorMessage = document.getElementById('cart-error');
            const currency = new Intl.NumberFormat('es-PE', { style: 'currency', currency: 'PEN' });
            
            let cart = [];
            let wishlist = [];

            try { cart = JSON.parse(localStorage.getItem(storageKey) || '[]'); } catch { cart = []; }
            if (!Array.isArray(cart)) cart = [];

            try { wishlist = JSON.parse(localStorage.getItem(wishlistStorageKey) || '[]'); } catch { wishlist = []; }
            if (!Array.isArray(wishlist)) wishlist = [];

            const escapeHtml = (value) => String(value).replace(/[&<>"']/g, (character) => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' })[character]);
            const saveCart = () => localStorage.setItem(storageKey, JSON.stringify(cart));
            const saveWishlist = () => localStorage.setItem(wishlistStorageKey, JSON.stringify(wishlist));

            const renderCart = () => {
                const itemCount = cart.reduce((sum, item) => sum + item.quantity, 0);
                countBadges.forEach(badge => badge.textContent = itemCount);
                itemsContainer.innerHTML = cart.map((item) => `
                    <article class="flex gap-3 rounded-2xl border border-slate-200 p-3">
                        <div class="flex h-20 w-20 shrink-0 items-center justify-center overflow-hidden rounded-xl bg-slate-50">${item.image ? `<img src="${escapeHtml(item.image)}" alt="" class="h-full w-full object-contain">` : '<i class="fa-solid fa-laptop text-2xl text-indigo-400"></i>'}</div>
                        <div class="min-w-0 flex-1"><a href="${escapeHtml(item.url)}" class="line-clamp-2 text-xs font-bold text-slate-800 hover:text-indigo-700">${escapeHtml(item.name)}</a><p class="mt-1 text-xs font-extrabold text-indigo-700">${currency.format(item.price)}</p><div class="mt-2 flex items-center gap-2"><button type="button" data-cart-action="decrease" data-product-id="${item.product_id}" class="h-7 w-7 rounded-lg border border-slate-200 text-slate-600" aria-label="Reducir cantidad">−</button><span class="min-w-5 text-center text-xs font-bold">${item.quantity}</span><button type="button" data-cart-action="increase" data-product-id="${item.product_id}" class="h-7 w-7 rounded-lg border border-slate-200 text-slate-600" aria-label="Aumentar cantidad">+</button><button type="button" data-cart-action="remove" data-product-id="${item.product_id}" class="ml-auto px-2 text-[10px] font-bold text-rose-600">Quitar</button></div></div>
                    </article>`).join('');
                const total = cart.reduce((sum, item) => sum + item.price * item.quantity, 0);
                document.getElementById('cart-total').textContent = currency.format(total);
                emptyMessage.classList.toggle('hidden', cart.length > 0);
                checkoutPanel.classList.toggle('hidden', cart.length === 0);
            };

            const showWishlistToast = (msg, sub, isAdded) => {
                const toast = document.getElementById('wishlist-toast');
                const toastMsg = document.getElementById('wishlist-toast-msg');
                const toastSub = document.getElementById('wishlist-toast-sub');
                const toastIcon = document.getElementById('wishlist-toast-icon');
                if (!toast) return;

                toastMsg.textContent = msg;
                toastSub.textContent = sub;
                if (isAdded) {
                    toastIcon.innerHTML = '<i class="fa-solid fa-heart text-rose-500"></i>';
                } else {
                    toastIcon.innerHTML = '<i class="fa-regular fa-heart text-slate-400"></i>';
                }

                toast.classList.remove('translate-y-20', 'opacity-0', 'pointer-events-none');
                toast.classList.add('translate-y-0', 'opacity-100');

                clearTimeout(window.wishlistToastTimeout);
                window.wishlistToastTimeout = setTimeout(() => {
                    toast.classList.add('translate-y-20', 'opacity-0', 'pointer-events-none');
                    toast.classList.remove('translate-y-0', 'opacity-100');
                }, 2800);
            };

            const showCartToast = (productName) => {
                const toast = document.getElementById('cart-toast');
                const toastName = document.getElementById('cart-toast-name');
                if (!toast) return;

                if (toastName) toastName.textContent = productName || 'Producto añadido al carrito';

                toast.classList.remove('translate-y-20', 'opacity-0', 'pointer-events-none');
                toast.classList.add('translate-y-0', 'opacity-100');

                clearTimeout(window.cartToastTimeout);
                window.cartToastTimeout = setTimeout(() => {
                    toast.classList.add('translate-y-20', 'opacity-0', 'pointer-events-none');
                    toast.classList.remove('translate-y-0', 'opacity-100');
                }, 3200);
            };

            const updateWishlistUI = () => {
                const count = wishlist.length;
                document.querySelectorAll('#wishlist-count-mobile, #wishlist-count-desktop').forEach(badge => {
                    badge.textContent = count;
                    badge.style.display = count > 0 ? 'flex' : 'none';
                });

                // Update heart icons across the page
                document.querySelectorAll('[data-wishlist-toggle]').forEach(btn => {
                    const id = Number(btn.dataset.productId);
                    const isFav = wishlist.some(item => item.product_id === id);
                    const icon = btn.querySelector('i');
                    if (isFav) {
                        btn.classList.add('bg-rose-50', 'text-rose-500', 'border-rose-200');
                        btn.classList.remove('text-slate-400', 'bg-white/90');
                        if (icon) {
                            icon.classList.remove('fa-regular');
                            icon.classList.add('fa-solid', 'text-rose-500');
                        }
                    } else {
                        btn.classList.remove('bg-rose-50', 'text-rose-500', 'border-rose-200');
                        btn.classList.add('text-slate-400', 'bg-white/90');
                        if (icon) {
                            icon.classList.remove('fa-solid', 'text-rose-500');
                            icon.classList.add('fa-regular');
                        }
                    }
                });

                // Render Wishlist Dialog items
                const wishlistContainer = document.getElementById('wishlist-items');
                const wishlistEmpty = document.getElementById('wishlist-empty');
                if (wishlistContainer && wishlistEmpty) {
                    if (wishlist.length === 0) {
                        wishlistContainer.innerHTML = '';
                        wishlistEmpty.classList.remove('hidden');
                        wishlistEmpty.classList.add('flex');
                    } else {
                        wishlistEmpty.classList.add('hidden');
                        wishlistEmpty.classList.remove('flex');
                        wishlistContainer.innerHTML = wishlist.map(item => `
                            <article class="flex items-center gap-3 rounded-2xl border border-slate-200 p-3 bg-white hover:border-rose-200 transition">
                                <div class="flex h-16 w-16 shrink-0 items-center justify-center overflow-hidden rounded-xl bg-slate-50 border border-slate-100">
                                    ${item.image ? `<img src="${escapeHtml(item.image)}" alt="" class="h-full w-full object-contain p-1">` : '<i class="fa-solid fa-laptop text-xl text-indigo-400"></i>'}
                                </div>
                                <div class="min-w-0 flex-1">
                                    <a href="${escapeHtml(item.url)}" class="line-clamp-1 text-xs font-bold text-slate-800 hover:text-indigo-700">${escapeHtml(item.name)}</a>
                                    <p class="mt-0.5 text-xs font-extrabold text-indigo-700">${currency.format(item.price)}</p>
                                </div>
                                <div class="flex items-center gap-1.5 shrink-0">
                                    <button type="button" 
                                            data-add-to-cart 
                                            data-product-id="${item.product_id}" 
                                            data-product-name="${escapeHtml(item.name)}" 
                                            data-product-price="${item.price}" 
                                            data-product-stock="10" 
                                            data-product-image="${escapeHtml(item.image || '')}" 
                                            data-product-url="${escapeHtml(item.url)}" 
                                            class="flex h-8 items-center gap-1 rounded-lg bg-indigo-50 px-2.5 text-[10px] font-bold text-indigo-700 hover:bg-indigo-100 transition" 
                                            title="Agregar al carrito">
                                        <i class="fa-solid fa-bag-shopping"></i>
                                        <span class="hidden sm:inline">Al carrito</span>
                                    </button>
                                    <button type="button" 
                                            data-wishlist-remove="${item.product_id}" 
                                            class="flex h-8 w-8 items-center justify-center rounded-lg text-slate-400 hover:bg-rose-50 hover:text-rose-600 transition" 
                                            title="Quitar de favoritos">
                                        <i class="fa-solid fa-trash-can text-xs"></i>
                                    </button>
                                </div>
                            </article>
                        `).join('');
                    }
                }
            };

            document.addEventListener('click', (event) => {
                if (event.target.closest('[data-open-cart]')) dialog.showModal();
                if (event.target.closest('[data-close-cart]')) dialog.close();

                if (event.target.closest('[data-open-wishlist]')) {
                    updateWishlistUI();
                    if (wishlistDialog) wishlistDialog.showModal();
                }
                if (event.target.closest('[data-close-wishlist]')) {
                    if (wishlistDialog) wishlistDialog.close();
                }

                const wishlistToggleBtn = event.target.closest('[data-wishlist-toggle]');
                if (wishlistToggleBtn) {
                    event.preventDefault();
                    event.stopPropagation();

                    const id = Number(wishlistToggleBtn.dataset.productId);
                    const name = wishlistToggleBtn.dataset.productName || 'Producto';
                    const price = Number(wishlistToggleBtn.dataset.productPrice || 0);
                    const image = wishlistToggleBtn.dataset.productImage || '';
                    const url = wishlistToggleBtn.dataset.productUrl || '#';

                    const index = wishlist.findIndex(item => item.product_id === id);
                    if (index > -1) {
                        wishlist.splice(index, 1);
                        saveWishlist();
                        updateWishlistUI();
                        showWishlistToast('Eliminado de Favoritos', name, false);
                    } else {
                        wishlist.push({ product_id: id, name, price, image, url });
                        saveWishlist();
                        updateWishlistUI();
                        showWishlistToast('¡Añadido a Favoritos! ❤️', name, true);
                    }
                }

                const wishlistRemoveBtn = event.target.closest('[data-wishlist-remove]');
                if (wishlistRemoveBtn) {
                    const id = Number(wishlistRemoveBtn.dataset.wishlistRemove);
                    const item = wishlist.find(i => i.product_id === id);
                    wishlist = wishlist.filter(i => i.product_id !== id);
                    saveWishlist();
                    updateWishlistUI();
                    if (item) showWishlistToast('Eliminado de Favoritos', item.name, false);
                }

                const addButton = event.target.closest('[data-add-to-cart]');
                if (addButton) {
                    const product = {
                        product_id: Number(addButton.dataset.productId),
                        name: addButton.dataset.productName,
                        price: Number(addButton.dataset.productPrice),
                        stock: Number(addButton.dataset.productStock),
                        image: addButton.dataset.productImage || null,
                        url: addButton.dataset.productUrl,
                    };
                    const existing = cart.find((item) => item.product_id === product.product_id);
                    const requestedQuantity = Number(addButton.closest('[data-product-quantity]')?.querySelector('input')?.value || 1);
                    if (existing) existing.quantity = Math.min(product.stock, existing.quantity + requestedQuantity);
                    else cart.push({ ...product, quantity: Math.min(product.stock, requestedQuantity) });
                    saveCart(); 
                    renderCart();

                    // Visual feedback on the button
                    const origHtml = addButton.innerHTML;
                    addButton.classList.add('!bg-emerald-600', '!text-white', '!border-emerald-600');
                    addButton.innerHTML = '<i class="fa-solid fa-check text-xs"></i> <span class="font-bold">¡Agregado!</span>';
                    setTimeout(() => {
                        addButton.innerHTML = origHtml;
                        addButton.classList.remove('!bg-emerald-600', '!text-white', '!border-emerald-600');
                    }, 1400);

                    // Pulse/Scale animation on header cart badges
                    countBadges.forEach(badge => {
                        badge.classList.remove('scale-125', 'bg-emerald-500');
                        void badge.offsetWidth;
                        badge.classList.add('scale-125', 'bg-emerald-500', 'transition-transform');
                        setTimeout(() => badge.classList.remove('scale-125', 'bg-emerald-500'), 500);
                    });

                    // Toast notification to avoid interrupting shopping
                    showCartToast(product.name);
                }

                const action = event.target.closest('[data-cart-action]');
                if (action) {
                    const item = cart.find((entry) => entry.product_id === Number(action.dataset.productId));
                    if (!item) return;
                    if (action.dataset.cartAction === 'remove') cart = cart.filter((entry) => entry !== item);
                    if (action.dataset.cartAction === 'increase') item.quantity = Math.min(item.stock, item.quantity + 1);
                    if (action.dataset.cartAction === 'decrease') item.quantity -= 1;
                    cart = cart.filter((entry) => entry.quantity > 0);
                    saveCart(); renderCart();
                }
            });

            document.getElementById('receipt-type').addEventListener('change', (event) => {
                const isInvoice = event.target.value === 'factura';
                document.getElementById('tax-id-wrap').classList.toggle('hidden', !isInvoice);
                document.getElementById('checkout-tax-id').required = isInvoice;
            });

            checkoutForm.addEventListener('submit', async (event) => {
                event.preventDefault();
                errorMessage.classList.add('hidden');
                const submitButton = document.getElementById('checkout-submit');
                const whatsappWindow = window.open('about:blank', '_blank');
                submitButton.disabled = true;
                submitButton.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Guardando pedido…';

                try {
                    const payload = Object.fromEntries(new FormData(checkoutForm).entries());
                    payload.cart = cart.map(({ product_id, quantity }) => ({ product_id, quantity }));
                    const response = await fetch(checkoutForm.action, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': payload._token },
                        body: JSON.stringify(payload),
                    });
                    const result = await response.json();
                    if (!response.ok) throw new Error(Object.values(result.errors || {}).flat()[0] || result.message || 'No se pudo guardar el pedido.');
                    cart = []; saveCart(); renderCart(); checkoutForm.reset();
                    dialog.close();
                    if (whatsappWindow) whatsappWindow.location = result.whatsapp_url;
                    else window.location.href = result.whatsapp_url;
                } catch (error) {
                    if (whatsappWindow) whatsappWindow.close();
                    errorMessage.textContent = error.message;
                    errorMessage.classList.remove('hidden');
                } finally {
                    submitButton.disabled = false;
                    submitButton.innerHTML = '<i class="fa-brands fa-whatsapp text-lg"></i>Confirmar y enviar por WhatsApp';
                }
            });

            // Theme Toggle
            const themeToggleBtn = document.getElementById('theme-toggle');
            if (themeToggleBtn) {
                themeToggleBtn.addEventListener('click', function() {
                    if (document.documentElement.classList.contains('dark')) {
                        document.documentElement.classList.remove('dark');
                        localStorage.setItem('theme', 'light');
                    } else {
                        document.documentElement.classList.add('dark');
                        localStorage.setItem('theme', 'dark');
                    }
                });
            }

            renderCart();
            updateWishlistUI();
        })();
    </script>
</body>
</html>
