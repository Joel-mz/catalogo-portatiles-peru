@extends('layouts.admin')

@section('header_title', 'Banners')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.banners.index') }}" class="w-10 h-10 flex items-center justify-center rounded-xl bg-white border border-slate-200 text-slate-500 hover:text-blue-600 hover:border-blue-100 hover:bg-blue-50 transition-all shadow-sm">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <div>
            <h2 class="text-2xl font-semibold text-slate-800">Editar Banner</h2>
            <p class="text-sm text-slate-500">Actualiza la imagen o ubicación del banner publicitario.</p>
        </div>
    </div>

    <form action="{{ route('admin.banners.update', $banner) }}" method="POST" enctype="multipart/form-data" class="soft-card p-6">
        @csrf
        @method('PUT')
        <div class="space-y-5">
            <div>
                <label class="block text-xs font-bold text-slate-600 mb-1.5">Título (Opcional)</label>
                <input type="text" name="title" value="{{ old('title', $banner->title) }}" class="form-input" placeholder="Ej: Oferta CyberDays">
                @error('title') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-600 mb-1.5">Imagen Actual</label>
                @if($banner->image)
                    <img src="{{ Storage::url($banner->image) }}" class="h-32 w-auto object-cover rounded-lg border border-slate-200 mb-3" alt="Banner actual">
                @endif
                <label class="block text-xs font-bold text-slate-600 mb-1.5">Reemplazar Imagen (Opcional)</label>
                <input type="file" name="image" accept="image/*" class="form-input" style="padding: 5px;">
                <p class="text-[10px] text-slate-400 mt-1">Sube una nueva imagen sólo si deseas reemplazar la actual.</p>
                @error('image') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div class="grid grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1.5">Página a mostrar (Ubicación)</label>
                    <select name="position" class="form-input" required>
                        <option value="all" {{ old('position', $banner->position) == 'all' ? 'selected' : '' }}>Todas las páginas (Visible en toda la tienda)</option>
                        <option value="home" {{ old('position', $banner->position) == 'home' ? 'selected' : '' }}>Solo en la página de Inicio</option>
                        <option value="catalog" {{ old('position', $banner->position) == 'catalog' ? 'selected' : '' }}>Solo en el Catálogo</option>
                    </select>
                    @error('position') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1.5">Enlace al hacer clic (Opcional)</label>
                    <input type="url" name="link" value="{{ old('link', $banner->link) }}" class="form-input" placeholder="https://...">
                    @error('link') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="flex items-center gap-2 mt-4">
                <input type="checkbox" name="status" id="status" value="1" class="w-4 h-4 text-blue-600 border-slate-300 rounded focus:ring-blue-500" {{ old('status', $banner->status) ? 'checked' : '' }}>
                <label for="status" class="text-sm text-slate-700 font-medium">Banner Activo (Visible)</label>
            </div>
        </div>

        <div class="mt-8 flex justify-end">
            <button type="submit" class="btn-primary">
                <i class="fa-solid fa-save"></i> Guardar Cambios
            </button>
        </div>
    </form>
</div>
@endsection
