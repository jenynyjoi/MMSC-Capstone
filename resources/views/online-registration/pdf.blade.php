<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: Arial, sans-serif; font-size: 11px; color: #222; padding: 20px; }
    .header { text-align: center; border-bottom: 2px solid #0d4c8f; padding-bottom: 12px; margin-bottom: 16px; }
    .header h1 { font-size: 16px; color: #0d4c8f; font-weight: bold; }
    .header h2 { font-size: 13px; color: #333; margin-top: 4px; }
    .header p { font-size: 10px; color: #666; margin-top: 2px; }
    .ref-box { background: #f0f4ff; border: 1px solid #b8c8f0; border-radius: 6px; padding: 10px 16px; margin-bottom: 16px; }
    .ref-box table { width: 100%; }
    .ref-box td { padding: 2px 4px; font-size: 11px; }
    .ref-box .label { color: #666; width: 140px; }
    .ref-box .value { font-weight: bold; color: #0d4c8f; }
    .section { margin-bottom: 14px; }
    .section-title { background: #0d4c8f; color: white; font-size: 10px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.05em; padding: 4px 10px; border-radius: 3px; margin-bottom: 8px; }
    .field-grid { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 8px; }
    .field-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; }
    .field { margin-bottom: 4px; }
    .field-label { font-size: 9px; color: #888; text-transform: uppercase; }
    .field-value { font-size: 11px; color: #222; border-bottom: 1px solid #ddd; padding-bottom: 2px; min-height: 16px; }
    .status-badge { display: inline-block; background: #fef3c7; color: #92400e; border: 1px solid #fde68a; border-radius: 20px; padding: 2px 10px; font-size: 10px; font-weight: bold; }
    .footer { margin-top: 20px; border-top: 1px solid #ddd; padding-top: 10px; font-size: 9px; color: #999; text-align: center; }
    .doc-row { display: flex; align-items: center; gap: 8px; margin-bottom: 4px; }
    .doc-check { width: 12px; height: 12px; border: 1px solid #999; border-radius: 2px; display: inline-block; background: {{ '' }}; text-align: center; font-size: 9px; line-height: 12px; }
</style>
</head>
<body>

<div class="header">
    <h1>MY MESSIAH SCHOOL OF CAVITE</h1>
    <h2>STUDENT APPLICATION FORM</h2>
    <p>Academic Year {{ $application->school_year }}</p>
</div>

<div class="ref-box">
    <table>
        <tr>
            <td class="label">Reference Number:</td>
            <td class="value">{{ $application->reference_number }}</td>
            <td class="label">Status:</td>
            <td><span class="status-badge">{{ strtoupper($application->application_status) }}</span></td>
        </tr>
        <tr>
            <td class="label">Date Submitted:</td>
            <td class="value">{{ $application->submitted_at?->format('F d, Y g:i A') }}</td>
            <td class="label">Applied Grade:</td>
            <td class="value">{{ $application->incoming_grade_level }}</td>
        </tr>
    </table>
</div>

{{-- Section 1 --}}
<div class="section">
    <div class="section-title">Grade Level & Program</div>
    <div class="field-grid">
        <div class="field"><div class="field-label">Level Applied</div><div class="field-value">{{ $application->applied_level }}</div></div>
        <div class="field"><div class="field-label">Grade Level</div><div class="field-value">{{ $application->incoming_grade_level }}</div></div>
        <div class="field"><div class="field-label">Student Status</div><div class="field-value">{{ $application->student_status }}</div></div>
        <div class="field"><div class="field-label">Student Category</div><div class="field-value">{{ $application->student_category }}</div></div>
        <div class="field"><div class="field-label">Transferee</div><div class="field-value">{{ $application->is_transferee ? 'Yes' : 'No' }}</div></div>
        @if($application->is_transferee)
        <div class="field"><div class="field-label">Previous School</div><div class="field-value">{{ $application->previous_school }}</div></div>
        @endif
    </div>
</div>

{{-- Section 2 --}}
<div class="section">
    <div class="section-title">Personal Information</div>
    <div class="field-grid">
        <div class="field"><div class="field-label">First Name</div><div class="field-value">{{ $application->first_name }}</div></div>
        <div class="field"><div class="field-label">Middle Name</div><div class="field-value">{{ $application->middle_name ?? '—' }}</div></div>
        <div class="field"><div class="field-label">Last Name</div><div class="field-value">{{ $application->last_name }}</div></div>
        <div class="field"><div class="field-label">Suffix</div><div class="field-value">{{ $application->suffix ?? '—' }}</div></div>
        <div class="field"><div class="field-label">Gender</div><div class="field-value">{{ $application->gender }}</div></div>
        <div class="field"><div class="field-label">Date of Birth</div><div class="field-value">{{ $application->date_of_birth?->format('F d, Y') }}</div></div>
        <div class="field"><div class="field-label">Nationality</div><div class="field-value">{{ $application->nationality ?? 'Filipino' }}</div></div>
        <div class="field"><div class="field-label">Religion</div><div class="field-value">{{ $application->religion ?? '—' }}</div></div>
        <div class="field"><div class="field-label">LRN</div><div class="field-value">{{ $application->lrn ?? '—' }}</div></div>
        <div class="field"><div class="field-label">Mobile Number</div><div class="field-value">{{ $application->mobile_number }}</div></div>
        <div class="field"><div class="field-label">Email</div><div class="field-value">{{ $application->personal_email }}</div></div>
        <div class="field"><div class="field-label">City</div><div class="field-value">{{ $application->city ?? '—' }}</div></div>
        <div class="field" style="grid-column: span 2"><div class="field-label">Home Address</div><div class="field-value">{{ $application->home_address }}</div></div>
        <div class="field"><div class="field-label">ZIP Code</div><div class="field-value">{{ $application->zip_code ?? '—' }}</div></div>
    </div>
</div>

{{-- Section 3 --}}
<div class="section">
    <div class="section-title">Parent / Guardian Information</div>
    <div class="field-grid">
        <div class="field"><div class="field-label">Father Name</div><div class="field-value">{{ $application->father_name ?? '—' }}</div></div>
        <div class="field"><div class="field-label">Father Contact</div><div class="field-value">{{ $application->father_contact ?? '—' }}</div></div>
        <div class="field"></div>
        <div class="field"><div class="field-label">Mother Maiden Name</div><div class="field-value">{{ $application->mother_maiden_name ?? '—' }}</div></div>
        <div class="field"><div class="field-label">Mother Contact</div><div class="field-value">{{ $application->mother_contact ?? '—' }}</div></div>
        <div class="field"></div>
        <div class="field"><div class="field-label">Guardian Name</div><div class="field-value">{{ $application->guardian_name }}</div></div>
        <div class="field"><div class="field-label">Relationship</div><div class="field-value">{{ $application->guardian_relationship }}</div></div>
        <div class="field"><div class="field-label">Guardian Contact</div><div class="field-value">{{ $application->guardian_contact }}</div></div>
        <div class="field"><div class="field-label">Guardian Email</div><div class="field-value">{{ $application->guardian_email ?? '—' }}</div></div>
        <div class="field"><div class="field-label">Emergency Contact</div><div class="field-value">{{ $application->emergency_contact_number }}</div></div>
        <div class="field" style="grid-column: span 2"><div class="field-label">Guardian Address</div><div class="field-value">{{ $application->guardian_address }}</div></div>
    </div>
</div>

{{-- Section 4 --}}
<div class="section">
    <div class="section-title">Documents</div>
    <div style="padding: 4px 0;">
        <div class="doc-row">
            <span class="doc-check">{{ $application->psa_uploaded ? '✓' : '' }}</span>
            <span>PSA Birth Certificate {{ $application->psa_uploaded ? '— ' . $application->psa_filename : '(Not uploaded)' }}</span>
        </div>
        <div class="doc-row">
            <span class="doc-check">{{ $application->report_card_uploaded ? '✓' : '' }}</span>
            <span>Report Card (Form 138) {{ $application->report_card_uploaded ? '— ' . $application->report_card_filename : '(Not uploaded)' }}</span>
        </div>
        <div class="doc-row">
            <span class="doc-check">{{ $application->good_moral_uploaded ? '✓' : '' }}</span>
            <span>Good Moral Certificate {{ $application->good_moral_uploaded ? '— ' . $application->good_moral_filename : '(Not uploaded)' }}</span>
        </div>
    </div>
</div>

{{-- Signature area --}}
<div style="margin-top: 24px; display: grid; grid-template-columns: 1fr 1fr; gap: 30px;">
    <div>
        <div style="border-bottom: 1px solid #333; margin-bottom: 4px; height: 30px;"></div>
        <p style="font-size: 10px; text-align: center; color: #666;">Applicant / Guardian Signature</p>
    </div>
    <div>
        <div style="border-bottom: 1px solid #333; margin-bottom: 4px; height: 30px;"></div>
        <p style="font-size: 10px; text-align: center; color: #666;">Date</p>
    </div>
</div>

<div class="footer">
    <p>My Messiah School of Cavite &bull; 144 Compound, Brgy. Palenzuela I, #117, Dasmariñas, Cavite &bull; (046) 123-4567</p>
    <p style="margin-top:2px;">This is a computer-generated document. Reference: {{ $application->reference_number }}</p>
</div>

</body>
</html>