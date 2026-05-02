<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Class Roster — {{ $section->display_name }}</title>
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body {
        font-family: Arial, Helvetica, sans-serif;
        font-size: 11px;
        color: #1a1a1a;
        background: #fff;
        padding: 0;
    }
    @page {
        size: {{ $orientation === 'landscape' ? 'A4 landscape' : 'A4 portrait' }};
        margin: 18mm 15mm;
    }
    @media print {
        body { padding: 0; }
        .no-print { display: none !important; }
        table { page-break-inside: auto; }
        tr { page-break-inside: avoid; }
    }

    /* ── Print button ── */
    .print-bar {
        position: fixed; top: 0; left: 0; right: 0; z-index: 100;
        background: #0d4c8f; color: white;
        display: flex; align-items: center; justify-content: space-between;
        padding: 10px 20px; gap: 12px;
    }
    .print-bar h4 { font-size: 13px; font-weight: 700; }
    .print-bar p  { font-size: 11px; opacity: .8; }
    .print-bar-actions { display: flex; gap: 8px; }
    .btn-print {
        background: #fff; color: #0d4c8f; border: none;
        padding: 7px 18px; border-radius: 8px;
        font-size: 12px; font-weight: 700; cursor: pointer;
    }
    .btn-close {
        background: rgba(255,255,255,0.15); color: #fff; border: none;
        padding: 7px 14px; border-radius: 8px;
        font-size: 12px; font-weight: 600; cursor: pointer;
    }
    .page-wrapper { padding-top: 56px; }

    /* ── Document ── */
    .doc { padding: 20px 30px 30px; max-width: 800px; margin: 0 auto; }

    /* ── Header ── */
    .school-header { display: table; width: 100%; border-bottom: 2.5px solid #0d4c8f; padding-bottom: 10px; margin-bottom: 14px; }
    .school-header-logo { display: table-cell; width: 70px; vertical-align: middle; }
    .school-header-logo img { width: 62px; height: 62px; object-fit: contain; }
    .school-header-info { display: table-cell; vertical-align: middle; padding-left: 12px; }
    .school-name { font-size: 14.5px; font-weight: 900; color: #0d4c8f; text-transform: uppercase; letter-spacing: 0.04em; line-height: 1.2; }
    .school-tagline { font-size: 8.5px; color: #0d4c8f; font-style: italic; margin-top: 1px; }
    .school-address { font-size: 8px; color: #555; margin-top: 3px; line-height: 1.5; }
    .school-contact { font-size: 8px; color: #555; }
    .school-header-right { display: table-cell; vertical-align: middle; text-align: right; width: 130px; }
    .doc-title-box { border: 1.5px solid #0d4c8f; border-radius: 5px; padding: 6px 10px; text-align: center; }
    .doc-title { font-size: 10px; font-weight: 900; color: #0d4c8f; text-transform: uppercase; letter-spacing: 0.06em; }
    .doc-subtitle { font-size: 8px; color: #555; margin-top: 1px; }
    .doc-sy { font-size: 9px; font-weight: bold; color: #0d4c8f; margin-top: 3px; }

    /* ── Section info table ── */
    .section-info {
        width: 100%; border-collapse: collapse; margin-bottom: 14px;
        background: #f0f5fc; border-radius: 6px; overflow: hidden;
        border: 1px solid #dce8f5;
    }
    .section-info td { padding: 5px 10px; font-size: 10.5px; }
    .section-info .lbl { color: #555; font-weight: 600; width: 110px; }
    .section-info .val { color: #1a1a1a; font-weight: 700; }

    /* ── Roster table ── */
    .roster-table { width: 100%; border-collapse: collapse; margin-top: 6px; }
    .roster-table thead tr { background: #0d4c8f; color: white; }
    .roster-table thead th { padding: 7px 9px; text-align: left; font-size: 10.5px; font-weight: 700; letter-spacing: .2px; }
    .roster-table thead th.center { text-align: center; }
    .roster-table tbody tr { border-bottom: 1px solid #e8eef6; }
    .roster-table tbody tr:nth-child(even) { background: #f7faff; }
    .roster-table tbody td { padding: 5.5px 9px; font-size: 10.5px; vertical-align: middle; }
    .roster-table tbody td.center { text-align: center; }
    .roster-table tbody td.num { color: #888; font-size: 10px; text-align: center; width: 30px; }
    .roster-table tbody td.id  { font-family: monospace; color: #444; font-size: 10px; }
    .roster-table tbody td.name { font-weight: 600; color: #1a1a1a; }

    /* ── Footer ── */
    .doc-footer {
        margin-top: 24px; border-top: 1px solid #e2e8f0; padding-top: 12px;
        display: flex; justify-content: space-between; align-items: flex-end;
    }
    .signature-block { font-size: 9.5px; color: #555; }
    .signature-block .sig-name { font-weight: 700; color: #1a1a1a; font-size: 11px; border-top: 1px solid #1a1a1a; padding-top: 2px; margin-top: 24px; min-width: 160px; }
    .sig-label { color: #888; font-size: 9px; }
    .meta { font-size: 9.5px; color: #888; text-align: right; line-height: 1.6; }
</style>
</head>
<body>

{{-- Print bar (no-print hides on actual print) --}}
<div class="print-bar no-print">
    <div>
        <h4>Class Roster — {{ $section->display_name }}</h4>
        <p>SY {{ request('school_year', '2026-2027') }} · {{ $students->count() }} student(s)</p>
    </div>
    <div class="print-bar-actions">
        <button class="btn-print" onclick="window.print()">🖨 Print / Save PDF</button>
        <button class="btn-close" onclick="window.close()">✕ Close</button>
    </div>
</div>

<div class="page-wrapper">
<div class="doc">

    {{-- School Header --}}
    <div class="school-header">
        @if($showLogo)
        <div class="school-header-logo">
            <img src="{{ asset('images/messiah-logo.png') }}" alt="MMSC Logo">
        </div>
        @endif
        <div class="school-header-info">
            <div class="school-name">My Messiah School of Cavite</div>
            <div class="school-tagline">Nurturing Excellence, Building Character</div>
            <div class="school-address">144 Compound, Brgy. Palenzuela I, #117, Dasmariñas, Cavite 4114</div>
            <div class="school-contact">Tel: (046) 123-4567 &nbsp;&bull;&nbsp; Email: registrar@mmsc.edu.ph &nbsp;&bull;&nbsp; mmsc.edu.ph</div>
        </div>
        <div class="school-header-right">
            <div class="doc-title-box">
                <div class="doc-title">Class Roster</div>
                <div class="doc-subtitle">{{ $section->display_name }}</div>
                <div class="doc-sy">SY {{ request('school_year', '2025-2026') }}</div>
            </div>
        </div>
    </div>  

    {{-- Section info --}}
    <table class="section-info">
        <tr>
            <td class="lbl">Grade Level</td>
            <td class="val">{{ $section->grade_level }}</td>
            <td class="lbl">Section</td>
            <td class="val">{{ $section->section_name }}</td>
        </tr>
        <tr>
            <td class="lbl">Homeroom Adviser</td>
            <td class="val">{{ $section->homeroom_adviser_name ?? 'TBA' }}</td>
            <td class="lbl">Room</td>
            <td class="val">{{ $section->room ?? '—' }}</td>
        </tr>
        <tr>
            <td class="lbl">Program Level</td>
            <td class="val">{{ $section->program_level ?? '—' }}</td>
            <td class="lbl">Total Students</td>
            <td class="val">{{ $students->count() }}</td>
        </tr>
        @if($section->track || $section->strand)
        <tr>
            <td class="lbl">Track</td>
            <td class="val">{{ $section->track ?? '—' }}</td>
            <td class="lbl">Strand</td>
            <td class="val">{{ $section->strand ?? '—' }}</td>
        </tr>
        @endif
    </table>

    {{-- Roster Table --}}
    <table class="roster-table">
        <thead>
            <tr>
                <th class="center">#</th>
                <th>Student ID</th>
                @if($showLrn)<th>LRN</th>@endif
                <th>Student Name</th>
                @if($showGender)<th class="center">Gender</th>@endif
                @if($showEmail)<th>Email</th>@endif
            </tr>
        </thead>
        <tbody>
            @forelse($students as $i => $s)
            <tr>
                <td class="num">{{ $i + 1 }}</td>
                <td class="id">{{ $s->student_id }}</td>
                @if($showLrn)<td class="id">{{ $s->lrn ?? '—' }}</td>@endif
                <td class="name">
                    {{ $s->last_name }}, {{ $s->first_name }}
                    @if($s->middle_name) {{ strtoupper(substr($s->middle_name,0,1)) }}. @endif
                    @if($s->suffix) {{ $s->suffix }} @endif
                </td>
                @if($showGender)
                <td class="center">{{ $s->gender ?? '—' }}</td>
                @endif
                @if($showEmail)
                <td>{{ $s->school_email ?? $s->personal_email ?? '—' }}</td>
                @endif
            </tr>
            @empty
            <tr><td colspan="6" style="text-align:center;padding:16px;color:#888;font-style:italic;">No students enrolled in this section.</td></tr>
            @endforelse
        </tbody>
    </table>

    {{-- Footer --}}
    <div class="doc-footer">
        <div class="signature-block">
            <div class="sig-label">Prepared by:</div>
            <div class="sig-name">{{ $section->homeroom_adviser_name ?? 'Class Adviser' }}</div>
            <div class="sig-label">Homeroom Adviser</div>
        </div>
        <div class="signature-block" style="text-align:center">
            <div class="sig-name" style="min-width:180px">&nbsp;</div>
            <div class="sig-label">School Principal / Registrar</div>
        </div>
        <div class="meta">
            Generated: {{ now()->format('F d, Y g:i A') }}<br>
            {{ $section->display_name }} · SY {{ request('school_year','2026-2027') }}
        </div>
    </div>

</div>
</div>

<script>
window.focus();
</script>
</body>
</html>
