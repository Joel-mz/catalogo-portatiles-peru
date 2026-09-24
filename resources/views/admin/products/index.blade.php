@extends('layouts.admin')
@section('header_title', 'Productos')
@section('content')
<div class="space-y-6" x-data="productManager()">
    <!-- Top Action Bar -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-2xl font-display font-bold text-slate-800">Catálogo de Productos</h2>
            <p class="text-xs text-slate-500 mt-1">Gestiona inventario, especificaciones y precios.</p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('admin.pdf.index') }}" class="px-3 py-2 bg-red-50 text-red-600 hover:bg-red-100 rounded-lg transition-colors text-xs font-bold border border-red-100 flex items-center gap-2">
                <i class="fa-solid fa-file-pdf"></i> PDF Catálogo
            </a>
            <a href="{{ route('admin.imports.index') }}" class="px-3 py-2 bg-green-50 text-green-700 hover:bg-green-100 rounded-lg transition-colors text-xs font-bold border border-green-200 flex items-center gap-2">
                <i class="fa-solid fa-file-import"></i> Importar CSV
            </a>
            <a href="{{ route('admin.imports.export') }}" class="px-3 py-2 bg-blue-50 text-blue-700 hover:bg-blue-100 rounded-lg transition-colors text-xs font-bold border border-blue-200 flex items-center gap-2">
                <i class="fa-solid fa-file-export"></i> Exportar CSV
            </a>
            <a href="{{ route('admin.products.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors shadow-sm flex items-center gap-2 text-sm font-bold">
                <i class="fa-solid fa-plus"></i> Nuevo Producto
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl relative flex items-center gap-3">
            <i class="fa-solid fa-circle-check"></i>
            <span class="block sm:inline text-sm font-medium">{{ session('success') }}</span>
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl relative flex items-start gap-3">
            <i class="fa-solid fa-triangle-exclamation mt-1"></i>
            <div class="text-sm font-medium">
                <p>Por favor, corrige los siguientes errores y vuelve a intentarlo:</p>
                <ul class="list-disc pl-5 mt-1 text-xs">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            <button type="button" @click="openCreateModal()" class="ml-auto px-3 py-1 bg-red-100 text-red-700 text-xs font-bold rounded hover:bg-red-200">Reabrir Formulario</button>
        </div>
    @endif

    <!-- Table -->
    <div class="soft-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th class="w-16">Img</th>
                        <th>Producto / SKU</th>
                        <th>Categoría</th>
                        <th>Precio</th>
                        <th>Stock</th>
                        <th>Estado</th>
                        <th class="text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td>
                            <div class="w-12 h-12 bg-white rounded-lg border border-slate-200 p-1 flex items-center justify-center overflow-hidden">
                                @php $mainImg = $product->images->firstWhere('is_main', true) ?? $product->images->first(); @endphp
                                @if($mainImg)
                                    <img src="{{ filter_var($mainImg->image_path, FILTER_VALIDATE_URL) ? $mainImg->image_path : asset('storage/' . $mainImg->image_path) }}" class="w-full h-full object-contain">
                                @else
                                    <i class="fa-solid fa-laptop text-slate-300 text-xl"></i>
                                @endif
                            </div>
                        </td>
                        <td>
                            <div class="text-sm font-bold text-slate-800 leading-tight">{{ $product->name }}</div>
                            <div class="text-[10px] text-slate-400 font-mono mt-0.5">{{ $product->code }} | SKU: {{ $product->sku ?? 'N/A' }}</div>
                        </td>
                        <td>
                            <div class="text-xs font-semibold text-slate-700">{{ $product->category->name ?? '—' }}</div>
                            <div class="text-[10px] text-slate-500">{{ $product->brand->name ?? '—' }}</div>
                        </td>
                        <td>
                            <div class="text-sm font-black text-blue-600">S/ {{ number_format($product->price, 2) }}</div>
                            @if($product->is_offer)
                            <div class="text-[10px] text-red-500 font-bold bg-red-50 px-1.5 py-0.5 rounded inline-block mt-1">Oferta: S/ {{ number_format($product->offer_price, 2) }}</div>
                            @endif
                        </td>
                        <td>
                            <span class="badge {{ $product->stock > 0 ? 'badge-gray' : 'badge-red' }}">{{ $product->stock }} un.</span>
                        </td>
                        <td>
                            @if($product->status)
                                <span class="badge badge-green">Activo</span>
                            @else
                                <span class="badge badge-gray">Inactivo</span>
                            @endif
                        </td>
                        <td class="text-right space-x-1">
                            <a href="{{ route('admin.products.edit', $product) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-blue-600 hover:bg-blue-50 transition-colors border border-transparent hover:border-blue-200" title="Editar">
                                <i class="fa-solid fa-pen-to-square text-sm"></i>
                            </a>
                            <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="inline-block" onsubmit="return confirm('¿Seguro que deseas eliminar este producto?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-red-600 hover:bg-red-50 transition-colors border border-transparent hover:border-red-200" title="Eliminar">
                                    <i class="fa-solid fa-trash text-sm"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-12 text-center text-slate-400">
                            <i class="fa-solid fa-box-open text-4xl block mb-3 text-slate-200"></i>
                            <p class="text-sm font-medium">No hay productos registrados en el catálogo.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="px-6 py-4 border-t border-slate-100">
            {{ $products->links() }}
        </div>
    </div>

    <!-- ==================== MODAL ==================== -->
    <div class="relative z-[100]" aria-labelledby="modal-title" role="dialog" aria-modal="true" x-show="showModal" x-cloak>
        <div class="fixed inset-0 bg-slate-900/70 backdrop-blur-sm transition-opacity" x-show="showModal" x-transition.opacity @click="closeModal()"></div>

        <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0 pointer-events-none">
                <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-4xl pointer-events-auto"
                     x-show="showModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                    
                    <form :action="formAction" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="_method" :value="isEditing ? 'PUT' : 'POST'">
                        
                        <!-- Modal Header -->
                        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50">
                            <h3 class="text-lg font-bold text-slate-800" id="modal-title" x-text="isEditing ? 'Editar Producto' : 'Registrar Nuevo Producto'"></h3>
                            <button type="button" @click="closeModal()" class="w-8 h-8 rounded-lg bg-slate-200 hover:bg-slate-300 text-slate-600 flex items-center justify-center transition-colors">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </div>

                        <!-- Modal Body -->
                        <div class="px-6 py-6 grid grid-cols-1 lg:grid-cols-12 gap-8 max-h-[70vh] overflow-y-auto custom-scrollbar">
                            
                            <!-- Left: Basic Info -->
                            <div class="lg:col-span-7 space-y-5">
                                <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Información Principal</h4>
                                
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">Nombre del Producto <span class="text-red-500">*</span></label>
                                    <input type="text" name="name" x-model="product.name" required class="w-full rounded-lg border-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>

                                <div class="grid grid-cols-3 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 mb-1">Código Oficial (P/N) <span class="text-red-500">*</span></label>
                                        <div class="relative">
                                            <input type="text" name="code" x-model="product.code" @keydown.enter.prevent required class="w-full rounded-lg border-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500 font-mono uppercase pr-10" placeholder="Código">
                                            <button type="button" @click="startScanner('code')" class="absolute right-2 top-1/2 -translate-y-1/2 text-slate-400 hover:text-blue-600 p-1" title="Escanear con Cámara">
                                                <i class="fa-solid fa-camera"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 mb-1">Número de Serie (S/N)</label>
                                        <div class="relative">
                                            <input type="text" name="serial_number" x-model="product.serial_number" @keydown.enter.prevent class="w-full rounded-lg border-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500 font-mono uppercase pr-10" placeholder="Serie">
                                            <button type="button" @click="startScanner('serial_number')" class="absolute right-2 top-1/2 -translate-y-1/2 text-slate-400 hover:text-blue-600 p-1" title="Escanear con Cámara">
                                                <i class="fa-solid fa-camera"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 mb-1">SKU Interno</label>
                                        <input type="text" name="sku" x-model="product.sku" class="w-full rounded-lg border-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500 font-mono" placeholder="Automático si está vacío">
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 mb-1">Categoría <span class="text-red-500">*</span></label>
                                        <select name="category_id" x-model="product.category_id" required class="w-full rounded-lg border-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500">
                                            <option value="">Seleccione...</option>
                                            @foreach($categories as $c) <option value="{{ $c->id }}">{{ $c->name }}</option> @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 mb-1">Marca <span class="text-red-500">*</span></label>
                                        <select name="brand_id" x-model="product.brand_id" required class="w-full rounded-lg border-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500">
                                            <option value="">Seleccione...</option>
                                            @foreach($brands as $b) <option value="{{ $b->id }}">{{ $b->name }}</option> @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">Descripción corta</label>
                                    <textarea name="description" x-model="product.description" rows="2" class="w-full rounded-lg border-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                                </div>

                                <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 mt-6">Precios e Inventario</h4>
                                
                                <div class="grid grid-cols-3 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 mb-1">Precio Reg. (S/) <span class="text-red-500">*</span></label>
                                        <input type="number" step="0.01" name="price" x-model="product.price" required class="w-full rounded-lg border-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500 font-bold text-blue-600">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 mb-1">Precio Oferta (S/)</label>
                                        <input type="number" step="0.01" name="offer_price" x-model="product.offer_price" class="w-full rounded-lg border-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500 text-red-500">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 mb-1">Stock Actual <span class="text-red-500">*</span></label>
                                        <input type="number" name="stock" x-model="product.stock" required class="w-full rounded-lg border-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500 font-bold">
                                    </div>
                                </div>

                                <!-- Dynamic Specs -->
                                <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 mt-6 flex items-center justify-between">
                                    Especificaciones Técnicas
                                    <button type="button" @click="addSpec()" class="text-[10px] bg-blue-50 text-blue-600 hover:bg-blue-100 px-2 py-1 rounded">
                                        <i class="fa-solid fa-plus"></i> Fila
                                    </button>
                                </h4>
                                <div class="space-y-2 bg-slate-50 p-3 rounded-xl border border-slate-100">
                                    <template x-for="(spec, index) in specs" :key="index">
                                        <div class="flex gap-2 items-center">
                                            <input type="text" :name="'spec_keys['+index+']'" x-model="spec.key" placeholder="Ej: Memoria RAM" class="flex-1 rounded-md border-slate-200 text-xs focus:ring-blue-500 h-8">
                                            <input type="text" :name="'spec_values['+index+']'" x-model="spec.value" placeholder="Ej: 32GB DDR5" class="flex-1 rounded-md border-slate-200 text-xs focus:ring-blue-500 h-8">
                                            <button type="button" @click="removeSpec(index)" class="w-8 h-8 flex items-center justify-center text-red-400 hover:text-red-600 hover:bg-red-50 rounded-md transition-colors"><i class="fa-solid fa-trash text-xs"></i></button>
                                        </div>
                                    </template>
                                    <div x-show="specs.length === 0" class="text-xs text-slate-400 text-center py-2">
                                        No hay especificaciones. Presiona "Fila" para agregar.
                                    </div>
                                </div>

                            </div>

                            <!-- Right: Media & Flags -->
                            <div class="lg:col-span-5 space-y-5">
                                
                                <!-- Images Manager -->
                                <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 flex justify-between items-center">
                                    Imágenes (Máx 5)
                                    <span class="text-[10px] text-blue-500 bg-blue-50 px-2 py-0.5 rounded-full" x-text="images.length + '/5'"></span>
                                </h4>
                                
                                <div class="space-y-3 bg-slate-50 p-3 rounded-xl border border-slate-100">
                                    <div x-show="isEditing && product.images && product.images.length > 0" class="mb-3">
                                        <p class="text-[10px] font-bold text-slate-500 mb-1">Imágenes Guardadas (Actuales):</p>
                                        <div class="flex gap-2 flex-wrap">
                                            <template x-for="pImg in product.images" :key="pImg.id">
                                                <div class="w-12 h-12 rounded border border-slate-200 overflow-hidden bg-white">
                                                    <img :src="pImg.image_path.startsWith('http') ? pImg.image_path : '/storage/' + pImg.image_path" class="w-full h-full object-contain">
                                                </div>
                                            </template>
                                        </div>
                                    </div>

                                    <div x-show="isEditing" class="text-[10px] text-amber-600 bg-amber-50 p-2 rounded border border-amber-100 mb-2">
                                        <i class="fa-solid fa-triangle-exclamation"></i> Al agregar nuevas imágenes aquí, se reemplazarán las anteriores. Déjalo vacío para conservar las actuales.
                                    </div>

                                    <template x-for="(img, index) in images" :key="index">
                                        <div class="flex items-start gap-2 bg-white p-2 rounded-lg border border-slate-200 shadow-sm relative">
                                            <span class="absolute -top-2 -left-2 w-5 h-5 rounded-full bg-slate-800 text-white flex items-center justify-center text-[9px] font-bold z-10" x-text="index === 0 ? '1 (Princ.)' : (index+1)"></span>
                                            
                                            <!-- Image Preview Thumbnail -->
                                            <div class="w-10 h-10 shrink-0 bg-slate-100 rounded-lg flex items-center justify-center overflow-hidden border border-slate-200 ml-1 mt-1">
                                                <img x-show="img.preview" :src="img.preview" class="w-full h-full object-cover">
                                                <i x-show="!img.preview" class="fa-solid fa-image text-slate-300"></i>
                                            </div>

                                            <select :name="'image_types['+index+']'" x-model="img.type" @change="img.preview = ''" class="w-24 rounded-md border-slate-200 text-xs focus:ring-blue-500 h-8 p-1">
                                                <option value="url">Link/URL</option>
                                                <option value="file">Archivo PC</option>
                                            </select>
                                            
                                            <div class="flex-1">
                                                <input x-show="img.type === 'url'" type="url" :name="'image_urls['+index+']'" x-model="img.url" @input="img.preview = img.url" placeholder="https://..." class="w-full rounded-md border-slate-200 text-xs focus:ring-blue-500 h-8">
                                                <input x-show="img.type === 'file'" type="file" :name="'image_files['+index+']'" accept="image/*" @change="if($event.target.files.length) img.preview = URL.createObjectURL($event.target.files[0]); else img.preview = '';" class="w-full text-xs text-slate-500 file:mr-2 file:py-1 file:px-2 file:rounded-md file:border-0 file:text-[10px] file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 mt-0.5">
                                            </div>
                                            
                                            <button type="button" @click="removeImage(index)" class="w-8 h-8 flex items-center justify-center text-slate-400 hover:text-red-500 bg-slate-50 hover:bg-red-50 rounded-md"><i class="fa-solid fa-xmark text-xs"></i></button>
                                        </div>
                                    </template>
                                    
                                    <button type="button" @click="addImage()" x-show="images.length < 5" class="w-full py-2 border-2 border-dashed border-slate-300 rounded-lg text-xs font-bold text-slate-500 hover:text-blue-600 hover:border-blue-400 hover:bg-blue-50 transition-colors">
                                        <i class="fa-solid fa-image mr-1"></i> Agregar Imagen
                                    </button>
                                </div>

                                <!-- Flags -->
                                <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 mt-6">Opciones de Visibilidad</h4>
                                <div class="bg-white border border-slate-200 rounded-xl p-4 space-y-3 shadow-sm">
                                    <label class="flex items-center gap-3 cursor-pointer">
                                        <input type="checkbox" name="status" x-model="product.status" class="w-4 h-4 text-green-600 rounded border-slate-300 focus:ring-green-500">
                                        <span class="text-sm text-slate-700 font-medium">Producto Activo / Visible</span>
                                    </label>
                                    <label class="flex items-center gap-3 cursor-pointer">
                                        <input type="checkbox" name="is_offer" x-model="product.is_offer" class="w-4 h-4 text-red-600 rounded border-slate-300 focus:ring-red-500">
                                        <span class="text-sm text-slate-700 font-medium">Etiqueta "Oferta Especial"</span>
                                    </label>
                                    <label class="flex items-center gap-3 cursor-pointer">
                                        <input type="checkbox" name="is_new" x-model="product.is_new" class="w-4 h-4 text-blue-600 rounded border-slate-300 focus:ring-blue-500">
                                        <span class="text-sm text-slate-700 font-medium">Etiqueta "Nuevo"</span>
                                    </label>
                                    <label class="flex items-center gap-3 cursor-pointer">
                                        <input type="checkbox" name="is_featured" x-model="product.is_featured" class="w-4 h-4 text-amber-500 rounded border-slate-300 focus:ring-amber-500">
                                        <span class="text-sm text-slate-700 font-medium">Destacar en Inicio (Hot Deals)</span>
                                    </label>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1 mt-4">Garantía Oficial</label>
                                    <input type="text" name="warranty" x-model="product.warranty" placeholder="Ej: 1 Año" class="w-full rounded-lg border-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>

                            </div>
                        </div>

                        <!-- Modal Footer -->
                        <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-end gap-3">
                            <button type="button" @click="closeModal()" class="px-4 py-2 bg-white border border-slate-300 text-slate-700 rounded-lg hover:bg-slate-50 transition-colors text-sm font-bold shadow-sm">
                                Cancelar
                            </button>
                            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm font-bold shadow-md shadow-blue-500/20 flex items-center gap-2">
                                <i class="fa-solid fa-floppy-disk"></i> <span x-text="isEditing ? 'Guardar Cambios' : 'Registrar Producto'"></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Scanner Modal Overlay -->
    <div class="fixed inset-0 z-[200] bg-black/90 flex flex-col items-center justify-center" x-show="showScanner" x-cloak x-transition.opacity>
        <div class="w-full max-w-lg bg-white rounded-xl overflow-hidden shadow-2xl relative">
            <div class="px-4 py-3 bg-slate-800 text-white flex justify-between items-center">
                <h3 class="font-bold text-sm flex items-center gap-2"><i class="fa-solid fa-barcode"></i> Escaneando Código de Barras</h3>
                <button type="button" @click="stopScanner()" class="text-slate-400 hover:text-white"><i class="fa-solid fa-xmark text-lg"></i></button>
            </div>
            <div class="p-4 bg-black relative">
                <!-- Div for html5-qrcode -->
                <div id="reader" class="w-full overflow-hidden rounded-lg bg-black min-h-[300px]"></div>
            </div>
            <div class="px-4 py-3 bg-slate-100 text-center text-xs text-slate-600 font-medium">
                Apunta la cámara al código de barras o QR del producto.
            </div>
        </div>
    </div>
</div>

<style>
.custom-scrollbar::-webkit-scrollbar { width: 6px; }
.custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
.custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
.custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
/* Override scanner UI slightly */
#reader button { background: #2563eb; color: white; padding: 5px 10px; border-radius: 5px; font-size: 12px; border: none; margin: 5px; cursor: pointer; }
#reader select { padding: 5px; border-radius: 5px; border: 1px solid #ccc; font-size: 12px; margin: 5px; }
</style>
@endsection

@push('scripts')
<script src="https://unpkg.com/html5-qrcode"></script>
<script>
function productManager() {
    return {
        showModal: false,
        showScanner: false,
        scannerTarget: null, // 'code' or 'serial_number'
        html5QrcodeScanner: null,
        isEditing: false,
        formAction: '{{ route('admin.products.store') }}',
        
        // Base structure
        product: {
            name: '', code: '', sku: '', serial_number: '', category_id: '', brand_id: '', description: '',
            price: '', offer_price: '', stock: 1, warranty: '',
            status: true, is_offer: false, is_new: true, is_featured: false
        },
        specs: [],
        images: [],

        openCreateModal() {
            this.isEditing = false;
            this.formAction = '{{ route('admin.products.store') }}';
            this.product = {
                name: '', code: '', sku: '', serial_number: '', category_id: '', brand_id: '', description: '',
                price: '', offer_price: '', stock: 1, warranty: '1 Año',
                status: true, is_offer: false, is_new: true, is_featured: false
            };
            this.specs = [{key: '', value: ''}];
            this.images = [{type: 'url', url: '', preview: ''}];
            this.showModal = true;
        },

        openEditModal(existingProduct) {
            this.isEditing = true;
            this.formAction = '/admin/products/' + existingProduct.id;
            
            // Map basic fields
            this.product = { ...existingProduct };
            
            // Map technical specs from object/array to array of key-value pairs
            this.specs = [];
            if (existingProduct.technical_specs && typeof existingProduct.technical_specs === 'object') {
                for (const [key, value] of Object.entries(existingProduct.technical_specs)) {
                    this.specs.push({ key: key, value: value });
                }
            }
            if(this.specs.length === 0) this.specs.push({key:'', value:''});

            // Images are empty for edit unless user wants to overwrite
            this.images = [];

            this.showModal = true;
        },

        closeModal() {
            this.showModal = false;
        },

        addSpec() {
            this.specs.push({key: '', value: ''});
        },
        removeSpec(idx) {
            this.specs.splice(idx, 1);
        },

        addImage() {
            if(this.images.length < 5) {
                this.images.push({type: 'url', url: '', preview: ''});
            }
        },
        removeImage(idx) {
            this.images.splice(idx, 1);
        },

        // --- Camera Scanner Logic ---
        startScanner(targetField) {
            this.scannerTarget = targetField;
            this.showScanner = true;
            
            // Initialize scanner if not already
            if (!this.html5QrcodeScanner) {
                this.html5QrcodeScanner = new Html5QrcodeScanner("reader", { 
                    fps: 10, 
                    qrbox: {width: 250, height: 150},
                    supportedScanTypes: [Html5QrcodeScanType.SCAN_TYPE_CAMERA]
                }, false);
            }

            // Render scanner
            setTimeout(() => {
                this.html5QrcodeScanner.render((decodedText, decodedResult) => {
                    // Success callback
                    if (this.scannerTarget === 'code') {
                        this.product.code = decodedText;
                        
                        // Buscar en BD si el producto ya existe y precargar el nombre
                        fetch(`/admin/products/search-by-code?code=${encodeURIComponent(decodedText)}`)
                            .then(res => res.json())
                            .then(data => {
                                if (data.found && data.product) {
                                    this.product.name = data.product.name;
                                    // Opcional: Mostrar una alerta sutil
                                    if(typeof Swal !== 'undefined') {
                                        Swal.fire({
                                            title: 'Producto encontrado',
                                            text: 'Se ha precargado el nombre: ' + data.product.name,
                                            icon: 'info',
                                            timer: 2000,
                                            showConfirmButton: false
                                        });
                                    }
                                }
                            })
                            .catch(err => console.error("Error buscando código:", err));
                            
                    } else if (this.scannerTarget === 'serial_number') {
                        this.product.serial_number = decodedText;
                    }
                    // Stop scanning
                    this.stopScanner();
                }, (errorMessage) => {
                    // ignore errors (it scans continuously)
                });
            }, 100);
        },

        stopScanner() {
            if (this.html5QrcodeScanner) {
                this.html5QrcodeScanner.clear().catch(error => {
                    console.error("Failed to clear html5QrcodeScanner. ", error);
                });
            }
            this.showScanner = false;
        }
    }
}
</script>
@endpush
