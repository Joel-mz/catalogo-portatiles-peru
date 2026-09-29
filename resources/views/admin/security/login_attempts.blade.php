@extends('layouts.admin')
@section('header_title', 'Intentos de Inicio de Sesión')
@section('content')

<div class="max-w-7xl mx-auto space-y-6">
    @include('admin.security.partials.nav', ['pageTitle' => 'Intentos de Inicio de Sesión & Autenticación', 'subTitle' => 'Accesos'])

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="kpi-card hover:border-emerald-300">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Protección Fuerza Bruta</span>
                <div class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-lock"></i>
                </div>
            </div>
            <p class="text-xl font-display font-black text-slate-800">5 intentos / min</p>
            <span class="badge badge-green text-[9px] mt-1">Bloqueo Automático Activo</span>
        </div>

        <div class="kpi-card hover:border-blue-300">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Autenticación Actual</span>
                <div class="w-7 h-7 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-user-check"></i>
                </div>
            </div>
            <p class="text-xl font-display font-black text-slate-800">{{ auth()->user()->name }}</p>
            <span class="badge badge-blue text-[9px] mt-1">Administrador Conectado</span>
        </div>

        <div class="kpi-card hover:border-purple-300">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">IP Conexión Actual</span>
                <div class="w-7 h-7 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-network-wired"></i>
                </div>
            </div>
            <p class="text-xl font-display font-black text-slate-800 font-mono">{{ request()->ip() }}</p>
            <span class="badge badge-gray text-[9px] mt-1">Conexión Verificada</span>
        </div>
    </div>

    <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-200/80">
        <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-100">
            <div>
                <h2 class="text-base font-black text-slate-800 font-display">Historial de Intentos de Autenticación</h2>
                <p class="text-xs text-slate-500">Registros de inicios de sesión e intentos de acceso al panel.</p>
            </div>
            <span class="badge badge-blue text-xs">Protección Rate-Limit Activa</span>
        </div>

        <div class="overflow-x-auto rounded-xl border border-slate-100">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Fecha y Hora</th>
                        <th>Evento</th>
                        <th>Usuario Asociado</th>
                        <th>Dirección IP</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentLogins as $login)
                        <tr>
                            <td class="text-slate-500 whitespace-nowrap text-xs">
                                {{ $login->created_at ? $login->created_at->isoFormat('D MMM YYYY, h:mm:ss A') : 'N/A' }}
                            </td>
                            <td class="font-semibold text-slate-800 text-xs">{{ $login->action }}</td>
                            <td class="text-xs text-slate-600">{{ $login->user->name ?? 'Invitado' }}</td>
                            <td class="font-mono text-xs text-slate-500">{{ $login->ip_address }}</td>
                            <td>
                                @if(str_contains(strtolower($login->action), 'fallido') || str_contains(strtolower($login->action), 'bloqueado'))
                                    <span class="badge badge-red text-[10px]">Fallido</span>
                                @else
                                    <span class="badge badge-green text-[10px]">Exitoso</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-6 text-slate-400 text-xs">
                                No se registran incidentes ni accesos fallidos recientes.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
