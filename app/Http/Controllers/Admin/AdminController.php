<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()                     { return view('admin.dashboard'); }
    public function admission()                 { return view('admin.admission'); }

    // ── Enrollment: Pending Section Assignment ──
    public function enrollStudent(Request $request)
    {
        $query = Student::whereNull('section_name')
                        ->orWhere('section_name', '');

        if ($request->filled('level')) {
            $query->where('applied_level', $request->level);
        }

        $students     = $query->latest('enrolled_at')->paginate(10)->withQueryString();
        $pendingCount = Student::where(function($q) {
            $q->whereNull('section_name')->orWhere('section_name', '');
        })->count();

        return view('admin.enrollment.enroll', compact('students', 'pendingCount'));
    }

    // ── Enrollment: Assign Section ──
    public function assignSection(Request $request, int $id)
    {
        $request->validate(['section_name' => 'required|string|max:100']);

        $student = Student::findOrFail($id);
        $student->update([
            'section_name'     => $request->section_name,
            'enrollment_status'=> 'enrolled',
        ]);

        return back()->with('success', $student->full_name . ' has been assigned to ' . $request->section_name . '.');
    }

    public function promoteStudent()            { return view('admin.enrollment.promote'); }

    public function studentList()               { return view('admin.student-records.list'); }
    public function studentProfile($id)         { return view('admin.student-records.profile', compact('id')); }
    public function withdrawnStudents()         { return view('admin.student-records.withdrawn'); }
    public function behavioralRecord()          { return view('admin.student-records.behavioral'); }
    public function studentArchives()           { return view('admin.student-records.archives'); }

    public function clearanceAcademicStanding() { return view('admin.clearance.academic-standing'); }
    public function clearanceFinance()          { return view('admin.clearance.finance'); }
    public function clearanceLibrary()          { return view('admin.clearance.library'); }
    public function clearanceRecords()          { return view('admin.clearance.records'); }
    public function clearanceSummary()          { return view('admin.clearance.summary'); }

    public function academicAttendance()        { return view('admin.academic.attendance'); }
    public function academicSubjects()          { return view('admin.academic.subjects'); }
    public function academicGrades()            { return view('admin.academic.grades'); }

    public function classesList()               { return view('admin.classes.list'); }
    public function classRosters()              { return view('admin.classes.rosters'); }
    public function classrooms()                { return view('admin.classes.classrooms'); }
    public function sectionManagement()         { return view('admin.classes.sections'); }

    public function scheduleClass()             { return view('admin.schedule.class'); }
    public function scheduleTeacher()           { return view('admin.schedule.teacher'); }
    public function scheduleRoom()              { return view('admin.schedule.room'); }

    public function teachers()                  { return view('admin.teachers'); }
    public function announcements()             { return view('admin.announcements'); }

    public function reportStudentProfile()      { return view('admin.reports.student-profile'); }
    public function reportStudentList()         { return view('admin.reports.student-list'); }
    public function reportEnrollmentSummary()   { return view('admin.reports.enrollment-summary'); }
    public function reportGraduationList()      { return view('admin.reports.graduation-list'); }
    public function reportReportCard()          { return view('admin.reports.report-card'); }
    public function reportDailyAttendance()     { return view('admin.reports.daily-attendance'); }
    public function reportAttendanceRecord()    { return view('admin.reports.attendance-record'); }
    public function reportClassRecord()         { return view('admin.reports.class-record'); }
    public function reportClassRoster()         { return view('admin.reports.class-roster'); }
    public function reportHonorRoll()           { return view('admin.reports.honor-roll'); }
    public function reportClearanceSummary()    { return view('admin.reports.clearance-summary'); }
    public function reportRecordsClearance()    { return view('admin.reports.records-clearance'); }
    public function reportLibraryClearance()    { return view('admin.reports.library-clearance'); }
    public function reportFinancialClearance()  { return view('admin.reports.financial-clearance'); }
    public function reportClassSchedule()       { return view('admin.reports.class-schedule'); }
    public function reportTeacherSchedule()     { return view('admin.reports.teacher-schedule'); }
    public function reportRoomSchedule()        { return view('admin.reports.room-schedule'); }
    public function reportTeacherLoad()         { return view('admin.reports.teacher-load'); }
    public function reportTeacherList()         { return view('admin.reports.teacher-list'); }

    public function schoolCalendar()            { return view('admin.school-calendar'); }

    public function settingsAccount()           { return view('admin.settings.account'); }
    public function settingsUserManagement()    { return view('admin.settings.user-management'); }
    public function settingsGeneral()           { return view('admin.settings.general'); }
}