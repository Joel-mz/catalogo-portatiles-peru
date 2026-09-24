@extends('layouts.admin')

@section('header_title', 'Editar Slider')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.sliders.index') }}" class="text-slate-400 hover:text-slate-600 transition-colors">
            <i class="fa-solid fa-arrow-left text-xl"></i>
        </a>
        <h2 class="text-2xl font-semibold text-slate-800">Editar Slider #{{ $slider->id }}</h2>
    </div>

    <div class="soft-card p-6">
        <form action="{{ route('admin.sliders.update', $slider) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Imagen Actual -->
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Imagen Actual</label>
                <div class="mt-2 mb-4">
                    <img src="{{ Storage::url($slider->image) }}" class="h-32 w-auto rounded-lg border border-slate-200" alt="Slider Image">
                </div>
            </div>

            <!-- Nueva Imagen -->
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Cambiar Imagen (Opcional)</label>
                <input type="file" name="image" accept="image/*" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                <p class="mt-1 text-xs text-slate-500">Deja este campo vacío si deseas mantener la imagen actual.</p>
                @error('image') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>

            <!-- Título -->
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Título (Opcional)</label>
                <input type="text" name="title" value="{{ old('title', $slider->title) }}" class="w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                @error('title') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>
            
            <!-- Subtítulo -->
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Subtítulo (Opcional)</label>
                <input type="text" name="subtitle" value="{{ old('subtitle', $slider->subtitle) }}" class="w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                @error('subtitle') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>

            <!-- Enlace -->
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Enlace de Destino (Opcional)</label>
                <input type="url" name="link" value="{{ old('link', $slider->link) }}" class="w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                @error('link') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Orden -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Orden de Aparición</label>
                    <input type="number" name="order" value="{{ old('order', $slider->order) }}" class="w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    @error('order') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Estado -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Estado</label>
                    <label class="inline-flex items-center cursor-pointer mt-2">
                        <input type="checkbox" name="status" value="1" {{ old('status', $slider->status) ? 'checked' : '' }} class="sr-only peer">
                        <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                        <span class="ml-3 text-sm font-medium text-slate-700">Activo / Visible</span>
                    </label>
                </div>
            </div>

            <!-- Actions -->
            <div class="pt-6 border-t border-slate-100 flex justify-end gap-3">
                <a href="{{ route('admin.sliders.index') }}" class="px-4 py-2 border border-slate-300 rounded-md text-slate-700 hover:bg-slate-50 transition-colors">Cancelar</a>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors shadow-sm">Actualizar Slider</button>
            </div>
        </form>
    </div>
</div>
@endsection
