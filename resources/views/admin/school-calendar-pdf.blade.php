<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>School Calendar – {{ $monthLabel }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', Arial, sans-serif; font-size: 11px; color: #1e293b; background: #fff; }

        .header { text-align: center; padding: 20px 0 12px; border-bottom: 2px solid #0d4c8f; margin-bottom: 16px; }
        .header h1 { font-size: 20px; font-weight: 700; color: #0d4c8f; letter-spacing: -0.3px; }
        .header p  { font-size: 11px; color: #64748b; margin-top: 3px; }

        table.calendar { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        table.calendar th {
            background: #0d4c8f; color: #fff; text-align: center;
            padding: 6px 4px; font-size: 10px; font-weight: 600; letter-spacing: 0.5px;
        }
        table.calendar td {
            border: 1px solid #e2e8f0; vertical-align: top;
            min-height: 60px; padding: 4px; width: 14.28%;
        }
        .day-num { font-size: 10px; font-weight: 600; text-align: right; color: #334155; margin-bottom: 2px; }
        .day-num.today { background: #0d4c8f; color: #fff; border-radius: 50%; width: 18px; height: 18px; display: inline-flex; align-items: center; justify-content: center; float: right; }
        .other-month  { color: #cbd5e1; }
        .weekend-cell { background: #f8fafc; }

        .badge { border-radius: 3px; padding: 1px 4px; font-size: 8.5px; line-height: 1.4; margin-top: 2px; display: block; }
        .badge-regular   { background: #dcfce7; color: #166534; }
        .badge-holiday   { background: #f3e8ff; color: #6b21a8; }
        .badge-suspended { background: #fee2e2; color: #991b1b; }
        .badge-early     { background: #fef3c7; color: #92400e; }
        .badge-exam      { background: #dbeafe; color: #1e40af; }
        .badge-event     { background: #fef9c3; color: #713f12; }
        .badge-break     { background: #ffedd5; color: #9a3412; }

        .legend { display: flex; flex-wrap: wrap; gap: 10px; padding: 10px 0; border-top: 1px solid #e2e8f0; margin-bottom: 16px; }
        .legend-item { display: flex; align-items: center; gap: 4px; font-size: 9px; color: #475569; }
        .legend-dot  { width: 8px; height: 8px; border-radius: 50%; display: inline-block; }

        .upcoming-section h2 { font-size: 13px; font-weight: 700; color: #0d4c8f; margin-bottom: 8px; }
        table.upcoming { width: 100%; border-collapse: collapse; font-size: 10px; }
        table.upcoming th { background: #f1f5f9; color: #334155; text-align: left; padding: 6px 8px; font-weight: 600; border-bottom: 1px solid #cbd5e1; }
        table.upcoming td { padding: 5px 8px; border-bottom: 1px solid #f1f5f9; color: #475569; }
        table.upcoming tr:nth-child(even) td { background: #f8fafc; }

        .footer { text-align: center; font-size: 9px; color: #94a3b8; margin-top: 20px; padding-top: 10px; border-top: 1px solid #e2e8f0; }
    </style>
</head>
<body>

    <div class="header">
        <h1>My Messiah School of Cavite — School Calendar</h1>
        <p>{{ $monthLabel }} &nbsp;|&nbsp; School Year {{ $schoolYear }}</p>
    </div>

    @php
        use Carbon\Carbon;
        $firstOfMonth = Carbon::createFromDate($year, $month, 1);
        $firstDay     = $firstOfMonth->dayOfWeek; // 0 = Sunday
        $daysInMonth  = $firstOfMonth->daysInMonth;
        $daysInPrev   = Carbon::createFromDate($year, $month, 1)->subMonth()->daysInMonth;
        $totalCells   = (int) ceil(($firstDay + $daysInMonth) / 7) * 7;
        $today        = now();

        $evMap = $events->keyBy(fn($e) => $e->date->format('Y-m-d'));

        $badgeCls = [
            'regular'         => 'badge-regular',
            'holiday'         => 'badge-holiday',
            'suspended'       => 'badge-suspended',
            'early_dismissal' => 'badge-early',
            'exam_day'        => 'badge-exam',
            'school_event'    => 'badge-event',
            'break'           => 'badge-break',
        ];
    @endphp

    <table class="calendar">
        <thead>
            <tr>
                @foreach(['Sun','Mon','Tue','Wed','Thu','Fri','Sat'] as $dow)
                <th>{{ $dow }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
        @php $row = []; @endphp
        @for($i = 0; $i < $totalCells; $i++)
            @php
                $col = $i % 7;
                if ($i < $firstDay) {
                    $day = $daysInPrev - $firstDay + $i + 1;
                    $cur = false;
                    $pm  = $month === 1 ? 12 : $month - 1;
                    $py  = $month === 1 ? $year - 1 : $year;
                    $ds  = sprintf('%04d-%02d-%02d', $py, $pm, $day);
                } elseif ($i >= $firstDay + $daysInMonth) {
                    $day = $i - $firstDay - $daysInMonth + 1;
                    $cur = false;
                    $nm  = $month === 12 ? 1 : $month + 1;
                    $ny  = $month === 12 ? $year + 1 : $year;
                    $ds  = sprintf('%04d-%02d-%02d', $ny, $nm, $day);
                } else {
                    $day = $i - $firstDay + 1;
                    $cur = true;
                    $ds  = sprintf('%04d-%02d-%02d', $year, $month, $day);
                }
                $isToday  = $cur && $today->format('Y-m-d') === $ds;
                $weekend  = $col === 0 || $col === 6;
                $ev       = $evMap[$ds] ?? null;
                $isReg    = $cur && !$weekend && !$ev;
            @endphp
            @if($col === 0)<tr>@endif
            <td class="{{ $weekend && $cur ? 'weekend-cell' : '' }}">
                <div class="day-num {{ !$cur ? 'other-month' : '' }}">
                    @if($isToday)<span class="today">{{ $day }}</span>@else{{ $day }}@endif
                </div>
                @if($isReg)
                    <span class="badge badge-regular">Regular Class</span>
                @elseif($ev)
                    <span class="badge {{ $badgeCls[$ev->day_type] ?? 'badge-regular' }}">
                        {{ $ev->event_title ?: $ev->dayTypeLabel() }}
                    </span>
                @endif
            </td>
            @if($col === 6)</tr>@endif
        @endfor
        </tbody>
    </table>

    {{-- Legend --}}
    <div class="legend">
        <span style="font-size:9px;font-weight:600;color:#475569;margin-left:10px;padding: left 10px;;">
            Legend:
        </span>
        @foreach([
            ['#86efac','Regular'],['#d8b4fe','Holiday'],['#fca5a5','Suspended'],
            ['#fde68a','School Event'],['#fcd34d','Early Dismissal'],['#93c5fd','Exam Day'],
            ['#fdba74','Break'],['#e2e8f0','Weekend/No Classes'],
        ] as $l)
        <span class="legend-item"><span class="legend-dot" style="background:{{ $l[0] }}"></span>{{ $l[1] }}</span>
        @endforeach
    </div>

    {{-- Upcoming Events --}}
    @if($events->whereIn('day_type',['holiday','exam_day','school_event','break'])->count())
    <div class="upcoming-section">
        <h2>Events this Month</h2>
        <table class="upcoming">
            <thead><tr><th>Date</th><th>Day</th><th>Type</th><th>Event</th></tr></thead>
            <tbody>
            @foreach($events->whereIn('day_type',['holiday','exam_day','school_event','break'])->sortBy('date') as $ev)
            <tr>
                <td>{{ $ev->date->format('M j, Y') }}</td>
                <td>{{ $ev->date->format('l') }}</td>
                <td>{{ $ev->dayTypeLabel() }}</td>
                <td>{{ $ev->event_title ?? '—' }}</td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <div class="footer">
        Generated on {{ now()->format('F j, Y \a\t g:i A') }} &nbsp;|&nbsp; My Messiah School of Cavite &copy; {{ now()->year }}
    </div>
</body>
</html>