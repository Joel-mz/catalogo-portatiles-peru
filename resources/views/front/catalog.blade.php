@extends('layouts.public')

@section('title', 'Catálogo de equipos')
@section('meta_description', 'Explora laptops y tecnología disponibles en MPC Antigravity. Filtra por categoría, marca y precio.')

@section('content')
@php
    $whatsappNumber = preg_replace('/[^0-9]/', '', \App\Models\Setting::where('key', 'whatsapp_number')->value('value') ?? '');
    
    $activeFilterCount = 0;
    if(request('q')) $activeFilterCount++;
    if(request('category')) $activeFilterCount++;
    if(request('brand')) $activeFilterCount++;
    if(request('offers')) $activeFilterCount++;
    if(request('in_stock')) $activeFilterCount++;
    if(request('min_price') || request('max_price')) $activeFilterCount++;
    if(request('sort') && request('sort') !== 'newest') $activeFilterCount++;
@endphp

<div class="mx-auto max-w-[1440px] px-4 py-6 sm:px-7 sm:py-10">
    <div class="flex flex-col gap-8 lg:flex-row lg:items-start">
        
        <!-- Desktop Sidebar Filters (Hidden on Mobile) -->
        <aside class="hidden lg:block w-72 xl:w-80 shrink-0">
            <div class="sticky top-24 rounded-3xl border border-slate-200/80 bg-white p-6 shadow-sm">
                <div class="mb-6 flex items-center justify-between border-b border-slate-100 pb-4">
                    <div>
                        <h2 id="filters-title" class="font-display text-lg font-extrabold text-slate-900 flex items-center gap-2">
                            <i class="fa-solid fa-sliders text-indigo-600 text-sm"></i> Filtros
                        </h2>
                        <p class="text-[11px] text-slate-500">Encuentra tu equipo ideal</p>
                    </div>
                    @if(request()->hasAny(['q', 'category', 'brand', 'offers', 'min_price', 'max_price', 'sort', 'in_stock']))
                        <a href="{{ route('catalog') }}" class="text-[11px] font-bold text-rose-600 hover:text-rose-700 bg-rose-50 px-2.5 py-1 rounded-lg transition">Limpiar</a>
                    @endif
                </div>
                
                <form action="{{ route('catalog') }}" method="GET" class="flex flex-col gap-5">
                    <div>
                        <label for="filter-search" class="mb-1.5 block text-xs font-bold text-slate-700">Buscar</label>
                        <div class="flex h-11 items-center gap-2 rounded-xl border border-slate-200 bg-slate-50 px-3 transition-colors focus-within:border-indigo-500 focus-within:bg-white focus-within:ring-2 focus-within:ring-indigo-100">
                            <i class="fa-solid fa-magnifying-glass text-slate-400 text-xs"></i>
                            <input id="filter-search" name="q" value="{{ request('q') }}" type="search" placeholder="Producto o modelo..." class="min-w-0 flex-1 bg-transparent text-xs text-slate-800 outline-none">
                        </div>
                    </div>
                    
                    <div>
                        <label for="filter-category" class="mb-1.5 block text-xs font-bold text-slate-700">Categoría</label>
                        <div class="relative">
                            <select id="filter-category" name="category" class="h-11 w-full appearance-none rounded-xl border border-slate-200 bg-slate-50 px-3 pr-8 text-xs text-slate-800 outline-none transition-colors focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-100">
                                <option value="">Todas las categorías</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->slug }}" @selected(request('category') === $category->slug)>{{ $category->name }}</option>
                                @endforeach
                            </select>
                            <i class="fa-solid fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-[9px] text-slate-400 pointer-events-none"></i>
                        </div>
                    </div>

                    <div>
                        <label for="filter-brand" class="mb-1.5 block text-xs font-bold text-slate-700">Marca</label>
                        <div class="relative">
                            <select id="filter-brand" name="brand" class="h-11 w-full appearance-none rounded-xl border border-slate-200 bg-slate-50 px-3 pr-8 text-xs text-slate-800 outline-none transition-colors focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-100">
                                <option value="">Todas las marcas</option>
                                @foreach($brands as $brand)
                                    <option value="{{ $brand->slug }}" @selected(request('brand') === $brand->slug)>{{ $brand->name }}</option>
                                @endforeach
                            </select>
                            <i class="fa-solid fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-[9px] text-slate-400 pointer-events-none"></i>
                        </div>
                    </div>
                    
                    <div>
                        <label class="mb-1.5 block text-xs font-bold text-slate-700">Rango de Precio</label>
                        <div class="flex items-center gap-2">
                            <div class="relative flex-1">
                                <span class="absolute left-2.5 top-1/2 -translate-y-1/2 text-[11px] text-slate-400">S/</span>
                                <input id="minimum-price" name="min_price" value="{{ request('min_price') }}" type="number" min="0" step="1" placeholder="{{ number_format((float) $minPrice, 0) }}" class="h-10 w-full rounded-xl border border-slate-200 bg-slate-50 pl-7 pr-2 text-xs text-slate-800 outline-none transition-colors focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-100">
                            </div>
                            <span class="text-slate-400 text-xs">-</span>
                            <div class="relative flex-1">
                                <span class="absolute left-2.5 top-1/2 -translate-y-1/2 text-[11px] text-slate-400">S/</span>
                                <input id="maximum-price" name="max_price" value="{{ request('max_price') }}" type="number" min="0" step="1" placeholder="{{ number_format((float) $maxPrice, 0) }}" class="h-10 w-full rounded-xl border border-slate-200 bg-slate-50 pl-7 pr-2 text-xs text-slate-800 outline-none transition-colors focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-100">
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <label for="sort-order" class="mb-1.5 block text-xs font-bold text-slate-700">Ordenar por</label>
                        <div class="relative">
                            <select id="sort-order" name="sort" class="h-11 w-full appearance-none rounded-xl border border-slate-200 bg-slate-50 px-3 pr-8 text-xs text-slate-800 outline-none transition-colors focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-100">
                                <option value="newest" @selected($sort === 'newest')>Más recientes</option>
                                <option value="popular" @selected($sort === 'popular')>Destacados</option>
                                <option value="price_asc" @selected($sort === 'price_asc')>Menor precio</option>
                                <option value="price_desc" @selected($sort === 'price_desc')>Mayor precio</option>
                            </select>
                            <i class="fa-solid fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-[9px] text-slate-400 pointer-events-none"></i>
                        </div>
                    </div>

                    <div class="flex flex-col gap-2.5 rounded-2xl bg-slate-50 p-3.5 border border-slate-100">
                        <label class="flex cursor-pointer items-center gap-2.5">
                            <input type="checkbox" name="offers" value="1" @checked(request()->boolean('offers')) class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                            <span class="text-xs font-semibold text-slate-700 select-none">Solo ofertas</span>
                        </label>
                        <label class="flex cursor-pointer items-center gap-2.5">
                            <input type="checkbox" name="in_stock" value="1" @checked(request()->filled('in_stock')) class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                            <span class="text-xs font-semibold text-slate-700 select-none">Disponibles ahora</span>
                        </label>
                    </div>

                    <button type="submit" class="flex h-11 w-full items-center justify-center gap-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-xs font-black text-white shadow-md shadow-indigo-600/20 transition active:scale-98">
                        <i class="fa-solid fa-sliders"></i> Aplicar filtros
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content (Products Show FIRST on Mobile) -->
        <main class="flex-1 min-w-0">
            <!-- Mobile Filter & Header Bar -->
            <div class="mb-5 flex flex-col gap-3">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <h1 class="font-display text-xl sm:text-2xl font-black text-slate-900">
                            {{ request('q') ? 'Resultados para “' . request('q') . '”' : (request('category') ? 'Categoría: ' . ($categories->firstWhere('slug', request('category'))->name ?? request('category')) : 'Catálogo de Equipos') }}
                        </h1>
                        <p class="text-xs text-slate-500 mt-0.5">
                            Mostrando {{ $products->count() }} de {{ $products->total() }} productos
                        </p>
                    </div>

                    <!-- Mobile Filter Button (Opens Drawer) -->
                    <button type="button" 
                            onclick="toggleFilterDrawer(true)" 
                            class="lg:hidden flex items-center gap-2 px-3.5 py-2 rounded-xl bg-indigo-600 text-white text-xs font-extrabold shadow-md shadow-indigo-600/20 active:scale-95 transition">
                        <i class="fa-solid fa-sliders"></i>
                        <span>Filtros</span>
                        @if($activeFilterCount > 0)
                            <span class="flex h-5 w-5 items-center justify-center rounded-full bg-white text-indigo-600 text-[10px] font-black">
                                {{ $activeFilterCount }}
                            </span>
                        @endif
                    </button>
                </div>

                <!-- Active Filter Tags (if any) -->
                @if(request()->hasAny(['q', 'category', 'brand', 'offers', 'min_price', 'max_price', 'in_stock']))
                    <div class="flex items-center gap-2 flex-wrap pt-1">
                        <span class="text-[11px] font-bold text-slate-400">Filtros activos:</span>
                        @if(request('q'))
                            <a href="{{ request()->fullUrlWithQuery(['q' => null]) }}" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-indigo-50 text-indigo-700 text-[11px] font-semibold hover:bg-indigo-100">
                                "{{ request('q') }}" <i class="fa-solid fa-xmark text-[9px]"></i>
                            </a>
                        @endif
                        @if(request('category'))
                            <a href="{{ request()->fullUrlWithQuery(['category' => null]) }}" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-indigo-50 text-indigo-700 text-[11px] font-semibold hover:bg-indigo-100">
                                {{ $categories->firstWhere('slug', request('category'))->name ?? request('category') }} <i class="fa-solid fa-xmark text-[9px]"></i>
                            </a>
                        @endif
                        @if(request('brand'))
                            <a href="{{ request()->fullUrlWithQuery(['brand' => null]) }}" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-indigo-50 text-indigo-700 text-[11px] font-semibold hover:bg-indigo-100">
                                {{ $brands->firstWhere('slug', request('brand'))->name ?? request('brand') }} <i class="fa-solid fa-xmark text-[9px]"></i>
                            </a>
                        @endif
                        @if(request()->boolean('offers'))
                            <a href="{{ request()->fullUrlWithQuery(['offers' => null]) }}" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-rose-50 text-rose-700 text-[11px] font-semibold hover:bg-rose-100">
                                Ofertas <i class="fa-solid fa-xmark text-[9px]"></i>
                            </a>
                        @endif
                        @if(request()->filled('in_stock'))
                            <a href="{{ request()->fullUrlWithQuery(['in_stock' => null]) }}" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-700 text-[11px] font-semibold hover:bg-emerald-100">
                                En Stock <i class="fa-solid fa-xmark text-[9px]"></i>
                            </a>
                        @endif
                        <a href="{{ route('catalog') }}" class="text-[11px] font-bold text-slate-500 hover:text-rose-600 underline ml-1">
                            Limpiar todos
                        </a>
                    </div>
                @endif
            </div>

            <!-- Product Grid -->
            @if($products->isNotEmpty())
                <div class="grid grid-cols-2 gap-3 sm:gap-5 md:grid-cols-3 xl:grid-cols-4">
                    @foreach($products as $product)
                        @include('front.partials.product-card', ['product' => $product, 'whatsappNumber' => $whatsappNumber])
                    @endforeach
                </div>
                @if($products->hasPages())
                    <div class="mt-8">{{ $products->links() }}</div>
                @endif
            @else
                <div class="flex min-h-[350px] flex-col items-center justify-center rounded-3xl border border-slate-200 bg-white p-8 text-center shadow-xs">
                    <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-slate-50 text-3xl text-slate-300 mb-4">
                        <i class="fa-solid fa-box-open"></i>
                    </div>
                    <h3 class="font-display text-xl font-bold text-slate-900">No encontramos productos</h3>
                    <p class="mt-1.5 max-w-sm text-xs text-slate-500">No hay equipos que coincidan con tus filtros actuales. Intenta eliminando algunos o realizando otra búsqueda.</p>
                    <a href="{{ route('catalog') }}" class="mt-5 inline-flex h-10 items-center justify-center rounded-xl bg-indigo-600 px-5 text-xs font-bold text-white shadow-md shadow-indigo-600/20 hover:bg-indigo-700 transition">
                        Limpiar todos los filtros
                    </a>
                </div>
            @endif
        </main>
    </div>
</div>

<!-- Mobile Filter Offcanvas Drawer ("Ventana Deslizable") -->
<div id="mobile-filter-drawer" class="fixed inset-0 z-[100] hidden">
    <!-- Backdrop Overlay -->
    <div id="drawer-backdrop" onclick="toggleFilterDrawer(false)" class="absolute inset-0 bg-slate-950/60 backdrop-blur-xs opacity-0 transition-opacity duration-300"></div>

    <!-- Sliding Panel (From Left) -->
    <div id="drawer-panel" class="absolute inset-y-0 left-0 w-[85%] max-w-sm bg-white shadow-2xl transform -translate-x-full transition-transform duration-300 ease-out flex flex-col z-10">
        <!-- Header -->
        <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100 bg-slate-50/50">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-sliders"></i>
                </div>
                <div>
                    <h3 class="font-display text-sm font-black text-slate-900">Filtros y Categorías</h3>
                    <p class="text-[10px] text-slate-500">Personaliza tu búsqueda</p>
                </div>
            </div>
            <button type="button" onclick="toggleFilterDrawer(false)" class="w-8 h-8 rounded-full hover:bg-slate-200 text-slate-400 hover:text-slate-700 flex items-center justify-center transition">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>

        <!-- Scrollable Form Body -->
        <form action="{{ route('catalog') }}" method="GET" class="flex-1 overflow-y-auto p-5 space-y-5">
            <div>
                <label class="mb-1.5 block text-xs font-bold text-slate-700">Buscar</label>
                <div class="flex h-11 items-center gap-2 rounded-xl border border-slate-200 bg-slate-50 px-3 transition-colors focus-within:border-indigo-500 focus-within:bg-white focus-within:ring-2 focus-within:ring-indigo-100">
                    <i class="fa-solid fa-magnifying-glass text-slate-400 text-xs"></i>
                    <input name="q" value="{{ request('q') }}" type="search" placeholder="Buscar equipo..." class="min-w-0 flex-1 bg-transparent text-xs text-slate-800 outline-none">
                </div>
            </div>

            <div>
                <label class="mb-1.5 block text-xs font-bold text-slate-700">Categoría</label>
                <div class="relative">
                    <select name="category" class="h-11 w-full appearance-none rounded-xl border border-slate-200 bg-slate-50 px-3 pr-8 text-xs text-slate-800 outline-none transition-colors focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-100">
                        <option value="">Todas las categorías</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->slug }}" @selected(request('category') === $category->slug)>{{ $category->name }}</option>
                        @endforeach
                    </select>
                    <i class="fa-solid fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-[9px] text-slate-400 pointer-events-none"></i>
                </div>
            </div>

            <div>
                <label class="mb-1.5 block text-xs font-bold text-slate-700">Marca</label>
                <div class="relative">
                    <select name="brand" class="h-11 w-full appearance-none rounded-xl border border-slate-200 bg-slate-50 px-3 pr-8 text-xs text-slate-800 outline-none transition-colors focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-100">
                        <option value="">Todas las marcas</option>
                        @foreach($brands as $brand)
                            <option value="{{ $brand->slug }}" @selected(request('brand') === $brand->slug)>{{ $brand->name }}</option>
                        @endforeach
                    </select>
                    <i class="fa-solid fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-[9px] text-slate-400 pointer-events-none"></i>
                </div>
            </div>

            <div>
                <label class="mb-1.5 block text-xs font-bold text-slate-700">Rango de Precio</label>
                <div class="flex items-center gap-2">
                    <div class="relative flex-1">
                        <span class="absolute left-2.5 top-1/2 -translate-y-1/2 text-[11px] text-slate-400">S/</span>
                        <input name="min_price" value="{{ request('min_price') }}" type="number" min="0" placeholder="{{ number_format((float) $minPrice, 0) }}" class="h-10 w-full rounded-xl border border-slate-200 bg-slate-50 pl-7 pr-2 text-xs text-slate-800 outline-none">
                    </div>
                    <span class="text-slate-400 text-xs">-</span>
                    <div class="relative flex-1">
                        <span class="absolute left-2.5 top-1/2 -translate-y-1/2 text-[11px] text-slate-400">S/</span>
                        <input name="max_price" value="{{ request('max_price') }}" type="number" min="0" placeholder="{{ number_format((float) $maxPrice, 0) }}" class="h-10 w-full rounded-xl border border-slate-200 bg-slate-50 pl-7 pr-2 text-xs text-slate-800 outline-none">
                    </div>
                </div>
            </div>

            <div>
                <label class="mb-1.5 block text-xs font-bold text-slate-700">Ordenar por</label>
                <div class="relative">
                    <select name="sort" class="h-11 w-full appearance-none rounded-xl border border-slate-200 bg-slate-50 px-3 pr-8 text-xs text-slate-800 outline-none">
                        <option value="newest" @selected($sort === 'newest')>Más recientes</option>
                        <option value="popular" @selected($sort === 'popular')>Destacados</option>
                        <option value="price_asc" @selected($sort === 'price_asc')>Menor precio</option>
                        <option value="price_desc" @selected($sort === 'price_desc')>Mayor precio</option>
                    </select>
                    <i class="fa-solid fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-[9px] text-slate-400 pointer-events-none"></i>
                </div>
            </div>

            <div class="flex flex-col gap-2.5 rounded-2xl bg-slate-50 p-3.5 border border-slate-100">
                <label class="flex cursor-pointer items-center gap-2.5">
                    <input type="checkbox" name="offers" value="1" @checked(request()->boolean('offers')) class="h-4 w-4 rounded border-slate-300 text-indigo-600">
                    <span class="text-xs font-semibold text-slate-700 select-none">Solo ofertas</span>
                </label>
                <label class="flex cursor-pointer items-center gap-2.5">
                    <input type="checkbox" name="in_stock" value="1" @checked(request()->filled('in_stock')) class="h-4 w-4 rounded border-slate-300 text-indigo-600">
                    <span class="text-xs font-semibold text-slate-700 select-none">Disponibles ahora</span>
                </label>
            </div>

            <!-- Drawer Footer Buttons -->
            <div class="pt-2 space-y-2">
                <button type="submit" class="flex h-12 w-full items-center justify-center gap-2 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-xs font-black text-white shadow-lg shadow-indigo-600/20 active:scale-98 transition">
                    <i class="fa-solid fa-check"></i> Aplicar Filtros
                </button>
                @if(request()->hasAny(['q', 'category', 'brand', 'offers', 'min_price', 'max_price', 'sort', 'in_stock']))
                    <a href="{{ route('catalog') }}" class="flex h-10 w-full items-center justify-center text-xs font-bold text-rose-600 hover:bg-rose-50 rounded-xl transition">
                        Limpiar todos los filtros
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>

<script>
    function toggleFilterDrawer(show) {
        const drawer = document.getElementById('mobile-filter-drawer');
        const backdrop = document.getElementById('drawer-backdrop');
        const panel = document.getElementById('drawer-panel');
        
        if (!drawer || !backdrop || !panel) return;

        if (show) {
            drawer.classList.remove('hidden');
            setTimeout(() => {
                backdrop.classList.remove('opacity-0');
                backdrop.classList.add('opacity-100');
                panel.classList.remove('-translate-x-full');
                panel.classList.add('translate-x-0');
            }, 10);
            document.body.style.overflow = 'hidden';
        } else {
            backdrop.classList.remove('opacity-100');
            backdrop.classList.add('opacity-0');
            panel.classList.remove('translate-x-0');
            panel.classList.add('-translate-x-full');
            setTimeout(() => {
                drawer.classList.add('hidden');
                document.body.style.overflow = '';
            }, 300);
        }
    }

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            toggleFilterDrawer(false);
        }
    });
</script>
@endsection

