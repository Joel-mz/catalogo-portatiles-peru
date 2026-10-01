<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Services\OtpSecurityService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PasswordController extends Controller
{
    public function __construct(
        protected OtpSecurityService $otpService
    ) {}

    /**
     * Update the user's password.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $user = $request->user();

        // If Administrador General, require OTP verification
        if ($user->isAdministradorGeneral()) {
            $action = 'profile_password';

            if (!session()->has('otp_verified_' . $action)) {
                $this->otpService->generateOtp($user, $action, ['password' => $validated['password']]);

                session()->put('pending_otp_action', [
                    'action' => $action,
                    'action_name' => 'Cambio de contraseña del Administrador General',
                    'description' => 'Actualización de las credenciales de acceso maestras del sistema.',
                    'url' => route('password.update'),
                    'method' => 'PUT',
                    'data' => ['password' => $validated['password']],
                    'redirect_back' => route('profile.edit'),
                ]);

                return redirect()->route('admin.security.otp.verify')
                    ->with('info', 'Por favor ingresa el código de 6 dígitos enviado a tu correo para autorizar el cambio de contraseña.');
            }

            session()->forget('otp_verified_' . $action);
        }

        $user->forceFill([
            'password' => $validated['password'], // User model casts password => hashed
            'must_change_password' => false,
        ])->save();

        AuditLog::create([
            'user_id' => $user->id,
            'action' => 'Cambio de contraseña',
            'status' => 'success',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        if ($user->isAdministradorGeneral()) {
            $this->otpService->notifyCriticalChangeCompleted(
                $user,
                'profile_password',
                'Tu contraseña de Administrador General ha sido cambiada exitosamente.'
            );
        }

        return back()->with('status', 'password-updated');
    }
}
