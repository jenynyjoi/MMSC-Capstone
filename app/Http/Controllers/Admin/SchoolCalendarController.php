<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SchoolCalendarEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class SchoolCalendarController extends Controller
{
    // Types shown in "Upcoming Events" table
    const UPCOMING_TYPES = ['holiday', 'exam_day', 'school_event', 'break'];

    // ── Index ────────────────────────────────────────────────
    public function index(Request $request)
    {
        $schoolYear = $request->get('school_year', '2025-2026');
        $month      = (int) $request->get('month', now()->month);
        $year       = (int) $request->get('year',  now()->year);

        $events = SchoolCalendarEvent::forYear($schoolYear)
            ->orderBy('date')
            ->get()
            ->mapWithKeys(fn($e) => [
                $e->date->format('Y-m-d') => [
                    'id'          => $e->id,
                    'day_type'    => $e->day_type,
                    'event_title' => $e->event_title,
                    'badge_class' => $e->badgeClass(),
                    'label'       => $e->event_title ?: $e->dayTypeLabel(),
                ]
            ]);

        $upcoming = SchoolCalendarEvent::forYear($schoolYear)
            ->whereIn('day_type', self::UPCOMING_TYPES)
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->orderBy('date')
            ->get();

        return view('admin.school-calendar', compact('events', 'upcoming', 'schoolYear', 'month', 'year'));
    }

    // ── Store (Add Event) ───────────────────────────────────
    public function store(Request $request)
    {
        $data = $request->validate([
            'school_year'          => 'required|string',
            'date'                 => 'required|date',
            'day_type'             => 'required|in:regular,holiday,suspended,early_dismissal,exam_day,school_event,break',
            'event_title'          => 'nullable|string|max:255',
            'description'          => 'nullable|string',
            'time_from'            => 'nullable|date_format:H:i',
            'time_to'              => 'nullable|date_format:H:i',
            'early_dismissal_time' => 'nullable|date_format:H:i',
            'attendance_rule'      => 'required|in:normal,no_attendance_holiday,no_attendance_suspension,morning_only,afternoon_only,exam_present',
            'applies_to'           => 'nullable|string',
            'notify_teachers'      => 'nullable|boolean',
            'notify_parents'       => 'nullable|boolean',
            'add_to_public'        => 'nullable|boolean',
            'send_reminder'        => 'nullable|boolean',
        ]);

        $data['notify_teachers'] = (bool) ($data['notify_teachers'] ?? false);
        $data['notify_parents']  = (bool) ($data['notify_parents']  ?? false);
        $data['add_to_public']   = (bool) ($data['add_to_public']   ?? false);
        $data['send_reminder']   = (bool) ($data['send_reminder']   ?? false);

        $event = SchoolCalendarEvent::updateOrCreate(
            ['school_year' => $data['school_year'], 'date' => $data['date']],
            $data
        );

        return response()->json([
            'success' => true,
            'message' => 'Event saved successfully.',
            'event'   => [
                'id'          => $event->id,
                'date'        => $event->date->format('Y-m-d'),
                'day_type'    => $event->day_type,
                'event_title' => $event->event_title,
                'badge_class' => $event->badgeClass(),
                'label'       => $event->event_title ?: $event->dayTypeLabel(),
            ],
        ]);
    }

    // ── Update (Edit Day — day_type, event_title, attendance_rule) ──
    public function update(Request $request, $id)
    {
        $event = SchoolCalendarEvent::findOrFail($id);

        $data = $request->validate([
            'day_type'        => 'required|in:regular,holiday,suspended,early_dismissal,exam_day,school_event,break',
            'event_title'     => 'nullable|string|max:255',
            'attendance_rule' => 'required|in:normal,no_attendance_holiday,no_attendance_suspension,morning_only,afternoon_only,exam_present',
        ]);

        $event->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Day updated successfully.',
            'event'   => [
                'id'          => $event->id,
                'date'        => $event->date->format('Y-m-d'),
                'day_type'    => $event->day_type,
                'event_title' => $event->event_title,
                'badge_class' => $event->badgeClass(),
                'label'       => $event->event_title ?: $event->dayTypeLabel(),
            ],
        ]);
    }

    // ── Show single event ───────────────────────────────────
    public function show($id)
    {
        return response()->json(SchoolCalendarEvent::findOrFail($id));
    }

    // ── Get event by date ───────────────────────────────────
    public function getByDate(Request $request)
    {
        $request->validate(['date' => 'required|date', 'school_year' => 'required|string']);

        $event = SchoolCalendarEvent::where('school_year', $request->school_year)
            ->where('date', $request->date)
            ->first();

        return response()->json($event);
    }

    // ── Delete ──────────────────────────────────────────────
    public function destroy($id)
    {
        SchoolCalendarEvent::findOrFail($id)->delete();
        return response()->json(['success' => true]);
    }

    // ── Download PDF ────────────────────────────────────────
    public function downloadPdf(Request $request)
    {
        $schoolYear = $request->get('school_year', '2025-2026');
        $month      = (int) $request->get('month', now()->month);
        $year       = (int) $request->get('year',  now()->year);

        $events = SchoolCalendarEvent::forYear($schoolYear)
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->orderBy('date')
            ->get();

        $monthLabel = Carbon::createFromDate($year, $month, 1)->format('F Y');

        $pdf = Pdf::loadView(
            'admin.school-calendar-pdf',
            compact('events', 'schoolYear', 'monthLabel', 'month', 'year')
        )->setPaper('a4', 'landscape');

        return $pdf->download("school-calendar-{$year}-{$month}.pdf");
    }
}