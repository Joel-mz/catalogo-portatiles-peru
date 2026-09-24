@extends('layouts.public')

@section('title', 'Catálogo de equipos')
@section('meta_description', 'Explora laptops y tecnología disponibles en MPC Antigravity. Filtra por categoría, marca y precio.')

@section('content')
@php
    $whatsappNumber = preg_replace('/[^0-9]/', '', \App\Models\Setting::where('key', 'whatsapp_number')->value('value') ?? '');
@endphp

<div class="mx-auto max-w-[1440px] space-y-7 px-4 py-6 sm:px-7 sm:py-9">
    <section class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-[#0b1730] via-[#152e67] to-[#4630a2] px-5 py-8 text-white sm:px-9 sm:py-10">
        <div class="absolute -right-8 -top-20 h-64 w-64 rounded-full bg-indigo-400/20 blur-3xl"></div>
        <div class="relative z-10 flex flex-col justify-between gap-5 sm:flex-row sm:items-end">
            <div><p class="text-[9px] font-bold uppercase tracking-[.2em] text-indigo-200">MPC Antigravity · Catálogo</p><h1 class="mt-3 font-display text-3xl font-extrabold tracking-tight sm:text-4xl">Encuentra tu próximo equipo.</h1><p class="mt-2 max-w-xl text-xs leading-5 text-blue-100/80">Explora opciones, compara precios y recibe asesoría para elegir con confianza.</p></div>
            <div class="flex items-center gap-3 rounded-xl border border-white/15 bg-white/10 px-4 py-3 backdrop-blur"><i class="fa-solid fa-boxes-stacked text-xl text-indigo-200"></i><span><span class="block font-display text-xl font-extrabold">{{ $products->total() }}</span><span class="block text-[9px] text-blue-100">equipos disponibles</span></span></div>
        </div>
    </section>

    <section class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-5" aria-labelledby="filters-title">
        <div class="mb-4 flex flex-wrap items-center justify-between gap-3"><div><p class="text-[9px] font-bold uppercase tracking-[.16em] text-violet-600">Busca a tu manera</p><h2 id="filters-title" class="mt-1 text-sm font-extrabold text-[#152244]">Filtrar productos</h2></div>@if(request()->hasAny(['q', 'category', 'brand', 'offers', 'min_price', 'max_price', 'sort', 'in_stock']))<a href="{{ route('catalog') }}" class="text-[10px] font-bold text-blue-700 underline underline-offset-4">Limpiar filtros</a>@endif</div>
        <form action="{{ route('catalog') }}" method="GET" class="grid gap-3 sm:grid-cols-2 lg:grid-cols-6 lg:items-end">
            <div class="lg:col-span-2"><label for="filter-search" class="mb-1.5 block text-[10px] font-bold text-slate-500">Palabra clave</label><div class="flex h-10 items-center gap-2 rounded-lg border border-slate-200 bg-slate-50 px-3 focus-within:border-indigo-400"><i class="fa-solid fa-magnifying-glass text-xs text-slate-400"></i><input id="filter-search" name="q" value="{{ request('q') }}" type="search" placeholder="Producto, modelo o código" class="min-w-0 flex-1 bg-transparent text-xs outline-none"></div></div>
            <div><label for="filter-category" class="mb-1.5 block text-[10px] font-bold text-slate-500">Categoría</label><select id="filter-category" name="category" class="h-10 w-full rounded-lg border border-slate-200 bg-slate-50 px-3 text-xs outline-none focus:border-indigo-400"><option value="">Todas</option>@foreach($categories as $category)<option value="{{ $category->slug }}" @selected(request('category') === $category->slug)>{{ $category->name }}</option>@endforeach</select></div>
            <div><label for="filter-brand" class="mb-1.5 block text-[10px] font-bold text-slate-500">Marca</label><select id="filter-brand" name="brand" class="h-10 w-full rounded-lg border border-slate-200 bg-slate-50 px-3 text-xs outline-none focus:border-indigo-400"><option value="">Todas</option>@foreach($brands as $brand)<option value="{{ $brand->slug }}" @selected(request('brand') === $brand->slug)>{{ $brand->name }}</option>@endforeach</select></div>
            <div><label for="sort-order" class="mb-1.5 block text-[10px] font-bold text-slate-500">Ordenar</label><select id="sort-order" name="sort" class="h-10 w-full rounded-lg border border-slate-200 bg-slate-50 px-3 text-xs outline-none focus:border-indigo-400"><option value="newest" @selected($sort === 'newest')>Más recientes</option><option value="popular" @selected($sort === 'popular')>Destacados</option><option value="price_asc" @selected($sort === 'price_asc')>Menor precio</option><option value="price_desc" @selected($sort === 'price_desc')>Mayor precio</option></select></div>
            <button type="submit" class="focus-ring h-10 rounded-lg bg-gradient-to-r from-[#2855d9] to-[#623ee0] px-4 text-xs font-bold text-white shadow-md shadow-indigo-700/15 hover:from-[#2149bf] hover:to-[#5030c1] lg:col-span-1"><i class="fa-solid fa-sliders mr-2"></i>Aplicar</button>
            <div class="grid grid-cols-2 gap-2 lg:col-span-2"><div><label for="minimum-price" class="mb-1.5 block text-[10px] font-bold text-slate-500">Desde S/</label><input id="minimum-price" name="min_price" value="{{ request('min_price') }}" type="number" min="0" step="1" placeholder="{{ number_format((float) $minPrice, 0) }}" class="h-10 w-full rounded-lg border border-slate-200 bg-slate-50 px-3 text-xs outline-none focus:border-indigo-400"></div><div><label for="maximum-price" class="mb-1.5 block text-[10px] font-bold text-slate-500">Hasta S/</label><input id="maximum-price" name="max_price" value="{{ request('max_price') }}" type="number" min="0" step="1" placeholder="{{ number_format((float) $maxPrice, 0) }}" class="h-10 w-full rounded-lg border border-slate-200 bg-slate-50 px-3 text-xs outline-none focus:border-indigo-400"></div></div>
            <div class="flex flex-wrap items-center gap-4 lg:col-span-4"><label class="flex items-center gap-2 text-[10px] font-semibold text-slate-600"><input type="checkbox" name="offers" value="1" @checked(request()->boolean('offers')) class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">Solo ofertas</label><label class="flex items-center gap-2 text-[10px] font-semibold text-slate-600"><input type="checkbox" name="in_stock" value="1" @checked(request()->filled('in_stock')) class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">Disponibles</label></div>
        </form>
    </section>

    <section aria-labelledby="results-title">
        <div class="mb-4 flex flex-wrap items-center justify-between gap-3"><div><h2 id="results-title" class="font-display text-xl font-extrabold text-[#152244]">{{ request('q') ? 'Resultados para “' . request('q') . '”' : 'Todos los equipos' }}</h2><p class="mt-1 text-[10px] text-slate-500">{{ $products->total() }} productos · Página {{ $products->currentPage() }}</p></div><span class="rounded-full bg-indigo-50 px-3 py-1.5 text-[9px] font-bold text-indigo-700">Asesoría disponible por WhatsApp</span></div>
        @if($products->isNotEmpty())
            <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-4">
                @foreach($products as $product)
                    @include('front.partials.product-card', ['product' => $product, 'whatsappNumber' => $whatsappNumber])
                @endforeach
            </div>
            @if($products->hasPages())<div class="mt-7 rounded-xl border border-slate-200 bg-white p-4">{{ $products->links() }}</div>@endif
        @else
            <div class="rounded-2xl border border-slate-200 bg-white px-5 py-16 text-center shadow-sm"><span class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-indigo-50 text-xl text-indigo-500"><i class="fa-solid fa-box-open"></i></span><h3 class="mt-4 font-display text-xl font-extrabold text-[#152244]">No encontramos ese producto</h3><p class="mt-2 text-xs text-slate-500">Prueba con otra búsqueda o cambia los filtros.</p><a href="{{ route('catalog') }}" class="focus-ring mt-5 inline-flex rounded-lg bg-indigo-600 px-4 py-2.5 text-xs font-bold text-white hover:bg-indigo-700">Ver todo el catálogo</a></div>
        @endif
    </section>
</div>
@endsection
