<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\OnlineRegistrationController;
use App\Http\Controllers\SuperAdmin\SuperAdminController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdmissionReviewController;
use App\Http\Controllers\Admin\SchoolCalendarController;
use App\Http\Controllers\Admin\SchoolYearController;
use App\Http\Controllers\Admin\EnrollController;
use App\Http\Controllers\Admin\SectionController;
use App\Http\Controllers\Admin\StudentRecordController;
use App\Http\Controllers\Admin\BehavioralRecordController;
use App\Http\Controllers\Admin\AcademicController;
use App\Http\Controllers\Admin\ClassScheduleController;
use App\Http\Controllers\Admin\TeacherController as AdminTeacherController;
use App\Http\Controllers\Admin\AnnouncementController;
use App\Http\Controllers\Admin\SearchController;
use App\Http\Controllers\Admin\FinanceController;
use App\Http\Controllers\Admin\ClassroomController;
use App\Http\Controllers\Teacher\TeacherController;
use App\Http\Controllers\Student\StudentController;
use App\Http\Controllers\Parent\ParentController;

// ── PUBLIC ──────────────────────────────────────────────────
Route::get('/', function () { return view('welcome'); })->name('welcome');

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
    Route::get('/confirmation/{ref}', [OnlineRegistrationController::class, 'confirmation'])->name('confirmation');
    Route::get('/download-pdf/{ref}', [OnlineRegistrationController::class, 'downloadPdf'])->name('download-pdf');
});

Route::get('/admission', fn() => redirect()->route('online.registration.step1'))->name('online.registration');

// ── PUBLIC PAGES ─────────────────────────────────────────────
Route::get('/about',                              fn() => view('pages.about'))->name('about');
Route::get('/contact',                            fn() => view('pages.contact'))->name('contact');
Route::get('/programs/senior-high-school',        fn() => view('pages.programs.shs'))->name('programs.shs');
Route::get('/programs/junior-high-school',        fn() => view('pages.programs.jhs'))->name('programs.jhs');
Route::get('/programs/elementary',                fn() => view('pages.programs.elementary'))->name('programs.elementary');
Route::get('/programs/pre-school',                fn() => view('pages.programs.preschool'))->name('programs.preschool');
Route::get('/admission/requirements',             fn() => view('pages.admission.requirements'))->name('admission.requirements');
Route::get('/admission/enrollment-process',       fn() => view('pages.admission.enrollment-process'))->name('admission.enrollment-process');

// ── SUPER ADMIN ──────────────────────────────────────────────
Route::middleware(['auth', 'role:super_admin'])
    ->prefix('superadmin')->name('superadmin.')
    ->group(function () {
        Route::get('/dashboard', [SuperAdminController::class, 'index'])->name('dashboard');
    });

// ── ADMIN ─────────────────────────────────────────────────────
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')->name('admin.')
    ->group(function () {

        Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
        Route::get('/search',   [SearchController::class, 'search'])->name('search');

        // ── Admission ────────────────────────────────────────
        Route::get('/admission',                      [AdmissionReviewController::class, 'index'])->name('admission');
        Route::get('/admission/{id}',                 [AdmissionReviewController::class, 'show'])->name('admission.show');
        Route::get('/admission/{id}/pdf',             [AdmissionReviewController::class, 'downloadPdf'])->name('admission.pdf');
        Route::get('/admission/{id}/document/{type}', [AdmissionReviewController::class, 'downloadDocument'])
            ->name('admission.document')
            ->where('type', 'psa|report_card|good_moral');
        Route::get('/admission/{id}/documents-data',  [AdmissionReviewController::class, 'getDocumentsData'])->name('admission.documents-data');
        Route::put('/admission/{id}/status',          [AdmissionReviewController::class, 'updateStatus'])->name('admission.status');
        Route::post('/admission/{id}/documents',      [AdmissionReviewController::class, 'updateDocuments'])->name('admission.documents');
        Route::post('/admission/bulk-status',                     [AdmissionReviewController::class, 'bulkUpdateStatus'])->name('admission.bulk-status');
        Route::post('/admission/send-notice',                     [AdmissionReviewController::class, 'sendNotice'])->name('admission.send-notice');
        Route::post('/admission/export',                          [AdmissionReviewController::class, 'exportExcel'])->name('admission.export');
        Route::patch('/admission/{id}/finance-clearance',         [AdmissionReviewController::class, 'updateFinanceClearance'])->name('admission.finance-clearance');

        // ── Enrollment ───────────────────────────────────────
        Route::get('/enrollment/enroll',             [EnrollController::class, 'index'])->name('enrollment.enroll');
        Route::get('/enrollment/promote',            [EnrollController::class, 'promote'])->name('enrollment.promote');
        Route::post('/enrollment/promote/single',    [EnrollController::class, 'promoteSingle'])->name('enrollment.promote.single');
        Route::post('/enrollment/promote/bulk',      [EnrollController::class, 'promoteBulk'])->name('enrollment.promote.bulk');
        Route::get('/enrollment/sections',           [EnrollController::class, 'getAvailableSections'])->name('enrollment.sections');
        Route::post('/enrollment/assign',            [EnrollController::class, 'assignSection'])->name('enrollment.assign');
        Route::post('/enrollment/edit-section',      [EnrollController::class, 'editSection'])->name('enrollment.edit-section');
        Route::post('/enrollment/bulk-preview',      [EnrollController::class, 'bulkAssignPreview'])->name('enrollment.bulk-preview');
        Route::post('/enrollment/bulk-assign',       [EnrollController::class, 'bulkAssign'])->name('enrollment.bulk-assign');
        Route::post('/enrollment/balancing-preview', [EnrollController::class, 'balancingPreview'])->name('enrollment.balancing-preview');
        Route::post('/enrollment/direct-enroll',     [EnrollController::class, 'directEnroll'])->name('enrollment.direct-enroll');

        // ── Classes / Section Management ─────────────────────
        Route::get('/classes/list',             [AdminController::class, 'classesList'])->name('classes.list');
        Route::get('/classes/list/{id}',        [AdminController::class, 'classShow'])->name('classes.list.show');
        Route::get('/classes/rosters',                [AdminController::class, 'classRosters'])->name('classes.rosters');
        Route::get('/classes/rosters/students',       [AdminController::class, 'classRosterStudents'])->name('classes.rosters.students');
        Route::get('/classes/rosters/export-excel',   [AdminController::class, 'classRosterExportExcel'])->name('classes.rosters.export-excel');
        Route::get('/classes/rosters/export-pdf',     [AdminController::class, 'classRosterExportPdf'])->name('classes.rosters.export-pdf');
        Route::get('/classes/classrooms',            [ClassroomController::class, 'index'])->name('classes.classrooms');
        Route::post('/classes/classrooms',           [ClassroomController::class, 'store'])->name('classes.classrooms.store');
        Route::put('/classes/classrooms/{classroom}', [ClassroomController::class, 'update'])->name('classes.classrooms.update');
        Route::delete('/classes/classrooms/{classroom}', [ClassroomController::class, 'destroy'])->name('classes.classrooms.destroy');
        Route::get('/classes/classrooms-list',       [ClassroomController::class, 'apiList'])->name('classes.classrooms.list');
        Route::get('/classes/sections',         [SectionController::class, 'index'])->name('classes.sections');
        Route::post('/classes/sections',        [SectionController::class, 'store'])->name('classes.sections.store');
        Route::get('/classes/sections/{id}',    [SectionController::class, 'show'])->name('classes.sections.show');
        Route::put('/classes/sections/{id}',    [SectionController::class, 'update'])->name('classes.sections.update');
        Route::delete('/classes/sections/{id}', [SectionController::class, 'destroy'])->name('classes.sections.destroy');

        // ── Student Records ───────────────────────────────────
        Route::get('/student-records/list',           [StudentRecordController::class, 'index'])->name('student-records.list');
        Route::get('/student-records/profile/{id}',   [StudentRecordController::class, 'show'])->name('student-records.profile');
        Route::patch('/student-records/profile/{id}', [StudentRecordController::class, 'updateProfile'])->name('student-records.update-profile');
        Route::get('/student-records/withdrawn',      [StudentRecordController::class, 'withdrawn'])->name('student-records.withdrawn');
        Route::get('/student-records/archives',        [StudentRecordController::class, 'archives'])->name('student-records.archives');
        Route::post('/student-records/archive',          [StudentRecordController::class, 'archive'])->name('student-records.archive');
        Route::post('/student-records/archive/restore', [StudentRecordController::class, 'restoreArchive'])->name('student-records.archive.restore');
        Route::post('/student-records/withdraw',       [StudentRecordController::class, 'withdraw'])->name('student-records.withdraw');
        Route::post('/student-records/send-notice',   [StudentRecordController::class, 'sendNotice'])->name('student-records.send-notice');
        Route::post('/student-records/export',        [StudentRecordController::class, 'export'])->name('student-records.export');

        // ── Behavioral Records ───────────────────────────────────
        Route::get('/student-records/behavioral',              [BehavioralRecordController::class, 'index'])->name('student-records.behavioral');
        Route::post('/student-records/behavioral',             [BehavioralRecordController::class, 'store'])->name('student-records.behavioral.store');
        Route::get('/student-records/behavioral/{id}',         [BehavioralRecordController::class, 'show'])->name('student-records.behavioral.show');
        Route::put('/student-records/behavioral/{id}',         [BehavioralRecordController::class, 'update'])->name('student-records.behavioral.update');
        Route::delete('/student-records/behavioral/{id}',      [BehavioralRecordController::class, 'destroy'])->name('student-records.behavioral.destroy');
        Route::patch('/student-records/behavioral/{id}/status',[BehavioralRecordController::class, 'updateStatus'])->name('student-records.behavioral.status');
        Route::post('/student-records/behavioral/{id}/upload', [BehavioralRecordController::class, 'uploadDoc'])->name('student-records.behavioral.upload');
        Route::get('/student-records/behavioral/document/{id}/download',[BehavioralRecordController::class, 'downloadDoc'])->name('admin.behavioral.document.download');
        Route::delete('/student-records/behavioral/document/{id}',      [BehavioralRecordController::class, 'deleteDoc'])->name('student-records.behavioral.document.delete');
        Route::post('/student-records/behavioral/send-notice', [BehavioralRecordController::class, 'sendNotice'])->name('student-records.behavioral.send-notice');
        Route::get('/student-records/behavioral/student-info', [BehavioralRecordController::class, 'getStudentInfo'])->name('student-records.behavioral.student-info');

        // ── Academics ─────────────────────────────────────────────
        Route::get('/academic/subjects',                        [AcademicController::class, 'index'])->name('academic.subjects');
        Route::post('/academic/subjects',                       [AcademicController::class, 'storeSubject'])->name('academic.subjects.store');
        Route::get('/academic/subjects/{id}',                   [AcademicController::class, 'showSubject'])->name('academic.subjects.show');
        Route::put('/academic/subjects/{id}',                   [AcademicController::class, 'updateSubject'])->name('academic.subjects.update');
        Route::delete('/academic/subjects/{id}',                [AcademicController::class, 'destroySubject'])->name('academic.subjects.destroy');
        Route::get('/academic/allocation/config',               [AcademicController::class, 'getAllocationConfig'])->name('academic.allocation.config');
        Route::post('/academic/allocation/set-required',        [AcademicController::class, 'setAllocationRequired'])->name('academic.allocation.set-required');
        Route::get('/academic/allocation/subjects-for-section', [AcademicController::class, 'getSubjectsForSection'])->name('academic.allocation.subjects-for-section');
        Route::get('/academic/allocation/teachers-for-section', [AcademicController::class, 'getTeachersForAllocation'])->name('academic.allocation.teachers-for-section');
        Route::post('/academic/allocation',                     [AcademicController::class, 'storeAllocation'])->name('academic.allocation.store');
        Route::put('/academic/allocation/{id}',                 [AcademicController::class, 'updateAllocation'])->name('academic.allocation.update');
        Route::delete('/academic/allocation/{id}',              [AcademicController::class, 'destroyAllocation'])->name('academic.allocation.destroy');
        Route::get('/academic/curriculum/grades',              [AcademicController::class, 'getCurriculumGrades'])->name('academic.curriculum.grades');
        Route::get('/academic/curriculum/detail',              [AcademicController::class, 'getCurriculumDetail'])->name('academic.curriculum.detail');
        Route::post('/academic/curriculum',                    [AcademicController::class, 'saveCurriculum'])->name('academic.curriculum.save');
        Route::put('/academic/curriculum/subject/{id}',        [AcademicController::class, 'updateCurriculumSubject'])->name('academic.curriculum.subject.update');
        Route::delete('/academic/curriculum/subject/{id}',     [AcademicController::class, 'removeCurriculumSubject'])->name('academic.curriculum.subject.remove');
        Route::get('/academic/section-subjects-teachers',       [AcademicController::class, 'getSectionSubjectsWithTeachers'])->name('academic.section-subjects-teachers');
        Route::post('/academic/assign-teacher',                 [AcademicController::class, 'assignTeacher'])->name('academic.assign-teacher');
        Route::post('/academic/components',                     [AcademicController::class, 'storeComponent'])->name('academic.components.store');
        Route::put('/academic/components/{id}',                 [AcademicController::class, 'updateComponent'])->name('academic.components.update');
        Route::delete('/academic/components/{id}',              [AcademicController::class, 'destroyComponent'])->name('academic.components.destroy');
        Route::post('/academic/assessments',                    [AcademicController::class, 'storeAssessment'])->name('academic.assessments.store');
        Route::put('/academic/assessments/{id}',                [AcademicController::class, 'updateAssessment'])->name('academic.assessments.update');
        Route::delete('/academic/assessments/{id}',             [AcademicController::class, 'destroyAssessment'])->name('academic.assessments.destroy');

        // ── Finance ───────────────────────────────────────────
        Route::post('/finance/configure',             [FinanceController::class, 'store'])->name('finance.store');
        Route::get('/finance/for-application',        [FinanceController::class, 'getForApplication'])->name('finance.for-application');
        Route::get('/finance/for-student',            [FinanceController::class, 'getForStudent'])->name('finance.for-student');
        Route::post('/finance/record-payment',        [FinanceController::class, 'recordPayment'])->name('finance.record-payment');
        Route::get('/finance/details',                [FinanceController::class, 'getDetails'])->name('finance.details');
        Route::get('/finance/receipt',                [FinanceController::class, 'getReceipt'])->name('finance.receipt');
        Route::post('/finance/send-reminder',         [FinanceController::class, 'sendReminder'])->name('finance.send-reminder');

        // ── Clearance ─────────────────────────────────────────
        Route::get('/clearance/academic-standing', [AdminController::class, 'clearanceAcademicStanding'])->name('clearance.academic-standing');
        Route::get('/clearance/finance',           [AdminController::class, 'clearanceFinance'])->name('clearance.finance');
        Route::get('/clearance/library',              [AdminController::class, 'clearanceLibrary'])->name('clearance.library');
        Route::post('/clearance/library/{id}/book',   [AdminController::class, 'addLibraryBook'])->name('clearance.library.add-book');
        Route::patch('/clearance/library/{id}/return',[AdminController::class, 'returnLibraryBook'])->name('clearance.library.return-book');
        Route::patch('/clearance/library/{id}/status',[AdminController::class, 'updateLibraryStatus'])->name('clearance.library.update-status');
        Route::get('/clearance/records',           [AdminController::class, 'clearanceRecords'])->name('clearance.records');
        Route::get('/clearance/behavioral',        [AdminController::class, 'clearanceBehavioral'])->name('clearance.behavioral');
        Route::get('/clearance/property',          [AdminController::class, 'clearanceProperty'])->name('clearance.property');
        Route::get('/clearance/summary',                   [AdminController::class, 'clearanceSummary'])->name('clearance.summary');
        Route::patch('/clearance/summary/{id}/mark-all',   [AdminController::class, 'markAllCleared'])->name('clearance.summary.mark-all');
        Route::patch('/clearance/behavioral/{id}', [AdminController::class, 'updateBehavioralClearance'])->name('clearance.behavioral.update');
        Route::patch('/clearance/property/{id}',     [AdminController::class, 'updatePropertyClearance'])->name('clearance.property.update');
        Route::post('/clearance/property/{id}/issue',[AdminController::class, 'issuePropertyItems'])->name('clearance.property.issue');
        Route::patch('/clearance/finance/{id}',    [AdminController::class, 'updateFinanceClearance'])->name('clearance.finance.update');

        // ── Academics ─────────────────────────────────────────
        Route::get('/academic/attendance', [AdminController::class, 'academicAttendance'])->name('academic.attendance');
        Route::get('/academic/grades',     [AdminController::class, 'academicGrades'])->name('academic.grades');

        // ── Schedule ──────────────────────────────────────────
        Route::get('/schedule/class',                       [ClassScheduleController::class, 'index'])->name('schedule.class');
        Route::get('/schedule/class/grid-data',             [ClassScheduleController::class, 'gridData'])->name('schedule.class.grid-data');
        Route::post('/schedule/class/auto-assign',          [ClassScheduleController::class, 'autoAssign'])->name('schedule.class.auto-assign');
        Route::post('/schedule/class/schedule',             [ClassScheduleController::class, 'storeSchedule'])->name('schedule.class.store');
        Route::put('/schedule/class/schedule/{id}',         [ClassScheduleController::class, 'updateSchedule'])->name('schedule.class.update');
        Route::delete('/schedule/class/schedule/{id}',      [ClassScheduleController::class, 'deleteSchedule'])->name('schedule.class.delete');
        Route::get('/schedule/class/setups',                [ClassScheduleController::class, 'getSetups'])->name('schedule.class.setups');
        Route::post('/schedule/class/setups',               [ClassScheduleController::class, 'saveSetup'])->name('schedule.class.setups.save');
        Route::delete('/schedule/class/setups/{id}',        [ClassScheduleController::class, 'deleteSetup'])->name('schedule.class.setups.delete');
        Route::get('/schedule/teacher', [AdminController::class, 'scheduleTeacher'])->name('schedule.teacher');
        Route::get('/schedule/room',    [AdminController::class, 'scheduleRoom'])->name('schedule.room');

        // ── Teachers ──────────────────────────────────────────────
        Route::get('/teachers',                        [AdminTeacherController::class, 'index'])->name('teachers');
        Route::post('/teachers',                       [AdminTeacherController::class, 'store'])->name('teachers.store');
        Route::get('/teachers/{id}',                   [AdminTeacherController::class, 'show'])->name('teachers.show');
        Route::put('/teachers/{id}',                   [AdminTeacherController::class, 'update'])->name('teachers.update');
        Route::delete('/teachers/{id}',                [AdminTeacherController::class, 'destroy'])->name('teachers.destroy');
        Route::get('/schedule/section-schedule',       [AdminTeacherController::class, 'sectionSchedule'])->name('schedule.section');
        Route::get('/schedule/teacher-schedule',       [AdminTeacherController::class, 'teacherSchedule'])->name('schedule.teacher');
        Route::get('/schedule/room-schedule',          [AdminTeacherController::class, 'roomSchedule'])->name('schedule.room');
        // ── Announcements ─────────────────────────────────────
        Route::get('/announcements',         [AnnouncementController::class, 'index'])->name('announcements');
        Route::post('/announcements',        [AnnouncementController::class, 'store'])->name('announcements.store');
        Route::put('/announcements/{id}',    [AnnouncementController::class, 'update'])->name('announcements.update');
        Route::delete('/announcements/{id}', [AnnouncementController::class, 'destroy'])->name('announcements.destroy');

        // ── Reports ───────────────────────────────────────────
        Route::get('/reports/student-profile',    [AdminController::class, 'reportStudentProfile'])->name('reports.student-profile');
        Route::get('/reports/student-list',       [AdminController::class, 'reportStudentList'])->name('reports.student-list');
        Route::get('/reports/enrollment-summary', [AdminController::class, 'reportEnrollmentSummary'])->name('reports.enrollment-summary');
        Route::get('/reports/graduation-list',    [AdminController::class, 'reportGraduationList'])->name('reports.graduation-list');
        Route::get('/reports/report-card',        [AdminController::class, 'reportReportCard'])->name('reports.report-card');
        Route::get('/reports/daily-attendance',   [AdminController::class, 'reportDailyAttendance'])->name('reports.daily-attendance');
        Route::get('/reports/attendance-record',  [AdminController::class, 'reportAttendanceRecord'])->name('reports.attendance-record');
        Route::get('/reports/class-record',       [AdminController::class, 'reportClassRecord'])->name('reports.class-record');
        Route::get('/reports/class-roster',       [AdminController::class, 'reportClassRoster'])->name('reports.class-roster');
        Route::get('/reports/honor-roll',         [AdminController::class, 'reportHonorRoll'])->name('reports.honor-roll');
        Route::get('/reports/clearance-summary',  [AdminController::class, 'reportClearanceSummary'])->name('reports.clearance-summary');
        Route::get('/reports/records-clearance',  [AdminController::class, 'reportRecordsClearance'])->name('reports.records-clearance');
        Route::get('/reports/library-clearance',  [AdminController::class, 'reportLibraryClearance'])->name('reports.library-clearance');
        Route::get('/reports/financial-clearance',[AdminController::class, 'reportFinancialClearance'])->name('reports.financial-clearance');
        Route::get('/reports/class-schedule',     [AdminController::class, 'reportClassSchedule'])->name('reports.class-schedule');
        Route::get('/reports/teacher-schedule',   [AdminController::class, 'reportTeacherSchedule'])->name('reports.teacher-schedule');
        Route::get('/reports/room-schedule',      [AdminController::class, 'reportRoomSchedule'])->name('reports.room-schedule');
        Route::get('/reports/teacher-load',       [AdminController::class, 'reportTeacherLoad'])->name('reports.teacher-load');
        Route::get('/reports/teacher-list',       [AdminController::class, 'reportTeacherList'])->name('reports.teacher-list');

        // ── School Calendar ───────────────────────────────────
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

        // ── School Year Configuration ─────────────────────────
        Route::prefix('school-year-config')->name('school-year-config.')->group(function () {
            Route::get('/',       [SchoolYearController::class, 'index'])->name('index');
            Route::post('/',      [SchoolYearController::class, 'store'])->name('store');
            Route::get('/{id}',   [SchoolYearController::class, 'show'])->name('show');
            Route::put('/{id}',   [SchoolYearController::class, 'update'])->name('update');
            Route::delete('/{id}',[SchoolYearController::class, 'destroy'])->name('destroy');
        });

        // ── Settings ──────────────────────────────────────────
        Route::get('/settings/account',              [AdminController::class, 'settingsAccount'])->name('settings.account');
        Route::patch('/settings/account/profile',    [AdminController::class, 'updateProfile'])->name('settings.account.profile');
        Route::patch('/settings/account/password',   [AdminController::class, 'updatePassword'])->name('settings.account.password');
        Route::post('/settings/account/photo',       [AdminController::class, 'updateProfilePhoto'])->name('settings.account.photo');
        Route::get('/settings/user-management', [AdminController::class, 'settingsUserManagement'])->name('settings.user-management');
        Route::get('/settings/general',          [AdminController::class, 'settingsGeneral'])->name('settings.general');
        Route::post('/settings/general',         [AdminController::class, 'saveGeneralSettings'])->name('settings.general.save');
    });

// ── OTHER ROLES ───────────────────────────────────────────────
Route::middleware(['auth', 'role:teacher'])->prefix('teacher')->name('teacher.')
    ->group(function () {
        Route::get('/dashboard',          [TeacherController::class, 'index'])->name('dashboard');
        Route::get('/my-subjects',        [TeacherController::class, 'mySubjects'])->name('my-subjects');
        Route::get('/grades',             [TeacherController::class, 'grades'])->name('grades');
        Route::get('/attendance',         [TeacherController::class, 'attendance'])->name('attendance');
        Route::get('/schedule',           [TeacherController::class, 'schedule'])->name('schedule');
        Route::get('/announcements',      [TeacherController::class, 'announcements'])->name('announcements');
        Route::get('/classes/list',       [TeacherController::class, 'classesList'])->name('classes.list');
        Route::get('/classes/roster',     [TeacherController::class, 'classesRoster'])->name('classes.roster');
        Route::get('/settings/account',   [TeacherController::class, 'settingsAccount'])->name('settings.account');
        Route::patch('/settings/account', [TeacherController::class, 'updateProfile'])->name('settings.account.update');
        Route::patch('/settings/password',[TeacherController::class, 'updatePassword'])->name('settings.account.password');
    });

Route::middleware(['auth', 'role:student'])->prefix('student')->name('student.')
    ->group(fn() => Route::get('/dashboard', [StudentController::class, 'index'])->name('dashboard'));

Route::middleware(['auth', 'role:parent'])->prefix('parent')->name('parent.')
    ->group(fn() => Route::get('/dashboard', [ParentController::class, 'index'])->name('dashboard'));

// ── AUTH PROFILE ──────────────────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/profile',    [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile',  [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';