@extends('layouts.admin')

@section('header_title', 'Verificación de Seguridad OTP')

@section('content')
<div class="max-w-xl mx-auto py-6 space-y-6">

    <!-- Card Principal -->
    <div class="soft-card p-8 text-center relative overflow-hidden shadow-lg border border-slate-200">
        
        <!-- Icono de Seguridad con pulso -->
        <div class="mx-auto w-16 h-16 rounded-2xl bg-blue-50 border border-blue-200 flex items-center justify-center text-blue-600 text-2xl mb-4 shadow-sm">
            <i class="fa-solid fa-shield-halved animate-pulse"></i>
        </div>

        <h2 class="text-2xl font-bold font-display text-slate-800 tracking-tight">Verificación de Seguridad</h2>
        <p class="text-xs text-slate-500 uppercase tracking-widest font-mono mt-1">Capa de Protección — Administrador General</p>

        <!-- Información de la Acción Crítica -->
        <div class="my-6 p-4 rounded-xl bg-slate-50 border border-slate-200 text-left">
            <div class="flex items-center gap-3">
                <span class="w-8 h-8 rounded-lg bg-amber-100 text-amber-700 flex items-center justify-center flex-shrink-0 text-sm">
                    <i class="fa-solid fa-lock"></i>
                </span>
                <div class="min-w-0 flex-1">
                    <div class="text-xs text-slate-400 uppercase font-semibold">Acción solicitada</div>
                    <div class="text-sm font-bold text-slate-800 truncate">{{ $pending['action_name'] ?? 'Modificación de alta seguridad' }}</div>
                </div>
            </div>
            @if(!empty($pending['description']))
                <div class="mt-2 text-xs text-slate-600 border-t border-slate-200 pt-2">
                    {{ $pending['description'] }}
                </div>
            @endif
        </div>

        <p class="text-sm text-slate-600 leading-relaxed mb-6">
            Hemos enviado un código OTP de <strong class="text-slate-800">6 dígitos</strong> al correo registrado:
            <br>
            <span class="inline-block px-3 py-1 mt-1 bg-blue-50 text-blue-700 font-mono font-bold text-sm rounded-md border border-blue-200">
                {{ $maskedEmail }}
            </span>
        </p>

        @if(!empty($latestCode))
            <div class="mb-6 p-4 rounded-xl bg-amber-50 border border-amber-300 text-amber-900 text-xs text-left">
                <div class="flex items-center gap-2 font-bold mb-1">
                    <i class="fa-solid fa-code"></i> Modo Desarrollo Local (MAIL_MAILER=log):
                </div>
                <p>Código de verificación generado: <strong class="text-base font-mono text-blue-700 select-all tracking-wider">{{ $latestCode }}</strong></p>
                <button type="button" onclick="document.getElementById('otp_code').value = '{{ $latestCode }}'" class="mt-2 text-xs text-blue-700 font-bold hover:underline inline-flex items-center gap-1">
                    <i class="fa-solid fa-wand-magic-sparkles"></i> Autocompletar código aquí
                </button>
            </div>
        @endif

        <!-- Alertas de Error / Éxito -->
        @if($errors->has('otp_code'))
            <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm flex items-center gap-3 text-left">
                <i class="fa-solid fa-triangle-exclamation text-lg flex-shrink-0 text-red-500"></i>
                <div>
                    <strong>Código Inválido:</strong> {{ $errors->first('otp_code') }}
                </div>
            </div>
        @endif

        @if(session('success'))
            <div class="mb-6 p-4 rounded-xl bg-green-50 border border-green-200 text-green-700 text-sm flex items-center gap-3 text-left">
                <i class="fa-solid fa-circle-check text-lg flex-shrink-0 text-green-500"></i>
                <div>{{ session('success') }}</div>
            </div>
        @endif

        <!-- Formulario OTP -->
        <form action="{{ route('admin.security.otp.verify.submit') }}" method="POST" class="space-y-6">
            @csrf

            <div>
                <label for="otp_code" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">
                    Ingresa el código recibido
                </label>
                <div class="relative max-w-xs mx-auto">
                    <input 
                        type="text" 
                        name="otp_code" 
                        id="otp_code" 
                        maxlength="6" 
                        pattern="[0-9]{6}" 
                        inputmode="numeric" 
                        autocomplete="one-time-code" 
                        required 
                        autofocus 
                        placeholder="••••••"
                        class="w-full text-center text-3xl font-mono tracking-[0.5em] font-extrabold py-3 px-4 rounded-xl border-2 border-slate-300 focus:border-blue-600 focus:ring-4 focus:ring-blue-100 transition-all shadow-inner text-slate-800 bg-white"
                    >
                </div>
            </div>

            <!-- Contador de vigencia (10 minutos) -->
            <div class="flex items-center justify-center gap-2 text-xs font-medium text-slate-500" x-data="otpCountdown()">
                <i class="fa-regular fa-clock"></i>
                <span>El código expira en:</span>
                <span class="font-mono font-bold text-amber-600" x-text="timeFormatted">10:00</span>
            </div>

            <!-- Botón de Envío -->
            <button type="submit" class="w-full py-3 px-4 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white rounded-xl font-bold text-sm shadow-md hover:shadow-lg transition-all flex items-center justify-center gap-2">
                <i class="fa-solid fa-check-circle"></i>
                <span>Verificar y Autorizar Cambio</span>
            </button>
        </form>

        <!-- Opciones secundarias: Reenviar y Cancelar -->
        <div class="mt-6 pt-6 border-t border-slate-100 flex items-center justify-between text-xs">
            <form action="{{ route('admin.security.otp.resend') }}" method="POST">
                @csrf
                <button type="submit" class="text-blue-600 hover:text-blue-800 font-semibold flex items-center gap-1.5 transition-colors">
                    <i class="fa-solid fa-rotate-right"></i>
                    <span>Reenviar código</span>
                </button>
            </form>

            <form action="{{ route('admin.security.otp.cancel') }}" method="POST">
                @csrf
                <button type="submit" class="text-slate-400 hover:text-red-600 font-semibold flex items-center gap-1.5 transition-colors">
                    <i class="fa-solid fa-xmark"></i>
                    <span>Cancelar operación</span>
                </button>
            </form>
        </div>

    </div>

    <!-- Banner Informativo de Auditoría -->
    <div class="text-center text-xs text-slate-400 flex items-center justify-center gap-2">
        <i class="fa-solid fa-shield-cat"></i>
        <span>Esta solicitud y todos los intentos están siendo registrados en la bitácora de auditoría.</span>
    </div>

</div>

<script>
    function otpCountdown() {
        return {
            secondsLeft: 600,
            interval: null,
            init() {
                this.interval = setInterval(() => {
                    if (this.secondsLeft > 0) {
                        this.secondsLeft--;
                    } else {
                        clearInterval(this.interval);
                    }
                }, 1000);
            },
            get timeFormatted() {
                const m = Math.floor(this.secondsLeft / 60);
                const s = this.secondsLeft % 60;
                return `${m.toString().padStart(2, '0')}:${s.toString().padStart(2, '0')}`;
            }
        }
    }
</script>
@endsection
