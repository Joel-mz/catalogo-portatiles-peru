<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alerta de Seguridad</title>
</head>
<body style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; background-color: #f4f6f9; margin: 0; padding: 30px 15px; color: #1e293b;">
    <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 580px; background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.06); border: 1px solid #fee2e2;">
        <tr>
            <td style="background-color: #991b1b; padding: 25px 30px; text-align: center;">
                <h1 style="color: #ffffff; font-size: 20px; margin: 0; font-weight: 700;">🚨 ALERTA DE SEGURIDAD</h1>
                <p style="color: #fecaca; font-size: 12px; margin: 5px 0 0 0; text-transform: uppercase; letter-spacing: 1px;">Intento de Modificación Bloqueado o Fallido</p>
            </td>
        </tr>
        <tr>
            <td style="padding: 30px 35px;">
                <p style="font-size: 15px; color: #334155; margin-top: 0;">Estimado/a <strong>{{ $user->name }}</strong>,</p>
                <p style="font-size: 14px; color: #475569; line-height: 1.6;">
                    Se detectó un intento no autorizado o fallido de modificar datos sensibles en tu cuenta de Administrador General:
                </p>

                <div style="background-color: #fef2f2; border-left: 4px solid #ef4444; padding: 14px 18px; border-radius: 4px; margin: 20px 0;">
                    <div style="font-size: 14px; font-weight: 700; color: #991b1b;">Operación: {{ $actionName }}</div>
                    @if(!empty($reason))
                        <div style="font-size: 13px; color: #b91c1c; margin-top: 4px;">Motivo: {{ $reason }}</div>
                    @endif
                </div>

                <p style="font-size: 13px; color: #475569; line-height: 1.6;">
                    La operación fue <strong>bloqueada y cancelada</strong>. Ningún dato sensible fue modificado.
                </p>

                <table style="width: 100%; border-top: 1px solid #f1f5f9; padding-top: 15px; font-size: 12px; color: #64748b;">
                    <tr>
                        <td style="padding: 4px 0;"><strong>Fecha y hora:</strong></td>
                        <td style="padding: 4px 0; text-align: right;">{{ $date }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 4px 0;"><strong>Dirección IP:</strong></td>
                        <td style="padding: 4px 0; text-align: right;">{{ $ip }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 4px 0;"><strong>Navegador:</strong></td>
                        <td style="padding: 4px 0; text-align: right;">{{ Str::limit($userAgent, 40) }}</td>
                    </tr>
                </table>

                <div style="background-color: #fffbeb; border: 1px solid #fde68a; border-radius: 8px; padding: 12px; margin-top: 20px; font-size: 12px; color: #92400e;">
                    🔒 <strong>Recomendación:</strong> Si tú no realizaste este intento, es posible que tus credenciales se hayan visto comprometidas. Cambia tu contraseña inmediatamente y revisa las sesiones activas en el panel de seguridad.
                </div>
            </td>
        </tr>
        <tr>
            <td style="background-color: #f8fafc; border-top: 1px solid #e2e8f0; padding: 15px 30px; text-align: center; font-size: 11px; color: #94a3b8;">
                Mensaje del sistema de seguridad de {{ $storeName }}.
            </td>
        </tr>
    </table>
</body>
</html>
