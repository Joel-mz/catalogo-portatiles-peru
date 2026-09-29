@extends('layouts.admin')
@section('header_title', 'Firewall del Sistema')
@section('content')

<div class="max-w-7xl mx-auto space-y-6">
    @include('admin.security.partials.nav', ['pageTitle' => 'Cortafuegos de Aplicación Web (WAF)', 'subTitle' => 'Firewall Perimetral'])

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Configuration Form -->
        <div class="lg:col-span-2 bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-200/80">
            <div class="mb-6 pb-4 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h2 class="text-base font-black text-slate-800 font-display">Ajustes del Cortafuegos WAF</h2>
                    <p class="text-xs text-slate-500">Configura la inspección profunda de peticiones entrantes.</p>
                </div>
                <span class="badge badge-green text-xs">Motor WAF v2.4 Activo</span>
            </div>

            <form action="{{ route('admin.security.update') }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="space-y-4">
                    <div class="p-4 rounded-2xl border border-slate-200 bg-slate-50/50 flex items-center justify-between">
                        <div>
                            <label for="security_firewall_enabled" class="font-bold text-xs text-slate-800 cursor-pointer">
                                Inspección Profunda WAF (Filtro SQLi y XSS)
                            </label>
                            <p class="text-[11px] text-slate-500 mt-0.5">Analiza parámetros de formulario y URL para neutralizar inyecciones de código.</p>
                        </div>
                        <input type="checkbox" name="security_firewall_enabled" id="security_firewall_enabled" value="1" class="rounded text-emerald-600 focus:ring-emerald-500 h-5 w-5" {{ ($settings['security_firewall_enabled'] ?? '1') === '1' ? 'checked' : '' }}>
                    </div>

                    <div class="p-4 rounded-2xl border border-slate-200 bg-slate-50/50 flex items-center justify-between">
                        <div>
                            <label for="security_hide_server_info" class="font-bold text-xs text-slate-800 cursor-pointer">
                                Ocultar Cabeceras de Servidor (Server Masking)
                            </label>
                            <p class="text-[11px] text-slate-500 mt-0.5">Elimina <code class="bg-slate-200 px-1 rounded font-mono text-[10px]">X-Powered-By</code> para no divulgar versiones de PHP.</p>
                        </div>
                        <input type="checkbox" name="security_hide_server_info" id="security_hide_server_info" value="1" class="rounded text-emerald-600 focus:ring-emerald-500 h-5 w-5" {{ ($settings['security_hide_server_info'] ?? '1') === '1' ? 'checked' : '' }}>
                    </div>

                    <div>
                        <label for="security_blocked_ips" class="block font-bold text-xs text-slate-800 mb-1">
                            Lista Negra de Direcciones IP (Bloqueo Definitivo)
                        </label>
                        <p class="text-[11px] text-slate-500 mb-2">Ingresa direcciones IP separadas por comas. Las peticiones desde estas IPs recibirán bloqueo HTTP 403 automático.</p>
                        <textarea name="security_blocked_ips" id="security_blocked_ips" rows="4" class="w-full font-mono text-xs rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 p-3 bg-slate-50/50" placeholder="192.168.1.100, 10.0.0.50">{{ $settings['security_blocked_ips'] ?? '' }}</textarea>
                    </div>
                </div>

                <div class="flex justify-end pt-3">
                    <button type="submit" class="btn-primary text-xs py-2.5 px-5">
                        <i class="fa-solid fa-floppy-disk"></i> Guardar Reglas del Firewall
                    </button>
                </div>
            </form>
        </div>

        <!-- Sidebar Info -->
        <div class="space-y-5">
            <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-200/80">
                <h3 class="font-bold text-xs uppercase tracking-wider text-slate-400 mb-3">Tu Dirección IP</h3>
                <div class="flex items-center gap-3 p-3 bg-slate-50 rounded-xl border border-slate-200/60 font-mono text-xs text-slate-700 font-bold">
                    <i class="fa-solid fa-network-wired text-indigo-500"></i>
                    {{ request()->ip() }}
                </div>
                <p class="text-[11px] text-slate-400 mt-2">Asegúrate de no incluir tu propia IP en la lista negra para evitar bloqueos involuntarios.</p>
            </div>

            <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-200/80">
                <h3 class="font-bold text-xs uppercase tracking-wider text-slate-400 mb-3">IPs Bloqueadas Actualmente</h3>
                @if(count($blockedIps) > 0)
                    <div class="space-y-1.5 max-h-48 overflow-y-auto">
                        @foreach($blockedIps as $ip)
                            <div class="flex items-center justify-between p-2 bg-red-50 text-red-700 rounded-lg text-xs font-mono">
                                <span><i class="fa-solid fa-ban text-red-500 mr-1.5"></i> {{ $ip }}</span>
                                <span class="badge badge-red text-[9px]">Bloqueada</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-xs text-slate-400 italic">No hay direcciones IP bloqueadas en la lista negra.</p>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection
