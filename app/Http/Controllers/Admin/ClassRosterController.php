<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Section;
use App\Models\Student;
use Illuminate\Http\Request;

class ClassRosterController extends Controller
{
    // ══════════════════════════════════════════════════════
    // INDEX — page with filter form
    // ══════════════════════════════════════════════════════
    public function index(Request $request)
    {
        $schoolYear = $request->get('school_year', \App\Models\SchoolYear::activeName());

        $sections = Section::where('school_year', $schoolYear)
            ->where('section_status', 'active')
            ->orderBy('grade_level')
            ->orderBy('section_name')
            ->get();

        $schoolYear = ['2026-2027', '2025-2026', '2024-2025'];

        return view('admin.class-rosters', compact('sections', 'schoolYears', 'schoolYear'));
    }

    // ══════════════════════════════════════════════════════
    // GET STUDENTS — AJAX for roster table
    // ══════════════════════════════════════════════════════
    public function getStudents(Request $request)
    {
        $request->validate([
            'section_id'  => 'required|exists:sections,id',
            'school_year' => 'required|string',
        ]);

        $section  = Section::findOrFail($request->section_id);

        $students = Student::where('section_id', $request->section_id)
            ->where('school_year', $request->school_year)
            ->whereIn('student_status', ['active', 'enrolled'])
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get([
                'id', 'student_id', 'first_name', 'middle_name', 'last_name', 'suffix',
                'gender', 'school_email', 'personal_email', 'lrn', 'student_status',
            ]);

        return response()->json([
            'success'  => true,
            'section'  => [
                'id'          => $section->id,
                'grade_level' => $section->grade_level,
                'section_name'=> $section->section_name,
                'school_year' => $request->school_year,
                'adviser'     => $section->homeroom_adviser_name ?? 'TBA',
                'room'        => $section->room ?? '—',
                'program_level' => $section->program_level ?? '—',
                'track'       => $section->track ?? null,
                'strand'      => $section->strand ?? null,
                'capacity'    => $section->capacity,
            ],
            'students' => $students->map(fn($s, $i) => [
                'no'         => $i + 1,
                'id'         => $s->id,
                'student_id' => $s->student_id,
                'full_name'  => trim(
                    $s->last_name . ', ' . $s->first_name
                    . ($s->middle_name ? ' ' . strtoupper(substr($s->middle_name, 0, 1)) . '.' : '')
                    . ($s->suffix ? ' ' . $s->suffix : '')
                ),
                'gender'     => $s->gender,
                'email'      => $s->school_email ?? $s->personal_email ?? '—',
                'lrn'        => $s->lrn ?? '—',
                'status'     => $s->student_status,
            ])->values()->toArray(),
            'total' => $students->count(),
        ]);
    }

    // ══════════════════════════════════════════════════════
    // EXPORT EXCEL
    // ══════════════════════════════════════════════════════
    public function exportExcel(Request $request)
    {
        $request->validate([
            'section_id'  => 'required|exists:sections,id',
            'school_year' => 'required|string',
        ]);

        $section  = Section::findOrFail($request->section_id);
        $students = Student::where('section_id', $request->section_id)
            ->where('school_year', $request->school_year)
            ->whereIn('student_status', ['active', 'enrolled'])
            ->orderBy('last_name')->orderBy('first_name')
            ->get();

        // Build CSV as fallback (works without PhpSpreadsheet installed)
        $filename = 'ClassRoster_' . str_replace(' ', '_', $section->grade_level)
            . '-' . $section->section_name . '_' . $request->school_year . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($section, $students, $request) {
            $out = fopen('php://output', 'w');

            // School header
            fputcsv($out, ['MY MESSIAH SCHOOL OF CAVITE']);
            fputcsv($out, ['Class Roster']);
            fputcsv($out, ['']);
            fputcsv($out, ['Grade Level:', $section->grade_level, 'Section:', $section->section_name]);
            fputcsv($out, ['School Year:', $request->school_year, 'Adviser:', $section->homeroom_adviser_name ?? 'TBA']);
            fputcsv($out, ['Room:', $section->room ?? '—', 'Total Students:', $students->count()]);
            fputcsv($out, ['Date Generated:', now()->format('F d, Y h:i A')]);
            fputcsv($out, ['']);

            // Column headers
            fputcsv($out, ['#', 'Student ID', 'LRN', 'Last Name', 'First Name', 'Middle Name', 'Suffix', 'Gender', 'Email']);

            // Rows
            foreach ($students as $i => $s) {
                fputcsv($out, [
                    $i + 1,
                    $s->student_id,
                    $s->lrn ?? '—',
                    $s->last_name,
                    $s->first_name,
                    $s->middle_name ?? '',
                    $s->suffix ?? '',
                    $s->gender,
                    $s->school_email ?? $s->personal_email ?? '—',
                ]);
            }

            fclose($out);
        };

        return response()->stream($callback, 200, $headers);
    }

    // ══════════════════════════════════════════════════════
    // EXPORT PDF — returns HTML for browser printing
    // ══════════════════════════════════════════════════════
    public function exportPdf(Request $request)
    {
        $request->validate([
            'section_id'   => 'required|exists:sections,id',
            'school_year'  => 'required|string',
            'orientation'  => 'nullable|in:portrait,landscape',
            'page_size'    => 'nullable|in:a4,letter',
            'show_logo'    => 'nullable',
            'show_lrn'     => 'nullable',
            'show_gender'  => 'nullable',
            'show_email'   => 'nullable',
        ]);

        $section  = Section::findOrFail($request->section_id);
        $students = Student::where('section_id', $request->section_id)
            ->where('school_year', $request->school_year)
            ->whereIn('student_status', ['active', 'enrolled'])
            ->orderBy('last_name')->orderBy('first_name')
            ->get();

        $orientation = $request->get('orientation', 'portrait');
        $showLrn     = $request->boolean('show_lrn', true);
        $showGender  = $request->boolean('show_gender', true);
        $showEmail   = $request->boolean('show_email', true);
        $showLogo    = $request->boolean('show_logo', true);

        $html = view('admin.pdf.class-roster-pdf', compact(
            'section', 'students', 'orientation', 'showLrn', 'showGender', 'showEmail', 'showLogo'
        ))->render();

        return response($html)->header('Content-Type', 'text/html');
    }
}