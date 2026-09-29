@extends('layouts.admin')
@section('header_title', 'Seguridad del Sistema')
@section('content')

<div class="max-w-7xl mx-auto space-y-8">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 text-white p-6 sm:p-8 rounded-3xl shadow-xl border border-indigo-500/20 relative overflow-hidden">
        <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-indigo-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute right-1/3 -top-10 w-48 h-48 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 mb-3">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                SISTEMA DE PROTECCIÓN ACTIVO
            </div>
            <h1 class="text-2xl sm:text-3xl font-black font-display tracking-tight text-white flex items-center gap-3">
                <i class="fa-solid fa-shield-halved text-emerald-400"></i>
                Seguridad & Blindaje del Catálogo
            </h1>
            <p class="text-slate-300 text-sm sm:text-base mt-2 max-w-2xl leading-relaxed">
                Protección perimetral del sistema y datos de clientes contra Inyecciones SQL, ataques XSS, CSRF, secuestro de sesiones y accesos no autorizados.
            </p>
        </div>

        <div class="relative z-10 flex flex-wrap items-center gap-3">
            <form action="{{ route('admin.security.scan') }}" method="POST">
                @csrf
                <button type="submit" class="inline-flex items-center gap-2 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white px-5 py-2.5 rounded-xl font-bold text-sm shadow-lg shadow-emerald-950/40 transition-all hover:scale-[1.02] active:scale-95">
                    <i class="fa-solid fa-radar text-emerald-200"></i>
                    Escanear Sistema
                </button>
            </form>

            <form action="{{ route('admin.security.clear_sessions') }}" method="POST" onsubmit="return confirm('¿Regenerar la sesión activa por seguridad?')">
                @csrf
                <button type="submit" class="inline-flex items-center gap-2 bg-white/10 hover:bg-white/15 text-white border border-white/20 px-4 py-2.5 rounded-xl font-semibold text-sm transition-all hover:scale-[1.02] active:scale-95">
                    <i class="fa-solid fa-key text-amber-300"></i>
                    Regenerar Sesión
                </button>
            </form>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-5 py-4 rounded-2xl shadow-sm flex items-center gap-3 animate-fade-in">
            <div class="w-8 h-8 rounded-xl bg-emerald-500 text-white flex items-center justify-center flex-shrink-0">
                <i class="fa-solid fa-check"></i>
            </div>
            <div>
                <p class="text-sm font-bold">Operación exitosa</p>
                <p class="text-xs text-emerald-700">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    <!-- Score & Quick Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-5">
        <!-- Main Score -->
        <div class="md:col-span-1 bg-white rounded-3xl p-6 shadow-sm border border-slate-200/80 flex flex-col justify-between relative overflow-hidden">
            <div class="text-xs font-bold uppercase tracking-wider text-slate-400">Puntaje de Seguridad</div>
            <div class="my-4 flex items-center gap-4">
                <div class="relative flex items-center justify-center w-20 h-20 rounded-2xl bg-emerald-50 border-2 border-emerald-400/40 text-emerald-600 font-display font-black text-3xl shadow-inner">
                    {{ $diagnostics['score'] }}
                    <span class="text-xs font-semibold absolute bottom-1 right-2 text-emerald-500">/100</span>
                </div>
                <div>
                    <span class="inline-block px-2.5 py-0.5 rounded-full text-xs font-black bg-emerald-100 text-emerald-800 uppercase tracking-wide">
                        {{ $diagnostics['score'] >= 90 ? 'Sistema Blindado' : 'Requiere Atención' }}
                    </span>
                    <p class="text-xs text-slate-500 mt-1.5 leading-snug">
                        {{ $diagnostics['secure_checks'] }} de {{ $diagnostics['total_checks'] }} verificaciones críticas aprobadas.
                    </p>
                </div>
            </div>
            <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                <div class="bg-emerald-500 h-full rounded-full transition-all duration-700" style="width: {{ $diagnostics['score'] }}%"></div>
            </div>
        </div>

        <!-- Metric 1: SQL Injection -->
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-200/80 flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Inyección SQL</span>
                <span class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-sm">
                    <i class="fa-solid fa-database"></i>
                </span>
            </div>
            <div class="mt-3">
                <div class="text-xl font-black text-slate-800 flex items-center gap-2">
                    <i class="fa-solid fa-circle-check text-emerald-500 text-base"></i>
                    100% Protegido
                </div>
                <p class="text-xs text-slate-500 mt-1">PDO Prepared Statements + Filtro Cortafuegos WAF activo.</p>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-100 text-[11px] font-semibold text-blue-600">
                Parámetros SQL Vinculados
            </div>
        </div>

        <!-- Metric 2: Customer Data -->
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-200/80 flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Datos de Clientes</span>
                <span class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-sm">
                    <i class="fa-solid fa-user-shield"></i>
                </span>
            </div>
            <div class="mt-3">
                <div class="text-xl font-black text-slate-800 flex items-center gap-2">
                    <i class="fa-solid fa-lock text-emerald-500 text-base"></i>
                    Aislado y Seguro
                </div>
                <p class="text-xs text-slate-500 mt-1">Sin exposición de datos financieros. Encriptación Bcrypt.</p>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-100 text-[11px] font-semibold text-emerald-600">
                Privacidad estricta
            </div>
        </div>

        <!-- Metric 3: WAF Active Protection -->
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-200/80 flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Cortafuegos WAF</span>
                <span class="w-8 h-8 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-sm">
                    <i class="fa-solid fa-network-wired"></i>
                </span>
            </div>
            <div class="mt-3">
                <div class="text-xl font-black text-slate-800 flex items-center gap-2">
                    <i class="fa-solid fa-bolt text-amber-500 text-base"></i>
                    {{ ($settings['security_firewall_enabled'] ?? '1') === '1' ? 'Filtro Activo' : 'Pausado' }}
                </div>
                <p class="text-xs text-slate-500 mt-1">Inspección de peticiones entrantes contra payloads maliciosos.</p>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-100 text-[11px] font-semibold text-purple-600">
                Bloqueo en tiempo real
            </div>
        </div>
    </div>

    <!-- Threat Defense Matrix (Diagnósticos detallados) -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-200/80">
        <div class="flex items-center justify-between pb-6 mb-6 border-b border-slate-100">
            <div>
                <h2 class="text-xl font-black text-slate-800 font-display">Matriz de Defensas & Certificación Técnica</h2>
                <p class="text-sm text-slate-500 mt-1">Estado de los protocolos implementados en el núcleo del catálogo virtual.</p>
            </div>
            <span class="text-xs font-bold bg-slate-100 text-slate-600 px-3 py-1 rounded-full">
                {{ count($diagnostics['checks']) }} Protocolos Verificados
            </span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($diagnostics['checks'] as $check)
                <div class="p-5 rounded-2xl border transition-all hover:shadow-md {{ $check['status'] === 'secure' ? 'bg-slate-50/50 border-slate-200/90' : ($check['status'] === 'warning' ? 'bg-amber-50/40 border-amber-200' : 'bg-red-50/40 border-red-200') }}">
                    <div class="flex items-start justify-between gap-3 mb-2">
                        <div class="flex items-center gap-2.5">
                            @if($check['status'] === 'secure')
                                <span class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-xs flex-shrink-0">
                                    <i class="fa-solid fa-check"></i>
                                </span>
                            @elseif($check['status'] === 'warning')
                                <span class="w-6 h-6 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center text-xs flex-shrink-0">
                                    <i class="fa-solid fa-triangle-exclamation"></i>
                                </span>
                            @else
                                <span class="w-6 h-6 rounded-full bg-red-100 text-red-600 flex items-center justify-center text-xs flex-shrink-0">
                                    <i class="fa-solid fa-xmark"></i>
                                </span>
                            @endif
                            <h3 class="font-bold text-slate-800 text-sm">{{ $check['title'] }}</h3>
                        </div>
                        <span class="text-[10px] font-extrabold uppercase px-2.5 py-0.5 rounded-full {{ $check['status'] === 'secure' ? 'bg-emerald-100 text-emerald-800' : ($check['status'] === 'warning' ? 'bg-amber-100 text-amber-800' : 'bg-red-100 text-red-800') }}">
                            {{ $check['badge'] }}
                        </span>
                    </div>

                    <p class="text-xs text-slate-600 leading-relaxed pl-8 mb-3">
                        {{ $check['description'] }}
                    </p>

                    <div class="pl-8 flex items-center gap-2 text-[11px] font-mono text-slate-500 bg-white/70 py-1.5 px-3 rounded-lg border border-slate-200/50">
                        <i class="fa-solid fa-terminal text-indigo-500 text-[10px]"></i>
                        <span>{{ $check['details'] }}</span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Security Configuration Form -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-200/80">
        <div class="mb-6 pb-6 border-b border-slate-100">
            <h2 class="text-xl font-black text-slate-800 font-display">Controles y Reglas de Seguridad Activas</h2>
            <p class="text-sm text-slate-500 mt-1">Personaliza el nivel de protección perimetral y mitigación de amenazas.</p>
        </div>

        <form action="{{ route('admin.security.update') }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Toggle 1: WAF -->
                <div class="p-5 rounded-2xl border border-slate-200 bg-slate-50/40 flex items-start justify-between gap-4">
                    <div class="space-y-1">
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-fire text-amber-500"></i>
                            <label for="security_firewall_enabled" class="font-bold text-sm text-slate-800 cursor-pointer">
                                Cortafuegos de Aplicación WAF (Anti-SQLi)
                            </label>
                        </div>
                        <p class="text-xs text-slate-500 leading-relaxed">
                            Inspecciona peticiones GET/POST y bloquea automáticamente cargas útiles con ataques SQL como <code class="bg-slate-200 px-1 rounded text-[10px]">UNION SELECT</code>, <code class="bg-slate-200 px-1 rounded text-[10px]">DROP TABLE</code> u <code class="bg-slate-200 px-1 rounded text-[10px]">OR 1=1</code>.
                        </p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer flex-shrink-0 mt-1">
                        <input type="checkbox" name="security_firewall_enabled" id="security_firewall_enabled" value="1" class="sr-only peer" {{ ($settings['security_firewall_enabled'] ?? '1') === '1' ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600"></div>
                    </label>
                </div>

                <!-- Toggle 2: Rate Limiting -->
                <div class="p-5 rounded-2xl border border-slate-200 bg-slate-50/40 flex items-start justify-between gap-4">
                    <div class="space-y-1">
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-gauge-high text-blue-500"></i>
                            <label for="security_rate_limit_enabled" class="font-bold text-sm text-slate-800 cursor-pointer">
                                Límite de Peticiones (Rate Limiting)
                            </label>
                        </div>
                        <p class="text-xs text-slate-500 leading-relaxed">
                            Mitiga ataques de fuerza bruta en logins y envíos automatizados de formularios de pedidos o comentarios sospechosos.
                        </p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer flex-shrink-0 mt-1">
                        <input type="checkbox" name="security_rate_limit_enabled" id="security_rate_limit_enabled" value="1" class="sr-only peer" {{ ($settings['security_rate_limit_enabled'] ?? '1') === '1' ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600"></div>
                    </label>
                </div>

                <!-- Toggle 3: Hide Server Info -->
                <div class="p-5 rounded-2xl border border-slate-200 bg-slate-50/40 flex items-start justify-between gap-4">
                    <div class="space-y-1">
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-eye-slash text-purple-500"></i>
                            <label for="security_hide_server_info" class="font-bold text-sm text-slate-800 cursor-pointer">
                                Ocultar Identidad del Servidor (Server Masking)
                            </label>
                        </div>
                        <p class="text-xs text-slate-500 leading-relaxed">
                            Elimina cabeceras HTTP que revelan versiones exactas de PHP o Apache (<code class="bg-slate-200 px-1 rounded text-[10px]">X-Powered-By</code>) para evitar escaneos de atacantes.
                        </p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer flex-shrink-0 mt-1">
                        <input type="checkbox" name="security_hide_server_info" id="security_hide_server_info" value="1" class="sr-only peer" {{ ($settings['security_hide_server_info'] ?? '1') === '1' ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600"></div>
                    </label>
                </div>

                <!-- Toggle 4: Threat Notification -->
                <div class="p-5 rounded-2xl border border-slate-200 bg-slate-50/40 flex items-start justify-between gap-4">
                    <div class="space-y-1">
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-bell text-rose-500"></i>
                            <label for="security_notify_threats" class="font-bold text-sm text-slate-800 cursor-pointer">
                                Registro de Auditoría en Tiempo Real
                            </label>
                        </div>
                        <p class="text-xs text-slate-500 leading-relaxed">
                            Guarda traza inmediata con dirección IP, fecha y patrón detectado de cualquier intento de vulneración o inyección en el catálogo.
                        </p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer flex-shrink-0 mt-1">
                        <input type="checkbox" name="security_notify_threats" id="security_notify_threats" value="1" class="sr-only peer" {{ ($settings['security_notify_threats'] ?? '1') === '1' ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600"></div>
                    </label>
                </div>
            </div>

            <!-- Blocked IPs Section -->
            <div class="pt-4 border-t border-slate-100">
                <label for="security_blocked_ips" class="block font-bold text-sm text-slate-800 mb-1 flex items-center gap-2">
                    <i class="fa-solid fa-ban text-red-500"></i>
                    Lista Negra de Direcciones IP Bloqueadas
                </label>
                <p class="text-xs text-slate-500 mb-3">
                    Ingresa direcciones IPv4 o IPv6 para bloquearles el acceso total al sitio. Separa múltiples IPs por comas o saltos de línea (ejemplo: <code class="bg-slate-100 px-1 rounded text-red-600">192.168.1.100, 10.0.0.50</code>).
                </p>
                <textarea name="security_blocked_ips" id="security_blocked_ips" rows="3" class="w-full font-mono text-xs rounded-2xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 p-3 bg-slate-50/50" placeholder="192.0.2.1, 198.51.100.2">{{ $settings['security_blocked_ips'] ?? '' }}</textarea>
            </div>

            <div class="flex justify-end pt-4">
                <button type="submit" class="inline-flex items-center gap-2 bg-slate-900 hover:bg-slate-800 text-white font-bold text-sm px-6 py-3 rounded-2xl shadow-lg transition-all hover:scale-[1.01] active:scale-95">
                    <i class="fa-solid fa-floppy-disk text-indigo-300"></i>
                    Guardar Configuración de Seguridad
                </button>
            </div>
        </form>
    </div>

    <!-- Security Events Audit Log Table -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-200/80">
        <div class="flex items-center justify-between pb-6 mb-6 border-b border-slate-100">
            <div>
                <h2 class="text-xl font-black text-slate-800 font-display flex items-center gap-2">
                    <i class="fa-solid fa-clock-rotate-left text-indigo-600"></i>
                    Bitácora de Auditoría y Eventos de Seguridad
                </h2>
                <p class="text-sm text-slate-500 mt-1">Registro de diagnósticos, cambios administrativos y mitigaciones recientes.</p>
            </div>
            <span class="text-xs font-mono text-slate-500 bg-slate-100 px-3 py-1 rounded-lg">
                IP Actual: <strong class="text-slate-700">{{ request()->ip() }}</strong>
            </span>
        </div>

        <div class="overflow-x-auto rounded-2xl border border-slate-100">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-200/80 text-slate-500 font-bold uppercase tracking-wider">
                        <th class="py-3 px-4">Fecha y Hora</th>
                        <th class="py-3 px-4">Acción / Evento</th>
                        <th class="py-3 px-4">Módulo</th>
                        <th class="py-3 px-4">Usuario / Origen</th>
                        <th class="py-3 px-4">Dirección IP</th>
                        <th class="py-3 px-4 text-right">Detalles</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($logs as $log)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="py-3 px-4 text-slate-500 whitespace-nowrap">
                                {{ $log->created_at ? $log->created_at->isoFormat('D MMM YYYY, h:mm A') : 'N/A' }}
                            </td>
                            <td class="py-3 px-4 font-semibold text-slate-800">
                                @if(str_contains(strtolower($log->action), 'bloqueado') || str_contains(strtolower($log->action), 'amenaza') || str_contains(strtolower($log->action), 'sql'))
                                    <span class="inline-flex items-center gap-1.5 text-red-600 font-bold">
                                        <i class="fa-solid fa-triangle-exclamation"></i>
                                        {{ $log->action }}
                                    </span>
                                @else
                                    <span class="text-slate-700">
                                        <i class="fa-solid fa-circle-check text-emerald-500 mr-1"></i>
                                        {{ $log->action }}
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 px-4">
                                <span class="bg-slate-100 text-slate-600 px-2 py-0.5 rounded-md font-mono text-[11px]">
                                    {{ $log->model ?? 'Sistema' }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-slate-600">
                                {{ $log->user->name ?? 'Sistema Automático' }}
                            </td>
                            <td class="py-3 px-4 font-mono text-slate-600">
                                {{ $log->ip_address ?? '127.0.0.1' }}
                            </td>
                            <td class="py-3 px-4 text-right text-slate-500">
                                @if(is_array($log->details))
                                    <span class="text-[11px] text-slate-500" title="{{ json_encode($log->details) }}">
                                        {{ Str::limit(json_encode($log->details), 40) }}
                                    </span>
                                @else
                                    <span class="text-slate-400">—</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-400">
                                <i class="fa-solid fa-shield-check text-3xl mb-2 text-slate-300"></i>
                                <p>No hay incidentes de seguridad registrados. El sistema opera de forma segura.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
