@extends('layouts.admin')
@section('header_title', 'Importar Productos')
@section('content')

<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div class="flex items-center gap-3">
        <div class="h-10 w-10 bg-indigo-600 rounded-xl flex items-center justify-center text-white shadow-lg shadow-indigo-200">
            <i class="fa-solid fa-file-import"></i>
        </div>
        <div>
            <h2 class="text-xl font-extrabold text-slate-800">Importación Masiva de Excel</h2>
            <p class="text-xs text-slate-500">Sube tu Excel, verifica los datos y guárdalos en el sistema.</p>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl flex items-center gap-3 shadow-sm">
        <i class="fa-solid fa-circle-check text-green-500"></i>
        <span class="text-sm font-medium">{{ session('success') }}</span>
    </div>
@endif
@if(session('error'))
    <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl flex items-center gap-3 shadow-sm">
        <i class="fa-solid fa-circle-exclamation text-red-500"></i>
        <span class="text-sm font-medium">{{ session('error') }}</span>
    </div>
@endif

<div x-data="importForm()" class="space-y-6">
    
    <!-- Upload Section -->
    <div x-show="!parsedData.length" class="bg-white rounded-2xl shadow-sm border border-slate-100 p-8 text-center relative overflow-hidden">
        <div class="max-w-xl mx-auto">
            <h3 class="text-lg font-extrabold text-slate-800 mb-2">Cargar Archivo de Productos</h3>
            <p class="text-sm text-slate-500 mb-6">El archivo debe contener las columnas: code, name, price, stock, category_id, brand_id</p>
            
            <div class="border-2 border-dashed border-indigo-200 rounded-2xl p-10 text-center bg-indigo-50/30 hover:bg-indigo-50 hover:border-indigo-400 transition-colors cursor-pointer group"
                 @click="$refs.fileInput.click()"
                 @dragover.prevent="$el.classList.add('bg-indigo-100', 'border-indigo-500')"
                 @dragleave.prevent="$el.classList.remove('bg-indigo-100', 'border-indigo-500')"
                 @drop.prevent="handleDrop($event); $el.classList.remove('bg-indigo-100', 'border-indigo-500')">
                
                <div class="w-20 h-20 bg-white rounded-full shadow-sm flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-file-excel text-4xl text-green-500"></i>
                </div>
                <p class="text-base font-bold text-indigo-700">Haz clic o arrastra tu archivo Excel aquí</p>
                <p class="text-xs text-slate-500 mt-2">Soporta .XLSX, .XLS o .CSV</p>
                
                <input type="file" class="hidden" x-ref="fileInput" @change="handleFileSelect" accept=".xlsx,.xls,.csv">
            </div>

            <div class="mt-6 flex justify-center gap-4">
                <a href="{{ route('admin.imports.template') }}" class="px-5 py-2.5 bg-slate-100 text-slate-600 rounded-xl text-sm font-bold hover:bg-slate-200 transition-colors flex items-center gap-2">
                    <i class="fa-solid fa-download"></i> Descargar Plantilla
                </a>
            </div>
        </div>
    </div>

    <!-- Preview & Edit Section -->
    <div x-show="parsedData.length > 0" x-cloak class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-5 border-b border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row justify-between items-center gap-4">
            <div>
                <h3 class="text-base font-bold text-slate-800 flex items-center gap-2">
                    <i class="fa-solid fa-table-list text-indigo-500"></i> Vista Previa de Productos
                </h3>
                <p class="text-xs text-slate-500 mt-1">Revisa y edita los datos antes de subirlos al sistema. Mostrando <span x-text="parsedData.length" class="font-bold"></span> registros.</p>
            </div>
            <div class="flex items-center gap-3">
                <button type="button" @click="clearData()" class="px-4 py-2 bg-white border border-slate-200 text-slate-600 rounded-xl text-xs font-bold hover:bg-slate-50 transition-colors">
                    <i class="fa-solid fa-rotate-left"></i> Cambiar Archivo
                </button>
                
                <form action="{{ route('admin.imports.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="products" :value="JSON.stringify(parsedData)">
                    <button type="submit" class="px-5 py-2 bg-indigo-600 text-white rounded-xl text-sm font-bold hover:bg-indigo-700 shadow-lg shadow-indigo-200 transition-colors flex items-center gap-2">
                        <i class="fa-solid fa-cloud-arrow-up"></i> Guardar en el Sistema
                    </button>
                </form>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm whitespace-nowrap">
                <thead class="bg-slate-50 text-slate-600 text-xs uppercase font-bold border-b border-slate-200">
                    <tr>
                        <th class="px-4 py-3">Código</th>
                        <th class="px-4 py-3">Nombre</th>
                        <th class="px-4 py-3">Precio (S/)</th>
                        <th class="px-4 py-3">Stock</th>
                        <th class="px-4 py-3">ID Categoría</th>
                        <th class="px-4 py-3">ID Marca</th>
                        <th class="px-4 py-3 text-center">Acción</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    <template x-for="(row, index) in parsedData" :key="index">
                        <tr class="hover:bg-indigo-50/30 transition-colors">
                            <td class="px-4 py-2">
                                <input type="text" x-model="row.code" class="w-24 px-2 py-1 text-xs border border-transparent hover:border-slate-300 focus:border-indigo-500 rounded bg-transparent focus:bg-white focus:ring-1 focus:ring-indigo-500">
                            </td>
                            <td class="px-4 py-2">
                                <input type="text" x-model="row.name" class="w-48 lg:w-64 px-2 py-1 text-xs border border-transparent hover:border-slate-300 focus:border-indigo-500 rounded bg-transparent focus:bg-white focus:ring-1 focus:ring-indigo-500">
                            </td>
                            <td class="px-4 py-2">
                                <input type="number" x-model="row.price" step="0.01" class="w-20 px-2 py-1 text-xs border border-transparent hover:border-slate-300 focus:border-indigo-500 rounded bg-transparent focus:bg-white focus:ring-1 focus:ring-indigo-500 text-right">
                            </td>
                            <td class="px-4 py-2">
                                <input type="number" x-model="row.stock" class="w-16 px-2 py-1 text-xs border border-transparent hover:border-slate-300 focus:border-indigo-500 rounded bg-transparent focus:bg-white focus:ring-1 focus:ring-indigo-500 text-center">
                            </td>
                            <td class="px-4 py-2">
                                <input type="number" x-model="row.category_id" class="w-16 px-2 py-1 text-xs border border-transparent hover:border-slate-300 focus:border-indigo-500 rounded bg-transparent focus:bg-white focus:ring-1 focus:ring-indigo-500 text-center">
                            </td>
                            <td class="px-4 py-2">
                                <input type="number" x-model="row.brand_id" class="w-16 px-2 py-1 text-xs border border-transparent hover:border-slate-300 focus:border-indigo-500 rounded bg-transparent focus:bg-white focus:ring-1 focus:ring-indigo-500 text-center">
                            </td>
                            <td class="px-4 py-2 text-center">
                                <button type="button" @click="parsedData.splice(index, 1)" class="text-red-400 hover:text-red-600 p-1" title="Eliminar fila">
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
                    
                    // Convierte la hoja a un array de objetos
                    const json = XLSX.utils.sheet_to_json(worksheet, { defval: "" });
                    
                    // Mapear al formato esperado si las columnas difieren ligeramente (case-insensitive)
                    this.parsedData = json.map(row => {
                        const getVal = (keyStr) => {
                            const foundKey = Object.keys(row).find(k => k.toLowerCase().includes(keyStr.toLowerCase()));
                            return foundKey ? row[foundKey] : '';
                        };
                        
                        return {
                            code: getVal('code') || getVal('codigo'),
                            name: getVal('name') || getVal('nombre'),
                            price: getVal('price') || getVal('precio'),
                            stock: getVal('stock') || getVal('cantidad'),
                            category_id: getVal('category_id') || getVal('categoria_id') || getVal('categoria') || '1',
                            brand_id: getVal('brand_id') || getVal('marca_id') || getVal('marca') || '1'
                        };
                    }).filter(row => row.code || row.name); // Filtrar filas totalmente vacías
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
