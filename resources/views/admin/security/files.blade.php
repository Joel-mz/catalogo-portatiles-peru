@extends('layouts.admin')
@section('header_title', 'Protección de Archivos')
@section('content')

<div class="max-w-7xl mx-auto space-y-6">
    @include('admin.security.partials.nav', ['pageTitle' => 'Protección del Sistema de Archivos', 'subTitle' => 'Archivos'])

    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-200/80">
            <h2 class="text-base font-black text-slate-800 font-display mb-4">Verificación de Integridad de Archivos</h2>
            <div class="space-y-3">
                <div class="p-3.5 rounded-xl border flex items-center justify-between {{ $filesCheck['env_in_root'] ? 'bg-emerald-50/50 border-emerald-200' : 'bg-red-50 border-red-200' }}">
                    <div class="flex items-center gap-2.5">
                        <i class="fa-solid fa-file-shield {{ $filesCheck['env_in_root'] ? 'text-emerald-600' : 'text-red-600' }}"></i>
                        <span class="text-xs font-bold text-slate-800">Archivo de Variables .env (Raíz)</span>
                    </div>
                    <span class="badge {{ $filesCheck['env_in_root'] ? 'badge-green' : 'badge-red' }} text-[9px]">
                        {{ $filesCheck['env_in_root'] ? 'Presente y Protegido' : 'Faltante' }}
                    </span>
                </div>

                <div class="p-3.5 rounded-xl border flex items-center justify-between {{ !$filesCheck['env_in_public'] ? 'bg-emerald-50/50 border-emerald-200' : 'bg-red-50 border-red-200' }}">
                    <div class="flex items-center gap-2.5">
                        <i class="fa-solid fa-lock {{ !$filesCheck['env_in_public'] ? 'text-emerald-600' : 'text-red-600' }}"></i>
                        <span class="text-xs font-bold text-slate-800">Aislamiento de .env en Carpeta Pública</span>
                    </div>
                    <span class="badge {{ !$filesCheck['env_in_public'] ? 'badge-green' : 'badge-red' }} text-[9px]">
                        {{ !$filesCheck['env_in_public'] ? 'No Expuesto (Seguro)' : '¡PELIGRO: Expuesto!' }}
                    </span>
                </div>

                <div class="p-3.5 rounded-xl border flex items-center justify-between {{ $filesCheck['storage_writable'] ? 'bg-emerald-50/50 border-emerald-200' : 'bg-amber-50 border-amber-200' }}">
                    <div class="flex items-center gap-2.5">
                        <i class="fa-solid fa-folder-open {{ $filesCheck['storage_writable'] ? 'text-emerald-600' : 'text-amber-600' }}"></i>
                        <span class="text-xs font-bold text-slate-800">Permisos de Escritura en /storage</span>
                    </div>
                    <span class="badge {{ $filesCheck['storage_writable'] ? 'badge-green' : 'badge-amber' }} text-[9px]">
                        {{ $filesCheck['storage_writable'] ? 'Permisos Correctos' : 'Revisar Permisos' }}
                    </span>
                </div>

                <div class="p-3.5 rounded-xl border flex items-center justify-between {{ $filesCheck['bootstrap_cache_writable'] ? 'bg-emerald-50/50 border-emerald-200' : 'bg-amber-50 border-amber-200' }}">
                    <div class="flex items-center gap-2.5">
                        <i class="fa-solid fa-bolt {{ $filesCheck['bootstrap_cache_writable'] ? 'text-emerald-600' : 'text-amber-600' }}"></i>
                        <span class="text-xs font-bold text-slate-800">Caché de Arranque /bootstrap/cache</span>
                    </div>
                    <span class="badge {{ $filesCheck['bootstrap_cache_writable'] ? 'badge-green' : 'badge-amber' }} text-[9px]">
                        {{ $filesCheck['bootstrap_cache_writable'] ? 'Optimizado' : 'Sin Escritura' }}
                    </span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-200/80">
            <h2 class="text-base font-black text-slate-800 font-display mb-4">Seguridad de Cargas (Uploads)</h2>
            <div class="space-y-3 text-xs text-slate-600 leading-relaxed">
                <p>
                    Para evitar la subida de shells maliciosos o scripts PHP camuflados como imágenes en el catálogo de productos y banners:
                </p>
                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 space-y-2">
                    <div class="font-bold text-slate-800 text-xs flex items-center gap-2">
                        <i class="fa-solid fa-shield-halved text-emerald-600"></i>
                        Validación Estricta de Tipos MIME
                    </div>
                    <p class="text-slate-500">Solo se permiten extensiones y encabezados binarios válidos (<code class="bg-slate-200 px-1 rounded font-mono text-[10px]">jpg, png, webp, pdf</code>). Cualquier intento de subir archivos con extensión ejecutable es abortado.</p>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
