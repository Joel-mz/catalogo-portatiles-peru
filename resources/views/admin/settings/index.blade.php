@extends('layouts.admin')
@section('header_title', 'Configuración del Sistema')
@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <div class="flex justify-between items-center px-2">
        <h2 class="text-2xl font-bold text-slate-800">Ajustes Generales</h2>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-200 text-green-700 px-4 py-3 rounded-xl shadow-sm flex items-center gap-2">
            <i class="fa-solid fa-circle-check"></i>
            <span class="text-sm font-medium">{{ session('success') }}</span>
        </div>
    @endif

    <div class="bg-[#fdffec]/40 backdrop-blur-sm border border-[#eef2d8] rounded-3xl p-8 shadow-sm">
        <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="space-y-6">
                <!-- Store Config -->
                <div class="pb-8 border-b border-slate-100/60">
                    <h3 class="text-lg font-bold text-slate-800 mb-6">Información de la Tienda</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Logo de la Empresa (Opcional)</label>
                            <input type="file" name="store_logo" accept="image/*" class="block w-full text-sm text-slate-500
                              file:mr-4 file:py-2 file:px-4
                              file:rounded-lg file:border-0
                              file:text-sm file:font-semibold
                              file:bg-slate-100 file:text-slate-700
                              hover:file:bg-slate-200
                              border border-slate-200 rounded-lg bg-transparent
                            ">
                            @if(!empty($settings['store_logo']))
                                <div class="mt-2">
                                    <img src="{{ Storage::url($settings['store_logo']) }}" alt="Logo" class="h-12 object-contain bg-slate-100 rounded p-1">
                                </div>
                            @endif
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Nombre de la Tienda</label>
                            <input type="text" name="store_name" value="{{ $settings['store_name'] ?? 'PORTÁTILES PERÚ / MOYO TECH' }}" class="block w-full rounded-xl border-slate-200 bg-transparent shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm px-4 py-2.5">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">RUC de la Empresa</label>
                            <input type="text" name="store_ruc" value="{{ $settings['store_ruc'] ?? '' }}" class="block w-full rounded-xl border-slate-200 bg-transparent shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm px-4 py-2.5" placeholder="Ej: 20123456789">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Correo Electrónico</label>
                            <div class="flex rounded-xl shadow-sm border border-slate-200 bg-transparent overflow-hidden">
                                <span class="inline-flex items-center px-4 border-r border-slate-200 bg-white/50 text-slate-500">
                                    <i class="fa-solid fa-envelope text-slate-400"></i>
                                </span>
                                <input type="email" name="store_email" value="{{ $settings['store_email'] ?? '' }}" class="flex-1 min-w-0 block w-full rounded-none border-0 bg-transparent focus:ring-0 sm:text-sm px-3">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Teléfono (WhatsApp)</label>
                            <div class="flex rounded-xl shadow-sm border border-slate-200 bg-transparent overflow-hidden">
                                <span class="inline-flex items-center px-4 border-r border-slate-200 bg-white/50 text-slate-500">
                                    <i class="fa-brands fa-whatsapp text-green-500 text-lg"></i>
                                </span>
                                <input type="text" name="whatsapp_number" value="{{ $settings['whatsapp_number'] ?? '+51999999999' }}" class="flex-1 min-w-0 block w-full rounded-none border-0 bg-transparent focus:ring-0 sm:text-sm px-3">
                            </div>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Mensaje predeterminado de WhatsApp</label>
                            <textarea name="whatsapp_message" rows="2" class="block w-full rounded-xl border-slate-200 bg-transparent shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm px-4 py-3">{{ $settings['whatsapp_message'] ?? 'Hola, estoy interesado en este producto:' }}</textarea>
                            <p class="mt-2 text-xs text-slate-500 font-medium">Este mensaje se usará cuando el cliente haga clic en el botón de comprar desde el catálogo.</p>
                        </div>
                    </div>
                </div>

                <!-- Identidad Corporativa -->
                <div class="pb-8 border-b border-slate-100/60 pt-4">
                    <h3 class="text-lg font-bold text-slate-800 mb-6">Misión y Visión</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Nuestra Misión</label>
                            <textarea name="store_mission" rows="4" class="block w-full rounded-xl border-slate-200 bg-transparent shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm px-4 py-3" placeholder="Define el propósito principal de la empresa...">{{ $settings['store_mission'] ?? '' }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Nuestra Visión</label>
                            <textarea name="store_vision" rows="4" class="block w-full rounded-xl border-slate-200 bg-transparent shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm px-4 py-3" placeholder="¿Hacia dónde se dirige la empresa en el futuro?">{{ $settings['store_vision'] ?? '' }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Colors & Branding -->
                <div class="pb-8 border-b border-slate-100/60 pt-4">
                    <div class="flex justify-between items-end mb-6">
                        <div>
                            <h3 class="text-lg font-bold text-slate-800">Apariencia y Temas</h3>
                            <p class="text-sm text-slate-500 mt-1">Selecciona el diseño visual de tu panel de administración.</p>
                        </div>
                        <div class="flex items-center gap-4">
                            <div>
                                <label class="block text-[11px] font-bold text-slate-500 mb-1">Color Principal (Botones)</label>
                                <div class="flex rounded-xl shadow-sm border border-slate-200 bg-white overflow-hidden h-9 w-32">
                                    <input type="color" value="{{ $settings['primary_color'] ?? '#1E764D' }}" class="h-full w-10 border-0 p-0 cursor-pointer" onchange="document.getElementById('primary_color_input').value = this.value">
                                    <input type="text" id="primary_color_input" name="primary_color" value="{{ $settings['primary_color'] ?? '#1E764D' }}" class="flex-1 min-w-0 block w-full rounded-none border-0 bg-transparent focus:ring-0 font-mono text-[10px] uppercase px-2">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-8">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Mensaje del Banner Superior (Opcional)</label>
                        <input type="text" name="top_banner_text" value="{{ $settings['top_banner_text'] ?? '' }}" class="block w-full rounded-xl border-slate-200 bg-transparent shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm px-4 py-2.5 placeholder-slate-400" placeholder="Ej: ¡Envíos gratis a todo el Perú en compras mayores a S/ 2000!">
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-4">
                        @php
                            $themes = [
                                'light' => [
                                    'name' => 'Claro',
                                    'desc' => 'Azul clásico',
                                    'preview_bg' => 'bg-[#f4f6fa]',
                                    'sidebar' => 'bg-[#0f172a]',
                                    'logo' => 'bg-blue-500',
                                    'navActive' => 'bg-blue-600',
                                    'nav' => 'bg-slate-700',
                                    'topbar' => 'bg-white border border-slate-200',
                                    'card' => 'bg-white border border-slate-200',
                                    'text' => 'bg-slate-700',
                                    'textMuted' => 'bg-slate-300',
                                    'accent' => 'bg-blue-600',
                                    'badge' => 'bg-emerald-500',
                                ],
                                'dark' => [
                                    'name' => 'Oscuro',
                                    'desc' => 'Nocturno azulado',
                                    'preview_bg' => 'bg-[#0f172a]',
                                    'sidebar' => 'bg-[#1e293b]',
                                    'logo' => 'bg-blue-500',
                                    'navActive' => 'bg-blue-500',
                                    'nav' => 'bg-slate-600',
                                    'topbar' => 'bg-[#1e293b] border border-slate-700',
                                    'card' => 'bg-[#1e293b] border border-slate-700',
                                    'text' => 'bg-slate-200',
                                    'textMuted' => 'bg-slate-500',
                                    'accent' => 'bg-blue-500',
                                    'badge' => 'bg-emerald-400',
                                ],
                                'indigo' => [
                                    'name' => 'Índigo',
                                    'desc' => 'Gradiente violeta',
                                    'preview_bg' => 'bg-[#f4f6fa]',
                                    'sidebar' => 'bg-gradient-to-b from-[#0b1730] to-[#142650]',
                                    'logo' => 'bg-indigo-500',
                                    'navActive' => 'bg-gradient-to-r from-blue-600 to-indigo-600',
                                    'nav' => 'bg-indigo-900',
                                    'topbar' => 'bg-white border border-indigo-100',
                                    'card' => 'bg-white border border-indigo-100',
                                    'text' => 'bg-slate-700',
                                    'textMuted' => 'bg-indigo-200',
                                    'accent' => 'bg-indigo-600',
                                    'badge' => 'bg-violet-500',
                                ],
                                'nature' => [
                                    'name' => 'Naturaleza',
                                    'desc' => 'Verde orgánico',
                                    'preview_bg' => 'bg-[#f0fdf4]',
                                    'sidebar' => 'bg-[#064e3b]',
                                    'logo' => 'bg-emerald-400',
                                    'navActive' => 'bg-emerald-600',
                                    'nav' => 'bg-emerald-900',
                                    'topbar' => 'bg-white border border-emerald-100',
                                    'card' => 'bg-white border border-emerald-100',
                                    'text' => 'bg-emerald-900',
                                    'textMuted' => 'bg-emerald-200',
                                    'accent' => 'bg-emerald-600',
                                    'badge' => 'bg-teal-500',
                                ],
                                'ocean' => [
                                    'name' => 'Océano',
                                    'desc' => 'Marino profundo',
                                    'preview_bg' => 'bg-[#f0f9ff]',
                                    'sidebar' => 'bg-gradient-to-b from-[#082f49] to-[#0c4a6e]',
                                    'logo' => 'bg-sky-400',
                                    'navActive' => 'bg-sky-600',
                                    'nav' => 'bg-sky-950',
                                    'topbar' => 'bg-white border border-sky-100',
                                    'card' => 'bg-white border border-sky-100',
                                    'text' => 'bg-slate-800',
                                    'textMuted' => 'bg-sky-200',
                                    'accent' => 'bg-sky-600',
                                    'badge' => 'bg-cyan-500',
                                ],
                                'sunset' => [
                                    'name' => 'Atardecer',
                                    'desc' => 'Cálido carmesí',
                                    'preview_bg' => 'bg-[#fff7ed]',
                                    'sidebar' => 'bg-gradient-to-b from-[#431407] to-[#7c2d12]',
                                    'logo' => 'bg-orange-500',
                                    'navActive' => 'bg-gradient-to-r from-orange-500 to-red-500',
                                    'nav' => 'bg-orange-950',
                                    'topbar' => 'bg-white border border-orange-100',
                                    'card' => 'bg-white border border-orange-100',
                                    'text' => 'bg-amber-950',
                                    'textMuted' => 'bg-orange-200',
                                    'accent' => 'bg-orange-600',
                                    'badge' => 'bg-red-500',
                                ],
                                'rose' => [
                                    'name' => 'Rosa Pastel',
                                    'desc' => 'Carmín & magenta',
                                    'preview_bg' => 'bg-[#fff1f2]',
                                    'sidebar' => 'bg-[#4c0519]',
                                    'logo' => 'bg-rose-400',
                                    'navActive' => 'bg-rose-600',
                                    'nav' => 'bg-rose-950',
                                    'topbar' => 'bg-white border border-rose-100',
                                    'card' => 'bg-white border border-rose-100',
                                    'text' => 'bg-rose-950',
                                    'textMuted' => 'bg-rose-200',
                                    'accent' => 'bg-rose-600',
                                    'badge' => 'bg-pink-500',
                                ],
                                'monochrome' => [
                                    'name' => 'Monocromático',
                                    'desc' => 'Escala de grises',
                                    'preview_bg' => 'bg-[#f8fafc]',
                                    'sidebar' => 'bg-[#1e293b]',
                                    'logo' => 'bg-slate-400',
                                    'navActive' => 'bg-slate-600',
                                    'nav' => 'bg-slate-800',
                                    'topbar' => 'bg-white border border-slate-200',
                                    'card' => 'bg-white border border-slate-200',
                                    'text' => 'bg-slate-800',
                                    'textMuted' => 'bg-slate-300',
                                    'accent' => 'bg-slate-600',
                                    'badge' => 'bg-slate-400',
                                ],
                                'neon' => [
                                    'name' => 'Neón',
                                    'desc' => 'Cyberpunk fucsia',
                                    'preview_bg' => 'bg-[#000000]',
                                    'sidebar' => 'bg-black border-r border-fuchsia-500',
                                    'logo' => 'bg-fuchsia-500',
                                    'navActive' => 'bg-fuchsia-600 text-white',
                                    'nav' => 'bg-fuchsia-950/40',
                                    'topbar' => 'bg-black border border-fuchsia-500',
                                    'card' => 'bg-black border border-fuchsia-600',
                                    'text' => 'bg-fuchsia-300',
                                    'textMuted' => 'bg-fuchsia-800',
                                    'accent' => 'bg-fuchsia-500',
                                    'badge' => 'bg-cyan-400',
                                ],
                                'luxury' => [
                                    'name' => 'Lujo',
                                    'desc' => 'Oro & antracita',
                                    'preview_bg' => 'bg-[#18181b]',
                                    'sidebar' => 'bg-gradient-to-b from-[#18181b] to-[#27272a] border-r border-amber-600',
                                    'logo' => 'bg-amber-500',
                                    'navActive' => 'bg-gradient-to-r from-amber-600 to-amber-700',
                                    'nav' => 'bg-amber-950/40',
                                    'topbar' => 'bg-[#18181b] border border-amber-600/60',
                                    'card' => 'bg-[#27272a] border border-amber-600/40',
                                    'text' => 'bg-amber-200',
                                    'textMuted' => 'bg-amber-800',
                                    'accent' => 'bg-amber-500',
                                    'badge' => 'bg-yellow-400',
                                ],
                            ];
                            $currentTheme = $settings['system_theme'] ?? 'light';
                        @endphp

                        @foreach($themes as $key => $data)
                        <label class="cursor-pointer group relative block">
                            <input type="radio" name="system_theme" value="{{ $key }}" class="peer hidden" {{ $currentTheme === $key ? 'checked' : '' }}>
                            <div class="border-2 border-transparent peer-checked:border-indigo-600 rounded-2xl overflow-hidden transition-all duration-200 peer-checked:shadow-[0_0_15px_rgba(79,70,229,0.35)] peer-checked:scale-[1.02] bg-white shadow-sm hover:shadow-md relative">
                                
                                {{-- Mini UI Mockup Preview --}}
                                <div class="aspect-[16/10] w-full p-2 flex gap-1 {{ $data['preview_bg'] }} transition-transform duration-300 group-hover:scale-[1.02]">
                                    <!-- Mini Sidebar -->
                                    <div class="w-[28%] h-full rounded-md flex flex-col gap-1 p-1 {{ $data['sidebar'] }} shadow-xs">
                                        <div class="h-1.5 w-4 rounded-full {{ $data['logo'] }}"></div>
                                        <div class="h-1 w-full rounded-full {{ $data['navActive'] }} mt-0.5"></div>
                                        <div class="h-1 w-3/4 rounded-full {{ $data['nav'] }}"></div>
                                        <div class="h-1 w-1/2 rounded-full {{ $data['nav'] }}"></div>
                                    </div>
                                    <!-- Mini Content -->
                                    <div class="flex-1 h-full flex flex-col gap-1">
                                        <!-- Mini Topbar -->
                                        <div class="h-2.5 w-full rounded flex items-center justify-between px-1.5 {{ $data['topbar'] }}">
                                            <div class="h-1 w-1/3 rounded-full {{ $data['textMuted'] }}"></div>
                                            <div class="h-1.5 w-1.5 rounded-full {{ $data['accent'] }}"></div>
                                        </div>
                                        <!-- Mini Dashboard Cards -->
                                        <div class="flex-1 grid grid-cols-2 gap-1">
                                            <div class="rounded p-1 flex flex-col justify-between {{ $data['card'] }}">
                                                <div class="h-1 w-3/4 rounded-full {{ $data['text'] }}"></div>
                                                <div class="h-1.5 w-1/2 rounded-full {{ $data['accent'] }}"></div>
                                            </div>
                                            <div class="rounded p-1 flex flex-col justify-between {{ $data['card'] }}">
                                                <div class="h-1 w-3/4 rounded-full {{ $data['text'] }}"></div>
                                                <div class="h-1.5 w-1/2 rounded-full {{ $data['badge'] }}"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Label & Checkbox Indicator -->
                                <div class="p-2.5 bg-white border-t border-slate-100 flex items-center justify-between">
                                    <div class="min-w-0">
                                        <span class="block text-xs font-bold text-slate-800 truncate">{{ $data['name'] }}</span>
                                    </div>
                                    <div class="w-4 h-4 rounded-full border-2 border-slate-300 peer-checked:border-indigo-600 peer-checked:bg-indigo-600 flex items-center justify-center transition-all flex-shrink-0">
                                        <i class="fa-solid fa-check text-[8px] text-white opacity-0 peer-checked:opacity-100"></i>
                                    </div>
                                </div>
                            </div>
                        </label>
                        @endforeach
                    </div>
                </div>
                
                <!-- Social Media -->
                <div class="pt-4 pb-8 border-b border-slate-100/60">
                    <h3 class="text-lg font-bold text-slate-800 mb-6">Redes Sociales</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Facebook URL</label>
                            <div class="flex rounded-xl shadow-sm border border-slate-200 bg-transparent overflow-hidden">
                                <span class="inline-flex items-center px-4 border-r border-slate-200 bg-white/50 text-slate-500">
                                    <i class="fa-brands fa-facebook text-[#1877F2]"></i>
                                </span>
                                <input type="url" name="facebook_url" value="{{ $settings['facebook_url'] ?? '' }}" class="flex-1 min-w-0 block w-full rounded-none border-0 bg-transparent focus:ring-0 sm:text-sm px-3">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Instagram URL</label>
                            <div class="flex rounded-xl shadow-sm border border-slate-200 bg-transparent overflow-hidden">
                                <span class="inline-flex items-center px-4 border-r border-slate-200 bg-white/50 text-slate-500">
                                    <i class="fa-brands fa-instagram text-[#E1306C]"></i>
                                </span>
                                <input type="url" name="instagram_url" value="{{ $settings['instagram_url'] ?? '' }}" class="flex-1 min-w-0 block w-full rounded-none border-0 bg-transparent focus:ring-0 sm:text-sm px-3">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">TikTok URL</label>
                            <div class="flex rounded-xl shadow-sm border border-slate-200 bg-transparent overflow-hidden">
                                <span class="inline-flex items-center px-4 border-r border-slate-200 bg-white/50 text-slate-500">
                                    <i class="fa-brands fa-tiktok text-slate-800"></i>
                                </span>
                                <input type="url" name="tiktok_url" value="{{ $settings['tiktok_url'] ?? '' }}" class="flex-1 min-w-0 block w-full rounded-none border-0 bg-transparent focus:ring-0 sm:text-sm px-3">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">YouTube URL</label>
                            <div class="flex rounded-xl shadow-sm border border-slate-200 bg-transparent overflow-hidden">
                                <span class="inline-flex items-center px-4 border-r border-slate-200 bg-white/50 text-slate-500">
                                    <i class="fa-brands fa-youtube text-[#FF0000]"></i>
                                </span>
                                <input type="url" name="youtube_url" value="{{ $settings['youtube_url'] ?? '' }}" class="flex-1 min-w-0 block w-full rounded-none border-0 bg-transparent focus:ring-0 sm:text-sm px-3">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Optimización SEO y Google -->
                <div class="pt-4 pb-8 border-b border-slate-100/60">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                                <i class="fa-solid fa-magnifying-glass-chart text-indigo-600"></i>
                                Posicionamiento SEO y Google
                            </h3>
                            <p class="text-xs text-slate-500 mt-1">Configura los metadatos globales para que Google indexe tu catálogo virtual y los productos aparezcan en búsquedas.</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <a href="{{ route('sitemap') }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-xs font-bold rounded-xl transition shadow-xs border border-indigo-200/50">
                                <i class="fa-solid fa-sitemap text-indigo-500"></i> Ver Sitemap XML
                            </a>
                            <a href="{{ url('/robots.txt') }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition shadow-xs">
                                <i class="fa-solid fa-robot text-slate-500"></i> Ver Robots.txt
                            </a>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-white/60 p-5 rounded-2xl border border-slate-200/60">
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Título SEO Principal (Meta Title para Google)</label>
                            <input type="text" name="seo_meta_title" value="{{ $settings['seo_meta_title'] ?? 'PORTÁTILES PERÚ — Catálogo Virtual de Laptops, Computadoras y Tecnología' }}" class="block w-full rounded-xl border-slate-200 bg-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-xs sm:text-sm px-4 py-2.5" placeholder="Ej: PORTÁTILES PERÚ — Catálogo Virtual de Laptops y Tecnología en Perú">
                            <p class="mt-1 text-[11px] text-slate-500">Título que aparecerá en los resultados de Google cuando busquen tu tienda o catálogo (Recomendado: 50 a 60 caracteres).</p>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Descripción SEO para Motores de Búsqueda (Meta Description)</label>
                            <textarea name="seo_meta_description" rows="2" class="block w-full rounded-xl border-slate-200 bg-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-xs sm:text-sm px-4 py-2.5" placeholder="Ej: Catálogo virtual con el mejor stock de laptops gamer, computadoras, cámaras de seguridad e impresoras en Perú. Envíos a todo el país y garantía real.">{{ $settings['seo_meta_description'] ?? 'Catálogo virtual con el mejor stock de laptops gamer, computadoras de oficina, cámaras de seguridad e impresoras en Perú. Precios actualizados, garantía y envíos nacionales.' }}</textarea>
                            <p class="mt-1 text-[11px] text-slate-500">Texto descriptivo que muestra Google debajo del título del sitio (Recomendado: 140 a 160 caracteres).</p>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Palabras Clave SEO (Keywords)</label>
                            <input type="text" name="seo_keywords" value="{{ $settings['seo_keywords'] ?? 'laptops peru, computadoras lima, catalogo virtual, camaras de seguridad ezviz, tecnologia peru, comprar laptops, precios computadoras, portatiles peru' }}" class="block w-full rounded-xl border-slate-200 bg-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-xs sm:text-sm px-4 py-2.5" placeholder="laptops peru, computadoras, catalogo virtual...">
                            <p class="mt-1 text-[11px] text-slate-500">Palabras o frases separadas por comas que describen tus productos.</p>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Código de Verificación de Google (Search Console)</label>
                            <input type="text" name="google_site_verification" value="{{ $settings['google_site_verification'] ?? '' }}" class="block w-full rounded-xl border-slate-200 bg-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-xs sm:text-sm px-4 py-2.5 font-mono" placeholder="Ej: dGhpcyBpcyBhbiBleGFtcGxl">
                            <p class="mt-1 text-[11px] text-slate-500">Código de la metaetiqueta que te da Google Search Console.</p>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Código de Verificación de Bing Webmaster</label>
                            <input type="text" name="bing_site_verification" value="{{ $settings['bing_site_verification'] ?? '' }}" class="block w-full rounded-xl border-slate-200 bg-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-xs sm:text-sm px-4 py-2.5 font-mono" placeholder="Ej: 1234567890ABCDEF1234567890ABCDEF">
                            <p class="mt-1 text-[11px] text-slate-500">Código de metaetiqueta para Bing & Yahoo Search Webmasters.</p>
                        </div>
                    </div>
                </div>

                <!-- Backup System -->
                <div class="pt-4">
                    <h3 class="text-lg font-bold text-slate-800 mb-2">Copia de Seguridad (Backup)</h3>
                    <p class="text-sm text-slate-500 mb-6">Genera copias de seguridad de toda tu base de datos o restaura copias anteriores. Se realiza una copia automática cada 24h.</p>
                    <div class="flex flex-wrap gap-4">
                        <a href="#" class="inline-flex justify-center items-center py-2.5 px-6 border border-slate-200 shadow-sm text-sm font-bold rounded-xl text-slate-700 bg-white hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-500 transition-colors">
                            <i class="fa-solid fa-download mr-2 text-indigo-600"></i> Descargar Copia Manual
                        </a>
                        <label class="inline-flex justify-center items-center py-2.5 px-6 border border-slate-200 shadow-sm text-sm font-bold rounded-xl text-slate-700 bg-white hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-500 transition-colors cursor-pointer">
                            <i class="fa-solid fa-upload mr-2 text-emerald-600"></i> Subir y Restaurar Copia
                            <input type="file" class="hidden" accept=".sql,.zip">
                        </label>
                    </div>
                </div>

            </div>

            <div class="mt-10 pt-6 flex justify-end">
                <button type="submit" class="inline-flex justify-center items-center py-2.5 px-8 border border-transparent shadow-lg shadow-indigo-200 text-sm font-bold rounded-xl text-white bg-[#5540D3] hover:bg-[#4330b3] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                    Guardar Configuraciones
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
