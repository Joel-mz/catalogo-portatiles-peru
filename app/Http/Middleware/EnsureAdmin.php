<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        // 1. Check user status (Activo, Suspendido, Bloqueado)
        if ($user->isSuspended() || $user->isBlocked()) {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->withErrors([
                'email' => 'Tu cuenta se encuentra ' . ($user->isSuspended() ? 'suspendida' : 'bloqueada') . '. Contacta al Administrador General.',
            ]);
        }

        // 2. Allowed panel roles
        $panelRoles = [
            'Administrador General',
            'Administrador',
            'Vendedor',
            'Personal',
            'Soporte Técnico',
            'Admin',
        ];

        $roleName = $user->role?->name;

        if (!$roleName || !in_array($roleName, $panelRoles, true)) {
            abort(403, 'No tienes privilegios para acceder al panel administrativo.');
        }

        // 3. Mandatory password change check
        if ($user->must_change_password) {
            $allowedRoutes = [
                'profile.edit',
                'password.update',
                'logout',
            ];

            if (!$request->routeIs($allowedRoutes)) {
                return redirect()->route('profile.edit')
                    ->with('status', 'must-change-password');
            }
        }

        // 4. Mandatory 2FA for elevated privileges (Administrador General and Administrador)
        if ($user->requiresTwoFactor() && !$user->hasEnabledTwoFactorAuthentication()) {
            $allowed2faRoutes = [
                'profile.edit',
                'two-factor.enable',
                'two-factor.confirm',
                'two-factor.recovery-codes',
                'logout',
            ];

            if (!$request->routeIs($allowed2faRoutes)) {
                return redirect()->route('profile.edit')
                    ->with('status', 'admin-two-factor-required');
            }
        }

        // 5. Role-based restrictions inside Admin Panel
        if ($user->isVendedor()) {
            $vendedorAllowed = [
                'dashboard',
                'admin.orders.*',
                'admin.clients.*',
                'admin.products.index',
                'admin.products.show',
                'admin.products.searchByCode',
                'admin.products.qrcodes',
                'profile.*',
                'logout',
            ];

            if (!$request->routeIs($vendedorAllowed)) {
                abort(403, 'Acceso restringido para el rol Vendedor.');
            }
        } elseif ($user->isPersonal()) {
            $personalAllowed = [
                'dashboard',
                'admin.products.*',
                'admin.categories.*',
                'admin.subcategories.*',
                'admin.brands.*',
                'admin.models.*',
                'admin.sliders.*',
                'admin.banners.*',
                'admin.publicidad.*',
                'profile.*',
                'logout',
            ];

            if (!$request->routeIs($personalAllowed)) {
                abort(403, 'Acceso restringido para el rol Personal.');
            }
        } elseif ($roleName === 'Administrador') {
            // High-security restricted actions: only Administrador General can manage system security, settings and backups
            if ($request->routeIs(['admin.security.*', 'admin.settings.*', 'admin.backups.*'])) {
                abort(403, 'Solo el Administrador General tiene acceso a seguridad, configuraciones del sistema y copias de seguridad.');
            }
        }

        return $next($request);
    }
}
