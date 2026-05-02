<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\SchoolYear;
use App\Models\Section;
use App\Models\Student;
use App\Models\SubjectAllocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class TeacherController extends Controller
{
    public function index()
    {
        $schoolYear = SchoolYear::activeName();
        $userId     = auth()->id();

        $allocations = SubjectAllocation::with(['section', 'subject'])
            ->where('teacher_id', $userId)
            ->where('school_year', $schoolYear)
            ->get();

        $sectionIds     = $allocations->pluck('section_id')->unique()->filter();
        $totalSections  = $sectionIds->count();
        $totalSubjects  = $allocations->count();
        $totalHours     = $allocations->sum('hours_per_week');
        $totalStudents  = $sectionIds->isNotEmpty()
            ? Student::where('school_year', $schoolYear)
                ->where('enrollment_status', 'enrolled')
                ->whereIn('section_id', $sectionIds)
                ->count()
            : 0;
        $recentSubjects = $allocations->take(6);
        $announcements  = Announcement::latest()->take(3)->get();

        return view('teacher.dashboard', compact(
            'totalSections', 'totalSubjects', 'totalHours', 'totalStudents',
            'recentSubjects', 'announcements', 'schoolYear'
        ));
    }

    public function mySubjects(Request $request)
    {
        $schoolYear = $request->get('school_year', SchoolYear::activeName());

        $allocations = SubjectAllocation::with(['section', 'subject'])
            ->where('teacher_id', auth()->id())
            ->where('school_year', $schoolYear)
            ->orderBy('subject_name')
            ->get();

        $bySection     = $allocations->groupBy('section_id');
        $schoolYears   = SchoolYear::orderBy('name', 'desc')->pluck('name');
        $totalHours    = $allocations->sum('hours_per_week');
        $totalSections = $bySection->count();
        $totalSubjects = $allocations->count();

        return view('teacher.my-subjects', compact(
            'bySection', 'schoolYears', 'schoolYear',
            'totalHours', 'totalSections', 'totalSubjects'
        ));
    }

    public function grades(Request $request)
    {
        $schoolYear = $request->get('school_year', SchoolYear::activeName());
        $userId     = auth()->id();

        $allocations = SubjectAllocation::with('section')
            ->where('teacher_id', $userId)
            ->where('school_year', $schoolYear)
            ->orderBy('subject_name')
            ->get();

        $schoolYears = SchoolYear::orderBy('name', 'desc')->pluck('name');

        // Build class quick-select tabs
        $classTabs = $allocations->map(fn($a) => [
            'label'   => ($a->subject_name ?? '—') . ' ' . ($a->section?->section_name ?? ''),
            'grade'   => $a->section?->grade_level ?? '7',
            'section' => $a->section?->section_name ?? 'A',
            'subject' => $a->subject_name ?? '',
        ])->values();

        // Dropdown options
        $sections = $allocations->map(fn($a) => $a->section?->section_name)->filter()->unique()->sort()->values();
        $subjects = $allocations->pluck('subject_name')->filter()->unique()->sort()->values();

        return view('teacher.grades', compact(
            'schoolYear', 'schoolYears', 'classTabs', 'sections', 'subjects'
        ));
    }

    public function attendance()
    {
        $schoolYear = SchoolYear::activeName();
        $userId     = auth()->id();

        $allocations = SubjectAllocation::with('section')
            ->where('teacher_id', $userId)
            ->where('school_year', $schoolYear)
            ->orderBy('subject_name')
            ->get();

        $classTabs = $allocations->map(fn($a) => [
            'label'   => ($a->subject_name ?? '—') . ' ' . ($a->section?->section_name ?? ''),
            'grade'   => $a->section?->grade_level ?? '7',
            'section' => $a->section?->section_name ?? 'A',
            'subject' => $a->subject_name ?? '',
        ])->values();

        $grades   = $allocations->map(fn($a) => $a->section?->grade_level)->filter()->unique()->sort()->values();
        $sections = $allocations->map(fn($a) => $a->section?->section_name)->filter()->unique()->sort()->values();
        $subjects = $allocations->pluck('subject_name')->filter()->unique()->sort()->values();

        return view('teacher.attendance', compact('classTabs', 'grades', 'sections', 'subjects'));
    }

    public function schedule()
    {
        return view('teacher.schedule');
    }

    public function announcements()
    {
        $announcements = Announcement::latest()->paginate(15);
        return view('teacher.announcements', compact('announcements'));
    }

    public function classesList()
    {
        $schoolYear = SchoolYear::activeName();
        $userId     = auth()->id();

        $allocations = SubjectAllocation::with('section')
            ->where('teacher_id', $userId)
            ->where('school_year', $schoolYear)
            ->get();

        $sections = $allocations
            ->groupBy('section_id')
            ->map(function ($group) {
                $first = $group->first();
                $sec   = $first->section;
                return [
                    'section_id'    => $first->section_id,
                    'section_name'  => $sec?->section_name ?? 'Unknown',
                    'grade_level'   => $sec?->grade_level ?? '—',
                    'program_level' => $sec?->program_level ?? 'Elem',
                    'subject_count' => $group->count(),
                ];
            })
            ->values();

        return view('teacher.classes.list', compact('sections', 'schoolYear'));
    }

    public function classesRoster(Request $request)
    {
        $schoolYear = SchoolYear::activeName();
        $sectionId  = $request->get('section');
        $section    = $sectionId ? Section::find($sectionId) : null;

        $students = Student::where('school_year', $schoolYear)
            ->where('enrollment_status', 'enrolled')
            ->when($section, fn($q) => $q->where('section_id', $sectionId))
            ->orderBy('last_name')
            ->paginate(30);

        return view('teacher.classes.roster', compact('students', 'section', 'schoolYear'));
    }

    public function settingsAccount()
    {
        return view('teacher.settings.account');
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name'          => ['required', 'string', 'max:255'],
            'email'         => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'profile_photo' => ['nullable', 'image', 'max:2048'],
        ]);

        if ($request->hasFile('profile_photo')) {
            $path = $request->file('profile_photo')->store('profile-photos', 'public');
            $user->profile_photo = $path;
        }

        $user->name  = $validated['name'];
        $user->email = $validated['email'];
        $user->save();

        return back()->with('success', 'Profile updated successfully.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password'         => ['required', 'confirmed', Password::defaults()],
        ]);

        auth()->user()->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Password updated successfully.');
    }
}
