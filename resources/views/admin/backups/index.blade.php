@extends('layouts.admin')
@section('header_title', 'Copias de Seguridad (Backups)')
@section('content')

<div class="max-w-5xl mx-auto space-y-6">
    <div class="flex justify-between items-center px-2">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Copias de Seguridad</h2>
            <p class="text-sm text-slate-500 mt-1">Gestiona el historial de respaldos de la base de datos de tu tienda.</p>
        </div>
        <form action="{{ route('admin.backups.generate') }}" method="POST">
            @csrf
            <button type="submit" class="btn-primary">
                <i class="fa-solid fa-cloud-arrow-up"></i> Generar Backup Ahora
            </button>
        </form>
    </div>

    @if(session('success'))
        <div class="bg-emerald-100 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl shadow-sm flex items-center gap-2">
            <i class="fa-solid fa-circle-check"></i>
            <span class="text-sm font-medium">{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-100 border border-red-200 text-red-800 px-4 py-3 rounded-xl shadow-sm flex items-center gap-2">
            <i class="fa-solid fa-circle-exclamation"></i>
            <span class="text-sm font-medium">{{ session('error') }}</span>
        </div>
    @endif

    <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-200">
        
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-bold text-slate-800">Historial de Backups</h3>
            <form action="{{ route('admin.backups.upload') }}" method="POST" enctype="multipart/form-data" class="flex gap-2 items-center" x-data="{ fileName: '' }">
                @csrf
                <label class="btn-secondary cursor-pointer relative overflow-hidden">
                    <i class="fa-solid fa-upload"></i> <span x-text="fileName ? fileName : 'Seleccionar Archivo'">Seleccionar Archivo</span>
                    <input type="file" name="backup_file" accept=".sql,.zip" class="absolute inset-0 opacity-0 cursor-pointer" @change="fileName = $event.target.files[0].name">
                </label>
                <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-xl text-sm font-bold shadow-sm transition-colors" x-show="fileName">
                    Subir y Restaurar
                </button>
            </form>
        </div>

        <div class="overflow-x-auto rounded-xl border border-slate-100">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Nombre del Archivo</th>
                        <th>Fecha de Creación</th>
                        <th>Tamaño</th>
                        <th class="text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($backups as $backup)
                    <tr>
                        <td class="font-mono text-sm text-slate-700">
                            <i class="fa-solid fa-file-zipper text-slate-400 mr-2"></i> {{ $backup['name'] }}
                        </td>
                        <td>{{ \Carbon\Carbon::parse($backup['date'])->isoFormat('DD MMM YYYY, hh:mm A') }}</td>
                        <td>{{ $backup['size'] }}</td>
                        <td class="text-right space-x-2">
                            <a href="{{ route('admin.backups.download', $backup['name']) }}" class="text-blue-600 hover:text-blue-800 bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-lg text-xs font-bold transition-colors">
                                <i class="fa-solid fa-download"></i> Descargar
                            </a>
                            
                            <form action="{{ route('admin.backups.restore', $backup['name']) }}" method="POST" class="inline-block" onsubmit="return confirm('¿Estás seguro de que deseas restaurar esta copia? Los datos actuales se sobreescribirán.');">
                                @csrf
                                <button type="submit" class="text-emerald-600 hover:text-emerald-800 bg-emerald-50 hover:bg-emerald-100 px-3 py-1.5 rounded-lg text-xs font-bold transition-colors">
                                    <i class="fa-solid fa-rotate-left"></i> Restaurar
                                </button>
                            </form>
                            
                            <form action="{{ route('admin.backups.delete', $backup['name']) }}" method="POST" class="inline-block" onsubmit="return confirm('¿Seguro que deseas eliminar esta copia de seguridad permanentemente?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded-lg text-xs font-bold transition-colors">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-8 text-slate-500">
                            <div class="flex flex-col items-center justify-center">
                                <i class="fa-solid fa-database text-4xl mb-3 text-slate-300"></i>
                                <p>No hay copias de seguridad generadas.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
    </div>
</div>

@endsection
