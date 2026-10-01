<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Services\OtpSecurityService;
use App\Services\TwoFactorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class TwoFactorAuthenticationController extends Controller
{
    public function __construct(
        protected TwoFactorService $twoFactorService,
        protected OtpSecurityService $otpService
    ) {}

    /**
     * Start enabling 2FA: Generate secret & recovery codes.
     */
    public function enable(Request $request)
    {
        $user = $request->user();

        // If user already has 2FA and is Administrador General, require OTP to change authenticator
        if ($user && $user->hasEnabledTwoFactorAuthentication() && $user->isAdministradorGeneral()) {
            $action = '2fa_reset';
            if (!session()->has('otp_verified_' . $action)) {
                $this->otpService->generateOtp($user, $action);

                session()->put('pending_otp_action', [
                    'action' => $action,
                    'action_name' => 'Cambio de autenticador (Google Authenticator u otro)',
                    'description' => 'Reconfigurar la aplicación autenticadora de la cuenta de Administrador General.',
                    'url' => route('profile.edit'),
                    'method' => 'GET',
                    'data' => [],
                    'redirect_back' => route('profile.edit'),
                ]);

                return response()->json([
                    'otp_required' => true,
                    'redirect' => route('admin.security.otp.verify'),
                    'message' => 'Por seguridad, debes verificar tu identidad con el código enviado a tu correo antes de reconfigurar el autenticador.',
                ], 403);
            }

            session()->forget('otp_verified_' . $action);
        }

        // Generate temporary secret if not already confirmed
        $secret = $this->twoFactorService->generateSecretKey();
        $recoveryCodes = $this->twoFactorService->generateRecoveryCodes();

        $request->session()->put('two_factor_pending', [
            'secret' => $secret,
            'recovery_codes' => $recoveryCodes,
        ]);

        return response()->json([
            'secret' => $secret,
            'formatted_secret' => chunk_split($secret, 4, ' '),
            'recovery_codes' => $recoveryCodes,
        ]);
    }

    /**
     * Confirm 2FA setup by validating the 6-digit TOTP code.
     */
    public function confirm(Request $request)
    {
        $request->validate([
            'code' => ['required', 'string', 'size:6'],
        ]);

        $pending = $request->session()->get('two_factor_pending');

        if (!$pending || empty($pending['secret'])) {
            throw ValidationException::withMessages([
                'code' => 'La sesión de configuración de 2FA ha expirado. Por favor inicia nuevamente.',
            ]);
        }

        $isValid = $this->twoFactorService->verifyKey($pending['secret'], $request->code);

        if (!$isValid) {
            throw ValidationException::withMessages([
                'code' => 'El código de 6 dígitos ingresado es incorrecto o ha expirado.',
            ]);
        }

        $user = $request->user();
        $isReconfiguring = !is_null($user->two_factor_confirmed_at);

        $user->forceFill([
            'two_factor_secret' => encrypt($pending['secret']),
            'two_factor_recovery_codes' => $pending['recovery_codes'],
            'two_factor_confirmed_at' => now(),
        ])->save();

        $request->session()->forget('two_factor_pending');

        AuditLog::create([
            'user_id' => $user->id,
            'action' => $isReconfiguring ? 'Reconfiguración de autenticador 2FA' : 'Activación de 2FA',
            'status' => 'success',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        if ($user->isAdministradorGeneral()) {
            $this->otpService->notifyCriticalChangeCompleted(
                $user,
                '2fa_reset',
                $isReconfiguring ? 'Se ha reconfigurado exitosamente la aplicación autenticadora 2FA.' : 'Se ha activado exitosamente el 2FA.'
            );
        }

        return response()->json([
            'success' => true,
            'message' => 'Autenticación de Dos Pasos activada exitosamente.',
            'recovery_codes' => $pending['recovery_codes'],
        ]);
    }

    /**
     * Disable 2FA for the authenticated user.
     */
    public function disable(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'string'],
        ]);

        $user = $request->user();

        if (!Hash::check($request->current_password, $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => 'La contraseña actual es incorrecta.',
            ]);
        }

        if ($user->isAdministradorGeneral()) {
            $action = '2fa_disable';
            if (!session()->has('otp_verified_' . $action)) {
                $this->otpService->generateOtp($user, $action);

                session()->put('pending_otp_action', [
                    'action' => $action,
                    'action_name' => 'Desactivación de autenticación en dos factores (2FA)',
                    'description' => 'Desactivar la protección 2FA de la cuenta de Administrador General.',
                    'url' => route('two-factor.disable'),
                    'method' => 'DELETE',
                    'data' => [],
                    'redirect_back' => route('profile.edit'),
                ]);

                return redirect()->route('admin.security.otp.verify')
                    ->with('info', 'Por favor ingresa el código de 6 dígitos enviado a tu correo para autorizar la desactivación del 2FA.');
            }

            session()->forget('otp_verified_' . $action);
        }

        $user->forceFill([
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'two_factor_confirmed_at' => null,
        ])->save();

        AuditLog::create([
            'user_id' => $user->id,
            'action' => 'Desactivación de autenticación en dos factores (2FA)',
            'status' => 'success',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        if ($user->isAdministradorGeneral()) {
            $this->otpService->notifyCriticalChangeCompleted(
                $user,
                '2fa_disable',
                'La autenticación en dos factores (2FA) ha sido desactivada en tu cuenta de Administrador General.'
            );
        }

        return back()->with('status', 'two-factor-authentication-disabled');
    }

    /**
     * Regenerate fresh recovery codes.
     */
    public function regenerateRecoveryCodes(Request $request)
    {
        $user = $request->user();

        if (!$user->hasEnabledTwoFactorAuthentication()) {
            return back();
        }

        $newCodes = $this->twoFactorService->generateRecoveryCodes();
        $user->forceFill([
            'two_factor_recovery_codes' => $newCodes,
        ])->save();

        AuditLog::create([
            'user_id' => $user->id,
            'action' => 'Regeneración de códigos de recuperación 2FA',
            'status' => 'success',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return back()->with('status', 'recovery-codes-regenerated');
    }
}
