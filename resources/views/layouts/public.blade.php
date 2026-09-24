<!DOCTYPE html>
<html lang="es-PE" class="scroll-smooth">
<head>
    @php
        $settings = \App\Models\Setting::pluck('value', 'key');
        $storeName = $settings['store_name'] ?? 'MPC Antigravity';
        $primaryColor = $settings['primary_color'] ?? '#2855d9';
        $topBannerText = $settings['top_banner_text'] ?? '';
        $facebookUrl = $settings['facebook_url'] ?? '';
        $instagramUrl = $settings['instagram_url'] ?? '';
    @endphp
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="@yield('meta_description', 'Laptops y tecnología seleccionada por ' . $storeName . ' en Perú.')">
    <title>@yield('title', 'Catálogo') — {{ $storeName }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script>
        tailwind = { config: { theme: { extend: { fontFamily: { sans: ['Inter', 'sans-serif'], display: ['Outfit', 'sans-serif'] }, colors: { brand: { blue: '{{ $primaryColor }}', violet: '#673de6', navy: '#0b1730' } } } } };
    </script>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        [x-cloak] { display: none !important; }
        body { background: #f4f6fa; color: #172033; font-family: Inter, sans-serif; }
        .focus-ring:focus-visible { outline: 3px solid #7654e8; outline-offset: 3px; }
        .line-clamp-1 { display: -webkit-box; -webkit-line-clamp: 1; -webkit-box-orient: vertical; overflow: hidden; }
        .line-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        .shop-shadow { box-shadow: 0 14px 36px rgba(25, 45, 91, .08); }
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
                    <a href="{{ auth()->check() ? route('dashboard') : route('login') }}" class="focus-ring flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-[{{ $primaryColor }}] to-[#713ee7] text-lg font-black italic text-white shadow-lg">{{ substr($storeName, 0, 1) }}</a>
                    <a href="{{ route('home') }}" class="focus-ring leading-tight"><span class="block font-display text-sm font-extrabold tracking-tight text-[#111c36] uppercase">{{ $storeName }}</span></a>
                </div>
                <div class="flex items-center gap-2">
                    <button type="button" data-open-cart class="focus-ring relative inline-flex h-10 items-center gap-2 rounded-xl border border-slate-200 bg-white px-3 text-xs font-bold text-slate-700 transition hover:border-indigo-200 hover:bg-indigo-50 hover:text-indigo-700" aria-label="Abrir carrito">
                        <i class="fa-solid fa-bag-shopping text-base"></i><span id="cart-count" class="flex h-5 min-w-5 items-center justify-center rounded-full bg-indigo-600 px-1 text-[10px] font-extrabold text-white">0</span>
                    </button>
                </div>
            </div>

            <!-- Search Bar (Mobile & Desktop) & Desktop Layout -->
            <div class="mt-3 flex flex-col gap-3 lg:mt-0 lg:flex-row lg:items-center lg:justify-between">
                
                <!-- Desktop Brand (Hidden on Mobile) -->
                <div class="hidden lg:flex lg:w-[230px] lg:shrink-0 lg:items-center lg:gap-3">
                    <a href="{{ auth()->check() ? route('dashboard') : route('login') }}" class="focus-ring flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-[{{ $primaryColor }}] to-[#713ee7] text-xl font-black italic text-white shadow-lg">{{ substr($storeName, 0, 1) }}</a>
                    <a href="{{ route('home') }}" class="focus-ring leading-tight"><span class="block font-display text-base font-extrabold tracking-tight text-[#111c36] uppercase">{{ $storeName }}</span></a>
                </div>

                <!-- Search Bar -->
                <form action="{{ route('catalog') }}" method="GET" role="search" class="flex h-11 flex-1 overflow-hidden rounded-xl border border-slate-200 bg-slate-50 focus-within:border-[#536be2] focus-within:ring-4 focus-within:ring-indigo-100 lg:max-w-2xl">
                    <label for="site-search" class="sr-only">Buscar productos</label>
                    <input id="site-search" type="search" name="q" value="{{ request('q') }}" placeholder="Busca productos, marcas y modelos..." class="min-w-0 flex-1 bg-transparent px-4 text-sm outline-none placeholder:text-slate-400">
                    <button type="submit" aria-label="Buscar" style="background-color: {{ $primaryColor }}" class="focus-ring flex w-12 items-center justify-center text-white"><i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i></button>
                </form>

                <!-- Actions (Desktop & Extra Mobile buttons) -->
                <div class="flex items-center justify-between gap-2 lg:w-[390px] lg:shrink-0 lg:justify-end">
                    @auth
                        <a href="{{ route('dashboard') }}" class="focus-ring flex min-h-10 items-center gap-2 rounded-xl border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-700 transition hover:border-indigo-200 hover:bg-indigo-50 hover:text-blue-700 lg:flex"><i class="fa-regular fa-user text-base"></i><span class="hidden sm:inline">Mi cuenta</span></a>
                    @endauth
                    
                    <!-- Desktop Cart (Hidden on Mobile since it's on Top Row) -->
                    <button type="button" data-open-cart class="focus-ring relative hidden h-10 items-center gap-2 rounded-xl border border-slate-200 bg-white px-3 text-xs font-bold text-slate-700 transition hover:border-indigo-200 hover:bg-indigo-50 hover:text-indigo-700 lg:inline-flex" aria-label="Abrir carrito">
                        <i class="fa-solid fa-bag-shopping text-base"></i><span>Carrito</span><span id="cart-count-desktop" class="flex h-5 min-w-5 items-center justify-center rounded-full bg-indigo-600 px-1 text-[10px] font-extrabold text-white">0</span>
                    </button>

                    <a href="https://wa.me/{{ $wpNum }}?text={{ urlencode('Hola, quisiera información sobre sus equipos.') }}" target="_blank" rel="noopener noreferrer" class="focus-ring inline-flex w-full items-center justify-center gap-2 rounded-xl bg-[#25d366] px-4 py-2.5 text-xs font-bold text-white shadow-md shadow-emerald-600/15 hover:bg-[#1fb85a] lg:w-auto"><i class="fa-brands fa-whatsapp text-base" aria-hidden="true"></i><span>Consultar</span></a>
                </div>
            </div>
        </div>
        <nav aria-label="Navegación principal" class="border-t border-slate-100">
            <div class="hide-scrollbar mx-auto flex max-w-[1440px] items-center gap-2 overflow-x-auto px-4 py-2.5 sm:px-7">
                <a href="{{ route('catalog') }}" style="background-color: {{ $primaryColor }}" class="focus-ring flex shrink-0 items-center gap-2 rounded-lg px-4 py-2 text-xs font-bold text-white"><i class="fa-solid fa-bars" aria-hidden="true"></i>Todo el catálogo</a>
                <a href="{{ route('home') }}" class="focus-ring shrink-0 rounded-lg px-4 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-100 hover:text-blue-700">Inicio</a>
                <a href="{{ route('catalog') }}" class="focus-ring shrink-0 rounded-lg px-4 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-100 hover:text-blue-700">Equipos</a>
                <a href="{{ route('catalog', ['offers' => 1]) }}" class="focus-ring shrink-0 rounded-lg px-4 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-100 hover:text-blue-700"><i class="fa-solid fa-bolt mr-1 text-amber-500" aria-hidden="true"></i>Ofertas</a>
                <a href="{{ route('home') }}#marcas" class="focus-ring shrink-0 rounded-lg px-4 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-100 hover:text-blue-700">Marcas</a>
                <span class="ml-auto hidden shrink-0 items-center gap-2 text-[10px] font-semibold text-slate-500 lg:flex"><i class="fa-solid fa-headset text-[#6042d7]" aria-hidden="true"></i>Te ayudamos a elegir</span>
            </div>
        </nav>
    </header>

    <main id="contenido">@yield('content')</main>

    <dialog id="cart-dialog" class="w-[min(100%-1rem,680px)] max-h-[92vh] overflow-y-auto rounded-3xl border-0 p-0 shadow-2xl backdrop:bg-slate-950/60">
        <div class="sticky top-0 z-10 flex items-center justify-between border-b border-slate-100 bg-white/95 px-5 py-4 backdrop-blur sm:px-7">
            <div><p class="text-[9px] font-extrabold uppercase tracking-[.18em] text-indigo-600">Tu selección</p><h2 class="mt-1 font-display text-xl font-extrabold text-[#142143]">Carrito de compras</h2></div>
            <button type="button" data-close-cart class="focus-ring flex h-10 w-10 items-center justify-center rounded-full bg-slate-100 text-slate-600 hover:bg-slate-200" aria-label="Cerrar carrito"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <div class="p-5 sm:p-7">
            <div id="cart-items" class="space-y-3"></div>
            <p id="cart-empty" class="hidden rounded-2xl bg-slate-50 px-4 py-10 text-center text-sm text-slate-500">Tu carrito está vacío. Agrega un equipo para continuar.</p>
            <div id="cart-checkout" class="mt-5 hidden">
                <div class="flex items-center justify-between border-t border-slate-100 py-4"><span class="text-sm font-semibold text-slate-500">Total estimado</span><strong id="cart-total" class="font-display text-2xl font-extrabold text-[#142143]">S/ 0.00</strong></div>
                <form id="cart-checkout-form" class="space-y-4" action="{{ route('api.checkout') }}" method="POST">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="grid gap-3 sm:grid-cols-2">
                        <div><label for="checkout-name" class="mb-1.5 block text-xs font-bold text-slate-600">Nombre completo</label><input id="checkout-name" name="client_name" required maxlength="255" autocomplete="name" class="h-11 w-full rounded-xl border border-slate-200 bg-slate-50 px-3 text-sm outline-none focus:border-indigo-400 focus:bg-white focus:ring-4 focus:ring-indigo-100"></div>
                        <div><label for="checkout-phone" class="mb-1.5 block text-xs font-bold text-slate-600">Número celular</label><input id="checkout-phone" name="client_phone" required inputmode="tel" autocomplete="tel" pattern="[0-9+ ().-]{7,20}" class="h-11 w-full rounded-xl border border-slate-200 bg-slate-50 px-3 text-sm outline-none focus:border-indigo-400 focus:bg-white focus:ring-4 focus:ring-indigo-100"></div>
                    </div>
                    <div class="grid gap-3 sm:grid-cols-2">
                        <div><label for="receipt-type" class="mb-1.5 block text-xs font-bold text-slate-600">Comprobante</label><select id="receipt-type" name="receipt_type" required class="h-11 w-full rounded-xl border border-slate-200 bg-slate-50 px-3 text-sm outline-none focus:border-indigo-400"><option value="boleta">Boleta</option><option value="factura">Factura</option></select></div>
                        <div id="tax-id-wrap" class="hidden"><label for="checkout-tax-id" class="mb-1.5 block text-xs font-bold text-slate-600">RUC para la factura</label><input id="checkout-tax-id" name="tax_id" inputmode="numeric" maxlength="11" pattern="[0-9]{11}" class="h-11 w-full rounded-xl border border-slate-200 bg-slate-50 px-3 text-sm outline-none focus:border-indigo-400"></div>
                    </div>
                    <details class="group rounded-xl border border-slate-200 bg-slate-50 px-4 py-3">
                        <summary class="focus-ring flex cursor-pointer list-none items-center justify-between text-xs font-bold text-slate-600"><span><i class="fa-regular fa-note-sticky mr-2 text-indigo-600"></i>Agregar nota de venta</span><i class="fa-solid fa-chevron-down text-[10px] transition group-open:rotate-180"></i></summary>
                        <label for="sale-note" class="sr-only">Nota de venta</label><textarea id="sale-note" name="sale_note" rows="3" maxlength="1000" placeholder="Indicaciones para preparar tu pedido (opcional)" class="mt-3 w-full rounded-lg border border-slate-200 bg-white p-3 text-xs outline-none focus:border-indigo-400"></textarea>
                    </details>
                    <p id="cart-error" class="hidden rounded-xl bg-rose-50 px-4 py-3 text-xs font-semibold text-rose-700" role="alert"></p>
                    <button id="checkout-submit" type="submit" class="focus-ring flex min-h-12 w-full items-center justify-center gap-2 rounded-xl bg-[#25d366] px-4 text-sm font-extrabold text-white shadow-lg shadow-emerald-600/15 transition hover:bg-emerald-600"><i class="fa-brands fa-whatsapp text-lg"></i>Confirmar y enviar por WhatsApp</button>
                    <p class="text-center text-[10px] text-slate-400">Guardaremos tu pedido para que nuestro equipo pueda atenderte.</p>
                </form>
            </div>
        </div>
    </dialog>

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
            const dialog = document.getElementById('cart-dialog');
            const itemsContainer = document.getElementById('cart-items');
            const countBadges = document.querySelectorAll('#cart-count, #cart-count-desktop');
            const checkoutForm = document.getElementById('cart-checkout-form');
            const checkoutPanel = document.getElementById('cart-checkout');
            const emptyMessage = document.getElementById('cart-empty');
            const errorMessage = document.getElementById('cart-error');
            const currency = new Intl.NumberFormat('es-PE', { style: 'currency', currency: 'PEN' });
            let cart = [];

            try { cart = JSON.parse(localStorage.getItem(storageKey) || '[]'); } catch { cart = []; }
            if (!Array.isArray(cart)) cart = [];

            const escapeHtml = (value) => String(value).replace(/[&<>"']/g, (character) => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' })[character]);
            const saveCart = () => localStorage.setItem(storageKey, JSON.stringify(cart));
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

            document.addEventListener('click', (event) => {
                if (event.target.closest('[data-open-cart]')) dialog.showModal();
                if (event.target.closest('[data-close-cart]')) dialog.close();

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
                    saveCart(); renderCart(); dialog.showModal();
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

            renderCart();
        })();
    </script>
</body>
</html>
