@extends('layouts.admin')
@section('header_title', 'Registrar Producto')
@section('content')

<!-- External JS Libraries for Live Barcodes and QR Codes -->
<script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

<style>
    /* Warm cream palette matching reference design */
    :root {
        --cream-bg: #FAF9F5;
        --card-border: #E8E5DF;
        --purple-brand: #7C3AED;
        --purple-hover: #6D28D9;
        --purple-soft: #F3E8FF;
    }

    .form-warm-bg {
        background-color: var(--cream-bg);
    }

    /* Laser Scanner Beam Keyframe Animation */
    @keyframes laserScan {
        0% { top: 12%; opacity: 0.8; }
        50% { top: 84%; opacity: 1; box-shadow: 0 0 15px #ef4444, 0 0 30px #ec4899; }
        100% { top: 12%; opacity: 0.8; }
    }

    .animate-laser {
        position: absolute;
        left: 8%;
        right: 8%;
        height: 3px;
        background: linear-gradient(90deg, transparent, #ef4444 20%, #f43f5e 50%, #ef4444 80%, transparent);
        box-shadow: 0 0 12px #ef4444, 0 0 20px #f43f5e;
        border-radius: 9999px;
        animation: laserScan 2.4s ease-in-out infinite;
        pointer-events: none;
    }

    /* Reticle corner brackets */
    .reticle-corner {
        position: absolute;
        width: 22px;
        height: 22px;
        border-color: #38bdf8;
        pointer-events: none;
    }

    /* Print styles specifically for thermal sticker */
    @media print {
        body * {
            visibility: hidden;
        }
        #thermal-print-area, #thermal-print-area * {
            visibility: visible;
        }
        #thermal-print-area {
            position: fixed;
            left: 0;
            top: 0;
            width: 100mm;
            height: 60mm;
            margin: 0;
            padding: 2mm;
            background: white !important;
            color: black !important;
            box-shadow: none !important;
            border: 1px solid #000 !important;
        }
        @page {
            size: 100mm 60mm;
            margin: 0;
        }
    }
</style>

<div x-data="productCreateApp()" x-init="initComponent()" class="min-h-screen pb-28">

    <!-- Top Breadcrumb & Header -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center gap-3.5">
            <div class="h-11 w-11 rounded-2xl bg-gradient-to-tr from-[#6D28D9] to-[#8B5CF6] flex items-center justify-center text-white shadow-md shadow-purple-600/25">
                <i class="fa-solid fa-cube text-xl"></i>
            </div>
            <div>
                <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight flex items-center gap-2">
                    Registrar Producto
                </h1>
                <p class="text-xs text-slate-500 font-medium">Completa la información para registrar un nuevo producto en el catálogo oficial.</p>
            </div>
        </div>
        
        <!-- Breadcrumb pill -->
        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white border border-stone-200/80 shadow-xs text-[11px] font-semibold text-slate-500 self-start sm:self-auto">
            <i class="fa-solid fa-circle text-[6px] text-purple-600"></i>
            <a href="{{ route('admin.products.index') }}" class="hover:text-purple-700 transition">Productos</a>
            <i class="fa-solid fa-chevron-right text-[8px] text-slate-300"></i>
            <span class="text-purple-700 font-bold">Registrar Producto</span>
        </div>
    </div>

    @if ($errors->any())
    <div class="mb-6 p-4 rounded-2xl bg-red-50 border border-red-200/80 shadow-sm animate-shake">
        <div class="flex items-start gap-3">
            <div class="w-8 h-8 rounded-xl bg-red-100 text-red-600 flex items-center justify-center shrink-0">
                <i class="fa-solid fa-circle-exclamation text-sm"></i>
            </div>
            <div>
                <h3 class="text-xs font-bold text-red-900">Se encontraron errores en el formulario:</h3>
                <ul class="mt-1 text-xs text-red-700 list-disc pl-4 space-y-0.5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    @endif

    <form id="createProductForm" action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
            
            <!-- ========================================== -->
            <!-- LEFT COLUMN (8 Columns)                   -->
            <!-- ========================================== -->
            <div class="lg:col-span-8 space-y-6">
                
                <!-- 1. INFORMACIÓN PRINCIPAL -->
                <section class="bg-white rounded-2xl shadow-xs border border-stone-200/70 p-6 transition-all hover:border-stone-300">
                    <div class="flex items-center justify-between mb-5">
                        <div class="flex items-center gap-3">
                            <span class="flex items-center justify-center w-7 h-7 rounded-full bg-[#7C3AED] text-white text-xs font-bold shadow-xs">1</span>
                            <h2 class="text-sm font-bold text-slate-900">Información Principal</h2>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <!-- Nombre del Producto -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                Nombre del Producto <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <i class="fa-solid fa-laptop absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                                <input type="text" 
                                       name="name" 
                                       x-model="name" 
                                       @input="updateLiveBarcodes()"
                                       class="w-full pl-9 pr-3.5 py-2.5 bg-stone-50/50 border border-stone-200 rounded-xl text-xs font-medium text-slate-800 placeholder-slate-400 focus:bg-white focus:ring-2 focus:ring-purple-500/20 focus:border-purple-600 transition" 
                                       placeholder="Ej. MacBook Pro 16&quot; M3 Max" 
                                       required>
                            </div>
                        </div>

                        <!-- Categoría y Subcategoría -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- Categoría -->
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                    Categoría <span class="text-red-500">*</span>
                                </label>
                                <div class="flex gap-2">
                                    <div class="relative flex-1">
                                        <i class="fa-regular fa-folder absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                                        <select name="category_id" 
                                                id="category_select"
                                                x-model="categoryId" 
                                                @change="handleCategoryChange($event)" 
                                                class="w-full pl-9 pr-8 py-2.5 bg-stone-50/50 border border-stone-200 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:ring-2 focus:ring-purple-500/20 focus:border-purple-600 transition appearance-none cursor-pointer" 
                                                required>
                                            <option value="">Seleccionar categoría</option>
                                            @foreach($categories as $cat)
                                                <option value="{{ $cat->id }}" data-name="{{ $cat->name }}">{{ $cat->name }}</option>
                                            @endforeach
                                        </select>
                                        <i class="fa-solid fa-chevron-down absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-[10px] pointer-events-none"></i>
                                    </div>
                                    <button type="button" 
                                            @click="openQuickAdd('categories', 'Categoría')" 
                                            class="w-10 h-10 shrink-0 flex items-center justify-center bg-purple-50 border border-purple-200 text-purple-700 rounded-xl hover:bg-purple-600 hover:text-white transition shadow-xs"
                                            title="Agregar nueva categoría">
                                        <i class="fa-solid fa-plus text-xs"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Subcategoría -->
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                    Subcategoría (Opcional)
                                </label>
                                <div class="flex gap-2">
                                    <div class="relative flex-1">
                                        <i class="fa-solid fa-sitemap absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                                        <select name="subcategory_id" 
                                                id="subcategory_select"
                                                x-model="subcategoryId"
                                                class="w-full pl-9 pr-8 py-2.5 bg-stone-50/50 border border-stone-200 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:ring-2 focus:ring-purple-500/20 focus:border-purple-600 transition appearance-none cursor-pointer">
                                            <option value="">Laptops Profesionales (Opcional)</option>
                                            @foreach($subcategories as $subcat)
                                                <option value="{{ $subcat->id }}">{{ $subcat->name }}</option>
                                            @endforeach
                                        </select>
                                        <i class="fa-solid fa-chevron-down absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-[10px] pointer-events-none"></i>
                                    </div>
                                    <button type="button" 
                                            @click="openQuickAdd('subcategories', 'Subcategoría')" 
                                            class="w-10 h-10 shrink-0 flex items-center justify-center bg-purple-50 border border-purple-200 text-purple-700 rounded-xl hover:bg-purple-600 hover:text-white transition shadow-xs"
                                            title="Agregar nueva subcategoría">
                                        <i class="fa-solid fa-plus text-xs"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Marca, Modelo y Modalidad Producto -->
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <!-- Marca -->
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                    Marca <span class="text-red-500">*</span>
                                </label>
                                <div class="flex gap-2">
                                    <div class="relative flex-1">
                                        <i class="fa-solid fa-shield-halved absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                                        <select name="brand_id" 
                                                id="brand_select"
                                                x-model="brandId" 
                                                @change="handleBrandChange($event)" 
                                                class="w-full pl-9 pr-8 py-2.5 bg-stone-50/50 border border-stone-200 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:ring-2 focus:ring-purple-500/20 focus:border-purple-600 transition appearance-none cursor-pointer" 
                                                required>
                                            <option value="">Seleccionar marca</option>
                                            @foreach($brands as $brand)
                                                <option value="{{ $brand->id }}" data-name="{{ $brand->name }}">{{ $brand->name }}</option>
                                            @endforeach
                                        </select>
                                        <i class="fa-solid fa-chevron-down absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-[10px] pointer-events-none"></i>
                                    </div>
                                    <button type="button" 
                                            @click="openQuickAdd('brands', 'Marca')" 
                                            class="w-10 h-10 shrink-0 flex items-center justify-center bg-purple-50 border border-purple-200 text-purple-700 rounded-xl hover:bg-purple-600 hover:text-white transition shadow-xs"
                                            title="Agregar nueva marca">
                                        <i class="fa-solid fa-plus text-xs"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Modelo -->
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                    Modelo <span class="text-red-500">*</span>
                                </label>
                                <div class="flex gap-2">
                                    <div class="relative flex-1">
                                        <i class="fa-solid fa-microchip absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                                        <select name="device_model_id" 
                                                id="model_select"
                                                x-model="modelId" 
                                                @change="handleModelChange($event)"
                                                class="w-full pl-9 pr-8 py-2.5 bg-stone-50/50 border border-stone-200 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:ring-2 focus:ring-purple-500/20 focus:border-purple-600 transition appearance-none cursor-pointer">
                                            <option value="">A2991 - M3V</option>
                                            @foreach($models as $model)
                                                <option value="{{ $model->id }}" data-name="{{ $model->name }}">{{ $model->name }}</option>
                                            @endforeach
                                        </select>
                                        <i class="fa-solid fa-chevron-down absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-[10px] pointer-events-none"></i>
                                    </div>
                                    <button type="button" 
                                            @click="openQuickAdd('models', 'Modelo')" 
                                            class="w-10 h-10 shrink-0 flex items-center justify-center bg-purple-50 border border-purple-200 text-purple-700 rounded-xl hover:bg-purple-600 hover:text-white transition shadow-xs"
                                            title="Agregar nuevo modelo">
                                        <i class="fa-solid fa-plus text-xs"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Modalidad Producto -->
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                    Producto <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <i class="fa-solid fa-box-archive absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                                    <select name="control_type" 
                                            x-model="controlType" 
                                            class="w-full pl-9 pr-8 py-2.5 bg-stone-50/50 border border-stone-200 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:ring-2 focus:ring-purple-500/20 focus:border-purple-600 transition appearance-none cursor-pointer" 
                                            required>
                                        <option value="Por Serie">Por Serie</option>
                                        <option value="Por Cantidad">Por Cantidad</option>
                                        <option value="Estándar">Estándar</option>
                                    </select>
                                    <i class="fa-solid fa-chevron-down absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-[10px] pointer-events-none"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Descripción del Producto -->
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <label class="block text-xs font-bold text-slate-700">
                                    Descripción del Producto <span class="text-red-500">*</span>
                                </label>
                                <div class="flex items-center gap-2">
                                    <span class="text-[10px] font-mono text-slate-400" x-text="(description ? description.length : 0) + ' caracteres'"></span>
                                    <button type="button" 
                                            @click="openAiModal('description')" 
                                            class="px-3 py-1 bg-purple-100 hover:bg-purple-200 text-purple-800 text-[11px] font-bold rounded-lg transition flex items-center gap-1.5 border border-purple-200/80 shadow-xs cursor-pointer">
                                        <i class="fa-solid fa-wand-magic-sparkles text-purple-600"></i> Generar con IA
                                    </button>
                                </div>
                            </div>
                            <textarea name="description" 
                                      x-model="description" 
                                      rows="3" 
                                      class="w-full px-3.5 py-2.5 bg-stone-50/50 border border-stone-200 rounded-xl text-xs text-slate-800 leading-relaxed placeholder-slate-400 focus:bg-white focus:ring-2 focus:ring-purple-500/20 focus:border-purple-600 transition resize-y font-sans" 
                                      placeholder="MacBook Pro 16 pulgadas con chip Apple M3 Max (CPU 16 núcleos, GPU 40 núcleos), 36 GB de memoria unificada y almacenamiento ultrarrápido SSD de 1 TB. Pantalla Liquid Retina XDR de 16.2&quot; con ProMotion a 120Hz en color Negro Espacial." 
                                      required></textarea>
                        </div>
                    </div>
                </section>

                <!-- 2. IDENTIFICACIÓN DEL PRODUCTO (TRIPLE SISTEMA) -->
                <section class="bg-white rounded-2xl shadow-xs border border-stone-200/70 p-6 transition-all hover:border-stone-300">
                    <div class="flex items-center justify-between mb-5 flex-wrap gap-2">
                        <div class="flex items-center gap-3">
                            <span class="flex items-center justify-center w-7 h-7 rounded-full bg-[#7C3AED] text-white text-xs font-bold shadow-xs">2</span>
                            <div>
                                <h2 class="text-sm font-bold text-slate-900">Identificación del Producto</h2>
                                <p class="text-[11px] text-slate-400">Manejo de códigos únicos de trazabilidad, stock y venta al cliente.</p>
                            </div>
                        </div>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-purple-50 text-purple-700 border border-purple-200 text-[11px] font-bold">
                            <i class="fa-solid fa-circle text-[6px]"></i> Triple Sistema de Identificación
                        </span>
                    </div>

                    <!-- 3 Cards Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        
                        <!-- CARD 1: CÓDIGO / SKU -->
                        <div class="bg-[#FAF9F6] border border-stone-200/80 rounded-2xl p-4 flex flex-col justify-between">
                            <div>
                                <div class="flex items-center justify-between mb-1">
                                    <h3 class="text-xs font-extrabold text-slate-800">1. CÓDIGO / SKU</h3>
                                    <span class="px-2 py-0.5 rounded-full bg-purple-100 text-purple-700 text-[10px] font-extrabold">Interno</span>
                                </div>
                                <p class="text-[10px] text-slate-500 mb-3 leading-tight">Identificador de inventario en almacén</p>
                                
                                <div class="relative flex items-center bg-white border border-stone-300 rounded-xl px-2.5 py-1.5 shadow-2xs mb-2">
                                    <i class="fa-solid fa-hashtag text-slate-400 text-xs mr-2"></i>
                                    <input type="text" 
                                           name="sku" 
                                           x-model="sku" 
                                           @input="updateLiveBarcodes()"
                                           class="w-full bg-transparent border-0 p-0 text-xs font-bold text-slate-900 focus:ring-0" 
                                           placeholder="SKU-APP-001">
                                    
                                    <div class="flex items-center gap-1 shrink-0 ml-1">
                                        <button type="button" 
                                                @click="regenerateSku()" 
                                                class="w-6 h-6 rounded-lg text-slate-400 hover:text-purple-600 hover:bg-purple-50 flex items-center justify-center transition" 
                                                title="Regenerar SKU">
                                            <i class="fa-solid fa-rotate text-[11px]"></i>
                                        </button>
                                        <button type="button" 
                                                @click="copyText(sku, 'SKU copiado')" 
                                                class="w-6 h-6 rounded-lg text-slate-400 hover:text-purple-600 hover:bg-purple-50 flex items-center justify-center transition" 
                                                title="Copiar SKU">
                                            <i class="fa-regular fa-copy text-[11px]"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="flex items-center gap-1.5 text-[10px] font-bold text-emerald-600">
                                    <i class="fa-solid fa-circle-check text-[11px]"></i>
                                    <span>Autogenerado algoritmo único</span>
                                </div>
                            </div>

                            <p class="text-[9px] text-slate-400 mt-4 leading-tight border-t border-stone-200/60 pt-2">
                                Generado automáticamente por el sistema con algoritmo de unidireccional.
                            </p>
                        </div>

                        <!-- CARD 2: CÓDIGO DE BARRAS (EAN-13) -->
                        <div class="bg-[#FAF9F6] border border-stone-200/80 rounded-2xl p-4 flex flex-col justify-between">
                            <div>
                                <div class="flex items-center justify-between mb-1">
                                    <h3 class="text-xs font-extrabold text-slate-800">2. CÓDIGO DE BARRAS</h3>
                                    <span class="px-2 py-0.5 rounded-full bg-purple-100 text-purple-700 text-[10px] font-extrabold">EAN-13 / UPC</span>
                                </div>
                                <p class="text-[10px] text-slate-500 mb-2 leading-tight">Para escáner de caja o empaque original</p>

                                <div class="bg-white border border-stone-200 rounded-xl p-2.5 shadow-2xs mb-2">
                                    <div class="flex items-center justify-between mb-1.5">
                                        <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded bg-emerald-50 text-emerald-700 text-[9px] font-bold border border-emerald-200">
                                            <i class="fa-solid fa-check text-[8px]"></i> EAN-13 Verificado
                                        </span>
                                        <span class="text-[9px] text-slate-400">Folio/Garantía</span>
                                    </div>

                                    <!-- Visual Barcode Display -->
                                    <div class="bg-white flex flex-col items-center justify-center py-1 overflow-hidden">
                                        <svg id="card-barcode-svg" class="max-w-full h-11"></svg>
                                        <span class="text-[10px] font-mono font-bold tracking-widest text-slate-800 mt-0.5" x-text="code || '7751234567890'"></span>
                                    </div>

                                    <!-- Hidden code input submitting with form -->
                                    <input type="hidden" name="code" :value="code">

                                    <!-- Bottom Scanner and Input Switcher Buttons -->
                                    <div class="grid grid-cols-2 gap-1.5 mt-2">
                                        <button type="button" 
                                                @click="openScannerModal()" 
                                                class="px-2 py-1.5 bg-[#7C3AED] hover:bg-[#6D28D9] text-white text-[10px] font-bold rounded-lg flex items-center justify-center gap-1.5 transition shadow-xs cursor-pointer">
                                            <i class="fa-solid fa-camera"></i> Cámara
                                        </button>
                                        <button type="button" 
                                                @click="promptManualCode()" 
                                                class="px-2 py-1.5 bg-stone-100 hover:bg-stone-200 text-slate-700 text-[10px] font-bold rounded-lg flex items-center justify-center gap-1 transition cursor-pointer">
                                            <i class="fa-solid fa-keyboard"></i> Escribir
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <p class="text-[9px] text-slate-400 mt-2 leading-tight border-t border-stone-200/60 pt-2">
                                Opcional. Código del fabricante en empaque o caja original para pistoleo rápido.
                            </p>
                        </div>

                        <!-- CARD 3: N° DE SERIE (S/N) -->
                        <div class="bg-[#FAF9F6] border border-stone-200/80 rounded-2xl p-4 flex flex-col justify-between">
                            <div>
                                <div class="flex items-center justify-between mb-1">
                                    <h3 class="text-xs font-extrabold text-slate-800">3. N° DE SERIE (S/N)</h3>
                                    <span class="px-2 py-0.5 rounded-full bg-slate-200 text-slate-700 text-[10px] font-extrabold">Físico</span>
                                </div>
                                <p class="text-[10px] text-slate-500 mb-3 leading-tight">Identificador del chasis / placa madre</p>

                                <div class="relative flex items-center bg-white border border-stone-300 rounded-xl px-2.5 py-1.5 shadow-2xs mb-2">
                                    <i class="fa-solid fa-barcode text-slate-400 text-xs mr-2"></i>
                                    <input type="text" 
                                           name="serial_number" 
                                           x-model="serialNumber" 
                                           class="w-full bg-transparent border-0 p-0 text-xs font-bold text-slate-900 focus:ring-0" 
                                           placeholder="PF-XXXXXX">
                                </div>

                                <div class="flex items-center justify-between text-[10px]">
                                    <span class="text-slate-400">Individual por equipo físico</span>
                                    <span class="font-bold text-purple-600 hover:underline cursor-pointer">Verificar garantía</span>
                                </div>
                            </div>

                            <p class="text-[9px] text-slate-400 mt-4 leading-tight border-t border-stone-200/60 pt-2">
                                Solo para laptops, impresoras, monitores, celulares u otros equipos con S/N.
                            </p>
                        </div>

                    </div>

                    <!-- Business Rule Alert Bar -->
                    <div class="mt-4 p-3 bg-pink-50/70 border border-pink-200/80 rounded-xl flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                        <div class="flex items-center gap-2.5">
                            <div class="w-5 h-5 rounded-full bg-pink-200 text-pink-700 flex items-center justify-center shrink-0">
                                <i class="fa-solid fa-info text-[10px]"></i>
                            </div>
                            <p class="text-[11px] text-slate-700">
                                <span class="font-bold text-slate-900">Regla de negocio:</span> 
                                SKU = Identificador interno | EAN = Código de barras (caja/escáner) | Serie = Identificador físico del chasis
                            </p>
                        </div>
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md bg-white border border-emerald-300 text-emerald-700 text-[10px] font-bold shrink-0 self-start sm:self-auto">
                            <i class="fa-solid fa-check text-[9px]"></i> Listo para inventario
                        </span>
                    </div>
                </section>

                <!-- 3. PRECIOS E INVENTARIO -->
                <section class="bg-white rounded-2xl shadow-xs border border-stone-200/70 p-6 transition-all hover:border-stone-300">
                    <div class="flex items-center gap-3 mb-5">
                        <span class="flex items-center justify-center w-7 h-7 rounded-full bg-[#7C3AED] text-white text-xs font-bold shadow-xs">3</span>
                        <h2 class="text-sm font-bold text-slate-900">Precios e Inventario</h2>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                        <!-- Precio de Venta -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                Precio de Venta <span class="text-red-500">*</span>
                            </label>
                            <div class="relative flex items-center">
                                <span class="absolute left-3.5 text-slate-400 font-bold text-xs pointer-events-none">S/</span>
                                <input type="number" 
                                       step="0.01" 
                                       name="price" 
                                       x-model="price" 
                                       @input="calculateMinPrice()" 
                                       class="w-full pl-8 pr-3.5 py-2.5 bg-stone-50/50 border border-stone-200 rounded-xl text-xs font-bold text-slate-900 focus:bg-white focus:ring-2 focus:ring-purple-500/20 focus:border-purple-600 transition" 
                                       placeholder="12,099.00" 
                                       required>
                            </div>
                        </div>

                        <!-- Precio Mínimo -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                Precio Mínimo <span class="text-red-500">*</span>
                            </label>
                            <div class="relative flex items-center">
                                <span class="absolute left-3.5 text-slate-400 font-bold text-xs pointer-events-none">S/</span>
                                <input type="number" 
                                       step="0.01" 
                                       name="min_price" 
                                       x-model="minPrice" 
                                       class="w-full pl-8 pr-3.5 py-2.5 bg-stone-50/50 border border-stone-200 rounded-xl text-xs font-bold text-slate-900 focus:bg-white focus:ring-2 focus:ring-purple-500/20 focus:border-purple-600 transition" 
                                       placeholder="11,800.00" 
                                       required>
                            </div>
                        </div>

                        <!-- Stock Actual -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                Stock Actual <span class="text-red-500">*</span>
                            </label>
                            <div class="relative flex items-center">
                                <i class="fa-solid fa-box absolute left-3.5 text-slate-400 text-xs pointer-events-none"></i>
                                <input type="number" 
                                       name="stock" 
                                       x-model="stock" 
                                       class="w-full pl-9 pr-3.5 py-2.5 bg-stone-50/50 border border-stone-200 rounded-xl text-xs font-bold text-slate-900 focus:bg-white focus:ring-2 focus:ring-purple-500/20 focus:border-purple-600 transition" 
                                       placeholder="10" 
                                       required>
                            </div>
                        </div>

                        <!-- Estado -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                Estado <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <select name="state" 
                                        x-model="state" 
                                        class="w-full pl-3.5 pr-8 py-2.5 bg-stone-50/50 border border-stone-200 rounded-xl text-xs font-bold text-emerald-700 focus:bg-white focus:ring-2 focus:ring-purple-500/20 focus:border-purple-600 transition appearance-none cursor-pointer" 
                                        required>
                                    <option value="Activo">✓ Activo</option>
                                    <option value="Agotado">Agotado</option>
                                    <option value="Próximamente">Próximamente</option>
                                </select>
                                <i class="fa-solid fa-chevron-down absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-[10px] pointer-events-none"></i>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- 4. ESPECIFICACIONES TÉCNICAS -->
                <section class="bg-white rounded-2xl shadow-xs border border-stone-200/70 p-6 transition-all hover:border-stone-300">
                    <div class="flex items-center gap-3 mb-5">
                        <span class="flex items-center justify-center w-7 h-7 rounded-full bg-[#7C3AED] text-white text-xs font-bold shadow-xs">4</span>
                        <h2 class="text-sm font-bold text-slate-900">Especificaciones Técnicas</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-start">
                        
                        <!-- Table / Rows (7 cols) -->
                        <div class="md:col-span-7">
                            <div class="grid grid-cols-12 gap-2 mb-2 px-1 text-[11px] font-bold text-slate-500">
                                <div class="col-span-5">Característica</div>
                                <div class="col-span-6">Valor</div>
                                <div class="col-span-1 text-center">Acción</div>
                            </div>

                            <div class="space-y-2 mb-4">
                                <template x-for="(spec, index) in specs" :key="index">
                                    <div class="grid grid-cols-12 gap-2 items-center group">
                                        <div class="col-span-5 relative">
                                            <i class="fa-solid fa-grip-vertical absolute left-2.5 top-1/2 -translate-y-1/2 text-slate-300 text-[10px] cursor-grab"></i>
                                            <input type="text" 
                                                   list="commonSpecsDatalist" 
                                                   x-model="spec.name" 
                                                   :name="'specs['+index+'][name]'" 
                                                   class="w-full pl-7 pr-2.5 py-2 bg-stone-50/50 border border-stone-200 rounded-xl text-xs font-semibold text-slate-800 focus:bg-white focus:ring-1 focus:ring-purple-600 focus:border-purple-600 transition" 
                                                   placeholder="Procesador">
                                        </div>
                                        <div class="col-span-6">
                                            <input type="text" 
                                                   x-model="spec.value" 
                                                   :name="'specs['+index+'][value]'" 
                                                   @input="updateLiveBarcodes()"
                                                   class="w-full px-3 py-2 bg-stone-50/50 border border-stone-200 rounded-xl text-xs text-slate-800 focus:bg-white focus:ring-1 focus:ring-purple-600 focus:border-purple-600 transition" 
                                                   placeholder="Apple M3 Max (16-Core CPU)">
                                        </div>
                                        <div class="col-span-1 text-center">
                                            <button type="button" 
                                                    @click="removeSpec(index)" 
                                                    class="w-8 h-8 rounded-lg text-rose-400 hover:text-rose-600 hover:bg-rose-50 flex items-center justify-center transition mx-auto"
                                                    title="Eliminar fila">
                                                <i class="fa-regular fa-trash-can text-xs"></i>
                                            </button>
                                        </div>
                                    </div>
                                </template>

                                <datalist id="commonSpecsDatalist">
                                    <option value="Procesador">
                                    <option value="Memoria RAM">
                                    <option value="Almacenamiento">
                                    <option value="Pantalla">
                                    <option value="Sistema Operativo">
                                    <option value="Tarjeta Gráfica">
                                    <option value="Color">
                                    <option value="Batería">
                                    <option value="Peso">
                                    <option value="Conectividad">
                                </datalist>
                            </div>

                            <div class="flex items-center gap-2.5 pt-2">
                                <button type="button" 
                                        @click="addSpec()" 
                                        class="px-3.5 py-2 bg-[#7C3AED] hover:bg-[#6D28D9] text-white text-xs font-bold rounded-xl transition flex items-center gap-2 shadow-xs cursor-pointer">
                                    <i class="fa-solid fa-plus text-[10px]"></i> Agregar especificación
                                </button>
                                <button type="button" 
                                        @click="openAiModal('specs')" 
                                        class="px-3.5 py-2 bg-purple-100 hover:bg-purple-200 text-purple-800 text-xs font-bold rounded-xl transition flex items-center gap-2 border border-purple-200 shadow-xs cursor-pointer">
                                    <i class="fa-solid fa-wand-magic-sparkles text-purple-600 text-xs"></i> Llenar con IA
                                </button>
                            </div>
                        </div>

                        <!-- Right Info Box (5 cols) -->
                        <div class="md:col-span-5">
                            <div class="bg-purple-50/50 border border-purple-200/80 rounded-2xl p-4">
                                <h3 class="text-xs font-bold text-purple-900 flex items-center gap-2 mb-3">
                                    <i class="fa-regular fa-lightbulb text-purple-600"></i> Ejemplos de especificaciones
                                </h3>
                                <ul class="text-[11px] text-slate-600 space-y-2">
                                    <li><span class="font-bold text-slate-800">Procesador:</span> Intel Core, AMD Ryzen, Apple M3</li>
                                    <li><span class="font-bold text-slate-800">Memoria RAM:</span> 16GB, 32GB, 64GB DDR5</li>
                                    <li><span class="font-bold text-slate-800">Almacenamiento:</span> SSD NVMe, HDD, M.2</li>
                                    <li><span class="font-bold text-slate-800">Pantalla:</span> FHD IPS, Retina XDR, OLED 120Hz</li>
                                    <li><span class="font-bold text-slate-800">Sistema Operativo:</span> Windows 11, macOS, Linux</li>
                                    <li><span class="font-bold text-slate-800">Gráficos:</span> Integrados / RTX 4080 Dedicada</li>
                                </ul>
                            </div>
                        </div>

                    </div>
                </section>

            </div>

            <!-- ========================================== -->
            <!-- RIGHT COLUMN (4 Columns)                  -->
            <!-- ========================================== -->
            <div class="lg:col-span-4 space-y-6">
                
                <!-- 5. IMÁGENES DEL PRODUCTO -->
                <section class="bg-white rounded-2xl shadow-xs border border-stone-200/70 p-5 transition-all hover:border-stone-300">
                    <div class="flex items-center gap-3 mb-4">
                        <span class="flex items-center justify-center w-7 h-7 rounded-full bg-[#7C3AED] text-white text-xs font-bold shadow-xs">5</span>
                        <h2 class="text-sm font-bold text-slate-900">Imágenes del Producto</h2>
                    </div>

                    <!-- Drag & Drop Box with Pink/Purple Accent -->
                    <div class="border-2 border-dashed border-purple-200 bg-purple-50/30 rounded-2xl p-6 text-center hover:bg-purple-50/60 hover:border-purple-400 transition cursor-pointer mb-3.5 group"
                         @click="triggerImageSelect()"
                         @dragover.prevent="dragOver = true"
                         @dragleave.prevent="dragOver = false"
                         @drop.prevent="handleImageDrop($event)"
                         :class="dragOver ? 'bg-purple-100 border-purple-500 scale-[0.99]' : ''">
                        
                        <div class="w-12 h-12 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center mx-auto mb-2.5 group-hover:scale-110 transition">
                            <i class="fa-solid fa-cloud-arrow-up text-xl"></i>
                        </div>
                        <p class="text-xs font-bold text-slate-800">Arrastra y suelta las imágenes aquí</p>
                        <p class="text-[10px] text-slate-500 mt-0.5">o haz clic para seleccionar archivos</p>
                        <p class="text-[9px] font-medium text-slate-400 mt-2">Formatos: JPG, PNG, WEBP (Hasta 8 imágenes)</p>
                    </div>

                    <!-- URL input bar -->
                    <div class="flex gap-2 mb-3.5">
                        <input type="url" 
                               x-model="imageUrlInput" 
                               placeholder="Pegar URL de la imagen..." 
                               class="flex-1 px-3 py-2 bg-stone-50/50 border border-stone-200 rounded-xl text-xs focus:bg-white focus:ring-1 focus:ring-purple-600 focus:border-purple-600 transition">
                        <button type="button" 
                                @click="addImageFromUrl()" 
                                class="px-3.5 py-2 bg-[#7C3AED] hover:bg-[#6D28D9] text-white text-xs font-bold rounded-xl transition flex items-center gap-1.5 shadow-xs shrink-0 cursor-pointer">
                            <i class="fa-solid fa-plus text-[10px]"></i> Agregar
                        </button>
                    </div>

                    <!-- Hidden file input container -->
                    <div id="hidden-file-inputs" class="hidden">
                        <template x-for="item in imagesList" :key="item.id">
                            <input type="file" :name="'images_files['+item.id+']'" :id="'file_inp_'+item.id" accept="image/*" @change="handleFileSelected($event, item)">
                        </template>
                    </div>

                    <!-- Thumbnails Row -->
                    <div class="grid grid-cols-4 gap-2.5">
                        <template x-for="(img, idx) in imagesList" :key="img.id">
                            <div class="relative aspect-square rounded-xl border border-stone-200 bg-stone-50 overflow-hidden group">
                                <span x-show="idx === 0" class="absolute top-1 left-1 bg-[#7C3AED] text-white text-[8px] font-black px-1.5 py-0.5 rounded-md z-10 shadow-xs">Principal</span>
                                <img :src="img.preview" class="w-full h-full object-cover">
                                <button type="button" 
                                        @click.stop="removeImage(img.id)" 
                                        class="absolute top-1 right-1 w-5 h-5 bg-white/90 hover:bg-white text-rose-500 rounded-full flex items-center justify-center shadow-xs opacity-0 group-hover:opacity-100 transition"
                                        title="Eliminar imagen">
                                    <i class="fa-solid fa-xmark text-[9px]"></i>
                                </button>
                            </div>
                        </template>

                        <!-- Add Slots placeholders -->
                        <template x-for="n in Math.max(0, 4 - imagesList.length)" :key="'empty-'+n">
                            <button type="button" 
                                    @click="triggerImageSelect()" 
                                    class="aspect-square rounded-xl border border-dashed border-stone-300 bg-stone-50/60 hover:bg-purple-50/50 hover:border-purple-300 flex items-center justify-center text-slate-400 hover:text-purple-600 transition cursor-pointer">
                                <i class="fa-solid fa-plus text-xs"></i>
                            </button>
                        </template>
                    </div>
                </section>

                <!-- 6. GARANTÍA -->
                <section class="bg-white rounded-2xl shadow-xs border border-stone-200/70 p-5 transition-all hover:border-stone-300">
                    <div class="flex items-center gap-3 mb-4">
                        <span class="flex items-center justify-center w-7 h-7 rounded-full bg-[#7C3AED] text-white text-xs font-bold shadow-xs">6</span>
                        <h2 class="text-sm font-bold text-slate-900">Garantía</h2>
                    </div>

                    <div class="relative">
                        <i class="fa-solid fa-shield-halved absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                        <select name="warranty" 
                                x-model="warranty" 
                                class="w-full pl-9 pr-8 py-2.5 bg-stone-50/50 border border-stone-200 rounded-xl text-xs font-bold text-slate-800 focus:bg-white focus:ring-2 focus:ring-purple-500/20 focus:border-purple-600 transition appearance-none cursor-pointer">
                            <option value="12 meses (Garantía Oficial de Marca)">12 meses (Garantía Oficial de Marca)</option>
                            <option value="24 meses (Garantía Oficial)">24 meses (Garantía Oficial)</option>
                            <option value="36 meses (Garantía Extendida)">36 meses (Garantía Extendida)</option>
                            <option value="6 meses (Garantía de Tienda)">6 meses (Garantía de Tienda)</option>
                            <option value="Sin garantía">Sin garantía</option>
                        </select>
                        <i class="fa-solid fa-chevron-down absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-[10px] pointer-events-none"></i>
                    </div>
                </section>

                <!-- 7. OPCIONES DE VISUALIZACIÓN -->
                <section class="bg-white rounded-2xl shadow-xs border border-stone-200/70 p-5 transition-all hover:border-stone-300">
                    <div class="flex items-center gap-3 mb-4">
                        <span class="flex items-center justify-center w-7 h-7 rounded-full bg-[#7C3AED] text-white text-xs font-bold shadow-xs">7</span>
                        <h2 class="text-sm font-bold text-slate-900">Opciones de Visualización</h2>
                    </div>

                    <div class="space-y-3.5">
                        <!-- Producto activo -->
                        <label class="flex items-center justify-between cursor-pointer group">
                            <div class="flex items-center gap-2.5 text-xs font-bold text-slate-800">
                                <i class="fa-solid fa-circle-check text-purple-600"></i>
                                <span>Producto activo</span>
                            </div>
                            <input type="checkbox" name="status" value="1" x-model="isActive" class="w-4 h-4 rounded text-purple-600 focus:ring-purple-500 border-stone-300 cursor-pointer">
                        </label>

                        <!-- Producto nuevo -->
                        <label class="flex items-center justify-between cursor-pointer group">
                            <div class="flex items-center gap-2.5 text-xs font-bold text-slate-800">
                                <i class="fa-solid fa-award text-amber-500"></i>
                                <span>Producto nuevo (Badge Nuevo)</span>
                            </div>
                            <input type="checkbox" name="is_new" value="1" x-model="isNew" class="w-4 h-4 rounded text-purple-600 focus:ring-purple-500 border-stone-300 cursor-pointer">
                        </label>

                        <!-- Oferta especial -->
                        <label class="flex items-center justify-between cursor-pointer group">
                            <div class="flex items-center gap-2.5 text-xs font-bold text-slate-800">
                                <i class="fa-solid fa-percent text-rose-500"></i>
                                <span>Oferta especial</span>
                            </div>
                            <input type="checkbox" name="is_offer" value="1" x-model="isOffer" class="w-4 h-4 rounded text-purple-600 focus:ring-purple-500 border-stone-300 cursor-pointer">
                        </label>

                        <!-- Destacar en Inicio -->
                        <label class="flex items-center justify-between cursor-pointer group">
                            <div class="flex items-center gap-2.5 text-xs font-bold text-slate-800">
                                <i class="fa-solid fa-star text-amber-400"></i>
                                <span>Destacar en Inicio</span>
                            </div>
                            <input type="checkbox" name="is_featured" value="1" x-model="isFeatured" class="w-4 h-4 rounded text-purple-600 focus:ring-purple-500 border-stone-300 cursor-pointer">
                        </label>
                    </div>
                </section>

                <!-- 8. RESUMEN DEL PRODUCTO -->
                <section class="bg-white rounded-2xl shadow-xs border border-stone-200/70 p-5 transition-all hover:border-stone-300">
                    <div class="flex items-center gap-3 mb-4">
                        <span class="flex items-center justify-center w-7 h-7 rounded-full bg-[#7C3AED] text-white text-xs font-bold shadow-xs">8</span>
                        <h2 class="text-sm font-bold text-slate-900">Resumen del Producto</h2>
                    </div>

                    <div class="flex items-center gap-3.5 bg-stone-50/70 border border-stone-200/70 rounded-2xl p-3.5">
                        <div class="w-16 h-16 rounded-xl bg-purple-50 border border-purple-200/80 flex items-center justify-center overflow-hidden shrink-0">
                            <template x-if="imagesList.length > 0">
                                <img :src="imagesList[0].preview" class="w-full h-full object-cover">
                            </template>
                            <template x-if="imagesList.length === 0">
                                <i class="fa-solid fa-display text-purple-600 text-xl"></i>
                            </template>
                        </div>
                        <div class="min-w-0 flex-1">
                            <h3 class="text-xs font-bold text-slate-900 truncate" x-text="name || 'MacBook Pro 16&quot; M3 Max'"></h3>
                            <p class="text-[10px] text-slate-500 truncate mt-0.5" x-text="(brandName || 'Apple') + ' ' + (specs[1]?.value || '36GB') + ' ' + (specs[2]?.value || '1TB SSD')"></p>
                            <div class="flex items-center gap-2 mt-1.5">
                                <span class="px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800 text-[9px] font-extrabold" x-show="isActive">Activo</span>
                                <span class="text-xs font-black text-slate-900" x-text="price ? 'S/ ' + formatPrice(price) : 'S/ 12,099.00'"></span>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- DARK PURPLE CARD: GENERACIÓN AUTOMÁTICA AL GUARDAR -->
                <div class="bg-[#1C0D36] text-white rounded-3xl p-5 shadow-xl border border-purple-900/60 relative overflow-hidden">
                    <div class="absolute -right-8 -top-8 w-28 h-28 bg-purple-600/20 rounded-full blur-2xl pointer-events-none"></div>

                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 rounded-lg bg-purple-600/40 text-purple-300 flex items-center justify-center text-xs">
                                <i class="fa-solid fa-shield-halved"></i>
                            </div>
                            <span class="text-[11px] font-black uppercase tracking-wider text-purple-100">Generación Automática al Guardar</span>
                        </div>
                        <span class="px-2 py-0.5 rounded-full bg-purple-700/60 text-purple-200 text-[9px] font-black tracking-wider uppercase border border-purple-500/40">
                            Auto-ready
                        </span>
                    </div>

                    <div class="grid grid-cols-2 gap-2 mb-4">
                        <div class="bg-purple-950/60 border border-purple-800/40 rounded-xl p-2 flex items-center gap-2 text-[10px] font-semibold text-purple-200">
                            <i class="fa-solid fa-check text-emerald-400 text-xs shrink-0"></i>
                            <span class="truncate">SKU Interno (<span x-text="sku"></span>)</span>
                        </div>
                        <div class="bg-purple-950/60 border border-purple-800/40 rounded-xl p-2 flex items-center gap-2 text-[10px] font-semibold text-purple-200">
                            <i class="fa-solid fa-check text-emerald-400 text-xs shrink-0"></i>
                            <span class="truncate">Código QR (URL / Catálogo)</span>
                        </div>
                        <div class="bg-purple-950/60 border border-purple-800/40 rounded-xl p-2 flex items-center gap-2 text-[10px] font-semibold text-purple-200">
                            <i class="fa-solid fa-check text-emerald-400 text-xs shrink-0"></i>
                            <span class="truncate">Código Code-128 Interno</span>
                        </div>
                        <div class="bg-purple-950/60 border border-purple-800/40 rounded-xl p-2 flex items-center gap-2 text-[10px] font-semibold text-purple-200">
                            <i class="fa-solid fa-check text-emerald-400 text-xs shrink-0"></i>
                            <span class="truncate">Etiqueta Térmica lista</span>
                        </div>
                    </div>

                    <p class="text-[10px] text-purple-300/80 leading-relaxed">
                        Al guardar este registro, el sistema vinculará la serie, almacenará el código de barras y dejará lista la plantilla para impresión térmica.
                    </p>
                </div>

            </div>

        </div>

        <!-- STICKY BOTTOM ACTIONS BAR -->
        <div class="fixed bottom-0 left-0 right-0 z-40 bg-white/95 backdrop-blur-md border-t border-stone-200 py-3.5 px-4 sm:px-8 shadow-lg">
            <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-3">
                <div class="flex items-center gap-2 text-xs font-bold text-slate-700">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span>Marcador: Listo para registrar</span>
                </div>

                <div class="flex items-center gap-2.5 w-full sm:w-auto justify-end">
                    <a href="{{ route('admin.products.index') }}" 
                       class="px-4 py-2 bg-white border border-stone-200 hover:bg-stone-50 text-slate-700 text-xs font-bold rounded-xl transition shadow-2xs">
                        <i class="fa-solid fa-xmark mr-1"></i> Cancelar
                    </a>

                    <!-- Button to open Thermal Label Modal (Image 2) -->
                    <button type="button" 
                            @click="openThermalModal()" 
                            class="px-4 py-2 bg-purple-50 hover:bg-purple-100 text-purple-700 border border-purple-200 text-xs font-bold rounded-xl transition shadow-2xs flex items-center gap-1.5 cursor-pointer">
                        <i class="fa-solid fa-tag"></i> Vista previa de etiqueta térmica
                    </button>

                    <!-- Save Submit Button -->
                    <button type="submit" 
                            class="px-5 py-2 bg-[#7C3AED] hover:bg-[#6D28D9] text-white text-xs font-bold rounded-xl transition shadow-md shadow-purple-600/30 flex items-center gap-2 cursor-pointer">
                        <i class="fa-solid fa-floppy-disk"></i> Guardar Producto
                    </button>
                </div>
            </div>
        </div>

    </form>

    <!-- ============================================================== -->
    <!-- MODAL 2: ETIQUETA DEL PRODUCTO / IMPRESIÓN TÉRMICA (IMAGE 2) -->
    <!-- ============================================================== -->
    <template x-teleport="body">
        <div x-show="thermalModalOpen" 
             x-cloak 
             class="fixed inset-0 z-[300] flex items-center justify-center bg-slate-950/75 backdrop-blur-sm p-4 overflow-y-auto"
             @keydown.window.escape="thermalModalOpen = false">
            
            <div @click.away="thermalModalOpen = false" 
                 class="bg-white rounded-3xl shadow-2xl w-full max-w-xl p-6 border border-stone-200 relative my-auto">
                
                <!-- Modal Header -->
                <div class="flex items-start justify-between pb-3 border-b border-stone-100 mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-purple-100 text-purple-700 flex items-center justify-center text-lg shadow-xs">
                            <i class="fa-solid fa-tag"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-extrabold text-slate-900">Etiqueta del Producto</h3>
                            <p class="text-xs text-slate-500">Configuración y previsualización de impresión térmica para rotulado de stock</p>
                        </div>
                    </div>
                    <button type="button" @click="thermalModalOpen = false" class="text-slate-400 hover:text-slate-600 p-1.5 rounded-lg hover:bg-stone-100 transition">
                        <i class="fa-solid fa-xmark text-sm"></i>
                    </button>
                </div>

                <!-- Product Summary Header Card inside Modal -->
                <div class="bg-[#FAF9F5] border border-stone-200/80 rounded-2xl p-3.5 mb-4">
                    <div class="flex items-center justify-between mb-1">
                        <div class="flex items-center gap-2">
                            <span class="px-2 py-0.5 rounded bg-purple-100 text-purple-700 text-[10px] font-black uppercase tracking-wider" x-text="brandName || 'APPLE'"></span>
                            <span class="text-xs font-extrabold text-slate-900" x-text="name || 'MacBook Pro 16&quot; M3 Max'"></span>
                        </div>
                        <span class="text-xs font-mono font-bold text-slate-700" x-text="'SKU: ' + (sku || 'SKU-APP-001')"></span>
                    </div>
                    <div class="flex items-center justify-between text-[10px] text-slate-500">
                        <span x-text="'Configuración: ' + (specs[1]?.value || '36GB Memoria') + ' + ' + (specs[2]?.value || '1TB SSD')"></span>
                        <span x-text="'S/N: ' + (serialNumber || 'PF4XXXXXX')"></span>
                    </div>
                </div>

                <!-- Section Title & Dimensions Badge -->
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-1.5 text-xs font-bold text-slate-700">
                        <i class="fa-regular fa-eye text-purple-600"></i> Previsualización Falsa de Rotulador Térmico
                    </div>
                    <span class="px-2.5 py-0.5 rounded-full bg-stone-100 text-slate-600 text-[10px] font-mono font-bold border border-stone-200" x-text="selectedLabelSize + ' (300 DPI)'"></span>
                </div>

                <!-- THE REAL THERMAL STICKER (Preview & Printable) -->
                <div id="thermal-print-area" class="border-2 border-slate-900 rounded-2xl p-4 bg-white shadow-sm mb-4 mx-auto text-black font-sans max-w-md">
                    
                    <!-- Sticker Top Bar -->
                    <div class="flex items-center justify-between pb-1.5 border-b border-black">
                        <div class="flex items-center gap-1.5">
                            <span class="bg-black text-white px-1.5 py-0.5 text-[10px] font-black uppercase tracking-wider">PORTÁTILES PERÚ</span>
                            <span class="text-[9px] font-extrabold tracking-wider">ROTULADO OFICIAL</span>
                        </div>
                        <div class="flex items-center gap-1">
                            <span class="border border-black px-1 text-[8px] font-bold">OFICIAL</span>
                            <span class="text-[8px] font-mono font-bold">LOTE 2026-10</span>
                        </div>
                    </div>

                    <!-- Sticker Product Title -->
                    <div class="py-1.5 text-center border-b border-black">
                        <h4 class="text-xs font-black uppercase tracking-tight" x-text="(name || 'MacBook Pro 16&quot; M3 Max') + ' (' + (specs[1]?.value || '36GB') + ' / ' + (specs[2]?.value || '1TB') + ')'"></h4>
                    </div>

                    <!-- Sticker Middle: QR + Metadata -->
                    <div class="py-2 grid grid-cols-12 gap-2 items-center border-b border-black">
                        <div class="col-span-4 flex flex-col items-center justify-center">
                            <div id="modal-qr-container" class="w-16 h-16 flex items-center justify-center bg-white p-0.5 border border-slate-300"></div>
                            <span class="text-[7px] font-mono text-slate-600 mt-0.5">ESCANEAR PARA WEB</span>
                        </div>
                        <div class="col-span-8 text-[9px] font-bold space-y-0.5 leading-tight">
                            <div class="flex justify-between">
                                <span>MARCA: <span x-text="brandName || 'APPLE'"></span></span>
                                <span>COLOR: <span>SPACE BLACK</span></span>
                            </div>
                            <div class="flex justify-between">
                                <span>PROCESADOR: <span x-text="specs[0]?.value || 'M3 MAX 16-CPU'"></span></span>
                                <span>ALMAC.: <span x-text="specs[2]?.value || '1TB PCIE SSD'"></span></span>
                            </div>
                            <div class="flex justify-between pt-1 border-t border-dashed border-slate-300 text-[10px]">
                                <span>Garantía: <span x-text="warranty ? warranty.split(' ')[0] + ' ' + warranty.split(' ')[1] : '12 MESES'"></span></span>
                                <span class="font-black text-black" x-text="'PVP: S/ ' + (price ? formatPrice(price) : '12,099.00')"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Sticker Code-128 Barcode -->
                    <div class="py-2 text-center border-b border-black flex flex-col items-center justify-center">
                        <svg id="modal-code128-svg" class="max-w-full h-9"></svg>
                        <div class="flex items-center justify-center gap-2 mt-0.5">
                            <span class="text-[9px] font-mono font-bold" x-text="'SKU: ' + (sku || 'SKU-APP-001')"></span>
                            <span class="bg-black text-white text-[7px] px-1 py-0.2 font-black uppercase">VERIFICADO</span>
                        </div>
                    </div>

                    <!-- Sticker EAN-13 Barcode -->
                    <div class="pt-1.5 flex items-center justify-between">
                        <div class="flex-1 flex flex-col items-center">
                            <svg id="modal-ean13-svg" class="max-w-full h-8"></svg>
                            <span class="text-[8px] font-mono font-bold" x-text="code || '7751234567890'"></span>
                        </div>
                        <div class="text-right text-[8px] font-mono pl-2">
                            <div class="font-bold" x-text="'L/N: ' + (serialNumber || 'PF4XXXXXX')"></div>
                            <div class="text-slate-500">Etiqueta Inventario</div>
                        </div>
                    </div>

                </div>

                <!-- Yellow Auto Suggestion Banner -->
                <div class="p-3 bg-amber-50 border border-amber-200/80 rounded-2xl mb-4 flex items-start gap-2.5">
                    <i class="fa-solid fa-bolt text-amber-500 mt-0.5 text-xs"></i>
                    <div class="text-[11px] text-amber-900 leading-tight">
                        <span class="font-bold">Tamaño sugerido automáticamente: Grande (100x60mm)</span><br>
                        <span class="text-amber-700 text-[10px]">Determinado por la categoría física de este producto: <span x-text="categoryName || 'Laptops'"></span>.</span>
                    </div>
                </div>

                <!-- Size Selection Cards -->
                <div class="mb-4">
                    <label class="block text-xs font-bold text-slate-700 mb-2">Formato de Rollo Térmico:</label>
                    <div class="grid grid-cols-3 gap-2.5">
                        
                        <!-- Option 1: Pequeña -->
                        <div @click="selectedLabelSize = '50 × 30 mm'" 
                             :class="selectedLabelSize === '50 × 30 mm' ? 'border-[#7C3AED] ring-2 ring-purple-600/20 bg-purple-50/20' : 'border-stone-200 bg-white'"
                             class="border rounded-2xl p-2.5 cursor-pointer transition text-left relative">
                            <span class="block text-xs font-extrabold text-slate-800">Pequeña</span>
                            <span class="block text-[11px] font-bold text-purple-700 font-mono mt-0.5">50 × 30 mm</span>
                            <span class="block text-[9px] text-slate-400 mt-1">Memorias, Tintas, USB, MicroSD</span>
                        </div>

                        <!-- Option 2: Mediana -->
                        <div @click="selectedLabelSize = '70 × 40 mm'" 
                             :class="selectedLabelSize === '70 × 40 mm' ? 'border-[#7C3AED] ring-2 ring-purple-600/20 bg-purple-50/20' : 'border-stone-200 bg-white'"
                             class="border rounded-2xl p-2.5 cursor-pointer transition text-left relative">
                            <span class="block text-xs font-extrabold text-slate-800">Mediana</span>
                            <span class="block text-[11px] font-bold text-purple-700 font-mono mt-0.5">70 × 40 mm</span>
                            <span class="block text-[9px] text-slate-400 mt-1">Celulares, Parlantes, Cámaras</span>
                        </div>

                        <!-- Option 3: Grande (Active) -->
                        <div @click="selectedLabelSize = '100 × 60 mm'" 
                             :class="selectedLabelSize === '100 × 60 mm' ? 'border-[#7C3AED] ring-2 ring-purple-600/20 bg-purple-50/20' : 'border-stone-200 bg-white'"
                             class="border rounded-2xl p-2.5 cursor-pointer transition text-left relative">
                            <span class="absolute top-1.5 right-1.5 bg-[#7C3AED] text-white text-[7px] font-black px-1.5 py-0.2 rounded-full uppercase">ACTIVO</span>
                            <span class="block text-xs font-extrabold text-slate-800">Grande</span>
                            <span class="block text-[11px] font-bold text-purple-700 font-mono mt-0.5">100 × 60 mm</span>
                            <span class="block text-[9px] text-slate-400 mt-1">Laptops, Monitores, Impresoras</span>
                        </div>

                    </div>
                </div>

                <p class="text-[10px] text-slate-500 mb-5 leading-tight flex items-start gap-1.5">
                    <i class="fa-solid fa-file-lines text-slate-400 mt-0.5"></i>
                    <span>El sistema preselecciona el formato óptimo según el tamaño físico del empaque de la categoría, pero puedes cambiarlo manualmente si imprimes en etiquetas más reducidas.</span>
                </p>

                <!-- Modal Actions -->
                <div class="flex items-center justify-between pt-3 border-t border-stone-100">
                    <button type="button" @click="thermalModalOpen = false" class="px-4 py-2 text-xs font-bold text-slate-600 hover:bg-stone-100 rounded-xl transition">
                        Cancelar
                    </button>
                    <div class="flex items-center gap-2">
                        <button type="button" @click="previewSheet()" class="px-4 py-2 bg-stone-100 hover:bg-stone-200 text-slate-700 text-xs font-bold rounded-xl transition flex items-center gap-1.5">
                            <i class="fa-regular fa-eye"></i> Vista previa de hoja
                        </button>
                        <button type="button" @click="printThermalSticker()" class="px-5 py-2 bg-[#7C3AED] hover:bg-[#6D28D9] text-white text-xs font-bold rounded-xl transition shadow-md shadow-purple-600/30 flex items-center gap-2 cursor-pointer">
                            <i class="fa-solid fa-print"></i> Imprimir Etiqueta
                            <i class="fa-solid fa-chevron-down text-[9px]"></i>
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </template>

    <!-- ============================================================== -->
    <!-- MODAL 3: ESCANEAR CÓDIGO DE BARRAS / CÁMARA (IMAGE 3)         -->
    <!-- ============================================================== -->
    <template x-teleport="body">
        <div x-show="scannerModalOpen" 
             x-cloak 
             class="fixed inset-0 z-[320] flex items-center justify-center bg-slate-950/80 backdrop-blur-sm p-4 overflow-y-auto"
             @keydown.window.escape="closeScannerModal()">
            
            <div @click.away="closeScannerModal()" 
                 class="bg-white rounded-3xl shadow-2xl w-full max-w-lg p-6 border border-stone-200 relative my-auto">
                
                <!-- Modal Header -->
                <div class="flex items-start justify-between pb-3 border-b border-stone-100 mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-purple-100 text-purple-700 flex items-center justify-center text-lg shadow-xs">
                            <i class="fa-solid fa-qrcode"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-extrabold text-slate-900">Escanear código de barras</h3>
                            <p class="text-xs text-slate-500">Cámara lista para lectura de códigos EAN-13, UPC-A y Code-128</p>
                        </div>
                    </div>
                    <button type="button" @click="closeScannerModal()" class="text-slate-400 hover:text-slate-600 p-1.5 rounded-lg hover:bg-stone-100 transition">
                        <i class="fa-solid fa-xmark text-sm"></i>
                    </button>
                </div>

                <!-- Camera Scanner Viewport -->
                <div class="relative bg-slate-950 rounded-2xl overflow-hidden h-64 border border-slate-800 flex items-center justify-center mb-4">
                    
                    <!-- Html5Qrcode video container -->
                    <div id="camera-reader-viewport" class="w-full h-full object-cover"></div>

                    <!-- Simulated Visual Center (Fallback or while camera starts) -->
                    <div x-show="!cameraActive" class="absolute inset-0 flex flex-col items-center justify-center p-6 text-center z-10 bg-slate-950/90">
                        <div class="bg-white rounded-xl p-3 shadow-lg max-w-[240px] mb-2 border border-slate-200">
                            <svg id="sample-camera-barcode" class="max-w-full h-12"></svg>
                            <span class="block text-[11px] font-mono font-bold tracking-widest text-slate-800 text-center" x-text="detectedScanCode"></span>
                        </div>
                        <button type="button" @click="startWebcamScan()" class="mt-2 px-3 py-1.5 bg-purple-600 hover:bg-purple-700 text-white rounded-lg text-xs font-bold flex items-center gap-2">
                            <i class="fa-solid fa-camera"></i> Activar Cámara en vivo
                        </button>
                    </div>

                    <!-- Reticle target brackets -->
                    <div class="reticle-corner top-4 left-4 border-t-2 border-l-2"></div>
                    <div class="reticle-corner top-4 right-4 border-t-2 border-r-2"></div>
                    <div class="reticle-corner bottom-4 left-4 border-b-2 border-l-2"></div>
                    <div class="reticle-corner bottom-4 right-4 border-b-2 border-r-2"></div>

                    <!-- Glowing Red Laser Scan Line Animation -->
                    <div class="animate-laser"></div>

                    <!-- Status pill at bottom of viewport -->
                    <div class="absolute bottom-3 left-1/2 -translate-x-1/2 px-3 py-1 rounded-full bg-slate-900/80 backdrop-blur-md text-emerald-400 text-[10px] font-bold border border-slate-700/60 z-20 flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-ping"></span>
                        <span>Coloca el código dentro del área marcada</span>
                    </div>
                </div>

                <!-- Detected Result Card -->
                <div class="bg-emerald-50 border border-emerald-300 rounded-2xl p-4 mb-4">
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center gap-2 text-emerald-800 text-xs font-bold">
                            <i class="fa-solid fa-circle-check text-emerald-600"></i> Código detectado con éxito
                        </div>
                        <span class="px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800 text-[9px] font-bold">Lectura precisa</span>
                    </div>

                    <div class="grid grid-cols-3 gap-2 py-2 border-y border-emerald-200/80 text-center">
                        <div>
                            <span class="block text-[9px] uppercase font-bold text-emerald-700">TIPO:</span>
                            <span class="block text-xs font-black text-slate-900 font-mono mt-0.5">EAN-13</span>
                        </div>
                        <div>
                            <span class="block text-[9px] uppercase font-bold text-emerald-700">CÓDIGO:</span>
                            <span class="block text-xs font-black text-slate-900 font-mono mt-0.5" x-text="detectedScanCode"></span>
                        </div>
                        <div>
                            <span class="block text-[9px] uppercase font-bold text-emerald-700">FABRICANTE:</span>
                            <span class="block text-xs font-bold text-emerald-800 italic mt-0.5" x-text="(brandName || 'Apple Inc.') + ' [Validado]'"></span>
                        </div>
                    </div>

                    <p class="text-[10px] text-emerald-800/80 mt-2 text-center leading-tight">
                        Apunta la cámara hacia el código de barras del producto. La lectura se realiza de forma automática al enfocar correctamente.
                    </p>
                </div>

                <!-- Modal Actions -->
                <div class="flex items-center justify-between pt-2 border-t border-stone-100">
                    <button type="button" @click="closeScannerModal()" class="px-4 py-2 text-xs font-bold text-slate-600 hover:bg-stone-100 rounded-xl transition">
                        Cancelar
                    </button>
                    <div class="flex items-center gap-2">
                        <button type="button" @click="promptManualCode()" class="px-3.5 py-2 bg-stone-100 hover:bg-stone-200 text-slate-700 text-xs font-bold rounded-xl transition flex items-center gap-1.5">
                            <i class="fa-solid fa-keyboard"></i> Escanear manualmente
                        </button>
                        <button type="button" @click="applyScannedCode()" class="px-5 py-2 bg-[#7C3AED] hover:bg-[#6D28D9] text-white text-xs font-bold rounded-xl transition shadow-md shadow-purple-600/30 flex items-center gap-1.5 cursor-pointer">
                            <i class="fa-solid fa-check"></i> Continuar e insertar
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </template>

    <!-- ============================================================== -->
    <!-- MODAL 4: ASISTENTE IA PARA DESCRIPCIÓN O SPECS                -->
    <!-- ============================================================== -->
    <template x-teleport="body">
        <div x-show="aiModalOpen" 
             x-cloak 
             class="fixed inset-0 z-[350] flex items-center justify-center bg-slate-950/75 backdrop-blur-sm p-4 overflow-y-auto"
             @keydown.window.escape="aiModalOpen = false">
            <div @click.away="aiModalOpen = false" class="bg-white rounded-3xl shadow-2xl w-full max-w-xl p-6 border border-stone-100 relative my-auto">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-base font-extrabold text-slate-900 flex items-center gap-2.5">
                        <div class="w-9 h-9 rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center shadow-xs">
                            <i class="fa-solid fa-wand-magic-sparkles text-sm"></i>
                        </div>
                        <span x-text="aiTargetField === 'specs' ? 'Autocompletar Ficha Técnica con IA' : 'Generar Descripción con IA'"></span>
                    </h3>
                    <button type="button" @click="aiModalOpen = false" class="w-8 h-8 rounded-full hover:bg-stone-100 text-slate-400 hover:text-slate-600 flex items-center justify-center transition">
                        <i class="fa-solid fa-xmark text-sm"></i>
                    </button>
                </div>
                
                <p class="text-xs text-slate-500 mb-3 leading-relaxed">
                    <span class="font-semibold text-purple-700">💡 Pega directamente la ficha técnica</span> copiada de un sitio oficial o escribe el modelo del producto para que el sistema organice la información.
                </p>

                <!-- Category Presets -->
                <div class="mb-3 flex flex-wrap gap-1.5 items-center">
                    <span class="text-[10px] font-bold text-slate-400 self-center mr-1">Atajos:</span>
                    <button type="button" @click="setAiPreset('Laptop Gamer')" class="px-2 py-1 bg-stone-100 hover:bg-purple-100 hover:text-purple-700 text-slate-600 rounded-lg text-[10px] font-semibold transition">💻 Laptop Gamer</button>
                    <button type="button" @click="setAiPreset('MacBook Pro')" class="px-2 py-1 bg-stone-100 hover:bg-purple-100 hover:text-purple-700 text-slate-600 rounded-lg text-[10px] font-semibold transition">🍏 MacBook</button>
                    <button type="button" @click="setAiPreset('Impresora')" class="px-2 py-1 bg-stone-100 hover:bg-purple-100 hover:text-purple-700 text-slate-600 rounded-lg text-[10px] font-semibold transition">🖨️ Impresora</button>
                    <button type="button" @click="setAiPreset('Monitor')" class="px-2 py-1 bg-stone-100 hover:bg-purple-100 hover:text-purple-700 text-slate-600 rounded-lg text-[10px] font-semibold transition">🖥️ Monitor</button>
                    <button type="button" @click="aiPrompt = ''" class="ml-auto text-[10px] font-bold text-rose-500 hover:underline">Limpiar</button>
                </div>

                <textarea x-model="aiPrompt" rows="5" class="w-full px-3.5 py-2.5 bg-stone-50 border border-stone-200 rounded-2xl text-xs text-slate-800 focus:ring-2 focus:ring-purple-500 focus:bg-white transition mb-4 resize-y font-mono" placeholder="Pega aquí el texto o tabla de especificaciones..."></textarea>
                
                <div class="flex items-center justify-between">
                    <button type="button" @click="aiModalOpen = false" class="px-4 py-2 text-xs text-slate-600 hover:bg-stone-100 rounded-xl font-bold transition">
                        Cancelar
                    </button>
                    <button type="button" @click="processAiGeneration()" :disabled="isAiLoading" class="px-5 py-2.5 text-xs bg-[#7C3AED] hover:bg-[#6D28D9] text-white rounded-xl font-bold shadow-md shadow-purple-600/30 flex items-center gap-2 transition cursor-pointer">
                        <i class="fa-solid fa-bolt" x-show="!isAiLoading"></i>
                        <i class="fa-solid fa-spinner fa-spin" x-show="isAiLoading"></i>
                        <span x-text="isAiLoading ? 'Procesando...' : (aiTargetField === 'specs' ? 'Llenar Especificaciones' : 'Generar Descripción')"></span>
                    </button>
                </div>
            </div>
        </div>
    </template>

    <!-- ============================================================== -->
    <!-- MODAL 5: AGREGAR RÁPIDAMENTE (CATEGORÍA/MARCA/MODELO)         -->
    <!-- ============================================================== -->
    <template x-teleport="body">
        <div x-show="quickAddModalOpen" 
             x-cloak 
             class="fixed inset-0 z-[360] flex items-center justify-center bg-slate-950/70 backdrop-blur-sm p-4"
             @keydown.window.escape="quickAddModalOpen = false">
            <div @click.away="quickAddModalOpen = false" class="bg-white rounded-3xl shadow-xl w-full max-w-sm p-6 border border-stone-200 my-auto">
                <h3 class="text-base font-extrabold text-slate-900 mb-1" x-text="'Nueva ' + quickAddLabel"></h3>
                <p class="text-xs text-slate-500 mb-4">Ingresa el nombre para guardarlo instantáneamente.</p>
                <div class="mb-4">
                    <input type="text" 
                           x-model="quickAddValue" 
                           class="w-full px-3.5 py-2.5 border border-stone-300 rounded-xl text-xs font-semibold text-slate-900 focus:ring-2 focus:ring-purple-500 focus:border-purple-600" 
                           :placeholder="'Nombre de ' + quickAddLabel + '...'" 
                           @keydown.enter="processQuickAdd()">
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" @click="quickAddModalOpen = false" class="px-4 py-2 text-xs font-bold text-slate-600 hover:bg-stone-100 rounded-xl">Cancelar</button>
                    <button type="button" @click="processQuickAdd()" :disabled="isQuickAddLoading" class="px-4 py-2 text-xs font-bold bg-[#7C3AED] hover:bg-[#6D28D9] text-white rounded-xl flex items-center gap-1.5 shadow-sm">
                        <i class="fa-solid fa-spinner fa-spin" x-show="isQuickAddLoading"></i>
                        <span x-text="isQuickAddLoading ? 'Guardando...' : 'Guardar'"></span>
                    </button>
                </div>
            </div>
        </div>
    </template>

</div>

@endsection

@push('scripts')
<script>
    function productCreateApp() {
        return {
            // Form state
            name: 'MacBook Pro 16" M3 Max',
            categoryId: '',
            subcategoryId: '',
            brandId: '',
            modelId: '',
            categoryName: 'Laptops',
            brandName: 'Apple',
            controlType: 'Por Serie',
            description: 'MacBook Pro 16 pulgadas con chip Apple M3 Max (CPU 16 núcleos, GPU 40 núcleos), 36 GB de memoria unificada y almacenamiento ultrarrápido SSD de 1 TB. Pantalla Liquid Retina XDR de 16.2" con ProMotion a 120Hz en color Negro Espacial.',
            sku: 'SKU-APP-001',
            code: '7751234567890',
            serialNumber: 'PF4XXXXXX',
            price: 12099.00,
            minPrice: 11800.00,
            stock: 10,
            state: 'Activo',
            warranty: '12 meses (Garantía Oficial de Marca)',
            isActive: true,
            isNew: true,
            isOffer: false,
            isFeatured: true,

            // Specs table
            specs: [
                { name: 'Procesador', value: 'Apple M3 Max (16-Core CPU)' },
                { name: 'Memoria RAM', value: '36GB Memoria Unificada' },
                { name: 'Almacenamiento', value: '1TB SSD PCIe Gen 4' },
                { name: 'Pantalla', value: '16.2" Liquid Retina XDR 120H' },
                { name: 'Sistema Operativo', value: 'macOS Sonoma preinstalado' }
            ],

            // Images Manager
            imagesList: [],
            imageUrlInput: '',
            dragOver: false,

            // Modal States
            thermalModalOpen: false,
            selectedLabelSize: '100 × 60 mm',
            
            scannerModalOpen: false,
            cameraActive: false,
            detectedScanCode: '7751234567890',
            html5QrScanner: null,

            aiModalOpen: false,
            aiTargetField: 'specs',
            aiPrompt: '',
            isAiLoading: false,

            quickAddModalOpen: false,
            quickAddEndpoint: '',
            quickAddLabel: '',
            quickAddValue: '',
            isQuickAddLoading: false,

            initComponent() {
                // Initialize default barcodes rendering
                this.$nextTick(() => {
                    this.updateLiveBarcodes();
                });
            },

            formatPrice(val) {
                const num = parseFloat(val);
                if (isNaN(num)) return '0.00';
                return num.toLocaleString('es-PE', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            },

            calculateMinPrice() {
                if (this.price && (!this.minPrice || this.minPrice == 0)) {
                    this.minPrice = (parseFloat(this.price) * 0.95).toFixed(2);
                }
            },

            regenerateSku() {
                const prefix = (this.brandName ? this.brandName.substring(0, 3).toUpperCase() : 'PRD');
                const randomNum = Math.floor(100 + Math.random() * 900);
                this.sku = 'SKU-' + prefix + '-' + randomNum;
                this.updateLiveBarcodes();
            },

            promptManualCode() {
                const current = this.code || '7751234567890';
                const entered = prompt('Ingresa el código de barras (EAN-13, UPC o Code-128):', current);
                if (entered && entered.trim()) {
                    this.code = entered.trim();
                    this.detectedScanCode = this.code;
                    this.updateLiveBarcodes();
                    if (this.scannerModalOpen) {
                        this.closeScannerModal();
                    }
                }
            },

            copyText(txt, msg) {
                if (!txt) return;
                navigator.clipboard.writeText(txt).then(() => {
                    alert(msg || 'Copiado al portapapeles');
                });
            },

            handleCategoryChange(e) {
                const opt = e.target.options[e.target.selectedIndex];
                this.categoryName = opt ? (opt.dataset.name || opt.text) : '';
                this.updateLiveBarcodes();
            },

            handleBrandChange(e) {
                const opt = e.target.options[e.target.selectedIndex];
                this.brandName = opt ? (opt.dataset.name || opt.text) : '';
                this.regenerateSku();
                this.updateLiveBarcodes();
            },

            handleModelChange(e) {
                this.updateLiveBarcodes();
            },

            addSpec() {
                this.specs.push({ name: '', value: '' });
            },

            removeSpec(idx) {
                this.specs.splice(idx, 1);
            },

            // --- BARCODE RENDERING LOGIC ---
            updateLiveBarcodes() {
                this.$nextTick(() => {
                    // 1. Render Card 2 Barcode SVG
                    const cardSvg = document.getElementById('card-barcode-svg');
                    if (cardSvg && typeof JsBarcode !== 'undefined') {
                        try {
                            const barcodeVal = this.code || '7751234567890';
                            JsBarcode(cardSvg, barcodeVal, {
                                format: barcodeVal.length === 13 && /^\d+$/.test(barcodeVal) ? "EAN13" : "CODE128",
                                width: 1.5,
                                height: 38,
                                displayValue: false,
                                margin: 0,
                                lineColor: "#1e293b"
                            });
                        } catch (e) {
                            // Fallback to CODE128 if EAN13 checksum fails
                            try {
                                JsBarcode(cardSvg, this.code || '7751234567890', {
                                    format: "CODE128",
                                    width: 1.5,
                                    height: 38,
                                    displayValue: false,
                                    margin: 0
                                });
                            } catch (err) {}
                        }
                    }

                    // 2. Render Modal Thermal Barcodes & QR
                    this.renderThermalBarcodes();
                });
            },

            renderThermalBarcodes() {
                if (typeof JsBarcode === 'undefined') return;

                // Code 128 for SKU
                const code128Svg = document.getElementById('modal-code128-svg');
                if (code128Svg) {
                    try {
                        JsBarcode(code128Svg, this.sku || 'SKU-APP-001', {
                            format: "CODE128",
                            width: 1.4,
                            height: 32,
                            displayValue: false,
                            margin: 0,
                            lineColor: "#000000"
                        });
                    } catch (e) {}
                }

                // EAN 13 for Code
                const ean13Svg = document.getElementById('modal-ean13-svg');
                if (ean13Svg) {
                    const eanVal = this.code || '7751234567890';
                    try {
                        JsBarcode(ean13Svg, eanVal, {
                            format: eanVal.length === 13 && /^\d+$/.test(eanVal) ? "EAN13" : "CODE128",
                            width: 1.2,
                            height: 26,
                            displayValue: false,
                            margin: 0,
                            lineColor: "#000000"
                        });
                    } catch (e) {
                        try {
                            JsBarcode(ean13Svg, eanVal, { format: "CODE128", width: 1.2, height: 26, displayValue: false, margin: 0 });
                        } catch (err) {}
                    }
                }

                // Sample camera barcode preview
                const sampleCamSvg = document.getElementById('sample-camera-barcode');
                if (sampleCamSvg) {
                    try {
                        JsBarcode(sampleCamSvg, this.detectedScanCode || '7751234567890', {
                            format: "CODE128",
                            width: 1.6,
                            height: 40,
                            displayValue: false,
                            margin: 0,
                            lineColor: "#1e293b"
                        });
                    } catch (e) {}
                }

                // QR Code
                const qrContainer = document.getElementById('modal-qr-container');
                if (qrContainer && typeof QRCode !== 'undefined') {
                    qrContainer.innerHTML = '';
                    try {
                        new QRCode(qrContainer, {
                            text: window.location.origin + '/producto/' + (this.sku || 'SKU-APP-001'),
                            width: 58,
                            height: 58,
                            colorDark: "#000000",
                            colorLight: "#ffffff",
                            correctLevel: QRCode.CorrectLevel.M
                        });
                    } catch (e) {}
                }
            },

            // --- THERMAL MODAL ---
            openThermalModal() {
                this.thermalModalOpen = true;
                this.$nextTick(() => {
                    this.renderThermalBarcodes();
                });
            },

            previewSheet() {
                alert('Modo hoja de etiquetas (A4 con 24 divisiones térmicas) configurado.');
            },

            printThermalSticker() {
                window.print();
            },

            // --- CAMERA SCANNER MODAL (IMAGE 3) ---
            openScannerModal() {
                this.detectedScanCode = this.code || '7751234567890';
                this.scannerModalOpen = true;
                this.$nextTick(() => {
                    this.renderThermalBarcodes();
                });
            },

            startWebcamScan() {
                if (typeof Html5Qrcode === 'undefined') {
                    alert('Librería de cámara no disponible.');
                    return;
                }
                this.cameraActive = true;
                this.$nextTick(() => {
                    try {
                        this.html5QrScanner = new Html5Qrcode("camera-reader-viewport");
                        this.html5QrScanner.start(
                            { facingMode: "environment" },
                            { fps: 15, qrbox: { width: 280, height: 160 } },
                            (decodedText) => {
                                this.detectedScanCode = decodedText;
                                // Sound beep feedback
                                try {
                                    const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
                                    const osc = audioCtx.createOscillator();
                                    osc.type = "sine";
                                    osc.frequency.setValueAtTime(880, audioCtx.currentTime);
                                    osc.connect(audioCtx.destination);
                                    osc.start();
                                    osc.stop(audioCtx.currentTime + 0.15);
                                } catch(e) {}
                            },
                            (errorMessage) => {}
                        ).catch(err => {
                            console.warn("Camera error:", err);
                            this.cameraActive = false;
                        });
                    } catch(e) {
                        this.cameraActive = false;
                    }
                });
            },

            closeScannerModal() {
                if (this.html5QrScanner && this.cameraActive) {
                    this.html5QrScanner.stop().then(() => {
                        this.html5QrScanner.clear();
                    }).catch(() => {});
                }
                this.cameraActive = false;
                this.scannerModalOpen = false;
            },

            applyScannedCode() {
                if (this.detectedScanCode) {
                    this.code = this.detectedScanCode;
                    this.updateLiveBarcodes();
                }
                this.closeScannerModal();
            },

            // --- IMAGE UPLOAD LOGIC ---
            triggerImageSelect() {
                if (this.imagesList.length >= 8) {
                    alert('Máximo 8 imágenes permitidas');
                    return;
                }
                const newId = Date.now() + Math.random().toString(36).substring(7);
                this.imagesList.push({ id: newId, type: 'file', preview: '' });

                this.$nextTick(() => {
                    const input = document.getElementById('file_inp_' + newId);
                    if (input) input.click();
                });
            },

            handleFileSelected(event, item) {
                const file = event.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        item.preview = e.target.result;
                    };
                    reader.readAsDataURL(file);
                } else {
                    this.removeImage(item.id);
                }
            },

            addImageFromUrl() {
                if (!this.imageUrlInput || !this.imageUrlInput.trim()) return;
                if (this.imagesList.length >= 8) {
                    alert('Máximo 8 imágenes permitidas');
                    return;
                }
                const newId = Date.now() + Math.random().toString(36).substring(7);
                const url = this.imageUrlInput.trim();
                this.imagesList.push({ id: newId, type: 'url', preview: url });

                // Append hidden input for URL
                const container = document.getElementById('hidden-file-inputs');
                const inp = document.createElement('input');
                inp.type = 'hidden';
                inp.name = 'images_urls[' + newId + ']';
                inp.value = url;
                inp.id = 'url_inp_' + newId;
                container.appendChild(inp);

                this.imageUrlInput = '';
            },

            removeImage(id) {
                this.imagesList = this.imagesList.filter(img => img.id !== id);
                const fileInp = document.getElementById('file_inp_' + id);
                if (fileInp) fileInp.remove();
                const urlInp = document.getElementById('url_inp_' + id);
                if (urlInp) urlInp.remove();
            },

            handleImageDrop(e) {
                this.dragOver = false;
                const files = e.dataTransfer.files;
                if (!files || !files.length) return;

                for (let i = 0; i < files.length; i++) {
                    if (this.imagesList.length >= 8) break;
                    if (!files[i].type.startsWith('image/')) continue;

                    const newId = Date.now() + Math.random().toString(36).substring(7);
                    const newImg = { id: newId, type: 'file', preview: '' };
                    this.imagesList.push(newImg);

                    this.$nextTick(() => {
                        const dt = new DataTransfer();
                        dt.items.add(files[i]);
                        const input = document.getElementById('file_inp_' + newId);
                        if (input) {
                            input.files = dt.files;
                            this.handleFileSelected({ target: { files: dt.files } }, newImg);
                        }
                    });
                }
            },

            // --- AI ASSISTANT ---
            openAiModal(field) {
                this.aiTargetField = field;
                this.aiPrompt = this.name || '';
                this.aiModalOpen = true;
            },

            setAiPreset(preset) {
                this.aiPrompt = preset + (this.aiPrompt ? ' - ' + this.aiPrompt : '');
            },

            processAiGeneration() {
                this.isAiLoading = true;
                setTimeout(() => {
                    if (this.aiTargetField === 'description') {
                        this.description = (this.name || 'Laptop') + ' de última generación con alto rendimiento para trabajo profesional y multitarea exigente. Cuenta con acabados premium, excelente autonomía y pantalla de alta resolución calibrada para profesionales.';
                    } else if (this.aiTargetField === 'specs') {
                        this.specs = [
                            { name: 'Procesador', value: 'Intel Core i7 / AMD Ryzen 7 / M3 Max' },
                            { name: 'Memoria RAM', value: '32GB DDR5 Alta Velocidad' },
                            { name: 'Almacenamiento', value: '1TB NVMe PCIe Gen 4' },
                            { name: 'Pantalla', value: '16 Pulgadas WQXGA 165Hz IPS' },
                            { name: 'Tarjeta Gráfica', value: 'NVIDIA GeForce RTX 4070 8GB GDDR6' },
                            { name: 'Sistema Operativo', value: 'Windows 11 Pro 64-bit' }
                        ];
                    }
                    this.isAiLoading = false;
                    this.aiModalOpen = false;
                }, 350);
            },

            // --- QUICK ADD (CATEGORÍA/MARCA/MODELO) ---
            openQuickAdd(endpoint, label) {
                this.quickAddEndpoint = endpoint;
                this.quickAddLabel = label;
                this.quickAddValue = '';
                this.quickAddModalOpen = true;
            },

            processQuickAdd() {
                if (!this.quickAddValue || !this.quickAddValue.trim()) return;
                this.isQuickAddLoading = true;

                const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                const url = '/admin/' + this.quickAddEndpoint + '/quick';

                fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ name: this.quickAddValue.trim() })
                })
                .then(res => res.json())
                .then(data => {
                    this.isQuickAddLoading = false;
                    if (data.success) {
                        // Append to corresponding select
                        let selectId = '';
                        if (this.quickAddEndpoint === 'categories') selectId = 'category_select';
                        if (this.quickAddEndpoint === 'subcategories') selectId = 'subcategory_select';
                        if (this.quickAddEndpoint === 'brands') selectId = 'brand_select';
                        if (this.quickAddEndpoint === 'models') selectId = 'model_select';

                        const selectEl = document.getElementById(selectId);
                        if (selectEl) {
                            const opt = document.createElement('option');
                            opt.value = data.id;
                            opt.text = data.name;
                            opt.dataset.name = data.name;
                            opt.selected = true;
                            selectEl.add(opt);

                            if (this.quickAddEndpoint === 'categories') {
                                this.categoryId = data.id;
                                this.categoryName = data.name;
                            }
                            if (this.quickAddEndpoint === 'brands') {
                                this.brandId = data.id;
                                this.brandName = data.name;
                            }
                        }
                        this.quickAddModalOpen = false;
                    } else {
                        alert(data.message || 'Error al guardar');
                    }
                })
                .catch(err => {
                    this.isQuickAddLoading = false;
                    alert('Error en la comunicación con el servidor');
                });
            }
        };
    }
</script>
@endpush
