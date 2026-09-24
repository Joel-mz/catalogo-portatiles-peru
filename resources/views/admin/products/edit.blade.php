@extends('layouts.admin')
@section('header_title', 'Editar Producto')
@section('content')

@php
    $formattedSpecs = collect($product->technical_specs)->map(function($val, $key) {
        return ['key' => $key, 'value' => $val];
    })->values()->all();
    
    if(empty($formattedSpecs)) {
        $formattedSpecs = [
            ['key' => 'Procesador', 'value' => ''],
            ['key' => 'Memoria RAM', 'value' => ''],
            ['key' => 'Almacenamiento', 'value' => ''],
            ['key' => 'Pantalla', 'value' => '']
        ];
    }

    $existingImages = collect($product->images)->map(function($img) {
        return filter_var($img->image_path, FILTER_VALIDATE_URL) ? $img->image_path : Storage::url($img->image_path);
    })->toArray();
@endphp

<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div class="flex items-center gap-3">
        <div class="h-10 w-10 bg-indigo-600 rounded-xl flex items-center justify-center text-white shadow-lg shadow-indigo-200">
            <i class="fa-solid fa-pen-to-square"></i>
        </div>
        <div>
            <h2 class="text-xl font-extrabold text-slate-800">Editar Producto</h2>
            <p class="text-xs text-slate-500">Actualiza la información del producto en el catálogo.</p>
        </div>
    </div>
    <div class="text-sm font-medium text-slate-500 flex items-center gap-2">
        <a href="{{ route('admin.products.index') }}" class="hover:text-indigo-600"><i class="fa-solid fa-home text-xs"></i> Productos</a>
        <i class="fa-solid fa-chevron-right text-[10px]"></i>
        <span class="text-indigo-700">Editar Producto</span>
    </div>
</div>

<form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data" 
      x-data="productForm()" 
      class="pb-24">
    @csrf
    @method('PUT')

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        
        <!-- Left Column -->
        <div class="lg:col-span-8 space-y-6">
            
            <!-- 1. Información Principal -->
            <section class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 relative overflow-hidden">
                <div class="absolute top-0 left-0 w-1 h-full bg-indigo-500"></div>
                <h3 class="text-base font-extrabold text-slate-800 mb-5 flex items-center gap-2">
                    <span class="flex items-center justify-center w-6 h-6 rounded-full bg-indigo-100 text-indigo-700 text-xs">1</span> 
                    Información Principal
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-slate-700 mb-1">Nombre del Producto <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <i class="fa-solid fa-laptop absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                            <input type="text" name="name" x-model="name" class="w-full pl-9 pr-4 py-2 bg-slate-50 border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors" placeholder="Ej. Laptop Lenovo IdeaPad Slim 3" required>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Categoría <span class="text-red-500">*</span></label>
                        <select name="category_id" @change="updateCategoryName($event)" class="w-full px-3 py-2 bg-slate-50 border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors" required>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" data-name="{{ $cat->name }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Marca <span class="text-red-500">*</span></label>
                        <select name="brand_id" @change="updateBrandName($event)" class="w-full px-3 py-2 bg-slate-50 border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors" required>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}" data-name="{{ $brand->name }}" {{ old('brand_id', $product->brand_id) == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Modelo (Opcional)</label>
                        <div class="relative">
                            <i class="fa-regular fa-id-card absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                            <select name="device_model_id" class="w-full pl-9 pr-4 py-2 bg-slate-50 border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                                <option value="">Ninguno...</option>
                                @foreach($models as $model)
                                    <option value="{{ $model->id }}" {{ old('device_model_id', $product->device_model_id) == $model->id ? 'selected' : '' }}>{{ $model->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-slate-700 mb-1">Descripción corta <span class="text-red-500">*</span></label>
                        <textarea name="description" rows="2" class="w-full px-3 py-2 bg-slate-50 border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors" placeholder="Breve descripción del producto...">{{ old('description', $product->description) }}</textarea>
                    </div>
                </div>
            </section>

            <!-- 2. Identificación del Producto -->
            <section class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 relative overflow-hidden">
                <div class="absolute top-0 left-0 w-1 h-full bg-blue-500"></div>
                <h3 class="text-base font-extrabold text-slate-800 mb-5 flex items-center gap-2">
                    <span class="flex items-center justify-center w-6 h-6 rounded-full bg-blue-100 text-blue-700 text-xs">2</span> 
                    Identificación del Producto
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Código Oficial (P/N) <span class="text-red-500">*</span></label>
                        <div class="relative flex">
                            <span class="inline-flex items-center px-3 rounded-l-xl border border-r-0 border-slate-200 bg-slate-100 text-slate-500 sm:text-sm">
                                <i class="fa-solid fa-barcode"></i>
                            </span>
                            <input type="text" name="code" id="product_code" value="{{ old('code', $product->code) }}" class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-r-xl bg-slate-50 border-slate-200 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" required>
                            
                            <!-- Scanner btn -->
                            <button type="button" onclick="startScanner()" class="absolute right-2 top-1/2 -translate-y-1/2 text-indigo-600 hover:text-indigo-800" title="Escanear">
                                <i class="fa-solid fa-camera"></i>
                            </button>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Tipo de Control <span class="text-red-500">*</span></label>
                        <select name="control_type" class="w-full px-3 py-2 bg-slate-50 border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" required>
                            <option value="quantity" {{ old('control_type', $product->control_type) == 'quantity' ? 'selected' : '' }}>Solo por Cantidad</option>
                            <option value="serial" {{ old('control_type', $product->control_type) == 'serial' ? 'selected' : '' }}>Por Número de Serie (Unitario)</option>
                            <option value="lot" {{ old('control_type', $product->control_type) == 'lot' ? 'selected' : '' }}>Por Lote</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Número de Serie (Opcional)</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                                <i class="fa-solid fa-hashtag"></i>
                            </span>
                            <input type="text" name="serial_number" value="{{ old('serial_number', $product->serial_number) }}" class="w-full pl-9 pr-3 py-2 bg-slate-50 border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                    </div>

                    <div class="md:col-span-3">
                        <label class="block text-xs font-bold text-slate-700 mb-1">SKU Interno</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                                <i class="fa-solid fa-tag"></i>
                            </span>
                            <input type="text" name="sku" value="{{ old('sku', $product->sku) }}" class="w-full pl-9 pr-3 py-2 bg-slate-50 border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                    </div>
                </div>
            </section>

            <!-- 3. Precios e Inventario -->
            <section class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 relative overflow-hidden">
                <div class="absolute top-0 left-0 w-1 h-full bg-emerald-500"></div>
                <h3 class="text-base font-extrabold text-slate-800 mb-5 flex items-center gap-2">
                    <span class="flex items-center justify-center w-6 h-6 rounded-full bg-emerald-100 text-emerald-700 text-xs">3</span> 
                    Precios e Inventario
                </h3>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-5">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Precio Venta <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-500 font-bold">S/</span>
                            <input type="number" step="0.01" name="price" x-model="price" class="w-full pl-9 pr-3 py-2 bg-slate-50 border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" required>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Precio Oferta</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-red-500 font-bold">S/</span>
                            <input type="number" step="0.01" name="offer_price" x-model="offerPrice" class="w-full pl-9 pr-3 py-2 bg-slate-50 border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Stock Actual <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400"><i class="fa-solid fa-boxes-stacked"></i></span>
                            <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" class="w-full pl-9 pr-3 py-2 bg-slate-50 border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" required>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Garantía</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400"><i class="fa-solid fa-shield-halved"></i></span>
                            <input type="text" name="warranty" value="{{ old('warranty', $product->warranty) }}" class="w-full pl-9 pr-3 py-2 bg-slate-50 border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                    </div>
                </div>
            </section>

            <!-- 4. Especificaciones Técnicas -->
            <section class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 relative overflow-hidden">
                <div class="absolute top-0 left-0 w-1 h-full bg-violet-500"></div>
                <h3 class="text-base font-extrabold text-slate-800 mb-5 flex items-center gap-2">
                    <span class="flex items-center justify-center w-6 h-6 rounded-full bg-violet-100 text-violet-700 text-xs">4</span> 
                    Especificaciones Técnicas
                </h3>

                <div class="flex gap-6 flex-col md:flex-row">
                    <div class="flex-1 space-y-3">
                        <template x-for="(spec, index) in specs" :key="index">
                            <div class="flex items-center gap-2">
                                <div class="relative w-1/3">
                                    <span class="absolute inset-y-0 left-0 flex items-center pl-2 text-slate-400 text-[10px]"><i class="fa-solid fa-grip-vertical"></i></span>
                                    <input type="text" :name="'spec_keys['+index+']'" x-model="spec.key" placeholder="Característica" class="w-full pl-6 pr-2 py-2 bg-slate-50 border-slate-200 rounded-lg text-xs focus:ring-2 focus:ring-indigo-500">
                                </div>
                                <div class="relative flex-1">
                                    <input type="text" :name="'spec_values['+index+']'" x-model="spec.value" placeholder="Valor" class="w-full px-3 py-2 bg-slate-50 border-slate-200 rounded-lg text-xs focus:ring-2 focus:ring-indigo-500">
                                </div>
                                <button type="button" @click="removeSpec(index)" class="w-8 h-8 flex items-center justify-center text-red-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                    <i class="fa-solid fa-trash-can text-sm"></i>
                                </button>
                            </div>
                        </template>
                        
                        <button type="button" @click="addSpec()" class="mt-2 inline-flex items-center justify-center gap-2 w-full sm:w-auto px-4 py-2 bg-indigo-50 text-indigo-700 rounded-xl text-xs font-bold hover:bg-indigo-100 transition-colors">
                            <i class="fa-solid fa-plus"></i> Agregar especificación
                        </button>
                    </div>
                </div>
            </section>
        </div>

        <!-- Right Column -->
        <div class="lg:col-span-4 space-y-6">
            
            <!-- 5. Imágenes -->
            <!-- 5. Imágenes -->
            <section class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 relative overflow-hidden" x-data="imageUploadManager()">
                <div class="absolute top-0 left-0 w-1 h-full bg-fuchsia-500"></div>
                <h3 class="text-sm font-extrabold text-slate-800 mb-4 flex items-center gap-2">
                    <span class="flex items-center justify-center w-5 h-5 rounded-full bg-fuchsia-100 text-fuchsia-700 text-[10px]">5</span> Imágenes del Producto
                </h3>
                
                <div class="border-2 border-dashed border-slate-200 rounded-xl p-4 text-center bg-slate-50 relative hover:bg-slate-100 hover:border-indigo-300 transition-colors">
                    <i class="fa-solid fa-cloud-arrow-up text-3xl text-indigo-400 mb-2"></i>
                    <p class="text-xs font-bold text-indigo-600 mb-2">Reemplazar imágenes actuales (Máx 7)</p>
                    <p class="text-[9px] text-slate-500 mb-2">Al añadir nuevas imágenes, las anteriores se eliminarán.</p>
                    
                    <div class="flex flex-col sm:flex-row justify-center items-center gap-2">
                        <button type="button" @click="addFileInput()" class="px-4 py-2 bg-white border border-slate-300 text-slate-700 text-[10px] font-bold rounded-lg shadow-sm hover:bg-slate-50 transition-colors flex items-center gap-1">
                            <i class="fa-solid fa-folder-open text-indigo-500"></i> Subir desde PC
                        </button>
                        <span class="text-[10px] text-slate-400 font-bold hidden sm:inline">o</span>
                        <div class="flex items-center gap-1 bg-white border border-slate-300 rounded-lg p-1 shadow-sm w-full sm:w-auto">
                            <input type="url" x-model="tempUrl" placeholder="Pegar URL de imagen" class="text-[10px] border-none focus:ring-0 w-full sm:w-40 h-7 bg-transparent" @keydown.enter.prevent="addUrlInput()">
                            <button type="button" @click="addUrlInput()" class="px-3 py-1.5 bg-indigo-50 text-indigo-700 text-[10px] font-bold rounded hover:bg-indigo-100 transition-colors">
                                Añadir URL
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Contenedor oculto para inputs de archivo -->
                <div class="hidden">
                    <template x-for="(img, index) in images" :key="img.id">
                        <div x-show="img.type === 'file'">
                            <input type="file" :id="'file_input_' + img.id" :name="'image_files['+index+']'" accept="image/*" @change="handleFileChange($event, img)">
                        </div>
                    </template>
                </div>

                <!-- Inputs ocultos para tipos y URLs -->
                <template x-for="(img, index) in images" :key="'types-'+img.id">
                    <div>
                        <input type="hidden" :name="'image_types['+index+']'" :value="img.type">
                        <template x-if="img.type === 'url'">
                            <input type="hidden" :name="'image_urls['+index+']'" :value="img.url">
                        </template>
                    </div>
                </template>
                
                <!-- Preview thumbs -->
                <div class="mt-3 flex gap-2 overflow-x-auto pb-2" x-show="images.length > 0 || currentImages.length > 0" x-cloak>
                    <!-- Current Images -->
                    <template x-for="(img, index) in currentImages" :key="'curr-'+img.id">
                        <div class="relative w-16 h-16 shrink-0 rounded-lg border border-slate-200 overflow-hidden bg-white group" x-show="images.length === 0">
                            <img :src="img.url" class="w-full h-full object-contain p-1">
                            <div class="absolute top-1 left-1 bg-slate-500 text-white text-[8px] px-1.5 py-0.5 rounded-full font-bold" x-show="index === 0">Actual</div>
                        </div>
                    </template>
                    <!-- New Images Previews -->
                    <template x-for="(img, index) in images" :key="img.id">
                        <div class="relative w-16 h-16 shrink-0 rounded-lg border border-indigo-200 overflow-hidden bg-white group" x-show="img.preview">
                            <img :src="img.preview" class="w-full h-full object-contain p-1">
                            <div class="absolute top-1 left-1 bg-indigo-600 text-white text-[8px] px-1.5 py-0.5 rounded-full font-bold" x-show="index === 0">Principal</div>
                            <div class="absolute bottom-1 right-1 bg-black/50 text-white text-[7px] px-1 py-0.5 rounded" x-text="img.type === 'url' ? 'URL' : 'PC'"></div>
                            <button type="button" @click.prevent="removeImage(index)" class="absolute top-1 right-1 w-4 h-4 bg-white/80 hover:bg-white text-slate-500 hover:text-red-500 rounded-full flex items-center justify-center shadow-sm opacity-0 group-hover:opacity-100 transition-opacity">
                                <i class="fa-solid fa-xmark text-[8px]"></i>
                            </button>
                        </div>
                    </template>
                    <button type="button" @click="addFileInput()" x-show="images.length > 0 && images.length < 7" class="w-16 h-16 shrink-0 rounded-lg border border-dashed border-slate-300 flex items-center justify-center text-slate-400 hover:text-indigo-600 hover:bg-slate-50 hover:border-indigo-300 transition-colors">
                        <i class="fa-solid fa-plus"></i>
                    </button>
                </div>
            </section>

            <!-- 6. Opciones -->
            <section class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 relative overflow-hidden">
                <div class="absolute top-0 left-0 w-1 h-full bg-amber-500"></div>
                <h3 class="text-sm font-extrabold text-slate-800 mb-4 flex items-center gap-2">
                    <span class="flex items-center justify-center w-5 h-5 rounded-full bg-amber-100 text-amber-700 text-[10px]">6</span> Configuración
                </h3>
                
                <div class="space-y-4">
                    <label class="flex items-center justify-between cursor-pointer group">
                        <div class="flex items-center gap-2 text-sm text-slate-700 group-hover:text-indigo-700 transition-colors">
                            <i class="fa-solid fa-eye text-indigo-400"></i> Producto Activo
                        </div>
                        <div class="relative">
                            <input type="checkbox" name="status" value="1" class="sr-only" x-model="isActive">
                            <div class="block w-10 h-6 rounded-full transition-colors" :class="isActive ? 'bg-indigo-500' : 'bg-slate-200'"></div>
                            <div class="dot absolute left-1 top-1 bg-white w-4 h-4 rounded-full transition-transform" :class="isActive ? 'transform translate-x-4' : ''"></div>
                        </div>
                    </label>

                    <label class="flex items-center justify-between cursor-pointer group">
                        <div class="flex items-center gap-2 text-sm text-slate-700 group-hover:text-indigo-700 transition-colors">
                            <i class="fa-solid fa-certificate text-emerald-400"></i> Etiqueta "Nuevo"
                        </div>
                        <div class="relative">
                            <input type="checkbox" name="is_new" value="1" class="sr-only" x-model="isNew">
                            <div class="block w-10 h-6 rounded-full transition-colors" :class="isNew ? 'bg-emerald-500' : 'bg-slate-200'"></div>
                            <div class="dot absolute left-1 top-1 bg-white w-4 h-4 rounded-full transition-transform" :class="isNew ? 'transform translate-x-4' : ''"></div>
                        </div>
                    </label>

                    <label class="flex items-center justify-between cursor-pointer group">
                        <div class="flex items-center gap-2 text-sm text-slate-700 group-hover:text-indigo-700 transition-colors">
                            <i class="fa-solid fa-tag text-red-400"></i> Oferta Especial
                        </div>
                        <div class="relative">
                            <input type="checkbox" name="is_offer" value="1" class="sr-only" x-model="isOffer">
                            <div class="block w-10 h-6 rounded-full transition-colors" :class="isOffer ? 'bg-red-500' : 'bg-slate-200'"></div>
                            <div class="dot absolute left-1 top-1 bg-white w-4 h-4 rounded-full transition-transform" :class="isOffer ? 'transform translate-x-4' : ''"></div>
                        </div>
                    </label>

                    <label class="flex items-center justify-between cursor-pointer group">
                        <div class="flex items-center gap-2 text-sm text-slate-700 group-hover:text-indigo-700 transition-colors">
                            <i class="fa-solid fa-star text-amber-400"></i> Destacar en Inicio
                        </div>
                        <div class="relative">
                            <input type="checkbox" name="is_featured" value="1" class="sr-only" x-model="isFeatured">
                            <div class="block w-10 h-6 rounded-full transition-colors" :class="isFeatured ? 'bg-amber-500' : 'bg-slate-200'"></div>
                            <div class="dot absolute left-1 top-1 bg-white w-4 h-4 rounded-full transition-transform" :class="isFeatured ? 'transform translate-x-4' : ''"></div>
                        </div>
                    </label>
                </div>
            </section>

            <!-- 7. Resumen Visual -->
            <section class="bg-[#fcfcfa] rounded-2xl shadow-sm border border-slate-100 p-5 relative overflow-hidden">
                <h3 class="text-sm font-extrabold text-slate-800 mb-4 flex items-center gap-2">
                    <span class="flex items-center justify-center w-5 h-5 rounded-full bg-indigo-600 text-white text-[10px]">7</span> Resumen del Producto
                </h3>
                
                <div class="bg-white rounded-xl p-4 shadow-sm border border-slate-50 flex flex-col items-center text-center">
                    <div class="w-40 h-32 mb-3 flex items-center justify-center">
                        <template x-if="window.globalImages && window.globalImages.length > 0 && window.globalImages[0].preview">
                            <img :src="window.globalImages[0].preview" class="max-h-full object-contain">
                        </template>
                        <template x-if="!(window.globalImages && window.globalImages.length > 0 && window.globalImages[0].preview) && window.globalCurrentImages && window.globalCurrentImages.length > 0">
                            <img :src="window.globalCurrentImages[0].url" class="max-h-full object-contain">
                        </template>
                        <template x-if="!(window.globalImages && window.globalImages.length > 0 && window.globalImages[0].preview) && (!window.globalCurrentImages || window.globalCurrentImages.length === 0)">
                            <i class="fa-solid fa-laptop text-5xl text-slate-200"></i>
                        </template>
                    </div>
                    
                    <div class="text-sm font-bold text-slate-800 leading-tight mt-1 line-clamp-2" x-text="name || 'Nombre del producto'"></div>
                    <div class="text-[10px] text-slate-500 mt-1" x-text="getSpecsSummary()"></div>
                    
                    <div class="flex gap-2 justify-center mt-3">
                        <span x-show="isActive" class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-green-200 text-green-800">Activo</span>
                        <span x-show="isNew" class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-100 text-blue-700">Nuevo</span>
                    </div>
                    
                    <div class="mt-4 font-display text-xl font-black text-indigo-700">
                        S/ <span x-text="offerPrice ? offerPrice : (price ? price : '0.00')"></span>
                    </div>
                </div>

                <button type="button" class="mt-3 w-full py-2.5 bg-indigo-700 hover:bg-indigo-800 text-white text-xs font-bold rounded-xl flex items-center justify-center gap-2 transition-colors shadow-lg shadow-indigo-200/50">
                    <i class="fa-solid fa-eye"></i> Vista previa en catálogo
                </button>
            </section>

            <!-- Consejos -->
            <section class="bg-slate-50/80 rounded-2xl border border-slate-100 p-5">
                <h3 class="text-xs font-bold text-indigo-600 mb-3 flex items-center gap-2">
                    <i class="fa-regular fa-lightbulb text-sm"></i> Consejos
                </h3>
                <ul class="text-[10px] text-slate-600 space-y-2">
                    <li class="flex items-center gap-2"><i class="fa-solid fa-circle-check text-emerald-500"></i> Completa la información principal del producto.</li>
                    <li class="flex items-center gap-2"><i class="fa-solid fa-circle-check text-emerald-500"></i> Revisa que las nuevas imágenes tengan buena resolución.</li>
                    <li class="flex items-center gap-2"><i class="fa-solid fa-circle-check text-emerald-500"></i> Las especificaciones técnicas mejoran la búsqueda.</li>
                    <li class="flex items-center gap-2"><i class="fa-solid fa-circle-check text-emerald-500"></i> Verifica siempre el precio actual y en oferta.</li>
                </ul>
            </section>

        </div>
    </div>

    <!-- Actions (Desktop under left col, Mobile bottom) -->
    <div class="mt-8 flex flex-col-reverse sm:flex-row justify-between items-center gap-4">
        <a href="{{ route('admin.products.index') }}" class="w-full sm:w-auto px-6 py-3 text-sm font-bold text-slate-600 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 transition-colors text-center">
            Cancelar
        </a>
        <button type="submit" class="w-full sm:w-auto px-8 py-3 text-sm font-bold text-white bg-[#3e06cf] rounded-xl shadow-lg shadow-indigo-200 hover:bg-indigo-800 transition-colors flex items-center justify-center gap-2">
            <i class="fa-solid fa-save"></i> Guardar Cambios
        </button>
    </div>
</form>

@endsection

@push('scripts')
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('productForm', () => ({
            name: `{!! addslashes($product->name) !!}`,
            brandName: `{!! $product->brand ? addslashes($product->brand->name) : '' !!}`,
            categoryName: `{!! $product->category ? addslashes($product->category->name) : '' !!}`,
            price: `{{ $product->price }}`,
            offerPrice: `{{ $product->offer_price }}`,
            isActive: {{ $product->status ? 'true' : 'false' }},
            isNew: {{ $product->is_new ? 'true' : 'false' }},
            isOffer: {{ $product->is_offer ? 'true' : 'false' }},
            isFeatured: {{ $product->is_featured ? 'true' : 'false' }},
            specs: @json($formattedSpecs),

            updateBrandName(e) {
                this.brandName = e.target.options[e.target.selectedIndex].dataset.name || '';
            },
            updateCategoryName(e) {
                this.categoryName = e.target.options[e.target.selectedIndex].dataset.name || '';
            },
            addSpec() {
                this.specs.push({ key: '', value: '' });
            },
            removeSpec(index) {
                this.specs.splice(index, 1);
            },
            getSpecsSummary() {
                return this.specs.filter(s => s.value).slice(0, 3).map(s => s.value).join(' | ');
            }
        }));

        Alpine.data('imageUploadManager', () => ({
            images: [],
            tempUrl: '',
            currentImages: @json(array_map(function($img) {
                return [
                    'id' => $img['id'],
                    'url' => filter_var($img['image_path'], FILTER_VALIDATE_URL) ? $img['image_path'] : asset('storage/' . $img['image_path'])
                ];
            }, clone $existingImages)),
            
            init() {
                window.globalImages = this.images;
                window.globalCurrentImages = this.currentImages;
                this.$watch('images', val => window.globalImages = val);
            },
            addUrlInput() {
                if (!this.tempUrl) return;
                if (this.images.length >= 7) {
                    alert('Máximo 7 imágenes permitidas');
                    return;
                }
                this.images.push({
                    id: Date.now() + Math.random().toString(36).substring(7),
                    type: 'url',
                    url: this.tempUrl,
                    preview: this.tempUrl
                });
                this.tempUrl = '';
            },
            addFileInput() {
                if (this.images.length >= 7) {
                    alert('Máximo 7 imágenes permitidas');
                    return;
                }
                const newId = Date.now() + Math.random().toString(36).substring(7);
                this.images.push({
                    id: newId,
                    type: 'file',
                    url: '',
                    preview: ''
                });
                this.$nextTick(() => {
                    const input = document.getElementById('file_input_' + newId);
                    if (input) input.click();
                });
            },
            handleFileChange(event, img) {
                const file = event.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        img.preview = e.target.result;
                    };
                    reader.readAsDataURL(file);
                } else {
                    this.images = this.images.filter(i => i.id !== img.id);
                }
            },
            removeImage(index) {
                this.images.splice(index, 1);
            }
        }));
    });

    // Scanner
    let html5QrcodeScanner = null;
    function startScanner() {
        document.getElementById('scanner-modal').classList.remove('hidden');
        if (!html5QrcodeScanner) {
            html5QrcodeScanner = new Html5QrcodeScanner("reader", { fps: 10, qrbox: {width: 250, height: 150} }, false);
            html5QrcodeScanner.render(onScanSuccess, () => {});
        }
    }
    function stopScanner() {
        document.getElementById('scanner-modal').classList.add('hidden');
        if (html5QrcodeScanner) {
            html5QrcodeScanner.clear();
            html5QrcodeScanner = null;
        }
    }
    function onScanSuccess(decodedText) {
        document.getElementById('product_code').value = decodedText;
        stopScanner();
    }
</script>

<!-- Scanner Modal -->
<div id="scanner-modal" class="fixed inset-0 z-[100] hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity bg-slate-900 bg-opacity-75 backdrop-blur-sm" onclick="stopScanner()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
        <div class="inline-block px-4 pt-5 pb-4 overflow-hidden text-left align-bottom transition-all transform bg-white rounded-2xl shadow-2xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
            <div class="mt-3 text-center sm:mt-5">
                <h3 class="text-lg font-bold leading-6 text-slate-800 mb-4">Escanear Código</h3>
                <div id="reader" class="mx-auto overflow-hidden rounded-xl border-2 border-indigo-100"></div>
            </div>
            <div class="mt-6">
                <button type="button" onclick="stopScanner()" class="w-full px-4 py-2 text-sm font-bold text-white bg-red-500 rounded-xl hover:bg-red-600 transition-colors">
                    Cancelar
                </button>
            </div>
        </div>
    </div>
</div>
@endpush
