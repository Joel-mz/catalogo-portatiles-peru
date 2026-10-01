<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Role;
use App\Models\User;
use App\Services\OtpSecurityService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    public function __construct(
        protected OtpSecurityService $otpService
    ) {}

    public function index(Request $request)
    {
        $query = User::with('role');

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role_id')) {
            $query->where('role_id', $request->role_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $users = $query->latest()->paginate(15)->withQueryString();
        $roles = Role::all();

        return view('admin.users.index', compact('users', 'roles'));
    }

    public function create()
    {
        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'nullable|string|max:30',
            'role_id' => 'required|exists:roles,id',
            'status' => 'required|in:activo,suspendido,bloqueado',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'must_change_password' => 'nullable|boolean',
        ], [
            'role_id.required' => 'Debes seleccionar un rol para el usuario.',
            'status.required' => 'Debes seleccionar el estado inicial del usuario.',
        ]);

        $selectedRole = Role::find($validated['role_id']);
        $isAdminRole = in_array($selectedRole?->name, ['Administrador General', 'Administrador', 'Admin']);

        // Check uniqueness of Administrador General
        if ($selectedRole?->name === 'Administrador General') {
            $existingAdminGen = User::whereHas('role', fn($q) => $q->where('name', 'Administrador General'))->first();
            if ($existingAdminGen) {
                return back()->withInput()->withErrors([
                    'role_id' => 'Ya existe un Administrador General en el sistema. Solo puede haber uno.',
                ]);
            }
        }

        $currentUser = auth()->user();

        // If creating an Admin and current user is Administrador General, OTP is mandatory
        if ($isAdminRole && $currentUser->isAdministradorGeneral()) {
            $action = 'admin_create';

            if (!session()->has('otp_verified_' . $action)) {
                $payload = array_merge($validated, [
                    'must_change_password' => $request->boolean('must_change_password', true),
                ]);

                $this->otpService->generateOtp($currentUser, $action, $payload);

                session()->put('pending_otp_action', [
                    'action' => $action,
                    'action_name' => 'Creación de un nuevo administrador',
                    'description' => "Crear la cuenta administrativa para {$validated['name']} ({$validated['email']}).",
                    'url' => route('admin.users.store'),
                    'method' => 'POST',
                    'data' => $payload,
                    'redirect_back' => route('admin.users.create'),
                ]);

                return redirect()->route('admin.security.otp.verify')
                    ->with('info', 'Por favor ingresa el código de 6 dígitos enviado a tu correo para autorizar la creación del nuevo administrador.');
            }

            session()->forget('otp_verified_' . $action);
        }

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'status' => $validated['status'],
            'password' => $validated['password'], // User model hashed cast
            'must_change_password' => $request->boolean('must_change_password', true),
            'role_id' => $validated['role_id'],
        ]);

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => "Creación de usuario: {$user->name} ({$selectedRole?->name})",
            'model' => 'User',
            'model_id' => $user->id,
            'status' => 'success',
            'details' => [
                'email' => $user->email,
                'role' => $selectedRole?->name,
                'status' => $user->status,
                'must_change_password' => $user->must_change_password,
            ],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        if ($isAdminRole && $currentUser->isAdministradorGeneral()) {
            $this->otpService->notifyCriticalChangeCompleted(
                $currentUser,
                'admin_create',
                "Se creó la cuenta administrativa para {$user->name} ({$user->email})."
            );
        }

        return redirect()->route('admin.users.index')->with('success', 'Usuario creado exitosamente.');
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
            'phone' => 'nullable|string|max:30',
            'role_id' => 'required|exists:roles,id',
            'status' => 'required|in:activo,suspendido,bloqueado',
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'must_change_password' => 'nullable|boolean',
        ]);

        $selectedRole = Role::find($validated['role_id']);
        $currentUser = auth()->user();

        // Enforce uniqueness of Administrador General
        if ($selectedRole?->name === 'Administrador General' && !$user->isAdministradorGeneral()) {
            $existingAdminGen = User::whereHas('role', fn($q) => $q->where('name', 'Administrador General'))
                ->where('id', '!=', $user->id)
                ->first();

            if ($existingAdminGen) {
                return back()->withInput()->withErrors([
                    'role_id' => 'Ya existe un Administrador General en el sistema. No se puede asignar a otro usuario.',
                ]);
            }
        }

        // Check if modifying an admin or promoting to admin role
        $isTargetAdmin = $user->isAdministrador();
        $isBecomingAdmin = in_array($selectedRole?->name, ['Administrador General', 'Administrador', 'Admin']);
        $isChangingRole = ($user->role_id != $validated['role_id']);

        // OTP is only required if elevating to or altering an Administrator account
        if (($isTargetAdmin || $isBecomingAdmin) && $isChangingRole && $currentUser->isAdministradorGeneral() && $currentUser->id !== $user->id) {
            $action = 'admin_edit_role';

            if (!session()->has('otp_verified_' . $action)) {
                $payload = array_merge($validated, [
                    'target_user_id' => $user->id,
                    'must_change_password' => $request->boolean('must_change_password'),
                ]);

                $this->otpService->generateOtp($currentUser, $action, $payload);

                session()->put('pending_otp_action', [
                    'action' => $action,
                    'action_name' => 'Cambio de permisos o roles de administradores',
                    'description' => "Modificar datos/rol del usuario {$user->name} al rol {$selectedRole?->name}.",
                    'url' => route('admin.users.update', $user),
                    'method' => 'PUT',
                    'data' => $payload,
                    'redirect_back' => route('admin.users.edit', $user),
                ]);

                return redirect()->route('admin.security.otp.verify')
                    ->with('info', 'Por favor ingresa el código de 6 dígitos enviado a tu correo para autorizar el cambio de roles/permisos.');
            }

            session()->forget('otp_verified_' . $action);
        }

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->phone = $validated['phone'] ?? null;
        $user->status = $validated['status'];
        $user->role_id = $validated['role_id'];

        if (!empty($validated['password'])) {
            $user->password = $validated['password'];
            $user->must_change_password = $request->boolean('must_change_password');
        }

        $user->save();

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => "Actualización de usuario: {$user->name}",
            'model' => 'User',
            'model_id' => $user->id,
            'status' => 'success',
            'details' => [
                'email' => $user->email,
                'role' => $selectedRole?->name,
                'status' => $user->status,
                'password_changed' => !empty($validated['password']),
            ],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        if (($isTargetAdmin || $isBecomingAdmin) && $currentUser->isAdministradorGeneral() && $currentUser->id !== $user->id) {
            $this->otpService->notifyCriticalChangeCompleted(
                $currentUser,
                'admin_edit_role',
                "Se actualizaron los datos/rol del usuario: {$user->name}."
            );
        }

        return redirect()->route('admin.users.index')->with('success', 'Usuario actualizado exitosamente.');
    }

    public function resetPassword(Request $request, User $user)
    {
        $currentUser = auth()->user();

        if ($user->isAdministradorGeneral() && $currentUser && $currentUser->id !== $user->id) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => 'No puedes restablecer la contraseña del Administrador General.'], 403);
            }
            return back()->with('error', 'No puedes restablecer la contraseña del Administrador General.');
        }

        $request->validate([
            'password' => 'nullable|string|min:6|max:100',
        ]);

        $newPassword = $request->filled('password') ? $request->input('password') : 'Portatil#' . random_int(1000, 9999);
        $user->password = $newPassword;
        $user->must_change_password = $request->boolean('must_change_password', true);
        $user->save();

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => "Restablecimiento de contraseña para {$user->name} ({$user->email})",
            'model' => 'User',
            'model_id' => $user->id,
            'status' => 'success',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Contraseña restablecida exitosamente.',
                'user_name' => $user->name,
                'user_email' => $user->email,
                'new_password' => $newPassword,
            ]);
        }

        return back()
            ->with('success', "Contraseña restablecida exitosamente para {$user->name}.")
            ->with('temp_password', $newPassword)
            ->with('temp_user', $user->name);
    }

    public function destroy(Request $request, User $user)
    {
        if ($user->isAdministradorGeneral()) {
            return redirect()->route('admin.users.index')->with('error', 'No se puede eliminar al Administrador General del sistema.');
        }

        if (auth()->id() === $user->id) {
            return redirect()->route('admin.users.index')->with('error', 'No puedes eliminarte a ti mismo.');
        }

        $currentUser = auth()->user();

        // If deleting an admin and current user is Administrador General, require OTP
        if ($user->isAdministrador() && $currentUser->isAdministradorGeneral()) {
            $action = 'admin_delete';

            if (!session()->has('otp_verified_' . $action)) {
                $payload = ['target_user_id' => $user->id];

                $this->otpService->generateOtp($currentUser, $action, $payload);

                session()->put('pending_otp_action', [
                    'action' => $action,
                    'action_name' => 'Eliminación de administrador',
                    'description' => "Eliminar permanentemente al administrador {$user->name} ({$user->email}).",
                    'url' => route('admin.users.destroy', $user),
                    'method' => 'DELETE',
                    'data' => $payload,
                    'redirect_back' => route('admin.users.index'),
                ]);

                return redirect()->route('admin.security.otp.verify')
                    ->with('info', 'Por favor ingresa el código de 6 dígitos enviado a tu correo para autorizar la eliminación del administrador.');
            }

            session()->forget('otp_verified_' . $action);
        }

        $name = $user->name;
        $email = $user->email;
        $user->delete();

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => "Eliminación de usuario: {$name} ({$email})",
            'model' => 'User',
            'status' => 'success',
            'details' => ['email' => $email],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        if ($currentUser->isAdministradorGeneral()) {
            $this->otpService->notifyCriticalChangeCompleted(
                $currentUser,
                'admin_delete',
                "Se eliminó permanentemente la cuenta de {$name} ({$email})."
            );
        }

        return redirect()->route('admin.users.index')->with('success', 'Usuario eliminado exitosamente.');
    }
}
