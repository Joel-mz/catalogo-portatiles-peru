@extends('layouts.admin')

@section('header_title', 'Editar Usuario')

@section('content')
<div class="max-w-3xl mx-auto space-y-6" x-data="userEditForm()">
    <div class="flex items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.users.index') }}" class="text-slate-400 hover:text-slate-600 transition-colors">
                <i class="fa-solid fa-arrow-left text-xl"></i>
            </a>
            <div>
                <h2 class="text-2xl font-bold font-display text-slate-800">Editar Usuario: {{ $user->name }}</h2>
                <p class="text-xs text-slate-500">Actualiza los datos, rol de trabajo, estado o credenciales del usuario.</p>
            </div>
        </div>

        @if(!$user->isAdministradorGeneral() || auth()->id() === $user->id)
        <button type="button" @click="confirmQuickReset()" 
                class="hidden sm:inline-flex items-center gap-1.5 px-3 py-2 bg-amber-50 hover:bg-amber-100 text-amber-800 border border-amber-200 rounded-xl text-xs font-bold transition shadow-sm">
            <i class="fa-solid fa-key"></i>
            <span>Restablecer Contraseña</span>
        </button>
        @endif
    </div>

    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm" role="alert">
            <strong class="font-bold flex items-center gap-2 mb-1">
                <i class="fa-solid fa-triangle-exclamation"></i> Revisa los siguientes errores:
            </strong>
            <ul class="list-disc pl-5 space-y-0.5 text-xs">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="soft-card p-6 sm:p-8 bg-white rounded-2xl border border-slate-200 shadow-sm">
        <form action="{{ route('admin.users.update', $user) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- 1. Información Personal -->
            <div>
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-user-circle text-blue-600"></i> Información de Contacto
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Nombre Completo <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <i class="fa-solid fa-user absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" required 
                                   class="w-full pl-9 pr-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition">
                        </div>
                        @error('name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Correo Electrónico <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <i class="fa-solid fa-envelope absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" required 
                                   class="w-full pl-9 pr-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition">
                        </div>
                        @error('email') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-slate-700 mb-1">Teléfono / WhatsApp <span class="text-slate-400 font-normal">(Opcional)</span></label>
                        <div class="relative max-w-md">
                            <i class="fa-solid fa-phone absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="+51 987 654 321" 
                                   class="w-full pl-9 pr-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition">
                        </div>
                        @error('phone') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <!-- 2. Rol y Estado -->
            <div class="pt-6 border-t border-slate-100">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-shield-halved text-blue-600"></i> Rol y Estado en el Sistema
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Rol Asignado <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <i class="fa-solid fa-id-badge absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                            <select name="role_id" required 
                                    class="w-full pl-9 pr-8 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition appearance-none">
                                @foreach($roles as $role)
                                    @php
                                        if ($role->name === 'Customer') continue;
                                        
                                        $isDisabled = false;
                                        $labelSuffix = '';
                                        // If someone else tries to take Administrador General
                                        if ($role->name === 'Administrador General' && !$user->isAdministradorGeneral()) {
                                            $adminGenExists = \App\Models\User::where('role_id', $role->id)->where('id', '!=', $user->id)->exists();
                                            if ($adminGenExists) {
                                                $isDisabled = true;
                                                $labelSuffix = ' (Ya existe 1 en el sistema)';
                                            }
                                        }
                                    @endphp
                                    <option value="{{ $role->id }}" 
                                            {{ (old('role_id', $user->role_id) == $role->id) ? 'selected' : '' }} 
                                            {{ $isDisabled ? 'disabled' : '' }}>
                                        {{ $role->name }}{{ $labelSuffix }}
                                    </option>
                                @endforeach
                            </select>
                            <i class="fa-solid fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 text-[10px] pointer-events-none"></i>
                        </div>
                        <p class="text-[11px] text-slate-500 mt-1.5 leading-relaxed">
                            <strong>Personal:</strong> Gestión de catálogo (productos, marcas, categorías, sliders).<br>
                            <strong>Administrador:</strong> Acceso completo operativo y equipo.<br>
                            <strong>Vendedor:</strong> Catálogo y pedidos WhatsApp.
                        </p>
                        @error('role_id') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Estado de la Cuenta <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <i class="fa-solid fa-circle-dot absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                            <select name="status" required 
                                    class="w-full pl-9 pr-8 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition appearance-none">
                                <option value="activo" {{ old('status', $user->status) === 'activo' ? 'selected' : '' }}>Activo (Permite acceso normal)</option>
                                <option value="suspendido" {{ old('status', $user->status) === 'suspendido' ? 'selected' : '' }}>Suspendido (Acceso pausado)</option>
                                <option value="bloqueado" {{ old('status', $user->status) === 'bloqueado' ? 'selected' : '' }}>Bloqueado (Acceso revocado)</option>
                            </select>
                            <i class="fa-solid fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 text-[10px] pointer-events-none"></i>
                        </div>
                        @error('status') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <!-- 3. Cambio de Contraseña -->
            <div class="pt-6 border-t border-slate-100">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 flex items-center gap-2">
                        <i class="fa-solid fa-key text-blue-600"></i> Cambiar Contraseña (Opcional)
                    </h3>
                    <button type="button" @click="generatePassword()" class="text-xs text-blue-600 hover:text-blue-800 font-bold flex items-center gap-1 hover:underline">
                        <i class="fa-solid fa-wand-magic-sparkles"></i> Generar Nueva Contraseña
                    </button>
                </div>
                <p class="text-xs text-slate-500 mb-4">Deja estos campos en blanco si deseas mantener la contraseña actual.</p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Nueva Contraseña</label>
                        <div class="relative">
                            <input :type="showPass ? 'text' : 'password'" name="password" x-model="password" 
                                   class="w-full pl-3 pr-10 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition">
                            <button type="button" @click="showPass = !showPass" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 text-xs">
                                <i :class="showPass ? 'fa-solid fa-eye-slash' : 'fa-solid fa-eye'"></i>
                            </button>
                        </div>
                        @error('password') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Confirmar Nueva Contraseña</label>
                        <div class="relative">
                            <input :type="showPass ? 'text' : 'password'" name="password_confirmation" x-model="passwordConfirmation" 
                                   class="w-full pl-3 pr-10 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition">
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <label class="inline-flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="must_change_password" value="1" {{ old('must_change_password', $user->must_change_password) ? 'checked' : '' }} 
                               class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                        <span class="text-xs text-slate-700 font-medium">Exigir cambio de contraseña en su próximo inicio de sesión</span>
                    </label>
                </div>
            </div>

            <!-- Botones -->
            <div class="pt-6 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="{{ route('admin.users.index') }}" class="px-5 py-2.5 border border-slate-200 rounded-xl text-slate-700 hover:bg-slate-50 text-xs font-bold transition">
                    Cancelar
                </a>
                <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold shadow-md shadow-blue-500/20 transition flex items-center gap-2">
                    <i class="fa-solid fa-floppy-disk"></i>
                    <span>Actualizar Usuario</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Hidden Quick Reset Form -->
<form id="quick-reset-form" action="{{ route('admin.users.resetPassword', $user) }}" method="POST" class="hidden">
    @csrf
</form>

<script>
function userEditForm() {
    return {
        password: '',
        passwordConfirmation: '',
        showPass: false,
        generatePassword() {
            const chars = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz23456789!@#$%';
            let pass = '';
            for (let i = 0; i < 10; i++) {
                pass += chars.charAt(Math.floor(Math.random() * chars.length));
            }
            this.password = pass;
            this.passwordConfirmation = pass;
            this.showPass = true;
        },
        confirmQuickReset() {
            Swal.fire({
                title: '¿Restablecer Contraseña?',
                text: 'Se generará una contraseña temporal aleatoria para este usuario.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#2563eb',
                cancelButtonColor: '#64748b',
                confirmButtonText: '<i class="fa-solid fa-key mr-1"></i> Sí, Restablecer',
                cancelButtonText: 'Cancelar',
                customClass: {
                    confirmButton: 'rounded-xl font-bold text-xs px-4 py-2.5',
                    cancelButton: 'rounded-xl font-bold text-xs px-4 py-2.5'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('quick-reset-form').submit();
                }
            });
        }
    };
}
</script>
@endsection
