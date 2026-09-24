@extends('layouts.public')

@section('title', $product->name)
@section('meta_description', Str::limit(strip_tags($product->description ?: $product->name), 155))

@section('content')
@php
    $whatsappNumber = preg_replace('/[^0-9]/', '', \App\Models\Setting::where('key', 'whatsapp_number')->value('value') ?? '');
    $price = $product->is_offer && $product->offer_price ? $product->offer_price : $product->price;
    $gallery = $product->images->sortByDesc('is_main')->values();
    $mainImage = $gallery->first()?->image_path;
    $mainImageUrl = $mainImage ? (filter_var($mainImage, FILTER_VALIDATE_URL) ? $mainImage : asset('storage/' . $mainImage)) : null;
    $whatsappMessage = 'Hola, quiero COMPRAR POR WHATSAPP: ' . $product->name . ' — S/ ' . number_format((float) $price, 2) . '. ' . route('product.show', $product->slug);
@endphp

<div class="mx-auto max-w-[1440px] px-4 py-6 sm:px-7 sm:py-9">
    <nav aria-label="Ruta de navegación" class="mb-5 flex flex-wrap items-center gap-2 text-[10px] text-slate-500"><a href="{{ route('home') }}" class="focus-ring hover:text-blue-700">Inicio</a><i class="fa-solid fa-chevron-right text-[8px] text-slate-300"></i><a href="{{ route('catalog', ['category' => $product->category->slug ?? null]) }}" class="focus-ring hover:text-blue-700">{{ $product->category->name ?? 'Catálogo' }}</a><i class="fa-solid fa-chevron-right text-[8px] text-slate-300"></i><span class="font-semibold text-slate-700">{{ $product->name }}</span></nav>

    <section class="grid gap-6 lg:grid-cols-[1.2fr_.8fr]">
        <div class="grid gap-3 sm:grid-cols-[82px_minmax(0,1fr)]">
            @if($gallery->count() > 1)
                <div class="order-2 flex gap-2 overflow-x-auto sm:order-1 sm:flex-col">
                    @foreach($gallery->take(4) as $image)
                        @php
                            $imgUrl = filter_var($image->image_path, FILTER_VALIDATE_URL) ? $image->image_path : asset('storage/' . $image->image_path);
                        @endphp
                        <button type="button" onclick="document.getElementById('main-product-image').src = '{{ $imgUrl }}'" class="relative aspect-square h-16 w-16 shrink-0 overflow-hidden rounded-xl border border-slate-200 bg-white p-2 transition hover:border-indigo-400 focus:border-indigo-500 focus:outline-none sm:h-[74px] sm:w-[74px]"><img src="{{ $imgUrl }}" alt="{{ $product->name }} — imagen {{ $loop->iteration }}" class="h-full w-full object-contain"></button>
                    @endforeach
                </div>
            @endif
            <div id="image-zoom-container" class="relative order-1 flex min-h-[350px] cursor-zoom-in items-center justify-center overflow-hidden rounded-2xl border border-slate-200 bg-gradient-to-br from-[#eef1fa] via-white to-[#e7eaff] p-2 sm:min-h-[550px] sm:p-4 {{ $gallery->count() > 1 ? 'sm:order-2' : 'sm:col-span-2' }}">
                <div class="absolute right-[-10%] top-[-10%] h-64 w-64 rounded-full bg-violet-200/40 blur-3xl"></div>
                @if($product->is_offer && $product->offer_price)<span class="absolute left-5 top-5 z-10 rounded-lg bg-rose-500 px-3 py-1.5 text-[10px] font-extrabold text-white">PRECIO ESPECIAL</span>@endif
                @if($mainImage)
                    <img id="main-product-image" src="{{ filter_var($mainImage, FILTER_VALIDATE_URL) ? $mainImage : asset('storage/' . $mainImage) }}" alt="{{ $product->name }}" class="relative z-10 max-h-[550px] w-full object-contain mix-blend-multiply drop-shadow-xl" style="transition: transform 0.1s ease-out;">
                @else
                    <div class="relative z-10 flex h-64 w-[78%] items-center justify-center rounded-[2rem] border border-slate-300 bg-gradient-to-br from-white via-slate-200 to-slate-400 p-4 shadow-2xl sm:h-[330px]">
                        <div class="flex h-[86%] w-[88%] items-center justify-center rounded-xl bg-gradient-to-br from-[#101b36] via-[#2458c9] to-[#763fe1] text-6xl text-white/80 shadow-inner"><i class="fa-solid fa-laptop" aria-hidden="true"></i></div>
                    </div>
                @endif
                <span class="absolute bottom-4 right-5 z-10 text-[9px] font-semibold uppercase tracking-[.16em] text-slate-400">{{ $product->brand->name ?? 'MPC Antigravity' }}</span>
            </div>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-8">
            <div class="flex items-center justify-between gap-3"><span class="text-[10px] font-extrabold uppercase tracking-[.16em] text-violet-600">{{ $product->brand->name ?? 'MPC Antigravity' }}</span><span class="rounded-full px-3 py-1 text-[9px] font-bold {{ $product->stock > 0 ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700' }}">{{ $product->stock > 0 ? 'Disponible' : 'Consultar stock' }}</span></div>
            <h1 class="mt-3 font-display text-2xl font-extrabold leading-tight text-[#142143] sm:text-4xl">{{ $product->name }}</h1>
            <p class="mt-2 text-xs text-slate-500">Código: {{ $product->code }}@if($product->deviceModel) <span class="px-1">·</span> Modelo: {{ $product->deviceModel->name }}@endif</p>
            <a href="#opiniones" class="mt-3 inline-flex items-center gap-2 text-xs font-semibold text-slate-500 hover:text-indigo-700"><span class="text-amber-400">@for($star = 1; $star <= 5; $star++)<i class="fa-solid fa-star {{ $star <= round((float) $product->reviews_avg_rating) ? '' : 'text-slate-200' }}"></i>@endfor</span><span>{{ $product->reviews_count }} opiniones</span></a>
            <p class="mt-4 text-[10px] font-semibold text-indigo-600"><i class="fa-solid fa-circle-check mr-1"></i> Equipo seleccionado por MPC Antigravity</p>
            <p class="mt-5 text-sm leading-6 text-slate-600">{{ $product->description ?: 'Consulta las especificaciones y disponibilidad con nuestro equipo.' }}</p>

            <div class="mt-6 rounded-xl bg-slate-50 p-4 sm:p-5">
                <p class="text-[9px] font-bold uppercase tracking-[.14em] text-slate-400">Precio de referencia</p>
                @if($product->is_offer && $product->offer_price)<p class="mt-2 text-xs text-slate-400 line-through">S/ {{ number_format((float) $product->price, 2) }}</p>@endif
                <p class="font-display text-3xl font-extrabold text-[#15264d]">S/ {{ number_format((float) $price, 2) }}</p>
                <p class="mt-2 text-[10px] text-slate-500"><i class="fa-solid fa-box mr-1 text-indigo-500"></i>{{ $product->stock > 0 ? $product->stock . ' unidades disponibles' : 'Confirma disponibilidad con un asesor' }}</p>
            </div>

            <div data-product-quantity class="mt-5 flex gap-2">
                <label for="product-quantity" class="sr-only">Cantidad</label><input id="product-quantity" type="number" min="1" max="{{ max(1, $product->stock) }}" value="1" @disabled($product->stock < 1) class="h-12 w-20 rounded-xl border border-slate-200 bg-slate-50 px-3 text-center text-sm font-bold outline-none focus:border-indigo-400">
                <button type="button" data-add-to-cart data-product-id="{{ $product->id }}" data-product-name="{{ $product->name }}" data-product-price="{{ (float) $price }}" data-product-stock="{{ (int) $product->stock }}" data-product-image="{{ $mainImageUrl ?? '' }}" data-product-url="{{ route('product.show', $product->slug) }}" @disabled($product->stock < 1) class="focus-ring flex min-h-12 flex-1 items-center justify-center gap-2 rounded-xl border border-indigo-200 bg-indigo-50 px-3 text-xs font-extrabold text-indigo-700 transition hover:bg-indigo-100 disabled:cursor-not-allowed disabled:opacity-50 sm:text-sm"><i class="fa-solid fa-bag-shopping" aria-hidden="true"></i> AGREGAR AL CARRITO</button>
            </div>
            <a href="https://wa.me/{{ $whatsappNumber }}?text={{ urlencode($whatsappMessage) }}" target="_blank" rel="noopener noreferrer" class="focus-ring mt-2 flex min-h-12 items-center justify-center gap-2 rounded-xl bg-[#25d366] px-4 text-sm font-extrabold text-white shadow-lg shadow-emerald-600/15 transition hover:bg-emerald-600"><i class="fa-brands fa-whatsapp text-lg" aria-hidden="true"></i> COMPRAR POR WHATSAPP</a>
            <a href="{{ route('product.pdf', $product->slug) }}" class="focus-ring mt-2 flex min-h-12 items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-4 text-sm font-extrabold text-slate-700 shadow-sm transition hover:bg-slate-50"><i class="fa-solid fa-file-pdf text-lg text-rose-500" aria-hidden="true"></i> DESCARGAR FICHA TÉCNICA</a>
            <p class="mt-2 text-center text-[9px] text-slate-400">Un asesor confirmará el precio y coordinará contigo.</p>

            <div class="mt-6 grid grid-cols-2 gap-3 border-t border-slate-100 pt-5">
                <div class="flex items-start gap-2"><i class="fa-solid fa-shield-halved mt-0.5 text-sm text-indigo-500"></i><span><b class="block text-[10px] text-slate-700">Garantía</b><span class="mt-1 block text-[9px] text-slate-400">{{ $product->warranty ?: 'Consulta condiciones' }}</span></span></div>
                <div class="flex items-start gap-2"><i class="fa-solid fa-truck-fast mt-0.5 text-sm text-indigo-500"></i><span><b class="block text-[10px] text-slate-700">Envío</b><span class="mt-1 block text-[9px] text-slate-400">Coordinación nacional</span></span></div>
            </div>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const container = document.getElementById('image-zoom-container');
            const img = document.getElementById('main-product-image');
            
            if (container && img) {
                container.addEventListener('mousemove', (e) => {
                    const { left, top, width, height } = container.getBoundingClientRect();
                    const x = ((e.clientX - left) / width) * 100;
                    const y = ((e.clientY - top) / height) * 100;
                    
                    img.style.transformOrigin = `${x}% ${y}%`;
                    img.style.transform = 'scale(2.5)';
                });
                
                container.addEventListener('mouseleave', () => {
                    img.style.transformOrigin = 'center center';
                    img.style.transform = 'scale(1)';
                });
            }
        });
    </script>

    <section class="mt-7 grid gap-5 lg:grid-cols-[1.1fr_.9fr]">
        <div class="rounded-2xl border border-slate-200 bg-white p-5 sm:p-7">
            <p class="text-[9px] font-extrabold uppercase tracking-[.16em] text-violet-600">Información técnica</p><h2 class="mt-2 font-display text-xl font-extrabold text-[#142143]">Especificaciones del equipo</h2>
            <div class="mt-5 divide-y divide-slate-100 border-y border-slate-100">
                <div class="grid grid-cols-[minmax(110px,.6fr)_1fr] gap-3 py-3 text-xs"><span class="font-semibold text-slate-500">Marca</span><span class="text-slate-800">{{ $product->brand->name ?? '—' }}</span></div>
                <div class="grid grid-cols-[minmax(110px,.6fr)_1fr] gap-3 py-3 text-xs"><span class="font-semibold text-slate-500">Categoría</span><span class="text-slate-800">{{ $product->category->name ?? '—' }}</span></div>
                @if(is_array($product->technical_specs))
                    @foreach($product->technical_specs as $key => $value)
                        <div class="grid grid-cols-[minmax(110px,.6fr)_1fr] gap-3 py-3 text-xs"><span class="font-semibold text-slate-500">{{ $key }}</span><span class="text-slate-800">{{ $value }}</span></div>
                    @endforeach
                @endif
                <div class="grid grid-cols-[minmax(110px,.6fr)_1fr] gap-3 py-3 text-xs"><span class="font-semibold text-slate-500">Garantía</span><span class="text-slate-800">{{ $product->warranty ?: 'Consultar' }}</span></div>
            </div>
        </div>
        <div class="rounded-2xl bg-gradient-to-br from-[#101c3b] to-[#263c83] p-6 text-white sm:p-7">
            <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-white/10 text-xl text-indigo-200"><i class="fa-solid fa-headset"></i></span><h2 class="mt-4 font-display text-xl font-extrabold">¿Tienes dudas sobre este equipo?</h2><p class="mt-2 text-xs leading-5 text-blue-100/75">Escríbenos y te ayudaremos con las especificaciones, disponibilidad y opciones de entrega.</p><a href="https://wa.me/{{ $whatsappNumber }}?text={{ urlencode('Hola, tengo una consulta sobre ' . $product->name . '.') }}" target="_blank" rel="noopener noreferrer" class="focus-ring mt-5 inline-flex items-center gap-2 rounded-lg bg-white px-4 py-2.5 text-xs font-bold text-blue-800 hover:bg-indigo-50"><i class="fa-brands fa-whatsapp"></i> Consultar por WhatsApp</a>
        </div>
    </section>

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
