<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class AdminController extends Controller
{
    // ── Dashboard ──
    public function index()
    {
        return view('admin.dashboard');
    }

    // ── Admission ──
    public function admission()
    {
        return view('admin.admission');
    }

    // ── Enrollment ──
    public function enrollment()
    {
        return view('admin.enrollment');
    }

    // ── Student Records ──
    public function studentRecords()
    {
        return view('admin.student-records.index');
    }

    public function studentDocuments()
    {
        return view('admin.student-records.documents');
    }

    // ── Clearance ──
    public function clearance()
    {
        return view('admin.clearance');
    }

    // ── Academics ──
    public function subjects()
    {
        return view('admin.academics.subjects');
    }

    public function curriculum()
    {
        return view('admin.academics.curriculum');
    }

    // ── Classes ──
    public function classes()
    {
        return view('admin.classes');
    }

    // ── Schedule ──
    public function schedule()
    {
        return view('admin.schedule');
    }

    // ── Teachers ──
    public function teachers()
    {
        return view('admin.teachers');
    }

    // ── Announcements ──
    public function announcements()
    {
        return view('admin.announcements');
    }

    // ── Reports ──
    public function reportsSummary()
    {
        return view('admin.reports.summary');
    }

    public function reportsAnalytics()
    {
        return view('admin.reports.analytics');
    }

    // ── Settings ──
    public function settingsGeneral()
    {
        return view('admin.settings.general');
    }

    public function settingsPreferences()
    {
        return view('admin.settings.preferences');
    }
}

// namespace App\Http\Controllers\Admin;

// use App\Http\Controllers\Controller;

// class AdminController extends Controller
// {
//     public function index()
//     {
//         return view('admin.dashboard');
//     }
// }