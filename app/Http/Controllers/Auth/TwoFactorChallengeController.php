<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\TwoFactorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class TwoFactorChallengeController extends Controller
{
    public function __construct(
        protected TwoFactorService $twoFactorService
    ) {}

    /**
     * Show the 2FA challenge login view.
     */
    public function create(Request $request)
    {
        if (!$request->session()->has('login.id')) {
            return redirect()->route('login');
        }

        return view('auth.two-factor-challenge');
    }

    /**
     * Authenticate using 2FA TOTP code or recovery code.
     */
    public function store(Request $request)
    {
        $userId = $request->session()->get('login.id');

        if (!$userId) {
            return redirect()->route('login');
        }

        /** @var User|null $user */
        $user = User::find($userId);

        if (!$user || !$user->hasEnabledTwoFactorAuthentication()) {
            return redirect()->route('login');
        }

        $secret = decrypt($user->two_factor_secret);

        // Option 1: Verification with 6-digit Code
        if ($code = $request->input('code')) {
            if ($this->twoFactorService->verifyKey($secret, $code)) {
                return $this->finishLogin($request, $user);
            }

            throw ValidationException::withMessages([
                'code' => 'El código de 6 dígitos ingresado es incorrecto o ha expirado.',
            ]);
        }

        // Option 2: Verification with Recovery Code
        if ($recoveryCode = $request->input('recovery_code')) {
            $codes = $user->two_factor_recovery_codes ?? [];
            $trimmed = trim($recoveryCode);

            if (($key = array_search($trimmed, $codes)) !== false) {
                // Invalidate used recovery code
                unset($codes[$key]);
                $user->forceFill([
                    'two_factor_recovery_codes' => array_values($codes),
                ])->save();

                return $this->finishLogin($request, $user);
            }

            throw ValidationException::withMessages([
                'recovery_code' => 'El código de recuperación ingresado no es válido.',
            ]);
        }

        throw ValidationException::withMessages([
            'code' => 'Por favor ingresa un código de autenticación o código de recuperación.',
        ]);
    }

    /**
     * Complete the login process.
     */
    protected function finishLogin(Request $request, User $user)
    {
        $remember = $request->session()->get('login.remember', false);
        $request->session()->forget(['login.id', 'login.remember']);

        Auth::login($user, $remember);
        $request->session()->regenerate();

        return redirect()->intended(route('dashboard', absolute: false));
    }
}
