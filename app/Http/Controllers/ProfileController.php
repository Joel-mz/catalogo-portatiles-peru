<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\AuditLog;
use App\Services\OtpSecurityService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function __construct(
        protected OtpSecurityService $otpService
    ) {}

    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $validated = $request->validated();

        if ($user->isAdministradorGeneral()) {
            $isEmailChange = ($validated['email'] !== $user->email);
            $isPhoneChange = (isset($validated['phone']) && $validated['phone'] !== $user->phone);
            $isNameChange = ($validated['name'] !== $user->name);

            if ($isEmailChange || $isPhoneChange || $isNameChange) {
                $action = $isEmailChange ? 'profile_email' : ($isPhoneChange ? 'profile_phone' : 'profile_data');
                $actionName = $this->otpService->getActionLabel($action);

                if (!session()->has('otp_verified_' . $action)) {
                    $this->otpService->generateOtp($user, $action, $validated);

                    session()->put('pending_otp_action', [
                        'action' => $action,
                        'action_name' => $actionName,
                        'description' => "Modificación de datos sensibles del Administrador General.",
                        'url' => route('profile.update'),
                        'method' => 'PATCH',
                        'data' => $validated,
                        'redirect_back' => route('profile.edit'),
                    ]);

                    return redirect()->route('admin.security.otp.verify')
                        ->with('info', "Por favor ingresa el código de 6 dígitos enviado a tu correo para autorizar: {$actionName}.");
                }

                session()->forget('otp_verified_' . $action);
            }
        }

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        AuditLog::create([
            'user_id' => $user->id,
            'action' => 'Actualización de datos del perfil',
            'status' => 'success',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        if ($user->isAdministradorGeneral()) {
            $this->otpService->notifyCriticalChangeCompleted(
                $user,
                'profile_data',
                'Datos de perfil del Administrador General actualizados exitosamente.'
            );
        }

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user->isAdministradorGeneral()) {
            return back()->with('error', 'El Administrador General no puede eliminar su propia cuenta principal.');
        }

        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
