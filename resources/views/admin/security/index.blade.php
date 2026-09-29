@extends('layouts.admin')
@section('header_title', 'Dashboard de Seguridad')
@section('content')

<div class="max-w-7xl mx-auto space-y-6">

    @include('admin.security.partials.nav', ['pageTitle' => 'Dashboard de Seguridad Integral', 'subTitle' => 'Visión General'])

    <!-- Hero Score & Status -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
        <!-- Main Score Card -->
        <div class="lg:col-span-1 bg-gradient-to-br from-slate-900 via-slate-800 to-indigo-950 text-white rounded-3xl p-6 shadow-sm border border-slate-700/50 flex flex-col justify-between relative overflow-hidden">
            <div class="absolute -right-6 -bottom-6 w-32 h-32 bg-emerald-500/10 rounded-full blur-2xl pointer-events-none"></div>
            
            <div>
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Puntaje Global de Salud</span>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-500/20 text-emerald-400 border border-emerald-500/30">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                        ACTIVO
                    </span>
                </div>
                <div class="mt-4 flex items-center gap-4">
                    <div class="w-20 h-20 rounded-2xl bg-white/10 border border-white/10 flex items-center justify-center font-display font-black text-3xl text-emerald-400 shadow-inner">
                        {{ $diagnostics['score'] }}
                    </div>
                    <div>
                        <div class="text-lg font-black text-white font-display">
                            {{ $diagnostics['score'] >= 90 ? 'Sistema Blindado' : 'Revisión Sugerida' }}
                        </div>
                        <p class="text-xs text-slate-300 mt-1 leading-snug">
                            {{ $diagnostics['secure_checks'] }} de {{ $diagnostics['total_checks'] }} protocolos de seguridad críticos verificados.
                        </p>
                    </div>
                </div>
            </div>

            <div class="mt-6 pt-4 border-t border-white/10 flex items-center justify-between text-xs text-slate-400 font-medium">
                <span>WAF Anti-SQLi: <strong class="text-emerald-400">Activo</strong></span>
                <span>SSL: <strong class="text-emerald-400">Protegido</strong></span>
            </div>
        </div>

        <!-- 4 Quick Stats -->
        <div class="lg:col-span-2 grid grid-cols-2 sm:grid-cols-4 gap-3 sm:gap-4">
            <div class="kpi-card hover:border-blue-300">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Inyección SQL</span>
                    <div class="w-7 h-7 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-xs">
                        <i class="fa-solid fa-database"></i>
                    </div>
                </div>
                <p class="text-xl sm:text-2xl font-display font-black text-slate-800">100%</p>
                <span class="badge badge-green text-[9px] mt-2">Parametrizado</span>
            </div>

            <div class="kpi-card hover:border-emerald-300">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Firewall WAF</span>
                    <div class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-xs">
                        <i class="fa-solid fa-fire"></i>
                    </div>
                </div>
                <p class="text-xl sm:text-2xl font-display font-black text-slate-800">
                    {{ ($settings['security_firewall_enabled'] ?? '1') === '1' ? 'Activo' : 'Inactivo' }}
                </p>
                <span class="badge badge-blue text-[9px] mt-2">Tiempo Real</span>
            </div>

            <div class="kpi-card hover:border-purple-300">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Datos Clientes</span>
                    <div class="w-7 h-7 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center text-xs">
                        <i class="fa-solid fa-user-shield"></i>
                    </div>
                </div>
                <p class="text-xl sm:text-2xl font-display font-black text-slate-800">Aislado</p>
                <span class="badge badge-green text-[9px] mt-2">Bcrypt + Auth</span>
            </div>

            <div class="kpi-card hover:border-amber-300">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">IPs Bloqueadas</span>
                    <div class="w-7 h-7 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center text-xs">
                        <i class="fa-solid fa-ban"></i>
                    </div>
                </div>
                @php
                    $blockedCount = count(array_filter(array_map('trim', explode(',', $settings['security_blocked_ips'] ?? ''))));
                @endphp
                <p class="text-xl sm:text-2xl font-display font-black text-slate-800">{{ $blockedCount }}</p>
                <span class="badge badge-gray text-[9px] mt-2">Lista Negra</span>
            </div>
        </div>
    </div>

    <!-- Quick Access Hub to all 15 Security Modules -->
    <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-200/80">
        <div class="flex items-center justify-between mb-5 pb-4 border-b border-slate-100">
            <div>
                <h2 class="text-base font-black text-slate-800 font-display flex items-center gap-2">
                    <i class="fa-solid fa-cubes text-indigo-600"></i>
                    Módulos y Submenús de Seguridad Integrados
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">Acceso directo a cada una de las 15 herramientas de seguridad del catálogo.</p>
            </div>
            <span class="text-[11px] font-mono bg-slate-100 text-slate-600 px-3 py-1 rounded-lg">15 Módulos</span>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-3.5">
            <a href="{{ route('admin.security.attacks') }}" class="p-4 rounded-2xl border border-slate-200/80 bg-slate-50/50 hover:bg-indigo-50/40 hover:border-indigo-200 transition-all group">
                <div class="w-9 h-9 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center text-sm mb-3 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-shield-virus"></i>
                </div>
                <h3 class="font-bold text-xs text-slate-800 group-hover:text-indigo-600">Protección contra Ataques</h3>
                <p class="text-[11px] text-slate-500 mt-1">SQLi, XSS, CSRF, Brute Force</p>
            </a>

            <a href="{{ route('admin.security.firewall') }}" class="p-4 rounded-2xl border border-slate-200/80 bg-slate-50/50 hover:bg-indigo-50/40 hover:border-indigo-200 transition-all group">
                <div class="w-9 h-9 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-sm mb-3 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-fire-flame-curved"></i>
                </div>
                <h3 class="font-bold text-xs text-slate-800 group-hover:text-indigo-600">Firewall del Sistema</h3>
                <p class="text-[11px] text-slate-500 mt-1">WAF y filtrado de IPs</p>
            </a>

            <a href="{{ route('admin.users.index') }}" class="p-4 rounded-2xl border border-slate-200/80 bg-slate-50/50 hover:bg-indigo-50/40 hover:border-indigo-200 transition-all group">
                <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-sm mb-3 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-users-gear"></i>
                </div>
                <h3 class="font-bold text-xs text-slate-800 group-hover:text-indigo-600">Gestión de Usuarios</h3>
                <p class="text-[11px] text-slate-500 mt-1">Roles y accesos autorizados</p>
            </a>

            <a href="{{ route('admin.security.logs') }}" class="p-4 rounded-2xl border border-slate-200/80 bg-slate-50/50 hover:bg-indigo-50/40 hover:border-indigo-200 transition-all group">
                <div class="w-9 h-9 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center text-sm mb-3 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-file-lines"></i>
                </div>
                <h3 class="font-bold text-xs text-slate-800 group-hover:text-indigo-600">Registro de Logs</h3>
                <p class="text-[11px] text-slate-500 mt-1">Historial completo de acciones</p>
            </a>

            <a href="{{ route('admin.security.login_attempts') }}" class="p-4 rounded-2xl border border-slate-200/80 bg-slate-50/50 hover:bg-indigo-50/40 hover:border-indigo-200 transition-all group">
                <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-sm mb-3 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-user-check"></i>
                </div>
                <h3 class="font-bold text-xs text-slate-800 group-hover:text-indigo-600">Intentos de Login</h3>
                <p class="text-[11px] text-slate-500 mt-1">Monitoreo de accesos</p>
            </a>

            <a href="{{ route('admin.security.passwords') }}" class="p-4 rounded-2xl border border-slate-200/80 bg-slate-50/50 hover:bg-indigo-50/40 hover:border-indigo-200 transition-all group">
                <div class="w-9 h-9 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-sm mb-3 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-key"></i>
                </div>
                <h3 class="font-bold text-xs text-slate-800 group-hover:text-indigo-600">Gestión Contraseñas</h3>
                <p class="text-[11px] text-slate-500 mt-1">Políticas Bcrypt y fuerza</p>
            </a>

            <a href="{{ route('admin.security.mfa') }}" class="p-4 rounded-2xl border border-slate-200/80 bg-slate-50/50 hover:bg-indigo-50/40 hover:border-indigo-200 transition-all group">
                <div class="w-9 h-9 rounded-xl bg-violet-50 text-violet-600 flex items-center justify-center text-sm mb-3 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-mobile-screen-button"></i>
                </div>
                <h3 class="font-bold text-xs text-slate-800 group-hover:text-indigo-600">Autenticación MFA (2FA)</h3>
                <p class="text-[11px] text-slate-500 mt-1">Segundo factor TOTP</p>
            </a>

            <a href="{{ route('admin.security.sessions') }}" class="p-4 rounded-2xl border border-slate-200/80 bg-slate-50/50 hover:bg-indigo-50/40 hover:border-indigo-200 transition-all group">
                <div class="w-9 h-9 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center text-sm mb-3 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-fingerprint"></i>
                </div>
                <h3 class="font-bold text-xs text-slate-800 group-hover:text-indigo-600">Seguridad de Sesiones</h3>
                <p class="text-[11px] text-slate-500 mt-1">Anti-Fixation y tiempo</p>
            </a>

            <a href="{{ route('admin.security.database') }}" class="p-4 rounded-2xl border border-slate-200/80 bg-slate-50/50 hover:bg-indigo-50/40 hover:border-indigo-200 transition-all group">
                <div class="w-9 h-9 rounded-xl bg-cyan-50 text-cyan-600 flex items-center justify-center text-sm mb-3 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-database"></i>
                </div>
                <h3 class="font-bold text-xs text-slate-800 group-hover:text-indigo-600">Seguridad Base Datos</h3>
                <p class="text-[11px] text-slate-500 mt-1">PDO, tablas e integridad</p>
            </a>

            <a href="{{ route('admin.security.files') }}" class="p-4 rounded-2xl border border-slate-200/80 bg-slate-50/50 hover:bg-indigo-50/40 hover:border-indigo-200 transition-all group">
                <div class="w-9 h-9 rounded-xl bg-amber-50 text-amber-700 flex items-center justify-center text-sm mb-3 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-folder-shield"></i>
                </div>
                <h3 class="font-bold text-xs text-slate-800 group-hover:text-indigo-600">Protección de Archivos</h3>
                <p class="text-[11px] text-slate-500 mt-1">.env, storage y permisos</p>
            </a>

            <a href="{{ route('admin.security.https') }}" class="p-4 rounded-2xl border border-slate-200/80 bg-slate-50/50 hover:bg-indigo-50/40 hover:border-indigo-200 transition-all group">
                <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center text-sm mb-3 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-certificate"></i>
                </div>
                <h3 class="font-bold text-xs text-slate-800 group-hover:text-indigo-600">Certificado HTTPS</h3>
                <p class="text-[11px] text-slate-500 mt-1">SSL/TLS y HSTS activo</p>
            </a>

            <a href="{{ route('admin.security.threats') }}" class="p-4 rounded-2xl border border-slate-200/80 bg-slate-50/50 hover:bg-indigo-50/40 hover:border-indigo-200 transition-all group">
                <div class="w-9 h-9 rounded-xl bg-purple-50 text-purple-700 flex items-center justify-center text-sm mb-3 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-satellite-dish"></i>
                </div>
                <h3 class="font-bold text-xs text-slate-800 group-hover:text-indigo-600">Monitor de Amenazas</h3>
                <p class="text-[11px] text-slate-500 mt-1">Radar en tiempo real</p>
            </a>

            <a href="{{ route('admin.security.alerts') }}" class="p-4 rounded-2xl border border-slate-200/80 bg-slate-50/50 hover:bg-indigo-50/40 hover:border-indigo-200 transition-all group">
                <div class="w-9 h-9 rounded-xl bg-rose-50 text-rose-700 flex items-center justify-center text-sm mb-3 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-bell"></i>
                </div>
                <h3 class="font-bold text-xs text-slate-800 group-hover:text-indigo-600">Alertas</h3>
                <p class="text-[11px] text-slate-500 mt-1">Canales y notificaciones</p>
            </a>

            <a href="{{ route('admin.security.audit') }}" class="p-4 rounded-2xl border border-slate-200/80 bg-slate-50/50 hover:bg-indigo-50/40 hover:border-indigo-200 transition-all group">
                <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-700 flex items-center justify-center text-sm mb-3 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-clipboard-check"></i>
                </div>
                <h3 class="font-bold text-xs text-slate-800 group-hover:text-indigo-600">Auditoría</h3>
                <p class="text-[11px] text-slate-500 mt-1">OWASP y certificación</p>
            </a>

            <a href="{{ route('admin.backups.index') }}" class="p-4 rounded-2xl border border-slate-200/80 bg-slate-50/50 hover:bg-indigo-50/40 hover:border-indigo-200 transition-all group">
                <div class="w-9 h-9 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-sm mb-3 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-database"></i>
                </div>
                <h3 class="font-bold text-xs text-slate-800 group-hover:text-indigo-600">Copias de Respaldo</h3>
                <p class="text-[11px] text-slate-500 mt-1">Historial de Backups</p>
            </a>
        </div>
    </div>

    <!-- Active Events Table -->
    <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-200/80">
        <div class="flex items-center justify-between mb-4 pb-4 border-b border-slate-100">
            <div>
                <h2 class="text-base font-black text-slate-800 font-display flex items-center gap-2">
                    <i class="fa-solid fa-clock-rotate-left text-slate-400"></i>
                    Actividad Reciente del Catálogo
                </h2>
                <p class="text-xs text-slate-500">Últimos eventos registrados en el sistema.</p>
            </div>
            <a href="{{ route('admin.security.logs') }}" class="btn-secondary text-xs py-1.5 px-3">
                Ver todos los logs <i class="fa-solid fa-chevron-right text-[10px] ml-1"></i>
            </a>
        </div>

        <div class="overflow-x-auto rounded-xl border border-slate-100">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Evento</th>
                        <th>Módulo</th>
                        <th>Usuario</th>
                        <th>IP</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs->take(6) as $log)
                        <tr>
                            <td class="text-slate-500 whitespace-nowrap text-xs">
                                {{ $log->created_at ? $log->created_at->isoFormat('D MMM, h:mm A') : 'N/A' }}
                            </td>
                            <td class="font-semibold text-slate-800 text-xs">
                                @if(str_contains(strtolower($log->action), 'bloqueado') || str_contains(strtolower($log->action), 'amenaza'))
                                    <span class="text-red-600 font-bold"><i class="fa-solid fa-shield-virus mr-1"></i> {{ $log->action }}</span>
                                @else
                                    <span class="text-slate-700"><i class="fa-solid fa-circle-check text-emerald-500 mr-1"></i> {{ $log->action }}</span>
                                @endif
                            </td>
                            <td><span class="badge badge-gray text-[10px]">{{ $log->model ?? 'Sistema' }}</span></td>
                            <td class="text-xs text-slate-600">{{ $log->user->name ?? 'Sistema' }}</td>
                            <td class="font-mono text-xs text-slate-500">{{ $log->ip_address }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-6 text-slate-400 text-xs">No hay eventos recientes registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
