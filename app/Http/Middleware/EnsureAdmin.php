<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
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

        if ($user?->role?->name !== 'Admin') {
            abort(403);
        }

        if (!$user->hasEnabledTwoFactorAuthentication()) {
            return redirect()->route('profile.edit')->with('status', 'admin-two-factor-required');
        }

        return $next($request);
    }
}
