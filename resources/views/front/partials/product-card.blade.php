@php
    $imagePath = $product->images->first()?->image_path;
    $imageUrl = $imagePath ? (filter_var($imagePath, FILTER_VALIDATE_URL) ? $imagePath : asset('storage/' . $imagePath)) : null;
    $currentPrice = $product->is_offer && $product->offer_price ? $product->offer_price : $product->price;
    $message = 'Hola, quiero COMPRAR POR WHATSAPP: ' . $product->name . ' — S/ ' . number_format((float) $currentPrice, 2) . '. ' . route('product.show', $product->slug);
@endphp

<article class="group flex min-w-0 flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white transition duration-200 hover:-translate-y-1 hover:border-indigo-200 hover:shadow-xl hover:shadow-blue-950/10">
    <div class="relative p-2 sm:p-3">
        <a href="{{ route('product.show', $product->slug) }}" class="focus-ring relative flex aspect-square items-center justify-center overflow-hidden rounded-xl bg-gradient-to-br from-slate-50 to-indigo-50/70 p-3 sm:p-5">
            @if($imagePath)
                <img src="{{ filter_var($imagePath, FILTER_VALIDATE_URL) ? $imagePath : asset('storage/' . $imagePath) }}" alt="{{ $product->name }}" loading="lazy" class="h-full w-full object-contain transition duration-300 group-hover:scale-105">
            @else
                <div class="flex h-3/4 w-4/5 items-center justify-center rounded-[1.5rem] border border-slate-300 bg-gradient-to-br from-white via-slate-200 to-slate-400 shadow-lg">
                    <div class="flex h-[82%] w-[88%] items-center justify-center rounded-xl bg-gradient-to-br from-[#101b36] via-[#2458c9] to-[#763fe1] text-3xl text-white/80 shadow-inner sm:text-4xl"><i class="fa-solid fa-laptop" aria-hidden="true"></i></div>
                </div>
            @endif
        </a>
        @if($product->is_offer && $product->offer_price)
            @php $discount = $product->price > 0 ? round((1 - ($product->offer_price / $product->price)) * 100) : 0; @endphp
            <span class="absolute left-4 top-4 rounded-md bg-rose-500 px-2 py-1 text-[9px] font-extrabold text-white shadow-sm sm:left-5 sm:top-5">-{{ $discount }}% OFERTA</span>
        @elseif($product->is_new)
            <span class="absolute left-4 top-4 rounded-md bg-indigo-600 px-2 py-1 text-[9px] font-extrabold text-white shadow-sm sm:left-5 sm:top-5">NUEVO</span>
        @endif
    </div>

    <div class="flex flex-1 flex-col px-3 pb-3 sm:px-4 sm:pb-4">
        <span class="text-[9px] font-extrabold uppercase tracking-[.12em] text-violet-600">{{ $product->brand->name ?? 'MPC' }}</span>
        <a href="{{ route('product.show', $product->slug) }}" class="focus-ring mt-1 line-clamp-2 min-h-10 text-xs font-bold leading-5 text-slate-800 hover:text-blue-700 sm:text-sm">{{ $product->name }}</a>
        <p class="mt-1 line-clamp-1 text-[9px] text-slate-400">{{ $product->category->name ?? 'Tecnología' }}@if($product->warranty) · {{ $product->warranty }} de garantía @endif</p>
        <div class="mt-3 flex items-end justify-between gap-2 border-t border-slate-100 pt-3">
            <div class="min-w-0">
                @if($product->is_offer && $product->offer_price)<span class="block text-[9px] text-slate-400 line-through">S/ {{ number_format((float) $product->price, 2) }}</span>@endif
                <span class="font-display text-base font-extrabold text-[#16264a] sm:text-lg">S/ {{ number_format((float) $currentPrice, 2) }}</span>
            </div>
            <span class="shrink-0 text-[9px] font-semibold {{ $product->stock > 0 ? 'text-emerald-600' : 'text-slate-400' }}">{{ $product->stock > 0 ? 'Disponible' : 'Consultar' }}</span>
        </div>
        <button type="button" data-add-to-cart data-product-id="{{ $product->id }}" data-product-name="{{ $product->name }}" data-product-price="{{ (float) $currentPrice }}" data-product-stock="{{ (int) $product->stock }}" data-product-image="{{ $imageUrl ?? '' }}" data-product-url="{{ route('product.show', $product->slug) }}" @disabled($product->stock < 1) class="focus-ring mt-3 flex min-h-10 items-center justify-center gap-2 rounded-lg border border-indigo-200 bg-indigo-50 px-2 text-[9px] font-extrabold text-indigo-700 transition hover:bg-indigo-100 disabled:cursor-not-allowed disabled:opacity-50 sm:text-[10px]"><i class="fa-solid fa-bag-shopping" aria-hidden="true"></i> AGREGAR AL CARRITO</button>
        <a href="https://wa.me/{{ $whatsappNumber }}?text={{ urlencode($message) }}" target="_blank" rel="noopener noreferrer" class="focus-ring mt-2 flex min-h-10 items-center justify-center gap-2 rounded-lg bg-[#25d366] px-2 text-[9px] font-extrabold text-white transition hover:bg-emerald-600 sm:text-[10px]"><i class="fa-brands fa-whatsapp text-sm" aria-hidden="true"></i> COMPRAR POR WHATSAPP</a>
    </div>
</article>
