@extends('layouts.admin')
@section('header_title', 'Nueva Categoría')
@section('content')
<div class="soft-card p-6 max-w-2xl mx-auto">
    <form action="{{ route('admin.categories.store') }}" method="POST">
        @csrf
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-slate-700">Nombre</label>
                <input type="text" name="name" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="flex items-center">
                    <input type="checkbox" name="status" value="1" class="rounded border-slate-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" checked>
                    <span class="ml-2 text-sm text-slate-600">Activo</span>
                </label>
            </div>
            <div class="flex justify-end mt-6 pt-4 border-t border-slate-100">
                <a href="{{ route('admin.categories.index') }}" class="mr-3 px-4 py-2 text-slate-600 hover:bg-slate-100 rounded-lg transition-colors">Cancelar</a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 shadow-sm transition-colors">Guardar Categoría</button>
            </div>
        </div>
    </form>
</div>
@endsection
