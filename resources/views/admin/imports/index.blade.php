@extends('layouts.admin')
@section('header_title', 'Importación Masiva de Productos')
@section('content')

<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div class="flex items-center gap-3">
        <div class="h-11 w-11 bg-indigo-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-indigo-600/25">
            <i class="fa-solid fa-file-excel text-xl"></i>
        </div>
        <div>
            <h2 class="text-xl font-extrabold text-slate-800">Importación y Exportación Masiva</h2>
            <p class="text-xs text-slate-500">Carga o actualiza tu catálogo completo con especificaciones técnicas, precios, stock y garantías.</p>
        </div>
    </div>

    <!-- Top Action Buttons -->
    <div class="flex flex-wrap items-center gap-2.5">
        <a href="{{ route('admin.imports.export') }}" class="px-4 py-2 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-xl text-xs font-bold hover:bg-emerald-100 transition-colors flex items-center gap-2">
            <i class="fa-solid fa-file-export text-emerald-600"></i> Exportar Productos (.XLS)
        </a>
        <a href="{{ route('admin.imports.template') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-xl text-xs font-bold hover:bg-indigo-700 shadow-md shadow-indigo-600/25 transition-colors flex items-center gap-2">
            <i class="fa-solid fa-download"></i> Descargar Plantilla Completa
        </a>
    </div>
</div>

@if(session('success'))
    <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3.5 rounded-2xl flex items-center gap-3 shadow-sm">
        <i class="fa-solid fa-circle-check text-emerald-600 text-lg flex-shrink-0"></i>
        <span class="text-xs sm:text-sm font-medium">{{ session('success') }}</span>
    </div>
@endif

@if(session('error'))
    <div class="mb-6 bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3.5 rounded-2xl flex items-center gap-3 shadow-sm">
        <i class="fa-solid fa-circle-exclamation text-rose-600 text-lg flex-shrink-0"></i>
        <span class="text-xs sm:text-sm font-medium">{{ session('error') }}</span>
    </div>
@endif

<div x-data="importForm()" class="space-y-6">
    
    <!-- Upload Section -->
    <div x-show="!parsedData.length" class="bg-white rounded-3xl shadow-sm border border-slate-100 p-6 sm:p-10 text-center relative overflow-hidden">
        <div class="max-w-2xl mx-auto">
            
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-50 border border-indigo-100 text-indigo-700 text-xs font-bold uppercase tracking-wider mb-4">
                <i class="fa-solid fa-table-cells text-[10px]"></i> Plantilla 100% Actualizada
            </div>

            <h3 class="text-xl font-extrabold text-slate-900 mb-2">Cargar Archivo Excel de Productos</h3>
            <p class="text-xs text-slate-500 mb-6 max-w-lg mx-auto leading-relaxed">
                Puedes importar información básica, números de serie, precios mínimos, ofertas, garantía y todas las especificaciones técnicas (procesador, RAM, disco, pantalla, gráficos, OS).
            </p>
            
            <!-- Drag & Drop Zone -->
            <div class="border-2 border-dashed border-indigo-200 rounded-3xl p-8 sm:p-12 text-center bg-indigo-50/20 hover:bg-indigo-50/50 hover:border-indigo-400 transition-all cursor-pointer group"
                 @click="$refs.fileInput.click()"
                 @dragover.prevent="$el.classList.add('bg-indigo-100/50', 'border-indigo-500')"
                 @dragleave.prevent="$el.classList.remove('bg-indigo-100/50', 'border-indigo-500')"
                 @drop.prevent="handleDrop($event); $el.classList.remove('bg-indigo-100/50', 'border-indigo-500')">
                
                <div class="w-20 h-20 bg-white rounded-2xl shadow-md border border-slate-100 flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-file-excel text-4xl text-emerald-600"></i>
                </div>
                <p class="text-base font-bold text-indigo-900">Haz clic o arrastra tu archivo Excel aquí</p>
                <p class="text-xs text-slate-500 mt-1">Archivos compatibles: <span class="font-bold text-slate-700">.XLSX, .XLS o .CSV</span></p>
                
                <div class="mt-4 inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-white border border-slate-200 text-slate-600 text-xs font-semibold shadow-xs">
                    <i class="fa-solid fa-arrow-up-from-bracket text-[10px] text-indigo-600"></i> Seleccionar archivo del equipo
                </div>

                <input type="file" class="hidden" x-ref="fileInput" @change="handleFileSelect" accept=".xlsx,.xls,.csv">
            </div>

            <!-- Quick Template download link -->
            <div class="mt-6 flex flex-wrap justify-center gap-3">
                <a href="{{ route('admin.imports.template') }}" class="px-5 py-2.5 bg-slate-100 text-slate-700 rounded-xl text-xs font-bold hover:bg-slate-200 transition-colors flex items-center gap-2">
                    <i class="fa-solid fa-download text-indigo-600"></i> Descargar Plantilla Oficial con Ejemplos
                </a>
            </div>

        </div>

        <!-- Columns Reference Guide -->
        <div class="mt-10 pt-8 border-t border-slate-100 text-left">
            <h4 class="text-xs font-extrabold uppercase tracking-wider text-slate-700 mb-3 flex items-center gap-2">
                <i class="fa-solid fa-circle-info text-indigo-500"></i> Columnas Compatibles en la Plantilla Excel:
            </h4>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 text-xs">
                <!-- Group 1 -->
                <div class="p-3 bg-slate-50 rounded-xl border border-slate-200/70">
                    <span class="block font-bold text-indigo-700 mb-1.5">1. Identificación</span>
                    <ul class="space-y-1 text-[11px] text-slate-600">
                        <li><code>code</code> (Código único / SKU)</li>
                        <li><code>sku</code> (SKU interno opcional)</li>
                        <li><code>serial_number</code> (Número de Serie)</li>
                    </ul>
                </div>

                <!-- Group 2 -->
                <div class="p-3 bg-slate-50 rounded-xl border border-slate-200/70">
                    <span class="block font-bold text-indigo-700 mb-1.5">2. Información</span>
                    <ul class="space-y-1 text-[11px] text-slate-600">
                        <li><code>name</code> (Nombre completo)</li>
                        <li><code>category</code> (Categoría)</li>
                        <li><code>subcategory</code> (Subcategoría)</li>
                        <li><code>brand</code> (Marca)</li>
                        <li><code>device_model</code> (Modelo)</li>
                        <li><code>description</code> (Descripción)</li>
                    </ul>
                </div>

                <!-- Group 3 -->
                <div class="p-3 bg-slate-50 rounded-xl border border-slate-200/70">
                    <span class="block font-bold text-amber-700 mb-1.5">3. Precios & Garantía</span>
                    <ul class="space-y-1 text-[11px] text-slate-600">
                        <li><code>price</code> (Precio de Venta)</li>
                        <li><code>min_price</code> (Precio Mínimo)</li>
                        <li><code>offer_price</code> (Precio Oferta)</li>
                        <li><code>stock</code> (Stock disponible)</li>
                        <li><code>state</code> (Nuevo/Seminuevo)</li>
                        <li><code>warranty</code> (Garantía ej. 1 año)</li>
                    </ul>
                </div>

                <!-- Group 4 -->
                <div class="p-3 bg-slate-50 rounded-xl border border-slate-200/70">
                    <span class="block font-bold text-emerald-700 mb-1.5">4. Especificaciones Técnicas</span>
                    <ul class="space-y-1 text-[11px] text-slate-600">
                        <li><code>processor</code> (Procesador)</li>
                        <li><code>ram</code> (Memoria RAM)</li>
                        <li><code>storage</code> (Disco / Almacenamiento)</li>
                        <li><code>screen</code> (Pantalla)</li>
                        <li><code>graphics</code> (Tarjeta Gráfica)</li>
                        <li><code>operating_system</code> (Sistema Op.)</li>
                        <li><code>image_url</code> (URL imagen)</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Preview & Edit Section -->
    <div x-show="parsedData.length > 0" x-cloak class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-5 sm:p-6 border-b border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row justify-between items-center gap-4">
            <div>
                <h3 class="text-base font-bold text-slate-900 flex items-center gap-2">
                    <i class="fa-solid fa-table-list text-indigo-600"></i> Vista Previa de Productos Detectados
                </h3>
                <p class="text-xs text-slate-500 mt-1">
                    Se detectaron <span x-text="parsedData.length" class="font-bold text-indigo-600"></span> productos. Puedes corregir los campos directamente antes de guardar.
                </p>
            </div>
            <div class="flex items-center gap-3 w-full sm:w-auto justify-end">
                <button type="button" @click="clearData()" class="px-4 py-2 bg-white border border-slate-200 text-slate-700 rounded-xl text-xs font-bold hover:bg-slate-100 transition-colors flex items-center gap-1.5">
                    <i class="fa-solid fa-rotate-left"></i> Cambiar Archivo
                </button>
                
                <form action="{{ route('admin.imports.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="products" :value="JSON.stringify(parsedData)">
                    <button type="submit" class="px-5 py-2 bg-indigo-600 text-white rounded-xl text-xs font-bold hover:bg-indigo-700 shadow-md shadow-indigo-600/30 transition-colors flex items-center gap-2">
                        <i class="fa-solid fa-cloud-arrow-up"></i> Guardar <span x-text="parsedData.length"></span> Productos
                    </button>
                </form>
            </div>
        </div>
        
        <div class="overflow-x-auto max-h-[600px]">
            <table class="w-full text-left text-xs whitespace-nowrap">
                <thead class="bg-slate-100/80 text-slate-700 uppercase font-bold border-b border-slate-200 sticky top-0 z-10">
                    <tr>
                        <th class="px-3 py-3">Código</th>
                        <th class="px-3 py-3">Nombre del Producto</th>
                        <th class="px-3 py-3">Categoría</th>
                        <th class="px-3 py-3">Marca</th>
                        <th class="px-3 py-3">Precio (S/)</th>
                        <th class="px-3 py-3">P. Mínimo</th>
                        <th class="px-3 py-3">P. Oferta</th>
                        <th class="px-3 py-3">Stock</th>
                        <th class="px-3 py-3">Garantía</th>
                        <th class="px-3 py-3">Procesador</th>
                        <th class="px-3 py-3">RAM</th>
                        <th class="px-3 py-3">Almacenamiento</th>
                        <th class="px-3 py-3">Pantalla</th>
                        <th class="px-3 py-3">Gráficos</th>
                        <th class="px-3 py-3 text-center">Acción</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    <template x-for="(row, index) in parsedData" :key="index">
                        <tr class="hover:bg-indigo-50/40 transition-colors">
                            <td class="px-3 py-2">
                                <input type="text" x-model="row.code" class="w-24 px-2 py-1 text-xs border border-transparent hover:border-slate-300 focus:border-indigo-500 rounded bg-transparent focus:bg-white font-mono font-bold">
                            </td>
                            <td class="px-3 py-2">
                                <input type="text" x-model="row.name" class="w-56 px-2 py-1 text-xs border border-transparent hover:border-slate-300 focus:border-indigo-500 rounded bg-transparent focus:bg-white font-medium">
                            </td>
                            <td class="px-3 py-2">
                                <input type="text" x-model="row.category" class="w-24 px-2 py-1 text-xs border border-transparent hover:border-slate-300 focus:border-indigo-500 rounded bg-transparent focus:bg-white">
                            </td>
                            <td class="px-3 py-2">
                                <input type="text" x-model="row.brand" class="w-24 px-2 py-1 text-xs border border-transparent hover:border-slate-300 focus:border-indigo-500 rounded bg-transparent focus:bg-white">
                            </td>
                            <td class="px-3 py-2">
                                <input type="number" x-model="row.price" step="0.01" class="w-20 px-2 py-1 text-xs border border-transparent hover:border-slate-300 focus:border-indigo-500 rounded bg-transparent focus:bg-white text-right font-bold text-slate-900">
                            </td>
                            <td class="px-3 py-2">
                                <input type="number" x-model="row.min_price" step="0.01" class="w-20 px-2 py-1 text-xs border border-transparent hover:border-slate-300 focus:border-indigo-500 rounded bg-transparent focus:bg-white text-right text-slate-500">
                            </td>
                            <td class="px-3 py-2">
                                <input type="number" x-model="row.offer_price" step="0.01" class="w-20 px-2 py-1 text-xs border border-transparent hover:border-slate-300 focus:border-indigo-500 rounded bg-transparent focus:bg-white text-right text-emerald-600 font-bold">
                            </td>
                            <td class="px-3 py-2">
                                <input type="number" x-model="row.stock" class="w-16 px-2 py-1 text-xs border border-transparent hover:border-slate-300 focus:border-indigo-500 rounded bg-transparent focus:bg-white text-center font-bold">
                            </td>
                            <td class="px-3 py-2">
                                <input type="text" x-model="row.warranty" class="w-20 px-2 py-1 text-xs border border-transparent hover:border-slate-300 focus:border-indigo-500 rounded bg-transparent focus:bg-white">
                            </td>
                            <td class="px-3 py-2">
                                <input type="text" x-model="row.processor" class="w-28 px-2 py-1 text-xs border border-transparent hover:border-slate-300 focus:border-indigo-500 rounded bg-transparent focus:bg-white" placeholder="Ej. Core i7">
                            </td>
                            <td class="px-3 py-2">
                                <input type="text" x-model="row.ram" class="w-24 px-2 py-1 text-xs border border-transparent hover:border-slate-300 focus:border-indigo-500 rounded bg-transparent focus:bg-white" placeholder="Ej. 16GB">
                            </td>
                            <td class="px-3 py-2">
                                <input type="text" x-model="row.storage" class="w-24 px-2 py-1 text-xs border border-transparent hover:border-slate-300 focus:border-indigo-500 rounded bg-transparent focus:bg-white" placeholder="Ej. 512GB SSD">
                            </td>
                            <td class="px-3 py-2">
                                <input type="text" x-model="row.screen" class="w-24 px-2 py-1 text-xs border border-transparent hover:border-slate-300 focus:border-indigo-500 rounded bg-transparent focus:bg-white" placeholder='Ej. 15.6" FHD'>
                            </td>
                            <td class="px-3 py-2">
                                <input type="text" x-model="row.graphics" class="w-28 px-2 py-1 text-xs border border-transparent hover:border-slate-300 focus:border-indigo-500 rounded bg-transparent focus:bg-white" placeholder="Ej. RTX 4060">
                            </td>
                            <td class="px-3 py-2 text-center">
                                <button type="button" @click="parsedData.splice(index, 1)" class="text-rose-400 hover:text-rose-600 p-1.5 rounded hover:bg-rose-50 transition" title="Eliminar fila">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('importForm', () => ({
            parsedData: [],
            
            handleFileSelect(event) {
                this.processFile(event.target.files[0]);
            },
            
            handleDrop(event) {
                if (event.dataTransfer.files.length > 0) {
                    this.processFile(event.dataTransfer.files[0]);
                }
            },
            
            processFile(file) {
                if (!file) return;
                
                const reader = new FileReader();
                reader.onload = (e) => {
                    const data = new Uint8Array(e.target.result);
                    const workbook = XLSX.read(data, {type: 'array'});
                    
                    const firstSheetName = workbook.SheetNames[0];
                    const worksheet = workbook.Sheets[firstSheetName];
                    
                    const cleanEncoding = (str) => {
                        if (!str) return '';
                        const replacements = {
                            'Ã¡': 'á', 'Ã©': 'é', 'Ã­': 'í', 'Ã³': 'ó', 'Ãº': 'ú', 'Ã±': 'ñ',
                            'Ã ': 'Á', 'Ã‰': 'É', 'Ã ': 'Í', 'Ã“': 'Ó', 'Ãš': 'Ú', 'Ã‘': 'Ñ',
                            'Ã¼': 'ü', 'Ãœ': 'Ü',
                            'Â¿': '¿', 'Â¡': '¡', 'Â°': '°', 'Âº': 'º', 'Âª': 'ª',
                            'â€œ': '“', 'â€ ': '”', 'â€˜': '‘', 'â€™': '’', 'â€“': '–', 'â€”': '—',
                            'Â ': ' '
                        };
                        let res = String(str);
                        for (const [bad, good] of Object.entries(replacements)) {
                            res = res.split(bad).join(good);
                        }
                        return res;
                    };

                    this.parsedData = json.map(row => {
                        const getVal = (possibleKeys) => {
                            for (let key of possibleKeys) {
                                const found = Object.keys(row).find(k => k.toLowerCase().trim() === key.toLowerCase().trim());
                                if (found && row[found] !== undefined && row[found] !== null && String(row[found]).trim() !== '') {
                                    return cleanEncoding(String(row[found]).trim());
                                }
                            }
                            return '';
                        };
                        
                        return {
                            code: getVal(['code', 'codigo', 'cod', 'sku']),
                            sku: getVal(['sku', 'sku_interno']),
                            serial_number: getVal(['serial_number', 'serie', 'numero_serie', 'nro_serie']),
                            name: getVal(['name', 'nombre', 'producto', 'titulo']),
                            category: getVal(['category', 'categoria', 'category_id', 'categoria_id']) || 'Laptops',
                            subcategory: getVal(['subcategory', 'subcategoria', 'subcategory_id', 'subcategoria_id']),
                            brand: getVal(['brand', 'marca', 'brand_id', 'marca_id']) || 'Genérica',
                            device_model: getVal(['device_model', 'modelo', 'device_model_id', 'modelo_id']),
                            control_type: getVal(['control_type', 'tipo_control', 'control']) || 'Por Cantidad',
                            description: getVal(['description', 'descripcion', 'detalle']),
                            price: getVal(['price', 'precio', 'precio_venta', 'precio_normal']) || '0',
                            min_price: getVal(['min_price', 'precio_minimo', 'p_minimo']),
                            offer_price: getVal(['offer_price', 'precio_oferta', 'oferta']),
                            stock: getVal(['stock', 'cantidad', 'existencias', 'cant']) || '10',
                            state: getVal(['state', 'estado', 'estado_producto']) || 'Nuevo',
                            warranty: getVal(['warranty', 'garantia']) || '1 año',
                            processor: getVal(['processor', 'procesador', 'cpu']),
                            ram: getVal(['ram', 'memoria_ram', 'memoria']),
                            storage: getVal(['storage', 'almacenamiento', 'disco', 'ssd', 'hdd']),
                            screen: getVal(['screen', 'pantalla', 'display']),
                            graphics: getVal(['graphics', 'graficos', 'tarjeta_grafica', 'gpu']),
                            operating_system: getVal(['operating_system', 'sistema_operativo', 'so', 'os']),
                            image_url: getVal(['image_url', 'imagen', 'image', 'foto']),
                            status: getVal(['status', 'activo', 'publicado']) !== '0' ? '1' : '0'
                        };
                    }).filter(row => row.code || row.name);
                };
                
                reader.readAsArrayBuffer(file);
            },
            
            clearData() {
                this.parsedData = [];
                if (this.$refs.fileInput) {
                    this.$refs.fileInput.value = '';
                }
            }
        }))
    });
</script>
@endpush
