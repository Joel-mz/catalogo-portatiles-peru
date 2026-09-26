@extends('layouts.admin')

@section('header_title', 'Crear Publicidad')

@section('content')
<div class="max-w-4xl mx-auto space-y-6" x-data="publicidadForm()">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.publicidad.index') }}" class="w-10 h-10 flex items-center justify-center rounded-xl bg-white border border-slate-200 text-slate-500 hover:text-indigo-600 hover:border-indigo-100 hover:bg-indigo-50 transition-all shadow-sm">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <div>
                <h2 class="text-2xl font-bold text-slate-800">Crear Nueva Publicidad</h2>
                <p class="text-sm text-slate-500">Sube imágenes o videos promocionales para los espacios vacíos y laterales de tu tienda.</p>
            </div>
        </div>
        <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-3 py-1 text-xs font-bold text-emerald-700 border border-emerald-200">
            <i class="fa-solid fa-photo-film"></i> Imagen & Video
        </span>
    </div>

    @if ($errors->any())
        <div class="rounded-2xl bg-rose-50 border border-rose-200 p-4 text-rose-700 shadow-sm">
            <div class="flex items-center gap-2 font-bold text-sm mb-1">
                <i class="fa-solid fa-triangle-exclamation"></i> Por favor corrige los siguientes errores:
            </div>
            <ul class="list-disc list-inside text-xs space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.publicidad.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Left Column: Form Fields -->
            <div class="lg:col-span-2 space-y-5 bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/80 shadow-sm">
                <!-- Title -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                        Título o Nombre de Campaña <span class="text-slate-400 font-normal">(Opcional)</span>
                    </label>
                    <input type="text" name="title" value="{{ old('title') }}" class="form-input text-sm px-4 py-3 rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100" placeholder="Ej: Ofertas de Verano en Laptops Gamer">
                    <p class="text-[11px] text-slate-400 mt-1">Texto descriptivo para identificar tu anuncio o mostrar como pie de banner.</p>
                </div>

                <!-- Media Upload Zone -->
                <div class="space-y-2">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                        Archivo de Imagen o Video <span class="text-rose-500">*</span>
                    </label>
                    
                    <div class="border-2 border-dashed border-indigo-200 hover:border-indigo-500 rounded-2xl p-6 text-center bg-slate-50 transition-all">
                        <div class="flex flex-col items-center justify-center space-y-3">
                            <div class="w-12 h-12 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center text-xl shadow-sm">
                                <i class="fa-solid fa-cloud-arrow-up"></i>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-slate-800">Selecciona o arrastra una imagen / video</p>
                                <p class="text-xs text-slate-400 mt-0.5">Formatos: JPG, PNG, WEBP, GIF, MP4, WEBM (Máx 30 MB)</p>
                            </div>
                            <input type="file" name="image" required accept="image/*,video/mp4,video/webm,video/ogg,video/quicktime" 
                                   class="block w-full max-w-sm text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-indigo-600 file:text-white hover:file:bg-indigo-700 file:cursor-pointer cursor-pointer border border-slate-200 rounded-xl bg-white p-1"
                                   @change="handleFileSelect($event)">
                        </div>
                    </div>
                </div>

                <!-- Location Selector with Visual Options -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                        Ubicación en la Tienda <span class="text-rose-500">*</span>
                    </label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <label class="relative flex cursor-pointer rounded-2xl border p-3.5 shadow-sm transition hover:border-indigo-300"
                               :class="location === 'all' ? 'border-indigo-600 bg-indigo-50/50 ring-2 ring-indigo-500/20' : 'border-slate-200 bg-white'">
                            <input type="radio" name="location" value="all" x-model="location" class="sr-only">
                            <div class="flex items-start gap-3">
                                <div class="w-9 h-9 rounded-xl bg-indigo-100 text-indigo-700 flex items-center justify-center shrink-0 text-sm">
                                    <i class="fa-solid fa-arrows-left-right-to-line"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-slate-800">Ambos Lados (General)</p>
                                    <p class="text-[10px] text-slate-500">Márgenes vacíos izquierdo y derecho en todas las páginas.</p>
                                </div>
                            </div>
                        </label>

                        <label class="relative flex cursor-pointer rounded-2xl border p-3.5 shadow-sm transition hover:border-indigo-300"
                               :class="location === 'sidebar_left' ? 'border-indigo-600 bg-indigo-50/50 ring-2 ring-indigo-500/20' : 'border-slate-200 bg-white'">
                            <input type="radio" name="location" value="sidebar_left" x-model="location" class="sr-only">
                            <div class="flex items-start gap-3">
                                <div class="w-9 h-9 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center shrink-0 text-sm">
                                    <i class="fa-solid fa-align-left"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-slate-800">Lateral Izquierdo</p>
                                    <p class="text-[10px] text-slate-500">Espacio vacío en blanco del lado izquierdo.</p>
                                </div>
                            </div>
                        </label>

                        <label class="relative flex cursor-pointer rounded-2xl border p-3.5 shadow-sm transition hover:border-indigo-300"
                               :class="location === 'sidebar_right' ? 'border-indigo-600 bg-indigo-50/50 ring-2 ring-indigo-500/20' : 'border-slate-200 bg-white'">
                            <input type="radio" name="location" value="sidebar_right" x-model="location" class="sr-only">
                            <div class="flex items-start gap-3">
                                <div class="w-9 h-9 rounded-xl bg-purple-100 text-purple-700 flex items-center justify-center shrink-0 text-sm">
                                    <i class="fa-solid fa-align-right"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-slate-800">Lateral Derecho</p>
                                    <p class="text-[10px] text-slate-500">Espacio vacío en blanco del lado derecho.</p>
                                </div>
                            </div>
                        </label>

                        <label class="relative flex cursor-pointer rounded-2xl border p-3.5 shadow-sm transition hover:border-indigo-300"
                               :class="location === 'home' ? 'border-indigo-600 bg-indigo-50/50 ring-2 ring-indigo-500/20' : 'border-slate-200 bg-white'">
                            <input type="radio" name="location" value="home" x-model="location" class="sr-only">
                            <div class="flex items-start gap-3">
                                <div class="w-9 h-9 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0 text-sm">
                                    <i class="fa-solid fa-house"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-slate-800">Solo en Inicio</p>
                                    <p class="text-[10px] text-slate-500">Sección de anuncios destacados de la portada.</p>
                                </div>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Target Link -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                        Enlace de Destino al hacer clic <span class="text-slate-400 font-normal">(Opcional)</span>
                    </label>
                    <div class="relative">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400 text-sm">
                            <i class="fa-solid fa-link"></i>
                        </div>
                        <input type="url" name="link" value="{{ old('link') }}" class="form-input pl-10 text-sm py-3 rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100" placeholder="https://tu-tienda.com/catalogo o enlace de WhatsApp">
                    </div>
                </div>

                <!-- Status Toggle -->
                <div class="pt-2">
                    <label class="flex items-center gap-3 cursor-pointer select-none">
                        <input type="checkbox" name="status" id="status" value="1" {{ old('status', true) ? 'checked' : '' }} class="h-5 w-5 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                        <div>
                            <span class="text-sm font-bold text-slate-800">Publicidad Activa</span>
                            <p class="text-[11px] text-slate-400">Si está desmarcada, no se mostrará a los clientes.</p>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Right Column: Live Preview & Summary -->
            <div class="space-y-6">
                <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm sticky top-24">
                    <h3 class="text-xs font-black uppercase tracking-wider text-slate-700 mb-4 flex items-center gap-2">
                        <i class="fa-solid fa-eye text-indigo-600"></i>
                        Vista Previa en Tiempo Real
                    </h3>

                    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-slate-50 min-h-[220px] flex flex-col items-center justify-center p-3 text-center relative">
                        <template x-if="previewType === 'image'">
                            <div class="w-full">
                                <img :src="previewUrl" alt="Vista previa" class="w-full h-auto max-h-72 object-contain rounded-xl shadow-sm">
                            </div>
                        </template>

                        <template x-if="previewType === 'video'">
                            <div class="w-full">
                                <video :src="previewUrl" controls autoplay loop muted playsinline class="w-full h-auto max-h-72 rounded-xl object-contain shadow-sm bg-black"></video>
                            </div>
                        </template>

                        <template x-if="!previewUrl">
                            <div class="text-slate-400 py-10">
                                <i class="fa-regular fa-image text-4xl mb-2 text-slate-300"></i>
                                <p class="text-xs font-medium">Sube una imagen o video para ver la previsualización.</p>
                            </div>
                        </template>
                    </div>

                    <div class="mt-4 p-3.5 rounded-xl bg-slate-50 border border-slate-100 space-y-2">
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-slate-500">Tipo de Medio:</span>
                            <span class="font-bold uppercase" :class="previewType === 'video' ? 'text-purple-600' : (previewType === 'image' ? 'text-indigo-600' : 'text-slate-400')" x-text="previewType || 'Sin archivo'"></span>
                        </div>
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-slate-500">Ubicación:</span>
                            <span class="font-bold text-slate-700" x-text="getLocationLabel(location)"></span>
                        </div>
                    </div>

                    <div class="mt-6">
                        <button type="submit" class="btn-primary w-full py-3.5 justify-center text-sm font-bold shadow-lg shadow-indigo-600/30">
                            <i class="fa-solid fa-check"></i> Guardar Publicidad
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </form>
</div>

<script>
    function publicidadForm() {
        return {
            location: '{{ old('location', 'all') }}',
            previewUrl: '',
            previewType: '',

            handleFileSelect(event) {
                const file = event.target.files[0];
                this.loadPreview(file);
            },

            handleFileDrop(event) {
                const file = event.dataTransfer.files[0];
                if (file) {
                    this.$refs.fileInput.files = event.dataTransfer.files;
                    this.loadPreview(file);
                }
            },

            loadPreview(file) {
                if (!file) {
                    this.previewUrl = '';
                    this.previewType = '';
                    return;
                }
                this.previewUrl = URL.createObjectURL(file);
                if (file.type.startsWith('video/')) {
                    this.previewType = 'video';
                } else {
                    this.previewType = 'image';
                }
            },

            getLocationLabel(loc) {
                const map = {
                    'all': 'Ambos Lados (General)',
                    'sidebar_left': 'Lateral Izquierdo',
                    'sidebar_right': 'Lateral Derecho',
                    'home': 'Solo en Inicio',
                    'catalog': 'Solo en Catálogo'
                };
                return map[loc] || loc;
            }
        }
    }
</script>
@endsection
