<?php

namespace App\Http\Controllers;

use App\Services\TwoFactorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class TwoFactorAuthenticationController extends Controller
{
    public function __construct(
        protected TwoFactorService $twoFactorService
    ) {}

    /**
     * Start enabling 2FA: Generate secret & recovery codes.
     */
    public function enable(Request $request)
    {
        $user = $request->user();

        // Generate temporary secret if not already confirmed
        $secret = $this->twoFactorService->generateSecretKey();
        $recoveryCodes = $this->twoFactorService->generateRecoveryCodes();

        $request->session()->put('two_factor_pending', [
            'secret' => $secret,
            'recovery_codes' => $recoveryCodes,
        ]);

        $storeName = config('app.name', 'Portatiles Peru');
        $otpauthUrl = $this->twoFactorService->getOtpAuthUrl($storeName, $user->email, $secret);

        return response()->json([
            'secret' => $secret,
            'formatted_secret' => chunk_split($secret, 4, ' '),
            'otpauth_url' => $otpauthUrl,
            'qr_url' => 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=' . urlencode($otpauthUrl),
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
        $user->forceFill([
            'two_factor_secret' => encrypt($pending['secret']),
            'two_factor_recovery_codes' => $pending['recovery_codes'],
            'two_factor_confirmed_at' => now(),
        ])->save();

        $request->session()->forget('two_factor_pending');

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

        $user->forceFill([
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'two_factor_confirmed_at' => null,
        ])->save();

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

        return back()->with('status', 'recovery-codes-regenerated');
    }
}
