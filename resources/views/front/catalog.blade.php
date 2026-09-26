@extends('layouts.public')

@section('title', 'Catálogo de equipos')
@section('meta_description', 'Explora laptops y tecnología disponibles en MPC Antigravity. Filtra por categoría, marca y precio.')

@section('content')
@php
    $whatsappNumber = preg_replace('/[^0-9]/', '', \App\Models\Setting::where('key', 'whatsapp_number')->value('value') ?? '');
@endphp

<div class="mx-auto max-w-[1440px] px-4 py-8 sm:px-7 sm:py-12">
    <div class="flex flex-col gap-8 lg:flex-row lg:items-start">
        
        <!-- Sidebar Filters -->
        <aside class="w-full shrink-0 lg:w-72 xl:w-80">
            <div class="sticky top-24 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
                <div class="mb-6 flex items-center justify-between">
                    <div>
                        <h2 id="filters-title" class="font-display text-lg font-extrabold text-[#152244]">Filtros</h2>
                        <p class="text-[11px] text-slate-500">Encuentra tu equipo ideal</p>
                    </div>
                    @if(request()->hasAny(['q', 'category', 'brand', 'offers', 'min_price', 'max_price', 'sort', 'in_stock']))
                        <a href="{{ route('catalog') }}" class="text-[11px] font-bold text-blue-700 hover:text-blue-800 underline underline-offset-4 transition">Limpiar</a>
                    @endif
                </div>
                
                <form action="{{ route('catalog') }}" method="GET" class="flex flex-col gap-6">
                    <div>
                        <label for="filter-search" class="mb-2 block text-xs font-bold text-slate-700">Buscar</label>
                        <div class="flex h-11 items-center gap-2 rounded-xl border border-slate-200 bg-slate-50 px-3 transition-colors focus-within:border-indigo-400 focus-within:bg-white focus-within:ring-2 focus-within:ring-indigo-100">
                            <i class="fa-solid fa-magnifying-glass text-slate-400"></i>
                            <input id="filter-search" name="q" value="{{ request('q') }}" type="search" placeholder="Producto o modelo..." class="min-w-0 flex-1 bg-transparent text-sm outline-none">
                        </div>
                    </div>
                    
                    <div>
                        <label for="filter-category" class="mb-2 block text-xs font-bold text-slate-700">Categoría</label>
                        <div class="relative">
                            <select id="filter-category" name="category" class="h-11 w-full appearance-none rounded-xl border border-slate-200 bg-slate-50 px-4 pr-10 text-sm outline-none transition-colors focus:border-indigo-400 focus:bg-white focus:ring-2 focus:ring-indigo-100">
                                <option value="">Todas las categorías</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->slug }}" @selected(request('category') === $category->slug)>{{ $category->name }}</option>
                                @endforeach
                            </select>
                            <i class="fa-solid fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-[10px] text-slate-400 pointer-events-none"></i>
                        </div>
                    </div>

                    <div>
                        <label for="filter-brand" class="mb-2 block text-xs font-bold text-slate-700">Marca</label>
                        <div class="relative">
                            <select id="filter-brand" name="brand" class="h-11 w-full appearance-none rounded-xl border border-slate-200 bg-slate-50 px-4 pr-10 text-sm outline-none transition-colors focus:border-indigo-400 focus:bg-white focus:ring-2 focus:ring-indigo-100">
                                <option value="">Todas las marcas</option>
                                @foreach($brands as $brand)
                                    <option value="{{ $brand->slug }}" @selected(request('brand') === $brand->slug)>{{ $brand->name }}</option>
                                @endforeach
                            </select>
                            <i class="fa-solid fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-[10px] text-slate-400 pointer-events-none"></i>
                        </div>
                    </div>
                    
                    <div>
                        <label class="mb-2 block text-xs font-bold text-slate-700">Rango de Precio</label>
                        <div class="flex items-center gap-3">
                            <div class="relative flex-1">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-xs text-slate-400">S/</span>
                                <input id="minimum-price" name="min_price" value="{{ request('min_price') }}" type="number" min="0" step="1" placeholder="{{ number_format((float) $minPrice, 0) }}" class="h-11 w-full rounded-xl border border-slate-200 bg-slate-50 pl-8 pr-3 text-sm outline-none transition-colors focus:border-indigo-400 focus:bg-white focus:ring-2 focus:ring-indigo-100">
                            </div>
                            <span class="text-slate-400">-</span>
                            <div class="relative flex-1">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-xs text-slate-400">S/</span>
                                <input id="maximum-price" name="max_price" value="{{ request('max_price') }}" type="number" min="0" step="1" placeholder="{{ number_format((float) $maxPrice, 0) }}" class="h-11 w-full rounded-xl border border-slate-200 bg-slate-50 pl-8 pr-3 text-sm outline-none transition-colors focus:border-indigo-400 focus:bg-white focus:ring-2 focus:ring-indigo-100">
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <label for="sort-order" class="mb-2 block text-xs font-bold text-slate-700">Ordenar por</label>
                        <div class="relative">
                            <select id="sort-order" name="sort" class="h-11 w-full appearance-none rounded-xl border border-slate-200 bg-slate-50 px-4 pr-10 text-sm outline-none transition-colors focus:border-indigo-400 focus:bg-white focus:ring-2 focus:ring-indigo-100">
                                <option value="newest" @selected($sort === 'newest')>Más recientes</option>
                                <option value="popular" @selected($sort === 'popular')>Destacados</option>
                                <option value="price_asc" @selected($sort === 'price_asc')>Menor precio</option>
                                <option value="price_desc" @selected($sort === 'price_desc')>Mayor precio</option>
                            </select>
                            <i class="fa-solid fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-[10px] text-slate-400 pointer-events-none"></i>
                        </div>
                    </div>

                    <div class="flex flex-col gap-3 rounded-xl bg-slate-50 p-4">
                        <label class="flex cursor-pointer items-center gap-3">
                            <div class="relative flex items-center">
                                <input type="checkbox" name="offers" value="1" @checked(request()->boolean('offers')) class="peer h-5 w-5 cursor-pointer appearance-none rounded border-2 border-slate-300 bg-white transition-all checked:border-indigo-600 checked:bg-indigo-600 hover:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-600/20">
                                <i class="fa-solid fa-check absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 text-[10px] text-white opacity-0 transition-opacity peer-checked:opacity-100 pointer-events-none"></i>
                            </div>
                            <span class="text-sm font-semibold text-slate-700 select-none">Solo ofertas</span>
                        </label>
                        <label class="flex cursor-pointer items-center gap-3">
                            <div class="relative flex items-center">
                                <input type="checkbox" name="in_stock" value="1" @checked(request()->filled('in_stock')) class="peer h-5 w-5 cursor-pointer appearance-none rounded border-2 border-slate-300 bg-white transition-all checked:border-indigo-600 checked:bg-indigo-600 hover:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-600/20">
                                <i class="fa-solid fa-check absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 text-[10px] text-white opacity-0 transition-opacity peer-checked:opacity-100 pointer-events-none"></i>
                            </div>
                            <span class="text-sm font-semibold text-slate-700 select-none">Disponibles ahora</span>
                        </label>
                    </div>

                    <button type="submit" class="focus-ring mt-2 flex h-12 w-full items-center justify-center gap-2 rounded-xl bg-[#111c36] text-sm font-extrabold text-white transition hover:bg-slate-800 shadow-lg shadow-slate-900/10">
                        <i class="fa-solid fa-sliders"></i> Aplicar filtros
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 min-w-0">
            <section aria-labelledby="results-title">
                <div class="mb-6 flex flex-wrap items-center justify-between gap-4 border-b border-slate-200 pb-4">
                    <div>
                        <h2 id="results-title" class="font-display text-2xl font-extrabold text-[#111c36]">{{ request('q') ? 'Resultados para “' . request('q') . '”' : 'Catálogo completo' }}</h2>
                        <p class="mt-1 text-xs text-slate-500">Mostrando {{ $products->count() }} de {{ $products->total() }} productos</p>
                    </div>
                </div>

                @if($products->isNotEmpty())
                    <div class="grid grid-cols-2 gap-4 sm:gap-5 lg:grid-cols-3 xl:grid-cols-4">
                        @foreach($products as $product)
                            @include('front.partials.product-card', ['product' => $product, 'whatsappNumber' => $whatsappNumber])
                        @endforeach
                    </div>
                    @if($products->hasPages())
                        <div class="mt-10">{{ $products->links() }}</div>
                    @endif
                @else
                    <div class="flex min-h-[400px] flex-col items-center justify-center rounded-2xl border border-slate-200 bg-white px-5 py-16 text-center shadow-sm">
                        <span class="mb-6 flex h-20 w-20 items-center justify-center rounded-full bg-slate-50 text-3xl text-slate-300">
                            <i class="fa-solid fa-box-open"></i>
                        </span>
                        <h3 class="font-display text-2xl font-extrabold text-[#111c36]">No encontramos productos</h3>
                        <p class="mt-2 max-w-sm text-sm text-slate-500">No hay equipos que coincidan con tus filtros actuales. Intenta eliminando algunos o realizando otra búsqueda.</p>
                        <a href="{{ route('catalog') }}" class="focus-ring mt-6 inline-flex h-11 items-center justify-center rounded-xl bg-indigo-600 px-6 text-sm font-bold text-white transition hover:bg-indigo-700 shadow-sm">
                            Limpiar todos los filtros
                        </a>
                    </div>
                @endif
            </section>
        </main>
    </div>
</div>
@endsection
