@extends('layouts.admin')
@section('header_title', 'Nuevo Producto')
@section('content')
<div class="soft-card p-6 max-w-4xl mx-auto">
    <form action="{{ route('admin.products.store') }}" method="POST">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            <div class="col-span-2 md:col-span-1">
                <label class="block text-sm font-medium text-slate-700">Nombre del Producto</label>
                <input type="text" name="name" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
            </div>
            
            <div class="relative">
                <label class="block text-sm font-medium text-slate-700">Código</label>
                <div class="mt-1 flex rounded-md shadow-sm">
                    <input type="text" name="code" id="product_code" class="block w-full rounded-none rounded-l-md border-slate-300 focus:border-blue-500 focus:ring-blue-500" required>
                    <button type="button" onclick="startScanner()" class="inline-flex items-center rounded-r-md border border-l-0 border-slate-300 bg-slate-50 px-3 text-sm text-slate-500 hover:bg-slate-100 focus:outline-none">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h4a1 1 0 010 2H5v3a1 1 0 01-2 0V4zm14-1a1 1 0 011 1v3a1 1 0 01-2 0V5h-3a1 1 0 010-2h4zM4 19a1 1 0 01-1-1v-3a1 1 0 012 0v3h3a1 1 0 010 2H5a1 1 0 01-1-1zm15 1a1 1 0 01-1-1h-3a1 1 0 010-2h4v-3a1 1 0 012 0v3a1 1 0 01-1 1h-1z"></path>
                        </svg>
                        Escanear
                    </button>
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-slate-700">SKU (Opcional)</label>
                <input type="text" name="sku" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700">Tipo de Control <span class="text-red-500">*</span></label>
                <select name="control_type" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                    <option value="quantity">Solo por Cantidad</option>
                    <option value="serial">Por Número de Serie (Unitario)</option>
                    <option value="lot">Por Lote</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700">Número de Serie (si corresponde)</label>
                <input type="text" name="serial_number" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-slate-700">Categoría</label>
                <select name="category_id" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                    <option value="">Seleccione...</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700">Marca</label>
                <select name="brand_id" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                    <option value="">Seleccione...</option>
                    @foreach($brands as $brand)
                        <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700">Modelo (Opcional)</label>
                <select name="device_model_id" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">Ninguno...</option>
                    @foreach($models as $model)
                        <option value="{{ $model->id }}">{{ $model->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-span-2">
                <label class="block text-sm font-medium text-slate-700">Descripción</label>
                <textarea name="description" rows="3" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
            </div>

            <div class="col-span-2">
                <label class="block text-sm font-medium text-slate-700">Especificaciones Técnicas (Una por línea. Formato: Propiedad: Valor)</label>
                <textarea name="technical_specs" rows="4" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Procesador: Intel Core i7&#10;RAM: 16GB&#10;Almacenamiento: 512GB SSD"></textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700">Precio (S/)</label>
                <input type="number" step="0.01" name="price" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700">Precio de Oferta (Opcional) (S/)</label>
                <input type="number" step="0.01" name="offer_price" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700">Stock</label>
                <input type="number" name="stock" value="0" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700">Garantía</label>
                <input type="text" name="warranty" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Ej: 1 Año">
            </div>

            <div class="col-span-2 grid grid-cols-3 gap-4 border-t border-slate-100 pt-4">
                <label class="flex items-center">
                    <input type="checkbox" name="status" value="1" class="rounded border-slate-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" checked>
                    <span class="ml-2 text-sm text-slate-600">Público (Activo)</span>
                </label>
                <label class="flex items-center">
                    <input type="checkbox" name="is_offer" value="1" class="rounded border-slate-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    <span class="ml-2 text-sm text-slate-600">En Oferta</span>
                </label>
                <label class="flex items-center">
                    <input type="checkbox" name="is_featured" value="1" class="rounded border-slate-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    <span class="ml-2 text-sm text-slate-600">Destacado</span>
                </label>
            </div>

            <div class="col-span-2 flex justify-end mt-4 pt-4 border-t border-slate-100">
                <a href="{{ route('admin.products.index') }}" class="mr-3 px-4 py-2 text-slate-600 hover:bg-slate-100 rounded-lg transition-colors">Cancelar</a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 shadow-sm transition-colors">Guardar Producto</button>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script>
    let html5QrcodeScanner = null;

    function startScanner() {
        // Mostrar el modal
        document.getElementById('scanner-modal').classList.remove('hidden');
        
        if (!html5QrcodeScanner) {
            html5QrcodeScanner = new Html5QrcodeScanner(
                "reader",
                { fps: 10, qrbox: {width: 250, height: 150} },
                false);
            
            html5QrcodeScanner.render(onScanSuccess, onScanFailure);
        }
    }

    function stopScanner() {
        document.getElementById('scanner-modal').classList.add('hidden');
        if (html5QrcodeScanner) {
            html5QrcodeScanner.clear();
            html5QrcodeScanner = null;
        }
    }

    function onScanSuccess(decodedText, decodedResult) {
        // Asignar el valor al input
        document.getElementById('product_code').value = decodedText;
        // Cerrar el modal
        stopScanner();
    }

    function onScanFailure(error) {
        // Errores o cuando no detecta un código (se llama repetidamente, usualmente se ignora)
    }
</script>

<!-- Scanner Modal -->
<div id="scanner-modal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" aria-hidden="true" onclick="stopScanner()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block px-4 pt-5 pb-4 overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
            <div>
                <div class="mt-3 text-center sm:mt-5">
                    <h3 class="text-lg font-medium leading-6 text-gray-900" id="modal-title">Escanear Código de Barras</h3>
                    <div class="mt-2">
                        <div id="reader" width="600px"></div>
                    </div>
                </div>
            </div>
            <div class="mt-5 sm:mt-6">
                <button type="button" onclick="stopScanner()" class="inline-flex justify-center w-full px-4 py-2 text-base font-medium text-white bg-red-600 border border-transparent rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:text-sm">
                    Cerrar y Cancelar
                </button>
            </div>
        </div>
    </div>
</div>
@endpush
