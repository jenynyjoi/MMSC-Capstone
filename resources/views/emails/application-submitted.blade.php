<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width,initial-scale=1.0"/>
  <title>Application Received — {{ $application->reference_number }}</title>
</head>
<body style="margin:0;padding:0;background:#f1f5f9;font-family:'Segoe UI',Arial,sans-serif;">

<table width="100%" cellpadding="0" cellspacing="0" style="background:#f1f5f9;padding:40px 16px;">
  <tr>
    <td align="center">
      <table width="560" cellpadding="0" cellspacing="0" style="max-width:560px;width:100%;background:#ffffff;border-radius:16px;overflow:hidden;box-shadow:0 4px 24px rgba(0,0,0,0.08);">

        {{-- Header --}}
        <tr>
          <td style="background:linear-gradient(135deg,#0c2340 0%,#0d4c8f 60%,#0891b2 100%);padding:30px 36px;text-align:center;">
            <p style="margin:0 0 4px;font-size:10px;font-weight:700;letter-spacing:0.22em;text-transform:uppercase;color:#7dd3fc;">MY MESSIAH SCHOOL OF CAVITE</p>
            <h1 style="margin:0 0 4px;font-size:20px;font-weight:800;color:#ffffff;">Application Received!</h1>
            <p style="margin:0;font-size:12px;color:rgba(255,255,255,0.65);">Academic Year {{ $application->school_year }}</p>
          </td>
        </tr>

        {{-- Body --}}
        <tr>
          <td style="padding:32px 36px 0;">

            {{-- Greeting --}}
            <p style="margin:0 0 8px;font-size:15px;color:#1e293b;">
              Dear <strong>{{ $application->first_name }} {{ $application->last_name }}</strong>,
            </p>
            <p style="margin:0 0 24px;font-size:13.5px;color:#475569;line-height:1.7;">
              We're happy to confirm that your online admission application for
              <strong>My Messiah School of Cavite</strong> has been successfully received.
              Your application form is attached to this email as a PDF.
            </p>

            {{-- Reference box --}}
            <table width="100%" cellpadding="0" cellspacing="0" style="background:linear-gradient(to right,#0c2340,#0d4c8f);border-radius:12px;overflow:hidden;margin-bottom:24px;">
              <tr>
                <td style="padding:16px 22px;">
                  <p style="margin:0 0 4px;font-size:9px;font-weight:700;letter-spacing:0.18em;text-transform:uppercase;color:rgba(255,255,255,0.6);">Reference Number</p>
                  <p style="margin:0;font-size:26px;font-weight:900;color:#ffffff;letter-spacing:0.06em;font-family:'Courier New',monospace;">{{ $application->reference_number }}</p>
                </td>
              </tr>
            </table>

            {{-- Details grid --}}
            <table width="100%" cellpadding="0" cellspacing="0" style="border:1px solid #e2e8f0;border-radius:10px;overflow:hidden;margin-bottom:24px;">
              @php
                $rows = [
                  ['Applicant Name',   $application->first_name . ' ' . $application->last_name],
                  ['Applied Level',    $application->applied_level],
                  ['Grade Level',      $application->incoming_grade_level],
                  ['Student Category', $application->student_category],
                  ['Date Submitted',   $application->submitted_at->format('F d, Y · g:i A')],
                  ['Application Status', 'PENDING REVIEW'],
                ];
              @endphp
              @foreach($rows as $i => [$label, $value])
              <tr style="background:{{ $i % 2 === 0 ? '#f8fafc' : '#ffffff' }};">
                <td style="padding:10px 16px;font-size:11px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:0.08em;width:45%;border-bottom:{{ !$loop->last ? '1px solid #f1f5f9' : 'none' }};">
                  {{ $label }}
                </td>
                <td style="padding:10px 16px;font-size:13px;font-weight:600;color:{{ $label === 'Application Status' ? '#d97706' : '#0c2340' }};border-bottom:{{ !$loop->last ? '1px solid #f1f5f9' : 'none' }};">
                  @if($label === 'Application Status')
                    <span style="background:#fef3c7;color:#d97706;border:1px solid #fde68a;border-radius:20px;padding:2px 10px;font-size:11px;font-weight:800;">{{ $value }}</span>
                  @else
                    {{ $value }}
                  @endif
                </td>
              </tr>
              @endforeach
            </table>

            {{-- Attachment notice --}}
            <table width="100%" cellpadding="0" cellspacing="0" style="background:#f0f7ff;border:1px solid #bfdbfe;border-radius:10px;margin-bottom:24px;">
              <tr>
                <td style="padding:14px 18px;">
                  <p style="margin:0 0 4px;font-size:12px;font-weight:700;color:#1d4ed8;">📎 Application Form Attached</p>
                  <p style="margin:0;font-size:12px;color:#3730a3;line-height:1.6;">
                    A PDF copy of your completed application form is attached to this email.
                    Please save it for your records and bring a printed copy when you visit the Registrar's Office.
                  </p>
                </td>
              </tr>
            </table>

            {{-- What's next --}}
            <p style="margin:0 0 12px;font-size:12px;font-weight:700;color:#0d4c8f;text-transform:uppercase;letter-spacing:0.1em;">What Happens Next?</p>
            <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:24px;">
              @php
                $steps = [
                  ['Visit the Registrar\'s Office', 'Bring your printed application form and original documents (PSA Birth Certificate, Report Card, Good Moral Certificate). Mon–Fri, 7:30 AM – 5:00 PM.'],
                  ['Wait for Assessment', 'Our admissions team will review your application within 2–3 business days and notify you via email.'],
                  ['Check Your Email', 'You will receive updates about your application status, interview schedules, or additional requirements at this email address.'],
                ];
              @endphp
              @foreach($steps as $n => [$title, $desc])
              <tr>
                <td style="vertical-align:top;padding:0 0 12px 0;">
                  <table cellpadding="0" cellspacing="0" width="100%">
                    <tr>
                      <td style="vertical-align:top;padding-right:12px;width:30px;">
                        <div style="width:26px;height:26px;border-radius:50%;background:#0d4c8f;text-align:center;line-height:26px;font-size:11px;font-weight:800;color:#fff;">{{ $n + 1 }}</div>
                      </td>
                      <td>
                        <p style="margin:0 0 3px;font-size:13px;font-weight:700;color:#0c2340;">{{ $title }}</p>
                        <p style="margin:0;font-size:12px;color:#64748b;line-height:1.6;">{{ $desc }}</p>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
              @endforeach
            </table>

            {{-- Required documents reminder --}}
            <table width="100%" cellpadding="0" cellspacing="0" style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:10px;margin-bottom:28px;">
              <tr>
                <td style="padding:14px 18px;">
                  <p style="margin:0 0 10px;font-size:12px;font-weight:700;color:#0d4c8f;text-transform:uppercase;letter-spacing:0.08em;">Documents to Bring</p>
                  @foreach(['Printed copy of this confirmation email', 'PSA Birth Certificate (original)', 'Report Card / Form 138 (for transferees)', 'Good Moral Certificate', '2 pcs 2×2 ID picture'] as $doc)
                  <p style="margin:0 0 5px;font-size:12px;color:#475569;">
                    <span style="color:#0d4c8f;font-weight:700;margin-right:6px;">✓</span>{{ $doc }}
                  </p>
                  @endforeach
                </td>
              </tr>
            </table>

          </td>
        </tr>

        {{-- Footer --}}
        <tr>
          <td style="padding:20px 36px 28px;border-top:1px solid #f1f5f9;">
            <p style="margin:0 0 6px;font-size:12px;color:#64748b;line-height:1.7;">
              For questions or concerns, please contact us at
              <a href="mailto:registrar@mmsc.edu.ph" style="color:#0d4c8f;font-weight:600;text-decoration:none;">registrar@mmsc.edu.ph</a>
              or call <strong>(046) 123-4567</strong>.
            </p>
            <p style="margin:0;font-size:11px;color:#94a3b8;">
              This is an automated message. Please do not reply directly to this email.
            </p>
          </td>
        </tr>

        {{-- Brand footer --}}
        <tr>
          <td style="background:linear-gradient(135deg,#0c2340,#0d4c8f);padding:16px 36px;text-align:center;">
            <p style="margin:0 0 2px;font-size:11px;font-weight:700;color:#ffffff;">My Messiah School of Cavite</p>
            <p style="margin:0;font-size:10px;color:rgba(255,255,255,0.55);">144 Compound, Brgy. Palenzuela I, Dasmariñas, Cavite</p>
          </td>
        </tr>

      </table>
    </td>
  </tr>
</table>

</body>
</html>
