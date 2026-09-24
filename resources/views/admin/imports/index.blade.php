@extends('layouts.admin')
@section('header_title', 'Importar Productos')
@section('content')

<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div class="flex items-center gap-3">
        <div class="h-10 w-10 bg-indigo-600 rounded-xl flex items-center justify-center text-white shadow-lg shadow-indigo-200">
            <i class="fa-solid fa-file-import"></i>
        </div>
        <div>
            <h2 class="text-xl font-extrabold text-slate-800">Importación Masiva</h2>
            <p class="text-xs text-slate-500">Agrega o actualiza productos masivamente usando Excel o CSV.</p>
        </div>
    </div>
    <div class="text-sm font-medium text-slate-500 flex items-center gap-2">
        <a href="{{ route('admin.products.index') }}" class="hover:text-indigo-600"><i class="fa-solid fa-home text-xs"></i> Productos</a>
        <i class="fa-solid fa-chevron-right text-[10px]"></i>
        <span class="text-indigo-700">Importar</span>
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

<div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
    
    <!-- Left Column (Form) -->
    <div class="lg:col-span-8">
        <form action="{{ route('admin.imports.store') }}" method="POST" enctype="multipart/form-data" 
              x-data="importForm()" 
              class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 relative overflow-hidden">
            @csrf
            <div class="absolute top-0 left-0 w-1 h-full bg-indigo-500"></div>
            
            <h3 class="text-base font-extrabold text-slate-800 mb-5 flex items-center gap-2">
                <span class="flex items-center justify-center w-6 h-6 rounded-full bg-indigo-100 text-indigo-700 text-xs">1</span> 
                Seleccionar Archivo
            </h3>

            <div class="border-2 border-dashed border-indigo-200 rounded-xl p-8 text-center bg-slate-50/50 hover:bg-slate-50 hover:border-indigo-400 transition-colors cursor-pointer group relative"
                 @click="$refs.fileInput.click()">
                
                <template x-if="!fileName">
                    <div>
                        <div class="w-16 h-16 bg-white rounded-full shadow-sm flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform">
                            <i class="fa-solid fa-file-excel text-3xl text-indigo-400"></i>
                        </div>
                        <p class="text-sm font-bold text-indigo-700">Haz clic o arrastra tu archivo aquí</p>
                        <p class="text-xs text-slate-500 mt-2">Soporta archivos XLSX, XLS y CSV (Máx 5MB)</p>
                    </div>
                </template>

                <template x-if="fileName">
                    <div>
                        <div class="w-16 h-16 bg-indigo-100 rounded-full shadow-sm flex items-center justify-center mx-auto mb-4 text-indigo-600">
                            <i class="fa-solid fa-check text-3xl"></i>
                        </div>
                        <p class="text-sm font-bold text-indigo-700" x-text="fileName"></p>
                        <p class="text-xs text-slate-500 mt-2">Archivo seleccionado. Haz clic en "Iniciar Importación".</p>
                        <button type="button" @click.stop="clearFile()" class="mt-3 text-xs text-red-500 hover:text-red-700 font-bold">Cambiar archivo</button>
                    </div>
                </template>

                <input type="file" name="file" class="hidden" x-ref="fileInput" @change="handleFileSelect" accept=".xlsx,.xls,.csv" required>
            </div>

            <div class="mt-8 flex justify-end">
                <button type="submit" class="px-6 py-2.5 text-sm font-bold text-white bg-indigo-600 rounded-xl shadow-lg shadow-indigo-200 hover:bg-indigo-700 transition-colors flex items-center gap-2"
                        :class="!fileName ? 'opacity-50 cursor-not-allowed' : ''" :disabled="!fileName">
                    <i class="fa-solid fa-cloud-arrow-up"></i> Iniciar Importación
                </button>
            </div>
        </form>
    </div>

    <!-- Right Column (Instructions) -->
    <div class="lg:col-span-4 space-y-6">
        <section class="bg-gradient-to-br from-indigo-50 to-blue-50 rounded-2xl shadow-sm border border-indigo-100 p-5 relative overflow-hidden">
            <h3 class="text-sm font-extrabold text-indigo-900 mb-4 flex items-center gap-2">
                <i class="fa-solid fa-circle-info text-indigo-500"></i> Instrucciones
            </h3>
            
            <p class="text-xs text-indigo-800/80 mb-4 leading-relaxed">
                El archivo debe tener las siguientes columnas en la primera fila. Las columnas marcadas con (*) son obligatorias:
            </p>

            <ul class="space-y-2 text-xs text-indigo-900 mb-6 font-medium">
                <li class="flex gap-2 items-start"><i class="fa-solid fa-check text-indigo-400 mt-0.5"></i> <span><strong>code *</strong> : Código Oficial (P/N)</span></li>
                <li class="flex gap-2 items-start"><i class="fa-solid fa-check text-indigo-400 mt-0.5"></i> <span><strong>name *</strong> : Nombre del Producto</span></li>
                <li class="flex gap-2 items-start"><i class="fa-solid fa-check text-indigo-400 mt-0.5"></i> <span><strong>price *</strong> : Precio Venta (S/)</span></li>
                <li class="flex gap-2 items-start"><i class="fa-solid fa-check text-indigo-400 mt-0.5"></i> <span><strong>stock *</strong> : Stock Actual</span></li>
                <li class="flex gap-2 items-start"><i class="fa-solid fa-check text-indigo-400 mt-0.5"></i> <span><strong>category_id *</strong> : ID de Categoría</span></li>
                <li class="flex gap-2 items-start"><i class="fa-solid fa-check text-indigo-400 mt-0.5"></i> <span><strong>brand_id *</strong> : ID de Marca</span></li>
                <li class="flex gap-2 items-start"><i class="fa-solid fa-minus text-slate-400 mt-0.5"></i> <span class="text-slate-500"><strong>control_type</strong> : quantity, serial, o lot</span></li>
                <li class="flex gap-2 items-start"><i class="fa-solid fa-minus text-slate-400 mt-0.5"></i> <span class="text-slate-500"><strong>serial_number</strong> : (Solo si aplica)</span></li>
            </ul>

            <a href="{{ route('admin.imports.template') }}" class="flex items-center justify-center gap-2 w-full px-4 py-2 bg-white text-indigo-700 border border-indigo-200 rounded-xl text-xs font-bold hover:bg-indigo-50 transition-colors shadow-sm">
                <i class="fa-solid fa-download"></i> Descargar Plantilla
            </a>
        </section>

        <!-- Export -->
        <section class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 relative overflow-hidden">
            <h3 class="text-sm font-extrabold text-slate-800 mb-2 flex items-center gap-2">
                <i class="fa-solid fa-file-export text-emerald-500"></i> Exportar
            </h3>
            <p class="text-xs text-slate-500 mb-4">Descarga todo tu catálogo actual en un archivo Excel.</p>
            <a href="{{ route('admin.imports.export') }}" class="flex items-center justify-center gap-2 w-full px-4 py-2 bg-emerald-50 text-emerald-700 rounded-xl text-xs font-bold hover:bg-emerald-100 transition-colors">
                <i class="fa-solid fa-download"></i> Exportar Productos
            </a>
        </section>
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('importForm', () => ({
            fileName: '',
            
            handleFileSelect(event) {
                const files = event.target.files;
                if (files.length > 0) {
                    this.fileName = files[0].name;
                }
            },
            clearFile() {
                this.fileName = '';
                this.$refs.fileInput.value = '';
            }
        }))
    });
</script>
@endpush
