<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width,initial-scale=1.0"/>
  <title>Password Reset OTP</title>
</head>
<body style="margin:0;padding:0;background:#f1f5f9;font-family:'Segoe UI',Arial,sans-serif;">

  <table width="100%" cellpadding="0" cellspacing="0" style="background:#f1f5f9;padding:40px 0;">
    <tr>
      <td align="center">
        <table width="520" cellpadding="0" cellspacing="0" style="background:#ffffff;border-radius:16px;overflow:hidden;box-shadow:0 4px 24px rgba(0,0,0,0.07);">

          {{-- Header --}}
          <tr>
            <td style="background:linear-gradient(135deg,#0c2340 0%,#0d4c8f 60%,#0891b2 100%);padding:28px 36px;text-align:center;">
              <p style="margin:0 0 4px;font-size:10px;font-weight:700;letter-spacing:0.2em;text-transform:uppercase;color:#7dd3fc;">MY MESSIAH SCHOOL OF CAVITE</p>
              <h1 style="margin:0;font-size:20px;font-weight:800;color:#ffffff;">Password Reset OTP</h1>
            </td>
          </tr>

          {{-- Body --}}
          <tr>
            <td style="padding:32px 36px;">
              @if($name)
              <p style="margin:0 0 16px;font-size:15px;color:#1e293b;">Hi, <strong>{{ $name }}</strong>!</p>
              @endif

              <p style="margin:0 0 20px;font-size:14px;color:#475569;line-height:1.7;">
                You requested to reset your password. Use the one-time code below.
                This code expires in <strong>10 minutes</strong>.
              </p>

              {{-- OTP Box --}}
              <div style="text-align:center;margin:28px 0;">
                <div style="display:inline-block;background:#f0f7ff;border:2px solid #bfdbfe;border-radius:12px;padding:20px 40px;">
                  <p style="margin:0 0 6px;font-size:11px;font-weight:700;letter-spacing:0.18em;text-transform:uppercase;color:#3b82f6;">ONE-TIME PASSWORD</p>
                  <p style="margin:0;font-size:40px;font-weight:900;letter-spacing:0.22em;color:#0d4c8f;font-family:'Courier New',monospace;">{{ $otp }}</p>
                </div>
              </div>

              <p style="margin:0 0 12px;font-size:13px;color:#64748b;line-height:1.6;">
                If you did not request a password reset, you can safely ignore this email.
                Your account remains secure.
              </p>

              <p style="margin:0;font-size:13px;color:#94a3b8;">
                <strong>Note:</strong> Never share this code with anyone. MMSC will never ask for your OTP.
              </p>
            </td>
          </tr>

          {{-- Footer --}}
          <tr>
            <td style="background:#f8fafc;border-top:1px solid #e2e8f0;padding:16px 36px;text-align:center;">
              <p style="margin:0;font-size:11px;color:#94a3b8;">
                © {{ date('Y') }} My Messiah School of Cavite · Cavite, Philippines
              </p>
            </td>
          </tr>

        </table>
      </td>
    </tr>
  </table>

</body>
</html>
