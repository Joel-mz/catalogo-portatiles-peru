@extends('layouts.admin')
@section('header_title', 'Seguridad de Base de Datos')
@section('content')

<div class="max-w-7xl mx-auto space-y-6">
    @include('admin.security.partials.nav', ['pageTitle' => 'Seguridad & Protección de Base de Datos', 'subTitle' => 'Base de Datos'])

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="kpi-card hover:border-emerald-300">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Conexión / Driver</span>
                <div class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-database"></i>
                </div>
            </div>
            <p class="text-xl font-display font-black text-slate-800 uppercase font-mono">{{ $dbConnection }}</p>
            <span class="badge badge-green text-[9px] mt-1">PDO MySQL Nativo</span>
        </div>

        <div class="kpi-card hover:border-blue-300">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Versión Motor</span>
                <div class="w-7 h-7 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-server"></i>
                </div>
            </div>
            <p class="text-xl font-display font-black text-slate-800 font-mono">{{ Str::limit($pdoVersion, 12) }}</p>
            <span class="badge badge-blue text-[9px] mt-1">Soporte Transacciones</span>
        </div>

        <div class="kpi-card hover:border-purple-300">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total de Tablas</span>
                <div class="w-7 h-7 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-table"></i>
                </div>
            </div>
            <p class="text-xl font-display font-black text-slate-800">{{ count($tables) }} Tablas</p>
            <span class="badge badge-green text-[9px] mt-1">InnoDB Engine</span>
        </div>

        <div class="kpi-card hover:border-amber-300">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Copias de Seguridad</span>
                <div class="w-7 h-7 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-cloud-arrow-up"></i>
                </div>
            </div>
            <a href="{{ route('admin.backups.index') }}" class="text-base font-bold text-amber-700 hover:text-amber-800 flex items-center gap-1 mt-1">
                Respaldos <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
            </a>
            <span class="badge badge-amber text-[9px] mt-1">Módulo Integrado</span>
        </div>
    </div>

    <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-200/80">
        <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-100">
            <div>
                <h2 class="text-base font-black text-slate-800 font-display">Estado de Tablas y Seguridad de Consultas</h2>
                <p class="text-xs text-slate-500">Métricas de almacenamiento e integridad relacional.</p>
            </div>
            <a href="{{ route('admin.backups.index') }}" class="btn-primary text-xs py-2 px-3">
                <i class="fa-solid fa-download"></i> Ir a Copias de Seguridad
            </a>
        </div>

        <div class="overflow-x-auto rounded-xl border border-slate-100">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Nombre de Tabla</th>
                        <th>Motor</th>
                        <th>Filas Registradas</th>
                        <th>Cotejamiento (Collation)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach(array_slice($tables, 0, 10) as $table)
                        @php
                            $t = (array) $table;
                            $name = $t['Name'] ?? 'tabla';
                            $engine = $t['Engine'] ?? 'InnoDB';
                            $rows = $t['Rows'] ?? 0;
                            $collation = $t['Collation'] ?? 'utf8mb4_unicode_ci';
                        @endphp
                        <tr>
                            <td class="font-mono text-xs font-bold text-slate-800">
                                <i class="fa-solid fa-table-cells text-slate-400 mr-1.5"></i> {{ $name }}
                            </td>
                            <td><span class="badge badge-gray text-[10px]">{{ $engine }}</span></td>
                            <td class="text-xs font-mono text-slate-600">{{ number_format($rows) }}</td>
                            <td class="text-xs text-slate-500 font-mono">{{ $collation }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
