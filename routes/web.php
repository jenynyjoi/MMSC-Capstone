<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\OnlineRegistrationController;
use App\Http\Controllers\SuperAdmin\SuperAdminController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdmissionReviewController;
use App\Http\Controllers\Admin\SchoolCalendarController;
use App\Http\Controllers\Teacher\TeacherController;
use App\Http\Controllers\Student\StudentController;
use App\Http\Controllers\Parent\ParentController;

// ── PUBLIC ──────────────────────────────────────────────────
Route::get('/', function () { return view('welcome'); })->name('welcome');

// Online Registration (public)
Route::prefix('registration')->name('online.registration.')->group(function () {
    Route::get('/',         [OnlineRegistrationController::class, 'step1'])->name('index');
    Route::get('/step-1',   [OnlineRegistrationController::class, 'step1'])->name('step1');
    Route::post('/step-1',  [OnlineRegistrationController::class, 'saveStep1'])->name('save-step1');
    Route::get('/step-2',   [OnlineRegistrationController::class, 'step2'])->name('step2');
    Route::post('/step-2',  [OnlineRegistrationController::class, 'saveStep2'])->name('save-step2');
    Route::get('/step-3',   [OnlineRegistrationController::class, 'step3'])->name('step3');
    Route::post('/step-3',  [OnlineRegistrationController::class, 'saveStep3'])->name('save-step3');
    Route::get('/review',   [OnlineRegistrationController::class, 'review'])->name('review');
    Route::post('/submit',  [OnlineRegistrationController::class, 'submit'])->name('submit');
    Route::get('/confirmation/{ref}',  [OnlineRegistrationController::class, 'confirmation'])->name('confirmation');
    Route::get('/download-pdf/{ref}',  [OnlineRegistrationController::class, 'downloadPdf'])->name('download-pdf');
});

// Alias: "Apply Now" & navbar "Admission"
Route::get('/admission', fn() => redirect()->route('online.registration.step1'))->name('online.registration');

// ── SUPER ADMIN ──────────────────────────────────────────────
Route::middleware(['auth', 'role:super_admin'])
    ->prefix('superadmin')->name('superadmin.')
    ->group(function () {
        Route::get('/dashboard', [SuperAdminController::class, 'index'])->name('dashboard');
    });

// ── ADMIN ────────────────────────────────────────────────────
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')->name('admin.')
    ->group(function () {

        Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');

        // ── Admission ──
        Route::get('/admission',                [AdmissionReviewController::class, 'index'])->name('admission');
        Route::get('/admission/{id}',           [AdmissionReviewController::class, 'show'])->name('admission.show');
        Route::get('/admission/{id}/pdf',       [AdmissionReviewController::class, 'downloadPdf'])->name('admission.pdf');
        Route::put('/admission/{id}/status',    [AdmissionReviewController::class, 'updateStatus'])->name('admission.status');
        Route::post('/admission/bulk-status',   [AdmissionReviewController::class, 'bulkUpdateStatus'])->name('admission.bulk-status');
        Route::post('/admission/send-notice',   [AdmissionReviewController::class, 'sendNotice'])->name('admission.send-notice');

        // ── Enrollment ──
        Route::get('/enrollment/enroll',               [AdminController::class, 'enrollStudent'])->name('enrollment.enroll');
        Route::patch('/enrollment/assign/{id}',        [AdminController::class, 'assignSection'])->name('enrollment.assign');
        Route::get('/enrollment/promote',              [AdminController::class, 'promoteStudent'])->name('enrollment.promote');

        // ── Student Records ──
        Route::get('/student-records/list',            [AdminController::class, 'studentList'])->name('student-records.list');
        Route::get('/student-records/profile/{id}',    [AdminController::class, 'studentProfile'])->name('student-records.profile');
        Route::get('/student-records/withdrawn',       [AdminController::class, 'withdrawnStudents'])->name('student-records.withdrawn');
        Route::get('/student-records/behavioral',      [AdminController::class, 'behavioralRecord'])->name('student-records.behavioral');
        Route::get('/student-records/archives',        [AdminController::class, 'studentArchives'])->name('student-records.archives');

        // ── Clearance ──
        Route::get('/clearance/academic-standing',     [AdminController::class, 'clearanceAcademicStanding'])->name('clearance.academic-standing');
        Route::get('/clearance/finance',               [AdminController::class, 'clearanceFinance'])->name('clearance.finance');
        Route::get('/clearance/library',               [AdminController::class, 'clearanceLibrary'])->name('clearance.library');
        Route::get('/clearance/records',               [AdminController::class, 'clearanceRecords'])->name('clearance.records');
        Route::get('/clearance/summary',               [AdminController::class, 'clearanceSummary'])->name('clearance.summary');

        // ── Academics ──
        Route::get('/academic/attendance',             [AdminController::class, 'academicAttendance'])->name('academic.attendance');
        Route::get('/academic/subjects',               [AdminController::class, 'academicSubjects'])->name('academic.subjects');
        Route::get('/academic/grades',                 [AdminController::class, 'academicGrades'])->name('academic.grades');

        // ── Classes ──
        Route::get('/classes/list',                    [AdminController::class, 'classesList'])->name('classes.list');
        Route::get('/classes/rosters',                 [AdminController::class, 'classRosters'])->name('classes.rosters');
        Route::get('/classes/classrooms',              [AdminController::class, 'classrooms'])->name('classes.classrooms');
        Route::get('/classes/sections',                [AdminController::class, 'sectionManagement'])->name('classes.sections');

        // ── Schedule ──
        Route::get('/schedule/class',                  [AdminController::class, 'scheduleClass'])->name('schedule.class');
        Route::get('/schedule/teacher',                [AdminController::class, 'scheduleTeacher'])->name('schedule.teacher');
        Route::get('/schedule/room',                   [AdminController::class, 'scheduleRoom'])->name('schedule.room');

        Route::get('/teachers',                        [AdminController::class, 'teachers'])->name('teachers');
        Route::get('/announcements',                   [AdminController::class, 'announcements'])->name('announcements');

        // ── Reports ──
        Route::get('/reports/student-profile',         [AdminController::class, 'reportStudentProfile'])->name('reports.student-profile');
        Route::get('/reports/student-list',            [AdminController::class, 'reportStudentList'])->name('reports.student-list');
        Route::get('/reports/enrollment-summary',      [AdminController::class, 'reportEnrollmentSummary'])->name('reports.enrollment-summary');
        Route::get('/reports/graduation-list',         [AdminController::class, 'reportGraduationList'])->name('reports.graduation-list');
        Route::get('/reports/report-card',             [AdminController::class, 'reportReportCard'])->name('reports.report-card');
        Route::get('/reports/daily-attendance',        [AdminController::class, 'reportDailyAttendance'])->name('reports.daily-attendance');
        Route::get('/reports/attendance-record',       [AdminController::class, 'reportAttendanceRecord'])->name('reports.attendance-record');
        Route::get('/reports/class-record',            [AdminController::class, 'reportClassRecord'])->name('reports.class-record');
        Route::get('/reports/class-roster',            [AdminController::class, 'reportClassRoster'])->name('reports.class-roster');
        Route::get('/reports/honor-roll',              [AdminController::class, 'reportHonorRoll'])->name('reports.honor-roll');
        Route::get('/reports/clearance-summary',       [AdminController::class, 'reportClearanceSummary'])->name('reports.clearance-summary');
        Route::get('/reports/records-clearance',       [AdminController::class, 'reportRecordsClearance'])->name('reports.records-clearance');
        Route::get('/reports/library-clearance',       [AdminController::class, 'reportLibraryClearance'])->name('reports.library-clearance');
        Route::get('/reports/financial-clearance',     [AdminController::class, 'reportFinancialClearance'])->name('reports.financial-clearance');
        Route::get('/reports/class-schedule',          [AdminController::class, 'reportClassSchedule'])->name('reports.class-schedule');
        Route::get('/reports/teacher-schedule',        [AdminController::class, 'reportTeacherSchedule'])->name('reports.teacher-schedule');
        Route::get('/reports/room-schedule',           [AdminController::class, 'reportRoomSchedule'])->name('reports.room-schedule');
        Route::get('/reports/teacher-load',            [AdminController::class, 'reportTeacherLoad'])->name('reports.teacher-load');
        Route::get('/reports/teacher-list',            [AdminController::class, 'reportTeacherList'])->name('reports.teacher-list');

        // ── School Calendar ──
        Route::prefix('school-calendar')->name('school-calendar.')->group(function () {
            Route::get('/',             [SchoolCalendarController::class, 'index'])->name('index');
            Route::post('/',            [SchoolCalendarController::class, 'store'])->name('store');
            Route::get('/get-by-date',  [SchoolCalendarController::class, 'getByDate'])->name('get-by-date');
            Route::get('/download-pdf', [SchoolCalendarController::class, 'downloadPdf'])->name('download-pdf');
            Route::get('/{id}',         [SchoolCalendarController::class, 'show'])->name('show');
            Route::put('/{id}',         [SchoolCalendarController::class, 'update'])->name('update');
            Route::delete('/{id}',      [SchoolCalendarController::class, 'destroy'])->name('destroy');
        });
        Route::get('/school-calendar-page', fn() => redirect()->route('admin.school-calendar.index'))->name('school-calendar');

        // ── Settings ──
        Route::get('/settings/account',               [AdminController::class, 'settingsAccount'])->name('settings.account');
        Route::get('/settings/user-management',        [AdminController::class, 'settingsUserManagement'])->name('settings.user-management');
        Route::get('/settings/general',               [AdminController::class, 'settingsGeneral'])->name('settings.general');
    });

// ── TEACHER / STUDENT / PARENT ──────────────────────────────
Route::middleware(['auth', 'role:teacher'])->prefix('teacher')->name('teacher.')
    ->group(fn() => Route::get('/dashboard', [TeacherController::class, 'index'])->name('dashboard'));

Route::middleware(['auth', 'role:student'])->prefix('student')->name('student.')
    ->group(fn() => Route::get('/dashboard', [StudentController::class, 'index'])->name('dashboard'));

Route::middleware(['auth', 'role:parent'])->prefix('parent')->name('parent.')
    ->group(fn() => Route::get('/dashboard', [ParentController::class, 'index'])->name('dashboard'));

// ── AUTH PROFILE ─────────────────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/profile',    [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile',  [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';