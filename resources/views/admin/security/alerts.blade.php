@extends('layouts.admin')
@section('header_title', 'Alertas de Seguridad')
@section('content')

<div class="max-w-7xl mx-auto space-y-6">
    @include('admin.security.partials.nav', ['pageTitle' => 'Configuración de Alertas & Notificaciones de Seguridad', 'subTitle' => 'Alertas'])

    <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-200/80">
        <div class="mb-6 pb-4 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h2 class="text-base font-black text-slate-800 font-display">Canales y Reglas de Alerta</h2>
                <p class="text-xs text-slate-500">Recibe notificaciones inmediatas ante eventos de seguridad críticos.</p>
            </div>
            <span class="badge badge-green text-xs">Canales Operativos</span>
        </div>

        <form action="{{ route('admin.security.update') }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="p-4 rounded-2xl border border-slate-200 bg-slate-50/50 flex items-center justify-between">
                    <div>
                        <span class="font-bold text-xs text-slate-800">Alertar Ataques de Inyección SQL</span>
                        <p class="text-[11px] text-slate-500 mt-0.5">Registrar evento inmediato y marcar como alerta crítica.</p>
                    </div>
                    <input type="checkbox" name="security_notify_threats" id="security_notify_threats" value="1" class="rounded text-emerald-600 focus:ring-emerald-500 h-5 w-5" {{ ($settings['security_notify_threats'] ?? '1') === '1' ? 'checked' : '' }}>
                </div>

                <div class="p-4 rounded-2xl border border-slate-200 bg-slate-50/50 flex items-center justify-between">
                    <div>
                        <span class="font-bold text-xs text-slate-800">Alertar Intentos Fallidos de Inicio de Sesión</span>
                        <p class="text-[11px] text-slate-500 mt-0.5">Notificar bloqueos por superación de límite de intentos.</p>
                    </div>
                    <input type="checkbox" checked class="rounded text-emerald-600 focus:ring-emerald-500 h-5 w-5" disabled>
                </div>
            </div>

            <div class="p-4 rounded-2xl bg-indigo-50/50 border border-indigo-100 text-xs text-indigo-900 flex items-center gap-3">
                <i class="fa-solid fa-envelope-circle-check text-indigo-600 text-base"></i>
                <span>Las alertas críticas del sistema se dirigen al correo administrativo registrado: <strong class="font-mono text-indigo-950">{{ auth()->user()->email }}</strong></span>
            </div>

            <div class="flex justify-end pt-3">
                <button type="submit" class="btn-primary text-xs py-2.5 px-5">
                    <i class="fa-solid fa-floppy-disk"></i> Guardar Configuración de Alertas
                </button>
            </div>
        </form>
    </div>
</div>

@endsection
