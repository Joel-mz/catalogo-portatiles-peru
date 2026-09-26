@extends('layouts.public')

@section('title', 'Tecnología para avanzar')
@section('meta_description', 'Encuentra laptops y tecnología seleccionada por ' . ($settings['store_name'] ?? 'Portátiles Perú') . '. Compra y recibe asesoría directa por WhatsApp.')

@section('content')
@php
    $settings = \App\Models\Setting::pluck('value', 'key');
    $leadProduct = $featuredProducts->first() ?? $offerProducts->first();
    $leadImage = $leadProduct?->images->first()?->image_path;
    $whatsappNumber = preg_replace('/[^0-9]/', '', $settings['whatsapp_number'] ?? '');
    $leadPrice = $leadProduct?->is_offer && $leadProduct?->offer_price ? $leadProduct->offer_price : $leadProduct?->price;
    $discount = $leadProduct && $leadProduct->price > 0 && $leadProduct->offer_price ? round((1 - ($leadProduct->offer_price / $leadProduct->price)) * 100) : null;
@endphp

<div class="mx-auto max-w-[1440px] space-y-10 px-4 py-6 sm:space-y-12 sm:px-7 sm:py-8">
    
    <!-- Hero Section (Antixor 3-Column Layout: Categories / Center Hero / Side Promos) -->
    <section class="grid gap-4 lg:grid-cols-[230px_minmax(0,1fr)_230px]" aria-label="Descubre el catálogo">
        
        <!-- Left Column: Category Sidebar -->
        <aside class="hidden rounded-2xl border border-slate-200/80 bg-white p-4 shadow-sm lg:flex lg:flex-col lg:justify-between">
            <div>
                <div class="mb-3 flex items-center justify-between border-b border-slate-100 pb-3">
                    <h2 class="text-xs font-black uppercase tracking-wider text-slate-900 flex items-center gap-2">
                        <i class="fa-solid fa-layer-group text-indigo-600"></i>
                        Categorías
                    </h2>
                </div>
                <div class="space-y-1">
                    @forelse($categories->take(8) as $category)
                        @php
                            $categoryName = strtolower($category->name);
                            $icon = str_contains($categoryName, 'laptop') ? 'fa-laptop' : (str_contains($categoryName, 'gaming') ? 'fa-gamepad' : (str_contains($categoryName, 'audio') || str_contains($categoryName, 'parlante') ? 'fa-headphones' : (str_contains($categoryName, 'accesorio') ? 'fa-plug' : (str_contains($categoryName, 'celular') || str_contains($categoryName, 'smartphone') ? 'fa-mobile-screen' : 'fa-microchip'))));
                        @endphp
                        <a href="{{ route('catalog', ['category' => $category->slug]) }}" class="group flex items-center justify-between rounded-xl px-2.5 py-2 text-xs font-medium text-slate-600 transition hover:bg-indigo-50 hover:text-indigo-700">
                            <div class="flex items-center gap-2.5">
                                <i class="fa-solid {{ $icon }} w-4 text-center text-slate-400 group-hover:text-indigo-600 transition"></i>
                                <span>{{ $category->name }}</span>
                            </div>
                            <i class="fa-solid fa-chevron-right text-[9px] text-slate-300 group-hover:text-indigo-600 group-hover:translate-x-0.5 transition"></i>
                        </a>
                    @empty
                        <p class="py-3 text-xs text-slate-400">Nuevas categorías pronto.</p>
                    @endforelse
                </div>
            </div>
            <a href="{{ route('catalog') }}" class="mt-4 block rounded-xl border border-slate-100 bg-slate-50 py-2.5 text-center text-xs font-bold text-indigo-600 transition hover:bg-indigo-50 hover:text-indigo-700">
                Ver todas <i class="fa-solid fa-arrow-right text-[10px] ml-1"></i>
            </a>
        </aside>

        <!-- Center Column: Antixor Dark Sleek Hero Banner -->
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-[#0a0f1d] via-[#111a33] to-[#1c1836] text-white shadow-xl min-h-[420px] flex flex-col justify-between p-6 sm:p-10">
            <!-- Background Glow Highlights -->
            <div class="pointer-events-none absolute right-0 top-0 h-[350px] w-[350px] rounded-full bg-indigo-500/20 blur-3xl"></div>
            <div class="pointer-events-none absolute bottom-0 left-1/3 h-[250px] w-[250px] rounded-full bg-cyan-500/15 blur-3xl"></div>

            <div class="relative z-10 grid gap-6 md:grid-cols-[1.1fr_0.9fr] items-center h-full">
                <!-- Text Content -->
                <div class="flex flex-col items-start justify-center">
                    <span class="inline-flex items-center gap-1.5 rounded-full border border-indigo-400/30 bg-indigo-500/10 px-3 py-1 text-[10px] font-bold uppercase tracking-widest text-indigo-300">
                        <span class="h-1.5 w-1.5 rounded-full bg-indigo-400 animate-pulse"></span>
                        LATEST COLLECTION
                    </span>
                    <h1 class="mt-4 font-display text-3xl font-extrabold leading-[1.08] tracking-tight text-white sm:text-4xl lg:text-5xl">
                        Upgrade Your Everyday <span class="bg-gradient-to-r from-indigo-300 via-purple-300 to-pink-300 bg-clip-text text-transparent">Tech Life</span>
                    </h1>
                    <p class="mt-3 text-xs leading-relaxed text-slate-300 sm:text-sm max-w-sm">
                        Premium devices. Better performance. A smarter tomorrow with official warranty and direct WhatsApp support.
                    </p>
                    
                    <div class="mt-6 flex flex-wrap items-center gap-3">
                        <a href="{{ route('catalog') }}" class="focus-ring inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-6 py-3 text-xs font-bold text-white shadow-lg shadow-indigo-600/30 transition hover:bg-indigo-500 hover:scale-105 active:scale-95">
                            Shop Now <i class="fa-solid fa-arrow-right text-[11px]"></i>
                        </a>
                        @if($leadProduct)
                        <a href="{{ route('product.show', $leadProduct->slug) }}" class="focus-ring inline-flex items-center gap-2 rounded-xl border border-white/20 bg-white/5 px-4 py-3 text-xs font-bold text-white backdrop-blur transition hover:bg-white/10">
                            Ver destacado
                        </a>
                        @endif
                    </div>
                </div>

                <!-- Product Showcase Image -->
                <div class="relative flex items-center justify-center">
                    <!-- 40% OFF Sticker Badge -->
                    <div class="absolute -top-2 right-2 z-20 flex h-16 w-16 sm:h-20 sm:w-20 rotate-12 flex-col items-center justify-center rounded-full bg-gradient-to-br from-violet-600 to-indigo-600 text-white shadow-xl ring-4 ring-indigo-500/20">
                        <span class="text-[8px] font-black uppercase tracking-wider opacity-80">Up to</span>
                        <span class="font-display text-lg sm:text-xl font-black leading-none">40%</span>
                        <span class="text-[8px] font-black uppercase tracking-wider opacity-80">OFF</span>
                    </div>

                    @if($leadImage)
                        <img src="{{ filter_var($leadImage, FILTER_VALIDATE_URL) ? $leadImage : asset('storage/' . $leadImage) }}" alt="{{ $leadProduct->name }}" class="relative z-10 max-h-[280px] sm:max-h-[320px] w-auto object-contain drop-shadow-2xl transition duration-500 hover:scale-105">
                    @else
                        <div class="relative z-10 flex h-48 w-48 sm:h-64 sm:w-64 items-center justify-center rounded-3xl bg-gradient-to-tr from-indigo-500/20 to-purple-500/20 border border-white/10 backdrop-blur-md">
                            <i class="fa-solid fa-laptop text-7xl text-indigo-300/80 drop-shadow-lg"></i>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Bottom Mini Bar -->
            <div class="relative z-10 border-t border-white/10 pt-3 text-[10px] text-slate-400 flex items-center justify-between">
                <span>Laptops · Audio · Gaming · Accesorios</span>
                <span class="text-indigo-300 font-semibold flex items-center gap-1">
                    <i class="fa-solid fa-truck-fast"></i> Envíos a todo el Perú
                </span>
            </div>
        </div>

        <!-- Right Column: 3 Configurable Promo Cards -->
        <aside class="grid grid-cols-1 gap-3 sm:grid-cols-3 lg:grid-cols-1">
            @if(($settings['hero_card1_active'] ?? '1') == '1')
            <a href="{{ $settings['hero_card1_link'] ?? route('catalog') }}" class="group relative flex min-h-[125px] flex-col justify-between overflow-hidden rounded-2xl bg-gradient-to-br from-[#101b38] to-[#1c2c54] p-4 text-white shadow-sm transition hover:shadow-md">
                <span class="text-[9px] font-black uppercase tracking-widest text-indigo-300">{{ $settings['hero_card1_tag'] ?? 'Nuevos Ingresos' }}</span>
                <div class="relative z-10">
                    <h3 class="font-display text-base font-extrabold leading-tight">{{ $settings['hero_card1_title'] ?? 'Equipos para cada reto' }}</h3>
                </div>
                <i class="fa-solid fa-laptop absolute bottom-2 right-2 text-4xl text-white/10 transition duration-300 group-hover:scale-110 group-hover:text-white/20"></i>
            </a>
            @endif

            @if(($settings['hero_card2_active'] ?? '1') == '1')
            <a href="{{ $settings['hero_card2_link'] ?? route('catalog', ['offers' => 1]) }}" class="group relative flex min-h-[125px] flex-col justify-between overflow-hidden rounded-2xl bg-gradient-to-br from-[#3b1a7a] to-[#5d2bb8] p-4 text-white shadow-sm transition hover:shadow-md">
                <span class="text-[9px] font-black uppercase tracking-widest text-purple-200">{{ $settings['hero_card2_tag'] ?? 'Precios Especiales' }}</span>
                <div class="relative z-10">
                    <h3 class="font-display text-base font-extrabold leading-tight">{{ $settings['hero_card2_title'] ?? 'Ofertas para aprovechar' }}</h3>
                </div>
                <i class="fa-solid fa-bolt absolute bottom-2 right-2 text-4xl text-white/10 transition duration-300 group-hover:scale-110 group-hover:text-white/20"></i>
            </a>
            @endif

            @if(($settings['hero_card3_active'] ?? '1') == '1')
            <a href="{{ !empty($settings['hero_card3_link']) ? $settings['hero_card3_link'] : 'https://wa.me/' . $whatsappNumber . '?text=' . urlencode('Hola, necesito ayuda para elegir una laptop.') }}" target="_blank" rel="noopener noreferrer" class="group relative flex min-h-[125px] flex-col justify-between overflow-hidden rounded-2xl bg-gradient-to-br from-[#0c5963] to-[#14838f] p-4 text-white shadow-sm transition hover:shadow-md">
                <span class="text-[9px] font-black uppercase tracking-widest text-teal-200">{{ $settings['hero_card3_tag'] ?? 'Atención Personal' }}</span>
                <div class="relative z-10">
                    <h3 class="font-display text-base font-extrabold leading-tight">{{ $settings['hero_card3_title'] ?? 'Te ayudamos a elegir' }}</h3>
                </div>
                <i class="fa-brands fa-whatsapp absolute bottom-2 right-2 text-4xl text-white/10 transition duration-300 group-hover:scale-110 group-hover:text-white/20"></i>
            </a>
            @endif
        </aside>
    </section>

    <!-- Trust Badges (Antixor 4-Pillars Style) -->
    <section class="grid grid-cols-2 gap-3 rounded-2xl border border-slate-200/80 bg-white p-4 shadow-sm sm:grid-cols-4 sm:p-5" aria-label="Ventajas de compra">
        <div class="flex items-center gap-3 px-2 py-1 sm:border-r sm:border-slate-100">
            <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600 text-lg border border-indigo-100">
                <i class="fa-regular fa-circle-check"></i>
            </span>
            <div>
                <span class="block text-xs font-extrabold text-slate-900">100% Original Products</span>
                <span class="block text-[10px] text-slate-400 font-medium">Genuine & Trusted</span>
            </div>
        </div>
        
        <div class="flex items-center gap-3 px-2 py-1 sm:border-r sm:border-slate-100">
            <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600 text-lg border border-emerald-100">
                <i class="fa-solid fa-shield-halved"></i>
            </span>
            <div>
                <span class="block text-xs font-extrabold text-slate-900">Secure Payment</span>
                <span class="block text-[10px] text-slate-400 font-medium">SSL Encrypted</span>
            </div>
        </div>

        <div class="flex items-center gap-3 px-2 py-1 sm:border-r sm:border-slate-100">
            <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-amber-50 text-amber-600 text-lg border border-amber-100">
                <i class="fa-solid fa-arrow-rotate-left"></i>
            </span>
            <div>
                <span class="block text-xs font-extrabold text-slate-900">Easy Return</span>
                <span class="block text-[10px] text-slate-400 font-medium">7 Days Return Policy</span>
            </div>
        </div>

        <div class="flex items-center gap-3 px-2 py-1">
            <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-purple-50 text-purple-600 text-lg border border-purple-100">
                <i class="fa-solid fa-headset"></i>
            </span>
            <div>
                <span class="block text-xs font-extrabold text-slate-900">24/7 Support</span>
                <span class="block text-[10px] text-slate-400 font-medium">We're Here to Help</span>
            </div>
        </div>
    </section>

    <!-- Circular Categories Row (Browse by Category) -->
    <section aria-labelledby="categories-title">
        <div class="flex gap-4 sm:gap-6 overflow-x-auto hide-scrollbar py-2 justify-start sm:justify-center">
            @foreach($categories as $category)
                <a href="{{ route('catalog', ['category' => $category->slug]) }}" class="group flex flex-col items-center gap-2.5 min-w-[76px] sm:min-w-[90px]">
                    <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-full bg-white flex items-center justify-center shadow-sm border border-slate-200/80 transition duration-300 group-hover:border-indigo-400 group-hover:shadow-md p-3.5 group-hover:-translate-y-1">
                        @if($category->image)
                            <img src="{{ filter_var($category->image, FILTER_VALIDATE_URL) ? $category->image : asset('storage/' . $category->image) }}" alt="{{ $category->name }}" class="w-full h-full object-contain transition group-hover:scale-110">
                        @else
                            @php
                                $categoryName = strtolower($category->name);
                                $icon = str_contains($categoryName, 'laptop') ? 'fa-laptop' : (str_contains($categoryName, 'gaming') ? 'fa-gamepad' : (str_contains($categoryName, 'audio') || str_contains($categoryName, 'parlante') ? 'fa-headphones' : (str_contains($categoryName, 'accesorio') ? 'fa-keyboard' : (str_contains($categoryName, 'camara') ? 'fa-camera' : (str_contains($categoryName, 'reloj') || str_contains($categoryName, 'watch') ? 'fa-clock' : 'fa-mobile-screen')))));
                            @endphp
                            <i class="fa-solid {{ $icon }} text-2xl text-slate-400 transition group-hover:text-indigo-600 group-hover:scale-110"></i>
                        @endif
                    </div>
                    <span class="text-[11px] font-bold text-slate-700 text-center leading-tight group-hover:text-indigo-600 transition">{{ $category->name }}</span>
                </a>
            @endforeach
        </div>
    </section>



    <!-- Hot Deals Section (Antixor Best Deals This Week) -->
    @if($offerProducts->isNotEmpty())
        <section aria-labelledby="offers-title">
            <div class="mb-5 flex items-end justify-between gap-3">
                <div>
                    <span class="text-[10px] font-black uppercase tracking-widest text-rose-500">HOT DEALS</span>
                    <h2 id="offers-title" class="mt-1 font-display text-2xl font-black text-slate-900 sm:text-3xl">Best Deals This Week</h2>
                    <p class="mt-1 text-xs text-slate-500">Top-rated products at unbeatable prices. Don't miss out!</p>
                </div>
                <a href="{{ route('catalog', ['offers' => 1]) }}" class="focus-ring rounded-xl bg-[#0f172a] px-4 py-2.5 text-xs font-bold text-white hover:bg-slate-800 transition">
                    View All Deals <span aria-hidden="true">→</span>
                </a>
            </div>
            <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-4">
                @foreach($offerProducts->take(4) as $product)
                    @include('front.partials.product-card', ['product' => $product, 'whatsappNumber' => $whatsappNumber])
                @endforeach
            </div>
        </section>
    @endif

    <!-- Dual Promo Banners (Middle: Gaming / Audio Collection) -->
    @if((($settings['promo_gaming_active'] ?? '1') == '1') || (($settings['promo_audio_active'] ?? '1') == '1'))
    <section class="grid gap-4 md:grid-cols-2" aria-label="Colecciones especiales">
        <!-- Banner 1: Gaming Experience -->
        @if(($settings['promo_gaming_active'] ?? '1') == '1')
        <div class="relative min-h-[220px] overflow-hidden rounded-2xl bg-gradient-to-r from-[#07131b] via-[#0b1d28] to-[#122e3f] p-6 text-white sm:min-h-[250px] sm:p-8 flex flex-col justify-between shadow-sm">
            <div class="relative z-10 max-w-xs">
                <span class="text-[9px] font-black uppercase tracking-widest text-teal-400">{{ $settings['promo_gaming_tag'] ?? 'PLAYSTATION & GAMING' }}</span>
                <h2 class="mt-2 font-display text-2xl font-black leading-tight sm:text-3xl text-white">
                    {{ $settings['promo_gaming_title'] ?? 'Next Level Gaming Experience' }}
                </h2>
                <p class="mt-2 text-xs text-slate-400">{{ $settings['promo_gaming_subtitle'] ?? 'Immersive. Powerful. Unstoppable.' }}</p>
                <a href="{{ $settings['promo_gaming_link'] ?? route('catalog') }}" class="focus-ring mt-5 inline-flex items-center gap-2 rounded-xl bg-teal-400 px-5 py-2.5 text-xs font-extrabold text-slate-950 hover:bg-teal-300 transition shadow-lg shadow-teal-500/20">
                    {{ $settings['promo_gaming_button'] ?? 'Shop Now' }} <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </a>
            </div>
            <i class="fa-solid fa-gamepad absolute bottom-[-15px] right-4 text-[140px] text-white/5 pointer-events-none" aria-hidden="true"></i>
        </div>
        @endif

        <!-- Banner 2: Audio Collection -->
        @if(($settings['promo_audio_active'] ?? '1') == '1')
        <div class="relative min-h-[220px] overflow-hidden rounded-2xl bg-gradient-to-r from-[#170e28] via-[#24133f] to-[#311b54] p-6 text-white sm:min-h-[250px] sm:p-8 flex flex-col justify-between shadow-sm">
            <div class="relative z-10 max-w-xs">
                <span class="text-[9px] font-black uppercase tracking-widest text-purple-300">{{ $settings['promo_audio_tag'] ?? 'PREMIUM AUDIO' }}</span>
                <h2 class="mt-2 font-display text-2xl font-black leading-tight sm:text-3xl text-white">
                    {{ $settings['promo_audio_title'] ?? 'Premium Audio Collection' }}
                </h2>
                <p class="mt-2 text-xs text-slate-400">{{ $settings['promo_audio_subtitle'] ?? 'Feel Every Beat with High-Res Sound' }}</p>
                <a href="{{ $settings['promo_audio_link'] ?? route('catalog') }}" class="focus-ring mt-5 inline-flex items-center gap-2 rounded-xl bg-indigo-500 px-5 py-2.5 text-xs font-extrabold text-white hover:bg-indigo-400 transition shadow-lg shadow-indigo-600/30">
                    {{ $settings['promo_audio_button'] ?? 'Shop Now' }} <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </a>
            </div>
            <i class="fa-solid fa-headphones absolute bottom-[-18px] right-4 text-[140px] text-white/5 pointer-events-none" aria-hidden="true"></i>
        </div>
        @endif
    </section>
    @endif

    <!-- Trending / Featured Products -->
    @if($featuredProducts->isNotEmpty())
        <section aria-labelledby="featured-title">
            <div class="mb-5 flex items-end justify-between gap-3">
                <div>
                    <span class="text-[10px] font-black uppercase tracking-widest text-indigo-600">TRENDING</span>
                    <h2 id="featured-title" class="mt-1 font-display text-2xl font-black text-slate-900 sm:text-3xl">Featured Products</h2>
                    <p class="mt-1 text-xs text-slate-500">Handpicked just for you. Explore our most popular tech items.</p>
                </div>
                <a href="{{ route('catalog') }}" class="focus-ring rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-xs font-bold text-slate-700 hover:border-indigo-300 hover:text-indigo-600 transition">
                    Ver catálogo completo <span aria-hidden="true">→</span>
                </a>
            </div>
            <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-4">
                @foreach($featuredProducts->take(8) as $product)
                    @include('front.partials.product-card', ['product' => $product, 'whatsappNumber' => $whatsappNumber])
                @endforeach
            </div>
        </section>
    @endif

    <!-- Special Offer Full-Width Banner (50% Off Banner) -->
    @if(($settings['promo_special_active'] ?? '1') == '1')
    <section class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-[#181145] via-[#2a1b74] to-[#4527a0] p-6 sm:p-10 text-white shadow-xl">
        <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="max-w-xl text-center md:text-left">
                <span class="inline-flex items-center rounded-full bg-white/10 px-3 py-1 text-[10px] font-bold uppercase tracking-wider text-purple-200">
                    {{ $settings['promo_special_tag'] ?? 'SPECIAL OFFER' }}
                </span>
                <h2 class="mt-3 font-display text-3xl font-black text-white sm:text-4xl">
                    {{ $settings['promo_special_title'] ?? 'Up to 50% Off Selected Models' }}
                </h2>
                <p class="mt-2 text-xs text-purple-200 sm:text-sm">
                    {{ $settings['promo_special_subtitle'] ?? 'Limited time deals on high-performance laptops and accessories. Grab your favorite tech now before stock runs out!' }}
                </p>
                <div class="mt-5">
                    <a href="{{ $settings['promo_special_link'] ?? route('catalog', ['offers' => 1]) }}" class="focus-ring inline-flex items-center gap-2 rounded-xl bg-white px-6 py-3 text-xs font-black text-indigo-900 shadow-lg transition hover:bg-purple-50">
                        {{ $settings['promo_special_button'] ?? 'Shop Now' }} <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </a>
                </div>
            </div>
            <div class="flex items-center justify-center">
                <i class="fa-solid fa-laptop-code text-8xl text-purple-300/30"></i>
            </div>
        </div>
    </section>
    @endif

    <!-- Top Brands Section -->
    @if(($settings['brands_active'] ?? '1') == '1' && $brands->isNotEmpty())
        <section id="marcas" aria-labelledby="brands-title" class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm sm:p-7">
            <div class="mb-5 flex items-end justify-between">
                <div>
                    <span class="text-[10px] font-black uppercase tracking-widest text-indigo-600">{{ $settings['brands_tag'] ?? 'MARCAS OFICIALES' }}</span>
                    <h2 id="brands-title" class="mt-1 font-display text-xl font-extrabold text-slate-900 sm:text-2xl">{{ $settings['brands_title'] ?? 'Top Brands' }}</h2>
                </div>
                <i class="fa-solid fa-award text-2xl text-indigo-400"></i>
            </div>
            <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-6">
                @foreach($brands as $brand)
                    @php
                        $brandLogoUrl = $brand->logo ? (filter_var($brand->logo, FILTER_VALIDATE_URL) ? $brand->logo : asset('storage/' . $brand->logo)) : null;
                    @endphp
                    <a href="{{ route('catalog', ['brand' => $brand->slug]) }}" class="focus-ring group flex min-h-[68px] items-center justify-center rounded-xl border border-slate-100 bg-slate-50/70 p-3 text-center transition duration-200 hover:border-indigo-300 hover:bg-indigo-50/50 hover:shadow-sm">
                        @if($brandLogoUrl)
                            <img src="{{ $brandLogoUrl }}" alt="{{ $brand->name }}" class="max-h-8 max-w-[120px] object-contain transition group-hover:scale-105" title="{{ $brand->name }}">
                        @else
                            <span class="text-xs font-black tracking-wide text-slate-700 group-hover:text-indigo-700">{{ $brand->name }}</span>
                        @endif
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    <!-- Testimonials Section (What Our Customers Say) -->
    <section aria-labelledby="testimonials-title" class="rounded-2xl bg-[#0b1730] p-6 sm:p-10 text-white shadow-xl">
        <div class="mb-8 flex items-end justify-between gap-3">
            <div>
                <span class="text-[10px] font-black uppercase tracking-widest text-indigo-400">TESTIMONIALS</span>
                <h2 id="testimonials-title" class="mt-1 font-display text-2xl font-extrabold sm:text-3xl text-white">What Our Customers Say</h2>
                <p class="mt-2 text-xs text-slate-400">Real people. Real experiences. Here's why thousands trust us in Peru.</p>
            </div>
        </div>
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
            <div class="rounded-2xl bg-white/5 border border-white/10 p-5 text-white backdrop-blur">
                <div class="mb-3 flex items-center gap-3">
                    <img src="https://ui-avatars.com/api/?name=Carlos+Mendoza&background=6366f1&color=fff" alt="Carlos Mendoza" class="h-10 w-10 rounded-full">
                    <div>
                        <h4 class="text-sm font-bold">Carlos Mendoza</h4>
                        <div class="text-xs text-amber-400 flex gap-0.5"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i></div>
                    </div>
                </div>
                <p class="text-xs leading-relaxed text-slate-300">"Amazing products and super fast delivery. Excellent customer service via WhatsApp!"</p>
            </div>
            <div class="rounded-2xl bg-white/5 border border-white/10 p-5 text-white backdrop-blur">
                <div class="mb-3 flex items-center gap-3">
                    <img src="https://ui-avatars.com/api/?name=Lucia+Fernandez&background=ec4899&color=fff" alt="Lucia Fernandez" class="h-10 w-10 rounded-full">
                    <div>
                        <h4 class="text-sm font-bold">Lucía Fernández</h4>
                        <div class="text-xs text-amber-400 flex gap-0.5"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i></div>
                    </div>
                </div>
                <p class="text-xs leading-relaxed text-slate-300">"Best online store I've used so far. Genuine products with warranty and fast guidance."</p>
            </div>
            <div class="rounded-2xl bg-white/5 border border-white/10 p-5 text-white backdrop-blur">
                <div class="mb-3 flex items-center gap-3">
                    <img src="https://ui-avatars.com/api/?name=Jorge+Ruiz&background=06b6d4&color=fff" alt="Jorge Ruiz" class="h-10 w-10 rounded-full">
                    <div>
                        <h4 class="text-sm font-bold">Jorge Ruiz</h4>
                        <div class="text-xs text-amber-400 flex gap-0.5"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i></div>
                    </div>
                </div>
                <p class="text-xs leading-relaxed text-slate-300">"The customer service is excellent. Will shop again for gaming gear!"</p>
            </div>
        </div>
    </section>

    <!-- Join Our Newsletter Section -->
    @if(($settings['newsletter_active'] ?? '1') == '1')
    <section class="flex flex-col items-center rounded-2xl bg-white p-8 text-center sm:p-12 border border-slate-200/80 shadow-sm">
        <div class="mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-indigo-50 text-indigo-600 shadow-sm border border-indigo-100">
            <i class="fa-regular fa-envelope-open text-2xl"></i>
        </div>
        <h2 class="font-display text-2xl font-black text-slate-900 sm:text-3xl">{{ $settings['newsletter_title'] ?? 'Join Our Newsletter' }}</h2>
        <p class="mb-6 mt-2 max-w-md text-xs sm:text-sm text-slate-500">{{ $settings['newsletter_subtitle'] ?? 'Get the latest updates, deals and exclusive offers straight to your inbox.' }}</p>
        <form class="flex w-full max-w-md flex-col sm:flex-row gap-2" onsubmit="event.preventDefault();">
            <input type="email" placeholder="Enter your email address" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm focus:border-indigo-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-100">
            <button type="button" class="whitespace-nowrap rounded-xl bg-indigo-600 px-6 py-3 text-xs font-black text-white transition hover:bg-indigo-700 shadow-md shadow-indigo-600/20">Subscribe</button>
        </form>
    </section>
    @endif

    <!-- Shop with Confidence Banner (Antixor Footer Banner) -->
    <section class="grid gap-6 rounded-2xl bg-gradient-to-r from-[#0c162c] to-[#152347] p-6 sm:p-8 text-white sm:grid-cols-3 shadow-lg">
        <div class="flex items-center gap-4">
            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-white/10 text-xl text-indigo-300"><i class="fa-solid fa-shield-check"></i></div>
            <div>
                <h3 class="font-bold text-white text-sm">Shop with Confidence</h3>
                <p class="mt-0.5 text-[11px] text-slate-400">Your satisfaction is our priority. Safe & reliable shopping.</p>
            </div>
        </div>
        <div class="flex items-center gap-4">
            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-white/10 text-xl text-teal-300"><i class="fa-solid fa-medal"></i></div>
            <div>
                <h3 class="font-bold text-white text-sm">Trusted Brand</h3>
                <p class="mt-0.5 text-[11px] text-slate-400">Official products with guaranteed technical warranty.</p>
            </div>
        </div>
        <div class="flex items-center gap-4">
            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-white/10 text-xl text-purple-300"><i class="fa-solid fa-truck-fast"></i></div>
            <div>
                <h3 class="font-bold text-white text-sm">Fast & Secure Delivery</h3>
                <p class="mt-0.5 text-[11px] text-slate-400">Safe delivery throughout all regions in Peru.</p>
            </div>
        </div>
    </section>

</div>
@endsection

