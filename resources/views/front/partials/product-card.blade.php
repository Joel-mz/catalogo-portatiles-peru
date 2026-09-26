@php
    $whatsappNumber = $whatsappNumber ?? preg_replace('/[^0-9]/', '', \App\Models\Setting::where('key', 'whatsapp_number')->value('value') ?? '');
    $imagePath = $product->images->first()?->image_path;
    $imageUrl = $imagePath ? (filter_var($imagePath, FILTER_VALIDATE_URL) ? $imagePath : asset('storage/' . $imagePath)) : null;
    $currentPrice = $product->is_offer && $product->offer_price ? $product->offer_price : $product->price;
    $discount = $product->price > 0 && $product->offer_price ? round((1 - ($product->offer_price / $product->price)) * 100) : 0;
    $message = 'Hola, quiero comprar: ' . $product->name . ' — S/ ' . number_format((float) $currentPrice, 2) . '. ' . route('product.show', $product->slug);
@endphp

<article class="group relative flex flex-col overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-sm transition-all duration-300 hover:-translate-y-1 hover:border-indigo-300 hover:shadow-xl hover:shadow-indigo-950/5">
    <!-- Image & Badges Container -->
    <div class="relative overflow-hidden bg-[#f8fafc] p-4 text-center">
        <!-- Badges -->
        <div class="absolute left-3 top-3 z-10 flex flex-col gap-1">
            @if($product->is_offer && $product->offer_price && $discount > 0)
                <span class="inline-flex items-center rounded-md bg-rose-500 px-2 py-0.5 text-[10px] font-extrabold text-white shadow-sm">-{{ $discount }}%</span>
            @elseif($product->is_new)
                <span class="inline-flex items-center rounded-md bg-indigo-600 px-2 py-0.5 text-[10px] font-extrabold text-white shadow-sm">NUEVO</span>
            @endif
        </div>

        <!-- Wishlist Button -->
        <a href="https://wa.me/{{ $whatsappNumber }}?text={{ urlencode('Hola, me interesa este producto: ' . $product->name) }}" target="_blank" rel="noopener noreferrer" class="absolute right-3 top-3 z-10 flex h-8 w-8 items-center justify-center rounded-full bg-white/90 text-slate-400 shadow-sm transition hover:bg-rose-50 hover:text-rose-500" title="Consultar por WhatsApp">
            <i class="fa-regular fa-heart text-xs"></i>
        </a>

        <!-- Product Image -->
        <a href="{{ route('product.show', $product->slug) }}" class="focus-ring block aspect-square w-full overflow-hidden">
            @if($imagePath)
                <img src="{{ filter_var($imagePath, FILTER_VALIDATE_URL) ? $imagePath : asset('storage/' . $imagePath) }}" alt="{{ $product->name }}" loading="lazy" class="h-full w-full object-contain p-2 transition-transform duration-500 group-hover:scale-105">
            @else
                <div class="flex h-full w-full items-center justify-center rounded-xl bg-gradient-to-br from-slate-100 to-indigo-50/50 text-3xl text-indigo-400">
                    <i class="fa-solid fa-laptop"></i>
                </div>
            @endif
        </a>
    </div>

    <!-- Details Container -->
    <div class="flex flex-1 flex-col p-4">
        <!-- Brand & Category -->
        <div class="flex items-center justify-between gap-2 text-[10px] font-bold uppercase tracking-wider text-indigo-600">
            <span>{{ $product->brand->name ?? 'Tecnología' }}</span>
            <span class="text-slate-400 font-normal lowercase first-letter:uppercase">{{ $product->category->name ?? '' }}</span>
        </div>

        <!-- Title -->
        <h3 class="mt-1.5 line-clamp-2 min-h-[2.5rem] font-display text-xs font-bold leading-tight text-slate-800 transition group-hover:text-indigo-600 sm:text-sm">
            <a href="{{ route('product.show', $product->slug) }}" class="focus-ring">
                {{ $product->name }}
            </a>
        </h3>

        <!-- Star Rating -->
        <div class="mt-2 flex items-center gap-1.5 text-xs">
            <div class="flex text-amber-400 text-[11px]">
                <i class="fa-solid fa-star"></i>
                <i class="fa-solid fa-star"></i>
                <i class="fa-solid fa-star"></i>
                <i class="fa-solid fa-star"></i>
                <i class="fa-solid fa-star"></i>
            </div>
            <span class="text-[10px] font-bold text-slate-400">(5.0)</span>
            @if($product->stock > 0)
                <span class="ml-auto text-[9px] font-semibold text-emerald-600 bg-emerald-50 px-1.5 py-0.5 rounded">En stock</span>
            @else
                <span class="ml-auto text-[9px] font-semibold text-slate-400 bg-slate-100 px-1.5 py-0.5 rounded">Agotado</span>
            @endif
        </div>

        <!-- Price -->
        <div class="mt-3 flex items-baseline gap-2 border-t border-slate-100 pt-3">
            <span class="font-display text-base font-extrabold text-[#0f172a] sm:text-lg">
                S/ {{ number_format((float) $currentPrice, 2) }}
            </span>
            @if($product->is_offer && $product->offer_price && $product->price > $product->offer_price)
                <span class="text-xs text-slate-400 line-through">
                    S/ {{ number_format((float) $product->price, 2) }}
                </span>
            @endif
        </div>

        <!-- Actions -->
        <div class="mt-3 grid grid-cols-2 gap-2">
            <button type="button" 
                    data-add-to-cart 
                    data-product-id="{{ $product->id }}" 
                    data-product-name="{{ $product->name }}" 
                    data-product-price="{{ (float) $currentPrice }}" 
                    data-product-stock="{{ (int) $product->stock }}" 
                    data-product-image="{{ $imageUrl ?? '' }}" 
                    data-product-url="{{ route('product.show', $product->slug) }}" 
                    @disabled($product->stock < 1) 
                    class="focus-ring flex h-9 items-center justify-center gap-1.5 rounded-xl border border-indigo-200 bg-indigo-50/70 px-2 text-[10px] font-bold text-indigo-700 transition hover:bg-indigo-100 hover:border-indigo-300 disabled:cursor-not-allowed disabled:opacity-40">
                <i class="fa-solid fa-bag-shopping text-xs"></i>
                <span class="hidden sm:inline">Carrito</span>
            </button>
            <a href="https://wa.me/{{ $whatsappNumber }}?text={{ urlencode($message) }}" 
               target="_blank" 
               rel="noopener noreferrer" 
               class="focus-ring flex h-9 items-center justify-center gap-1.5 rounded-xl bg-emerald-500 px-2 text-[10px] font-bold text-white shadow-sm transition hover:bg-emerald-600">
                <i class="fa-brands fa-whatsapp text-xs"></i>
                <span>Comprar</span>
            </a>
        </div>
    </div>
</article>
