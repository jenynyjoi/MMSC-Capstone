<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SuperAdmin\SuperAdminController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Teacher\TeacherController;
use App\Http\Controllers\Student\StudentController;
use App\Http\Controllers\Parent\ParentController;

// ── Public ──
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// ── Dashboard Redirect ──
Route::get('/login', function () {
    $user = auth()->user();

    if ($user->hasRole('super_admin')) {
        return redirect()->route('superadmin.dashboard');
    }
    if ($user->hasRole('admin')) {
        return redirect()->route('admin.dashboard');
    }
    if ($user->hasRole('teacher')) {
        return redirect()->route('teacher.dashboard');
    }
    if ($user->hasRole('student')) {
        return redirect()->route('student.dashboard');
    }
    if ($user->hasRole('parent')) {
        return redirect()->route('parent.dashboard');
    }

    return redirect('/');
})->middleware(['auth'])->name('dashboard');

// ── Super Admin ──
Route::middleware(['auth', 'role:super_admin'])
    ->prefix('superadmin')
    ->name('superadmin.')
    ->group(function () {
        Route::get('/dashboard', [SuperAdminController::class, 'index'])->name('dashboard');
    });

// ── Admin ──
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard',   [AdminController::class, 'index'])->name('dashboard');

        // Admission
        Route::get('/admission',   [AdminController::class, 'admission'])->name('admission');

        // Enrollment
        Route::get('/enrollment',  [AdminController::class, 'enrollment'])->name('enrollment');

        // Student Records
        Route::get('/student-records',           [AdminController::class, 'studentRecords'])->name('student-records.index');
        Route::get('/student-records/documents', [AdminController::class, 'studentDocuments'])->name('student-records.documents');

        // Clearance
        Route::get('/clearance',   [AdminController::class, 'clearance'])->name('clearance');

        // Academics
        Route::get('/academics/subjects',   [AdminController::class, 'subjects'])->name('academics.subjects');
        Route::get('/academics/curriculum', [AdminController::class, 'curriculum'])->name('academics.curriculum');

        // Classes
        Route::get('/classes',     [AdminController::class, 'classes'])->name('classes');

        // Schedule
        Route::get('/schedule',    [AdminController::class, 'schedule'])->name('schedule');

        // Teachers
        Route::get('/teachers',    [AdminController::class, 'teachers'])->name('teachers');

        // Announcements
        Route::get('/announcements', [AdminController::class, 'announcements'])->name('announcements');

        // Reports
        Route::get('/reports/summary',   [AdminController::class, 'reportsSummary'])->name('reports.summary');
        Route::get('/reports/analytics', [AdminController::class, 'reportsAnalytics'])->name('reports.analytics');

        // Settings
        Route::get('/settings/general',     [AdminController::class, 'settingsGeneral'])->name('settings.general');
        Route::get('/settings/preferences', [AdminController::class, 'settingsPreferences'])->name('settings.preferences');
    });

// ── Teacher ──
Route::middleware(['auth', 'role:teacher'])
    ->prefix('teacher')
    ->name('teacher.')
    ->group(function () {
        Route::get('/dashboard', [TeacherController::class, 'index'])->name('dashboard');
    });

// ── Student ──
Route::middleware(['auth', 'role:student'])
    ->prefix('student')
    ->name('student.')
    ->group(function () {
        Route::get('/dashboard', [StudentController::class, 'index'])->name('dashboard');
    });

// ── Parent ──
Route::middleware(['auth', 'role:parent'])
    ->prefix('parent')
    ->name('parent.')
    ->group(function () {
        Route::get('/dashboard', [ParentController::class, 'index'])->name('dashboard');
    });

// ── Profile ──
Route::middleware('auth')->group(function () {
    Route::get('/profile',  [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile',[ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile',[ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ── Auth Routes ──
require __DIR__.'/auth.php';


// use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\Admin\AdminController;
// use App\Http\Controllers\SuperAdmin\SuperAdminController;
// use App\Http\Controllers\Teacher\TeacherController;
// use App\Http\Controllers\Student\StudentController;
// use App\Http\Controllers\Parent\ParentController;

// // ── Public ──
// Route::get('/', function () {
//     return view('welcome');
// })->name('welcome');

// // ── Super Admin ──
// Route::middleware(['auth', 'role:super_admin'])
//     ->prefix('superadmin')
//     ->name('superadmin.')
//     ->group(function () {
//         Route::get('/dashboard', [SuperAdminController::class, 'index'])->name('dashboard');
//         Route::get('/users', [SuperAdminController::class, 'users'])->name('users');
//         Route::get('/settings', [SuperAdminController::class, 'settings'])->name('settings');
//     });

// // ── Admin ──
// Route::middleware(['auth', 'role:admin'])
//     ->prefix('admin')
//     ->name('admin.')
//     ->group(function () {
//         Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
//         Route::get('/enrollment', [AdminController::class, 'enrollment'])->name('enrollment');
//         Route::get('/students', [AdminController::class, 'students'])->name('students');
//     });

// // ── Teacher ──
// Route::middleware(['auth', 'role:teacher'])
//     ->prefix('teacher')
//     ->name('teacher.')
//     ->group(function () {
//         Route::get('/dashboard', [TeacherController::class, 'index'])->name('dashboard');
//     });

// // ── Student ──
// Route::middleware(['auth', 'role:student'])
//     ->prefix('student')
//     ->name('student.')
//     ->group(function () {
//         Route::get('/dashboard', [StudentController::class, 'index'])->name('dashboard');
//     });

// // ── Parent ──
// Route::middleware(['auth', 'role:parent'])
//     ->prefix('parent')
//     ->name('parent.')
//     ->group(function () {
//         Route::get('/dashboard', [ParentController::class, 'index'])->name('dashboard');
//     });

// ── Breeze Auth Routes (login, logout, forgot password etc.) ──
// require __DIR__.'/auth.php';