@extends('layouts.admin')
@section('header_title', 'Seguridad de Sesiones')
@section('content')

<div class="max-w-7xl mx-auto space-y-6">
    @include('admin.security.partials.nav', ['pageTitle' => 'Seguridad de Sesiones & Control de Acceso', 'subTitle' => 'Sesiones'])

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="kpi-card hover:border-emerald-300">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Controlador de Sesión</span>
                <div class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-server"></i>
                </div>
            </div>
            <p class="text-xl font-display font-black text-slate-800 font-mono">{{ $sessionDriver }}</p>
            <span class="badge badge-green text-[9px] mt-1">Almacenamiento Local</span>
        </div>

        <div class="kpi-card hover:border-blue-300">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Tiempo de Expiración</span>
                <div class="w-7 h-7 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-hourglass-half"></i>
                </div>
            </div>
            <p class="text-xl font-display font-black text-slate-800">{{ $lifetime }} minutos</p>
            <span class="badge badge-blue text-[9px] mt-1">Cierre Inactividad</span>
        </div>

        <div class="kpi-card hover:border-purple-300">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Cookie HttpOnly</span>
                <div class="w-7 h-7 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-cookie-bite"></i>
                </div>
            </div>
            <p class="text-xl font-display font-black text-slate-800">Activado</p>
            <span class="badge badge-green text-[9px] mt-1">Inaccesible por JavaScript</span>
        </div>

        <div class="kpi-card hover:border-amber-300">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Protección SameSite</span>
                <div class="w-7 h-7 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-shield-halved"></i>
                </div>
            </div>
            <p class="text-xl font-display font-black text-slate-800">Lax</p>
            <span class="badge badge-amber text-[9px] mt-1">Anti-CSRF Nativo</span>
        </div>
    </div>

    <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-200/80">
        <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-100">
            <div>
                <h2 class="text-base font-black text-slate-800 font-display">Protección contra Fijación y Secuestro de Sesión</h2>
                <p class="text-xs text-slate-500">Mecanismos de rotación y blindaje de identificadores criptográficos de sesión.</p>
            </div>
            <form action="{{ route('admin.security.clear_sessions') }}" method="POST">
                @csrf
                <button type="submit" class="btn-primary text-xs py-2 px-4 bg-slate-900 hover:bg-slate-800">
                    <i class="fa-solid fa-rotate"></i> Regenerar ID de Sesión Ahora
                </button>
            </form>
        </div>

        <div class="space-y-4 text-xs text-slate-600">
            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200">
                <h3 class="font-bold text-slate-800 text-xs mb-1">Rotación Automática en Cada Inicio de Sesión</h3>
                <p>Cada vez que un usuario inicia o cierra sesión en el panel, el framework regenera el identificador de sesión para frustrar ataques de secuestro de sesión (Session Hijacking).</p>
            </div>
            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200">
                <h3 class="font-bold text-slate-800 text-xs mb-1">Cifrado de Cookies de Sesión</h3>
                <p>Las cookies de sesión viajan cifradas con la clave maestra de la aplicación (<code class="bg-slate-200 px-1 rounded font-mono text-[10px]">APP_KEY</code>), impidiendo que terceros puedan manipular o leer la carga de sesión.</p>
            </div>
        </div>
    </div>
</div>

@endsection
