@extends('layouts.admin')

@section('header_title', 'Crear Usuario')

@section('content')
<div class="max-w-3xl mx-auto space-y-6" x-data="userCreateForm()">
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.users.index') }}" class="text-slate-400 hover:text-slate-600 transition-colors">
            <i class="fa-solid fa-arrow-left text-xl"></i>
        </a>
        <div>
            <h2 class="text-2xl font-bold font-display text-slate-800">Añadir Nuevo Usuario / Personal</h2>
            <p class="text-xs text-slate-500">Registra un miembro del equipo y asígnale su rol y permisos de acceso.</p>
        </div>
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
        <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- 1. Datos Personales -->
            <div>
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-user-circle text-blue-600"></i> Información de Contacto
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Nombre Completo <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <i class="fa-solid fa-user absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                            <input type="text" name="name" value="{{ old('name') }}" placeholder="Ej. Juan Pérez" required 
                                   class="w-full pl-9 pr-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition">
                        </div>
                        @error('name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Correo Electrónico <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <i class="fa-solid fa-envelope absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                            <input type="email" name="email" value="{{ old('email') }}" placeholder="usuario@correo.com" required 
                                   class="w-full pl-9 pr-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition">
                        </div>
                        @error('email') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-slate-700 mb-1">Teléfono / WhatsApp <span class="text-slate-400 font-normal">(Opcional)</span></label>
                        <div class="relative max-w-md">
                            <i class="fa-solid fa-phone absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                            <input type="text" name="phone" value="{{ old('phone') }}" placeholder="+51 987 654 321" 
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
                                <option value="">-- Selecciona un Rol --</option>
                                @foreach($roles as $role)
                                    @php
                                        // Ignore Customer for staff panel
                                        if ($role->name === 'Customer') continue;
                                        
                                        $isDisabled = false;
                                        $labelSuffix = '';
                                        if ($role->name === 'Administrador General') {
                                            $adminGenExists = \App\Models\User::where('role_id', $role->id)->exists();
                                            if ($adminGenExists) {
                                                $isDisabled = true;
                                                $labelSuffix = ' (Ya existe 1 en el sistema)';
                                            }
                                        }
                                    @endphp
                                    <option value="{{ $role->id }}" 
                                            {{ (old('role_id') == $role->id || (old('role_id') === null && $role->name === 'Personal')) ? 'selected' : '' }} 
                                            {{ $isDisabled ? 'disabled' : '' }}>
                                        {{ $role->name }}{{ $labelSuffix }}
                                    </option>
                                @endforeach
                            </select>
                            <i class="fa-solid fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 text-[10px] pointer-events-none"></i>
                        </div>
                        <p class="text-[11px] text-slate-500 mt-1.5 leading-relaxed">
                            <strong>Personal:</strong> Acceso a gestión de catálogo (productos, marcas, categorías).<br>
                            <strong>Administrador:</strong> Acceso a catálogo, ventas, clientes y gestión de personal.<br>
                            <strong>Vendedor:</strong> Catálogo de consulta y seguimiento de pedidos WhatsApp.
                        </p>
                        @error('role_id') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Estado de la Cuenta <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <i class="fa-solid fa-circle-dot absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                            <select name="status" required 
                                    class="w-full pl-9 pr-8 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition appearance-none">
                                <option value="activo" {{ old('status', 'activo') === 'activo' ? 'selected' : '' }}>Activo (Permite el acceso normal)</option>
                                <option value="suspendido" {{ old('status') === 'suspendido' ? 'selected' : '' }}>Suspendido (Acceso pausado)</option>
                                <option value="bloqueado" {{ old('status') === 'bloqueado' ? 'selected' : '' }}>Bloqueado (Acceso revocado)</option>
                            </select>
                            <i class="fa-solid fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 text-[10px] pointer-events-none"></i>
                        </div>
                        @error('status') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <!-- 3. Contraseña -->
            <div class="pt-6 border-t border-slate-100">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 flex items-center gap-2">
                        <i class="fa-solid fa-key text-blue-600"></i> Credenciales de Acceso
                    </h3>
                    <button type="button" @click="generatePassword()" class="text-xs text-blue-600 hover:text-blue-800 font-bold flex items-center gap-1 hover:underline">
                        <i class="fa-solid fa-wand-magic-sparkles"></i> Generar Contraseña Segura
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Contraseña <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <input :type="showPass ? 'text' : 'password'" name="password" x-model="password" required 
                                   class="w-full pl-3 pr-10 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition">
                            <button type="button" @click="showPass = !showPass" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 text-xs">
                                <i :class="showPass ? 'fa-solid fa-eye-slash' : 'fa-solid fa-eye'"></i>
                            </button>
                        </div>
                        @error('password') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Confirmar Contraseña <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <input :type="showPass ? 'text' : 'password'" name="password_confirmation" x-model="passwordConfirmation" required 
                                   class="w-full pl-3 pr-10 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition">
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <label class="inline-flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="must_change_password" value="1" {{ old('must_change_password', true) ? 'checked' : '' }} 
                               class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                        <span class="text-xs text-slate-700 font-medium">Exigir cambio de contraseña en el primer inicio de sesión del usuario</span>
                    </label>
                </div>
            </div>

            <!-- Botones -->
            <div class="pt-6 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="{{ route('admin.users.index') }}" class="px-5 py-2.5 border border-slate-200 rounded-xl text-slate-700 hover:bg-slate-50 text-xs font-bold transition">
                    Cancelar
                </a>
                <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold shadow-md shadow-blue-500/20 transition flex items-center gap-2">
                    <i class="fa-solid fa-check"></i>
                    <span>Guardar Usuario</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function userCreateForm() {
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
        }
    };
}
</script>
@endsection
