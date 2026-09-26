@extends('layouts.admin')
@section('header_title', 'Generar Catálogo PDF')
@section('content')

<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div class="flex items-center gap-3">
        <div class="h-10 w-10 bg-indigo-600 rounded-xl flex items-center justify-center text-white shadow-lg shadow-indigo-200">
            <i class="fa-solid fa-file-pdf"></i>
        </div>
        <div>
            <h2 class="text-xl font-extrabold text-slate-800">Generador de Catálogos PDF</h2>
            <p class="text-xs text-slate-500">Configura la presentación visual de tu catálogo antes de descargarlo.</p>
        </div>
    </div>
</div>
<div x-data="{
    pdfTitle: '',
    pdfShowSpecs: true,
    pdfShowPrice: true,
    pdfOnlyOffers: false,
    pdfBankAccounts: '',
    pdfAddress: ''
}" class="grid grid-cols-1 lg:grid-cols-12 gap-6">
    <!-- Formulario -->
    <div class="lg:col-span-6 xl:col-span-6">
        <form action="{{ route('admin.pdf.generate') }}" method="POST" class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 relative overflow-hidden">
            @csrf
            <div class="absolute top-0 left-0 w-1 h-full bg-red-500"></div>
            
            <h3 class="text-base font-extrabold text-slate-800 mb-6 flex items-center gap-2">
                <i class="fa-solid fa-sliders text-red-500"></i> Opciones de Diseño y Filtros
            </h3>

            <div class="space-y-6">
                <!-- Título -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1">Título Personalizado del Catálogo</label>
                    <input type="text" name="custom_title" x-model="pdfTitle" placeholder="Ej. Catálogo Oficial - Promociones de Verano" class="w-full px-4 py-2 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-shadow">
                    <p class="text-[11px] text-slate-500 mt-1">Déjalo en blanco para usar el nombre de la tienda.</p>
                </div>

                <!-- Switches -->
                <div class="grid grid-cols-1 gap-4">
                    <label class="flex items-center p-4 border border-slate-100 rounded-xl cursor-pointer hover:bg-slate-50 transition-colors">
                        <input type="checkbox" name="show_specs" value="1" :checked="pdfShowSpecs" @change="pdfShowSpecs = $event.target.checked" class="w-5 h-5 text-red-600 border-gray-300 rounded focus:ring-red-500">
                        <span class="ml-3">
                            <span class="block text-sm font-bold text-slate-700">Mostrar Especificaciones</span>
                            <span class="block text-[10px] text-slate-500">Muestra los detalles técnicos de cada producto</span>
                        </span>
                    </label>

                    <label class="flex items-center p-4 border border-slate-100 rounded-xl cursor-pointer hover:bg-slate-50 transition-colors">
                        <input type="checkbox" name="show_price" value="1" :checked="pdfShowPrice" @change="pdfShowPrice = $event.target.checked" class="w-5 h-5 text-red-600 border-gray-300 rounded focus:ring-red-500">
                        <span class="ml-3">
                            <span class="block text-sm font-bold text-slate-700">Mostrar Precio</span>
                            <span class="block text-[10px] text-slate-500">Incluye el precio de venta en el catálogo</span>
                        </span>
                    </label>

                    <label class="flex items-center p-4 border border-slate-100 rounded-xl cursor-pointer hover:bg-slate-50 transition-colors">
                        <input type="checkbox" name="only_offers" value="1" :checked="pdfOnlyOffers" @change="pdfOnlyOffers = $event.target.checked" class="w-5 h-5 text-red-600 border-gray-300 rounded focus:ring-red-500">
                        <span class="ml-3">
                            <span class="block text-sm font-bold text-slate-700">Solo Ofertas</span>
                            <span class="block text-[10px] text-slate-500">Exporta únicamente los productos en promoción</span>
                        </span>
                    </label>

                    <label class="flex items-center p-4 border border-slate-100 rounded-xl cursor-pointer hover:bg-slate-50 transition-colors">
                        <input type="checkbox" name="include_out_of_stock" value="1" checked class="w-5 h-5 text-red-600 border-gray-300 rounded focus:ring-red-500">
                        <span class="ml-3">
                            <span class="block text-sm font-bold text-slate-700">Incluir Sin Stock</span>
                            <span class="block text-[10px] text-slate-500">Mostrar productos con stock en cero</span>
                        </span>
                    </label>
                </div>
            </div>

            <div class="mt-8 border-t border-slate-100 pt-6">
                <h3 class="text-sm font-bold text-slate-800 mb-4">Información de Contacto y Pagos</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Cuentas Bancarias / Yape / Plin</label>
                        <textarea name="bank_accounts" x-model="pdfBankAccounts" rows="3" class="w-full rounded-xl border-slate-200 bg-transparent shadow-sm focus:border-red-500 focus:ring-red-500 text-sm px-4 py-2" placeholder="Ej: BCP: 191-xxxxxx-x-xx&#10;Yape: 999 999 999"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Ubicación de la Tienda (Dirección)</label>
                        <input type="text" name="store_address" x-model="pdfAddress" class="w-full rounded-xl border-slate-200 bg-transparent shadow-sm focus:border-red-500 focus:ring-red-500 text-sm px-4 py-2" placeholder="Ej: Av. Principal 123">
                    </div>
                </div>
            </div>

            <div class="mt-8 pt-6 border-t border-slate-100 flex justify-end">
                <button type="submit" class="w-full sm:w-auto px-8 py-3 text-sm font-bold text-white bg-red-600 rounded-xl shadow-lg shadow-red-200 hover:bg-red-700 transition-colors flex items-center justify-center gap-2">
                    <i class="fa-solid fa-file-pdf"></i> Generar Catálogo PDF
                </button>
            </div>
        </form>
    </div>

    <!-- Live Preview Mock -->
    <div class="lg:col-span-6 xl:col-span-6">
        <div class="sticky top-6 mx-auto" style="max-width: 600px;">
            <h3 class="text-sm font-bold text-slate-700 mb-3 flex items-center gap-2">
                <i class="fa-regular fa-eye"></i> Vista Previa en Vivo
            </h3>
            <!-- Contenedor Hoja A4 Simulada -->
            <div class="bg-white shadow-2xl rounded-sm overflow-hidden w-full" style="aspect-ratio: 1 / 1.414; border: 1px solid #e2e8f0; display: flex; flex-direction: column;">
                <!-- Header PDF -->
                <div class="bg-red-600 text-white px-6 py-4 flex justify-between items-center">
                    <div class="text-xl font-black uppercase tracking-wider" x-text="pdfTitle ? pdfTitle : 'CATÁLOGO DE PRODUCTOS'"></div>
                    <div class="text-xs text-red-100"><i class="fa-solid fa-laptop"></i> Mi Tienda</div>
                </div>

                <!-- Body PDF -->
                <div class="p-6 flex-1 bg-slate-50/50">
                    <div class="grid grid-cols-2 gap-4">
                        <!-- Producto 1 -->
                        <div class="bg-white border border-slate-200 p-3 flex flex-col relative" x-show="!pdfOnlyOffers">
                            <div class="h-24 bg-slate-100 mb-2 flex items-center justify-center rounded">
                                <i class="fa-solid fa-laptop text-slate-300 text-3xl"></i>
                            </div>
                            <div class="text-[11px] font-bold text-slate-800 line-clamp-2">Laptop Gamer RTX 4060 Intel Core i7 16GB RAM</div>
                            
                            <!-- Precio -->
                            <div class="mt-1 flex items-center gap-1" x-show="pdfShowPrice">
                                <span class="text-xs font-black text-red-600">S/ 4,500.00</span>
                            </div>

                            <!-- Specs -->
                            <div class="mt-2 text-[9px] text-slate-500 space-y-0.5" x-show="pdfShowSpecs">
                                <p>• Pantalla 15.6" 144Hz</p>
                                <p>• SSD 1TB NVMe</p>
                                <p>• Windows 11 Home</p>
                            </div>
                        </div>

                        <!-- Producto 2 (Oferta) -->
                        <div class="bg-white border border-slate-200 p-3 flex flex-col relative">
                            <div class="absolute top-0 right-0 bg-yellow-400 text-yellow-900 text-[8px] font-bold px-1.5 py-0.5 rounded-bl">OFERTA</div>
                            <div class="h-24 bg-slate-100 mb-2 flex items-center justify-center rounded">
                                <i class="fa-solid fa-mobile-screen text-slate-300 text-3xl"></i>
                            </div>
                            <div class="text-[11px] font-bold text-slate-800 line-clamp-2">Smartphone 5G 256GB Cámara 108MP</div>
                            
                            <!-- Precio -->
                            <div class="mt-1 flex items-center gap-1" x-show="pdfShowPrice">
                                <span class="text-[10px] text-slate-400 line-through">S/ 1,200.00</span>
                                <span class="text-xs font-black text-red-600">S/ 999.00</span>
                            </div>

                            <!-- Specs -->
                            <div class="mt-2 text-[9px] text-slate-500 space-y-0.5" x-show="pdfShowSpecs">
                                <p>• Batería 5000mAh</p>
                                <p>• Carga rápida 67W</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer PDF -->
                <div class="bg-slate-100 p-4 border-t border-slate-200 text-[9px] text-slate-600 flex justify-between">
                    <div style="white-space: pre-line;" x-text="pdfBankAccounts ? pdfBankAccounts : 'Cuentas bancarias aquí...'"></div>
                    <div class="text-right max-w-[50%]">
                        <div class="font-bold"><i class="fa-solid fa-location-dot"></i> Dirección</div>
                        <div x-text="pdfAddress ? pdfAddress : 'Tu dirección aquí...'"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
