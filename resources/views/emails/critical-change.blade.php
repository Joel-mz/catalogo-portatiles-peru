<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmación de Cambio Crítico</title>
</head>
<body style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; background-color: #f4f6f9; margin: 0; padding: 30px 15px; color: #1e293b;">
    <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 580px; background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.06); border: 1px solid #e2e8f0;">
        <tr>
            <td style="background-color: #065f46; padding: 25px 30px; text-align: center;">
                <h1 style="color: #ffffff; font-size: 20px; margin: 0; font-weight: 700;">✅ Operación Completada</h1>
                <p style="color: #a7f3d0; font-size: 12px; margin: 5px 0 0 0; text-transform: uppercase; letter-spacing: 1px;">{{ $storeName }}</p>
            </td>
        </tr>
        <tr>
            <td style="padding: 30px 35px;">
                <p style="font-size: 15px; color: #334155; margin-top: 0;">Estimado/a <strong>{{ $user->name }}</strong>,</p>
                <p style="font-size: 14px; color: #475569; line-height: 1.6;">
                    Te confirmamos que se ha completado exitosamente la siguiente acción crítica en el sistema tras la debida verificación de seguridad:
                </p>

                <div style="background-color: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 8px; padding: 15px; margin: 20px 0;">
                    <div style="font-size: 14px; font-weight: 700; color: #166534; margin-bottom: 5px;">{{ $actionName }}</div>
                    @if(!empty($description))
                        <div style="font-size: 13px; color: #374151;">{{ $description }}</div>
                    @endif
                </div>

                <table style="width: 100%; border-top: 1px solid #f1f5f9; padding-top: 15px; font-size: 12px; color: #64748b;">
                    <tr>
                        <td style="padding: 4px 0;"><strong>Fecha y hora:</strong></td>
                        <td style="padding: 4px 0; text-align: right;">{{ $date }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 4px 0;"><strong>Dirección IP:</strong></td>
                        <td style="padding: 4px 0; text-align: right;">{{ $ip }}</td>
                    </tr>
                </table>

                <p style="font-size: 12px; color: #94a3b8; margin-top: 20px;">
                    Si no reconoces esta actividad, por favor comunícate de inmediato y cambia tu clave de acceso.
                </p>
            </td>
        </tr>
        <tr>
            <td style="background-color: #f8fafc; border-top: 1px solid #e2e8f0; padding: 15px 30px; text-align: center; font-size: 11px; color: #94a3b8;">
                Mensaje automático de seguridad de {{ $storeName }}.
            </td>
        </tr>
    </table>
</body>
</html>
