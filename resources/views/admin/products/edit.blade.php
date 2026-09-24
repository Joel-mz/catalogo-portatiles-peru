@extends('layouts.admin')
@section('header_title', 'Editar Producto')
@section('content')
<div class="soft-card p-6 max-w-4xl mx-auto">
    <form action="{{ route('admin.products.update', $product) }}" method="POST">
        @csrf @method('PUT')
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            <div class="col-span-2 md:col-span-1">
                <label class="block text-sm font-medium text-slate-700">Nombre del Producto</label>
                <input type="text" name="name" value="{{ old('name', $product->name) }}" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-slate-700">Código</label>
                <input type="text" name="code" value="{{ old('code', $product->code) }}" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-slate-700">SKU (Opcional)</label>
                <input type="text" name="sku" value="{{ old('sku', $product->sku) }}" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-slate-700">Categoría</label>
                <select name="category_id" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700">Marca</label>
                <select name="brand_id" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                    @foreach($brands as $brand)
                        <option value="{{ $brand->id }}" {{ old('brand_id', $product->brand_id) == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700">Modelo (Opcional)</label>
                <select name="device_model_id" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">Ninguno...</option>
                    @foreach($models as $model)
                        <option value="{{ $model->id }}" {{ old('device_model_id', $product->device_model_id) == $model->id ? 'selected' : '' }}>{{ $model->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-span-2">
                <label class="block text-sm font-medium text-slate-700">Descripción</label>
                <textarea name="description" rows="3" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('description', $product->description) }}</textarea>
            </div>

            <div class="col-span-2">
                <label class="block text-sm font-medium text-slate-700">Especificaciones Técnicas (Una por línea. Formato: Propiedad: Valor)</label>
                <textarea name="technical_specs" rows="4" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('technical_specs', $specsString) }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700">Precio (S/)</label>
                <input type="number" step="0.01" name="price" value="{{ old('price', $product->price) }}" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700">Precio de Oferta (Opcional) (S/)</label>
                <input type="number" step="0.01" name="offer_price" value="{{ old('offer_price', $product->offer_price) }}" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700">Stock</label>
                <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700">Garantía</label>
                <input type="text" name="warranty" value="{{ old('warranty', $product->warranty) }}" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>

            <div class="col-span-2 grid grid-cols-3 gap-4 border-t border-slate-100 pt-4">
                <label class="flex items-center">
                    <input type="checkbox" name="status" value="1" class="rounded border-slate-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" {{ old('status', $product->status) ? 'checked' : '' }}>
                    <span class="ml-2 text-sm text-slate-600">Público (Activo)</span>
                </label>
                <label class="flex items-center">
                    <input type="checkbox" name="is_offer" value="1" class="rounded border-slate-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" {{ old('is_offer', $product->is_offer) ? 'checked' : '' }}>
                    <span class="ml-2 text-sm text-slate-600">En Oferta</span>
                </label>
                <label class="flex items-center">
                    <input type="checkbox" name="is_featured" value="1" class="rounded border-slate-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}>
                    <span class="ml-2 text-sm text-slate-600">Destacado</span>
                </label>
            </div>

            <div class="col-span-2 flex justify-end mt-4 pt-4 border-t border-slate-100">
                <a href="{{ route('admin.products.index') }}" class="mr-3 px-4 py-2 text-slate-600 hover:bg-slate-100 rounded-lg transition-colors">Cancelar</a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 shadow-sm transition-colors">Actualizar Producto</button>
            </div>
        </div>
    </form>
</div>
@endsection
