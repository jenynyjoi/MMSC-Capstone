<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<style>
* { margin:0; padding:0; box-sizing:border-box; }
body { font-family: Arial, sans-serif; font-size: 10.5px; color: #1a1a2e; background: #fff; }

/* ── Page layout ── */
@page { margin: 18mm 15mm 14mm 15mm; }

/* ── Header ── */
.school-header { display:table; width:100%; border-bottom: 2.5px solid #0d4c8f; padding-bottom: 10px; margin-bottom: 14px; }
.school-header-logo { display:table-cell; width:70px; vertical-align:middle; }
.school-header-logo img { width:62px; height:62px; object-fit:contain; }
.school-header-info { display:table-cell; vertical-align:middle; padding-left:12px; }
.school-name { font-size:14.5px; font-weight:900; color:#0d4c8f; text-transform:uppercase; letter-spacing:0.04em; line-height:1.2; }
.school-tagline { font-size:8.5px; color:#0d4c8f; font-style:italic; margin-top:1px; }
.school-address { font-size:8px; color:#555; margin-top:3px; line-height:1.5; }
.school-contact { font-size:8px; color:#555; }
.school-header-right { display:table-cell; vertical-align:middle; text-align:right; width:130px; }
.form-title-box { border:1.5px solid #0d4c8f; border-radius:5px; padding:6px 10px; text-align:center; }
.form-title { font-size:10px; font-weight:900; color:#0d4c8f; text-transform:uppercase; letter-spacing:0.06em; }
.form-subtitle { font-size:8px; color:#555; margin-top:1px; }
.form-sy { font-size:9px; font-weight:bold; color:#0d4c8f; margin-top:3px; }

/* ── Reference strip ── */
.ref-strip { background:#f0f5ff; border:1px solid #c5d5f8; border-radius:5px; padding:7px 12px; margin-bottom:13px; }
.ref-strip table { width:100%; border-collapse:collapse; }
.ref-strip td { padding:2px 6px 2px 0; font-size:10px; }
.ref-label { color:#666; font-size:9px; text-transform:uppercase; letter-spacing:0.04em; white-space:nowrap; width:110px; }
.ref-value { font-weight:bold; color:#0d4c8f; }
.status-badge { display:inline-block; background:#fef3c7; color:#92400e; border:1px solid #fcd34d; border-radius:20px; padding:1px 9px; font-size:9px; font-weight:bold; letter-spacing:0.04em; }
.status-badge.approved { background:#d1fae5; color:#065f46; border-color:#6ee7b7; }

/* ── Sections ── */
.section { margin-bottom:12px; }
.section-title {
    background: #0d4c8f;
    color: #fff;
    font-size: 8.5px;
    font-weight: bold;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    padding: 4px 10px;
    border-radius: 3px;
    margin-bottom: 7px;
    display: block;
}

/* ── Field grids ── */
.field-grid   { display:table; width:100%; border-collapse:separate; border-spacing:0 0; }
.field-row    { display:table-row; }
.field-cell   { display:table-cell; padding:0 6px 6px 0; vertical-align:top; }
.w33 { width:33.33%; }
.w50 { width:50%; }
.w25 { width:25%; }
.w66 { width:66.66%; }
.w100 { width:100%; }
.field-label  { font-size:8px; color:#888; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:1px; }
.field-value  { font-size:10.5px; color:#111; border-bottom:1px solid #cbd5e1; padding-bottom:2px; min-height:15px; }
.field-value.empty { color:#bbb; }

/* ── SHS box ── */
.shs-box { background:#eff6ff; border:1px solid #bfdbfe; border-radius:4px; padding:7px 10px; margin-top:5px; }
.shs-row { display:table; width:100%; }
.shs-cell { display:table-cell; padding-right:8px; }
.shs-label { font-size:8px; color:#1d4ed8; font-weight:bold; text-transform:uppercase; letter-spacing:0.04em; }
.shs-value { font-size:10.5px; color:#1e40af; font-weight:bold; }

/* ── Documents ── */
.doc-row { display:table; width:100%; margin-bottom:6px; }
.doc-check-cell { display:table-cell; width:20px; vertical-align:middle; }
.doc-box { width:13px; height:13px; border:1.5px solid #94a3b8; border-radius:2px; display:inline-block; }
.doc-box-filled { width:13px; height:13px; border:1.5px solid #16a34a; border-radius:2px; display:inline-block; background:#d1fae5; }
.doc-text { display:table-cell; vertical-align:middle; font-size:10.5px; }

/* ── Subsidy badge ── */
.subsidy-badge { display:inline-block; background:#e0f2fe; color:#0369a1; border:1px solid #bae6fd; border-radius:12px; padding:1px 8px; font-size:9px; font-weight:bold; margin-left:4px; }

/* ── Signature area ── */
.sig-table { display:table; width:100%; margin-top:18px; }
.sig-cell { display:table-cell; padding-right:20px; vertical-align:bottom; }
.sig-cell:last-child { padding-right:0; }
.sig-line { border-bottom:1px solid #334155; height:28px; }
.sig-caption { font-size:8.5px; color:#666; text-align:center; margin-top:3px; }

/* ── Footer ── */
.footer { margin-top:14px; border-top:1px solid #e2e8f0; padding-top:8px; }
.footer-inner { display:table; width:100%; }
.footer-left { display:table-cell; vertical-align:middle; }
.footer-right { display:table-cell; vertical-align:middle; text-align:right; }
.footer-text { font-size:8px; color:#94a3b8; }
.footer-ref { font-size:8px; color:#94a3b8; font-family:monospace; }

/* ── Watermark / official stamp area ── */
.official-box { border:1.5px dashed #0d4c8f; border-radius:6px; padding:10px 14px; text-align:center; width:120px; }
.official-label { font-size:8px; color:#0d4c8f; text-transform:uppercase; letter-spacing:0.06em; font-weight:bold; }
.official-sub { font-size:7.5px; color:#94a3b8; margin-top:2px; }
</style>
</head>
<body>

{{-- ══════════════════════════════════════════════
     SCHOOL HEADER
══════════════════════════════════════════════ --}}
<div class="school-header">
    <div class="school-header-logo">
        <img src="{{ public_path('images/messiah-logo.png') }}" alt="MMSC Logo">
    </div>
    <div class="school-header-info">
        <div class="school-name">My Messiah School of Cavite</div>
        <div class="school-tagline">Nurturing Excellence, Building Character</div>
        <div class="school-address">
            144 Compound, Brgy. Palenzuela I, #117, Dasmariñas, Cavite 4114<br>
        </div>
        <div class="school-contact">
            Tel: (046) 123-4567 &nbsp;&bull;&nbsp; Email: registrar@mmsc.edu.ph &nbsp;&bull;&nbsp; mmsc.edu.ph
        </div>
    </div>
    <div class="school-header-right">
        <div class="form-title-box">
            <div class="form-title">Application Form</div>
            <div class="form-subtitle">Online Admission</div>
            <div class="form-sy">S.Y. {{ $application->school_year }}</div>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════
     REFERENCE STRIP
══════════════════════════════════════════════ --}}
<div class="ref-strip">
    <table>
        <tr>
            <td class="ref-label">Reference No.</td>
            <td class="ref-value" style="font-size:12px;letter-spacing:0.06em;">{{ $application->reference_number }}</td>
            <td class="ref-label">Date Submitted</td>
            <td class="ref-value">{{ $application->submitted_at?->format('F d, Y  g:i A') ?? '—' }}</td>
        </tr>
        <tr>
            <td class="ref-label">Applied Level</td>
            <td class="ref-value">{{ $application->applied_level }}</td>
            <td class="ref-label">Grade Level</td>
            <td class="ref-value">{{ $application->incoming_grade_level }}</td>
        </tr>
    </table>
</div>

{{-- ══════════════════════════════════════════════
     SECTION 1 — ENROLLMENT INFORMATION
══════════════════════════════════════════════ --}}
<div class="section">
    <span class="section-title">&#9632;&nbsp; Enrollment Information</span>
    <div class="field-grid">
        <div class="field-row">
            <div class="field-cell w33">
                <div class="field-label">Applied Level</div>
                <div class="field-value">{{ $application->applied_level }}</div>
            </div>
            <div class="field-cell w33">
                <div class="field-label">Grade Level</div>
                <div class="field-value">{{ $application->incoming_grade_level }}</div>
            </div>
            <div class="field-cell w33">
                <div class="field-label">Student Status</div>
                <div class="field-value">{{ $application->student_status }}</div>
            </div>
        </div>
        <div class="field-row">
            <div class="field-cell w33">
                <div class="field-label">Student Category</div>
                <div class="field-value">
                    {{ $application->student_category }}
                    @if($application->student_category !== 'Regular Payee' && $application->subsidy_prev_school_type)
                        @php
                            $subsidyLabels = [
                                'public'             => 'Public Elem.',
                                'private'            => 'Private Elem.',
                                'public_jhs'         => 'Public JHS (FREE)',
                                'private_jhs_esc'    => 'Private JHS + ESC',
                                'private_jhs_no_esc' => 'Private JHS (No ESC)',
                            ];
                        @endphp
                        <span class="subsidy-badge">{{ $subsidyLabels[$application->subsidy_prev_school_type] ?? $application->subsidy_prev_school_type }}</span>
                    @endif
                </div>
            </div>
            <div class="field-cell w33">
                <div class="field-label">Transferee</div>
                <div class="field-value">{{ $application->is_transferee ? 'Yes' : 'No' }}</div>
            </div>
            <div class="field-cell w33">
                <div class="field-label">School Year</div>
                <div class="field-value">{{ $application->school_year }}</div>
            </div>
        </div>
        @if($application->is_transferee)
        <div class="field-row">
            <div class="field-cell w50">
                <div class="field-label">Previous School</div>
                <div class="field-value">{{ $application->previous_school ?? '—' }}</div>
            </div>
            <div class="field-cell w50">
                <div class="field-label">Previous School Address</div>
                <div class="field-value">{{ $application->previous_school_address ?? '—' }}</div>
            </div>
        </div>
        @endif
    </div>

    @if($application->applied_level === 'Senior High School')
    <div class="shs-box">
        <div class="shs-row">
            <div class="shs-cell">
                <div class="shs-label">Academic Track</div>
                <div class="shs-value">{{ $application->track ?? '—' }}</div>
            </div>
            <div class="shs-cell">
                <div class="shs-label">Strand / Pathway</div>
                <div class="shs-value">{{ $application->strand ?? $application->pathway ?? '—' }}</div>
            </div>
            <div class="shs-cell">
                <div class="shs-label">SHS Student Type</div>
                <div class="shs-value">{{ $application->shs_student_type ?? 'Regular' }}</div>
            </div>
        </div>
    </div>
    @endif
</div>

{{-- ══════════════════════════════════════════════
     SECTION 2 — PERSONAL INFORMATION
══════════════════════════════════════════════ --}}
<div class="section">
    <span class="section-title">&#9632;&nbsp; Personal Information</span>
    <div class="field-grid">
        <div class="field-row">
            <div class="field-cell w33">
                <div class="field-label">Last Name</div>
                <div class="field-value">{{ strtoupper($application->last_name) }}</div>
            </div>
            <div class="field-cell w33">
                <div class="field-label">First Name</div>
                <div class="field-value">{{ strtoupper($application->first_name) }}</div>
            </div>
            <div class="field-cell w33">
                <div class="field-label">Middle Name</div>
                <div class="field-value">{{ strtoupper($application->middle_name ?? '—') }}</div>
            </div>
        </div>
        <div class="field-row">
            <div class="field-cell w25">
                <div class="field-label">Suffix</div>
                <div class="field-value">{{ $application->suffix ?? '—' }}</div>
            </div>
            <div class="field-cell w25">
                <div class="field-label">Gender</div>
                <div class="field-value">{{ $application->gender }}</div>
            </div>
            <div class="field-cell w25">
                <div class="field-label">Date of Birth</div>
                <div class="field-value">{{ $application->date_of_birth?->format('M d, Y') }}</div>
            </div>
            <div class="field-cell w25">
                <div class="field-label">LRN</div>
                <div class="field-value">{{ $application->lrn ?? '—' }}</div>
            </div>
        </div>
        <div class="field-row">
            <div class="field-cell w33">
                <div class="field-label">Nationality</div>
                <div class="field-value">{{ $application->nationality ?? 'Filipino' }}</div>
            </div>
            <div class="field-cell w33">
                <div class="field-label">Mother Tongue</div>
                <div class="field-value">{{ $application->mother_tongue ?? '—' }}</div>
            </div>
            <div class="field-cell w33">
                <div class="field-label">Religion</div>
                <div class="field-value">{{ $application->religion ?? '—' }}</div>
            </div>
        </div>
        <div class="field-row">
            <div class="field-cell w33">
                <div class="field-label">Mobile Number</div>
                <div class="field-value">{{ $application->mobile_number }}</div>
            </div>
            <div class="field-cell w66">
                <div class="field-label">Personal Email</div>
                <div class="field-value">{{ $application->personal_email }}</div>
            </div>
        </div>
        <div class="field-row">
            <div class="field-cell w66">
                <div class="field-label">Home Address</div>
                <div class="field-value">{{ $application->home_address }}</div>
            </div>
            <div class="field-cell w25">
                <div class="field-label">City / Municipality</div>
                <div class="field-value">{{ $application->city ?? '—' }}</div>
            </div>
            <div class="field-cell" style="width:9%">
                <div class="field-label">ZIP</div>
                <div class="field-value">{{ $application->zip_code ?? '—' }}</div>
            </div>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════
     SECTION 3 — PARENT / GUARDIAN INFORMATION
══════════════════════════════════════════════ --}}
<div class="section">
    <span class="section-title">&#9632;&nbsp; Parent / Guardian Information</span>
    <div class="field-grid">
        <div class="field-row">
            <div class="field-cell w50">
                <div class="field-label">Father's Name</div>
                <div class="field-value">{{ strtoupper($application->father_name ?? '—') }}</div>
            </div>
            <div class="field-cell w50">
                <div class="field-label">Father's Contact</div>
                <div class="field-value">{{ $application->father_contact ?? '—' }}</div>
            </div>
        </div>
        <div class="field-row">
            <div class="field-cell w50">
                <div class="field-label">Mother's Maiden Name</div>
                <div class="field-value">{{ strtoupper($application->mother_maiden_name ?? '—') }}</div>
            </div>
            <div class="field-cell w50">
                <div class="field-label">Mother's Contact</div>
                <div class="field-value">{{ $application->mother_contact ?? '—' }}</div>
            </div>
        </div>
        <div class="field-row">
            <div class="field-cell w50">
                <div class="field-label">Guardian's Name</div>
                <div class="field-value">{{ strtoupper($application->guardian_name) }}</div>
            </div>
            <div class="field-cell w25">
                <div class="field-label">Relationship</div>
                <div class="field-value">{{ $application->guardian_relationship }}</div>
            </div>
            <div class="field-cell w25">
                <div class="field-label">Guardian Contact</div>
                <div class="field-value">{{ $application->guardian_contact }}</div>
            </div>
        </div>
        <div class="field-row">
            <div class="field-cell w50">
                <div class="field-label">Guardian Address</div>
                <div class="field-value">{{ $application->guardian_address ?? '—' }}</div>
            </div>
            <div class="field-cell w50">
                <div class="field-label">Guardian Email</div>
                <div class="field-value">{{ $application->guardian_email ?? '—' }}</div>
            </div>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════
     SECTION 4 — DOCUMENTS SUBMITTED
══════════════════════════════════════════════ --}}
<div class="section">
    <span class="section-title">&#9632;&nbsp; Documents Submitted</span>
    @foreach([
        ['PSA Birth Certificate',   $application->psa_uploaded],
        ['Report Card (Form 138)',  $application->report_card_uploaded],
        ['Good Moral Certificate',  $application->good_moral_uploaded],
        ['Medical Certificate',     $application->medical_uploaded ?? false],
    ] as [$docName, $uploaded])
    <div class="doc-row">
        <div class="doc-check-cell">
            @if($uploaded)
                <span class="doc-box-filled"></span>
            @else
                <span class="doc-box"></span>
            @endif
        </div>
        <div class="doc-text">{{ $docName }}</div>
    </div>
    @endforeach
</div>

{{-- ══════════════════════════════════════════════
     SIGNATURE + OFFICIAL USE
══════════════════════════════════════════════ --}}
<div class="sig-table">
    <div class="sig-cell w50">
        <div class="sig-line"></div>
        <div class="sig-caption">Applicant / Parent / Guardian Signature over Printed Name</div>
    </div>
    <div class="sig-cell w25">
        <div class="sig-line"></div>
        <div class="sig-caption">Date Signed</div>
    </div>
    <div class="sig-cell w25" style="text-align:right; vertical-align:middle; padding-right:0;">
        <div class="official-box" style="float:right;">
            <div class="official-label">For Official Use Only</div>
            <div style="height:32px;"></div>
            <div class="official-sub">Received by / Date Received</div>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════
     FOOTER
══════════════════════════════════════════════ --}}
<div class="footer">
    <div class="footer-inner">
        <div class="footer-left">
            <div class="footer-text">
                <strong>My Messiah School of Cavite</strong> &bull;
                144 Compound, Brgy. Palenzuela I, #117, Dasmariñas, Cavite 4114 &bull;
                (046) 123-4567 &bull; registrar@mmsc.edu.ph
            </div>
            <div class="footer-text" style="margin-top:2px;">This is a computer-generated document. No signature required for online submission.</div>
        </div>
        <div class="footer-right">
            <div class="footer-ref">Ref: {{ $application->reference_number }}</div>
            <div class="footer-text">Printed: {{ now()->format('M d, Y') }}</div>
        </div>
    </div>
</div>

</body>
</html>
