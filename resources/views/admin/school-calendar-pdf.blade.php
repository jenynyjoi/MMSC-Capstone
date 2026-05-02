<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>School Calendar – SY {{ $schoolYear }}</title>
<style>
* { margin:0; padding:0; box-sizing:border-box; }

@page { margin: 22mm 18mm 18mm 18mm; }

body {
    font-family: Arial, sans-serif;
    font-size: 9pt;
    color: #1a1a2e;
    background: #fff;
}

/* ── Document header ── */
.doc-header { display: table; width: 100%; border-bottom: 2.5px solid #0d4c8f; padding-bottom: 10px; margin-bottom: 10px; border-collapse: collapse; }
.doc-header-logo { display: table-cell; width: 70px; vertical-align: middle; }
.doc-header-logo img { width: 62px; height: 62px; object-fit: contain; }
.doc-header-info { display: table-cell; vertical-align: middle; padding-left: 12px; }
.school-name  { font-size: 14.5px; font-weight: 900; color: #0d4c8f; text-transform: uppercase; letter-spacing: 0.04em; line-height: 1.2; }
.school-tagline { font-size: 8.5px; color: #0d4c8f; font-style: italic; margin-top: 1px; }
.school-address { font-size: 8px; color: #555; margin-top: 3px; line-height: 1.5; }
.school-contact { font-size: 8px; color: #555; }
.doc-header-right { display: table-cell; vertical-align: middle; text-align: right; width: 130px; }
.doc-title-box { border: 1.5px solid #0d4c8f; border-radius: 5px; padding: 6px 10px; text-align: center; }
.doc-title-label { font-size: 10px; font-weight: 900; color: #0d4c8f; text-transform: uppercase; letter-spacing: 0.06em; }
.doc-title-sub   { font-size: 8px; color: #555; margin-top: 1px; }
.doc-title-sy    { font-size: 9px; font-weight: bold; color: #0d4c8f; margin-top: 3px; }

/* ── Title ── */
.main-title {
    text-align: center;
    font-size: 11pt;
    font-weight: bold;
    color: #0d4c8f;
    margin: 8px 0 1px;
}
.main-subtitle {
    text-align: center;
    font-size: 9.5pt;
    font-weight: bold;
    color: #1a1a2e;
    margin-bottom: 10px;
}

/* ── Outer table ── */
.main-table {
    width: 100%;
    border-collapse: collapse;
}
.main-table > thead > tr > th {
    border: 1.5px solid #0d4c8f;
    background: #0d4c8f;
    color: #fff;
    text-align: center;
    font-size: 9pt;
    font-weight: bold;
    padding: 5px 8px;
    letter-spacing: 0.4px;
}
.main-table > tbody > tr > td {
    border: 1px solid #b3cde8;
    vertical-align: top;
    padding: 0;
}

/* ── Month header row ── */
.month-hdr td {
    background: #0d4c8f;
    color: #fff;
    font-weight: bold;
    font-size: 9.5pt;
    padding: 4px 10px;
    border: 1px solid #0d4c8f;
    letter-spacing: 0.8px;
}

/* ── Left date column ── */
.date-col {
    width: 9%;
    padding: 6px 4px;
    text-align: center;
    vertical-align: top;
    border-right: 1px solid #b3cde8;
    background: #f0f6ff;
}
.date-num {
    font-size: 9pt;
    font-weight: bold;
    color: #0d4c8f;
    line-height: 1.8;
    display: block;
}

/* ── Activity + mini-cal split ── */
.split-table {
    width: 100%;
    border-collapse: collapse;
}
.split-table > tbody > tr > td {
    border: none;
    vertical-align: top;
    padding: 0;
}
.activity-part {
    width: 57%;
    padding: 5px 6px 5px 5px;
}
.calendar-part {
    width: 43%;
    border-left: 1px solid #d0e4f7;
    padding: 5px 6px;
    background: #fafcff;
}

/* ── Activity table inside activity-part ── */
.act-table {
    width: 100%;
    border-collapse: collapse;
}
.act-table td {
    padding: 2px 4px;
    vertical-align: top;
    font-size: 8pt;
    line-height: 1.5;
    border: none;
}
.act-table td.act-type {
    width: 1%;
    white-space: nowrap;
    padding-right: 5px;
}
.act-type-badge {
    display: inline-block;
    border-radius: 2px;
    padding: 0 4px;
    font-size: 7pt;
    font-weight: bold;
    white-space: nowrap;
}
.badge-regular   { background:#dcfce7; color:#166534; }
.badge-holiday   { background:#ede9fe; color:#5b21b6; }
.badge-suspended { background:#fee2e2; color:#991b1b; }
.badge-early     { background:#fef3c7; color:#92400e; }
.badge-exam      { background:#dbeafe; color:#1e40af; }
.badge-event     { background:#fef9c3; color:#713f12; }
.badge-break     { background:#ffedd5; color:#9a3412; }

/* ── Mini calendar ── */
.mini-title {
    text-align: center;
    font-size: 7.5pt;
    font-weight: bold;
    color: #0d4c8f;
    margin-bottom: 3px;
    letter-spacing: 0.4px;
}
.mini-cal {
    width: 100%;
    border-collapse: collapse;
}
.mini-cal th {
    text-align: center;
    font-size: 6.8pt;
    font-weight: bold;
    color: #0d4c8f;
    padding: 1px 0;
    border: none;
}
.mini-cal td {
    text-align: center;
    font-size: 7pt;
    padding: 1.5px 0;
    border: none;
    line-height: 1.4;
    color: #1a1a2e;
}
.mini-cal td.wkend    { color: #bbbbbb; }
.mini-cal td.no-class { text-decoration: line-through; color: #aaaaaa; }
.mini-cal td.has-event {
    background: #0d4c8f;
    color: #fff;
    font-weight: bold;
    border-radius: 2px;
}
.mini-cal td.has-event-wkend {
    background: #1e6fcf;
    color: #fff;
    font-weight: bold;
    border-radius: 2px;
}
.class-days-line {
    text-align: right;
    font-size: 7pt;
    font-weight: bold;
    color: #0d4c8f;
    margin-top: 3px;
}

/* ── Footer ── */
.footer {
    text-align: center;
    font-size: 7.5pt;
    color: #888;
    margin-top: 14px;
    padding-top: 7px;
    border-top: 1px solid #b3cde8;
}

.month-block { page-break-inside: avoid; }
</style>
</head>
<body>

@php
use Carbon\Carbon;

$typeLabel = [
    'regular'          => 'Regular Day',
    'holiday'          => 'Holiday',
    'suspended'        => 'Suspended',
    'early_dismissal'  => 'Early Dismissal',
    'exam_day'         => 'Exam Day',
    'school_event'     => 'School Event',
    'break'            => 'Break',
];
$typeBadge = [
    'regular'          => 'badge-regular',
    'holiday'          => 'badge-holiday',
    'suspended'        => 'badge-suspended',
    'early_dismissal'  => 'badge-early',
    'exam_day'         => 'badge-exam',
    'school_event'     => 'badge-event',
    'break'            => 'badge-break',
];
@endphp

{{-- ── Document Header ── --}}
<table class="doc-header">
    <tr>
        <td class="doc-header-logo">
            <img src="{{ public_path('images/messiah-logo.png') }}" alt="MMSC Logo">
        </td>
        <td class="doc-header-info">
            <div class="school-name">My Messiah School of Cavite</div>
            <div class="school-tagline">Nurturing Excellence, Building Character</div>
            <div class="school-address">144 Compound, Brgy. Palenzuela I, #117, Dasmariñas, Cavite 4114</div>
            <div class="school-contact">Tel: (046) 123-4567 &nbsp;&bull;&nbsp; Email: registrar@mmsc.edu.ph &nbsp;&bull;&nbsp; mmsc.edu.ph</div>
        </td>
    </tr>
</table>

<p class="main-title">Monthly School Calendar of Activities</p>
<p class="main-subtitle">For School Year {{ $schoolYear }}</p>

<table class="main-table">
    <thead>
        <tr>
            <th style="width:9%">Month</th>
            <th>Activity</th>
        </tr>
    </thead>
    <tbody>

    @foreach($months as $mData)
    @php
        $mYear  = $mData['year'];
        $mMonth = $mData['month'];
        $mKey   = "{$mYear}-{$mMonth}";
        $allMonthEvents = $allEvents[$mKey] ?? [];

        // Include any meaningful saved entry in the PDF activity list.
        // Plain regular days without a title are omitted so the export stays readable.
        $listedEvArr = array_values(array_filter($allMonthEvents, function ($e) {
            return $e->day_type !== 'regular' || filled($e->event_title);
        }));
    @endphp

    {{-- Skip months with no listed entries --}}
    @if(empty($listedEvArr))
        @continue
    @endif

    @php
        // Sort by date
        usort($listedEvArr, fn($a, $b) => $a->date->timestamp - $b->date->timestamp);

        $mDate       = Carbon::createFromDate($mYear, $mMonth, 1);
        $monthName   = strtoupper($mDate->format('F'));
        $daysInMonth = $mDate->daysInMonth;
        $firstDow    = $mDate->dayOfWeek; // 0=Sun

        // Full event map (all types) for mini-cal highlighting
        $evMap = [];
        foreach ($allMonthEvents as $e) {
            $evMap[(int) $e->date->format('j')] = $e;
        }

        // Count class days (Mon–Fri, exclude holiday/suspended/break)
        $classDays = 0;
        for ($d = 1; $d <= $daysInMonth; $d++) {
            $dow = Carbon::createFromDate($mYear, $mMonth, $d)->dayOfWeek;
            if ($dow === 0 || $dow === 6) continue;
            $ev = $evMap[$d] ?? null;
            if ($ev && in_array($ev->day_type, ['holiday','suspended','break'])) continue;
            $classDays++;
        }

        $totalCalCells = (int) ceil(($firstDow + $daysInMonth) / 7) * 7;
    @endphp

    {{-- Month header --}}
    <tr class="month-hdr month-block">
        <td colspan="2">{{ $monthName }}</td>
    </tr>

    {{-- Month content --}}
    <tr class="month-block">

        {{-- Date column --}}
        <td class="date-col">
            @foreach($listedEvArr as $ev)
            <span class="date-num">{{ $ev->date->format('j') }}</span>
            @endforeach
        </td>

        {{-- Activity + mini calendar --}}
        <td style="padding:0;">
            <table class="split-table">
                <tr>

                    {{-- Activity table --}}
                    <td class="activity-part">
                        <table class="act-table">
                            @foreach($listedEvArr as $ev)
                            <tr>
                                <td class="act-type">
                                    <span class="act-type-badge {{ $typeBadge[$ev->day_type] ?? '' }}">
                                        {{ $typeLabel[$ev->day_type] ?? $ev->day_type }}
                                    </span>
                                </td>
                                <td>
                                    • {{ $ev->event_title ?: ($typeLabel[$ev->day_type] ?? $ev->dayTypeLabel()) }}
                                </td>
                            </tr>
                            @endforeach
                        </table>
                    </td>

                    {{-- Mini calendar --}}
                    <td class="calendar-part">
                        <div class="mini-title">{{ $monthName }} {{ $mYear }}</div>
                        <table class="mini-cal">
                            <tr>
                                @foreach(['S','M','T','W','T','F','Sa'] as $h)
                                <th>{{ $h }}</th>
                                @endforeach
                            </tr>

                            @for($i = 0; $i < $totalCalCells; $i++)
                            @php
                                $col    = $i % 7;
                                $wkend  = ($col === 0 || $col === 6);

                                if ($i < $firstDow || $i >= $firstDow + $daysInMonth) {
                                    $dayNum = null;
                                } else {
                                    $dayNum = $i - $firstDow + 1;
                                }

                                $evDay   = $dayNum ? ($evMap[$dayNum] ?? null) : null;
                                $noClass = $evDay && in_array($evDay->day_type, ['holiday','suspended','break']);
                                $hasEv   = $evDay && !$noClass;

                                if ($dayNum === null)        { $tdClass = ''; }
                                elseif ($noClass)            { $tdClass = 'no-class'; }
                                elseif ($hasEv && $wkend)   { $tdClass = 'has-event-wkend'; }
                                elseif ($hasEv)              { $tdClass = 'has-event'; }
                                elseif ($wkend)              { $tdClass = 'wkend'; }
                                else                         { $tdClass = ''; }
                            @endphp
                            @if($col === 0)<tr>@endif
                            <td class="{{ $tdClass }}">
                                @if($dayNum !== null)
                                    @if($noClass)✗@else{{ $dayNum }}@endif
                                @endif
                            </td>
                            @if($col === 6)</tr>@endif
                            @endfor
                        </table>
                        <div class="class-days-line">Class days: {{ $classDays }}</div>
                    </td>

                </tr>
            </table>
        </td>
    </tr>

    @endforeach

    </tbody>
</table>

<div class="footer">
    Generated on {{ now()->format('F j, Y \a\t g:i A') }}
    &nbsp;|&nbsp; My Messiah School of Cavite &copy; {{ now()->year }}
</div>

</body>
</html>
