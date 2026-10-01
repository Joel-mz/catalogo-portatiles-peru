@extends('layouts.admin')

@section('header_title', 'Usuarios y Personal')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold font-display text-slate-800">Usuarios y Personal de Trabajo</h2>
            <p class="text-xs text-slate-500">Administra cuentas de acceso, roles (Administrador, Personal, Vendedor) y contraseñas.</p>
        </div>
        <a href="{{ route('admin.users.create') }}" class="px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold transition-all shadow-md shadow-blue-500/20 flex items-center justify-center gap-2">
            <i class="fa-solid fa-user-plus"></i>
            <span>Nuevo Usuario</span>
        </a>
    </div>

    <!-- Alert Success / Error -->
    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-2xl flex items-center gap-3 shadow-sm" role="alert">
            <i class="fa-solid fa-circle-check text-emerald-600 text-lg flex-shrink-0"></i>
            <span class="text-xs font-medium">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 rounded-2xl flex items-center gap-3 shadow-sm" role="alert">
            <i class="fa-solid fa-triangle-exclamation text-rose-600 text-lg flex-shrink-0"></i>
            <span class="text-xs font-medium">{{ session('error') }}</span>
        </div>
    @endif

    <!-- Alert Password Reset Success with Copy Feature -->
    @if(session('temp_password'))
        <div class="bg-gradient-to-r from-amber-50 to-blue-50 border border-amber-300 text-slate-800 p-4 rounded-2xl shadow-sm space-y-2">
            <div class="flex items-center gap-2 font-bold text-xs text-amber-900">
                <i class="fa-solid fa-key text-amber-600"></i>
                <span>Contraseña Restablecida Exitosamente para: {{ session('temp_user') }}</span>
            </div>
            <div class="flex flex-wrap items-center gap-3">
                <span class="text-xs text-slate-600">Nueva contraseña temporal:</span>
                <span id="temp-pass-val" class="px-3 py-1 bg-white font-mono font-bold text-blue-700 text-sm rounded-lg border border-slate-300 select-all tracking-wider shadow-inner">
                    {{ session('temp_password') }}
                </span>
                <button type="button" onclick="navigator.clipboard.writeText('{{ session('temp_password') }}'); Swal.fire({icon:'success', title:'Copiado', text:'Contraseña copiada al portapapeles', timer:1500, showConfirmButton:false});" 
                        class="px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-bold transition flex items-center gap-1">
                    <i class="fa-solid fa-copy"></i> Copiar Contraseña
                </button>
            </div>
            <p class="text-[11px] text-slate-500">Comparte esta contraseña con el usuario. Se le exigirá cambiarla al iniciar sesión.</p>
        </div>
    @endif

    <!-- Search & Filters -->
    <div class="soft-card p-4 bg-white rounded-2xl border border-slate-200 shadow-sm">
        <form action="{{ route('admin.users.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-center">
            
            <div class="sm:col-span-5 relative">
                <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Buscar por nombre, correo o teléfono..." 
                       class="w-full pl-9 pr-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition">
            </div>

            <div class="sm:col-span-3">
                <select name="role_id" onchange="this.form.submit()" 
                        class="w-full py-2 px-3 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-700 focus:bg-white focus:border-blue-500 outline-none transition">
                    <option value="">Todos los Roles</option>
                    @foreach($roles as $role)
                        @if($role->name !== 'Customer')
                            <option value="{{ $role->id }}" {{ request('role_id') == $role->id ? 'selected' : '' }}>
                                {{ $role->name }}
                            </option>
                        @endif
                    @endforeach
                </select>
            </div>

            <div class="sm:col-span-2">
                <select name="status" onchange="this.form.submit()" 
                        class="w-full py-2 px-3 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-700 focus:bg-white focus:border-blue-500 outline-none transition">
                    <option value="">Todos los Estados</option>
                    <option value="activo" {{ request('status') === 'activo' ? 'selected' : '' }}>Activo</option>
                    <option value="suspendido" {{ request('status') === 'suspendido' ? 'selected' : '' }}>Suspendido</option>
                    <option value="bloqueado" {{ request('status') === 'bloqueado' ? 'selected' : '' }}>Bloqueado</option>
                </select>
            </div>

            <div class="sm:col-span-2 flex gap-2">
                <button type="submit" class="flex-1 py-2 px-3 bg-slate-800 hover:bg-slate-900 text-white rounded-xl text-xs font-bold transition flex items-center justify-center gap-1.5">
                    <i class="fa-solid fa-filter"></i> Filtrar
                </button>
                @if(request()->hasAny(['search', 'role_id', 'status']))
                    <a href="{{ route('admin.users.index') }}" class="p-2 border border-slate-200 rounded-xl text-slate-500 hover:text-slate-800 hover:bg-slate-50 text-xs transition" title="Limpiar filtros">
                        <i class="fa-solid fa-xmark"></i>
                    </a>
                @endif
            </div>

        </form>
    </div>

    <!-- Table Card -->
    <div class="soft-card overflow-hidden bg-white rounded-2xl border border-slate-200 shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th scope="col" class="px-6 py-3.5 text-left text-[11px] font-extrabold text-slate-500 uppercase tracking-wider">Usuario / Contacto</th>
                        <th scope="col" class="px-6 py-3.5 text-left text-[11px] font-extrabold text-slate-500 uppercase tracking-wider">Correo Electrónico</th>
                        <th scope="col" class="px-6 py-3.5 text-left text-[11px] font-extrabold text-slate-500 uppercase tracking-wider">Rol de Trabajo</th>
                        <th scope="col" class="px-6 py-3.5 text-left text-[11px] font-extrabold text-slate-500 uppercase tracking-wider">Estado</th>
                        <th scope="col" class="px-6 py-3.5 text-right text-[11px] font-extrabold text-slate-500 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-100">
                    @forelse($users as $user)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        
                        <!-- Usuario -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-3">
                                <div class="flex-shrink-0 h-10 w-10 rounded-full flex items-center justify-center font-bold text-white shadow-sm text-sm"
                                     style="background: {{ $user->isAdministradorGeneral() ? 'linear-gradient(135deg, #7c3aed, #4f46e5)' : ($user->isAdministrador() ? 'linear-gradient(135deg, #2563eb, #38bdf8)' : 'linear-gradient(135deg, #059669, #10b981)') }};">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="text-xs font-bold text-slate-900 flex items-center gap-1.5">
                                        <span>{{ $user->name }}</span>
                                        @if(auth()->id() === $user->id)
                                            <span class="px-1.5 py-0.2 bg-blue-100 text-blue-700 font-extrabold text-[9px] rounded">Tú</span>
                                        @endif
                                    </div>
                                    <div class="text-[11px] text-slate-500 mt-0.5 flex items-center gap-2">
                                        @if($user->phone)
                                            <span><i class="fa-solid fa-phone text-[9px] text-slate-400"></i> {{ $user->phone }}</span>
                                            <span>·</span>
                                        @endif
                                        <span>Reg: {{ $user->created_at->format('d/m/Y') }}</span>
                                    </div>
                                </div>
                            </div>
                        </td>

                        <!-- Correo -->
                        <td class="px-6 py-4 whitespace-nowrap text-xs text-slate-600 font-medium">
                            <div class="flex items-center gap-1.5">
                                <span>{{ $user->email }}</span>
                            </div>
                        </td>

                        <!-- Rol -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $roleName = $user->role?->name ?? 'Sin Rol';
                                $roleBadgeClass = match($roleName) {
                                    'Administrador General' => 'bg-purple-100 text-purple-800 border-purple-200',
                                    'Administrador', 'Admin' => 'bg-blue-100 text-blue-800 border-blue-200',
                                    'Personal' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                                    'Vendedor' => 'bg-amber-100 text-amber-800 border-amber-200',
                                    'Soporte Técnico' => 'bg-cyan-100 text-cyan-800 border-cyan-200',
                                    default => 'bg-slate-100 text-slate-800 border-slate-200'
                                };
                                $roleIcon = match($roleName) {
                                    'Administrador General' => 'fa-crown',
                                    'Administrador', 'Admin' => 'fa-shield-halved',
                                    'Personal' => 'fa-user-gear',
                                    'Vendedor' => 'fa-bag-shopping',
                                    'Soporte Técnico' => 'fa-screwdriver-wrench',
                                    default => 'fa-user'
                                };
                            @endphp
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-bold rounded-lg border {{ $roleBadgeClass }}">
                                <i class="fa-solid {{ $roleIcon }} text-[10px]"></i>
                                <span>{{ $roleName }}</span>
                            </span>
                        </td>

                        <!-- Estado -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($user->isSuspended())
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Suspendido
                                </span>
                            @elseif($user->isBlocked())
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Bloqueado
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> Activo
                                </span>
                            @endif
                        </td>

                        <!-- Acciones -->
                        <td class="px-6 py-4 whitespace-nowrap text-right text-xs font-medium space-x-1.5">
                            
                            <!-- Restablecer Contraseña -->
                            @if(!$user->isAdministradorGeneral() || auth()->id() === $user->id)
                            <button type="button" 
                                    onclick="promptResetPassword({{ $user->id }}, '{{ addslashes($user->name) }}', '{{ route('admin.users.resetPassword', $user) }}')"
                                    class="inline-flex items-center justify-center p-2 rounded-xl text-amber-700 hover:text-amber-900 bg-amber-50 hover:bg-amber-100 transition" 
                                    title="Restablecer Contraseña">
                                <i class="fa-solid fa-key"></i>
                            </button>
                            @endif

                            <!-- Editar -->
                            <a href="{{ route('admin.users.edit', $user) }}" 
                               class="inline-flex items-center justify-center p-2 rounded-xl text-blue-600 hover:text-blue-900 bg-blue-50 hover:bg-blue-100 transition" 
                               title="Editar Usuario">
                                <i class="fa-solid fa-pen"></i>
                            </a>
                            
                            <!-- Eliminar -->
                            @if(auth()->id() !== $user->id && !$user->isAdministradorGeneral())
                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline-block" onsubmit="return confirm('¿Seguro que deseas eliminar a {{ addslashes($user->name) }} permanentemente?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center justify-center p-2 rounded-xl text-rose-600 hover:text-rose-900 bg-rose-50 hover:bg-rose-100 transition" title="Eliminar Usuario">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                            @endif
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-slate-500">
                            <div class="flex flex-col items-center justify-center gap-2">
                                <i class="fa-solid fa-users text-3xl text-slate-300"></i>
                                <p class="text-sm font-semibold">No se encontraron usuarios</p>
                                <p class="text-xs text-slate-400">Intenta con otros términos de búsqueda o registra uno nuevo.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="px-6 py-4 border-t border-slate-100">
            {{ $users->links() }}
        </div>
    </div>
</div>

<script>
function promptResetPassword(userId, userName, url) {
    Swal.fire({
        title: 'Restablecer Contraseña',
        html: `
            <div class="text-left text-xs text-slate-600 space-y-3">
                <p>Estás a punto de restablecer la contraseña para: <strong class="text-slate-800 text-sm block mt-0.5">${userName}</strong></p>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Nueva Contraseña (Opcional):</label>
                    <input type="text" id="swal-new-password" placeholder="Dejar en blanco para generar una automática" 
                           class="w-full p-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:bg-white focus:border-blue-500 outline-none">
                    <span class="text-[10px] text-slate-400 mt-1 block">Si lo dejas vacío, el sistema generará una clave aleatoria segura.</span>
                </div>
            </div>
        `,
        showCancelButton: true,
        confirmButtonColor: '#2563eb',
        cancelButtonColor: '#64748b',
        confirmButtonText: '<i class="fa-solid fa-key mr-1"></i> Restablecer Ahora',
        cancelButtonText: 'Cancelar',
        customClass: {
            confirmButton: 'rounded-xl font-bold text-xs px-4 py-2.5',
            cancelButton: 'rounded-xl font-bold text-xs px-4 py-2.5'
        },
        preConfirm: () => {
            const pass = document.getElementById('swal-new-password').value;
            return { password: pass };
        }
    }).then(async (result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Restableciendo...',
                allowOutsideClick: false,
                didOpen: () => { Swal.showLoading(); }
            });

            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ password: result.value.password })
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Contraseña Restablecida!',
                        html: `
                            <div class="text-left text-xs space-y-3">
                                <p>La contraseña para <strong>${data.user_name}</strong> se ha actualizado con éxito.</p>
                                <div class="p-3 bg-slate-50 border border-slate-200 rounded-xl">
                                    <span class="text-[11px] text-slate-500 block mb-1">Nueva Contraseña Temporal:</span>
                                    <div class="flex items-center justify-between gap-2">
                                        <code class="font-mono text-base font-extrabold text-blue-700">${data.new_password}</code>
                                        <button type="button" onclick="navigator.clipboard.writeText('${data.new_password}'); this.innerHTML='<i class=\\\'fa-solid fa-check\\\'></i> Copiado'; setTimeout(()=>this.innerHTML='<i class=\\\'fa-solid fa-copy\\\'></i> Copiar', 2000);" 
                                                class="px-2.5 py-1 bg-blue-600 text-white rounded-lg text-xs font-bold transition flex items-center gap-1">
                                            <i class="fa-solid fa-copy"></i> Copiar
                                        </button>
                                    </div>
                                </div>
                                <p class="text-[11px] text-slate-500">El usuario deberá ingresar con esta clave y actualizarla.</p>
                            </div>
                        `,
                        confirmButtonColor: '#2563eb',
                        confirmButtonText: 'Entendido'
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message || 'No se pudo restablecer la contraseña.',
                        confirmButtonColor: '#2563eb'
                    });
                }
            } catch (err) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error de Red',
                    text: 'No se pudo comunicar con el servidor.',
                    confirmButtonColor: '#2563eb'
                });
            }
        }
    });
}
</script>
@endsection
