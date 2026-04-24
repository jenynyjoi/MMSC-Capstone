<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Security Alert</title>
</head>
<body style="margin:0;padding:0;background:#f1f5f9;font-family:'Poppins',Arial,sans-serif;">
  <table width="100%" cellpadding="0" cellspacing="0" style="background:#f1f5f9;padding:40px 0;">
    <tr>
      <td align="center">
        <table width="520" cellpadding="0" cellspacing="0" style="background:#ffffff;border-radius:16px;overflow:hidden;box-shadow:0 4px 24px rgba(0,0,0,0.07);">

          {{-- Header --}}
          <tr>
            <td style="background:linear-gradient(135deg,#0d4c8f,#1e88e5);padding:32px 40px;text-align:center;">
              <p style="margin:0;font-size:22px;font-weight:700;color:#ffffff;letter-spacing:0.5px;">
                My Messiah School of Cavite
              </p>
              <p style="margin:6px 0 0;font-size:13px;color:rgba(255,255,255,0.75);">Security Notification</p>
            </td>
          </tr>

          {{-- Alert icon row --}}
          <tr>
            <td style="padding:32px 40px 0;text-align:center;">
              <div style="display:inline-block;width:64px;height:64px;background:#fff3cd;border-radius:50%;line-height:64px;font-size:32px;">
                ⚠️
              </div>
            </td>
          </tr>

          {{-- Body --}}
          <tr>
            <td style="padding:24px 40px 32px;">
              <h2 style="margin:0 0 12px;font-size:18px;font-weight:700;color:#1e293b;text-align:center;">
                Multiple Failed Login Attempts Detected
              </h2>
              <p style="margin:0 0 20px;font-size:14px;color:#475569;line-height:1.7;text-align:center;">
                Hi <strong>{{ $userName }}</strong>, we detected multiple failed login attempts on your MMSC account.
              </p>

              {{-- Detail box --}}
              <table width="100%" cellpadding="0" cellspacing="0" style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:10px;margin-bottom:24px;">
                <tr>
                  <td style="padding:16px 20px;">
                    <table width="100%">
                      <tr>
                        <td style="font-size:12px;color:#94a3b8;padding-bottom:6px;">Time of attempt</td>
                        <td style="font-size:13px;color:#1e293b;font-weight:600;text-align:right;padding-bottom:6px;">{{ $attemptTime }}</td>
                      </tr>
                      <tr>
                        <td style="font-size:12px;color:#94a3b8;">IP Address</td>
                        <td style="font-size:13px;color:#1e293b;font-weight:600;text-align:right;">{{ $ip }}</td>
                      </tr>
                    </table>
                  </td>
                </tr>
              </table>

              {{-- Warning message --}}
              <table width="100%" cellpadding="0" cellspacing="0" style="background:#fff3cd;border:1px solid #fbbf24;border-radius:10px;margin-bottom:24px;">
                <tr>
                  <td style="padding:14px 18px;font-size:13px;color:#92400e;line-height:1.6;">
                    <strong>If this wasn't you</strong>, please secure your account immediately by resetting your password. Your account may be locked after too many attempts.
                  </td>
                </tr>
              </table>

              <div style="text-align:center;">
                <a href="{{ route('password.request') }}"
                   style="display:inline-block;background:#0d4c8f;color:#ffffff;text-decoration:none;padding:12px 32px;border-radius:8px;font-size:14px;font-weight:600;letter-spacing:0.3px;">
                  Secure My Account
                </a>
              </div>
            </td>
          </tr>

          {{-- Footer --}}
          <tr>
            <td style="background:#f8fafc;padding:18px 40px;text-align:center;border-top:1px solid #e2e8f0;">
              <p style="margin:0;font-size:11px;color:#94a3b8;">
                This is an automated security alert from My Messiah School of Cavite.<br>
                If you have questions, contact the school administrator.
              </p>
            </td>
          </tr>

        </table>
      </td>
    </tr>
  </table>
</body>
</html>
