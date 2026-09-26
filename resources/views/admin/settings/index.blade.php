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
                            @if(isset($settings['store_logo']))
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

                    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                        @php
                            $themes = [
                                'light' => ['name' => 'Claro', 'filter' => 'hue-rotate(0deg) saturate(1)'],
                                'dark' => ['name' => 'Oscuro', 'filter' => 'invert(0.9) hue-rotate(180deg)'],
                                'indigo' => ['name' => 'Índigo', 'filter' => 'hue-rotate(220deg) saturate(1.5)'],
                                'nature' => ['name' => 'Naturaleza', 'filter' => 'hue-rotate(140deg) saturate(1.2)'],
                                'ocean' => ['name' => 'Océano', 'filter' => 'hue-rotate(190deg) saturate(1.4)'],
                                'sunset' => ['name' => 'Atardecer', 'filter' => 'hue-rotate(10deg) saturate(1.6) brightness(0.9)'],
                                'rose' => ['name' => 'Rosa Pastel', 'filter' => 'hue-rotate(320deg) saturate(1.3)'],
                                'monochrome' => ['name' => 'Monocromático', 'filter' => 'grayscale(1)'],
                                'neon' => ['name' => 'Neón', 'filter' => 'invert(1) hue-rotate(280deg) saturate(2)'],
                                'luxury' => ['name' => 'Lujo', 'filter' => 'invert(0.9) sepia(1) hue-rotate(350deg) saturate(2)'],
                            ];
                            $currentTheme = $settings['system_theme'] ?? 'light';
                        @endphp

                        @foreach($themes as $key => $data)
                        <label class="cursor-pointer group relative">
                            <input type="radio" name="system_theme" value="{{ $key }}" class="peer hidden" {{ $currentTheme === $key ? 'checked' : '' }}>
                            <div class="border-2 border-transparent peer-checked:border-indigo-600 rounded-2xl overflow-hidden transition-all peer-checked:shadow-[0_0_15px_rgba(79,70,229,0.3)] bg-slate-100 relative">
                                <div class="aspect-video w-full overflow-hidden relative">
                                    <img src="{{ Storage::url('base_theme_preview.jpg') }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" style="filter: {{ $data['filter'] }}">
                                </div>
                                <div class="p-2.5 text-center bg-white border-t border-slate-100 flex items-center justify-between">
                                    <span class="text-[11px] font-bold text-slate-700">{{ $data['name'] }}</span>
                                    <div class="w-4 h-4 rounded-full border border-slate-300 peer-checked:border-indigo-600 peer-checked:bg-indigo-600 flex items-center justify-center transition-colors">
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
