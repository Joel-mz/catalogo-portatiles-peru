@extends('layouts.admin')
@section('header_title', 'Registrar Producto')
@section('content')

<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div class="flex items-center gap-3">
        <div class="h-10 w-10 bg-indigo-600 rounded-lg flex items-center justify-center text-white shadow-sm">
            <i class="fa-solid fa-cube text-xl"></i>
        </div>
        <div>
            <h2 class="text-xl font-bold text-slate-800">Registrar Producto</h2>
            <p class="text-xs text-slate-500">Completa la información para registrar un nuevo producto en el catálogo.</p>
        </div>
    </div>
    <div class="text-[11px] font-medium text-slate-500 flex items-center gap-2">
        <a href="{{ route('dashboard') }}" class="hover:text-indigo-600"><i class="fa-solid fa-home"></i></a>
        <i class="fa-solid fa-chevron-right text-[8px]"></i>
        <a href="{{ route('admin.products.index') }}" class="hover:text-indigo-600">Productos</a>
        <i class="fa-solid fa-chevron-right text-[8px]"></i>
        <span class="text-indigo-600">Registrar Producto</span>
    </div>
</div>

<div x-data="productForm()">
<form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="pb-24">
    @csrf

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
            <section class="bg-[#FCFCF9] rounded-2xl shadow-sm border border-slate-100 p-6">
                <h3 class="text-sm font-bold text-slate-800 mb-5 flex items-center gap-3">
                    <span class="flex items-center justify-center w-6 h-6 rounded-full bg-indigo-600 text-white text-xs">1</span> 
                    Información Principal
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    <div class="md:col-span-1">
                        <label class="block text-xs font-bold text-slate-700 mb-1">Nombre del Producto <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <i class="fa-solid fa-tag absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-[10px]"></i>
                            <input type="text" name="name" x-model="name" class="w-full pl-8 pr-3 py-2 bg-white border border-slate-200 rounded-lg text-xs focus:ring-1 focus:ring-black focus:border-black transition-colors" placeholder="Ej. Laptop Lenovo IdeaPad Slim 3" required>
                        </div>
                    </div>
                    
                    <div class="md:col-span-1">
                        <label class="block text-xs font-bold text-slate-700 mb-1">Categoría <span class="text-red-500">*</span></label>
                        <div class="flex gap-2">
                            <div class="relative flex-1">
                                <i class="fa-regular fa-folder absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-[10px]"></i>
                                <select name="category_id" x-model="categoryId" @change="updateCategoryName($event)" class="w-full pl-8 pr-3 py-2 bg-white border border-slate-200 rounded-lg text-xs focus:ring-1 focus:ring-black focus:border-black transition-colors appearance-none" required>
                                    <option value="">Selecciona una categoría</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" data-name="{{ $cat->name }}">{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                                <i class="fa-solid fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 text-[9px] pointer-events-none"></i>
                            </div>
                            <button type="button" @click="openQuickAddModal('category_id', 'Categoría')" class="w-9 h-[34px] shrink-0 flex items-center justify-center bg-indigo-50 text-indigo-600 border border-indigo-200 rounded-lg hover:bg-indigo-600 hover:text-white transition-colors" title="Agregar nueva categoría">
                                <i class="fa-solid fa-plus text-xs"></i>
                            </button>
                        </div>
                    </div>

                    <div class="md:col-span-1">
                        <label class="block text-xs font-bold text-slate-700 mb-1">Subcategoría (Opcional)</label>
                        <div class="flex gap-2">
                            <div class="relative flex-1">
                                <i class="fa-solid fa-sitemap absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-[10px]"></i>
                                <select name="subcategory_id" class="w-full pl-8 pr-3 py-2 bg-white border border-slate-200 rounded-lg text-xs focus:ring-1 focus:ring-black focus:border-black transition-colors appearance-none">
                                    <option value="">Selecciona una subcategoría (Opcional)</option>
                                    @foreach($subcategories as $subcat)
                                        <option value="{{ $subcat->id }}">{{ $subcat->name }}</option>
                                    @endforeach
                                </select>
                                <i class="fa-solid fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 text-[9px] pointer-events-none"></i>
                            </div>
                            <button type="button" @click="openQuickAddModal('subcategory_id', 'Subcategoría')" class="w-9 h-[34px] shrink-0 flex items-center justify-center bg-indigo-50 text-indigo-600 border border-indigo-200 rounded-lg hover:bg-indigo-600 hover:text-white transition-colors" title="Agregar nueva subcategoría">
                                <i class="fa-solid fa-plus text-xs"></i>
                            </button>
                        </div>
                    </div>

                    <div class="md:col-span-1">
                        <label class="block text-xs font-bold text-slate-700 mb-1">Marca <span class="text-red-500">*</span></label>
                        <div class="flex gap-2">
                            <div class="relative flex-1">
                                <i class="fa-solid fa-shield-halved absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-[10px]"></i>
                                <select name="brand_id" x-model="brandId" @change="updateBrandName($event)" class="w-full pl-8 pr-3 py-2 bg-white border border-slate-200 rounded-lg text-xs focus:ring-1 focus:ring-black focus:border-black transition-colors appearance-none" required>
                                    <option value="">Selecciona una marca</option>
                                    @foreach($brands as $brand)
                                        <option value="{{ $brand->id }}" data-name="{{ $brand->name }}">{{ $brand->name }}</option>
                                    @endforeach
                                </select>
                                <i class="fa-solid fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 text-[9px] pointer-events-none"></i>
                            </div>
                            <button type="button" @click="openQuickAddModal('brand_id', 'Marca')" class="w-9 h-[34px] shrink-0 flex items-center justify-center bg-indigo-50 text-indigo-600 border border-indigo-200 rounded-lg hover:bg-indigo-600 hover:text-white transition-colors" title="Agregar nueva marca">
                                <i class="fa-solid fa-plus text-xs"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="md:col-span-1">
                        <label class="block text-xs font-bold text-slate-700 mb-1">Modelo <span class="text-red-500">*</span></label>
                        <div class="flex gap-2">
                            <div class="relative flex-1">
                                <i class="fa-solid fa-microchip absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-[10px]"></i>
                                <select name="device_model_id" class="w-full pl-8 pr-3 py-2 bg-white border border-slate-200 rounded-lg text-xs focus:ring-1 focus:ring-black focus:border-black transition-colors appearance-none">
                                    <option value="">Selecciona un modelo</option>
                                    @foreach($models as $model)
                                        <option value="{{ $model->id }}">{{ $model->name }}</option>
                                    @endforeach
                                </select>
                                <i class="fa-solid fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 text-[9px] pointer-events-none"></i>
                            </div>
                            <button type="button" @click="openQuickAddModal('device_model_id', 'Modelo')" class="w-9 h-[34px] shrink-0 flex items-center justify-center bg-indigo-50 text-indigo-600 border border-indigo-200 rounded-lg hover:bg-indigo-600 hover:text-white transition-colors" title="Agregar nuevo modelo">
                                <i class="fa-solid fa-plus text-xs"></i>
                            </button>
                        </div>
                    </div>

                    <div class="md:col-span-1">
                        <label class="block text-xs font-bold text-slate-700 mb-1">Producto <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <i class="fa-solid fa-laptop absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-[10px]"></i>
                            <select name="control_type" class="w-full pl-8 pr-3 py-2 bg-white border border-slate-200 rounded-lg text-xs focus:ring-1 focus:ring-black focus:border-black transition-colors appearance-none" required>
                                <option value="Serie">Por Serie</option>
                                <option value="Cantidad">Por Cantidad</option>
                            </select>
                            <i class="fa-solid fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 text-[9px] pointer-events-none"></i>
                        </div>
                    </div>

                    <div class="md:col-span-3">
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
                            <textarea name="description" x-model="description" rows="4" class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-colors resize-y leading-relaxed" placeholder="Ingresa o pega la descripción completa y detallada del producto sin límite..." required></textarea>
                        </div>
                    </div>
                </div>
            </section>

            <!-- 2. Identificación del Producto -->
            <section class="bg-[#FCFCF9] rounded-2xl shadow-sm border border-slate-100 p-6">
                <h3 class="text-sm font-bold text-slate-800 mb-5 flex items-center gap-3">
                    <span class="flex items-center justify-center w-6 h-6 rounded-full bg-indigo-600 text-white text-xs">2</span> 
                    Identificación del Producto
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Código / SKU <span class="text-red-500">*</span></label>
                        <div class="relative flex">
                            <i class="fa-solid fa-barcode absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-[10px]"></i>
                            <input type="text" name="code" value="{{ 'SKU-' . strtoupper(Str::random(6)) }}" class="w-full pl-8 pr-3 py-2 bg-white border border-slate-200 rounded-lg text-xs focus:ring-1 focus:ring-black focus:border-black transition-colors" placeholder="Ej. SKU-000123" required>
                        </div>
                        <p class="text-[9px] text-slate-400 mt-1">Se genera automáticamente o escríbelo</p>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Número de Serie (Opcional)</label>
                        <div class="relative">
                            <i class="fa-solid fa-hard-drive absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-[10px]"></i>
                            <input type="text" name="serial_number" class="w-full pl-8 pr-3 py-2 bg-white border border-slate-200 rounded-lg text-xs focus:ring-1 focus:ring-black focus:border-black transition-colors" placeholder="Ej. PF5WQYD7">
                        </div>
                        <p class="text-[9px] text-slate-400 mt-1">Solo para laptops, impresoras, monitores, celulares u otros equipos.</p>
                    </div>
                </div>
            </section>

            <!-- 3. Precios e Inventario -->
            <section class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                <h3 class="text-sm font-bold text-slate-800 mb-5 flex items-center gap-3">
                    <span class="flex items-center justify-center w-6 h-6 rounded-full bg-indigo-600 text-white text-xs">3</span> 
                    Precios e Inventario
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-5">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Precio de Venta <span class="text-red-500">*</span></label>
                        <div class="relative flex items-center">
                            <span class="absolute left-3 text-slate-500 text-xs font-bold">S/</span>
                            <input type="number" step="0.01" name="price" x-model="price" class="w-full pl-8 pr-3 py-2 bg-white border border-slate-200 rounded-lg text-xs focus:ring-1 focus:ring-black focus:border-black transition-colors" placeholder="2,499.00" required>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Precio Mínimo <span class="text-red-500">*</span></label>
                        <div class="relative flex items-center">
                            <span class="absolute left-3 text-slate-500 text-xs font-bold">S/</span>
                            <input type="number" step="0.01" name="min_price" class="w-full pl-8 pr-3 py-2 bg-white border border-slate-200 rounded-lg text-xs focus:ring-1 focus:ring-black focus:border-black transition-colors" placeholder="2,299.00" required>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Stock Actual <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <i class="fa-solid fa-box absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-[10px]"></i>
                            <input type="number" name="stock" value="10" class="w-full pl-8 pr-3 py-2 bg-white border border-slate-200 rounded-lg text-xs focus:ring-1 focus:ring-black focus:border-black transition-colors" required>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Estado <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <i class="fa-solid fa-check-circle absolute left-3 top-1/2 -translate-y-1/2 text-green-500 text-[10px]"></i>
                            <select name="state" class="w-full pl-8 pr-3 py-2 bg-white border border-slate-200 rounded-lg text-xs focus:ring-1 focus:ring-black focus:border-black transition-colors appearance-none" required>
                                <option value="disponible">Activo</option>
                                <option value="agotado">Agotado</option>
                                <option value="proximamente">Próximamente</option>
                            </select>
                            <i class="fa-solid fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 text-[9px] pointer-events-none"></i>
                        </div>
                    </div>
                </div>
            </section>

            <!-- 4. Especificaciones Técnicas -->
            <section class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                <h3 class="text-sm font-bold text-slate-800 mb-5 flex items-center gap-3">
                    <span class="flex items-center justify-center w-6 h-6 rounded-full bg-indigo-600 text-white text-xs">4</span> 
                    Especificaciones Técnicas
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
                    <div class="md:col-span-7">
                        <div class="grid grid-cols-12 gap-2 mb-2 px-2">
                            <div class="col-span-5 text-[10px] font-bold text-slate-700">Característica</div>
                            <div class="col-span-6 text-[10px] font-bold text-slate-700">Valor</div>
                            <div class="col-span-1"></div>
                        </div>
                        
                        <div class="space-y-2 mb-4">
                            <template x-for="(spec, index) in specs" :key="index">
                                <div class="grid grid-cols-12 gap-2 items-center group">
                                    <div class="col-span-5 relative">
                                        <i class="fa-solid fa-grip-vertical absolute left-2 top-1/2 -translate-y-1/2 text-slate-300 text-[9px] cursor-move"></i>
                                        <input type="text" list="commonSpecsCreate" x-model="spec.name" :name="'specs['+index+'][name]'" class="w-full pl-6 pr-2 py-1.5 bg-white border border-slate-200 rounded-lg text-[11px] font-semibold text-slate-800 focus:ring-1 focus:ring-black focus:border-black" placeholder="Ej. Procesador, RAM, Resolución...">
                                    </div>
                                    <div class="col-span-6">
                                        <input type="text" x-model="spec.value" :name="'specs['+index+'][value]'" class="w-full px-2 py-1.5 bg-white border border-slate-200 rounded-lg text-[11px] focus:ring-1 focus:ring-black focus:border-black" placeholder="Ej. Ryzen 5 7520U / 3K / 16GB">
                                    </div>
                                    <div class="col-span-1 text-center">
                                        <button type="button" @click="removeSpec(index)" class="text-red-400 hover:text-red-600 p-1">
                                            <i class="fa-solid fa-trash text-[11px]"></i>
                                        </button>
                                    </div>
                                </div>
                            </template>
                            <datalist id="commonSpecsCreate">
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
                                <option value="Protección">
                                <option value="Tipo de Impresión">
                                <option value="Funciones">
                                <option value="Resolución de Impresión">
                                <option value="Velocidad de Impresión">
                                <option value="Capacidad de Bandeja">
                                <option value="Batería">
                            </datalist>
                        </div>
                        
                        <div class="flex gap-2">
                            <button type="button" @click="addSpec()" class="px-3 py-1.5 bg-indigo-600 text-white text-[11px] font-bold rounded-lg hover:bg-indigo-700 transition-colors flex items-center gap-1.5">
                                <i class="fa-solid fa-plus text-[10px]"></i> Agregar especificación
                            </button>
                            <button type="button" @click="openAiModal('specs')" class="px-3 py-1.5 bg-purple-600 text-white text-[11px] font-bold rounded-lg hover:bg-purple-700 transition-colors flex items-center gap-1.5 shadow-sm shadow-purple-600/30">
                                <i class="fa-solid fa-wand-magic-sparkles text-[10px]"></i> Llenar con IA
                            </button>
                        </div>
                    </div>
                    
                    <div class="md:col-span-5">
                        <div class="bg-indigo-50/50 rounded-xl p-4 border border-indigo-100">
                            <h4 class="text-[11px] font-bold text-indigo-800 flex items-center gap-2 mb-3">
                                <i class="fa-regular fa-lightbulb text-indigo-500 text-sm"></i> Ejemplos de especificaciones
                            </h4>
                            <ul class="text-[10px] text-slate-600 space-y-1.5">
                                <li><span class="font-bold text-slate-700">Procesador:</span> Intel Core, AMD Ryzen, etc.</li>
                                <li><span class="font-bold text-slate-700">Memoria RAM:</span> 8GB, 16GB, 32GB, etc.</li>
                                <li><span class="font-bold text-slate-700">Almacenamiento:</span> SSD, HDD, M.2, etc.</li>
                                <li><span class="font-bold text-slate-700">Pantalla:</span> HD, FHD, QHD, etc.</li>
                                <li><span class="font-bold text-slate-700">Sistema Operativo:</span> Windows, Linux, etc.</li>
                                <li><span class="font-bold text-slate-700">Gráficos:</span> Integrados / Dedicados, etc.</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </section>
            
        </div>

        <!-- Right Column -->
        <div class="lg:col-span-4 space-y-6" x-data="imageUploadManager()">
            
            <!-- 5. Imágenes del Producto -->
            <section class="bg-[#FCFCF9] rounded-2xl shadow-sm border border-slate-100 p-5">
                <h3 class="text-sm font-bold text-slate-800 mb-4 flex items-center gap-3">
                    <span class="flex items-center justify-center w-6 h-6 rounded-full bg-indigo-600 text-white text-xs">5</span> Imágenes del Producto
                </h3>
                
                <div class="border-2 border-dashed border-indigo-200 rounded-xl p-6 text-center bg-indigo-50/30 relative hover:bg-indigo-50 hover:border-indigo-300 transition-colors cursor-pointer mb-3" 
                     @click="addFileInput()"
                     @dragover.prevent="dragOver = true"
                     @dragleave.prevent="dragOver = false"
                     @drop.prevent="handleDrop($event)"
                     :class="dragOver ? 'bg-indigo-100 border-indigo-400' : ''">
                    <i class="fa-solid fa-cloud-arrow-up text-3xl text-indigo-500 mb-2"></i>
                    <p class="text-[11px] font-bold text-indigo-700 mb-1">Arrastra y suelta las imágenes aquí</p>
                    <p class="text-[9px] text-slate-500">o haz clic para seleccionar</p>
                    <p class="text-[8px] text-slate-400 mt-2">Formatos: JPG, PNG (Máx. 8 imágenes)</p>
                </div>

                <!-- URL Input for Images -->
                <div class="flex gap-2 mb-3">
                    <input type="url" x-model="imageUrl" placeholder="Pegar URL de la imagen..." class="flex-1 px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-xs focus:ring-1 focus:ring-black focus:border-black">
                    <button type="button" @click="addImageFromUrl()" class="px-3 py-1.5 bg-indigo-600 text-white rounded-lg text-[10px] font-bold hover:bg-indigo-700 transition-colors flex items-center gap-1 shrink-0">
                        <i class="fa-solid fa-link"></i> Agregar
                    </button>
                </div>

                <!-- Hidden inputs container -->
                <div id="hidden-inputs-container" class="hidden">
                    <template x-for="img in images" :key="img.id">
                        <input type="file" :name="'images_files['+img.id+']'" :id="'file_input_'+img.id" accept="image/*" @change="handleFileChange($event, img)">
                    </template>
                </div>

                <!-- Preview Area -->
                <div class="grid grid-cols-4 gap-2">
                    <template x-for="(img, index) in images" :key="img.id">
                        <div class="relative aspect-square rounded-lg border border-slate-200 bg-white overflow-hidden group">
                            <span x-show="index === 0" class="absolute top-0 left-0 bg-indigo-600 text-white text-[8px] font-bold px-1.5 py-0.5 rounded-br-lg z-10">Principal</span>
                            <img :src="img.preview" class="w-full h-full object-cover">
                            <button type="button" @click.stop="removeImage(img.id)" class="absolute top-1 right-1 w-4 h-4 bg-white rounded-full flex items-center justify-center text-red-500 shadow-sm opacity-0 group-hover:opacity-100 transition-opacity">
                                <i class="fa-solid fa-xmark text-[8px]"></i>
                            </button>
                        </div>
                    </template>
                    <button type="button" @click="addFileInput()" x-show="images.length < 8" class="aspect-square rounded-lg border border-slate-200 bg-slate-50 flex items-center justify-center text-indigo-500 hover:bg-slate-100 transition-colors">
                        <i class="fa-solid fa-plus text-lg"></i>
                    </button>
                </div>
            </section>

            <!-- 6. Garantía -->
            <section class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
                <h3 class="text-sm font-bold text-slate-800 mb-4 flex items-center gap-3">
                    <span class="flex items-center justify-center w-6 h-6 rounded-full bg-indigo-600 text-white text-xs">6</span> Garantía
                </h3>
                <div class="relative">
                    <i class="fa-solid fa-shield-halved absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-[10px]"></i>
                    <select name="warranty" class="w-full pl-8 pr-3 py-2 bg-white border border-slate-200 rounded-lg text-xs focus:ring-1 focus:ring-black focus:border-black appearance-none" required>
                        <option value="6 meses">6 meses</option>
                        <option value="1 año">1 año</option>
                        <option value="2 años">2 años</option>
                        <option value="3 años">3 años</option>
                        <option value="Sin garantía">Sin garantía</option>
                    </select>
                    <i class="fa-solid fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 text-[9px] pointer-events-none"></i>
                </div>
            </section>

            <!-- 7. Opciones de Visualización -->
            <section class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
                <h3 class="text-sm font-bold text-slate-800 mb-4 flex items-center gap-3">
                    <span class="flex items-center justify-center w-6 h-6 rounded-full bg-indigo-600 text-white text-xs">7</span> Opciones de Visualización
                </h3>
                
                <div class="space-y-4">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <div class="relative">
                            <input type="checkbox" name="status" value="1" class="sr-only peer" x-model="isActive">
                            <div class="w-9 h-5 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-indigo-600"></div>
                        </div>
                        <div class="flex items-center gap-2 text-[11px] font-bold text-slate-700">
                            <i class="fa-solid fa-eye text-indigo-500"></i> Producto activo
                        </div>
                    </label>

                    <label class="flex items-center gap-3 cursor-pointer">
                        <div class="relative">
                            <input type="checkbox" name="is_new" value="1" class="sr-only peer" x-model="isNew">
                            <div class="w-9 h-5 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-indigo-600"></div>
                        </div>
                        <div class="flex items-center gap-2 text-[11px] font-bold text-slate-700">
                            <i class="fa-solid fa-certificate text-slate-400 peer-checked:text-indigo-500"></i> Producto nuevo
                        </div>
                    </label>

                    <label class="flex items-center gap-3 cursor-pointer">
                        <div class="relative">
                            <input type="checkbox" name="is_offer" value="1" class="sr-only peer" x-model="isOffer">
                            <div class="w-9 h-5 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-indigo-600"></div>
                        </div>
                        <div class="flex items-center gap-2 text-[11px] font-bold text-slate-700">
                            <i class="fa-solid fa-tag text-slate-400 peer-checked:text-indigo-500"></i> Oferta especial
                        </div>
                    </label>

                    <label class="flex items-center gap-3 cursor-pointer">
                        <div class="relative">
                            <input type="checkbox" name="is_featured" value="1" class="sr-only peer" x-model="isFeatured">
                            <div class="w-9 h-5 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-indigo-600"></div>
                        </div>
                        <div class="flex items-center gap-2 text-[11px] font-bold text-slate-700">
                            <i class="fa-regular fa-star text-slate-400 peer-checked:text-indigo-500"></i> Destacar en Inicio
                        </div>
                    </label>
                </div>
            </section>

            <!-- 8. Resumen del Producto -->
            <section class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
                <h3 class="text-sm font-bold text-slate-800 mb-4 flex items-center gap-3">
                    <span class="flex items-center justify-center w-6 h-6 rounded-full bg-indigo-600 text-white text-xs">8</span> Resumen del Producto
                </h3>
                
                <div class="flex gap-4">
                    <div class="w-20 h-20 bg-slate-50 rounded-lg border border-slate-100 flex items-center justify-center overflow-hidden shrink-0">
                        <template x-if="window.globalImages && window.globalImages.length > 0">
                            <img :src="window.globalImages[0].preview" class="w-full h-full object-cover">
                        </template>
                        <template x-if="!window.globalImages || window.globalImages.length === 0">
                            <i class="fa-solid fa-laptop text-slate-300 text-2xl"></i>
                        </template>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-slate-800 line-clamp-2 leading-tight" x-text="name || 'Nombre del Producto'"></h4>
                        <p class="text-[9px] text-slate-500 mt-1 line-clamp-1" x-text="specs[0]?.value ? specs[0].value + ' | ' + (specs[1]?.value || '') + ' | ' + (specs[2]?.value || '') : 'Especificaciones'"></p>
                        <div class="flex gap-1.5 mt-2">
                            <span x-show="isActive" class="px-1.5 py-0.5 rounded text-[8px] font-bold bg-green-100 text-green-700">Activo</span>
                            <span x-show="isNew" class="px-1.5 py-0.5 rounded text-[8px] font-bold bg-indigo-100 text-indigo-700">Nuevo</span>
                        </div>
                        <p class="text-sm font-black text-indigo-600 mt-2" x-text="price ? 'S/ ' + price : 'S/ 0.00'"></p>
                    </div>
                </div>
            </section>

        </div>
    </div>
    
    <!-- Action Buttons -->
    <div class="mt-6 flex items-center justify-end gap-3">
        <a href="{{ route('admin.products.index') }}" class="px-5 py-2.5 bg-white border border-slate-200 text-slate-600 text-sm font-bold rounded-xl hover:bg-slate-50 transition-colors flex items-center gap-2">
            <i class="fa-solid fa-xmark"></i> Cancelar
        </a>
        <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white text-sm font-bold rounded-xl hover:bg-indigo-700 transition-colors shadow-lg shadow-indigo-600/30 flex items-center gap-2">
            <i class="fa-solid fa-floppy-disk"></i> Guardar Producto
        </button>
    </div>
</form>

<!-- AI Modal -->
<div x-show="aiModalOpen" 
     x-cloak 
     class="fixed inset-0 z-[150] flex items-center justify-center bg-slate-950/70 backdrop-blur-sm p-4"
     @keydown.window.escape="aiModalOpen = false">
    <div @click.away="aiModalOpen = false" class="bg-white rounded-3xl shadow-2xl w-full max-w-xl p-6 border border-slate-100 relative">
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

<!-- Quick Add Modal -->
<div x-show="quickAddModalOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-sm" style="display: none;">
    <div @click.away="quickAddModalOpen = false" class="bg-white rounded-2xl shadow-xl w-full max-w-sm p-6">
        <h3 class="text-lg font-bold text-slate-800 mb-2" x-text="'Agregar nueva ' + quickAddTypeLabel"></h3>
        <div class="mb-4">
            <label class="block text-xs font-bold text-slate-700 mb-1">Nombre <span class="text-red-500">*</span></label>
            <input type="text" x-model="quickAddValue" class="w-full px-3 py-2 border border-slate-200 rounded-xl text-sm focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Ej. Nuevo nombre..." @keydown.enter="processQuickAdd()">
        </div>
        <div class="flex justify-end gap-2">
            <button type="button" @click="quickAddModalOpen = false" class="px-4 py-2 text-sm text-slate-600 hover:bg-slate-100 rounded-lg font-bold">Cancelar</button>
            <button type="button" @click="processQuickAdd()" class="px-4 py-2 text-sm bg-indigo-600 text-white rounded-lg font-bold hover:bg-indigo-700 flex items-center gap-2">
                <i class="fa-solid fa-spinner fa-spin" x-show="isQuickAddLoading"></i>
                <span x-text="isQuickAddLoading ? 'Guardando...' : 'Guardar'"></span>
            </button>
        </div>
    </div>
</div>
</div>
@endsection

@push('scripts')
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
            name: '',
            price: '',
            description: '',
            categoryId: '',
            brandId: '',
            categoryName: '',
            brandName: '',
            isActive: true,
            isNew: false,
            isOffer: false,
            isFeatured: false,
            specs: [
                { name: 'Procesador', value: '' },
                { name: 'Memoria RAM', value: '' },
                { name: 'Almacenamiento', value: '' },
                { name: 'Pantalla', value: '' },
                { name: 'Sistema Operativo', value: '' }
            ],
            
            // AI Modal state
            aiModalOpen: false,
            aiTargetField: 'specs',
            aiPrompt: '',
            isAiLoading: false,
            
            // Quick Add Modal state
            quickAddModalOpen: false,
            quickAddType: '',
            quickAddTypeLabel: '',
            quickAddValue: '',
            isQuickAddLoading: false,
            
            openAiModal(target) {
                this.aiTargetField = target;
                const pName = (this.name || '').trim();
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
                const promptText = (this.aiPrompt || this.name || '').trim();

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
            
            openQuickAddModal(type, label) {
                this.quickAddType = type;
                this.quickAddTypeLabel = label;
                this.quickAddValue = '';
                this.quickAddModalOpen = true;
            },
            
            processQuickAdd() {
                if (!this.quickAddValue.trim()) return;
                this.isQuickAddLoading = true;
                
                setTimeout(() => {
                    const selectEl = document.querySelector(`select[name="${this.quickAddType}"]`);
                    if (selectEl) {
                        const option = document.createElement('option');
                        option.value = "new_" + Date.now();
                        option.text = this.quickAddValue;
                        option.selected = true;
                        selectEl.add(option);
                        
                        if (this.quickAddType === 'category_id') this.categoryId = option.value;
                        if (this.quickAddType === 'brand_id') this.brandId = option.value;
                    }
                    
                    this.isQuickAddLoading = false;
                    this.quickAddModalOpen = false;
                }, 300);
            },

            updateCategoryName(e) {
                this.categoryName = e.target.options[e.target.selectedIndex].dataset.name || '';
            },
            updateBrandName(e) {
                this.brandName = e.target.options[e.target.selectedIndex].dataset.name || '';
            },
            addSpec() {
                this.specs.push({ name: '', value: '' });
            },
            removeSpec(index) {
                this.specs.splice(index, 1);
            }
        };
    }


    function imageUploadManager() {
        return {
            images: [],
            dragOver: false,
            imageUrl: '',
            
            init() {
                window.globalImages = this.images;
                this.$watch('images', val => window.globalImages = val);
            },
            
            addFileInput() {
                if (this.images.length >= 8) {
                    alert('Máximo 8 imágenes permitidas');
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
            addImageFromUrl() {
                if (!this.imageUrl) return;
                if (this.images.length >= 8) {
                    alert('Máximo 8 imágenes permitidas');
                    return;
                }
                
                const newId = Date.now() + Math.random().toString(36).substring(7);
                this.images.push({
                    id: newId,
                    type: 'url',
                    url: this.imageUrl,
                    preview: this.imageUrl
                });
                
                this.$nextTick(() => {
                    const container = document.getElementById('hidden-inputs-container');
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'images_urls[' + newId + ']';
                    input.id = 'url_input_' + newId;
                    input.value = this.imageUrl;
                    container.appendChild(input);
                    this.imageUrl = '';
                });
            },
            removeImage(id) {
                this.images = this.images.filter(img => img.id !== id);
                let input = document.getElementById('file_input_' + id);
                if (input) input.remove();
                let urlInput = document.getElementById('url_input_' + id);
                if (urlInput) urlInput.remove();
            },
            handleFileChange(event, img) {
                const file = event.target.files[0];
                this.processFile(file, img);
            },
            handleDrop(event) {
                this.dragOver = false;
                const files = event.dataTransfer.files;
                if (!files.length) return;
                
                for (let i = 0; i < files.length; i++) {
                    if (this.images.length >= 8) {
                        alert('Máximo 8 imágenes permitidas');
                        break;
                    }
                    if (!files[i].type.startsWith('image/')) continue;
                    
                    const newId = Date.now() + Math.random().toString(36).substring(7);
                    const newImg = {
                        id: newId,
                        type: 'file',
                        url: '',
                        preview: ''
                    };
                    this.images.push(newImg);
                    
                    this.$nextTick(() => {
                        const dt = new DataTransfer();
                        dt.items.add(files[i]);
                        const input = document.getElementById('file_input_' + newId);
                        if (input) {
                            input.files = dt.files;
                            this.processFile(files[i], newImg);
                        }
                    });
                }
            },
            processFile(file, img) {
                if (file) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        img.preview = e.target.result;
                    };
                    reader.readAsDataURL(file);
                } else {
                    this.images = this.images.filter(i => i.id !== img.id);
                }
            }
        };
    }
</script>
@endpush
