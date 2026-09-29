@extends('layouts.admin')
@section('header_title', 'Monitor de Amenazas')
@section('content')

<div class="max-w-7xl mx-auto space-y-6">
    @include('admin.security.partials.nav', ['pageTitle' => 'Monitor de Amenazas & Detección de Intrusos (IDS)', 'subTitle' => 'Amenazas'])

    <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 text-white rounded-3xl p-6 sm:p-8 border border-indigo-500/20 shadow-xl relative overflow-hidden">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 relative z-10">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 mb-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    RADAR DE VULNERABILIDADES EN TIEMPO REAL
                </div>
                <h2 class="text-xl sm:text-2xl font-black font-display text-white">Monitor Perimetral Activo</h2>
                <p class="text-slate-300 text-xs sm:text-sm mt-1 max-w-xl">Supervisión continua contra rastreadores maliciosos, crawlers no autorizados e inyecciones de código.</p>
            </div>
            <div class="flex items-center gap-3">
                <span class="text-xs font-mono text-emerald-400 bg-emerald-950/60 border border-emerald-500/30 px-3 py-1.5 rounded-xl">
                    <i class="fa-solid fa-satellite-dish mr-1"></i> Estado: Operativo Normal
                </span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-200/80">
            <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-base mb-3">
                <i class="fa-solid fa-robot"></i>
            </div>
            <h3 class="font-bold text-sm text-slate-800">Detección de Bots Maliciosos</h3>
            <p class="text-xs text-slate-500 mt-1">Identificación automática de scrapers abusivos y herramientas de escaneo masivo (sqlmap, nikto, wpscan).</p>
            <div class="mt-4 pt-3 border-t border-slate-100">
                <span class="badge badge-green text-[10px]">Bloqueo Automático Activo</span>
            </div>
        </div>

        <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-200/80">
            <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center text-base mb-3">
                <i class="fa-solid fa-crosshairs"></i>
            </div>
            <h3 class="font-bold text-sm text-slate-800">Ataques de Inyección</h3>
            <p class="text-xs text-slate-500 mt-1">Interceptación de payloads de bypass de autenticación y robo de información estructurada.</p>
            <div class="mt-4 pt-3 border-t border-slate-100">
                <span class="badge badge-green text-[10px]">WAF Perimetral Blindado</span>
            </div>
        </div>

        <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-200/80">
            <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-base mb-3">
                <i class="fa-solid fa-lock"></i>
            </div>
            <h3 class="font-bold text-sm text-slate-800">Protección de Datos Clientes</h3>
            <p class="text-xs text-slate-500 mt-1">Aislamiento de la base de datos de pedidos y números de contacto de compradores.</p>
            <div class="mt-4 pt-3 border-t border-slate-100">
                <span class="badge badge-green text-[10px]">Privacidad Garantizada</span>
            </div>
        </div>
    </div>
</div>

@endsection
