@extends('layouts.admin')
@section('header_title', 'Gestión de Contraseñas')
@section('content')

<div class="max-w-7xl mx-auto space-y-6">
    @include('admin.security.partials.nav', ['pageTitle' => 'Políticas & Gestión de Contraseñas', 'subTitle' => 'Credenciales'])

    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        <div class="kpi-card hover:border-emerald-300">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Algoritmo de Hashing</span>
                <div class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-hashtag"></i>
                </div>
            </div>
            <p class="text-xl font-display font-black text-slate-800">Bcrypt (Costo 12)</p>
            <span class="badge badge-green text-[9px] mt-1">Criptografía Irreversible</span>
        </div>

        <div class="kpi-card hover:border-blue-300">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Longitud Mínima</span>
                <div class="w-7 h-7 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-ruler"></i>
                </div>
            </div>
            <p class="text-xl font-display font-black text-slate-800">8 Caracteres</p>
            <span class="badge badge-blue text-[9px] mt-1">Requisito Obligatorio</span>
        </div>

        <div class="kpi-card hover:border-purple-300">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Cuentas Protegidas</span>
                <div class="w-7 h-7 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-users"></i>
                </div>
            </div>
            <p class="text-xl font-display font-black text-slate-800">{{ $usersCount }} Cuentas</p>
            <span class="badge badge-green text-[9px] mt-1">Todas Hasheadas</span>
        </div>
    </div>

    <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-200/80">
        <h2 class="text-base font-black text-slate-800 font-display mb-4">Políticas de Seguridad de Claves</h2>
        <div class="space-y-4 text-xs text-slate-600">
            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 flex items-start gap-3">
                <i class="fa-solid fa-circle-check text-emerald-500 mt-0.5 text-sm"></i>
                <div>
                    <h3 class="font-bold text-slate-800 text-xs">Almacenamiento Seguro sin Texto Plano</h3>
                    <p class="mt-0.5 text-slate-500">Ninguna contraseña se almacena jamás en texto plano. Cada contraseña pasa por la función criptográfica Bcrypt con salt único generado aleatoriamente.</p>
                </div>
            </div>

            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 flex items-start gap-3">
                <i class="fa-solid fa-circle-check text-emerald-500 mt-0.5 text-sm"></i>
                <div>
                    <h3 class="font-bold text-slate-800 text-xs">Validación de Complejidad en Formularios</h3>
                    <p class="mt-0.5 text-slate-500">El sistema exige confirmación estricta de clave y valida que no se utilicen contraseñas comunes o vacías al crear o editar administradores.</p>
                </div>
            </div>
        </div>

        <div class="mt-6 pt-4 border-t border-slate-100 flex items-center gap-3">
            <a href="{{ route('profile.edit') }}" class="btn-primary text-xs py-2 px-4">
                <i class="fa-solid fa-key"></i> Cambiar mi Contraseña Actual
            </a>
            <a href="{{ route('admin.users.index') }}" class="btn-secondary text-xs py-2 px-4">
                <i class="fa-solid fa-users"></i> Gestionar Contraseñas de Usuarios
            </a>
        </div>
    </div>
</div>

@endsection
