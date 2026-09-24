@extends('layouts.admin')

@section('header_title', 'Categorías')

@section('content')
<!-- Header title and subtitle like the image -->
<div class="mb-6">
    <div class="flex items-center gap-3">
        <div class="h-10 w-10 bg-blue-600 rounded-lg flex items-center justify-center text-white shadow-sm">
            <i class="fa-solid fa-layer-group"></i>
        </div>
        <div>
            <h2 class="text-xl font-bold text-slate-800">Categorías</h2>
            <p class="text-xs text-slate-500">Organiza tus productos en categorías para una mejor experiencia de compra.</p>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg relative text-sm" role="alert">
        <span class="block sm:inline"><i class="fa-solid fa-check-circle mr-1"></i> {{ session('success') }}</span>
    </div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
    
    <!-- COLUMNA IZQUIERDA: Listado (col-span-7) -->
    <div class="lg:col-span-7 soft-card">
        <div class="p-4 border-b border-slate-100 flex justify-between items-center bg-white rounded-t-2xl">
            <h3 class="font-bold text-slate-800 flex items-center gap-2">
                <i class="fa-regular fa-list-alt text-blue-500"></i> Listado de categorías
            </h3>
            
            <div class="flex items-center gap-3 text-sm">
                <div class="flex items-center gap-2 text-slate-500">
                    <span>Mostrar</span>
                    <select class="border-slate-200 rounded text-xs py-1 pl-2 pr-6">
                        <option>10</option>
                    </select>
                    <span>registros</span>
                </div>
                
                <div class="relative">
                    <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                    <input type="text" placeholder="Buscar categoría..." class="border-slate-200 rounded-full text-xs py-1.5 pl-8 pr-4 w-48 focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>
        </div>

        <div class="overflow-x-auto bg-white rounded-b-2xl">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="border-b border-slate-100">
                        <th class="py-3 px-4 text-left font-semibold text-slate-500 text-xs w-16">ID <i class="fa-solid fa-sort ml-1 text-slate-300"></i></th>
                        <th class="py-3 px-4 text-left font-semibold text-slate-500 text-xs w-20">Imagen <i class="fa-solid fa-sort ml-1 text-slate-300"></i></th>
                        <th class="py-3 px-4 text-left font-semibold text-slate-500 text-xs">Nombre de categoría <i class="fa-solid fa-sort ml-1 text-slate-300"></i></th>
                        <th class="py-3 px-4 text-left font-semibold text-slate-500 text-xs">Descripción</th>
                        <th class="py-3 px-4 text-center font-semibold text-slate-500 text-xs">Productos</th>
                        <th class="py-3 px-4 text-center font-semibold text-slate-500 text-xs">Estado</th>
                        <th class="py-3 px-4 text-right font-semibold text-slate-500 text-xs w-24">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($categories as $category)
                    <tr class="hover:bg-slate-50/50 transition-colors {{ isset($editingCategory) && $editingCategory->id === $category->id ? 'bg-blue-50/50' : '' }}">
                        <td class="py-3 px-4 text-slate-500">{{ $category->id }}</td>
                        <td class="py-3 px-4">
                            <div class="h-8 w-8 bg-slate-100 rounded flex items-center justify-center overflow-hidden">
                                @if($category->image)
                                    <img src="{{ Storage::url($category->image) }}" class="h-full w-full object-cover">
                                @else
                                    <i class="fa-solid fa-image text-slate-300"></i>
                                @endif
                            </div>
                        </td>
                        <td class="py-3 px-4 font-medium text-slate-800">{{ $category->name }}</td>
                        <td class="py-3 px-4 text-slate-500 truncate max-w-[150px] text-xs" title="{{ $category->description }}">{{ $category->description ?? 'Sin descripción' }}</td>
                        <td class="py-3 px-4 text-center font-medium text-blue-600">{{ $category->products_count ?? 0 }}</td>
                        <td class="py-3 px-4 text-center">
                            @if($category->status)
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
                            <a href="{{ route('admin.categories.index', ['edit' => $category->id]) }}" class="inline-flex items-center justify-center h-7 w-7 rounded bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition-colors" title="Editar">
                                <i class="fa-solid fa-pen text-xs"></i>
                            </a>
                            <a href="#" class="inline-flex items-center justify-center h-7 w-7 rounded bg-slate-50 text-slate-400 hover:bg-slate-200 transition-colors" title="Ver">
                                <i class="fa-solid fa-eye text-xs"></i>
                            </a>
                            <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="inline-block" onsubmit="return confirm('¿Seguro que deseas eliminar esta categoría?');">
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
                        <td colspan="7" class="py-8 text-center text-slate-500 text-sm">No hay categorías registradas.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            
            <div class="px-4 py-3 border-t border-slate-100 flex items-center justify-between">
                <span class="text-xs text-slate-500">Mostrando {{ $categories->firstItem() ?? 0 }} a {{ $categories->lastItem() ?? 0 }} de {{ $categories->total() }} registros</span>
                <div class="scale-90 origin-right">
                    {{ $categories->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- COLUMNA CENTRAL: Agregar / Editar (col-span-3) -->
    <div class="lg:col-span-3 soft-card bg-white rounded-2xl overflow-hidden">
        <div class="p-4 border-b border-slate-100 flex items-center gap-2">
            <i class="fa-solid {{ isset($editingCategory) ? 'fa-pen-to-square' : 'fa-user-plus' }} text-blue-500"></i>
            <h3 class="font-bold text-slate-800">{{ isset($editingCategory) ? 'Editar' : 'Agregar' }} categoría</h3>
        </div>
        
        <div class="p-4">
            <form action="{{ isset($editingCategory) ? route('admin.categories.update', $editingCategory) : route('admin.categories.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                @if(isset($editingCategory))
                    @method('PUT')
                @endif
                
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Nombre de categoría <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $editingCategory->name ?? '') }}" placeholder="Ej. Laptops" required class="w-full text-sm border-slate-200 rounded-lg focus:ring-blue-500 focus:border-blue-500 px-3 py-2">
                    @error('name') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                </div>
                
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Descripción</label>
                    <textarea name="description" rows="3" placeholder="Breve descripción de la categoría..." class="w-full text-sm border-slate-200 rounded-lg focus:ring-blue-500 focus:border-blue-500 px-3 py-2 resize-none">{{ old('description', $editingCategory->description ?? '') }}</textarea>
                    @error('description') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div class="col-span-2">
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Imagen de categoría</label>
                        <div class="border-2 border-dashed border-slate-200 rounded-lg p-4 text-center hover:bg-slate-50 transition-colors relative">
                            <input type="file" name="image" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" accept="image/*">
                            @if(isset($editingCategory) && $editingCategory->image)
                                <img src="{{ Storage::url($editingCategory->image) }}" class="mx-auto h-12 w-12 object-cover rounded mb-2">
                                <p class="text-[10px] text-slate-400">Clic para cambiar</p>
                            @else
                                <i class="fa-solid fa-image text-slate-300 text-2xl mb-2"></i>
                                <p class="text-[10px] font-semibold text-slate-600">Selecciona una imagen</p>
                                <p class="text-[9px] text-slate-400">PNG, JPG (máx. 2 MB)</p>
                            @endif
                        </div>
                    </div>
                    
                    <div class="col-span-2 flex items-center justify-between py-2 border-t border-slate-100">
                        <span class="text-xs font-semibold text-slate-700">Estado</span>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="status" value="1" class="sr-only peer" {{ old('status', $editingCategory->status ?? true) ? 'checked' : '' }}>
                            <div class="w-9 h-5 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-blue-600"></div>
                            <span class="ml-2 text-xs text-slate-500">Activo</span>
                        </label>
                    </div>
                </div>
                
                <div class="pt-2 flex gap-2">
                    @if(isset($editingCategory))
                        <a href="{{ route('admin.categories.index') }}" class="flex-1 text-center py-2 px-4 border border-slate-200 text-slate-600 rounded-lg text-xs font-semibold hover:bg-slate-50 transition-colors">Cancelar</a>
                        <button type="submit" class="flex-1 py-2 px-4 bg-blue-600 text-white rounded-lg text-xs font-semibold hover:bg-blue-700 transition-colors shadow-sm shadow-blue-500/30">Actualizar</button>
                    @else
                        <button type="reset" class="flex-1 py-2 px-4 border border-slate-200 text-slate-600 rounded-lg text-xs font-semibold hover:bg-slate-50 transition-colors">Limpiar</button>
                        <button type="submit" class="flex-1 py-2 px-4 bg-blue-600 text-white rounded-lg text-xs font-semibold hover:bg-blue-700 transition-colors shadow-sm shadow-blue-500/30">Guardar</button>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- COLUMNA DERECHA: Estadísticas (col-span-2) -->
    <div class="lg:col-span-2 soft-card bg-white rounded-2xl overflow-hidden">
        <div class="p-4 border-b border-slate-100">
            <h3 class="font-bold text-slate-800 text-sm">Categorías más usadas</h3>
        </div>
        <div class="p-4 space-y-4">
            @foreach($topCategories as $cat)
                @php
                    $percentage = $totalProducts > 0 ? round(($cat->products_count / $totalProducts) * 100) : 0;
                @endphp
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="h-8 w-8 bg-slate-50 rounded flex items-center justify-center border border-slate-100 shrink-0">
                            @if($cat->image)
                                <img src="{{ Storage::url($cat->image) }}" class="h-5 w-5 object-cover rounded-sm">
                            @else
                                <i class="fa-solid fa-folder text-slate-300 text-xs"></i>
                            @endif
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-800 leading-tight">{{ $cat->name }}</p>
                            <p class="text-[10px] text-slate-500">{{ $cat->products_count }} productos</p>
                        </div>
                    </div>
                    <span class="text-xs font-semibold text-slate-400">{{ $percentage }}%</span>
                </div>
            @endforeach
            
            @if($topCategories->isEmpty())
                <p class="text-xs text-slate-500 text-center">No hay datos suficientes.</p>
            @endif
        </div>
    </div>
    
</div>
@endsection
