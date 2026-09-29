@extends('layouts.public')

@php
    $whatsappNumber = preg_replace('/[^0-9]/', '', \App\Models\Setting::where('key', 'whatsapp_number')->value('value') ?? '');
    $storeName = \App\Models\Setting::where('key', 'store_name')->value('value') ?? 'PORTÁTILES PERÚ';
    $price = $product->is_offer && $product->offer_price ? $product->offer_price : $product->price;
    $gallery = $product->images->sortByDesc('is_main')->values();
    $mainImage = $gallery->first()?->image_path;
    $mainImageUrl = $mainImage ? (filter_var($mainImage, FILTER_VALIDATE_URL) ? $mainImage : asset('storage/' . $mainImage)) : null;
    $whatsappMessage = 'Hola, quiero COMPRAR POR WHATSAPP: ' . $product->name . ' — S/ ' . number_format((float) $price, 2) . '. ' . route('product.show', $product->slug);
    $seoDescription = Str::limit(strip_tags($product->description ?: ($product->name . ' con garantía y envíos a todo el Perú en ' . $storeName . '. Consulta precio y disponibilidad.')), 155);
    $seoKeywords = implode(', ', array_filter([$product->name, $product->brand?->name, $product->category?->name, 'precio peru', 'comprar en lima', 'garantia']));
@endphp

@section('title', $product->name)
@section('meta_description', $seoDescription)
@section('meta_keywords', $seoKeywords)
@section('og_type', 'product')
@section('og_title', $product->name . ' — S/ ' . number_format((float)$price, 2))
@section('og_description', $seoDescription)
@if($mainImageUrl)
@section('og_image', $mainImageUrl)
@endif

@section('og_extra')
    <meta property="product:price:amount" content="{{ number_format((float)$price, 2, '.', '') }}">
    <meta property="product:price:currency" content="PEN">
    <meta property="product:availability" content="{{ $product->stock > 0 ? 'in stock' : 'out of stock' }}">
    @if($product->brand)
    <meta property="product:brand" content="{{ $product->brand->name }}">
    @endif
    @if($product->category)
    <meta property="product:category" content="{{ $product->category->name }}">
    @endif
@endsection

@section('head')
@php
    $galleryUrls = $gallery->map(fn($img) => filter_var($img->image_path, FILTER_VALIDATE_URL) ? $img->image_path : asset('storage/' . $img->image_path))->values()->all();
    if (empty($galleryUrls) && $mainImageUrl) {
        $galleryUrls = [$mainImageUrl];
    }
@endphp
<script type="application/ld+json">
{
    "@@context": "https://schema.org/",
    "@@type": "Product",
    "name": {{ json_encode($product->name) }},
    "image": {{ json_encode($galleryUrls) }},
    "description": {{ json_encode(Str::limit(strip_tags($product->description ?: $product->name), 500)) }},
    "sku": {{ json_encode($product->code ?: (string)$product->id) }},
    "mpn": {{ json_encode($product->code ?: (string)$product->id) }},
    @if($product->brand)
    "brand": {
        "@@type": "Brand",
        "name": {{ json_encode($product->brand->name) }}
    },
    @endif
    @if($product->category)
    "category": {{ json_encode($product->category->name) }},
    @endif
    "offers": {
        "@@type": "Offer",
        "url": "{{ route('product.show', $product->slug) }}",
        "priceCurrency": "PEN",
        "price": "{{ number_format((float)$price, 2, '.', '') }}",
        "priceValidUntil": "{{ now()->addMonths(6)->toDateString() }}",
        "itemCondition": "{{ $product->state === 'Usado' ? 'https://schema.org/UsedCondition' : ($product->state === 'Seminuevo' ? 'https://schema.org/RefurbishedCondition' : 'https://schema.org/NewCondition') }}",
        "availability": "{{ $product->stock > 0 ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock' }}",
        "seller": {
            "@@type": "Organization",
            "name": {{ json_encode($storeName) }}
        }
    }
    @if($product->reviews_count > 0)
    ,
    "aggregateRating": {
        "@@type": "AggregateRating",
        "ratingValue": "{{ round((float)$product->reviews_avg_rating, 1) }}",
        "reviewCount": "{{ $product->reviews_count }}",
        "bestRating": "5",
        "worstRating": "1"
    }
    @endif
}
</script>
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "BreadcrumbList",
    "itemListElement": [
        {
            "@@type": "ListItem",
            "position": 1,
            "name": "Inicio",
            "item": "{{ route('home') }}"
        },
        @if($product->category)
        {
            "@@type": "ListItem",
            "position": 2,
            "name": {{ json_encode($product->category->name) }},
            "item": "{{ route('catalog', ['category' => $product->category->slug]) }}"
        },
        {
            "@@type": "ListItem",
            "position": 3,
            "name": {{ json_encode($product->name) }},
            "item": "{{ route('product.show', $product->slug) }}"
        }
        @else
        {
            "@@type": "ListItem",
            "position": 2,
            "name": {{ json_encode($product->name) }},
            "item": "{{ route('product.show', $product->slug) }}"
        }
        @endif
    ]
}
</script>
@endsection

@section('content')

<div class="mx-auto max-w-[1440px] px-4 py-6 sm:px-7 sm:py-8">
    <!-- Breadcrumb -->
    <nav aria-label="Ruta de navegación" class="mb-5 flex flex-wrap items-center gap-2 text-xs text-slate-500">
        <a href="{{ route('home') }}" class="transition hover:text-indigo-600">Inicio</a>
        <i class="fa-solid fa-chevron-right text-[9px] text-slate-300"></i>
        <a href="{{ route('catalog', ['category' => $product->category->slug ?? null]) }}" class="transition hover:text-indigo-600">{{ $product->category->name ?? 'Catálogo' }}</a>
        <i class="fa-solid fa-chevron-right text-[9px] text-slate-300"></i>
        <span class="font-medium text-slate-800 line-clamp-1 max-w-xs sm:max-w-md">{{ $product->name }}</span>
    </nav>

    <!-- Top Product Showcase -->
    <section class="grid gap-8 lg:grid-cols-12 items-start">
        <!-- Left Column: Gallery & Images -->
        <div class="lg:col-span-7 flex flex-col gap-4">
            <!-- Main Image Frame -->
            <div id="image-zoom-container" class="group relative w-full aspect-square sm:aspect-[4/3] max-h-[540px] flex items-center justify-center overflow-hidden rounded-3xl border border-slate-200/80 bg-white p-6 sm:p-10 shadow-sm transition-all">
                <!-- Badges -->
                <div class="absolute left-4 top-4 z-20 flex flex-col gap-1.5">
                    @if($product->is_offer && $product->offer_price)
                        <span class="rounded-full bg-rose-600 px-3 py-1 text-[11px] font-black uppercase tracking-wider text-white shadow-md shadow-rose-500/20">
                            Oferta Especial
                        </span>
                    @endif
                    @if($product->is_new)
                        <span class="rounded-full bg-indigo-600 px-3 py-1 text-[11px] font-black uppercase tracking-wider text-white shadow-md shadow-indigo-500/20">
                            Nuevo
                        </span>
                    @endif
                </div>
                
                <!-- Wishlist Toggle -->
                <button type="button" 
                        data-wishlist-toggle 
                        data-product-id="{{ $product->id }}" 
                        data-product-name="{{ $product->name }}" 
                        data-product-price="{{ (float) $price }}" 
                        data-product-image="{{ $mainImageUrl ?? '' }}" 
                        data-product-url="{{ route('product.show', $product->slug) }}" 
                        class="absolute right-4 top-4 z-20 flex h-11 w-11 items-center justify-center rounded-2xl bg-white/90 text-slate-400 shadow-md border border-slate-100 backdrop-blur transition-all duration-200 hover:bg-rose-50 hover:text-rose-500 hover:scale-105 active:scale-95 focus:outline-none" 
                        title="Guardar en favoritos">
                    <i class="fa-regular fa-heart text-lg transition-colors pointer-events-none"></i>
                </button>

                <!-- Product Image (Clean, No blend artifacts) -->
                @if($mainImage)
                    <img id="main-product-image" 
                         src="{{ filter_var($mainImage, FILTER_VALIDATE_URL) ? $mainImage : asset('storage/' . $mainImage) }}" 
                         alt="{{ $product->name }} — Vista Principal" 
                         width="600"
                         height="600"
                         decoding="async"
                         class="relative z-10 h-full w-full object-contain transition-transform duration-150 ease-out select-none cursor-zoom-in"
                         loading="eager">
                @else
                    <div class="relative z-10 flex h-64 w-64 items-center justify-center rounded-3xl bg-slate-100 text-slate-300">
                        <i class="fa-solid fa-image text-6xl"></i>
                    </div>
                @endif
                
                <span class="absolute bottom-4 right-5 z-10 text-[10px] font-bold uppercase tracking-widest text-slate-300 pointer-events-none">
                    {{ $product->brand->name ?? 'Portátiles Perú' }}
                </span>
            </div>

            <!-- Thumbnails Gallery -->
            @if($gallery->count() > 1)
                <div class="flex items-center gap-3 overflow-x-auto pb-2 scrollbar-thin">
                    @foreach($gallery as $image)
                        @php
                            $imgUrl = filter_var($image->image_path, FILTER_VALIDATE_URL) ? $image->image_path : asset('storage/' . $image->image_path);
                        @endphp
                        <button type="button" 
                                onclick="switchMainImage('{{ $imgUrl }}', this)" 
                                class="thumbnail-btn relative aspect-square h-20 w-20 shrink-0 overflow-hidden rounded-2xl border-2 {{ $loop->first ? 'border-indigo-600 ring-2 ring-indigo-500/20' : 'border-slate-200 hover:border-indigo-300' }} bg-white p-2 transition-all duration-200">
                            <img src="{{ $imgUrl }}" alt="{{ $product->name }} miniatura {{ $loop->iteration }}" width="80" height="80" loading="lazy" decoding="async" class="h-full w-full object-contain">
                        </button>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Right Column: Product Info & Actions -->
        <div class="lg:col-span-5 rounded-3xl border border-slate-200/80 bg-white p-6 sm:p-8 shadow-sm flex flex-col">
            <!-- Header Badges -->
            <div class="flex items-center justify-between gap-3 flex-wrap">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-black uppercase tracking-wider bg-indigo-50 text-indigo-700">
                    <i class="fa-solid fa-tag text-[10px]"></i> {{ $product->brand->name ?? 'Marca' }}
                </span>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold {{ $product->stock > 0 ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700' }}">
                    <span class="w-2 h-2 rounded-full {{ $product->stock > 0 ? 'bg-emerald-500 animate-pulse' : 'bg-amber-500' }}"></span>
                    {{ $product->stock > 0 ? 'Disponible (' . $product->stock . ' unid.)' : 'Consultar stock' }}
                </span>
            </div>

            <!-- Product Title -->
            <h1 class="mt-4 font-display text-2xl sm:text-3xl font-black leading-snug text-slate-900">
                {{ $product->name }}
            </h1>

            <!-- Meta details -->
            <div class="mt-3 flex items-center gap-3 text-xs text-slate-500 flex-wrap">
                <span class="font-medium text-slate-600"><span class="text-slate-400">P/N:</span> {{ $product->code }}</span>
                @if($product->deviceModel)
                    <span>•</span>
                    <span class="font-medium text-slate-600"><span class="text-slate-400">Modelo:</span> {{ $product->deviceModel->name }}</span>
                @endif
                <span>•</span>
                <a href="#opiniones" class="inline-flex items-center gap-1.5 text-amber-400 hover:underline">
                    <div class="flex">
                        @for($star = 1; $star <= 5; $star++)
                            <i class="fa-solid fa-star {{ $star <= round((float) $product->reviews_avg_rating) ? 'text-amber-400' : 'text-slate-200' }} text-[11px]"></i>
                        @endfor
                    </div>
                    <span class="text-slate-500 font-semibold text-[11px]">({{ $product->reviews_count }})</span>
                </a>
            </div>

            <!-- Price Block -->
            <div class="mt-5 rounded-2xl bg-gradient-to-br from-slate-50 to-indigo-50/30 p-4 sm:p-5 border border-slate-100">
                <p class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400">Precio especial</p>
                @if($product->is_offer && $product->offer_price)
                    <p class="mt-1 text-sm text-slate-400 line-through font-medium">S/ {{ number_format((float) $product->price, 2) }}</p>
                @endif
                <div class="mt-1 flex items-baseline gap-2">
                    <span class="text-sm font-black text-indigo-700">S/</span>
                    <span class="font-display text-3xl sm:text-4xl font-black tracking-tight text-slate-900">
                        {{ number_format((float) $price, 2) }}
                    </span>
                </div>
            </div>

            <!-- Description -->
            <div class="mt-5">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Descripción</h3>
                <div class="text-xs sm:text-sm leading-relaxed text-slate-600 whitespace-pre-line max-h-48 overflow-y-auto pr-2 scrollbar-thin">
                    {{ $product->description ?: 'Consulta las especificaciones y disponibilidad con nuestro equipo de atención al cliente.' }}
                </div>
            </div>

            <!-- CTA Actions -->
            <div class="mt-6 space-y-3 pt-4 border-t border-slate-100">
                <div data-product-quantity class="flex gap-2">
                    <label for="product-quantity" class="sr-only">Cantidad</label>
                    <input id="product-quantity" type="number" min="1" max="{{ max(1, $product->stock) }}" value="1" @disabled($product->stock < 1) class="h-12 w-16 sm:w-20 rounded-2xl border border-slate-200 bg-slate-50 px-2 text-center text-sm font-bold text-slate-800 outline-none focus:ring-2 focus:ring-indigo-500">
                    <button type="button" 
                            data-add-to-cart 
                            data-product-id="{{ $product->id }}" 
                            data-product-name="{{ $product->name }}" 
                            data-product-price="{{ (float) $price }}" 
                            data-product-stock="{{ (int) $product->stock }}" 
                            data-product-image="{{ $mainImageUrl ?? '' }}" 
                            data-product-url="{{ route('product.show', $product->slug) }}" 
                            @disabled($product->stock < 1) 
                            class="flex-1 h-12 flex items-center justify-center gap-2 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold text-xs sm:text-sm shadow-lg shadow-indigo-600/20 transition-all duration-200 active:scale-[0.98] disabled:opacity-50 disabled:cursor-not-allowed">
                        <i class="fa-solid fa-cart-plus text-base"></i> AGREGAR AL CARRITO
                    </button>
                </div>

                <a href="https://wa.me/{{ $whatsappNumber }}?text={{ urlencode($whatsappMessage) }}" 
                   target="_blank" 
                   rel="noopener noreferrer" 
                   class="flex h-12 w-full items-center justify-center gap-2 rounded-2xl bg-[#25D366] hover:bg-[#20bd5a] text-white font-extrabold text-xs sm:text-sm shadow-lg shadow-emerald-600/20 transition-all duration-200 active:scale-[0.98]">
                    <i class="fa-brands fa-whatsapp text-lg"></i> COMPRAR POR WHATSAPP
                </a>

                <a href="{{ route('product.pdf', $product->slug) }}" 
                   class="flex h-11 w-full items-center justify-center gap-2 rounded-2xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 font-bold text-xs shadow-xs transition-colors">
                    <i class="fa-solid fa-file-pdf text-rose-500 text-sm"></i> DESCARGAR FICHA TÉCNICA
                </a>
            </div>

            <!-- Guarantees & Perks -->
            <div class="mt-6 grid grid-cols-2 gap-3 border-t border-slate-100 pt-5">
                <div class="flex items-center gap-3 p-2.5 rounded-xl bg-slate-50/80 border border-slate-100">
                    <div class="w-8 h-8 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center shrink-0 text-sm">
                        <i class="fa-solid fa-shield-halved"></i>
                    </div>
                    <div>
                        <div class="text-[11px] font-bold text-slate-800">Garantía</div>
                        <div class="text-[10px] text-slate-500">{{ $product->warranty ?: 'Oficial' }}</div>
                    </div>
                </div>
                <div class="flex items-center gap-3 p-2.5 rounded-xl bg-slate-50/80 border border-slate-100">
                    <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0 text-sm">
                        <i class="fa-solid fa-truck-fast"></i>
                    </div>
                    <div>
                        <div class="text-[11px] font-bold text-slate-800">Envío Rápido</div>
                        <div class="text-[10px] text-slate-500">A todo el Perú</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Technical Specs & Contact Section -->
    <section class="mt-10 grid gap-8 lg:grid-cols-12 items-start">
        <!-- Specs Table (Span 8) -->
        <div class="lg:col-span-8 rounded-3xl border border-slate-200/80 bg-white p-6 sm:p-8 shadow-sm">
            <div class="flex items-center justify-between gap-4 mb-6 border-b border-slate-100 pb-4">
                <div>
                    <span class="text-[10px] font-extrabold uppercase tracking-widest text-indigo-600">Detalles Técnicos</span>
                    <h2 class="mt-1 font-display text-xl sm:text-2xl font-black text-slate-900">Especificaciones del producto</h2>
                </div>
                <div class="w-10 h-10 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                    <i class="fa-solid fa-microchip text-lg"></i>
                </div>
            </div>

            <div class="overflow-hidden rounded-2xl border border-slate-100">
                <div class="divide-y divide-slate-100">
                    <div class="grid grid-cols-1 sm:grid-cols-[220px_1fr] gap-2 p-3.5 text-xs bg-slate-50/70">
                        <span class="font-bold text-slate-600 flex items-center gap-2"><i class="fa-solid fa-tag text-slate-400 text-[10px]"></i> Marca</span>
                        <span class="text-slate-800 font-medium">{{ $product->brand->name ?? '—' }}</span>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-[220px_1fr] gap-2 p-3.5 text-xs">
                        <span class="font-bold text-slate-600 flex items-center gap-2"><i class="fa-solid fa-layer-group text-slate-400 text-[10px]"></i> Categoría</span>
                        <span class="text-slate-800 font-medium">{{ $product->category->name ?? '—' }}</span>
                    </div>
                    @if(is_array($product->technical_specs))
                        @foreach($product->technical_specs as $key => $value)
                            <div class="grid grid-cols-1 sm:grid-cols-[220px_1fr] gap-2 p-3.5 text-xs {{ $loop->even ? 'bg-slate-50/50' : 'bg-white' }}">
                                <span class="font-bold text-slate-700 flex items-center gap-2">
                                    <i class="fa-solid fa-circle-check text-indigo-500 text-[9px]"></i> {{ $key }}
                                </span>
                                <span class="text-slate-800 leading-relaxed font-normal">{{ $value }}</span>
                            </div>
                        @endforeach
                    @endif
                    <div class="grid grid-cols-1 sm:grid-cols-[220px_1fr] gap-2 p-3.5 text-xs bg-slate-50/70">
                        <span class="font-bold text-slate-600 flex items-center gap-2"><i class="fa-solid fa-shield-halved text-slate-400 text-[10px]"></i> Garantía</span>
                        <span class="text-slate-800 font-medium">{{ $product->warranty ?: 'Consultar' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sticky Contact & Advisory Sidebar (Span 4) -->
        <div class="lg:col-span-4 sticky top-24 space-y-4">
            <div class="rounded-3xl bg-gradient-to-br from-[#121c3b] via-[#1e2e60] to-[#3a1d7c] p-6 sm:p-7 text-white shadow-xl shadow-indigo-950/15">
                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-white/10 text-2xl text-indigo-300 backdrop-blur-md">
                    <i class="fa-solid fa-headset"></i>
                </div>
                <h3 class="mt-4 font-display text-xl font-extrabold text-white">¿Tienes dudas sobre este equipo?</h3>
                <p class="mt-2 text-xs leading-relaxed text-blue-100/80">
                    Nuestros asesores expertos te ayudan con especificaciones personalizadas, compatibilidad y cotizaciones corporativas.
                </p>
                <a href="https://wa.me/{{ $whatsappNumber }}?text={{ urlencode('Hola, tengo una consulta sobre ' . $product->name . '.') }}" 
                   target="_blank" 
                   rel="noopener noreferrer" 
                   class="mt-5 inline-flex w-full items-center justify-center gap-2 rounded-2xl bg-white px-5 py-3 text-xs font-black text-[#121c3b] hover:bg-slate-100 shadow-md transition-all active:scale-95">
                    <i class="fa-brands fa-whatsapp text-emerald-600 text-base"></i> Chatear con un Asesor
                </a>
            </div>

            <div class="rounded-3xl border border-slate-200/80 bg-white p-5 shadow-sm text-xs text-slate-600 space-y-3">
                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-credit-card text-indigo-600 text-base"></i>
                    <span>Aceptamos transferencias, Yape, Plin y tarjetas</span>
                </div>
                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-box-check text-indigo-600 text-base"></i>
                    <span>Productos nuevos con empaque original y garantía</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Gallery Switching & Zoom Script -->
    <script>
        function switchMainImage(url, btnElement) {
            const mainImg = document.getElementById('main-product-image');
            if (mainImg) {
                mainImg.src = url;
            }
            document.querySelectorAll('.thumbnail-btn').forEach(btn => {
                btn.classList.remove('border-indigo-600', 'ring-2', 'ring-indigo-500/20');
                btn.classList.add('border-slate-200');
            });
            if (btnElement) {
                btnElement.classList.add('border-indigo-600', 'ring-2', 'ring-indigo-500/20');
                btnElement.classList.remove('border-slate-200');
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            const container = document.getElementById('image-zoom-container');
            const img = document.getElementById('main-product-image');
            
            if (container && img) {
                container.addEventListener('mousemove', (e) => {
                    const { left, top, width, height } = container.getBoundingClientRect();
                    const x = ((e.clientX - left) / width) * 100;
                    const y = ((e.clientY - top) / height) * 100;
                    
                    img.style.transformOrigin = `${x}% ${y}%`;
                    img.style.transform = 'scale(1.75)';
                });
                
                container.addEventListener('mouseleave', () => {
                    img.style.transformOrigin = 'center center';
                    img.style.transform = 'scale(1)';
                });
            }
        });
    </script>

    <section id="opiniones" class="mt-8 scroll-mt-36 rounded-2xl border border-slate-200 bg-white p-5 sm:p-7" aria-labelledby="reviews-title">
        <div class="grid gap-7 lg:grid-cols-[.85fr_1.15fr]">
            <div>
                <p class="text-[9px] font-extrabold uppercase tracking-[.16em] text-violet-600">Experiencias de clientes</p>
                <h2 id="reviews-title" class="mt-2 font-display text-xl font-extrabold text-[#142143]">Opiniones del producto</h2>
                <div class="mt-4 flex items-center gap-3"><strong class="font-display text-4xl font-extrabold text-[#142143]">{{ number_format((float) $product->reviews_avg_rating, 1) }}</strong><div><div class="text-amber-400">@for($star = 1; $star <= 5; $star++)<i class="fa-solid fa-star {{ $star <= round((float) $product->reviews_avg_rating) ? '' : 'text-slate-200' }}"></i>@endfor</div><p class="mt-1 text-[10px] text-slate-500">{{ $product->reviews_count }} opiniones publicadas</p></div></div>
                <form action="{{ route('product.review', $product->slug) }}" method="POST" class="mt-5 space-y-3 rounded-xl bg-slate-50 p-4">
                    @csrf
                    <h3 class="text-xs font-extrabold text-slate-800">Comparte tu opinión</h3>
                    @if(session('review_submitted'))<p role="status" class="rounded-lg bg-emerald-50 px-3 py-2 text-xs font-semibold text-emerald-700">{{ session('review_submitted') }}</p>@endif
                    @if($errors->any())<p role="alert" class="rounded-lg bg-rose-50 px-3 py-2 text-xs text-rose-700">{{ $errors->first() }}</p>@endif
                    <label class="block text-[10px] font-semibold text-slate-600" for="reviewer-name">Tu nombre</label><input id="reviewer-name" name="reviewer_name" value="{{ old('reviewer_name') }}" required maxlength="120" class="h-10 w-full rounded-lg border border-slate-200 bg-white px-3 text-xs outline-none focus:border-indigo-400">
                    <label class="block text-[10px] font-semibold text-slate-600" for="review-rating">Calificación</label><select id="review-rating" name="rating" required class="h-10 w-full rounded-lg border border-slate-200 bg-white px-3 text-xs outline-none focus:border-indigo-400"><option value="5" @selected(old('rating', '5') == '5')>★★★★★ — Excelente</option><option value="4" @selected(old('rating') == '4')>★★★★☆ — Muy bueno</option><option value="3" @selected(old('rating') == '3')>★★★☆☆ — Bueno</option><option value="2" @selected(old('rating') == '2')>★★☆☆☆ — Regular</option><option value="1" @selected(old('rating') == '1')>★☆☆☆☆ — Puede mejorar</option></select>
                    <label class="block text-[10px] font-semibold text-slate-600" for="review-comment">Tu opinión</label><textarea id="review-comment" name="comment" rows="4" required minlength="3" maxlength="2000" placeholder="Cuéntanos qué te pareció este equipo…" class="w-full rounded-lg border border-slate-200 bg-white p-3 text-xs outline-none focus:border-indigo-400">{{ old('comment') }}</textarea>
                    <button type="submit" class="focus-ring flex h-10 w-full items-center justify-center gap-2 rounded-lg bg-gradient-to-r from-[#2855d9] to-[#6340df] text-xs font-extrabold text-white hover:opacity-95"><i class="fa-regular fa-paper-plane"></i> Publicar opinión</button>
                </form>
            </div>
            <div class="space-y-3">
                @forelse($reviews as $review)
                    <article class="rounded-xl border border-slate-100 p-4"><div class="flex flex-wrap items-center justify-between gap-2"><h3 class="text-xs font-extrabold text-slate-800">{{ $review->reviewer_name }}</h3><time class="text-[10px] text-slate-400" datetime="{{ $review->created_at->toDateString() }}">{{ $review->created_at->format('d/m/Y') }}</time></div><div class="mt-1 text-xs text-amber-400" aria-label="{{ $review->rating }} de 5 estrellas">@for($star = 1; $star <= 5; $star++)<i class="fa-solid fa-star {{ $star <= $review->rating ? '' : 'text-slate-200' }}"></i>@endfor</div><p class="mt-3 whitespace-pre-line text-xs leading-5 text-slate-600">{{ $review->comment }}</p></article>
                @empty
                    <div class="flex min-h-48 flex-col items-center justify-center rounded-xl border border-dashed border-slate-200 p-6 text-center"><i class="fa-regular fa-comments text-2xl text-indigo-300"></i><p class="mt-3 text-sm font-bold text-slate-700">Aún no hay opiniones</p><p class="mt-1 max-w-xs text-xs text-slate-500">Sé la primera persona en compartir tu experiencia con este producto.</p></div>
                @endforelse
                @if($reviews->hasPages())<div class="pt-2">{{ $reviews->fragment('opiniones')->links() }}</div>@endif
            </div>
        </div>
    </section>

    @if($relatedProducts->isNotEmpty())
        <section class="mt-10" aria-labelledby="related-title"><div class="mb-4 flex items-end justify-between"><div><p class="text-[9px] font-bold uppercase tracking-[.16em] text-violet-600">Más opciones</p><h2 id="related-title" class="mt-1 font-display text-xl font-extrabold text-[#142143]">También te puede interesar</h2></div><a href="{{ route('catalog') }}" class="focus-ring text-xs font-bold text-blue-700">Ver catálogo <span aria-hidden="true">→</span></a></div><div class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-4">@foreach($relatedProducts as $relatedProduct)@include('front.partials.product-card', ['product' => $relatedProduct, 'whatsappNumber' => $whatsappNumber])@endforeach</div></section>
    @endif
</div>
@endsection
