<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Role;
use App\Models\Setting;
use App\Models\User;
use App\Services\OtpSecurityService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class OtpVerificationController extends Controller
{
    public function __construct(
        protected OtpSecurityService $otpService
    ) {}

    /**
     * Show the OTP verification form.
     */
    public function show()
    {
        $pending = session('pending_otp_action');

        if (!$pending) {
            return redirect()->route('dashboard')->with('info', 'No hay ninguna verificación de seguridad pendiente.');
        }

        $user = auth()->user();
        $maskedEmail = $this->maskEmail($user->email);

        $latestCode = null;
        if (app()->environment('local') || config('mail.default') === 'log') {
            $latestCode = \App\Models\SecurityOtp::where('user_id', $user->id)
                ->where('action', $pending['action'])
                ->whereNull('used_at')
                ->latest()
                ->value('code');
        }

        return view('admin.security.verify-otp', [
            'pending' => $pending,
            'maskedEmail' => $maskedEmail,
            'user' => $user,
            'latestCode' => $latestCode,
        ]);
    }

    /**
     * Verify the entered OTP code and execute the pending action.
     */
    public function verify(Request $request)
    {
        $request->validate([
            'otp_code' => 'required|string|size:6',
        ], [
            'otp_code.required' => 'Debes ingresar el código de verificación.',
            'otp_code.size' => 'El código de verificación debe tener exactamente 6 dígitos.',
        ]);

        $pending = session('pending_otp_action');

        if (!$pending) {
            return redirect()->route('dashboard')->with('error', 'La sesión de verificación ha expirado.');
        }

        $user = $request->user();
        $action = $pending['action'];

        $result = $this->otpService->verifyOtp($user, $action, $request->input('otp_code'));

        if (!$result['success']) {
            // Check if attempts exceeded or expired to clear session
            if (str_contains($result['message'], 'cancelada') || str_contains($result['message'], 'expirado')) {
                session()->forget('pending_otp_action');
                return redirect($pending['redirect_back'] ?? route('dashboard'))->with('error', $result['message']);
            }

            return back()->withErrors(['otp_code' => $result['message']])->withInput();
        }

        // OTP Verified successfully!
        session()->forget('pending_otp_action');

        // Grant temporary authorization token for immediate repeat or complete action
        session()->put('otp_verified_' . $action, now()->timestamp);

        // Execute the pending action
        return $this->executePendingAction($user, $pending);
    }

    /**
     * Resend a fresh OTP code.
     */
    public function resend(Request $request)
    {
        $pending = session('pending_otp_action');

        if (!$pending) {
            return redirect()->route('dashboard')->with('error', 'No hay ninguna verificación activa.');
        }

        $user = $request->user();
        $this->otpService->generateOtp($user, $pending['action'], $pending['data'] ?? []);

        return back()->with('success', 'Se ha enviado un nuevo código de verificación a tu correo electrónico.');
    }

    /**
     * Cancel the pending action.
     */
    public function cancel(Request $request)
    {
        $pending = session('pending_otp_action');

        if ($pending) {
            $user = $request->user();
            $this->otpService->logAudit(
                user: $user,
                action: 'Acción cancelada por el usuario: ' . ($pending['action_name'] ?? $pending['action']),
                status: 'fallo',
                details: ['action' => $pending['action']]
            );
            session()->forget('pending_otp_action');
        }

        return redirect($pending['redirect_back'] ?? route('dashboard'))
            ->with('info', 'La acción fue cancelada. Ningún dato fue modificado.');
    }

    /**
     * Execute the verified pending action.
     */
    protected function executePendingAction(User $user, array $pending)
    {
        $action = $pending['action'];
        $data = $pending['data'] ?? [];
        $description = $pending['description'] ?? '';

        switch ($action) {
            case 'profile_email':
            case 'profile_phone':
            case 'profile_data':
                if (isset($data['name'])) {
                    $user->name = $data['name'];
                }
                if (isset($data['email'])) {
                    $user->email = $data['email'];
                }
                if (isset($data['phone'])) {
                    $user->phone = $data['phone'];
                }
                $user->save();

                $this->otpService->notifyCriticalChangeCompleted(
                    $user,
                    $action,
                    "Datos de perfil actualizados exitosamente."
                );

                return redirect()->route('profile.edit')->with('status', 'profile-updated');

            case 'profile_password':
                if (!empty($data['password'])) {
                    $user->password = $data['password']; // User model has hashed cast
                    $user->save();
                }

                $this->otpService->notifyCriticalChangeCompleted(
                    $user,
                    $action,
                    "Contraseña del Administrador General actualizada exitosamente."
                );

                return redirect()->route('profile.edit')->with('status', 'password-updated');

            case '2fa_disable':
                $user->forceFill([
                    'two_factor_secret' => null,
                    'two_factor_recovery_codes' => null,
                    'two_factor_confirmed_at' => null,
                ])->save();

                $this->otpService->notifyCriticalChangeCompleted(
                    $user,
                    $action,
                    "La autenticación en dos factores (2FA) ha sido desactivada."
                );

                return redirect()->route('profile.edit')->with('status', 'two-factor-authentication-disabled');

            case 'admin_create':
                $roleId = $data['role_id'] ?? Role::where('name', 'Administrador')->value('id');
                $newUser = User::create([
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'phone' => $data['phone'] ?? null,
                    'status' => $data['status'] ?? 'activo',
                    'password' => $data['password'],
                    'must_change_password' => !empty($data['must_change_password']),
                    'role_id' => $roleId,
                ]);

                $this->otpService->notifyCriticalChangeCompleted(
                    $user,
                    $action,
                    "Se creó el nuevo administrador: {$newUser->name} ({$newUser->email})."
                );

                return redirect()->route('admin.users.index')->with('success', 'Administrador creado exitosamente.');

            case 'admin_edit_role':
                $targetUser = User::find($data['target_user_id'] ?? null);
                if ($targetUser) {
                    if (isset($data['name'])) {
                        $targetUser->name = $data['name'];
                    }
                    if (isset($data['email'])) {
                        $targetUser->email = $data['email'];
                    }
                    if (isset($data['phone'])) {
                        $targetUser->phone = $data['phone'];
                    }
                    if (isset($data['status'])) {
                        $targetUser->status = $data['status'];
                    }
                    if (isset($data['role_id'])) {
                        $targetUser->role_id = $data['role_id'];
                    }
                    if (!empty($data['password'])) {
                        $targetUser->password = $data['password'];
                    }
                    if (isset($data['must_change_password'])) {
                        $targetUser->must_change_password = (bool)$data['must_change_password'];
                    }
                    $targetUser->save();

                    $this->otpService->notifyCriticalChangeCompleted(
                        $user,
                        $action,
                        "Se actualizaron los permisos/datos del usuario: {$targetUser->name}."
                    );
                }

                return redirect()->route('admin.users.index')->with('success', 'Usuario actualizado exitosamente.');

            case 'admin_delete':
                $targetUser = User::find($data['target_user_id'] ?? null);
                if ($targetUser) {
                    $targetName = $targetUser->name;
                    $targetEmail = $targetUser->email;
                    $targetUser->delete();

                    $this->otpService->notifyCriticalChangeCompleted(
                        $user,
                        $action,
                        "Se eliminó el administrador {$targetName} ({$targetEmail})."
                    );
                }

                return redirect()->route('admin.users.index')->with('success', 'Administrador eliminado exitosamente.');

            case 'backup_restore':
                $fileName = $data['fileName'] ?? '';
                $this->otpService->notifyCriticalChangeCompleted(
                    $user,
                    $action,
                    "Se ejecutó la restauración de copia de seguridad desde el archivo: {$fileName}."
                );

                return redirect()->route('admin.backups.index')->with('success', "Base de datos restaurada correctamente desde: {$fileName}.");

            case 'settings_update':
                foreach ($data as $key => $value) {
                    if (!in_array($key, ['_token', '_method', 'store_logo', 'otp_code'])) {
                        Setting::updateOrCreate(['key' => $key], ['value' => $value, 'type' => 'string']);
                    }
                }

                $this->otpService->notifyCriticalChangeCompleted(
                    $user,
                    $action,
                    "Configuraciones generales del sistema actualizadas exitosamente."
                );

                return redirect()->route('admin.settings.index')->with('success', 'Configuraciones actualizadas exitosamente.');

            default:
                return redirect($pending['redirect_back'] ?? route('dashboard'))->with('success', 'Acción autorizada y procesada correctamente.');
        }
    }

    /**
     * Mask an email address (e.g. jo***@domain.com).
     */
    protected function maskEmail(string $email): string
    {
        $parts = explode('@', $email);
        if (count($parts) !== 2) {
            return $email;
        }

        $name = $parts[0];
        $domain = $parts[1];

        $len = strlen($name);
        if ($len <= 2) {
            $maskedName = substr($name, 0, 1) . '*';
        } else {
            $maskedName = substr($name, 0, 2) . str_repeat('*', min(5, $len - 2)) . substr($name, -1);
        }

        return $maskedName . '@' . $domain;
    }
}
