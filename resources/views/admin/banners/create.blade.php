@extends('layouts.admin')

@section('header_title', 'Banners')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.banners.index') }}" class="w-10 h-10 flex items-center justify-center rounded-xl bg-white border border-slate-200 text-slate-500 hover:text-blue-600 hover:border-blue-100 hover:bg-blue-50 transition-all shadow-sm">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <div>
            <h2 class="text-2xl font-semibold text-slate-800">Crear Banner</h2>
            <p class="text-sm text-slate-500">Añade un nuevo banner publicitario a tu tienda.</p>
        </div>
    </div>

    <form action="{{ route('admin.banners.store') }}" method="POST" enctype="multipart/form-data" class="soft-card p-6">
        @csrf
        <div class="space-y-5">
            <div>
                <label class="block text-xs font-bold text-slate-600 mb-1.5">Título (Opcional)</label>
                <input type="text" name="title" value="{{ old('title') }}" class="form-input" placeholder="Ej: Oferta CyberDays">
                @error('title') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-600 mb-1.5">Imagen (Requerida)</label>
                <input type="file" name="image" accept="image/*" required class="form-input" style="padding: 5px;">
                <p class="text-[10px] text-slate-400 mt-1">Sube una imagen horizontal (ej. 1200x400px).</p>
                @error('image') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div class="grid grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1.5">Página a mostrar (Ubicación)</label>
                    <select name="position" class="form-input" required>
                        <option value="all" {{ old('position') == 'all' ? 'selected' : '' }}>Todas las páginas (Visible en toda la tienda)</option>
                        <option value="home" {{ old('position') == 'home' ? 'selected' : '' }}>Solo en la página de Inicio</option>
                        <option value="catalog" {{ old('position') == 'catalog' ? 'selected' : '' }}>Solo en el Catálogo</option>
                    </select>
                    @error('position') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1.5">Enlace al hacer clic (Opcional)</label>
                    <input type="url" name="link" value="{{ old('link') }}" class="form-input" placeholder="https://...">
                    @error('link') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="flex items-center gap-2 mt-4">
                <input type="checkbox" name="status" id="status" value="1" class="w-4 h-4 text-blue-600 border-slate-300 rounded focus:ring-blue-500" {{ old('status', true) ? 'checked' : '' }}>
                <label for="status" class="text-sm text-slate-700 font-medium">Banner Activo (Visible)</label>
            </div>
        </div>

        <div class="mt-8 flex justify-end">
            <button type="submit" class="btn-primary">
                <i class="fa-solid fa-save"></i> Guardar Banner
            </button>
        </div>
    </form>
</div>
@endsection
