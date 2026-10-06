@extends('layouts.admin')
@section('header_title', 'Generar Catálogo PDF')
@section('content')

<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div class="flex items-center gap-3">
        <div class="h-10 w-10 bg-red-600 rounded-xl flex items-center justify-center text-white shadow-lg shadow-red-200">
            <i class="fa-solid fa-file-pdf"></i>
        </div>
        <div>
            <h2 class="text-xl font-extrabold text-slate-800">Generador de Catálogos PDF</h2>
            <p class="text-xs text-slate-500">Personaliza el diseño y contenido antes de descargar.</p>
        </div>
    </div>
</div>

<div x-data="{
    pdfTitle: '',
    pdfShowDescription: true,
    pdfShowSpecs: false,
    pdfShowPrice: true,
    pdfShowStock: false,
    pdfShowMinPrice: false,
    pdfOnlyOffers: false,
    pdfBankAccounts: '',
    pdfAddress: '',
    pdfColor: 'red',
    colors: {
        red:    { bg: '#dc2626', text: '#fee2e2', btn: 'bg-red-600',    label: 'Rojo Clásico',   price: '#dc2626' },
        blue:   { bg: '#2563eb', text: '#dbeafe', btn: 'bg-blue-600',   label: 'Azul Profesional', price: '#2563eb' },
        green:  { bg: '#16a34a', text: '#dcfce7', btn: 'bg-green-600',  label: 'Verde Éxito',    price: '#16a34a' },
        purple: { bg: '#7c3aed', text: '#ede9fe', btn: 'bg-purple-600', label: 'Morado Premium', price: '#7c3aed' },
        orange: { bg: '#ea580c', text: '#ffedd5', btn: 'bg-orange-600', label: 'Naranja Energía', price: '#ea580c' },
        dark:   { bg: '#0f172a', text: '#cbd5e1', btn: 'bg-slate-800',  label: 'Negro Elegante', price: '#f59e0b' },
    },
    get headerBg() { return this.colors[this.pdfColor].bg; },
    get headerText() { return this.colors[this.pdfColor].text; },
    get priceColor() { return this.colors[this.pdfColor].price; },
}" class="grid grid-cols-1 lg:grid-cols-12 gap-6">

    <!-- =================== FORMULARIO =================== -->
    <div class="lg:col-span-6 xl:col-span-5">
        <form action="{{ route('admin.pdf.generate') }}" method="POST" class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            @csrf

            <!-- ── Título ── -->
            <div class="p-6 border-b border-slate-100">
                <h3 class="text-sm font-extrabold text-slate-700 mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-pen-to-square text-red-500"></i> Título del Catálogo
                </h3>
                <input type="text" name="custom_title" x-model="pdfTitle"
                    placeholder="Ej. Catálogo Oficial — Octubre 2026"
                    class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-shadow">
                <p class="text-[11px] text-slate-400 mt-1">Déjalo en blanco para usar el nombre de la tienda.</p>
            </div>

            <!-- ── Contenido a mostrar ── -->
            <div class="p-6 border-b border-slate-100">
                <h3 class="text-sm font-extrabold text-slate-700 mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-list-check text-indigo-500"></i> ¿Qué incluir en el PDF?
                </h3>
                <div class="grid grid-cols-1 gap-2">

                    {{-- Descripción --}}
                    <label class="flex items-center gap-3 p-3 border border-slate-100 rounded-xl cursor-pointer hover:bg-indigo-50 hover:border-indigo-200 transition-all"
                           :class="pdfShowDescription ? 'bg-indigo-50 border-indigo-200' : ''">
                        <input type="checkbox" name="show_description" value="1"
                            x-model="pdfShowDescription"
                            class="w-5 h-5 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500" checked>
                        <span>
                            <span class="block text-sm font-bold text-slate-700">
                                <i class="fa-solid fa-align-left text-indigo-400 mr-1"></i> Descripción
                            </span>
                            <span class="block text-[10px] text-slate-500">Texto breve del producto</span>
                        </span>
                    </label>

                    {{-- Especificaciones --}}
                    <label class="flex items-center gap-3 p-3 border border-slate-100 rounded-xl cursor-pointer hover:bg-blue-50 hover:border-blue-200 transition-all"
                           :class="pdfShowSpecs ? 'bg-blue-50 border-blue-200' : ''">
                        <input type="checkbox" name="show_specs" value="1"
                            x-model="pdfShowSpecs"
                            class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <span>
                            <span class="block text-sm font-bold text-slate-700">
                                <i class="fa-solid fa-microchip text-blue-400 mr-1"></i> Especificaciones Técnicas
                            </span>
                            <span class="block text-[10px] text-slate-500">RAM, procesador, pantalla, etc.</span>
                        </span>
                    </label>

                    {{-- Precio --}}
                    <label class="flex items-center gap-3 p-3 border border-slate-100 rounded-xl cursor-pointer hover:bg-green-50 hover:border-green-200 transition-all"
                           :class="pdfShowPrice ? 'bg-green-50 border-green-200' : ''">
                        <input type="checkbox" name="show_price" value="1"
                            x-model="pdfShowPrice"
                            class="w-5 h-5 text-green-600 border-gray-300 rounded focus:ring-green-500" checked>
                        <span>
                            <span class="block text-sm font-bold text-slate-700">
                                <i class="fa-solid fa-tag text-green-500 mr-1"></i> Precio de Venta
                            </span>
                            <span class="block text-[10px] text-slate-500">Precio público (y precio de oferta si aplica)</span>
                        </span>
                    </label>

                    {{-- Precio mínimo --}}
                    <label class="flex items-center gap-3 p-3 border border-slate-100 rounded-xl cursor-pointer hover:bg-emerald-50 hover:border-emerald-200 transition-all"
                           :class="pdfShowMinPrice ? 'bg-emerald-50 border-emerald-200' : ''">
                        <input type="checkbox" name="show_min_price" value="1"
                            x-model="pdfShowMinPrice"
                            class="w-5 h-5 text-emerald-600 border-gray-300 rounded focus:ring-emerald-500">
                        <span>
                            <span class="block text-sm font-bold text-slate-700">
                                <i class="fa-solid fa-handshake text-emerald-500 mr-1"></i> Precio Mínimo (Mayorista)
                            </span>
                            <span class="block text-[10px] text-slate-500">Precio interno para revendedores</span>
                        </span>
                    </label>

                    {{-- Stock --}}
                    <label class="flex items-center gap-3 p-3 border border-slate-100 rounded-xl cursor-pointer hover:bg-amber-50 hover:border-amber-200 transition-all"
                           :class="pdfShowStock ? 'bg-amber-50 border-amber-200' : ''">
                        <input type="checkbox" name="show_stock" value="1"
                            x-model="pdfShowStock"
                            class="w-5 h-5 text-amber-600 border-gray-300 rounded focus:ring-amber-500">
                        <span>
                            <span class="block text-sm font-bold text-slate-700">
                                <i class="fa-solid fa-boxes-stacked text-amber-500 mr-1"></i> Stock / Disponibilidad
                            </span>
                            <span class="block text-[10px] text-slate-500">Muestra unidades o «En Stock / A Pedido»</span>
                        </span>
                    </label>

                </div>
            </div>

            <!-- ── Color / Diseño del encabezado ── -->
            <div class="p-6 border-b border-slate-100">
                <h3 class="text-sm font-extrabold text-slate-700 mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-palette text-pink-500"></i> Color del Catálogo
                </h3>
                <input type="hidden" name="color_theme" :value="pdfColor">
                <div class="grid grid-cols-3 gap-2">
                    <template x-for="(c, key) in colors" :key="key">
                        <label class="relative cursor-pointer">
                            <input type="radio" name="_color_preview" :value="key" x-model="pdfColor" class="sr-only">
                            <div class="flex items-center gap-2 p-2.5 border-2 rounded-xl transition-all"
                                 :class="pdfColor === key ? 'border-current shadow-md' : 'border-slate-200 hover:border-slate-300'"
                                 :style="pdfColor === key ? 'border-color:' + c.bg : ''">
                                <span class="w-5 h-5 rounded-full flex-shrink-0 shadow-sm" :style="'background:' + c.bg"></span>
                                <span class="text-[11px] font-semibold text-slate-700 leading-tight" x-text="c.label"></span>
                                <i class="fa-solid fa-circle-check ml-auto text-xs" :style="'color:' + c.bg" x-show="pdfColor === key"></i>
                            </div>
                        </label>
                    </template>
                </div>
            </div>

            <!-- ── Filtros ── -->
            <div class="p-6 border-b border-slate-100">
                <h3 class="text-sm font-extrabold text-slate-700 mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-filter text-slate-400"></i> Filtros de Productos
                </h3>
                <div class="grid grid-cols-1 gap-2">
                    <label class="flex items-center gap-3 p-3 border border-slate-100 rounded-xl cursor-pointer hover:bg-rose-50 hover:border-rose-200 transition-all"
                           :class="pdfOnlyOffers ? 'bg-rose-50 border-rose-200' : ''">
                        <input type="checkbox" name="only_offers" value="1"
                            x-model="pdfOnlyOffers"
                            class="w-5 h-5 text-rose-600 border-gray-300 rounded focus:ring-rose-500">
                        <span>
                            <span class="block text-sm font-bold text-slate-700">
                                <i class="fa-solid fa-percent text-rose-500 mr-1"></i> Solo Ofertas
                            </span>
                            <span class="block text-[10px] text-slate-500">Exportar únicamente productos en promoción</span>
                        </span>
                    </label>
                    <label class="flex items-center gap-3 p-3 border border-slate-100 rounded-xl cursor-pointer hover:bg-slate-50 transition-all">
                        <input type="checkbox" name="include_out_of_stock" value="1" checked
                            class="w-5 h-5 text-slate-600 border-gray-300 rounded focus:ring-slate-500">
                        <span>
                            <span class="block text-sm font-bold text-slate-700">
                                <i class="fa-solid fa-box-open text-slate-400 mr-1"></i> Incluir sin stock
                            </span>
                            <span class="block text-[10px] text-slate-500">Mostrar productos con 0 unidades</span>
                        </span>
                    </label>
                </div>
            </div>

            <!-- ── Contacto / Pagos ── -->
            <div class="p-6 border-b border-slate-100">
                <h3 class="text-sm font-extrabold text-slate-700 mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-credit-card text-slate-400"></i> Información de Pagos y Contacto
                </h3>
                <div class="space-y-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Cuentas Bancarias / Yape / Plin</label>
                        <textarea name="bank_accounts" x-model="pdfBankAccounts" rows="3"
                            class="w-full rounded-xl border-slate-200 bg-transparent shadow-sm focus:border-red-500 focus:ring-red-500 text-sm px-4 py-2"
                            placeholder="BCP: 191-xxxxxx-x-xx&#10;Yape: 999 999 999"></textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Dirección de la Tienda</label>
                        <input type="text" name="store_address" x-model="pdfAddress"
                            class="w-full rounded-xl border-slate-200 bg-transparent shadow-sm focus:border-red-500 focus:ring-red-500 text-sm px-4 py-2"
                            placeholder="Ej: Av. Principal 123, Lima">
                    </div>
                </div>
            </div>

            <!-- ── Botón Generar ── -->
            <div class="p-6 flex justify-end">
                <button type="submit"
                    class="w-full flex items-center justify-center gap-2 px-8 py-3.5 text-sm font-extrabold text-white rounded-xl shadow-lg transition-all hover:opacity-90 active:scale-95"
                    :style="'background:' + colors[pdfColor].bg">
                    <i class="fa-solid fa-file-arrow-down text-base"></i>
                    Descargar Catálogo PDF
                </button>
            </div>
        </form>
    </div>

    <!-- =================== VISTA PREVIA =================== -->
    <div class="lg:col-span-6 xl:col-span-7">
        <div class="sticky top-6">
            <h3 class="text-sm font-bold text-slate-700 mb-3 flex items-center gap-2">
                <i class="fa-regular fa-eye"></i> Vista Previa en Tiempo Real
            </h3>

            <!-- Hoja A4 simulada -->
            <div class="bg-white shadow-2xl rounded overflow-hidden w-full"
                 style="border: 1px solid #e2e8f0; font-family: Arial, sans-serif; font-size: 12px;">

                <!-- Header -->
                <div class="flex justify-between items-center px-6 py-4 text-white"
                     :style="'background:' + colors[pdfColor].bg">
                    <div>
                        <div class="text-base font-black uppercase tracking-wider"
                             x-text="pdfTitle ? pdfTitle : 'CATÁLOGO DE PRODUCTOS'"></div>
                        <div class="text-xs mt-0.5 opacity-80">Catálogo Oficial · {{ date('d/m/Y') }}</div>
                    </div>
                    <div class="text-right text-xs opacity-90">
                        <i class="fa-brands fa-whatsapp"></i> WhatsApp<br>
                        <span class="font-bold">Mi Tienda Online</span>
                    </div>
                </div>

                <!-- Productos de muestra -->
                <div class="p-4 space-y-3 bg-slate-50">

                    @foreach($previewProducts as $index => $product)
                    <div class="bg-white border border-slate-200 rounded flex overflow-hidden" style="min-height:90px;" {!! $index > 0 ? 'x-show="!pdfOnlyOffers"' : '' !!}>
                        <div class="w-20 flex-shrink-0 bg-slate-100 flex items-center justify-center border-r border-slate-200 overflow-hidden">
                            @php
                                $mainImg = $product->images->firstWhere('is_main', true) ?? $product->images->first();
                            @endphp
                            @if($mainImg && !empty($mainImg->image_path))
                                <img src="{{ filter_var($mainImg->image_path, FILTER_VALIDATE_URL) ? $mainImg->image_path : asset('storage/' . $mainImg->image_path) }}" class="w-full h-full object-cover">
                            @else
                                <i class="fa-solid fa-box text-slate-300 text-2xl"></i>
                            @endif
                        </div>
                        <div class="p-3 flex-1 relative">
                            <div class="text-[11px] font-bold text-slate-800 leading-snug mb-1 line-clamp-2">{{ $product->name }}</div>
                            <div class="text-[9px] text-slate-400 mb-1.5">
                                {{ $product->brand->name ?? 'Variados' }} &nbsp;|&nbsp; P/N: {{ $product->code }}
                            </div>

                            <!-- Precio -->
                            <div x-show="pdfShowPrice" class="mb-1 flex items-center gap-1">
                                @if($product->is_offer && $product->offer_price)
                                    <span class="text-xs font-black" :style="'color:' + colors[pdfColor].price">S/ {{ number_format((float)$product->offer_price, 2) }}</span>
                                    <span class="text-[9px] text-slate-400 line-through">S/ {{ number_format((float)$product->price, 2) }}</span>
                                @else
                                    <span class="text-xs font-black" :style="'color:' + colors[pdfColor].price">S/ {{ number_format((float)$product->price, 2) }}</span>
                                @endif
                            </div>

                            <!-- Precio mínimo -->
                            @if($product->min_price)
                            <div x-show="pdfShowMinPrice" class="mb-1">
                                <span class="text-[9px] text-emerald-600 font-bold">Precio min: S/ {{ number_format((float)$product->min_price, 2) }}</span>
                            </div>
                            @endif

                            <!-- Descripción -->
                            @if($product->description)
                            <div x-show="pdfShowDescription" class="text-[9px] text-slate-500 leading-relaxed mb-1 line-clamp-2">
                                {{ $product->description }}
                            </div>
                            @endif

                            <!-- Especificaciones -->
                            @if(is_array($product->technical_specs) && count($product->technical_specs) > 0)
                            <div x-show="pdfShowSpecs" class="text-[9px] text-slate-500 space-y-0.5 mb-1">
                                @foreach(array_slice($product->technical_specs, 0, 3) as $k => $v)
                                    <div>• <b>{{ $k }}:</b> {{ $v }}</div>
                                @endforeach
                            </div>
                            @endif

                            <!-- Stock -->
                            <div x-show="pdfShowStock">
                                @php
                                    $stockClass = $product->stock > 5 ? 'background:#dcfce7;color:#166534;' : ($product->stock > 0 ? 'background:#fef9c3;color:#713f12;' : 'background:#fee2e2;color:#991b1b;');
                                    $stockLabel = $product->stock > 5 ? '✓ En Stock' : ($product->stock > 0 ? '⚠ Stock bajo' : '✕ A Pedido');
                                @endphp
                                <span class="inline-block text-[9px] font-bold px-1.5 py-0.5 rounded" style="{{ $stockClass }}">{{ $stockLabel }}</span>
                            </div>

                            <!-- Oferta badge -->
                            @if($product->is_offer)
                            <div class="absolute top-0 right-0 text-[8px] font-bold px-2 py-0.5 rounded-bl" style="background:#fde047;color:#78350f;">OFERTA</div>
                            @endif
                        </div>
                    </div>
                    @endforeach

                </div>

                <!-- Footer de contacto -->
                <div x-show="pdfBankAccounts || pdfAddress"
                     class="bg-slate-50 border-t border-slate-200 px-4 py-2 flex justify-between items-start gap-4 text-[9px] text-slate-600">
                    <div style="white-space: pre-line;" x-text="pdfBankAccounts || ''"></div>
                    <div class="text-right" x-text="pdfAddress || ''"></div>
                </div>

                <!-- Footer página -->
                <div class="bg-slate-100 px-4 py-1.5 border-t border-slate-200 text-[8px] text-slate-400 flex justify-between">
                    <span>Generado el {{ date('d/m/Y') }}</span>
                    <span>Catálogo exclusivo de Mi Tienda</span>
                </div>
            </div>

            <!-- Leyenda de colores -->
            <div class="mt-3 flex items-center gap-2 text-xs text-slate-500">
                <i class="fa-solid fa-circle-info text-slate-400"></i>
                Color seleccionado:
                <span class="font-bold text-slate-700" x-text="colors[pdfColor].label"></span>
                <span class="w-4 h-4 rounded-full shadow-sm" :style="'background:' + colors[pdfColor].bg"></span>
            </div>
        </div>
    </div>

</div>
@endsection
