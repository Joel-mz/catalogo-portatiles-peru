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
                        <label class="block text-xs font-bold text-slate-700 mb-1">Descripción corta <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <textarea name="description" x-model="description" rows="3" maxlength="200" class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-xs focus:ring-1 focus:ring-black focus:border-black transition-colors resize-none" placeholder="Ej. Breve descripción del producto (máx. 200 caracteres)." required></textarea>
                            <div class="absolute bottom-2 left-2">
                                <button type="button" @click="openAiModal('description')" class="px-2 py-1 bg-purple-100 text-purple-700 text-[9px] font-bold rounded hover:bg-purple-200 transition-colors flex items-center gap-1">
                                    <i class="fa-solid fa-wand-magic-sparkles"></i> Llenar con IA
                                </button>
                            </div>
                            <div class="absolute bottom-2 right-3 text-[9px] text-slate-400" x-text="(description ? description.length : 0) + '/200'"></div>
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
                                        <select x-model="spec.name" :name="'specs['+index+'][name]'" class="w-full pl-6 pr-2 py-1.5 bg-white border border-slate-200 rounded-lg text-[11px] focus:ring-1 focus:ring-black focus:border-black appearance-none">
                                            <option value="">Seleccionar...</option>
                                            <option value="Procesador">Procesador</option>
                                            <option value="Memoria RAM">Memoria RAM</option>
                                            <option value="Almacenamiento">Almacenamiento</option>
                                            <option value="Pantalla">Pantalla</option>
                                            <option value="Sistema Operativo">Sistema Operativo</option>
                                            <option value="Gráficos">Gráficos</option>
                                        </select>
                                    </div>
                                    <div class="col-span-6">
                                        <input type="text" x-model="spec.value" :name="'specs['+index+'][value]'" class="w-full px-2 py-1.5 bg-white border border-slate-200 rounded-lg text-[11px] focus:ring-1 focus:ring-black focus:border-black" placeholder="Ej. Ryzen 5 7520U">
                                    </div>
                                    <div class="col-span-1 text-center">
                                        <button type="button" @click="removeSpec(index)" class="text-red-400 hover:text-red-600 p-1">
                                            <i class="fa-solid fa-trash text-[11px]"></i>
                                        </button>
                                    </div>
                                </div>
                            </template>
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
<div x-show="aiModalOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-sm" style="display: none;">
    <div @click.away="aiModalOpen = false" class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6">
        <h3 class="text-lg font-bold text-slate-800 mb-2 flex items-center gap-2">
            <i class="fa-solid fa-wand-magic-sparkles text-purple-600"></i> Generar con Inteligencia Artificial
        </h3>
        <p class="text-xs text-slate-500 mb-4">Describe qué información deseas generar y la IA lo completará por ti.</p>
        <textarea x-model="aiPrompt" rows="4" class="w-full px-3 py-2 border border-slate-200 rounded-xl text-sm focus:ring-1 focus:ring-purple-500 focus:border-purple-500 mb-4" placeholder="Ej. Genera las especificaciones técnicas para una laptop HP Envy x360..."></textarea>
        <div class="flex justify-end gap-2">
            <button type="button" @click="aiModalOpen = false" class="px-4 py-2 text-sm text-slate-600 hover:bg-slate-100 rounded-lg font-bold">Cancelar</button>
            <button type="button" @click="processAiGeneration()" class="px-4 py-2 text-sm bg-purple-600 text-white rounded-lg font-bold hover:bg-purple-700 flex items-center gap-2">
                <i class="fa-solid fa-bolt" x-show="!isAiLoading"></i>
                <i class="fa-solid fa-spinner fa-spin" x-show="isAiLoading"></i>
                <span x-text="isAiLoading ? 'Generando...' : 'Generar'"></span>
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

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('productForm', () => ({
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
            aiTargetField: '',
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
                const pName = this.name || '';
                if (target === 'description') {
                    this.aiPrompt = pName ? `Generar descripción comercial para: ${pName}` : '';
                } else {
                    this.aiPrompt = pName || '';
                }
                this.aiModalOpen = true;
            },
            
            processAiGeneration() {
                this.isAiLoading = true;
                const promptText = (this.aiPrompt || this.name || '').trim();
                const lower = promptText.toLowerCase();

                setTimeout(() => {
                    if (this.aiTargetField === 'description') {
                        if (lower.includes('camara') || lower.includes('cámara') || lower.includes('ezviz') || lower.includes('imou') || lower.includes('seguridad')) {
                            this.description = `${promptText} con visión nocturna a color, detección inteligente de movimiento, audio bidireccional y alta resistencia para exteriores o interiores.`;
                        } else if (lower.includes('impresora') || lower.includes('epson') || lower.includes('canon') || lower.includes('hp smart')) {
                            this.description = `Impresora multifuncional de alto rendimiento con sistema continuo de tinta, conexión inalámbrica Wi-Fi y máxima velocidad de impresión con bajo costo por página.`;
                        } else {
                            this.description = `Laptop de alto rendimiento ideal para trabajo profesional, multitarea y entretenimiento, equipada con tecnología de última generación para máxima velocidad y autonomía.`;
                        }
                        if (this.description.length > 200) {
                            this.description = this.description.substring(0, 197) + '...';
                        }
                    } else if (this.aiTargetField === 'specs') {
                        if (lower.includes('camara') || lower.includes('cámara') || lower.includes('ezviz') || lower.includes('imou') || lower.includes('seguridad')) {
                            this.specs = [
                                { name: 'Resolución', value: lower.includes('3k') ? '3K (5 Megapíxeles)' : (lower.includes('2k') ? '2K (3 Megapíxeles)' : 'Full HD 1080p') },
                                { name: 'Visión Nocturna', value: 'A color inteligente con focos LED y luz infrarroja' },
                                { name: 'Ángulo de Cobertura', value: 'Panorámica 360° motorizada con seguimiento' },
                                { name: 'Conectividad', value: 'Wi-Fi 2.4 GHz y puerto Ethernet RJ45' },
                                { name: 'Almacenamiento', value: 'Ranura MicroSD hasta 512GB y EZVIZ CloudPlay' },
                                { name: 'Detección Inteligente', value: 'IA para personas y vehículos' },
                                { name: 'Protección', value: 'IP66 resistente a lluvia y polvo' }
                            ];
                        } else if (lower.includes('impresora') || lower.includes('multifuncional')) {
                            this.specs = [
                                { name: 'Tipo de Impresión', value: 'Inyección de tinta continua EcoTank' },
                                { name: 'Funciones', value: 'Imprime, Copia, Escanea' },
                                { name: 'Conectividad', value: 'Wi-Fi Direct, USB de alta velocidad' },
                                { name: 'Resolución de Impresión', value: 'Hasta 5760 x 1440 dpi' },
                                { name: 'Velocidad de Impresión', value: '33 ppm en negro y 15 ppm en color' },
                                { name: 'Capacidad de Bandeja', value: '100 hojas de papel común' }
                            ];
                        } else {
                            let cpu = 'Intel Core i5 / AMD Ryzen 5';
                            if (lower.includes('i7') || lower.includes('ryzen 7')) cpu = 'Intel Core i7 / AMD Ryzen 7';
                            if (lower.includes('i9') || lower.includes('ryzen 9')) cpu = 'Intel Core i9 / AMD Ryzen 9';
                            if (lower.includes('i3') || lower.includes('ryzen 3')) cpu = 'Intel Core i3 / AMD Ryzen 3';

                            let ram = '16GB DDR5 4800MHz';
                            if (lower.includes('8gb')) ram = '8GB DDR4 3200MHz';
                            if (lower.includes('32gb')) ram = '32GB DDR5 5600MHz';

                            let disk = '512GB SSD M.2 NVMe PCIe 4.0';
                            if (lower.includes('1tb')) disk = '1TB SSD M.2 NVMe PCIe 4.0';
                            if (lower.includes('256gb')) disk = '256GB SSD M.2 NVMe';

                            let gpu = lower.includes('rtx') ? 'NVIDIA GeForce RTX 4060 8GB GDDR6' : 'Gráficos Integrados de Alta Definición';

                            this.specs = [
                                { name: 'Procesador', value: cpu },
                                { name: 'Memoria RAM', value: ram },
                                { name: 'Almacenamiento', value: disk },
                                { name: 'Pantalla', value: '15.6" Full HD (1920x1080) Antirreflejo' },
                                { name: 'Gráficos', value: gpu },
                                { name: 'Sistema Operativo', value: 'Windows 11 Home 64-bit' }
                            ];
                        }
                    }
                    this.isAiLoading = false;
                    this.aiModalOpen = false;
                }, 600);
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
                
                // Simulate saving via AJAX to backend
                setTimeout(() => {
                    const selectEl = document.querySelector(`select[name="${this.quickAddType}"]`);
                    if (selectEl) {
                        const option = document.createElement('option');
                        option.value = "new_" + Date.now(); // Dummy ID for visual feedback
                        option.text = this.quickAddValue;
                        option.selected = true;
                        selectEl.add(option);
                        
                        // Update model if bound
                        if (this.quickAddType === 'category_id') this.categoryId = option.value;
                        if (this.quickAddType === 'brand_id') this.brandId = option.value;
                    }
                    
                    this.isQuickAddLoading = false;
                    this.quickAddModalOpen = false;
                    // Note: Here you would normally POST to your backend route
                }, 800);
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
                
                // Add a hidden input to submit the URL
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

    window.imageUploadManager = imageUploadManager;
    if (window.Alpine) {
        Alpine.data('imageUploadManager', imageUploadManager);
    } else {
        document.addEventListener('alpine:init', () => {
            Alpine.data('imageUploadManager', imageUploadManager);
        });
    }
</script>

@endsection
