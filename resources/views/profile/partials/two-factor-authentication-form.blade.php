<section x-data="twoFactorAuth()" class="space-y-6">
    <header class="flex items-start justify-between">
        <div>
            <h2 class="text-lg font-medium text-slate-900 dark:text-slate-100 flex items-center gap-2">
                <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
                Autenticación de Dos Pasos (2FA)
            </h2>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                Añade una capa extra de seguridad a tu cuenta mediante Google Authenticator, Microsoft Authenticator u otra app TOTP.
            </p>
        </div>

        @if(auth()->user()->hasEnabledTwoFactorAuthentication())
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800 border border-emerald-200 dark:bg-emerald-950/60 dark:text-emerald-300 dark:border-emerald-800">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                Activada
            </span>
        @else
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-700 border border-slate-200 dark:bg-slate-800 dark:text-slate-300 dark:border-slate-700">
                <span class="w-2 h-2 rounded-full bg-slate-400"></span>
                Desactivada
            </span>
        @endif
    </header>

    @if (session('status') === 'two-factor-authentication-disabled')
        <div class="p-4 rounded-xl bg-amber-50 border border-amber-200 text-amber-800 text-sm flex items-center gap-3 dark:bg-amber-950/40 dark:border-amber-800 dark:text-amber-300">
            <svg class="w-5 h-5 text-amber-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            <span>La autenticación de dos pasos ha sido desactivada para tu cuenta.</span>
        </div>
    @endif

    @if (session('status') === 'recovery-codes-regenerated')
        <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center gap-3 dark:bg-emerald-950/40 dark:border-emerald-800 dark:text-emerald-300">
            <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            <span>Nuevos códigos de recuperación generados con éxito. Asegúrate de guardarlos en un lugar seguro.</span>
        </div>
    @endif

    @if(auth()->user()->hasEnabledTwoFactorAuthentication())
        {{-- 2FA is ENABLED --}}
        <div class="space-y-6">
            <div class="p-4 rounded-xl bg-emerald-50/70 border border-emerald-200/80 text-emerald-900 text-sm flex items-start gap-3.5 dark:bg-emerald-950/30 dark:border-emerald-800/60 dark:text-emerald-200">
                <div class="p-2 bg-emerald-100 rounded-lg text-emerald-700 dark:bg-emerald-900/60 dark:text-emerald-300">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
                <div>
                    <h4 class="font-semibold text-emerald-950 dark:text-emerald-100">Tu cuenta está protegida con 2FA</h4>
                    <p class="mt-0.5 text-xs text-emerald-800 dark:text-emerald-300">
                        Cada vez que inicies sesión se solicitará un código de 6 dígitos generado por tu aplicación autenticadora.
                    </p>
                </div>
            </div>

            {{-- Recovery Codes Section --}}
            <div class="rounded-xl border border-slate-200 bg-slate-50/50 p-5 dark:border-slate-800 dark:bg-slate-900/50">
                <div class="flex items-center justify-between mb-3">
                    <h4 class="text-sm font-semibold text-slate-800 dark:text-slate-200 flex items-center gap-2">
                        <svg class="w-4 h-4 text-slate-600 dark:text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                        </svg>
                        Códigos de Recuperación de Emergencia
                    </h4>
                    <span class="text-xs text-slate-500">Usa cada uno solo una vez</span>
                </div>

                <p class="text-xs text-slate-600 dark:text-slate-400 mb-4">
                    Guarda estos códigos de recuperación en un gestor de contraseñas seguro. Te permitirán recuperar el acceso a tu cuenta si pierdes tu teléfono.
                </p>

                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 font-mono text-xs p-3.5 bg-white dark:bg-slate-950 rounded-lg border border-slate-200 dark:border-slate-800 shadow-inner">
                    @foreach(auth()->user()->two_factor_recovery_codes ?? [] as $code)
                        <div class="py-1 px-2 text-center bg-slate-50 dark:bg-slate-900 rounded border border-slate-200/60 dark:border-slate-800 text-slate-800 dark:text-slate-200 font-semibold tracking-wider">
                            {{ $code }}
                        </div>
                    @endforeach
                </div>

                <div class="mt-4 flex flex-wrap gap-2.5">
                    <form method="POST" action="{{ route('two-factor.recovery-codes') }}" class="inline">
                        @csrf
                        <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-lg bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-700 transition">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            Regenerar Códigos
                        </button>
                    </form>

                    <button type="button" @click="copyRecoveryCodes('{{ implode('\n', auth()->user()->two_factor_recovery_codes ?? []) }}')" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-lg bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-700 transition">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/>
                        </svg>
                        <span x-text="copied ? '¡Copiados al portapapeles!' : 'Copiar Códigos'"></span>
                    </button>
                </div>
            </div>

            {{-- Deactivate Button --}}
            <div class="pt-2">
                <button type="button" @click="showDisableModal = true" class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 text-white rounded-xl text-xs font-bold uppercase tracking-wider hover:bg-red-700 active:bg-red-800 shadow-sm transition">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                    </svg>
                    Desactivar 2FA
                </button>
            </div>
        </div>

    @else
        {{-- 2FA is DISABLED --}}
        <div>
            <p class="text-sm text-slate-600 dark:text-slate-400 mb-5">
                Cuando habilites la autenticación de dos pasos, se te pedirá un código numérico generado por tu teléfono para iniciar sesión, protegiendo tu panel ante accesos no autorizados.
            </p>

            <button type="button" @click="startSetup()" :disabled="loading" class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 text-white rounded-xl text-xs font-bold uppercase tracking-wider hover:bg-indigo-700 active:bg-indigo-800 shadow-md hover:shadow-indigo-500/25 transition disabled:opacity-50">
                <svg x-show="!loading" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
                <svg x-show="loading" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span x-text="loading ? 'Preparando...' : 'Habilitar 2FA Ahora'"></span>
            </button>
        </div>
    @endif

    {{-- SETUP MODAL --}}
    <div x-show="showSetupModal" 
         x-cloak 
         class="fixed inset-0 z-50 overflow-y-auto" 
         aria-labelledby="modal-title" 
         role="dialog" 
         aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="showSetupModal" 
                 x-transition:enter="ease-out duration-300" 
                 x-transition:enter-start="opacity-0" 
                 x-transition:enter-end="opacity-100" 
                 x-transition:leave="ease-in duration-200" 
                 x-transition:leave-start="opacity-100" 
                 x-transition:leave-end="opacity-0" 
                 class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" 
                 @click="showSetupModal = false"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="showSetupModal" 
                 x-transition:enter="ease-out duration-300" 
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                 x-transition:leave="ease-in duration-200" 
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                 class="inline-block align-bottom bg-white dark:bg-slate-900 rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-slate-200 dark:border-slate-800">
                
                <div class="p-6">
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100 dark:border-slate-800">
                        <div class="flex items-center gap-3">
                            <div class="p-2 rounded-xl bg-indigo-50 text-indigo-600 dark:bg-indigo-950/60 dark:text-indigo-400">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-slate-900 dark:text-white">Configurar Autenticación 2FA</h3>
                                <p class="text-xs text-slate-500 dark:text-slate-400">Escanea el código QR con tu aplicación autenticadora</p>
                            </div>
                        </div>
                        <button type="button" @click="showSetupModal = false" class="text-slate-400 hover:text-slate-500 dark:hover:text-slate-300">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    <div class="mt-5 space-y-5">
                        <div class="text-xs text-slate-600 dark:text-slate-300 space-y-2">
                            <p><strong>Paso 1:</strong> Abre tu aplicación de autenticación (<span class="font-semibold text-indigo-600 dark:text-indigo-400">Google Authenticator, Microsoft Authenticator o Authy</span>) y escanea este código QR:</p>
                        </div>

                        {{-- QR Code Display --}}
                        <div class="flex flex-col items-center justify-center p-4 bg-slate-50 dark:bg-slate-950 rounded-2xl border border-slate-200 dark:border-slate-800">
                            <template x-if="qrUrl">
                                <img :src="qrUrl" alt="Código QR 2FA" class="w-48 h-48 rounded-xl shadow-md bg-white p-2 border border-slate-200">
                            </template>
                            
                            <div class="mt-3 text-center">
                                <p class="text-[11px] text-slate-500 uppercase tracking-wider font-semibold">¿No puedes escanear el QR?</p>
                                <p class="text-xs font-mono font-bold text-indigo-600 dark:text-indigo-400 mt-1 select-all bg-indigo-50 dark:bg-indigo-950/40 px-3 py-1 rounded-lg border border-indigo-100 dark:border-indigo-900" x-text="secretFormatted"></p>
                            </div>
                        </div>

                        {{-- Step 2: Verification Code Form --}}
                        <div class="space-y-3">
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300">
                                <strong>Paso 2:</strong> Ingresa el código de 6 dígitos que muestra tu aplicación:
                            </label>
                            
                            <div class="relative">
                                <input type="text" 
                                       inputmode="numeric" 
                                       pattern="[0-9]*" 
                                       maxlength="6" 
                                       x-model="confirmCode" 
                                       @keyup.enter="confirmSetup()"
                                       placeholder="123456" 
                                       class="w-full text-center tracking-[0.4em] font-mono text-xl py-2.5 px-4 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm" />
                            </div>

                            <template x-if="errorMessage">
                                <p class="text-xs text-red-600 dark:text-red-400 font-medium" x-text="errorMessage"></p>
                            </template>
                        </div>
                    </div>
                </div>

                <div class="bg-slate-50 dark:bg-slate-800/50 px-6 py-4 flex items-center justify-end gap-3 border-t border-slate-100 dark:border-slate-800">
                    <button type="button" @click="showSetupModal = false" class="px-4 py-2 text-xs font-semibold text-slate-600 hover:text-slate-800 dark:text-slate-300 dark:hover:text-white">
                        Cancelar
                    </button>
                    <button type="button" @click="confirmSetup()" :disabled="confirming || confirmCode.length < 6" class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 text-white rounded-xl text-xs font-bold uppercase tracking-wider hover:bg-indigo-700 active:bg-indigo-800 shadow-md transition disabled:opacity-50">
                        <svg x-show="confirming" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span x-text="confirming ? 'Verificando...' : 'Activar 2FA'"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- DISABLE MODAL --}}
    <div x-show="showDisableModal" 
         x-cloak 
         class="fixed inset-0 z-50 overflow-y-auto" 
         aria-labelledby="modal-title" 
         role="dialog" 
         aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="showDisableModal" 
                 x-transition:enter="ease-out duration-300" 
                 x-transition:enter-start="opacity-0" 
                 x-transition:enter-end="opacity-100" 
                 x-transition:leave="ease-in duration-200" 
                 x-transition:leave-start="opacity-100" 
                 x-transition:leave-end="opacity-0" 
                 class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" 
                 @click="showDisableModal = false"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="showDisableModal" 
                 x-transition:enter="ease-out duration-300" 
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                 x-transition:leave="ease-in duration-200" 
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                 class="inline-block align-bottom bg-white dark:bg-slate-900 rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full border border-slate-200 dark:border-slate-800">
                
                <form method="POST" action="{{ route('two-factor.disable') }}">
                    @csrf
                    @method('DELETE')

                    <div class="p-6">
                        <div class="flex items-center gap-3 pb-4 border-b border-slate-100 dark:border-slate-800">
                            <div class="p-2 rounded-xl bg-red-50 text-red-600 dark:bg-red-950/60 dark:text-red-400">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-slate-900 dark:text-white">¿Desactivar Autenticación 2FA?</h3>
                                <p class="text-xs text-slate-500 dark:text-slate-400">Tu cuenta quedará menos protegida</p>
                            </div>
                        </div>

                        <div class="mt-4 space-y-3">
                            <p class="text-xs text-slate-600 dark:text-slate-300">
                                Para confirmar la desactivación, por favor ingresa tu contraseña actual:
                            </p>

                            <div>
                                <input type="password" 
                                       name="current_password" 
                                       required 
                                       placeholder="Tu contraseña actual" 
                                       class="w-full text-sm py-2.5 px-3.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-red-500 focus:border-red-500 shadow-sm" />
                                @error('current_password', 'twoFactorDisable')
                                    <p class="text-xs text-red-600 dark:text-red-400 mt-1 font-medium">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="bg-slate-50 dark:bg-slate-800/50 px-6 py-4 flex items-center justify-end gap-3 border-t border-slate-100 dark:border-slate-800">
                        <button type="button" @click="showDisableModal = false" class="px-4 py-2 text-xs font-semibold text-slate-600 hover:text-slate-800 dark:text-slate-300 dark:hover:text-white">
                            Cancelar
                        </button>
                        <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 text-white rounded-xl text-xs font-bold uppercase tracking-wider hover:bg-red-700 active:bg-red-800 shadow-sm transition">
                            Confirmar y Desactivar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<script>
    function twoFactorAuth() {
        return {
            loading: false,
            confirming: false,
            showSetupModal: false,
            showDisableModal: false,
            qrUrl: '',
            secret: '',
            secretFormatted: '',
            confirmCode: '',
            errorMessage: '',
            copied: false,

            startSetup() {
                this.loading = true;
                this.errorMessage = '';
                this.confirmCode = '';

                fetch('{{ route('two-factor.enable') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    this.loading = false;
                    this.qrUrl = data.qr_url;
                    this.secret = data.secret;
                    this.secretFormatted = data.formatted_secret;
                    this.showSetupModal = true;
                })
                .catch(err => {
                    this.loading = false;
                    alert('Error al iniciar la configuración de 2FA. Intenta de nuevo.');
                });
            },

            confirmSetup() {
                if (this.confirmCode.length < 6) return;
                this.confirming = true;
                this.errorMessage = '';

                fetch('{{ route('two-factor.confirm') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        code: this.confirmCode
                    })
                })
                .then(async res => {
                    const data = await res.json();
                    if (!res.ok) {
                        throw new Error(data.message || (data.errors && data.errors.code ? data.errors.code[0] : 'Código incorrecto'));
                    }
                    return data;
                })
                .then(data => {
                    this.confirming = false;
                    this.showSetupModal = false;
                    window.location.reload();
                })
                .catch(err => {
                    this.confirming = false;
                    this.errorMessage = err.message || 'Código incorrecto. Verifica en tu app e inténtalo de nuevo.';
                });
            },

            copyRecoveryCodes(codes) {
                navigator.clipboard.writeText(codes).then(() => {
                    this.copied = true;
                    setTimeout(() => { this.copied = false; }, 3000);
                });
            }
        }
    }
</script>
