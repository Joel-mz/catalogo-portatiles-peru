@extends('layouts.admin')
@section('header_title', 'Protección contra Ataques')
@section('content')

<div class="max-w-7xl mx-auto space-y-6">
    @include('admin.security.partials.nav', ['pageTitle' => 'Protección contra Ataques Informáticos', 'subTitle' => 'Defensa Perimetral'])

    <!-- KPI Row -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="kpi-card hover:border-emerald-300">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Inyección SQL</span>
                <div class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-database"></i>
                </div>
            </div>
            <p class="text-xl font-display font-black text-slate-800">Blindado</p>
            <span class="badge badge-green text-[9px] mt-1">PDO + WAF Activo</span>
        </div>

        <div class="kpi-card hover:border-blue-300">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Ataques XSS</span>
                <div class="w-7 h-7 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-code"></i>
                </div>
            </div>
            <p class="text-xl font-display font-black text-slate-800">Protegido</p>
            <span class="badge badge-blue text-[9px] mt-1">Escape HTML Blade</span>
        </div>

        <div class="kpi-card hover:border-purple-300">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Ataques CSRF</span>
                <div class="w-7 h-7 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-shield-halved"></i>
                </div>
            </div>
            <p class="text-xl font-display font-black text-slate-800">Activo</p>
            <span class="badge badge-green text-[9px] mt-1">Tokens Criptográficos</span>
        </div>

        <div class="kpi-card hover:border-amber-300">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Fuerza Bruta / DoS</span>
                <div class="w-7 h-7 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-gauge-high"></i>
                </div>
            </div>
            <p class="text-xl font-display font-black text-slate-800">Limitado</p>
            <span class="badge badge-amber text-[9px] mt-1">Throttle Middleware</span>
        </div>
    </div>

    <!-- Defense Matrix Detail -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <!-- SQL Injection Defense -->
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-200/80">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-base">
                    <i class="fa-solid fa-database"></i>
                </div>
                <div>
                    <h3 class="font-bold text-sm text-slate-800">1. Protección contra Inyección SQL (SQLi)</h3>
                    <p class="text-xs text-slate-500">Neutralización en capa de aplicación y base de datos</p>
                </div>
            </div>
            <div class="space-y-3 text-xs text-slate-600">
                <p>
                    Las inyecciones SQL ocurren cuando datos no sanitizados son concatenados directamente a consultas SQL. En este catálogo virtual:
                </p>
                <ul class="space-y-2 list-disc pl-5 text-slate-600">
                    <li><strong>PDO Prepared Statements:</strong> Todas las consultas en Eloquent ORM utilizan sentencias preparadas parametrizadas. El motor de base de datos trata los datos como valores literales, nunca como código ejecutable.</li>
                    <li><strong>Inspección WAF en tiempo real:</strong> El middleware SecurityHeaders examina cada consulta HTTP entrante contra firmas de ataque conocidas (<code class="bg-slate-100 px-1 rounded font-mono">UNION SELECT</code>, <code class="bg-slate-100 px-1 rounded font-mono">DROP TABLE</code>, etc.).</li>
                </ul>
            </div>
        </div>

        <!-- XSS & CSRF Defense -->
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-200/80">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-base">
                    <i class="fa-solid fa-shield"></i>
                </div>
                <div>
                    <h3 class="font-bold text-sm text-slate-800">2. Blindaje XSS & CSRF</h3>
                    <p class="text-xs text-slate-500">Integridad de formularios y protección del navegador</p>
                </div>
            </div>
            <div class="space-y-3 text-xs text-slate-600">
                <p>
                    Ataques que intentan ejecutar scripts no autorizados o realizar compras fraudulentas en nombre del usuario:
                </p>
                <ul class="space-y-2 list-disc pl-5 text-slate-600">
                    <li><strong>Escape Automático Blade:</strong> La sintaxis <code class="bg-slate-100 px-1 rounded font-mono">&#123;&#123; $variable &#125;&#125;</code> pasa todo el texto por <code class="bg-slate-100 px-1 rounded font-mono">htmlspecialchars()</code>, anulando scripts maliciosos.</li>
                    <li><strong>Tokens Anti-CSRF HMAC:</strong> Cada formulario requiere un token único ligado a la sesión que expira automáticamente.</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Blocked Attacks Log Table -->
    <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-200/80">
        <div class="flex items-center justify-between mb-4 pb-4 border-b border-slate-100">
            <div>
                <h2 class="text-base font-black text-slate-800 font-display flex items-center gap-2">
                    <i class="fa-solid fa-shield-virus text-rose-500"></i>
                    Ataques Detectados y Bloqueados
                </h2>
                <p class="text-xs text-slate-500">Historial de intentos sospechosos bloqueados automáticamente.</p>
            </div>
            <span class="badge badge-green text-xs">Protección Activa</span>
        </div>

        <div class="overflow-x-auto rounded-xl border border-slate-100">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Amenaza Detectada</th>
                        <th>Dirección IP</th>
                        <th>Detalles Técnicos</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($blockedAttacks as $log)
                        <tr>
                            <td class="text-slate-500 whitespace-nowrap text-xs">
                                {{ $log->created_at ? $log->created_at->isoFormat('D MMM YYYY, h:mm A') : 'N/A' }}
                            </td>
                            <td class="font-bold text-red-600 text-xs">
                                <i class="fa-solid fa-triangle-exclamation mr-1"></i> {{ $log->action }}
                            </td>
                            <td class="font-mono text-xs text-slate-700">{{ $log->ip_address }}</td>
                            <td class="text-xs text-slate-500">
                                @if(is_array($log->details))
                                    {{ json_encode($log->details) }}
                                @else
                                    Bloqueo inmediato por regla perimetral
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-8 text-slate-400 text-xs">
                                <i class="fa-solid fa-circle-check text-emerald-500 text-2xl mb-1 block"></i>
                                No se han detectado intentos de ataque recientes. El sitio está seguro.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
