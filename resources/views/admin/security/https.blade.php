@extends('layouts.admin')
@section('header_title', 'Certificado HTTPS')
@section('content')

<div class="max-w-7xl mx-auto space-y-6">
    @include('admin.security.partials.nav', ['pageTitle' => 'Certificado HTTPS & Cifrado en Tránsito (SSL/TLS)', 'subTitle' => 'HTTPS'])

    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        <div class="kpi-card hover:border-emerald-300">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Cifrado de Tráfico</span>
                <div class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-lock"></i>
                </div>
            </div>
            <p class="text-xl font-display font-black text-slate-800">{{ $isHttps ? 'HTTPS Activo' : 'HTTP Local' }}</p>
            <span class="badge {{ $isHttps ? 'badge-green' : 'badge-blue' }} text-[9px] mt-1">
                {{ $isHttps ? 'Conexión Cifrada' : 'Entorno Desarrollo Local' }}
            </span>
        </div>

        <div class="kpi-card hover:border-blue-300">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Cabecera HSTS</span>
                <div class="w-7 h-7 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-certificate"></i>
                </div>
            </div>
            <p class="text-xl font-display font-black text-slate-800">max-age=31536000</p>
            <span class="badge badge-green text-[9px] mt-1">HSTS 1 Año Activo</span>
        </div>

        <div class="kpi-card hover:border-purple-300">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Protección Anti-Sniffing</span>
                <div class="w-7 h-7 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-shield-halved"></i>
                </div>
            </div>
            <p class="text-xl font-display font-black text-slate-800">nosniff</p>
            <span class="badge badge-purple text-[9px] mt-1">X-Content-Type-Options</span>
        </div>
    </div>

    <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-200/80">
        <h2 class="text-base font-black text-slate-800 font-display mb-4">Cabeceras de Cifrado y Transporte Seguro</h2>
        <div class="space-y-3 text-xs text-slate-600 leading-relaxed">
            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200">
                <h3 class="font-bold text-slate-800 text-xs mb-1">HTTP Strict Transport Security (HSTS)</h3>
                <p>Obliga a los navegadores web modernos a conectarse únicamente mediante canales HTTPS autenticados, protegiendo a los compradores contra ataques Man-In-The-Middle (MITM) y degradación de protocolo SSL Strip.</p>
            </div>

            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200">
                <h3 class="font-bold text-slate-800 text-xs mb-1">Protección contra Clickjacking</h3>
                <p>La cabecera <code class="bg-slate-200 px-1 rounded font-mono text-[10px]">X-Frame-Options: SAMEORIGIN</code> impide que páginas externas incrusten el catálogo en frames transparentes para engañar a los visitantes.</p>
            </div>
        </div>
    </div>
</div>

@endsection
