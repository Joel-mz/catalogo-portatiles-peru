@extends('layouts.admin')
@section('header_title', 'Generar Catálogo PDF')
@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-semibold text-slate-800">Generación de Catálogos en PDF</h2>
    </div>

    <div class="soft-card p-6 text-center py-12">
        <i class="fa-solid fa-file-pdf text-6xl text-red-500 mb-4"></i>
        <h3 class="text-xl font-medium text-slate-900 mb-2">Descargar Catálogo Completo</h3>
        <p class="text-sm text-slate-500 mb-8 max-w-md mx-auto">
            El sistema generará un PDF con todos los productos activos de la tienda, agrupados y listos para enviar por WhatsApp a los clientes.
        </p>
        
        <form action="{{ route('admin.pdf.generate') }}" method="POST">
            @csrf
            <button type="submit" class="inline-flex justify-center items-center py-3 px-8 border border-transparent shadow-sm text-base font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                <i class="fa-solid fa-download mr-2"></i> Generar y Descargar PDF
            </button>
        </form>
    </div>
</div>
@endsection
