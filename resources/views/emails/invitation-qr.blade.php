<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Your QR Attendance Invitation</title>
</head>
<body style="margin:0;padding:0;background:#f4f6f8;font-family:Arial,Helvetica,sans-serif;color:#212529;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#f4f6f8;padding:24px 12px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:520px;background:#ffffff;border-radius:12px;overflow:hidden;border:1px solid #e9ecef;">
                    <tr>
                        <td style="background:#1a1d21;padding:24px 28px;text-align:center;">
                            <div style="font-size:20px;font-weight:700;color:#ffffff;letter-spacing:0.02em;">QR Attendance</div>
                            <div style="margin-top:6px;font-size:13px;color:#adb5bd;">Your event invitation</div>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:28px;">
                            <p style="margin:0 0 12px;font-size:16px;">Hi {{ $invitation->name }},</p>
                            <p style="margin:0 0 20px;font-size:14px;line-height:1.6;color:#495057;">
                                Your invitation request has been received. Show this QR code at the entrance so staff can mark your attendance.
                            </p>
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td align="center" style="padding:8px 0 20px;">
                                        <img src="{{ $message->embedData($qrPng, 'invitation-qr.png', 'image/png') }}" alt="Invitation QR Code" width="240" height="240" style="display:block;border:1px solid #dee2e6;border-radius:12px;padding:12px;background:#ffffff;">
                                    </td>
                                </tr>
                            </table>
                            <p style="margin:0 0 6px;font-size:12px;color:#6c757d;text-align:center;">Invitation ID</p>
                            <p style="margin:0 0 20px;font-size:13px;font-family:Consolas,Monaco,monospace;text-align:center;color:#212529;word-break:break-all;">
                                {{ $invitation->invite_id }}
                            </p>
                            @if ($invitation->expires_at)
                                <p style="margin:0;font-size:13px;color:#6c757d;text-align:center;">
                                    Valid until {{ $invitation->expires_at->format('d M Y, h:i A') }}
                                </p>
                            @endif
                            <p style="margin:20px 0 0;font-size:12px;line-height:1.6;color:#868e96;">
                                A PNG copy of this QR code is also attached to this email. Keep it until the event.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
