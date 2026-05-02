<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\SchoolYear;
use App\Models\SubjectAllocation;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    public function index()
    {
        return view('teacher.dashboard');
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
}