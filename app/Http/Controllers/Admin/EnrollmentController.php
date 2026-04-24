<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;

use App\Models\Section;
use App\Models\StudentEnrollment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class EnrollmentController extends Controller
{
    // ══════════════════════════════════════════════════════════
    // INDEX — Pending Section Assignment
    //
    // Shows all students who were approved in Admission (i.e.
    // portal_account_created = true / enrolled_at is set) but
    // have NOT yet been assigned to a section (section_name IS NULL).
    //
    // The AdmissionReviewController::approveAndTransfer() creates
    // the Student record when an application is approved, so we
    // simply query the students table for records with no section.
    // ══════════════════════════════════════════════════════════
    public function index(Request $request)
    {
        $query = Student::query()
            // Only students whose enrollment has been activated (approved from Admission)
            ->whereNotNull('enrolled_at');

        // ── Optional: show only un-assigned by default (toggle via ?assign_status=) ──
        if ($request->filled('assign_status')) {
            if ($request->assign_status === 'pending') {
                $query->whereNull('section_name');
            } elseif ($request->assign_status === 'assigned') {
                $query->whereNotNull('section_name');
            }
        }
        // Default view: show all pending (no section) if no status filter
        if (!$request->filled('assign_status')) {
            // Show ALL (pending + assigned) so admin has full context
            // Remove the whereNull filter below if you want ONLY pending by default
        }

        if ($request->filled('level')) {
            $query->where('applied_level', $request->level);
        }

        if ($request->filled('student_type')) {
            $query->where('student_category', $request->student_type);
        }

        if ($request->filled('school_year')) {
            $query->where('school_year', $request->school_year);
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(fn($q) => $q
                ->where('student_id',  'like', "%$s%")
                ->orWhere('first_name', 'like', "%$s%")
                ->orWhere('last_name',  'like', "%$s%")
                ->orWhereRaw("CONCAT(first_name,' ',last_name) LIKE ?", ["%$s%"])
            );
        }

        $students = $query->latest('enrolled_at')->paginate(10)->withQueryString();

        // Stat counts for the dashboard cards
        $pendingCount     = Student::whereNotNull('enrolled_at')->whereNull('section_name')->count();
        $totalEnrolled    = Student::whereNotNull('enrolled_at')->count();
        $totalSections    = 5;   // Replace with Section::count() when you have a sections table
        $availableSections = 3;  // Replace with Section::where('is_full', false)->count()

        return view('admin.enrollment.enroll', compact(
            'students',
            'pendingCount',
            'totalEnrolled',
            'totalSections',
            'availableSections'
        ));
    }

    // ══════════════════════════════════════════════════════════
    // ASSIGN SECTION — PATCH /admin/enrollment/assign/{student}
    // ══════════════════════════════════════════════════════════
    public function assignSection(Request $request, int $id)
    {
        $request->validate([
            'section_name' => 'required|string|max:100',
        ]);

        $student = Student::findOrFail($id);
        $student->update(['section_name' => $request->section_name]);

        return back()->with('success', "{$student->full_name} has been assigned to {$request->section_name}.");
    }
}


