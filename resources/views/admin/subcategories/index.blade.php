@extends('layouts.admin')

@section('header_title', 'Subcategorías')

@section('content')
<div class="mb-6">
    <div class="flex items-center gap-3">
        <div class="h-10 w-10 bg-blue-600 rounded-lg flex items-center justify-center text-white shadow-sm">
            <i class="fa-solid fa-sitemap"></i>
        </div>
        <div>
            <h2 class="text-xl font-bold text-slate-800">Subcategorías</h2>
            <p class="text-xs text-slate-500">Organiza tus productos de forma más detallada en subcategorías.</p>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg relative text-sm" role="alert">
        <span class="block sm:inline"><i class="fa-solid fa-check-circle mr-1"></i> {{ session('success') }}</span>
    </div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
    
    <!-- COLUMNA IZQUIERDA: Listado (col-span-8) -->
    <div class="lg:col-span-8 soft-card">
        <div class="p-4 border-b border-slate-100 flex justify-between items-center bg-white rounded-t-2xl">
            <h3 class="font-bold text-slate-800 flex items-center gap-2">
                <i class="fa-regular fa-list-alt text-blue-500"></i> Listado de Subcategorías
            </h3>
            
            <div class="flex items-center gap-3 text-sm">
                <div class="relative">
                    <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                    <input type="text" placeholder="Buscar..." class="border-slate-200 rounded-full text-xs py-1.5 pl-8 pr-4 w-48 focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>
        </div>

        <div class="overflow-x-auto bg-white rounded-b-2xl">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="border-b border-slate-100">
                        <th class="py-3 px-4 text-left font-semibold text-slate-500 text-xs w-16">ID</th>
                        <th class="py-3 px-4 text-left font-semibold text-slate-500 text-xs">Categoría Padre</th>
                        <th class="py-3 px-4 text-left font-semibold text-slate-500 text-xs">Nombre</th>
                        <th class="py-3 px-4 text-left font-semibold text-slate-500 text-xs">Slug</th>
                        <th class="py-3 px-4 text-center font-semibold text-slate-500 text-xs">Estado</th>
                        <th class="py-3 px-4 text-right font-semibold text-slate-500 text-xs w-24">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($subcategories as $subcategory)
                    <tr class="hover:bg-slate-50/50 transition-colors {{ isset($editingSubcategory) && $editingSubcategory->id === $subcategory->id ? 'bg-blue-50/50' : '' }}">
                        <td class="py-3 px-4 text-slate-500">{{ $subcategory->id }}</td>
                        <td class="py-3 px-4 font-medium text-slate-600">
                            {{ $subcategory->category->name ?? 'N/A' }}
                        </td>
                        <td class="py-3 px-4 font-bold text-slate-800">{{ $subcategory->name }}</td>
                        <td class="py-3 px-4 text-slate-400 text-xs">{{ $subcategory->slug }}</td>
                        <td class="py-3 px-4 text-center">
                            @if($subcategory->status)
                                <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-[10px] font-medium bg-green-50 text-green-600 border border-green-200">
                                    <span class="h-1.5 w-1.5 rounded-full bg-green-500"></span> Activo
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-[10px] font-medium bg-red-50 text-red-600 border border-red-200">
                                    <span class="h-1.5 w-1.5 rounded-full bg-red-500"></span> Inactivo
                                </span>
                            @endif
                        </td>
                        <td class="py-3 px-4 text-right space-x-1">
                            <a href="{{ route('admin.subcategories.index', ['edit' => $subcategory->id]) }}" class="inline-flex items-center justify-center h-7 w-7 rounded bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition-colors" title="Editar">
                                <i class="fa-solid fa-pen text-xs"></i>
                            </a>
                            <form action="{{ route('admin.subcategories.destroy', $subcategory) }}" method="POST" class="inline-block" onsubmit="return confirm('¿Seguro que deseas eliminar esta Subcategoría?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center justify-center h-7 w-7 rounded bg-red-50 text-red-500 hover:bg-red-500 hover:text-white transition-colors" title="Eliminar">
                                    <i class="fa-solid fa-trash text-xs"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-8 text-center text-slate-500 text-sm">No hay Subcategorías registradas.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            
            <div class="px-4 py-3 border-t border-slate-100 flex items-center justify-between">
                <span class="text-xs text-slate-500">Mostrando {{ $subcategories->firstItem() ?? 0 }} a {{ $subcategories->lastItem() ?? 0 }} de {{ $subcategories->total() }} registros</span>
                <div class="scale-90 origin-right">
                    {{ $subcategories->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- COLUMNA DERECHA: Agregar / Editar (col-span-4) -->
    <div class="lg:col-span-4 soft-card bg-white rounded-2xl overflow-hidden">
        <div class="p-4 border-b border-slate-100 flex items-center gap-2">
            <i class="fa-solid {{ isset($editingSubcategory) ? 'fa-pen-to-square' : 'fa-plus' }} text-blue-500"></i>
            <h3 class="font-bold text-slate-800">{{ isset($editingSubcategory) ? 'Editar' : 'Agregar' }} Subcategoría</h3>
        </div>
        
        <div class="p-4">
            <form action="{{ isset($editingSubcategory) ? route('admin.subcategories.update', $editingSubcategory) : route('admin.subcategories.store') }}" method="POST" class="space-y-4">
                @csrf
                @if(isset($editingSubcategory))
                    @method('PUT')
                @endif
                
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Categoría Padre <span class="text-red-500">*</span></label>
                    <select name="category_id" required class="w-full text-sm border-slate-200 rounded-lg focus:ring-blue-500 focus:border-blue-500 px-3 py-2">
                        <option value="">-- Seleccionar --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id', $editingSubcategory->category_id ?? '') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Nombre de Subcategoría <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $editingSubcategory->name ?? '') }}" placeholder="Ej. Laptops Gaming" required class="w-full text-sm border-slate-200 rounded-lg focus:ring-blue-500 focus:border-blue-500 px-3 py-2">
                    @error('name') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                </div>
                
                <div class="flex items-center justify-between py-2 border-t border-slate-100 mt-4">
                    <span class="text-xs font-semibold text-slate-700">Estado</span>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="status" value="1" class="sr-only peer" {{ old('status', $editingSubcategory->status ?? true) ? 'checked' : '' }}>
                        <div class="w-9 h-5 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-blue-600"></div>
                        <span class="ml-2 text-xs text-slate-500">Activo</span>
                    </label>
                </div>
                
                <div class="pt-4 flex gap-2">
                    @if(isset($editingSubcategory))
                        <a href="{{ route('admin.subcategories.index') }}" class="flex-1 text-center py-2 px-4 border border-slate-200 text-slate-600 rounded-lg text-xs font-semibold hover:bg-slate-50 transition-colors">Cancelar</a>
                        <button type="submit" class="flex-1 py-2 px-4 bg-blue-600 text-white rounded-lg text-xs font-semibold hover:bg-blue-700 transition-colors shadow-sm shadow-blue-500/30">Actualizar</button>
                    @else
                        <button type="reset" class="flex-1 py-2 px-4 border border-slate-200 text-slate-600 rounded-lg text-xs font-semibold hover:bg-slate-50 transition-colors">Limpiar</button>
                        <button type="submit" class="flex-1 py-2 px-4 bg-blue-600 text-white rounded-lg text-xs font-semibold hover:bg-blue-700 transition-colors shadow-sm shadow-blue-500/30">Guardar</button>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
