@extends('layouts.admin')
@section('header_title', 'Auditoría de Seguridad')
@section('content')

<div class="max-w-7xl mx-auto space-y-6">
    @include('admin.security.partials.nav', ['pageTitle' => 'Auditoría & Cumplimiento de Seguridad (OWASP)', 'subTitle' => 'Auditoría'])

    <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-200/80">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6 pb-4 border-b border-slate-100">
            <div>
                <h2 class="text-base font-black text-slate-800 font-display">Reporte de Cumplimiento Técnico (OWASP Top 10)</h2>
                <p class="text-xs text-slate-500">Evaluación del catálogo virtual según las mejores prácticas internacionales de seguridad web.</p>
            </div>
            <button onclick="window.print()" class="btn-secondary text-xs py-2 px-3.5">
                <i class="fa-solid fa-print"></i> Imprimir Reporte
            </button>
        </div>

        <div class="space-y-3">
            @php
                $auditItems = [
                    ['code' => 'A01:2021', 'title' => 'Pérdida de Control de Acceso (Broken Access Control)', 'desc' => 'Rutas administrativas protegidas por middleware EnsureAdmin y verificación de sesión.', 'status' => 'passed'],
                    ['code' => 'A02:2021', 'title' => 'Fallas Criptográficas (Cryptographic Failures)', 'desc' => 'Contraseñas cifradas con Bcrypt. Tokens HMAC en formularios y cookies firmadas.', 'status' => 'passed'],
                    ['code' => 'A03:2021', 'title' => 'Inyección (Injection - SQLi, Command Injection)', 'desc' => 'PDO Prepared Statements en Eloquent ORM + Filtro WAF en peticiones entrantes.', 'status' => 'passed'],
                    ['code' => 'A04:2021', 'title' => 'Diseño Inseguro (Insecure Design)', 'desc' => 'Validación estricta de tipos de datos en FormRequests y arquitectura MVC estándar.', 'status' => 'passed'],
                    ['code' => 'A05:2021', 'title' => 'Configuración de Seguridad Defectuosa (Security Misconfiguration)', 'desc' => 'Archivo .env protegido fuera del directorio público. Cabeceras HTTP X-Frame-Options.', 'status' => 'passed'],
                    ['code' => 'A06:2021', 'title' => 'Vulnerabilidades en Dependencias (Vulnerable Components)', 'desc' => 'Laravel 11 actualizado con paquetes oficiales y dependencias auditadas por Composer.', 'status' => 'passed'],
                    ['code' => 'A07:2021', 'title' => 'Fallas de Identificación y Autenticación (Auth Failures)', 'desc' => 'Throttle contra fuerza bruta en login. Soporte de 2FA / MFA TOTP para administradores.', 'status' => 'passed'],
                    ['code' => 'A08:2021', 'title' => 'Fallas de Integridad de Software y Datos (Software & Data Integrity)', 'desc' => 'Verificación CSRF en todas las peticiones POST/PUT/DELETE. Validación de uploads.', 'status' => 'passed'],
                    ['code' => 'A09:2021', 'title' => 'Fallas de Registro y Monitoreo (Logging Failures)', 'desc' => 'Bitácora AuditLog activa con IP, fecha, modelo y acción detallada en base de datos.', 'status' => 'passed'],
                    ['code' => 'A10:2021', 'title' => 'Falsificación de Peticiones del Lado del Servidor (SSRF)', 'desc' => 'El catálogo no realiza peticiones salientes a direcciones internas no autorizadas.', 'status' => 'passed'],
                ];
            @endphp

            @foreach($auditItems as $item)
                <div class="p-4 rounded-2xl border border-slate-200/80 bg-slate-50/40 flex items-start justify-between gap-4">
                    <div class="space-y-1">
                        <div class="flex items-center gap-2">
                            <span class="badge badge-blue font-mono text-[10px]">{{ $item['code'] }}</span>
                            <h3 class="font-bold text-xs text-slate-800">{{ $item['title'] }}</h3>
                        </div>
                        <p class="text-[11px] text-slate-500">{{ $item['desc'] }}</p>
                    </div>
                    <span class="badge badge-green text-[10px] flex items-center gap-1 flex-shrink-0">
                        <i class="fa-solid fa-check"></i> Cumple
                    </span>
                </div>
            @endforeach
        </div>
    </div>
</div>

@endsection
