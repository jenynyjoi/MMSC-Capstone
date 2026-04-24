<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\TeacherProfile;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    // Static nav pages — mirrors admin_sidebar navItems
    private static array $pages = [
        // Top-level
        ['label' => 'Dashboard',           'parent' => null,              'href' => 'admin.dashboard'],
        ['label' => 'Admission',           'parent' => null,              'href' => 'admin.admission'],
        // Enrollment
        ['label' => 'Enroll Student',      'parent' => 'Enrollment',      'href' => 'admin.enrollment.enroll'],
        ['label' => 'Promote Student',     'parent' => 'Enrollment',      'href' => 'admin.enrollment.promote'],
        // Student Records
        ['label' => 'Student List',        'parent' => 'Student Records', 'href' => 'admin.student-records.list'],
        ['label' => 'Withdrawn Students',  'parent' => 'Student Records', 'href' => 'admin.student-records.withdrawn'],
        ['label' => 'Behavioral Records',  'parent' => 'Student Records', 'href' => 'admin.student-records.behavioral'],
        ['label' => 'Student Archives',    'parent' => 'Student Records', 'href' => 'admin.student-records.archives'],
        // Clearance
        ['label' => 'Academic Standing',   'parent' => 'Clearance',       'href' => 'admin.clearance.academic-standing'],
        ['label' => 'Finance Clearance',   'parent' => 'Clearance',       'href' => 'admin.clearance.finance'],
        ['label' => 'Library Clearance',   'parent' => 'Clearance',       'href' => 'admin.clearance.library'],
        ['label' => 'Records Clearance',   'parent' => 'Clearance',       'href' => 'admin.clearance.records'],
        ['label' => 'Behavioral Clearance','parent' => 'Clearance',       'href' => 'admin.clearance.behavioral'],
        ['label' => 'Property Clearance',  'parent' => 'Clearance',       'href' => 'admin.clearance.property'],
        ['label' => 'Clearance Summary',   'parent' => 'Clearance',       'href' => 'admin.clearance.summary'],
        // Academics
        ['label' => 'Attendance',          'parent' => 'Academics',       'href' => 'admin.academic.attendance'],
        ['label' => 'Subjects',            'parent' => 'Academics',       'href' => 'admin.academic.subjects'],
        ['label' => 'Grades',              'parent' => 'Academics',       'href' => 'admin.academic.grades'],
        // Classes
        ['label' => 'Class List',          'parent' => 'Classes',         'href' => 'admin.classes.list'],
        ['label' => 'Class Rosters',       'parent' => 'Classes',         'href' => 'admin.classes.rosters'],
        ['label' => 'Classrooms',          'parent' => 'Classes',         'href' => 'admin.classes.classrooms'],
        ['label' => 'Section Management',  'parent' => 'Classes',         'href' => 'admin.classes.sections'],
        // Schedule
        ['label' => 'Class Schedule',      'parent' => 'Schedule',        'href' => 'admin.schedule.class'],
        ['label' => 'Teacher Schedule',    'parent' => 'Schedule',        'href' => 'admin.schedule.teacher'],
        ['label' => 'Room Schedule',       'parent' => 'Schedule',        'href' => 'admin.schedule.room'],
        // Top-level
        ['label' => 'Teachers',            'parent' => null,              'href' => 'admin.teachers'],
        ['label' => 'Announcements',       'parent' => null,              'href' => 'admin.announcements'],
        // Reports
        ['label' => 'Student Profile Report',   'parent' => 'Reports', 'href' => 'admin.reports.student-profile'],
        ['label' => 'Student List Report',      'parent' => 'Reports', 'href' => 'admin.reports.student-list'],
        ['label' => 'Enrollment Summary',       'parent' => 'Reports', 'href' => 'admin.reports.enrollment-summary'],
        ['label' => 'Graduation List',          'parent' => 'Reports', 'href' => 'admin.reports.graduation-list'],
        ['label' => 'Report Card',              'parent' => 'Reports', 'href' => 'admin.reports.report-card'],
        ['label' => 'Daily Attendance Report',  'parent' => 'Reports', 'href' => 'admin.reports.daily-attendance'],
        ['label' => 'Attendance Record',        'parent' => 'Reports', 'href' => 'admin.reports.attendance-record'],
        ['label' => 'Class Record',             'parent' => 'Reports', 'href' => 'admin.reports.class-record'],
        ['label' => 'Class Roster Report',      'parent' => 'Reports', 'href' => 'admin.reports.class-roster'],
        ['label' => 'Honor Roll',               'parent' => 'Reports', 'href' => 'admin.reports.honor-roll'],
        ['label' => 'Clearance Summary Report', 'parent' => 'Reports', 'href' => 'admin.reports.clearance-summary'],
        ['label' => 'Teacher Load',             'parent' => 'Reports', 'href' => 'admin.reports.teacher-load'],
        ['label' => 'Teacher List Report',      'parent' => 'Reports', 'href' => 'admin.reports.teacher-list'],
        // Calendar
        ['label' => 'School Calendar',     'parent' => null,              'href' => 'admin.school-calendar.index'],
        ['label' => 'School Year Config',  'parent' => 'School Calendar', 'href' => 'admin.school-year-config.index'],
        // Settings
        ['label' => 'Account Settings',   'parent' => 'Settings',        'href' => 'admin.settings.account'],
        ['label' => 'User Management',     'parent' => 'Settings',        'href' => 'admin.settings.user-management'],
        ['label' => 'General Settings',    'parent' => 'Settings',        'href' => 'admin.settings.general'],
    ];

    public function search(Request $request)
    {
        $q = trim($request->get('q', ''));

        if (strlen($q) < 1) {
            return response()->json(['students' => [], 'teachers' => [], 'pages' => []]);
        }

        $like = "%{$q}%";

        // ── Students ────────────────────────────────────────
        $students = Student::where('student_status', '!=', 'archived')
            ->where(function ($query) use ($like) {
                $query->whereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", [$like])
                      ->orWhereRaw("CONCAT(first_name, ' ', middle_name, ' ', last_name) LIKE ?", [$like])
                      ->orWhere('student_id', 'like', $like)
                      ->orWhere('lrn', 'like', $like);
            })
            ->orderBy('last_name')
            ->limit(6)
            ->get(['id', 'first_name', 'middle_name', 'last_name', 'student_id', 'grade_level', 'section_name'])
            ->map(fn($s) => [
                'id'          => $s->id,
                'name'        => trim("{$s->first_name} {$s->last_name}"),
                'meta'        => implode(' · ', array_filter([$s->student_id, $s->grade_level ? "Grade {$s->grade_level}" : null, $s->section_name])),
                'href'        => route('admin.student-records.profile', $s->id),
            ]);

        // ── Teachers ────────────────────────────────────────
        $teachers = TeacherProfile::with('user')
            ->whereHas('user', fn($q2) => $q2->where('name', 'like', $like))
            ->orWhere('teacher_id_code', 'like', $like)
            ->limit(5)
            ->get()
            ->map(fn($t) => [
                'id'   => $t->id,
                'name' => $t->user?->name ?? '—',
                'meta' => implode(' · ', array_filter([$t->teacher_id_code, $t->department])),
                'href' => route('admin.teachers', ['search' => $t->user?->name]),
            ]);

        // ── Pages ───────────────────────────────────────────
        $pages = collect(self::$pages)
            ->filter(fn($p) =>
                str_contains(strtolower($p['label']), strtolower($q)) ||
                ($p['parent'] && str_contains(strtolower($p['parent']), strtolower($q)))
            )
            ->take(6)
            ->values()
            ->map(fn($p) => [
                'label'  => $p['label'],
                'parent' => $p['parent'],
                'href'   => route($p['href']),
            ]);

        return response()->json([
            'students' => $students,
            'teachers' => $teachers,
            'pages'    => $pages,
        ]);
    }
}