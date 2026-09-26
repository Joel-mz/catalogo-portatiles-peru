@extends('layouts.admin')

@section('header_title', 'Marketing — Banners y Promociones')

@section('content')
<div class="space-y-6" x-data="{ activeTab: 'promotions' }">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Marketing & Banners</h2>
            <p class="text-sm text-slate-500">Administra los banners promocionales, ofertas destacadas y secciones visuales de tu tienda.</p>
        </div>
        <div class="flex items-center gap-2">
            <button type="button" @click="activeTab = 'promotions'" :class="activeTab === 'promotions' ? 'bg-indigo-600 text-white shadow-sm' : 'bg-white text-slate-700 border border-slate-200 hover:bg-slate-50'" class="px-4 py-2.5 rounded-xl font-bold text-xs transition flex items-center gap-2">
                <i class="fa-solid fa-wand-magic-sparkles"></i> Secciones de Inicio
            </button>
            <button type="button" @click="activeTab = 'banners'" :class="activeTab === 'banners' ? 'bg-indigo-600 text-white shadow-sm' : 'bg-white text-slate-700 border border-slate-200 hover:bg-slate-50'" class="px-4 py-2.5 rounded-xl font-bold text-xs transition flex items-center gap-2">
                <i class="fa-solid fa-images"></i> Banners de Imagen ({{ $banners->count() }})
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-200 text-green-700 px-4 py-3 rounded-xl shadow-sm flex items-center gap-2">
            <i class="fa-solid fa-circle-check"></i>
            <span class="text-sm font-medium">{{ session('success') }}</span>
        </div>
    @endif

    <!-- TAB 1: Banners y Secciones Promocionales de la Página de Inicio -->
    <div x-show="activeTab === 'promotions'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-6">
        <form action="{{ route('admin.banners.updatePromotions') }}" method="POST">
            @csrf
            @method('PUT')

            <!-- 1. Mini Banners Laterales del Hero -->
            <div class="mb-6 rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm">
                <div class="mb-5 flex items-center justify-between border-b border-slate-100 pb-3">
                    <div>
                        <h3 class="text-base font-bold text-slate-900 flex items-center gap-2">
                            <i class="fa-solid fa-table-columns text-indigo-600"></i>
                            1. Mini Banners Laterales (Hero Principal)
                        </h3>
                        <p class="text-xs text-slate-500 mt-0.5">3 tarjetas ubicadas a la derecha del banner principal en la portada.</p>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Card 1 -->
                    <div class="space-y-3 rounded-xl border border-slate-100 bg-slate-50/70 p-4">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-indigo-700">Tarjeta 1 (Nuevos Ingresos)</span>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="hidden" name="hero_card1_active" value="0">
                                <input type="checkbox" name="hero_card1_active" value="1" class="sr-only peer" {{ ($settings['hero_card1_active'] ?? '1') == '1' ? 'checked' : '' }}>
                                <div class="w-9 h-5 bg-slate-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-indigo-600"></div>
                            </label>
                        </div>
                        <div>
                            <label class="block text-[11px] font-semibold text-slate-600 mb-1">Tag / Etiqueta</label>
                            <input type="text" name="hero_card1_tag" value="{{ $settings['hero_card1_tag'] ?? 'Nuevos Ingresos' }}" class="block w-full rounded-lg border-slate-200 bg-white text-xs px-3 py-2 shadow-sm">
                        </div>
                        <div>
                            <label class="block text-[11px] font-semibold text-slate-600 mb-1">Título</label>
                            <input type="text" name="hero_card1_title" value="{{ $settings['hero_card1_title'] ?? 'Equipos para cada reto' }}" class="block w-full rounded-lg border-slate-200 bg-white text-xs px-3 py-2 shadow-sm">
                        </div>
                        <div>
                            <label class="block text-[11px] font-semibold text-slate-600 mb-1">Enlace (URL)</label>
                            <input type="text" name="hero_card1_link" value="{{ $settings['hero_card1_link'] ?? route('catalog') }}" class="block w-full rounded-lg border-slate-200 bg-white text-xs px-3 py-2 shadow-sm">
                        </div>
                    </div>

                    <!-- Card 2 -->
                    <div class="space-y-3 rounded-xl border border-slate-100 bg-slate-50/70 p-4">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-purple-700">Tarjeta 2 (Precios Especiales)</span>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="hidden" name="hero_card2_active" value="0">
                                <input type="checkbox" name="hero_card2_active" value="1" class="sr-only peer" {{ ($settings['hero_card2_active'] ?? '1') == '1' ? 'checked' : '' }}>
                                <div class="w-9 h-5 bg-slate-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-purple-600"></div>
                            </label>
                        </div>
                        <div>
                            <label class="block text-[11px] font-semibold text-slate-600 mb-1">Tag / Etiqueta</label>
                            <input type="text" name="hero_card2_tag" value="{{ $settings['hero_card2_tag'] ?? 'Precios Especiales' }}" class="block w-full rounded-lg border-slate-200 bg-white text-xs px-3 py-2 shadow-sm">
                        </div>
                        <div>
                            <label class="block text-[11px] font-semibold text-slate-600 mb-1">Título</label>
                            <input type="text" name="hero_card2_title" value="{{ $settings['hero_card2_title'] ?? 'Ofertas para aprovechar' }}" class="block w-full rounded-lg border-slate-200 bg-white text-xs px-3 py-2 shadow-sm">
                        </div>
                        <div>
                            <label class="block text-[11px] font-semibold text-slate-600 mb-1">Enlace (URL)</label>
                            <input type="text" name="hero_card2_link" value="{{ $settings['hero_card2_link'] ?? route('catalog', ['offers' => 1]) }}" class="block w-full rounded-lg border-slate-200 bg-white text-xs px-3 py-2 shadow-sm">
                        </div>
                    </div>

                    <!-- Card 3 -->
                    <div class="space-y-3 rounded-xl border border-slate-100 bg-slate-50/70 p-4">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-teal-700">Tarjeta 3 (Atención Personal)</span>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="hidden" name="hero_card3_active" value="0">
                                <input type="checkbox" name="hero_card3_active" value="1" class="sr-only peer" {{ ($settings['hero_card3_active'] ?? '1') == '1' ? 'checked' : '' }}>
                                <div class="w-9 h-5 bg-slate-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-teal-600"></div>
                            </label>
                        </div>
                        <div>
                            <label class="block text-[11px] font-semibold text-slate-600 mb-1">Tag / Etiqueta</label>
                            <input type="text" name="hero_card3_tag" value="{{ $settings['hero_card3_tag'] ?? 'Atención Personal' }}" class="block w-full rounded-lg border-slate-200 bg-white text-xs px-3 py-2 shadow-sm">
                        </div>
                        <div>
                            <label class="block text-[11px] font-semibold text-slate-600 mb-1">Título</label>
                            <input type="text" name="hero_card3_title" value="{{ $settings['hero_card3_title'] ?? 'Te ayudamos a elegir' }}" class="block w-full rounded-lg border-slate-200 bg-white text-xs px-3 py-2 shadow-sm">
                        </div>
                        <div>
                            <label class="block text-[11px] font-semibold text-slate-600 mb-1">Enlace / WhatsApp</label>
                            <input type="text" name="hero_card3_link" value="{{ $settings['hero_card3_link'] ?? '' }}" placeholder="Dejar vacío para usar WhatsApp por defecto" class="block w-full rounded-lg border-slate-200 bg-white text-xs px-3 py-2 shadow-sm">
                        </div>
                    </div>
                </div>
            </div>

            <!-- 2. Banners Promocionales Centrales (Gaming & Audio) -->
            <div class="mb-6 rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm">
                <div class="mb-5 flex items-center justify-between border-b border-slate-100 pb-3">
                    <div>
                        <h3 class="text-base font-bold text-slate-900 flex items-center gap-2">
                            <i class="fa-solid fa-gamepad text-indigo-600"></i>
                            2. Banners Promocionales Centrales (Gaming & Audio)
                        </h3>
                        <p class="text-xs text-slate-500 mt-0.5">2 bloques destacados para categorías estrella (Gaming y Sonido).</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Banner Gaming -->
                    <div class="space-y-3 rounded-xl border border-slate-100 bg-slate-50/70 p-4">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-teal-700">Banner 1 (Gaming Experience)</span>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="hidden" name="promo_gaming_active" value="0">
                                <input type="checkbox" name="promo_gaming_active" value="1" class="sr-only peer" {{ ($settings['promo_gaming_active'] ?? '1') == '1' ? 'checked' : '' }}>
                                <div class="w-9 h-5 bg-slate-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-teal-600"></div>
                            </label>
                        </div>
                        <div>
                            <label class="block text-[11px] font-semibold text-slate-600 mb-1">Tag</label>
                            <input type="text" name="promo_gaming_tag" value="{{ $settings['promo_gaming_tag'] ?? 'PLAYSTATION & GAMING' }}" class="block w-full rounded-lg border-slate-200 bg-white text-xs px-3 py-2 shadow-sm">
                        </div>
                        <div>
                            <label class="block text-[11px] font-semibold text-slate-600 mb-1">Título</label>
                            <input type="text" name="promo_gaming_title" value="{{ $settings['promo_gaming_title'] ?? 'Next Level Gaming Experience' }}" class="block w-full rounded-lg border-slate-200 bg-white text-xs px-3 py-2 shadow-sm">
                        </div>
                        <div>
                            <label class="block text-[11px] font-semibold text-slate-600 mb-1">Subtítulo</label>
                            <input type="text" name="promo_gaming_subtitle" value="{{ $settings['promo_gaming_subtitle'] ?? 'Immersive. Powerful. Unstoppable.' }}" class="block w-full rounded-lg border-slate-200 bg-white text-xs px-3 py-2 shadow-sm">
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-[11px] font-semibold text-slate-600 mb-1">Texto Botón</label>
                                <input type="text" name="promo_gaming_button" value="{{ $settings['promo_gaming_button'] ?? 'Shop Now' }}" class="block w-full rounded-lg border-slate-200 bg-white text-xs px-3 py-2 shadow-sm">
                            </div>
                            <div>
                                <label class="block text-[11px] font-semibold text-slate-600 mb-1">Enlace</label>
                                <input type="text" name="promo_gaming_link" value="{{ $settings['promo_gaming_link'] ?? route('catalog') }}" class="block w-full rounded-lg border-slate-200 bg-white text-xs px-3 py-2 shadow-sm">
                            </div>
                        </div>
                    </div>

                    <!-- Banner Audio -->
                    <div class="space-y-3 rounded-xl border border-slate-100 bg-slate-50/70 p-4">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-purple-700">Banner 2 (Premium Audio)</span>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="hidden" name="promo_audio_active" value="0">
                                <input type="checkbox" name="promo_audio_active" value="1" class="sr-only peer" {{ ($settings['promo_audio_active'] ?? '1') == '1' ? 'checked' : '' }}>
                                <div class="w-9 h-5 bg-slate-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-purple-600"></div>
                            </label>
                        </div>
                        <div>
                            <label class="block text-[11px] font-semibold text-slate-600 mb-1">Tag</label>
                            <input type="text" name="promo_audio_tag" value="{{ $settings['promo_audio_tag'] ?? 'PREMIUM AUDIO' }}" class="block w-full rounded-lg border-slate-200 bg-white text-xs px-3 py-2 shadow-sm">
                        </div>
                        <div>
                            <label class="block text-[11px] font-semibold text-slate-600 mb-1">Título</label>
                            <input type="text" name="promo_audio_title" value="{{ $settings['promo_audio_title'] ?? 'Premium Audio Collection' }}" class="block w-full rounded-lg border-slate-200 bg-white text-xs px-3 py-2 shadow-sm">
                        </div>
                        <div>
                            <label class="block text-[11px] font-semibold text-slate-600 mb-1">Subtítulo</label>
                            <input type="text" name="promo_audio_subtitle" value="{{ $settings['promo_audio_subtitle'] ?? 'Feel Every Beat with High-Res Sound' }}" class="block w-full rounded-lg border-slate-200 bg-white text-xs px-3 py-2 shadow-sm">
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-[11px] font-semibold text-slate-600 mb-1">Texto Botón</label>
                                <input type="text" name="promo_audio_button" value="{{ $settings['promo_audio_button'] ?? 'Shop Now' }}" class="block w-full rounded-lg border-slate-200 bg-white text-xs px-3 py-2 shadow-sm">
                            </div>
                            <div>
                                <label class="block text-[11px] font-semibold text-slate-600 mb-1">Enlace</label>
                                <input type="text" name="promo_audio_link" value="{{ $settings['promo_audio_link'] ?? route('catalog') }}" class="block w-full rounded-lg border-slate-200 bg-white text-xs px-3 py-2 shadow-sm">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 3. Banner Panorámico Oferta Especial (50% OFF) -->
            <div class="mb-6 rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between mb-4 border-b border-slate-100 pb-3">
                    <div>
                        <h3 class="text-base font-bold text-slate-900 flex items-center gap-2">
                            <i class="fa-solid fa-bolt text-indigo-600"></i>
                            3. Banner Panorámico de Oferta Especial (50% OFF)
                        </h3>
                        <p class="text-xs text-slate-500 mt-0.5">Banner ancho de alto impacto para promociones especiales o liquidaciones.</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="hidden" name="promo_special_active" value="0">
                        <input type="checkbox" name="promo_special_active" value="1" class="sr-only peer" {{ ($settings['promo_special_active'] ?? '1') == '1' ? 'checked' : '' }}>
                        <div class="w-9 h-5 bg-slate-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-indigo-600"></div>
                    </label>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-600 mb-1">Tag / Etiqueta</label>
                        <input type="text" name="promo_special_tag" value="{{ $settings['promo_special_tag'] ?? 'SPECIAL OFFER' }}" class="block w-full rounded-lg border-slate-200 bg-white text-xs px-3 py-2 shadow-sm">
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-600 mb-1">Título</label>
                        <input type="text" name="promo_special_title" value="{{ $settings['promo_special_title'] ?? 'Up to 50% Off Selected Models' }}" class="block w-full rounded-lg border-slate-200 bg-white text-xs px-3 py-2 shadow-sm">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-[11px] font-semibold text-slate-600 mb-1">Subtítulo / Descripción</label>
                        <input type="text" name="promo_special_subtitle" value="{{ $settings['promo_special_subtitle'] ?? 'Limited time deals on high-performance laptops and accessories. Grab your favorite tech now before stock runs out!' }}" class="block w-full rounded-lg border-slate-200 bg-white text-xs px-3 py-2 shadow-sm">
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-600 mb-1">Texto del Botón</label>
                        <input type="text" name="promo_special_button" value="{{ $settings['promo_special_button'] ?? 'Shop Now' }}" class="block w-full rounded-lg border-slate-200 bg-white text-xs px-3 py-2 shadow-sm">
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-600 mb-1">Enlace (URL)</label>
                        <input type="text" name="promo_special_link" value="{{ $settings['promo_special_link'] ?? route('catalog', ['offers' => 1]) }}" class="block w-full rounded-lg border-slate-200 bg-white text-xs px-3 py-2 shadow-sm">
                    </div>
                </div>
            </div>

            <!-- 4. Sección Boletín & Marcas Oficiales -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Newsletter -->
                <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm space-y-3">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                        <h3 class="text-base font-bold text-slate-900 flex items-center gap-2">
                            <i class="fa-regular fa-envelope text-indigo-600"></i>
                            4. Sección Boletín (Newsletter)
                        </h3>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="hidden" name="newsletter_active" value="0">
                            <input type="checkbox" name="newsletter_active" value="1" class="sr-only peer" {{ ($settings['newsletter_active'] ?? '1') == '1' ? 'checked' : '' }}>
                            <div class="w-9 h-5 bg-slate-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-indigo-600"></div>
                        </label>
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-600 mb-1">Título</label>
                        <input type="text" name="newsletter_title" value="{{ $settings['newsletter_title'] ?? 'Join Our Newsletter' }}" class="block w-full rounded-lg border-slate-200 bg-white text-xs px-3 py-2 shadow-sm">
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-600 mb-1">Subtítulo</label>
                        <input type="text" name="newsletter_subtitle" value="{{ $settings['newsletter_subtitle'] ?? 'Get the latest updates, deals and exclusive offers straight to your inbox.' }}" class="block w-full rounded-lg border-slate-200 bg-white text-xs px-3 py-2 shadow-sm">
                    </div>
                </div>

                <!-- Marcas -->
                <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm space-y-3">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                        <h3 class="text-base font-bold text-slate-900 flex items-center gap-2">
                            <i class="fa-solid fa-award text-indigo-600"></i>
                            5. Sección Marcas Oficiales
                        </h3>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="hidden" name="brands_active" value="0">
                            <input type="checkbox" name="brands_active" value="1" class="sr-only peer" {{ ($settings['brands_active'] ?? '1') == '1' ? 'checked' : '' }}>
                            <div class="w-9 h-5 bg-slate-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-indigo-600"></div>
                        </label>
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-600 mb-1">Tag / Subtítulo</label>
                        <input type="text" name="brands_tag" value="{{ $settings['brands_tag'] ?? 'MARCAS OFICIALES' }}" class="block w-full rounded-lg border-slate-200 bg-white text-xs px-3 py-2 shadow-sm">
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-600 mb-1">Título</label>
                        <input type="text" name="brands_title" value="{{ $settings['brands_title'] ?? 'Top Brands' }}" class="block w-full rounded-lg border-slate-200 bg-white text-xs px-3 py-2 shadow-sm">
                    </div>
                    <p class="text-[10px] text-slate-400">
                        <i class="fa-solid fa-circle-info mr-1 text-indigo-500"></i>
                        Las marcas y sus logos se gestionan desde <a href="{{ route('admin.brands.index') }}" class="text-indigo-600 font-bold underline">Productos > Marcas</a>.
                    </p>
                </div>
            </div>

            <!-- Botón Guardar -->
            <div class="flex justify-end">
                <button type="submit" class="btn-primary px-8 py-3 text-sm shadow-lg shadow-indigo-600/30">
                    <i class="fa-solid fa-save mr-1"></i> Guardar Cambios de Banners y Secciones
                </button>
            </div>
        </form>
    </div>

    <!-- TAB 2: Banners de Imagen Subidos -->
    <div x-show="activeTab === 'banners'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-4">
        <div class="flex justify-between items-center bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm">
            <div>
                <h3 class="text-sm font-bold text-slate-800">Listado de Banners de Imagen</h3>
                <p class="text-xs text-slate-500">Imágenes promocionales personalizadas que se ubican en cabeceras o laterales.</p>
            </div>
            <a href="{{ route('admin.banners.create') }}" class="btn-primary">
                <i class="fa-solid fa-plus mr-1"></i> Subir Nuevo Banner
            </a>
        </div>

        <div class="soft-card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Imagen</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Detalles</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Ubicación</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Estado</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-slate-500 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-slate-200">
                        @forelse($banners as $banner)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <img src="{{ asset('storage/' . $banner->image) }}" class="h-16 w-32 object-cover rounded-lg border border-slate-200" alt="Banner Image">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-slate-900">{{ $banner->title ?? 'Sin Título' }}</div>
                                @if($banner->link)
                                    <a href="{{ $banner->link }}" target="_blank" class="text-xs text-blue-500 hover:underline"><i class="fa-solid fa-link"></i> {{ \Illuminate\Support\Str::limit($banner->link, 20) }}</a>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500 font-bold">
                                @if($banner->position === 'all')
                                    <span class="badge badge-gray">Todas las Páginas</span>
                                @elseif($banner->position === 'home')
                                    <span class="badge badge-blue">Solo Inicio</span>
                                @elseif($banner->position === 'catalog')
                                    <span class="badge badge-amber">Solo Catálogo</span>
                                @else
                                    <span class="badge badge-gray">{{ ucfirst($banner->position) }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($banner->status)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Activo</span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Inactivo</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                <a href="{{ route('admin.banners.edit', $banner) }}" class="text-blue-600 hover:text-blue-900 bg-blue-50 p-2 rounded-lg transition-colors"><i class="fa-solid fa-pen"></i></a>
                                
                                <form action="{{ route('admin.banners.destroy', $banner) }}" method="POST" class="inline-block" onsubmit="return confirm('¿Seguro que deseas eliminar este banner?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 bg-red-50 p-2 rounded-lg transition-colors"><i class="fa-solid fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-slate-500">
                                No hay banners registrados.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

