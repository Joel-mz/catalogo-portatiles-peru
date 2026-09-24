@extends('layouts.admin')

@section('header_title', 'Crear Slider')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.sliders.index') }}" class="text-slate-400 hover:text-slate-600 transition-colors">
            <i class="fa-solid fa-arrow-left text-xl"></i>
        </a>
        <h2 class="text-2xl font-semibold text-slate-800">Añadir Nuevo Slider</h2>
    </div>

    <div class="soft-card p-6">
        <form action="{{ route('admin.sliders.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- Imagen -->
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Imagen (Recomendado: 1920x600) <span class="text-red-500">*</span></label>
                <input type="file" name="image" required accept="image/*" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                @error('image') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>

            <!-- Título -->
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Título (Opcional)</label>
                <input type="text" name="title" value="{{ old('title') }}" class="w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                @error('title') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>
            
            <!-- Subtítulo -->
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Subtítulo (Opcional)</label>
                <input type="text" name="subtitle" value="{{ old('subtitle') }}" class="w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                @error('subtitle') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>

            <!-- Enlace -->
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Enlace de Destino (Opcional)</label>
                <input type="url" name="link" value="{{ old('link') }}" placeholder="https://..." class="w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                @error('link') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Orden -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Orden de Aparición</label>
                    <input type="number" name="order" value="{{ old('order', 0) }}" class="w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    @error('order') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Estado -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Estado</label>
                    <label class="inline-flex items-center cursor-pointer mt-2">
                        <input type="checkbox" name="status" value="1" {{ old('status', true) ? 'checked' : '' }} class="sr-only peer">
                        <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                        <span class="ml-3 text-sm font-medium text-slate-700">Activo / Visible</span>
                    </label>
                </div>
            </div>

            <!-- Actions -->
            <div class="pt-6 border-t border-slate-100 flex justify-end gap-3">
                <a href="{{ route('admin.sliders.index') }}" class="px-4 py-2 border border-slate-300 rounded-md text-slate-700 hover:bg-slate-50 transition-colors">Cancelar</a>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors shadow-sm">Guardar Slider</button>
            </div>
        </form>
    </div>
</div>
@endsection
