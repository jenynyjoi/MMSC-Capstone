<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\SuperAdmin\SuperAdminController;
use App\Http\Controllers\Teacher\TeacherController;
use App\Http\Controllers\Student\StudentController;
use App\Http\Controllers\Parent\ParentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'role:super_admin'])->group(function () {
    Route::get('/superadmin/dashboard', [SuperAdminController::class, 'index'])->name('superadmin.dashboard');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/admission', [AdminController::class, 'admission'])->name('admin.admission');
    Route::get('/admin/enrollment', [AdminController::class, 'enrollment'])->name('admin.enrollment');
    Route::get('/admin/student-records', [AdminController::class, 'studentRecords'])->name('admin.student-records.index');
    Route::get('/admin/student-records/documents', [AdminController::class, 'studentDocuments'])->name('admin.student-records.documents');
    Route::get('/admin/clearance', [AdminController::class, 'clearance'])->name('admin.clearance');
    Route::get('/admin/academics/subjects', [AdminController::class, 'subjects'])->name('admin.academics.subjects');
    Route::get('/admin/academics/curriculum', [AdminController::class, 'curriculum'])->name('admin.academics.curriculum');
    Route::get('/admin/classes', [AdminController::class, 'classes'])->name('admin.classes');
    Route::get('/admin/schedule', [AdminController::class, 'schedule'])->name('admin.schedule');
    Route::get('/admin/teachers', [AdminController::class, 'teachers'])->name('admin.teachers');
    Route::get('/admin/announcements', [AdminController::class, 'announcements'])->name('admin.announcements');
    Route::get('/admin/reports/summary', [AdminController::class, 'reportsSummary'])->name('admin.reports.summary');
    Route::get('/admin/reports/analytics', [AdminController::class, 'reportsAnalytics'])->name('admin.reports.analytics');
    Route::get('/admin/settings/general', [AdminController::class, 'settingsGeneral'])->name('admin.settings.general');
    Route::get('/admin/settings/preferences', [AdminController::class, 'settingsPreferences'])->name('admin.settings.preferences');
});

Route::middleware(['auth', 'role:teacher'])->group(function () {
    Route::get('/teacher/dashboard', [TeacherController::class, 'index'])->name('teacher.dashboard');
});

Route::middleware(['auth', 'role:student'])->group(function () {
    Route::get('/student/dashboard', [StudentController::class, 'index'])->name('student.dashboard');
});

Route::middleware(['auth', 'role:parent'])->group(function () {
    Route::get('/parent/dashboard', [ParentController::class, 'index'])->name('parent.dashboard');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
