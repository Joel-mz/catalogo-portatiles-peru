@extends('layouts.admin')
@section('header_title', 'Configuración del Sistema')
@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-semibold text-slate-800">Ajustes Generales</h2>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-200 text-green-700 px-4 py-3 rounded-lg relative" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <div class="soft-card p-6">
        <form action="{{ route('admin.settings.update') }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="space-y-6">
                <!-- Store Config -->
                <div class="pb-6 border-b border-slate-100">
                    <h3 class="text-lg font-medium text-slate-900 mb-4">Información de la Tienda</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Nombre de la Tienda</label>
                            <input type="text" name="store_name" value="{{ $settings['store_name'] ?? 'PORTÁTILES PERÚ' }}" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Teléfono (WhatsApp)</label>
                            <div class="mt-1 flex rounded-md shadow-sm">
                                <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-slate-300 bg-slate-50 text-slate-500 sm:text-sm">
                                    <i class="fa-brands fa-whatsapp text-green-500"></i>
                                </span>
                                <input type="text" name="whatsapp_number" value="{{ $settings['whatsapp_number'] ?? '' }}" class="flex-1 min-w-0 block w-full rounded-none rounded-r-md border-slate-300 focus:border-blue-500 focus:ring-blue-500">
                            </div>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-slate-700">Mensaje predeterminado de WhatsApp</label>
                            <textarea name="whatsapp_message" rows="2" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ $settings['whatsapp_message'] ?? 'Hola, estoy interesado en este producto:' }}</textarea>
                            <p class="mt-1 text-xs text-slate-500">Este mensaje se usará cuando el cliente haga clic en el botón de comprar desde el catálogo.</p>
                        </div>
                    </div>
                </div>

                <!-- Colors & Branding -->
                <div class="pb-6 border-b border-slate-100">
                    <h3 class="text-lg font-medium text-slate-900 mb-4">Apariencia</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Color Principal (Hex)</label>
                            <div class="mt-1 flex rounded-md shadow-sm">
                                <input type="color" value="{{ $settings['primary_color'] ?? '#2563eb' }}" class="h-10 w-10 border-0 p-0 rounded-l-md cursor-pointer" onchange="document.getElementById('primary_color_input').value = this.value">
                                <input type="text" id="primary_color_input" name="primary_color" value="{{ $settings['primary_color'] ?? '#2563eb' }}" class="flex-1 min-w-0 block w-full rounded-none rounded-r-md border-slate-300 focus:border-blue-500 focus:ring-blue-500 font-mono text-sm uppercase">
                            </div>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-slate-700">Mensaje del Banner Superior (Opcional)</label>
                            <input type="text" name="top_banner_text" value="{{ $settings['top_banner_text'] ?? '' }}" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Ej: ¡Envíos gratis a todo el Perú en compras mayores a S/ 2000!">
                        </div>
                    </div>
                </div>
                
                <!-- Social Media -->
                <div>
                    <h3 class="text-lg font-medium text-slate-900 mb-4">Redes Sociales</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Facebook URL</label>
                            <div class="mt-1 flex rounded-md shadow-sm">
                                <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-slate-300 bg-slate-50 text-slate-500 sm:text-sm">
                                    <i class="fa-brands fa-facebook"></i>
                                </span>
                                <input type="url" name="facebook_url" value="{{ $settings['facebook_url'] ?? '' }}" class="flex-1 min-w-0 block w-full rounded-none rounded-r-md border-slate-300 focus:border-blue-500 focus:ring-blue-500">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Instagram URL</label>
                            <div class="mt-1 flex rounded-md shadow-sm">
                                <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-slate-300 bg-slate-50 text-slate-500 sm:text-sm">
                                    <i class="fa-brands fa-instagram"></i>
                                </span>
                                <input type="url" name="instagram_url" value="{{ $settings['instagram_url'] ?? '' }}" class="flex-1 min-w-0 block w-full rounded-none rounded-r-md border-slate-300 focus:border-blue-500 focus:ring-blue-500">
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="mt-8 pt-5 border-t border-slate-200 flex justify-end">
                <button type="submit" class="inline-flex justify-center py-2 px-6 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Guardar Configuraciones
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
