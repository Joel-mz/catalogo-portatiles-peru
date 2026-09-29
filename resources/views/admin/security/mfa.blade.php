@extends('layouts.admin')
@section('header_title', 'Autenticación Multifactor (MFA)')
@section('content')

<div class="max-w-7xl mx-auto space-y-6">
    @include('admin.security.partials.nav', ['pageTitle' => 'Autenticación Multifactor (MFA / 2FA)', 'subTitle' => 'Segundo Factor'])

    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        <div class="kpi-card hover:border-emerald-300">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Protocolo de 2FA</span>
                <div class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-mobile-screen"></i>
                </div>
            </div>
            <p class="text-xl font-display font-black text-slate-800">TOTP Estándar</p>
            <span class="badge badge-green text-[9px] mt-1">Google Auth / Authy</span>
        </div>

        <div class="kpi-card hover:border-blue-300">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Tu Estado de 2FA</span>
                <div class="w-7 h-7 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-user-shield"></i>
                </div>
            </div>
            <p class="text-xl font-display font-black text-slate-800">
                {{ auth()->user()->two_factor_confirmed_at ? 'Habilitado' : 'Disponible' }}
            </p>
            <span class="badge {{ auth()->user()->two_factor_confirmed_at ? 'badge-green' : 'badge-amber' }} text-[9px] mt-1">
                {{ auth()->user()->two_factor_confirmed_at ? 'Protegido' : 'Pendiente Activar' }}
            </span>
        </div>

        <div class="kpi-card hover:border-purple-300">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Códigos de Respaldo</span>
                <div class="w-7 h-7 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-shield-cat"></i>
                </div>
            </div>
            <p class="text-xl font-display font-black text-slate-800">8 Códigos</p>
            <span class="badge badge-purple text-[9px] mt-1">Recuperación de Emergencia</span>
        </div>
    </div>

    <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-200/80">
        <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-100">
            <div>
                <h2 class="text-base font-black text-slate-800 font-display">Estado de 2FA por Usuario Administrador</h2>
                <p class="text-xs text-slate-500">Supervisión de protección de doble factor en el equipo administrativo.</p>
            </div>
            <a href="{{ route('profile.edit') }}" class="btn-primary text-xs py-2 px-3">
                <i class="fa-solid fa-qrcode"></i> Configurar mi 2FA
            </a>
        </div>

        <div class="overflow-x-auto rounded-xl border border-slate-100">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Correo Electrónico</th>
                        <th>Rol</th>
                        <th>Estado MFA (2FA)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td class="font-bold text-xs text-slate-800">{{ $user->name }}</td>
                            <td class="text-xs text-slate-600">{{ $user->email }}</td>
                            <td><span class="badge badge-blue text-[10px]">Administrador</span></td>
                            <td>
                                @if($user->two_factor_confirmed_at)
                                    <span class="badge badge-green text-[10px]"><i class="fa-solid fa-check mr-1"></i> 2FA Activo</span>
                                @else
                                    <span class="badge badge-gray text-[10px]">Sin Activar</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
