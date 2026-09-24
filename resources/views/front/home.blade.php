@extends('layouts.public')

@section('title', 'Tecnología para avanzar')
@section('meta_description', 'Encuentra laptops y tecnología seleccionada por MPC Antigravity. Compra y recibe asesoría directa por WhatsApp.')

@section('content')
@php
    $leadProduct = $featuredProducts->first() ?? $offerProducts->first();
    $leadImage = $leadProduct?->images->first()?->image_path;
    $whatsappNumber = preg_replace('/[^0-9]/', '', \App\Models\Setting::where('key', 'whatsapp_number')->value('value') ?? '');
    $leadPrice = $leadProduct?->is_offer && $leadProduct?->offer_price ? $leadProduct->offer_price : $leadProduct?->price;
    $discount = $leadProduct && $leadProduct->price > 0 && $leadProduct->offer_price ? round((1 - ($leadProduct->offer_price / $leadProduct->price)) * 100) : null;
@endphp

<div class="mx-auto max-w-[1440px] space-y-10 px-4 py-6 sm:space-y-14 sm:px-7 sm:py-8">
    <section class="grid gap-4 lg:grid-cols-[225px_minmax(0,1fr)_220px]" aria-label="Descubre el catálogo">
        <aside class="hidden rounded-2xl border border-slate-200 bg-white p-4 shadow-sm lg:block">
            <div class="mb-3 flex items-center justify-between"><h1 class="text-sm font-extrabold text-[#111c36]">Categorías</h1><i class="fa-solid fa-layer-group text-indigo-500"></i></div>
            <div class="divide-y divide-slate-100">
                @forelse($categories as $category)
                    @php
                        $categoryName = strtolower($category->name);
                        $icon = str_contains($categoryName, 'laptop') ? 'fa-laptop' : (str_contains($categoryName, 'gaming') ? 'fa-gamepad' : (str_contains($categoryName, 'audio') || str_contains($categoryName, 'parlante') ? 'fa-headphones' : (str_contains($categoryName, 'accesorio') ? 'fa-plug' : 'fa-microchip')));
                    @endphp
                    <a href="{{ route('catalog', ['category' => $category->slug]) }}" class="focus-ring flex items-center gap-3 py-3 text-xs font-medium text-slate-600 hover:text-blue-700"><i class="fa-solid {{ $icon }} w-4 text-center text-indigo-500"></i><span class="flex-1">{{ $category->name }}</span><i class="fa-solid fa-chevron-right text-[9px] text-slate-300"></i></a>
                @empty
                    <p class="py-3 text-xs text-slate-500">Nuevas categorías pronto.</p>
                @endforelse
            </div>
            <a href="{{ route('catalog') }}" class="mt-3 block border-t border-slate-100 pt-3 text-xs font-bold text-blue-700 hover:text-violet-700">Ver todas las categorías <span aria-hidden="true">→</span></a>
        </aside>

        <div class="relative grid min-h-[410px] overflow-hidden rounded-2xl bg-gradient-to-br from-[#101d41] via-[#122d68] to-[#1647aa] text-white shadow-xl shadow-blue-950/10 sm:min-h-[460px] sm:grid-cols-[1fr_.9fr]">
            <div class="relative z-10 flex flex-col items-start justify-between gap-8 p-6 sm:p-10">
                <span class="rounded-full border border-white/20 bg-white/10 px-3 py-1.5 text-[9px] font-bold uppercase tracking-[.18em] text-blue-100">Colección seleccionada</span>
                <div>
                    <h2 class="max-w-lg font-display text-4xl font-extrabold leading-[1.02] tracking-tight sm:text-5xl">Actualiza tu día a día con mejor tecnología.</h2>
                    <p class="mt-4 max-w-sm text-sm leading-6 text-blue-100/80">Rendimiento, diseño y asesoría para encontrar el equipo que se ajusta a ti.</p>
                    <a href="{{ $leadProduct ? route('product.show', $leadProduct->slug) : route('catalog') }}" class="focus-ring mt-6 inline-flex items-center gap-2 rounded-lg bg-white px-5 py-3 text-xs font-extrabold text-[#172448] shadow-lg transition hover:bg-indigo-50">{{ $leadProduct ? 'Ver equipo destacado' : 'Explorar catálogo' }} <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></a>
                </div>
                <span class="text-[10px] font-medium tracking-wide text-blue-100/70">Laptops · Computación · Tecnología</span>
            </div>
            <div class="relative flex min-h-[190px] items-center justify-center overflow-hidden sm:min-h-[430px]">
                <div class="absolute right-[-5%] top-[8%] h-[330px] w-[330px] rounded-full bg-indigo-400/25 blur-3xl"></div>
                <div class="absolute bottom-[-20%] right-[5%] h-[280px] w-[280px] rounded-full bg-cyan-300/20 blur-3xl"></div>
                @if($leadImage)
                    <img src="{{ filter_var($leadImage, FILTER_VALIDATE_URL) ? $leadImage : asset('storage/' . $leadImage) }}" alt="{{ $leadProduct->name }}" class="relative z-10 max-h-[340px] w-full object-contain drop-shadow-2xl">
                @else
                    <div class="relative z-10 flex h-40 w-[82%] -rotate-6 items-center justify-center rounded-[1.5rem] border border-white/25 bg-gradient-to-br from-slate-100 via-slate-200 to-slate-400 p-3 shadow-2xl shadow-black/40 sm:h-64 sm:rounded-[2rem] sm:p-4">
                        <div class="absolute inset-x-8 top-6 h-[82%] rounded-xl border border-slate-500/20 bg-gradient-to-br from-[#111a34] via-[#1d4ca3] to-[#7c3aed] shadow-inner"></div>
                        <div class="absolute bottom-[-14px] h-4 w-[108%] rounded-b-2xl bg-gradient-to-b from-slate-200 to-slate-500 shadow-lg"></div>
                        <span class="relative z-10 font-display text-5xl font-extrabold tracking-tighter text-white drop-shadow-lg">MPC</span>
                    </div>
                @endif
                @if($leadProduct && $leadPrice !== null)
                    <div class="absolute bottom-7 right-5 z-20 rounded-xl bg-white px-4 py-3 text-slate-800 shadow-xl sm:right-8">
                        <span class="block max-w-[190px] truncate text-[10px] font-semibold text-slate-500">{{ $leadProduct->name }}</span>
                        <span class="mt-1 block font-display text-xl font-extrabold text-blue-700">S/ {{ number_format((float) $leadPrice, 2) }}</span>
                    </div>
                @endif
                @if($discount)
                    <div class="absolute right-6 top-7 z-20 flex h-[74px] w-[74px] rotate-6 flex-col items-center justify-center rounded-full bg-violet-600 text-white shadow-lg"><span class="text-[9px] font-bold">HASTA</span><span class="font-display text-xl font-black">{{ $discount }}%</span><span class="text-[9px] font-bold">MENOS</span></div>
                @endif
            </div>
        </div>

        <aside class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-1">
            <a href="{{ route('catalog') }}" class="group relative flex min-h-[125px] flex-col justify-between overflow-hidden rounded-2xl bg-gradient-to-br from-[#10244b] to-[#264f9a] p-4 text-white sm:min-h-[140px]">
                <span class="text-[9px] font-bold uppercase tracking-[.16em] text-blue-200">Nuevos ingresos</span><span class="font-display text-lg font-extrabold leading-tight">Equipos para<br>cada reto</span><i class="fa-solid fa-laptop absolute bottom-3 right-3 text-4xl text-white/20 transition group-hover:scale-110"></i>
            </a>
            <a href="{{ route('catalog', ['offers' => 1]) }}" class="group relative flex min-h-[125px] flex-col justify-between overflow-hidden rounded-2xl bg-gradient-to-br from-[#4524a8] to-[#7b3de1] p-4 text-white sm:min-h-[140px]">
                <span class="text-[9px] font-bold uppercase tracking-[.16em] text-violet-200">Precios especiales</span><span class="font-display text-lg font-extrabold leading-tight">Ofertas para<br>aprovechar</span><i class="fa-solid fa-bolt absolute bottom-3 right-3 text-4xl text-white/20 transition group-hover:scale-110"></i>
            </a>
            <a href="https://wa.me/{{ $whatsappNumber }}?text={{ urlencode('Hola, necesito ayuda para elegir una laptop.') }}" target="_blank" rel="noopener noreferrer" class="group relative flex min-h-[125px] flex-col justify-between overflow-hidden rounded-2xl bg-gradient-to-br from-[#0a6f83] to-[#11a7ae] p-4 text-white sm:min-h-[140px]">
                <span class="text-[9px] font-bold uppercase tracking-[.16em] text-cyan-100">Atención personal</span><span class="font-display text-lg font-extrabold leading-tight">Te ayudamos<br>a elegir</span><i class="fa-brands fa-whatsapp absolute bottom-3 right-3 text-4xl text-white/25 transition group-hover:scale-110"></i>
            </a>
        </aside>
    </section>

    <section class="grid grid-cols-2 gap-3 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:grid-cols-4 sm:p-5" aria-label="Ventajas de compra">
        @foreach([['fa-certificate', 'Productos seleccionados', 'Calidad para cada necesidad'], ['fa-shield-halved', 'Garantía por equipo', 'Consulta cobertura y detalles'], ['fa-truck-fast', 'Envíos nacionales', 'Coordinamos contigo'], ['fa-headset', 'Asesoría cercana', 'Antes y después de comprar']] as $feature)
            <div class="flex items-center gap-3 px-2 py-2 sm:border-r sm:border-slate-100 sm:last:border-0"><span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600"><i class="fa-solid {{ $feature[0] }}"></i></span><span><span class="block text-[11px] font-bold text-slate-800">{{ $feature[1] }}</span><span class="mt-1 block text-[9px] text-slate-400">{{ $feature[2] }}</span></span></div>
        @endforeach
    </section>

    <section aria-labelledby="categories-title">
        <div class="mb-5 flex items-end justify-between gap-3"><div><p class="text-[10px] font-bold uppercase tracking-[.18em] text-violet-600">Explora por categoría</p><h2 id="categories-title" class="mt-1 font-display text-2xl font-extrabold text-[#111c36] sm:text-3xl">¿Qué estás buscando?</h2></div><a href="{{ route('catalog') }}" class="focus-ring text-xs font-bold text-blue-700 hover:text-violet-700">Ver todo <span aria-hidden="true">→</span></a></div>
        <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-4">
            @foreach($categories as $category)
                @php
                    $categoryName = strtolower($category->name);
                    $icon = str_contains($categoryName, 'laptop') ? 'fa-laptop' : (str_contains($categoryName, 'gaming') ? 'fa-gamepad' : (str_contains($categoryName, 'audio') || str_contains($categoryName, 'parlante') ? 'fa-headphones' : (str_contains($categoryName, 'accesorio') ? 'fa-keyboard' : 'fa-microchip')));
                    $categoryColors = ['from-blue-50 to-indigo-100', 'from-violet-50 to-purple-100', 'from-cyan-50 to-blue-100', 'from-fuchsia-50 to-violet-100'];
                @endphp
                <a href="{{ route('catalog', ['category' => $category->slug]) }}" class="focus-ring group flex min-h-[150px] flex-col justify-between overflow-hidden rounded-2xl border border-slate-200 bg-gradient-to-br {{ $categoryColors[$loop->index % count($categoryColors)] }} p-4 transition hover:-translate-y-1 hover:shadow-lg sm:min-h-[180px] sm:p-5">
                    <i class="fa-solid {{ $icon }} self-end text-4xl text-[#293ca0]/35 transition group-hover:scale-110 group-hover:text-indigo-600/70 sm:text-5xl" aria-hidden="true"></i>
                    <span><span class="block font-display text-base font-extrabold text-[#111c36] sm:text-lg">{{ $category->name }}</span><span class="mt-1 block text-[10px] font-semibold text-slate-500">Explorar productos <span aria-hidden="true">↗</span></span></span>
                </a>
            @endforeach
        </div>
    </section>

    @if($offerProducts->isNotEmpty())
        <section aria-labelledby="offers-title">
            <div class="mb-5 flex items-end justify-between gap-3"><div><p class="text-[10px] font-bold uppercase tracking-[.18em] text-violet-600">Precio especial</p><h2 id="offers-title" class="mt-1 font-display text-2xl font-extrabold text-[#111c36] sm:text-3xl">Ofertas destacadas</h2><p class="mt-1 text-xs text-slate-500">Oportunidades disponibles por tiempo limitado.</p></div><a href="{{ route('catalog', ['offers' => 1]) }}" class="focus-ring rounded-lg border border-slate-200 bg-white px-3 py-2 text-[10px] font-bold text-blue-700 hover:border-indigo-300">Ver ofertas <span aria-hidden="true">→</span></a></div>
            <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-4">
                @foreach($offerProducts->take(4) as $product)
                    @include('front.partials.product-card', ['product' => $product, 'whatsappNumber' => $whatsappNumber])
                @endforeach
            </div>
        </section>
    @endif

    <section class="grid gap-3 md:grid-cols-2" aria-label="Colecciones">
        <div class="relative min-h-[200px] overflow-hidden rounded-2xl bg-gradient-to-br from-[#07182f] via-[#06394d] to-[#0b766e] p-6 text-white sm:min-h-[230px] sm:p-8">
            <div class="relative z-10 max-w-sm"><p class="text-[9px] font-bold uppercase tracking-[.2em] text-teal-200">Compra acompañada</p><h2 class="mt-3 font-display text-2xl font-extrabold leading-tight sm:text-3xl">Encuentra el equipo que va contigo.</h2><a href="https://wa.me/{{ $whatsappNumber }}?text={{ urlencode('Hola, quisiera una recomendación de equipo.') }}" target="_blank" rel="noopener noreferrer" class="focus-ring mt-5 inline-flex items-center gap-2 rounded-lg bg-white px-4 py-2.5 text-xs font-bold text-slate-900">Hablar con un asesor <i class="fa-solid fa-arrow-right"></i></a></div>
            <i class="fa-solid fa-headset absolute bottom-[-15px] right-4 text-[130px] text-white/10" aria-hidden="true"></i>
        </div>
        <div class="relative min-h-[200px] overflow-hidden rounded-2xl bg-gradient-to-br from-[#271459] via-[#4926a0] to-[#793ce1] p-6 text-white sm:min-h-[230px] sm:p-8">
            <div class="relative z-10 max-w-sm"><p class="text-[9px] font-bold uppercase tracking-[.2em] text-violet-200">Colección MPC</p><h2 class="mt-3 font-display text-2xl font-extrabold leading-tight sm:text-3xl">Tecnología lista para tus próximos proyectos.</h2><a href="{{ route('catalog') }}" class="focus-ring mt-5 inline-flex items-center gap-2 rounded-lg bg-white px-4 py-2.5 text-xs font-bold text-violet-900">Descubrir catálogo <i class="fa-solid fa-arrow-right"></i></a></div>
            <i class="fa-solid fa-microchip absolute bottom-[-18px] right-4 text-[130px] text-white/10" aria-hidden="true"></i>
        </div>
    </section>

    @if($featuredProducts->isNotEmpty())
        <section aria-labelledby="featured-title">
            <div class="mb-5 flex items-end justify-between gap-3"><div><p class="text-[10px] font-bold uppercase tracking-[.18em] text-violet-600">Selección MPC</p><h2 id="featured-title" class="mt-1 font-display text-2xl font-extrabold text-[#111c36] sm:text-3xl">Equipos recomendados</h2></div><a href="{{ route('catalog') }}" class="focus-ring text-xs font-bold text-blue-700 hover:text-violet-700">Explorar catálogo <span aria-hidden="true">→</span></a></div>
            <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-4">
                @foreach($featuredProducts->take(8) as $product)
                    @include('front.partials.product-card', ['product' => $product, 'whatsappNumber' => $whatsappNumber])
                @endforeach
            </div>
        </section>
    @endif

    @if($brands->isNotEmpty())
        <section id="marcas" aria-labelledby="brands-title" class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-7">
            <div class="mb-5 flex items-end justify-between"><div><p class="text-[10px] font-bold uppercase tracking-[.18em] text-violet-600">Marcas disponibles</p><h2 id="brands-title" class="mt-1 font-display text-xl font-extrabold text-[#111c36] sm:text-2xl">Encuentra tus favoritas</h2></div><i class="fa-solid fa-award text-2xl text-indigo-400"></i></div>
            <div class="grid grid-cols-2 gap-2 sm:grid-cols-3 lg:grid-cols-6">
                @foreach($brands as $brand)
                    <a href="{{ route('catalog', ['brand' => $brand->slug]) }}" class="focus-ring flex min-h-14 items-center justify-center rounded-xl border border-slate-100 bg-slate-50 px-3 text-center text-xs font-extrabold tracking-wide text-slate-600 transition hover:border-indigo-200 hover:bg-indigo-50 hover:text-indigo-700">{{ $brand->name }}</a>
                @endforeach
            </div>
        </section>
    @endif

    <section class="flex flex-col justify-between gap-5 rounded-2xl bg-gradient-to-r from-[#101c3b] via-[#15377c] to-[#42258f] p-6 text-white sm:flex-row sm:items-center sm:p-8">
        <div><p class="text-[9px] font-bold uppercase tracking-[.18em] text-indigo-200">MPC Antigravity</p><h2 class="mt-2 font-display text-2xl font-extrabold">¿Listo para actualizar tu equipo?</h2><p class="mt-2 text-xs text-blue-100/80">Conversemos sobre lo que necesitas.</p></div>
        <a href="https://wa.me/{{ $whatsappNumber }}?text={{ urlencode('Hola, quiero recibir asesoría para elegir un equipo.') }}" target="_blank" rel="noopener noreferrer" class="focus-ring inline-flex items-center justify-center gap-2 rounded-xl bg-[#25d366] px-5 py-3 text-xs font-extrabold text-white shadow-lg hover:bg-emerald-500"><i class="fa-brands fa-whatsapp text-base"></i> COMPRAR POR WHATSAPP</a>
    </section>
</div>
@endsection
