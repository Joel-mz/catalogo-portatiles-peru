@extends('layouts.admin')
@section('header_title', 'Editar Producto')
@section('content')

@php
    if (!isset($formattedSpecs) || empty($formattedSpecs)) {
        $formattedSpecs = [];
        if (is_array($product->technical_specs)) {
            foreach ($product->technical_specs as $key => $value) {
                if (is_array($value) && isset($value['name'])) {
                    $formattedSpecs[] = [
                        'name' => (string)$value['name'],
                        'value' => (string)($value['value'] ?? '')
                    ];
                } else {
                    $formattedSpecs[] = [
                        'name' => (string)$key,
                        'value' => (string)$value
                    ];
                }
            }
        }
        if (empty($formattedSpecs)) {
            $formattedSpecs = [
                ['name' => 'Procesador', 'value' => ''],
                ['name' => 'Memoria RAM', 'value' => ''],
                ['name' => 'Almacenamiento', 'value' => ''],
                ['name' => 'Pantalla', 'value' => '']
            ];
        }
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

<div x-data="productForm()">
<form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data" 
      class="pb-24">
    @csrf
    @method('PUT')

    @if ($errors->any())
    <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <i class="fa-solid fa-circle-exclamation text-red-500 mt-0.5"></i>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-bold text-red-800">Se encontraron errores en el formulario:</h3>
                <div class="mt-2 text-xs text-red-700">
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
    @endif

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
                        <select name="category_id" x-model="categoryId" @change="updateCategoryName($event)" class="w-full px-3 py-2 bg-slate-50 border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors" required>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" data-name="{{ $cat->name }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Subcategoría (Opcional)</label>
                        <select name="subcategory_id" class="w-full px-3 py-2 bg-slate-50 border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                            <option value="">Ninguna...</option>
                            @foreach($subcategories as $subcat)
                                <option value="{{ $subcat->id }}" {{ old('subcategory_id', $product->subcategory_id) == $subcat->id ? 'selected' : '' }}>{{ $subcat->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Marca <span class="text-red-500">*</span></label>
                        <select name="brand_id" x-model="brandId" @change="updateBrandName($event)" class="w-full px-3 py-2 bg-slate-50 border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors" required>
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
                        <div class="flex items-center justify-between mb-1">
                            <label class="block text-xs font-bold text-slate-700">Descripción del Producto <span class="text-red-500">*</span></label>
                            <div class="flex items-center gap-2">
                                <span class="text-[10px] text-slate-400 font-mono" x-text="(description ? description.length : 0) + ' caracteres'"></span>
                                <button type="button" @click="openAiModal('description')" class="px-2.5 py-1 bg-purple-100 hover:bg-purple-200 text-purple-700 text-[10px] font-bold rounded-lg transition-colors flex items-center gap-1.5 shadow-xs">
                                    <i class="fa-solid fa-wand-magic-sparkles text-purple-600"></i> Generar con IA
                                </button>
                            </div>
                        </div>
                        <div class="relative">
                            <textarea name="description" x-model="description" rows="4" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-colors resize-y leading-relaxed" placeholder="Ingresa o pega la descripción completa y detallada del producto sin límite..." required></textarea>
                        </div>
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
                            <option value="Por Cantidad" {{ old('control_type', $product->control_type) == 'Por Cantidad' || old('control_type', $product->control_type) == 'quantity' ? 'selected' : '' }}>Por Cantidad</option>
                            <option value="Por Serie" {{ old('control_type', $product->control_type) == 'Por Serie' || old('control_type', $product->control_type) == 'serial' ? 'selected' : '' }}>Por Serie (Unitario)</option>
                            <option value="Por Lote" {{ old('control_type', $product->control_type) == 'Por Lote' || old('control_type', $product->control_type) == 'lot' ? 'selected' : '' }}>Por Lote</option>
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
                        <label class="block text-xs font-bold text-slate-700 mb-1">Precio Mínimo <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-500 font-bold">S/</span>
                            <input type="number" step="0.01" name="min_price" value="{{ old('min_price', $product->min_price ?? $product->price) }}" class="w-full pl-9 pr-3 py-2 bg-slate-50 border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" required>
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
                        <label class="block text-xs font-bold text-slate-700 mb-1">Estado del Producto <span class="text-red-500">*</span></label>
                        <select name="state" class="w-full px-3 py-2 bg-slate-50 border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" required>
                            <option value="Nuevo" {{ old('state', $product->state) == 'Nuevo' ? 'selected' : '' }}>Nuevo</option>
                            <option value="Seminuevo" {{ old('state', $product->state) == 'Seminuevo' ? 'selected' : '' }}>Seminuevo</option>
                            <option value="Open Box" {{ old('state', $product->state) == 'Open Box' ? 'selected' : '' }}>Open Box</option>
                            <option value="Reacondicionado" {{ old('state', $product->state) == 'Reacondicionado' ? 'selected' : '' }}>Reacondicionado</option>
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-slate-700 mb-1">Garantía</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400"><i class="fa-solid fa-shield-halved"></i></span>
                            <input type="text" name="warranty" value="{{ old('warranty', $product->warranty) }}" class="w-full pl-9 pr-3 py-2 bg-slate-50 border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Ej. 1 año de garantía directa">
                        </div>
                    </div>
                </div>
            </section>

            <!-- 4. Especificaciones Técnicas -->
            <section class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 relative overflow-hidden">
                <div class="absolute top-0 left-0 w-1 h-full bg-violet-500"></div>
                <div class="flex items-center justify-between mb-5">
                    <h3 class="text-base font-extrabold text-slate-800 flex items-center gap-2">
                        <span class="flex items-center justify-center w-6 h-6 rounded-full bg-violet-100 text-violet-700 text-xs">4</span> 
                        Especificaciones Técnicas
                    </h3>
                    <button type="button" @click="openAiModal('specs')" class="px-3.5 py-1.5 bg-purple-600 text-white rounded-xl text-xs font-bold hover:bg-purple-700 transition-colors flex items-center gap-1.5 shadow-md shadow-purple-600/25">
                        <i class="fa-solid fa-wand-magic-sparkles text-[11px]"></i> Llenar con IA
                    </button>
                </div>

                <div class="flex gap-6 flex-col md:flex-row">
                    <div class="flex-1 space-y-3">
                        <template x-for="(spec, index) in specs" :key="index">
                            <div class="flex items-center gap-2">
                                <div class="relative w-1/3">
                                    <span class="absolute inset-y-0 left-0 flex items-center pl-2.5 text-slate-400 text-xs"><i class="fa-solid fa-grip-vertical"></i></span>
                                    <input type="text" list="commonSpecsEdit" :name="'specs['+index+'][name]'" x-model="spec.name" :placeholder="getSpecPlaceholder(spec.name, 'name')" class="w-full pl-7 pr-2 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 focus:ring-2 focus:ring-indigo-500 focus:bg-white transition">
                                </div>
                                <div class="relative flex-1">
                                    <input type="text" :name="'specs['+index+'][value]'" x-model="spec.value" :placeholder="getSpecPlaceholder(spec.name, 'value')" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-700 focus:ring-2 focus:ring-indigo-500 focus:bg-white transition">
                                </div>
                                <button type="button" @click="removeSpec(index)" class="w-8 h-8 flex items-center justify-center text-red-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors flex-shrink-0" title="Eliminar fila">
                                    <i class="fa-solid fa-trash-can text-xs"></i>
                                </button>
                            </div>
                        </template>

                        <datalist id="commonSpecsEdit">
                            <option value="Procesador">
                            <option value="Memoria RAM">
                            <option value="Almacenamiento">
                            <option value="Pantalla">
                            <option value="Gráficos">
                            <option value="Sistema Operativo">
                            <option value="Resolución">
                            <option value="Visión Nocturna">
                            <option value="Ángulo de Cobertura">
                            <option value="Conectividad">
                            <option value="Detección Inteligente">
                            <option value="Audio">
                            <option value="Protección">
                            <option value="Tipo de Impresión">
                            <option value="Funciones">
                            <option value="Resolución de Impresión">
                            <option value="Velocidad de Impresión">
                            <option value="Capacidad de Bandeja">
                            <option value="Batería">
                        </datalist>
                        
                        <div class="mt-4 flex flex-wrap items-center gap-2">
                            <button type="button" @click="addSpec()" class="px-3.5 py-2 bg-indigo-50 text-indigo-700 rounded-xl text-xs font-bold hover:bg-indigo-100 transition-colors flex items-center gap-1.5">
                                <i class="fa-solid fa-plus text-[10px]"></i> Agregar especificación
                            </button>
                            <button type="button" @click="openAiModal('specs')" class="px-3.5 py-2 bg-purple-600 text-white rounded-xl text-xs font-bold hover:bg-purple-700 transition-colors flex items-center gap-1.5 shadow-md shadow-purple-600/25">
                                <i class="fa-solid fa-wand-magic-sparkles text-[10px]"></i> Llenar con IA
                            </button>
                        </div>
                    </div>

                    <div class="md:w-72">
                        <div class="bg-indigo-50/60 rounded-2xl p-4 border border-indigo-100/80">
                            <h4 class="text-xs font-bold text-indigo-900 flex items-center gap-1.5 mb-2.5">
                                <i class="fa-regular fa-lightbulb text-indigo-600"></i> Ejemplos sugeridos
                            </h4>
                            <ul class="text-[11px] text-slate-600 space-y-1.5 leading-relaxed">
                                <li><strong class="text-slate-800">Procesador:</strong> Intel Core i7 / Ryzen 7</li>
                                <li><strong class="text-slate-800">Memoria RAM:</strong> 16GB DDR5 5200MHz</li>
                                <li><strong class="text-slate-800">Almacenamiento:</strong> 512GB SSD NVMe</li>
                                <li><strong class="text-slate-800">Pantalla:</strong> 15.6" FHD 144Hz IPS</li>
                                <li><strong class="text-slate-800">Gráficos:</strong> NVIDIA RTX 4060 8GB</li>
                                <li><strong class="text-slate-800">Cámaras:</strong> Resolución 2K, Visión Nocturna</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <!-- Right Column -->
        <div class="lg:col-span-4 space-y-6">
            
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

                <a href="{{ route('product.show', $product->slug) }}" target="_blank" class="mt-3 w-full py-2.5 bg-indigo-700 hover:bg-indigo-800 text-white text-xs font-bold rounded-xl flex items-center justify-center gap-2 transition-colors shadow-lg shadow-indigo-200/50">
                    <i class="fa-solid fa-eye"></i> Ver en catálogo público
                </a>
            </section>

            <!-- Consejos -->
            <section class="bg-slate-50/80 rounded-2xl border border-slate-100 p-5">
                <h3 class="text-xs font-bold text-indigo-600 mb-3 flex items-center gap-2">
                    <i class="fa-regular fa-lightbulb text-sm"></i> Consejos
                </h3>
                <ul class="text-[10px] text-slate-600 space-y-2">
                    <li class="flex items-center gap-2"><i class="fa-solid fa-circle-check text-emerald-500"></i> Usa "Llenar con IA" para generar rápidamente especificaciones o descripciones.</li>
                    <li class="flex items-center gap-2"><i class="fa-solid fa-circle-check text-emerald-500"></i> Revisa que las nuevas imágenes tengan buena resolución.</li>
                    <li class="flex items-center gap-2"><i class="fa-solid fa-circle-check text-emerald-500"></i> Las especificaciones técnicas mejoran la búsqueda en el catálogo.</li>
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

<!-- AI Generation Modal (Inside Alpine scope, teleported to body) -->
<template x-teleport="body">
    <div x-show="aiModalOpen" 
         x-cloak 
         class="fixed inset-0 z-[250] flex items-center justify-center bg-slate-950/75 backdrop-blur-sm p-4 overflow-y-auto"
         @keydown.window.escape="aiModalOpen = false">
        <div @click.away="aiModalOpen = false" class="bg-white rounded-3xl shadow-2xl w-full max-w-xl p-6 border border-slate-100 relative my-auto">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-base font-extrabold text-slate-900 flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center shadow-sm">
                        <i class="fa-solid fa-wand-magic-sparkles text-sm"></i>
                    </div>
                    <span x-text="aiTargetField === 'specs' ? 'Autocompletar / Pegar Ficha Técnica con IA' : 'Generar Descripción con IA'"></span>
                </h3>
                <button type="button" @click="aiModalOpen = false" class="w-8 h-8 rounded-full hover:bg-slate-100 text-slate-400 hover:text-slate-600 flex items-center justify-center transition">
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>
            </div>
            
            <p class="text-xs text-slate-500 mb-3 leading-relaxed">
                <span class="font-semibold text-purple-700">💡 Pega directamente la ficha técnica copiada</span> (de Excel, PDF o página web) o escribe el nombre del producto para que la IA extraiga y organice todas las filas exactas.
            </p>

            <!-- Category Presets / Tags -->
            <div class="mb-3 flex flex-wrap gap-1.5 items-center">
                <span class="text-[10px] font-bold text-slate-400 self-center mr-1">Atajos:</span>
                <button type="button" @click="setAiPreset('Laptop Gamer')" class="px-2 py-1 bg-slate-100 hover:bg-purple-100 hover:text-purple-700 text-slate-600 rounded-lg text-[10px] font-semibold transition">💻 Laptop Gamer</button>
                <button type="button" @click="setAiPreset('Laptop Oficina')" class="px-2 py-1 bg-slate-100 hover:bg-purple-100 hover:text-purple-700 text-slate-600 rounded-lg text-[10px] font-semibold transition">💼 Laptop Oficina</button>
                <button type="button" @click="setAiPreset('Cámara de Seguridad')" class="px-2 py-1 bg-slate-100 hover:bg-purple-100 hover:text-purple-700 text-slate-600 rounded-lg text-[10px] font-semibold transition">📷 Cámara</button>
                <button type="button" @click="setAiPreset('Impresora EcoTank')" class="px-2 py-1 bg-slate-100 hover:bg-purple-100 hover:text-purple-700 text-slate-600 rounded-lg text-[10px] font-semibold transition">🖨️ Impresora</button>
                <button type="button" @click="setAiPreset('Monitor Gaming')" class="px-2 py-1 bg-slate-100 hover:bg-purple-100 hover:text-purple-700 text-slate-600 rounded-lg text-[10px] font-semibold transition">🖥️ Monitor</button>
                <button type="button" @click="aiPrompt = ''" class="ml-auto text-[10px] font-bold text-red-500 hover:underline">Limpiar texto</button>
            </div>

            <label class="block text-xs font-bold text-slate-700 mb-1">Pega aquí el texto, tabla de especificaciones o nombre:</label>
            <textarea x-model="aiPrompt" rows="6" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-xs text-slate-800 focus:ring-2 focus:ring-purple-500 focus:bg-white transition-colors mb-4 resize-y font-mono" placeholder="Pega aquí la tabla copiada de especificaciones (ej: Sensor de imagen: CMOS..., Resolución: 2880*1620, Alarma inteligente: Detección IA...) o el nombre del equipo..."></textarea>
            
            <div class="flex items-center justify-between">
                <button type="button" @click="aiModalOpen = false" class="px-4 py-2 text-xs text-slate-600 hover:bg-slate-100 rounded-xl font-bold transition">
                    Cancelar
                </button>
                <button type="button" @click="processAiGeneration()" :disabled="isAiLoading" class="px-5 py-2.5 text-xs bg-purple-600 hover:bg-purple-700 text-white rounded-xl font-bold shadow-md shadow-purple-600/30 flex items-center gap-2 transition cursor-pointer">
                    <i class="fa-solid fa-bolt" x-show="!isAiLoading"></i>
                    <i class="fa-solid fa-spinner fa-spin" x-show="isAiLoading"></i>
                    <span x-text="isAiLoading ? 'Procesando...' : (aiTargetField === 'specs' ? 'Procesar y Llenar Especificaciones' : 'Generar Descripción Comercial')"></span>
                </button>
            </div>
        </div>
    </div>
</template>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script>
    // Exact Parser for pasted datasheets / tables / text
    function parseRawSpecs(text) {
        if (!text || !text.trim()) return [];

        const lines = text.split(/\r?\n/).map(l => l.trim()).filter(l => l.length > 0);
        const results = [];
        const sectionHeaders = [
            'especificaciones', 'especificacion', 'especificaciones tecnicas', 'especificaciones técnicas',
            'cámara', 'camara', 'vídeo y audio', 'video y audio', 'video', 'vídeo', 'audio',
            'red', 'redes', 'conectividad', 'funciones', 'funciones principales', 'almacenamiento',
            'general', 'generales', 'contenido de la caja', 'certificados', 'certificaciones',
            'pantalla', 'procesador', 'memoria', 'dimensiones', 'peso', 'puertos', 'caracteristicas',
            'características', 'datos tecnicos', 'datos técnicos', 'hardware', 'software', 'óptica', 'optica'
        ];

        for (let rawLine of lines) {
            let line = rawLine.replace(/^[\*\-\•\–\—\>\#\·\+]\s*/, '').trim();
            if (!line) continue;

            let key = '';
            let val = '';

            if (line.includes('\t')) {
                const parts = line.split('\t').map(p => p.trim()).filter(p => p.length > 0);
                if (parts.length >= 2) {
                    key = parts[0];
                    val = parts.slice(1).join(' - ');
                }
            } else if (line.includes(':') && !line.startsWith('http://') && !line.startsWith('https://')) {
                const colonIdx = line.indexOf(':');
                key = line.substring(0, colonIdx).trim();
                val = line.substring(colonIdx + 1).trim();
            } else if (line.includes(' | ')) {
                const parts = line.split(' | ');
                key = parts[0].trim();
                val = parts.slice(1).join(' | ').trim();
            } else if (line.includes(' - ') && !line.startsWith('-')) {
                const parts = line.split(' - ');
                key = parts[0].trim();
                val = parts.slice(1).join(' - ').trim();
            } else if (line.includes(' = ')) {
                const parts = line.split(' = ');
                key = parts[0].trim();
                val = parts.slice(1).join(' = ').trim();
            }

            if (key && val) {
                key = key.replace(/[\:\-\=\|]+$/, '').trim();
                val = val.replace(/^[\:\-\=\|\s]+/, '').trim();
                if (key.length > 0 && val.length > 0 && key.length < 90) {
                    if (key.toLowerCase() !== val.toLowerCase() && !sectionHeaders.includes(key.toLowerCase() + ':' + val.toLowerCase())) {
                        results.push({ name: key, value: val });
                    }
                }
            }
        }

        // Fallback: If no delimited pairs found or fewer than 2, check alternating lines
        if (results.length < 2 && lines.length >= 4) {
            const altResults = [];
            for (let i = 0; i < lines.length - 1; i += 2) {
                const k = lines[i].replace(/^[\*\-\•\–\—\>\#\·\+]\s*/, '').replace(/[\:\-\=]+$/, '').trim();
                const v = lines[i+1].trim();
                if (k && v && k.length < 70 && !sectionHeaders.includes(k.toLowerCase()) && k.toLowerCase() !== v.toLowerCase()) {
                    altResults.push({ name: k, value: v });
                }
            }
            if (altResults.length >= 2) {
                return altResults;
            }
        }

        return results;
    }

    // Smart AI Generator Helper
    function runSmartAiEngine(promptText, targetField, categoryHint = '', brandHint = '') {
        const raw = (promptText || '').trim();
        const parsedSpecs = parseRawSpecs(raw);

        // 1. If user pasted actual specs and wants SPECS: USE THE EXACT PARSED SPECS!
        if (targetField === 'specs') {
            if (parsedSpecs.length > 0) {
                return parsedSpecs;
            }
        }

        // 2. If user pasted specs and wants a DESCRIPTION: synthesize from their exact data!
        if (targetField === 'description' && parsedSpecs.length > 0) {
            const findSpec = (keywords) => {
                for (let spec of parsedSpecs) {
                    const k = spec.name.toLowerCase();
                    if (keywords.some(kw => k.includes(kw))) {
                        return spec.value;
                    }
                }
                return null;
            };

            const modelVal = findSpec(['modelo', 'model', 'nombre']);
            const resVal = findSpec(['resolución', 'resolucion', 'resolution']);
            const sensorVal = findSpec(['sensor', 'lente', 'procesador', 'cpu']);
            const nightVal = findSpec(['nocturn', 'visión', 'vision', 'pantalla']);
            const storageVal = findSpec(['almacenamiento', 'disco', 'ssd', 'microsd', 'ram']);
            const featuresVal = findSpec(['alarma', 'detección', 'deteccion', 'funciones', 'conectividad']);

            let parts = [];
            if (modelVal) parts.push(modelVal);
            if (resVal) parts.push(`con resolución ${resVal}`);
            if (sensorVal) parts.push(`${sensorVal}`);
            if (nightVal) parts.push(`visión ${nightVal}`);
            if (featuresVal) parts.push(`${featuresVal}`);
            if (storageVal) parts.push(`soporte ${storageVal}`);

            let desc = parts.length > 0 
                ? parts.join(', ') + '.'
                : `${raw}`;
            
            return desc;
        }

        // 3. Fallback: Intelligent keyword-based engine
        const lower = (raw + ' ' + categoryHint + ' ' + brandHint).toLowerCase();

        const isCamera = lower.includes('camara') || lower.includes('cámara') || lower.includes('ezviz') || lower.includes('imou') || lower.includes('seguridad') || lower.includes('dahua') || lower.includes('hikvision') || lower.includes('tapo') || lower.includes('cctv') || lower.includes('domo') || lower.includes('bullet') || lower.includes('vigilancia');
        const isPrinter = lower.includes('impresora') || lower.includes('multifuncional') || lower.includes('ecotank') || lower.includes('megatank') || lower.includes('smart tank') || lower.includes('epson') || lower.includes('canon') || lower.includes('brother') || lower.includes('laserjet') || lower.includes('láser') || lower.includes('laser') || lower.includes('tinta');
        const isMonitor = lower.includes('monitor') || lower.includes('pantalla') || lower.includes('display') || lower.includes('curvo') || lower.includes('gaming monitor') || lower.includes('curved');
        const isPhoneOrTablet = lower.includes('celular') || lower.includes('smartphone') || lower.includes('telefono') || lower.includes('teléfono') || lower.includes('galaxy') || lower.includes('iphone') || lower.includes('redmi') || lower.includes('xiaomi') || lower.includes('poco') || lower.includes('tablet') || lower.includes('ipad') || lower.includes('tab');
        const isStorageOrComponent = lower.includes('disco') || lower.includes('ssd') || lower.includes('nvme') || lower.includes('memoria ram') || lower.includes('ddr4') || lower.includes('ddr5') || lower.includes('fuente de poder') || lower.includes('placa madre') || lower.includes('motherboard') || lower.includes('kingston') || lower.includes('crucial') || lower.includes('western digital');
        const isAccessory = lower.includes('teclado') || lower.includes('mouse') || lower.includes('audifono') || lower.includes('audífono') || lower.includes('headset') || lower.includes('auricular') || lower.includes('auriculares') || lower.includes('parlante') || lower.includes('altavoz') || lower.includes('silla') || lower.includes('mochila') || lower.includes('funda') || lower.includes('cooler') || lower.includes('webcam') || lower.includes('camara web') || lower.includes('cámara web');

        if (targetField === 'description') {
            if (isCamera) {
                return `${raw || 'Cámara de Seguridad Inteligente'} con visión nocturna a color, detección IA de movimiento humano y vehículos, audio bidireccional y alta resistencia climática IP66.`;
            }
            if (isPrinter) {
                return `${raw || 'Impresora Multifuncional'} de alto rendimiento con sistema continuo de tinta original, conectividad inalámbrica Wi-Fi y ultrabajo costo de impresión por página.`;
            }
            if (isMonitor) {
                return `${raw || 'Monitor Profesional'} con alta tasa de refresco, colores vibrantes de amplio ángulo de visión y tecnología de protección ocular para gaming y oficina.`;
            }
            if (isPhoneOrTablet) {
                return `${raw || 'Dispositivo Inteligente'} con pantalla ultranítida de 120Hz, potente procesador para multitarea fluida, cámaras avanzadas y batería de larga duración.`;
            }
            if (isStorageOrComponent) {
                return `${raw || 'Componente de Alto Rendimiento'} con máxima velocidad de transferencia, estabilidad y durabilidad para repotenciar tu equipo al instante.`;
            }
            if (isAccessory) {
                return `${raw || 'Accesorio Premium'} con diseño ergonómico de alta durabilidad, respuesta inmediata y conectividad versátil para trabajo o gaming.`;
            }
            return `${raw || 'Laptop'} de alto rendimiento ideal para trabajo profesional, multitarea exigente y estudio, con tecnología de última generación y gran autonomía.`;
        }

        if (targetField === 'specs') {
            if (isCamera) {
                let resol = 'Full HD 1080p (2MP)';
                if (lower.includes('4k') || lower.includes('8mp')) resol = '4K Ultra HD (8 Megapíxeles)';
                else if (lower.includes('3k') || lower.includes('5mp')) resol = '3K (5 Megapíxeles)';
                else if (lower.includes('2k') || lower.includes('4mp') || lower.includes('3mp')) resol = '2K (4 Megapíxeles)';

                return [
                    { name: 'Resolución', value: resol },
                    { name: 'Visión Nocturna', value: 'A color inteligente con focos LED e infrarrojo' },
                    { name: 'Ángulo de Cobertura', value: 'Panorámica 360° motorizada con autoseguimiento' },
                    { name: 'Conectividad', value: 'Wi-Fi 2.4 GHz y puerto Ethernet RJ45' },
                    { name: 'Almacenamiento', value: 'Ranura MicroSD hasta 512GB y Nube' },
                    { name: 'Detección Inteligente', value: 'IA avanzada para personas y vehículos' },
                    { name: 'Audio', value: 'Bidireccional (Micrófono y altavoz integrados)' },
                    { name: 'Protección', value: 'IP66 resistente a intemperie, lluvia y polvo' }
                ];
            }

            if (isPrinter) {
                return [
                    { name: 'Tipo de Impresión', value: lower.includes('laser') || lower.includes('láser') ? 'Láser monocromática / color de alta velocidad' : 'Inyección de tinta continua original EcoTank' },
                    { name: 'Funciones', value: 'Imprime, Copia, Escanea' },
                    { name: 'Conectividad', value: 'Wi-Fi Direct, USB 2.0 de alta velocidad' },
                    { name: 'Resolución de Impresión', value: 'Hasta 5760 x 1440 dpi de alta definición' },
                    { name: 'Velocidad de Impresión', value: 'Hasta 33 ppm en negro y 15 ppm a color' },
                    { name: 'Capacidad de Bandeja', value: '100 hojas de papel común / 20 fotográficas' },
                    { name: 'Rendimiento', value: 'Hasta 4,500 páginas negro / 7,500 páginas color' }
                ];
            }

            if (isMonitor) {
                let size = '27 pulgadas';
                if (lower.includes('24') || lower.includes('23.8')) size = '24 pulgadas (23.8")';
                else if (lower.includes('32')) size = '32 pulgadas';
                else if (lower.includes('34')) size = '34 pulgadas UltraWide';

                let res = 'Full HD (1920x1080)';
                if (lower.includes('4k')) res = '4K UHD (3840x2160)';
                else if (lower.includes('2k') || lower.includes('qhd') || lower.includes('1440p')) res = '2K QHD (2560x1440)';

                let hz = '100Hz';
                if (lower.includes('240hz')) hz = '240Hz ultra fluido';
                else if (lower.includes('180hz')) hz = '180Hz gaming';
                else if (lower.includes('165hz')) hz = '165Hz gaming';
                else if (lower.includes('144hz')) hz = '144Hz gaming';
                else if (lower.includes('75hz')) hz = '75Hz';

                return [
                    { name: 'Tamaño de Pantalla', value: size },
                    { name: 'Resolución', value: res },
                    { name: 'Tasa de Refresco', value: hz },
                    { name: 'Tipo de Panel', value: lower.includes('curvo') || lower.includes('curved') ? 'VA Curvo 1500R' : 'IPS con 178° de visión' },
                    { name: 'Tiempo de Respuesta', value: '1ms (MPRT / GTG)' },
                    { name: 'Conectividad', value: 'HDMI 2.0, DisplayPort 1.4, Audio Jack' },
                    { name: 'Tecnologías', value: 'AMD FreeSync Premium, HDR10, Low Blue Light' }
                ];
            }

            if (isPhoneOrTablet) {
                let ram = '8GB RAM (+ expansión virtual)';
                if (lower.includes('12gb')) ram = '12GB RAM LPDDR5';
                else if (lower.includes('6gb')) ram = '6GB RAM';
                else if (lower.includes('4gb')) ram = '4GB RAM';

                let storage = '256GB UFS de alta velocidad';
                if (lower.includes('512gb')) storage = '512GB UFS de alta velocidad';
                else if (lower.includes('128gb')) storage = '128GB UFS';
                else if (lower.includes('1tb')) storage = '1TB Almacenamiento';

                return [
                    { name: 'Pantalla', value: 'AMOLED 6.67" FHD+ con tasa de refresco a 120Hz' },
                    { name: 'Memoria RAM', value: ram },
                    { name: 'Almacenamiento', value: storage },
                    { name: 'Cámara Principal', value: '50 MP con OIS y Modo Noche' },
                    { name: 'Cámara Frontal', value: '16 MP con HDR y modo retrato' },
                    { name: 'Batería', value: '5000 mAh con Carga Rápida 67W' },
                    { name: 'Conectividad', value: '5G, Wi-Fi 6, Bluetooth 5.3, NFC' }
                ];
            }

            if (isStorageOrComponent) {
                let cap = '512GB';
                if (lower.includes('1tb')) cap = '1TB (1000GB)';
                else if (lower.includes('2tb')) cap = '2TB (2000GB)';
                else if (lower.includes('256gb')) cap = '256GB';
                else if (lower.includes('16gb')) cap = '16GB';
                else if (lower.includes('32gb')) cap = '32GB';

                return [
                    { name: 'Capacidad', value: cap },
                    { name: 'Factor de Forma / Tipo', value: lower.includes('nvme') || lower.includes('m.2') ? 'M.2 2280 NVMe PCIe 4.0' : (lower.includes('ddr5') ? 'DDR5 5600MHz' : (lower.includes('ddr4') ? 'DDR4 3200MHz' : 'SATA III 2.5"')) },
                    { name: 'Velocidad de Lectura', value: 'Hasta 3500 MB/s / 5000 MB/s' },
                    { name: 'Velocidad de Escritura', value: 'Hasta 3000 MB/s / 4500 MB/s' },
                    { name: 'Compatibilidad', value: 'PC de escritorio, Laptops y Consolas' },
                    { name: 'Garantía / Durabilidad', value: 'Alta resistencia TBW con disipación térmica' }
                ];
            }

            if (isAccessory) {
                return [
                    { name: 'Tipo de Conexión', value: lower.includes('inalambrico') || lower.includes('wireless') || lower.includes('bluetooth') ? 'Inalámbrico 2.4GHz + Bluetooth 5.3' : 'Cable USB trenzado de alta resistencia' },
                    { name: 'Sensor / Switches', value: lower.includes('teclado') ? 'Switches mecánicos táctiles de larga duración' : (lower.includes('mouse') ? 'Sensor óptico de alta precisión hasta 12,000 DPI' : 'Drivers de audio de 50mm con sonido envolvente') },
                    { name: 'Iluminación', value: 'RGB Chroma configurable con efectos dinámicos' },
                    { name: 'Compatibilidad', value: 'Windows 11/10, macOS, PS5, Xbox y smartphones' },
                    { name: 'Material / Acabado', value: 'Diseño ergonómico premium antideslizante' }
                ];
            }

            // Laptops / Computers (Deep extraction)
            let cpu = 'Intel Core i5 / AMD Ryzen 5 de última generación';
            if (lower.includes('ultra 7') || lower.includes('ultra7')) cpu = 'Intel Core Ultra 7 155H con NPU IA integrada';
            else if (lower.includes('ultra 9') || lower.includes('ultra9')) cpu = 'Intel Core Ultra 9 185H con NPU IA integrada';
            else if (lower.includes('ultra 5') || lower.includes('ultra5')) cpu = 'Intel Core Ultra 5 125H con NPU IA integrada';
            else if (lower.includes('i9') || lower.includes('core i9')) cpu = 'Intel Core i9 14900HX / 13900H (24 núcleos)';
            else if (lower.includes('i7') || lower.includes('core i7')) cpu = 'Intel Core i7 13700H / 1355U Turbo Boost';
            else if (lower.includes('i5') || lower.includes('core i5')) cpu = 'Intel Core i5 13420H / 1335U Turbo Boost';
            else if (lower.includes('i3') || lower.includes('core i3')) cpu = 'Intel Core i3 1215U / 1315U de 6 núcleos';
            else if (lower.includes('ryzen 9')) cpu = 'AMD Ryzen 9 7940HS / 8945HS con Ryzen AI';
            else if (lower.includes('ryzen 7')) cpu = 'AMD Ryzen 7 7735HS / 7730U (8 núcleos, 16 hilos)';
            else if (lower.includes('ryzen 5')) cpu = 'AMD Ryzen 5 7535HS / 7520U (6 núcleos, 12 hilos)';
            else if (lower.includes('ryzen 3')) cpu = 'AMD Ryzen 3 7320U (4 núcleos, 8 hilos)';
            else if (lower.includes('m3')) cpu = 'Chip Apple M3 con CPU de 8 núcleos y GPU de 10 núcleos';
            else if (lower.includes('m2')) cpu = 'Chip Apple M2 con CPU de 8 núcleos';
            else if (lower.includes('m1')) cpu = 'Chip Apple M1 de 8 núcleos';

            let ram = '16GB DDR5 5200MHz de alta velocidad';
            if (lower.includes('32gb')) ram = '32GB DDR5 5600MHz Dual Channel';
            else if (lower.includes('64gb')) ram = '64GB DDR5 5600MHz';
            else if (lower.includes('8gb')) ram = '8GB DDR4 3200MHz (Expandible)';
            else if (lower.includes('12gb')) ram = '12GB DDR4/DDR5';
            else if (lower.includes('24gb')) ram = '24GB DDR5';

            let disk = '512GB SSD M.2 NVMe PCIe 4.0 ultra rápido';
            if (lower.includes('1tb') || lower.includes('1 tb')) disk = '1TB SSD M.2 NVMe PCIe 4.0 ultra rápido';
            else if (lower.includes('2tb') || lower.includes('2 tb')) disk = '2TB SSD M.2 NVMe PCIe 4.0 ultra rápido';
            else if (lower.includes('256gb')) disk = '256GB SSD M.2 NVMe PCIe';

            let screen = '15.6" Full HD (1920x1080) Antirreflejo IPS';
            if (lower.includes('144hz')) screen = '15.6" Full HD (1920x1080) 144Hz IPS Antirreflejo';
            else if (lower.includes('165hz')) screen = '16.0" WQXGA (2560x1600) 165Hz 100% sRGB';
            else if (lower.includes('16') || lower.includes('16"')) screen = '16.0" WUXGA (1920x1200) IPS 16:10';
            else if (lower.includes('14') || lower.includes('14"')) screen = '14.0" Full HD (1920x1080) IPS NanoEdge';
            else if (lower.includes('13.3') || lower.includes('13.3"')) screen = '13.3" OLED 2.8K (2880x1800) Dolby Vision';
            else if (lower.includes('17.3') || lower.includes('17.3"')) screen = '17.3" Full HD 144Hz IPS Gaming';

            let gpu = 'Gráficos Integrados de Alta Definición';
            if (lower.includes('rtx 4090')) gpu = 'NVIDIA GeForce RTX 4090 16GB GDDR6';
            else if (lower.includes('rtx 4080')) gpu = 'NVIDIA GeForce RTX 4080 12GB GDDR6';
            else if (lower.includes('rtx 4070')) gpu = 'NVIDIA GeForce RTX 4070 8GB GDDR6';
            else if (lower.includes('rtx 4060')) gpu = 'NVIDIA GeForce RTX 4060 8GB GDDR6';
            else if (lower.includes('rtx 4050')) gpu = 'NVIDIA GeForce RTX 4050 6GB GDDR6';
            else if (lower.includes('rtx 3050')) gpu = 'NVIDIA GeForce RTX 3050 6GB/4GB GDDR6';
            else if (lower.includes('rtx 2050')) gpu = 'NVIDIA GeForce RTX 2050 4GB GDDR6';
            else if (lower.includes('gtx 1650')) gpu = 'NVIDIA GeForce GTX 1650 4GB GDDR6';
            else if (lower.includes('iris')) gpu = 'Intel Iris Xe Graphics';
            else if (lower.includes('radeon')) gpu = 'AMD Radeon 780M / 680M Graphics';

            let os = 'Windows 11 Home 64-bit Original';
            if (lower.includes('macbook') || lower.includes('apple') || lower.includes('m1') || lower.includes('m2') || lower.includes('m3')) os = 'macOS Sonoma / Ventura';
            else if (lower.includes('pro')) os = 'Windows 11 Pro 64-bit';

            return [
                { name: 'Procesador', value: cpu },
                { name: 'Memoria RAM', value: ram },
                { name: 'Almacenamiento', value: disk },
                { name: 'Pantalla', value: screen },
                { name: 'Gráficos', value: gpu },
                { name: 'Sistema Operativo', value: os },
                { name: 'Conectividad', value: 'Wi-Fi 6 (802.11ax), Bluetooth 5.2, USB-C, HDMI' },
                { name: 'Cámara y Audio', value: 'Cámara HD con obturador de privacidad y altavoces estéreo' }
            ];
        }
    }

    function productForm() {
        return {
            name: {{ Illuminate\Support\Js::from($product->name) }},
            description: {{ Illuminate\Support\Js::from(old('description', $product->description ?? '')) }},
            categoryId: `{{ $product->category_id }}`,
            brandId: `{{ $product->brand_id }}`,
            brandName: {{ Illuminate\Support\Js::from($product->brand?->name ?? '') }},
            categoryName: {{ Illuminate\Support\Js::from($product->category?->name ?? '') }},
            price: `{{ $product->price }}`,
            offerPrice: `{{ $product->offer_price }}`,
            isActive: {{ $product->status ? 'true' : 'false' }},
            isNew: {{ $product->is_new ? 'true' : 'false' }},
            isOffer: {{ $product->is_offer ? 'true' : 'false' }},
            isFeatured: {{ $product->is_featured ? 'true' : 'false' }},
            specs: {{ Illuminate\Support\Js::from($formattedSpecs) }},

            // AI Modal state
            aiModalOpen: false,
            aiTargetField: 'specs',
            aiPrompt: '',
            isAiLoading: false,

            openAiModal(target) {
                this.aiTargetField = target;
                const pName = (this.name || {{ Illuminate\Support\Js::from($product->name) }}).trim();
                this.aiPrompt = pName;
                this.aiModalOpen = true;
            },

            setAiPreset(presetName) {
                if (this.aiPrompt.trim()) {
                    this.aiPrompt = `${presetName} - ${this.aiPrompt}`;
                } else {
                    this.aiPrompt = presetName;
                }
            },

            processAiGeneration() {
                this.isAiLoading = true;
                const promptText = (this.aiPrompt || this.name || {{ Illuminate\Support\Js::from($product->name) }}).trim();

                setTimeout(() => {
                    const generated = runSmartAiEngine(promptText, this.aiTargetField, this.categoryName, this.brandName);
                    if (this.aiTargetField === 'description') {
                        this.description = generated;
                    } else if (this.aiTargetField === 'specs') {
                        this.specs = Array.isArray(generated) ? generated : [];
                    }
                    this.isAiLoading = false;
                    this.aiModalOpen = false;
                }, 250);
            },

            getSpecPlaceholder(name, type) {
                if (type === 'name') return 'Ej. Procesador, RAM...';
                const lower = (name || '').toLowerCase();
                if (lower.includes('procesador') || lower.includes('cpu')) return 'Ej. Intel Core i7 / AMD Ryzen 7';
                if (lower.includes('ram') || lower.includes('memoria')) return 'Ej. 16GB DDR5 5200MHz';
                if (lower.includes('almacenamiento') || lower.includes('disco') || lower.includes('ssd')) return 'Ej. 512GB SSD M.2 NVMe';
                if (lower.includes('pantalla') || lower.includes('display')) return 'Ej. 15.6" FHD 144Hz IPS';
                if (lower.includes('gráfico') || lower.includes('grafico') || lower.includes('gpu') || lower.includes('video')) return 'Ej. NVIDIA RTX 4060 8GB';
                if (lower.includes('sistema') || lower.includes('so') || lower.includes('os')) return 'Ej. Windows 11 Home';
                if (lower.includes('resolución') || lower.includes('resolucion')) return 'Ej. 2K (3 MP) / 1080p';
                if (lower.includes('visión') || lower.includes('vision') || lower.includes('noche')) return 'Ej. A color hasta 30m';
                if (lower.includes('conectividad') || lower.includes('wifi') || lower.includes('red')) return 'Ej. Wi-Fi 2.4GHz / RJ45';
                return 'Ej. Valor o detalle técnico';
            },

            updateBrandName(e) {
                this.brandName = e.target.options[e.target.selectedIndex].dataset.name || '';
            },
            updateCategoryName(e) {
                this.categoryName = e.target.options[e.target.selectedIndex].dataset.name || '';
            },
            addSpec() {
                this.specs.push({ name: '', value: '' });
            },
            removeSpec(index) {
                this.specs.splice(index, 1);
            },
            getSpecsSummary() {
                return this.specs.filter(s => s.value).slice(0, 3).map(s => s.value).join(' | ');
            }
        };
    }

    window.productForm = productForm;
    if (window.Alpine) {
        Alpine.data('productForm', productForm);
    } else {
        document.addEventListener('alpine:init', () => {
            Alpine.data('productForm', productForm);
        });
    }

    function imageUploadManager() {
        return {
            images: [],
            tempUrl: '',
            currentImages: @json($product->images->map(function($img) {
                return [
                    'id' => $img->id,
                    'url' => filter_var($img->image_path, FILTER_VALIDATE_URL) ? $img->image_path : asset('storage/' . $img->image_path)
                ];
            })->toArray()),
            
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
        };
    }

    window.imageUploadManager = imageUploadManager;
    if (window.Alpine) {
        Alpine.data('imageUploadManager', imageUploadManager);
    } else {
        document.addEventListener('alpine:init', () => {
            Alpine.data('imageUploadManager', imageUploadManager);
        });
    }

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
