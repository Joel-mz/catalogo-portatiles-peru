<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Código de Verificación</title>
</head>
<body style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; background-color: #f4f6f9; margin: 0; padding: 30px 15px; color: #1e293b;">
    <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 580px; background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.06); border: 1px solid #e2e8f0;">
        <tr>
            <td style="background-color: #0f172a; padding: 25px 30px; text-align: center;">
                <h1 style="color: #ffffff; font-size: 20px; margin: 0; font-weight: 700; letter-spacing: 0.5px;">{{ $storeName }}</h1>
                <p style="color: #94a3b8; font-size: 12px; margin: 5px 0 0 0; text-transform: uppercase; letter-spacing: 1px;">Seguridad del Administrador General</p>
            </td>
        </tr>
        <tr>
            <td style="padding: 30px 35px;">
                <p style="font-size: 15px; color: #334155; margin-top: 0;">Estimado/a <strong>{{ $user->name }}</strong>,</p>
                <p style="font-size: 14px; color: #475569; line-height: 1.6;">
                    Has solicitado realizar una acción crítica en el sistema: <strong style="color: #0f172a;">{{ $actionName }}</strong>.
                </p>
                <p style="font-size: 14px; color: #475569; line-height: 1.6;">
                    Para autorizar esta operación, ingresa el siguiente código de verificación de un solo uso (OTP):
                </p>
                
                <div style="text-align: center; margin: 25px 0;">
                    <div style="display: inline-block; background-color: #eff6ff; border: 2px dashed #3b82f6; border-radius: 10px; padding: 16px 36px;">
                        <span style="font-family: monospace; font-size: 32px; font-weight: 800; letter-spacing: 8px; color: #1d4ed8;">{{ $code }}</span>
                    </div>
                    <p style="font-size: 12px; color: #64748b; margin: 10px 0 0 0;">
                        ⏱️ Este código vence en <strong>{{ $expiresMinutes }} minutos</strong> y solo puede ser utilizado una vez.
                    </p>
                </div>

                <div style="background-color: #fffbeb; border-left: 4px solid #f59e0b; padding: 12px 16px; border-radius: 4px; margin-bottom: 25px;">
                    <p style="margin: 0; font-size: 12px; color: #b45309; line-height: 1.5;">
                        <strong>⚠️ Importante:</strong> Tienes un máximo de 3 intentos para ingresar este código. Si no solicitaste este cambio, ignora este mensaje y cambia tu contraseña de inmediato.
                    </p>
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
            </td>
        </tr>
        <tr>
            <td style="background-color: #f8fafc; border-top: 1px solid #e2e8f0; padding: 15px 30px; text-align: center; font-size: 11px; color: #94a3b8;">
                Mensaje automático de seguridad. Por favor no respondas a este correo.
            </td>
        </tr>
    </table>
</body>
</html>
