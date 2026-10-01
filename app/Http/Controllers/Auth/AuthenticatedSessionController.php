<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\AuditLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $user = $request->user();

        // Check if user is suspended or blocked
        if ($user && ($user->isSuspended() || $user->isBlocked())) {
            $statusName = $user->isSuspended() ? 'suspendida' : 'bloqueada';
            AuditLog::create([
                'user_id' => $user->id,
                'action' => "Intento de acceso con cuenta {$statusName}",
                'status' => 'bloqueado',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->withErrors([
                'email' => "Tu cuenta se encuentra {$statusName}. Contacta al Administrador General.",
            ]);
        }

        // Record last login
        if ($user) {
            $user->forceFill([
                'last_login_at' => now(),
                'last_login_ip' => $request->ip(),
            ])->save();
        }

        if ($user && $user->hasEnabledTwoFactorAuthentication()) {
            $userId = $user->id;
            $remember = $request->boolean('remember');

            Auth::guard('web')->logout();

            $request->session()->put([
                'login.id' => $userId,
                'login.remember' => $remember,
            ]);

            return redirect()->route('two-factor.login');
        }

        // If not 2FA, log successful login
        AuditLog::create([
            'user_id' => $user->id,
            'action' => 'Inicio de sesión',
            'status' => 'success',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        $request->session()->regenerate();

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user) {
            AuditLog::create([
                'user_id' => $user->id,
                'action' => 'Cierre de sesión',
                'status' => 'success',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
        }

        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
