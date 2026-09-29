@extends('layouts.admin')
@section('header_title', 'Registro de Actividad (Logs)')
@section('content')

<div class="max-w-7xl mx-auto space-y-6">
    @include('admin.security.partials.nav', ['pageTitle' => 'Registro de Actividad & Logs de Seguridad', 'subTitle' => 'Bitácora'])

    <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-200/80">
        <!-- Search & Filter bar -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6 pb-6 border-b border-slate-100">
            <div>
                <h2 class="text-base font-black text-slate-800 font-display">Bitácora de Auditoría del Sistema</h2>
                <p class="text-xs text-slate-500">Historial inmutable de eventos, modificaciones y alertas de seguridad.</p>
            </div>

            <form method="GET" action="{{ route('admin.security.logs') }}" class="flex items-center gap-2 flex-wrap">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar por acción o IP..." class="form-input text-xs w-48 sm:w-64">
                
                <select name="type" class="form-input text-xs w-auto">
                    <option value="">Todos los eventos</option>
                    <option value="threat" {{ request('type') === 'threat' ? 'selected' : '' }}>Solo Amenazas/Bloqueos</option>
                </select>

                <button type="submit" class="btn-primary text-xs py-2 px-3">
                    <i class="fa-solid fa-filter"></i> Filtrar
                </button>

                @if(request('search') || request('type'))
                    <a href="{{ route('admin.security.logs') }}" class="btn-secondary text-xs py-2 px-3">
                        Limpiar
                    </a>
                @endif
            </form>
        </div>

        <div class="overflow-x-auto rounded-xl border border-slate-100">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Fecha y Hora</th>
                        <th>Acción Realizada</th>
                        <th>Módulo</th>
                        <th>Usuario</th>
                        <th>Dirección IP</th>
                        <th>Detalles</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                        <tr>
                            <td class="text-slate-500 whitespace-nowrap text-xs">
                                {{ $log->created_at ? $log->created_at->isoFormat('D MMM YYYY, h:mm:ss A') : 'N/A' }}
                            </td>
                            <td class="font-semibold text-xs">
                                @if(str_contains(strtolower($log->action), 'bloqueado') || str_contains(strtolower($log->action), 'amenaza') || str_contains(strtolower($log->action), 'sql'))
                                    <span class="text-red-600 font-bold"><i class="fa-solid fa-triangle-exclamation mr-1"></i> {{ $log->action }}</span>
                                @else
                                    <span class="text-slate-800"><i class="fa-solid fa-circle-check text-emerald-500 mr-1"></i> {{ $log->action }}</span>
                                @endif
                            </td>
                            <td><span class="badge badge-gray text-[10px]">{{ $log->model ?? 'General' }}</span></td>
                            <td class="text-xs text-slate-600">{{ $log->user->name ?? 'Sistema Automático' }}</td>
                            <td class="font-mono text-xs text-slate-500">{{ $log->ip_address }}</td>
                            <td class="text-xs text-slate-500 max-w-xs truncate">
                                @if(is_array($log->details))
                                    {{ json_encode($log->details) }}
                                @else
                                    —
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-8 text-slate-400 text-xs">
                                No se encontraron registros que coincidan con la búsqueda.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $logs->links() }}
        </div>
    </div>
</div>

@endsection
