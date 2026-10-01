<?php

namespace App\Services;

use App\Models\AuditLog;
use App\Models\SecurityOtp;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class OtpSecurityService
{
    /**
     * Actions requiring OTP verification for Administrador General.
     */
    public const ACTIONS = [
        'profile_email' => 'Cambio de correo electrónico',
        'profile_password' => 'Cambio de contraseña de Administrador General',
        'profile_phone' => 'Cambio de número de teléfono',
        'profile_data' => 'Modificación de datos del perfil de Administrador General',
        '2fa_disable' => 'Desactivación de autenticación en dos factores (2FA)',
        '2fa_reset' => 'Cambio o reconfiguración del autenticador 2FA',
        'admin_create' => 'Creación de un nuevo administrador',
        'admin_edit_role' => 'Cambio de roles o permisos de administradores',
        'admin_delete' => 'Eliminación de administrador',
        'backup_restore' => 'Restauración de copia de seguridad',
        'settings_update' => 'Modificación de configuraciones generales del sistema',
    ];

    /**
     * Check if a given action requires OTP for the user.
     */
    public function requiresOtp(User $user, string $action): bool
    {
        return $user->isAdministradorGeneral();
    }

    /**
     * Human readable name for action.
     */
    public function getActionLabel(string $action): string
    {
        return self::ACTIONS[$action] ?? ucwords(str_replace('_', ' ', $action));
    }

    /**
     * Generate and dispatch a new 6-digit OTP code valid for 10 minutes.
     */
    public function generateOtp(User $user, string $action, array $payload = []): SecurityOtp
    {
        // Invalidate previous unexpired OTPs for this user & action
        SecurityOtp::where('user_id', $user->id)
            ->where('action', $action)
            ->whereNull('used_at')
            ->delete();

        $code = sprintf('%06d', random_int(100000, 999999));
        $expiresAt = now()->addMinutes(10);
        $ip = request()->ip();
        $userAgent = request()->userAgent();

        $otp = SecurityOtp::create([
            'user_id' => $user->id,
            'action' => $action,
            'code' => $code,
            'payload' => $payload,
            'attempts' => 0,
            'expires_at' => $expiresAt,
            'ip_address' => $ip,
            'user_agent' => $userAgent,
        ]);

        $this->logAudit(
            user: $user,
            action: 'Solicitud de OTP: ' . $this->getActionLabel($action),
            status: 'success',
            details: [
                'action_key' => $action,
                'expires_at' => $expiresAt->toDateTimeString(),
            ]
        );

        $this->sendOtpEmail($user, $otp);

        return $otp;
    }

    /**
     * Verify an OTP code.
     * Max 3 attempts, 10 minutes validity, single use.
     *
     * @return array{success: bool, message: string, payload?: array, otp?: SecurityOtp}
     */
    public function verifyOtp(User $user, string $action, string $code): array
    {
        $code = trim($code);

        $otp = SecurityOtp::where('user_id', $user->id)
            ->where('action', $action)
            ->whereNull('used_at')
            ->latest()
            ->first();

        if (!$otp) {
            $this->logAudit(
                user: $user,
                action: 'Intento OTP fallido: Código inexistente o ya usado para ' . $this->getActionLabel($action),
                status: 'fallo',
                details: ['action_key' => $action]
            );

            return [
                'success' => false,
                'message' => 'No hay un código de verificación activo para esta acción. Por favor solicite uno nuevo.',
            ];
        }

        if ($otp->isExpired()) {
            $this->logAudit(
                user: $user,
                action: 'Intento OTP fallido: Código expirado para ' . $this->getActionLabel($action),
                status: 'fallo',
                details: ['action_key' => $action, 'expired_at' => $otp->expires_at->toDateTimeString()]
            );

            $this->notifyFailedAttempt(
                $user,
                $action,
                'Intento de verificación con código OTP expirado.'
            );

            return [
                'success' => false,
                'message' => 'El código de verificación ha expirado (validez máxima de 10 minutos). La acción ha sido cancelada.',
            ];
        }

        if ($otp->attempts >= 3) {
            $this->logAudit(
                user: $user,
                action: 'Intento OTP bloqueado: Límite de intentos superado para ' . $this->getActionLabel($action),
                status: 'fallo',
                details: ['action_key' => $action, 'attempts' => $otp->attempts]
            );

            $this->notifyFailedAttempt(
                $user,
                $action,
                'Se superó el límite máximo de 3 intentos permitidos para el código OTP.'
            );

            return [
                'success' => false,
                'message' => 'Has superado el límite de intentos permitidos (3 intentos). La acción ha sido cancelada por seguridad.',
            ];
        }

        // Increment attempt
        $otp->increment('attempts');

        if ($otp->code !== $code) {
            $remaining = max(0, 3 - $otp->attempts);

            $this->logAudit(
                user: $user,
                action: 'Intento OTP fallido: Código incorrecto para ' . $this->getActionLabel($action),
                status: 'fallo',
                details: [
                    'action_key' => $action,
                    'attempt_number' => $otp->attempts,
                    'remaining' => $remaining,
                ]
            );

            if ($remaining === 0) {
                $this->notifyFailedAttempt(
                    $user,
                    $action,
                    'Se ingresó un código incorrecto en repetidas ocasiones alcanzando el límite.'
                );

                return [
                    'success' => false,
                    'message' => 'Código incorrecto. Has agotado los 3 intentos permitidos. La acción ha sido cancelada por seguridad.',
                ];
            }

            return [
                'success' => false,
                'message' => "Código incorrecto. Te quedan {$remaining} intento(s).",
            ];
        }

        // Valid OTP! Mark as used
        $otp->update(['used_at' => now()]);

        $this->logAudit(
            user: $user,
            action: 'Verificación OTP exitosa: ' . $this->getActionLabel($action),
            status: 'success',
            details: [
                'action_key' => $action,
                'attempts_used' => $otp->attempts,
            ]
        );

        return [
            'success' => true,
            'message' => 'Código verificado exitosamente.',
            'payload' => $otp->payload ?? [],
            'otp' => $otp,
        ];
    }

    /**
     * Send email with OTP code.
     */
    protected function sendOtpEmail(User $user, SecurityOtp $otp): void
    {
        $storeName = Setting::where('key', 'store_name')->value('value') ?? 'Catálogo Portátiles Perú';
        $actionName = $this->getActionLabel($otp->action);

        $data = [
            'user' => $user,
            'code' => $otp->code,
            'actionName' => $actionName,
            'expiresMinutes' => 10,
            'storeName' => $storeName,
            'ip' => $otp->ip_address,
            'userAgent' => $otp->user_agent,
            'date' => now()->format('d/m/Y H:i:s'),
        ];

        try {
            Mail::send('emails.security-otp', $data, function ($message) use ($user, $storeName, $actionName) {
                $message->to($user->email, $user->name)
                    ->subject("Código de Verificación [{$actionName}] - {$storeName}");
            });
        } catch (\Throwable $e) {
            Log::warning("No se pudo enviar el correo de OTP a {$user->email}: " . $e->getMessage());
        }
    }

    /**
     * Notify user that a critical change was completed.
     */
    public function notifyCriticalChangeCompleted(User $user, string $action, string $description = ''): void
    {
        $storeName = Setting::where('key', 'store_name')->value('value') ?? 'Catálogo Portátiles Perú';
        $actionName = $this->getActionLabel($action);

        $this->logAudit(
            user: $user,
            action: 'Cambio crítico completado: ' . $actionName,
            status: 'success',
            details: [
                'action_key' => $action,
                'description' => $description,
            ]
        );

        $data = [
            'user' => $user,
            'actionName' => $actionName,
            'description' => $description,
            'storeName' => $storeName,
            'ip' => request()->ip(),
            'userAgent' => request()->userAgent(),
            'date' => now()->format('d/m/Y H:i:s'),
        ];

        try {
            Mail::send('emails.critical-change', $data, function ($message) use ($user, $storeName, $actionName) {
                $message->to($user->email, $user->name)
                    ->subject("Confirmación de Seguridad: {$actionName} - {$storeName}");
            });
        } catch (\Throwable $e) {
            Log::warning("No se pudo enviar notificación de cambio crítico a {$user->email}: " . $e->getMessage());
        }
    }

    /**
     * Notify user that a failed attempt on sensitive data occurred.
     */
    public function notifyFailedAttempt(User $user, string $action, string $reason = ''): void
    {
        $storeName = Setting::where('key', 'store_name')->value('value') ?? 'Catálogo Portátiles Perú';
        $actionName = $this->getActionLabel($action);

        $data = [
            'user' => $user,
            'actionName' => $actionName,
            'reason' => $reason,
            'storeName' => $storeName,
            'ip' => request()->ip(),
            'userAgent' => request()->userAgent(),
            'date' => now()->format('d/m/Y H:i:s'),
        ];

        try {
            Mail::send('emails.security-alert', $data, function ($message) use ($user, $storeName, $actionName) {
                $message->to($user->email, $user->name)
                    ->subject("ALERTA DE SEGURIDAD: Intento fallido en {$actionName} - {$storeName}");
            });
        } catch (\Throwable $e) {
            Log::warning("No se pudo enviar alerta de intento fallido a {$user->email}: " . $e->getMessage());
        }
    }

    /**
     * Helper to write structured audit logs.
     */
    public function logAudit(?User $user, string $action, string $status = 'success', array $details = []): AuditLog
    {
        return AuditLog::create([
            'user_id' => $user?->id,
            'action' => $action,
            'model' => $details['model'] ?? null,
            'model_id' => $details['model_id'] ?? null,
            'details' => $details,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'status' => $status,
        ]);
    }
}
