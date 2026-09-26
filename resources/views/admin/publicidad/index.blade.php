@extends('layouts.admin')

@section('header_title', 'Publicidad')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-semibold text-slate-800">Campaña de Publicidad</h2>
        <a href="{{ route('admin.publicidad.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors shadow-sm flex items-center">
            <i class="fa-solid fa-plus mr-2"></i> Nueva Publicidad
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-200 text-green-700 px-4 py-3 rounded-lg relative" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <div class="soft-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Imagen</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Detalles</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Ubicación</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Estado</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-slate-500 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-200">
                    @forelse($advertisements as $publicidad)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="relative h-16 w-28 rounded-xl overflow-hidden border border-slate-200 bg-white flex items-center justify-center p-1 group shadow-sm">
                                @if($publicidad->isVideo())
                                    <video src="{{ $publicidad->media_url }}" class="h-full w-full object-cover rounded-lg"></video>
                                    <span class="absolute bottom-1 right-1 bg-slate-900/80 text-white text-[9px] font-bold px-1.5 py-0.5 rounded flex items-center gap-1 shadow-sm">
                                        <i class="fa-solid fa-video text-violet-400"></i> Video
                                    </span>
                                @else
                                    <img src="{{ $publicidad->media_url }}" class="h-full w-full object-contain rounded-lg transition-transform group-hover:scale-105" alt="Publicidad Image">
                                    <span class="absolute bottom-1 right-1 bg-slate-900/80 text-white text-[9px] font-bold px-1.5 py-0.5 rounded flex items-center gap-1 shadow-sm">
                                        <i class="fa-solid fa-image text-blue-400"></i> Imagen
                                    </span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-bold text-slate-900">{{ $publicidad->title ?? 'Sin Título' }}</div>
                            @if($publicidad->link)
                                <a href="{{ $publicidad->link }}" target="_blank" class="text-xs text-blue-500 hover:underline"><i class="fa-solid fa-link"></i> Enlace destino</a>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500 font-bold">
                            @if($publicidad->location === 'all')
                                <span class="badge badge-gray">Ambos Lados</span>
                            @elseif($publicidad->location === 'sidebar_left' || $publicidad->location === 'left')
                                <span class="badge badge-blue">Lateral Izquierdo</span>
                            @elseif($publicidad->location === 'sidebar_right' || $publicidad->location === 'right')
                                <span class="badge badge-purple" style="background:#f3e8ff; color:#6b21a8;">Lateral Derecho</span>
                            @elseif($publicidad->location === 'home')
                                <span class="badge badge-blue">Solo Inicio</span>
                            @elseif($publicidad->location === 'catalog')
                                <span class="badge badge-amber">Solo Catálogo</span>
                            @else
                                <span class="badge badge-gray">{{ ucfirst($publicidad->location) }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($publicidad->status)
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Activa</span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Inactiva</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                            <a href="{{ route('admin.publicidad.edit', $publicidad) }}" class="text-blue-600 hover:text-blue-900 bg-blue-50 p-2 rounded-lg transition-colors"><i class="fa-solid fa-pen"></i></a>
                            
                            <form action="{{ route('admin.publicidad.destroy', $publicidad) }}" method="POST" class="inline-block" onsubmit="return confirm('¿Seguro que deseas eliminar esta publicidad?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 bg-red-50 p-2 rounded-lg transition-colors"><i class="fa-solid fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-slate-500">
                            No hay publicidad registrada.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
