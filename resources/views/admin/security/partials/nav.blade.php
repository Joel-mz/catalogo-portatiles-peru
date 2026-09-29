@php
    $currentRoute = Route::currentRouteName();
    $tabs = [
        ['route' => 'admin.security.index', 'title' => 'Dashboard', 'icon' => 'fa-gauge-high'],
        ['route' => 'admin.security.attacks', 'title' => 'Ataques', 'icon' => 'fa-shield-virus'],
        ['route' => 'admin.security.firewall', 'title' => 'Firewall', 'icon' => 'fa-fire-flame-curved'],
        ['route' => 'admin.users.index', 'title' => 'Usuarios', 'icon' => 'fa-users-gear'],
        ['route' => 'admin.security.logs', 'title' => 'Logs', 'icon' => 'fa-file-lines'],
        ['route' => 'admin.security.login_attempts', 'title' => 'Intentos Login', 'icon' => 'fa-user-check'],
        ['route' => 'admin.security.passwords', 'title' => 'Contraseñas', 'icon' => 'fa-key'],
        ['route' => 'admin.security.mfa', 'title' => 'MFA (2FA)', 'icon' => 'fa-mobile-screen-button'],
        ['route' => 'admin.security.sessions', 'title' => 'Sesiones', 'icon' => 'fa-fingerprint'],
        ['route' => 'admin.security.database', 'title' => 'Base de Datos', 'icon' => 'fa-database'],
        ['route' => 'admin.security.files', 'title' => 'Archivos', 'icon' => 'fa-folder-shield'],
        ['route' => 'admin.security.https', 'title' => 'HTTPS (SSL)', 'icon' => 'fa-certificate'],
        ['route' => 'admin.security.threats', 'title' => 'Amenazas', 'icon' => 'fa-satellite-dish'],
        ['route' => 'admin.security.alerts', 'title' => 'Alertas', 'icon' => 'fa-bell'],
        ['route' => 'admin.security.audit', 'title' => 'Auditoría', 'icon' => 'fa-clipboard-check'],
    ];
@endphp

<div class="space-y-4">
    <!-- Top Bar with Breadcrumbs & Scan Trigger -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-slate-900 text-emerald-400 flex items-center justify-center font-bold text-lg shadow-sm flex-shrink-0">
                <i class="fa-solid fa-lock"></i>
            </div>
            <div>
                <div class="flex items-center gap-2 text-xs font-semibold text-slate-400">
                    <span>Panel</span>
                    <span>/</span>
                    <a href="{{ route('admin.security.index') }}" class="hover:text-slate-600 transition-colors">Seguridad</a>
                    @if(isset($subTitle))
                        <span>/</span>
                        <span class="text-slate-700 font-bold">{{ $subTitle }}</span>
                    @endif
                </div>
                <h1 class="text-lg font-black text-slate-800 font-display">
                    {{ $pageTitle ?? 'Módulo de Seguridad del Sistema' }}
                </h1>
            </div>
        </div>

        <div class="flex items-center gap-2 flex-wrap">
            <form action="{{ route('admin.security.scan') }}" method="POST">
                @csrf
                <button type="submit" class="btn-primary text-xs py-2 px-3.5 bg-emerald-600 hover:bg-emerald-700 shadow-sm">
                    <i class="fa-solid fa-radar"></i> Escanear Sistema
                </button>
            </form>

            <form action="{{ route('admin.security.clear_sessions') }}" method="POST" onsubmit="return confirm('¿Regenerar la sesión activa por seguridad?')">
                @csrf
                <button type="submit" class="btn-secondary text-xs py-2 px-3">
                    <i class="fa-solid fa-rotate text-slate-500"></i> Regenerar Sesión
                </button>
            </form>
        </div>
    </div>

    <!-- Horizontal Pill Submenus -->
    <div class="flex items-center gap-1.5 overflow-x-auto pb-2 border-b border-slate-200/80 scrollbar-none text-xs font-medium">
        @foreach($tabs as $tab)
            @php
                $isActive = $currentRoute === $tab['route'] || (str_contains($tab['route'], 'users') && request()->routeIs('admin.users.*'));
            @endphp
            <a href="{{ route($tab['route']) }}" 
               class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl whitespace-nowrap transition-all {{ $isActive ? 'bg-slate-900 text-white font-bold shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200/60' }}">
                <i class="fa-solid {{ $tab['icon'] }} {{ $isActive ? 'text-emerald-400' : 'text-slate-400' }} text-xs"></i>
                <span>{{ $tab['title'] }}</span>
            </a>
        @endforeach
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl shadow-sm flex items-center gap-2.5 text-xs font-medium">
            <i class="fa-solid fa-circle-check text-emerald-600 text-sm"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl shadow-sm flex items-center gap-2.5 text-xs font-medium">
            <i class="fa-solid fa-circle-exclamation text-red-600 text-sm"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif
</div>
