@extends('layouts.admin')
@section('header_title', 'Importar Productos')
@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-semibold text-slate-800">Importación Masiva (Excel/CSV)</h2>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-200 text-green-700 px-4 py-3 rounded-lg relative" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-100 border border-red-200 text-red-700 px-4 py-3 rounded-lg relative" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    <div class="soft-card p-6">
        <form action="{{ route('admin.imports.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="space-y-6">
                <div>
                    <h3 class="text-lg font-medium text-slate-900 mb-2">Instrucciones</h3>
                    <p class="text-sm text-slate-500 mb-4">Sube un archivo de Excel (.xlsx, .xls) o CSV con el formato requerido. Las columnas obligatorias son: <strong>code, name, price, stock, category_id, brand_id</strong>.</p>
                    
                    <a href="{{ route('admin.imports.template') }}" class="inline-flex items-center text-sm text-blue-600 hover:text-blue-800 font-medium">
                        <i class="fa-solid fa-download mr-1"></i> Descargar Plantilla de Ejemplo
                    </a>
                </div>

                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-slate-300 border-dashed rounded-md bg-slate-50">
                    <div class="space-y-1 text-center">
                        <i class="fa-solid fa-file-excel text-4xl text-slate-400 mb-3"></i>
                        <div class="flex text-sm text-slate-600 justify-center">
                            <label for="file-upload" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500 px-2 py-1">
                                <span>Sube un archivo</span>
                                <input id="file-upload" name="file" type="file" class="sr-only" accept=".xlsx,.xls,.csv" required>
                            </label>
                            <p class="pl-1 py-1">o arrastra y suelta aquí</p>
                        </div>
                        <p class="text-xs text-slate-500">
                            XLSX, XLS, CSV hasta 5MB
                        </p>
                    </div>
                </div>

                <div class="pt-5 border-t border-slate-200 flex justify-end">
                    <button type="submit" class="inline-flex justify-center py-2 px-6 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fa-solid fa-upload mr-2 mt-1"></i> Iniciar Importación
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
