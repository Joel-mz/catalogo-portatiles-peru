@extends('layouts.admin')

@section('header_title', 'Marcas')

@section('content')
<div class="mb-6">
    <div class="flex items-center gap-3">
        <div class="h-10 w-10 bg-blue-600 rounded-lg flex items-center justify-center text-white shadow-sm">
            <i class="fa-solid fa-tags"></i>
        </div>
        <div>
            <h2 class="text-xl font-bold text-slate-800">Marcas</h2>
            <p class="text-xs text-slate-500">Gestiona las marcas de tus productos.</p>
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
                <i class="fa-regular fa-list-alt text-blue-500"></i> Listado de marcas
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
                    <input type="text" placeholder="Buscar marca..." class="border-slate-200 rounded-full text-xs py-1.5 pl-8 pr-4 w-48 focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>
        </div>

        <div class="overflow-x-auto bg-white rounded-b-2xl">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="border-b border-slate-100">
                        <th class="py-3 px-4 text-left font-semibold text-slate-500 text-xs w-16">ID <i class="fa-solid fa-sort ml-1 text-slate-300"></i></th>
                        <th class="py-3 px-4 text-left font-semibold text-slate-500 text-xs w-20">Logo <i class="fa-solid fa-sort ml-1 text-slate-300"></i></th>
                        <th class="py-3 px-4 text-left font-semibold text-slate-500 text-xs">Marca <i class="fa-solid fa-sort ml-1 text-slate-300"></i></th>
                        <th class="py-3 px-4 text-left font-semibold text-slate-500 text-xs">Descripción</th>
                        <th class="py-3 px-4 text-center font-semibold text-slate-500 text-xs">Productos</th>
                        <th class="py-3 px-4 text-center font-semibold text-slate-500 text-xs">Estado</th>
                        <th class="py-3 px-4 text-right font-semibold text-slate-500 text-xs w-24">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($brands as $brand)
                    <tr class="hover:bg-slate-50/50 transition-colors {{ isset($editingBrand) && $editingBrand->id === $brand->id ? 'bg-blue-50/50' : '' }}">
                        <td class="py-3 px-4 text-slate-500">{{ $brand->id }}</td>
                        <td class="py-3 px-4">
                            <div class="h-8 w-12 bg-white border border-slate-100 rounded flex items-center justify-center p-1">
                                @if($brand->logo)
                                    <img src="{{ Storage::url($brand->logo) }}" class="max-h-full max-w-full object-contain">
                                @else
                                    <span class="text-[10px] text-slate-400 font-bold">LOGO</span>
                                @endif
                            </div>
                        </td>
                        <td class="py-3 px-4 font-medium text-slate-800">{{ $brand->name }}</td>
                        <td class="py-3 px-4 text-slate-500 truncate max-w-[150px] text-xs" title="{{ $brand->description }}">{{ $brand->description ?? 'Sin descripción' }}</td>
                        <td class="py-3 px-4 text-center font-medium text-blue-600">{{ $brand->products_count ?? 0 }}</td>
                        <td class="py-3 px-4 text-center">
                            @if($brand->status)
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
                            <a href="{{ route('admin.brands.index', ['edit' => $brand->id]) }}" class="inline-flex items-center justify-center h-7 w-7 rounded bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition-colors" title="Editar">
                                <i class="fa-solid fa-pen text-xs"></i>
                            </a>
                            <a href="#" class="inline-flex items-center justify-center h-7 w-7 rounded bg-slate-50 text-slate-400 hover:bg-slate-200 transition-colors" title="Ver">
                                <i class="fa-solid fa-eye text-xs"></i>
                            </a>
                            <form action="{{ route('admin.brands.destroy', $brand) }}" method="POST" class="inline-block" onsubmit="return confirm('¿Seguro que deseas eliminar esta marca?');">
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
                        <td colspan="7" class="py-8 text-center text-slate-500 text-sm">No hay marcas registradas.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            
            <div class="px-4 py-3 border-t border-slate-100 flex items-center justify-between">
                <span class="text-xs text-slate-500">Mostrando {{ $brands->firstItem() ?? 0 }} a {{ $brands->lastItem() ?? 0 }} de {{ $brands->total() }} registros</span>
                <div class="scale-90 origin-right">
                    {{ $brands->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- COLUMNA CENTRAL: Agregar / Editar (col-span-3) -->
    <div class="lg:col-span-3 soft-card bg-white rounded-2xl overflow-hidden">
        <div class="p-4 border-b border-slate-100 flex items-center gap-2">
            <i class="fa-solid {{ isset($editingBrand) ? 'fa-pen-to-square' : 'fa-tag' }} text-blue-500"></i>
            <h3 class="font-bold text-slate-800">{{ isset($editingBrand) ? 'Editar' : 'Agregar' }} marca</h3>
        </div>
        
        <div class="p-4">
            <form action="{{ isset($editingBrand) ? route('admin.brands.update', $editingBrand) : route('admin.brands.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                @if(isset($editingBrand))
                    @method('PUT')
                @endif
                
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Nombre de la marca <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $editingBrand->name ?? '') }}" placeholder="Ej. Lenovo" required class="w-full text-sm border-slate-200 rounded-lg focus:ring-blue-500 focus:border-blue-500 px-3 py-2">
                    @error('name') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                </div>
                
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Descripción</label>
                    <textarea name="description" rows="3" placeholder="Breve descripción de la marca..." class="w-full text-sm border-slate-200 rounded-lg focus:ring-blue-500 focus:border-blue-500 px-3 py-2 resize-none">{{ old('description', $editingBrand->description ?? '') }}</textarea>
                    @error('description') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div class="col-span-2">
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Logo de la marca</label>
                        <div class="border-2 border-dashed border-slate-200 rounded-lg p-4 text-center hover:bg-slate-50 transition-colors relative">
                            <input type="file" name="logo" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" accept="image/*">
                            @if(isset($editingBrand) && $editingBrand->logo)
                                <img src="{{ Storage::url($editingBrand->logo) }}" class="mx-auto h-12 w-24 object-contain rounded mb-2 bg-white">
                                <p class="text-[10px] text-slate-400">Clic para cambiar</p>
                            @else
                                <i class="fa-solid fa-image text-slate-300 text-2xl mb-2"></i>
                                <p class="text-[10px] font-semibold text-slate-600">Selecciona un logo</p>
                                <p class="text-[9px] text-slate-400">PNG, JPG (máx. 2 MB)</p>
                            @endif
                        </div>
                    </div>
                    
                    <div class="col-span-2 flex items-center justify-between py-2 border-t border-slate-100">
                        <span class="text-xs font-semibold text-slate-700">Estado</span>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="status" value="1" class="sr-only peer" {{ old('status', $editingBrand->status ?? true) ? 'checked' : '' }}>
                            <div class="w-9 h-5 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-blue-600"></div>
                            <span class="ml-2 text-xs text-slate-500">Activo</span>
                        </label>
                    </div>
                </div>
                
                <div class="pt-2 flex gap-2">
                    @if(isset($editingBrand))
                        <a href="{{ route('admin.brands.index') }}" class="flex-1 text-center py-2 px-4 border border-slate-200 text-slate-600 rounded-lg text-xs font-semibold hover:bg-slate-50 transition-colors">Cancelar</a>
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
            <h3 class="font-bold text-slate-800 text-sm">Marcas más utilizadas</h3>
        </div>
        <div class="p-4 space-y-4">
            @foreach($topBrands as $brandStat)
                @php
                    $percentage = $totalProducts > 0 ? round(($brandStat->products_count / $totalProducts) * 100) : 0;
                @endphp
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="h-8 w-12 bg-white rounded flex items-center justify-center border border-slate-100 shrink-0 p-1">
                            @if($brandStat->logo)
                                <img src="{{ Storage::url($brandStat->logo) }}" class="max-h-full max-w-full object-contain">
                            @else
                                <span class="text-[8px] font-bold text-slate-300">LOGO</span>
                            @endif
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-800 leading-tight">{{ $brandStat->name }}</p>
                            <p class="text-[10px] text-slate-500">{{ $brandStat->products_count }} productos</p>
                        </div>
                    </div>
                    <span class="text-xs font-semibold text-slate-400">{{ $percentage }}%</span>
                </div>
            @endforeach
            
            @if($topBrands->isEmpty())
                <p class="text-xs text-slate-500 text-center">No hay datos suficientes.</p>
            @endif
        </div>
    </div>
    
</div>
@endsection
