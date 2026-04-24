<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SchoolYear;
use Illuminate\Http\Request;

class SchoolYearController extends Controller
{
    public function index()
    {
        $schoolYears = SchoolYear::orderByDesc('start_date')->get();
        return view('admin.school-year-config', compact('schoolYears'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'           => 'required|string|max:20|unique:school_years,name',
            'start_date'     => 'required|date',
            'end_date'       => 'required|date|after:start_date',
            'effective_date' => 'nullable|date',
            'class_days'     => 'required|array|min:1',
            'class_days.*'   => 'integer|between:0,6',
            'status'         => 'required|in:active,upcoming,ended',
            'description'    => 'nullable|string|max:500',
        ]);

        // Only one active school year at a time
        if ($data['status'] === 'active') {
            SchoolYear::where('status', 'active')->update(['status' => 'upcoming']);
        }

        $sy = SchoolYear::create($data);

        return response()->json([
            'success'     => true,
            'message'     => 'School year created.',
            'school_year' => $this->formatRow($sy),
        ]);
    }

    public function show($id)
    {
        return response()->json(SchoolYear::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $sy = SchoolYear::findOrFail($id);

        $data = $request->validate([
            'name'           => "required|string|max:20|unique:school_years,name,{$id}",
            'start_date'     => 'required|date',
            'end_date'       => 'required|date|after:start_date',
            'effective_date' => 'nullable|date',
            'class_days'     => 'required|array|min:1',
            'class_days.*'   => 'integer|between:0,6',
            'status'         => 'required|in:active,upcoming,ended',
            'description'    => 'nullable|string|max:500',
        ]);

        if ($data['status'] === 'active') {
            SchoolYear::where('status', 'active')->where('id', '!=', $id)->update(['status' => 'upcoming']);
        }

        $sy->update($data);

        return response()->json([
            'success'     => true,
            'message'     => 'School year updated.',
            'school_year' => $this->formatRow($sy->fresh()),
        ]);
    }

    public function destroy($id)
    {
        SchoolYear::findOrFail($id)->delete();
        return response()->json(['success' => true, 'message' => 'School year deleted.']);
    }

    private function formatRow(SchoolYear $sy): array
    {
        return [
            'id'             => $sy->id,
            'name'           => $sy->name,
            'start_date'     => $sy->start_date->format('Y-m-d'),
            'end_date'       => $sy->end_date->format('Y-m-d'),
            'effective_date' => $sy->effective_date?->format('Y-m-d'),
            'class_days'     => $sy->class_days,
            'class_days_label' => $sy->classDaysLabel(),
            'status'         => $sy->status,
            'status_badge'   => $sy->statusBadge(),
            'description'    => $sy->description,
            'start_fmt'      => $sy->start_date->format('M j, Y'),
            'end_fmt'        => $sy->end_date->format('M j, Y'),
            'eff_fmt'        => $sy->effective_date?->format('M j, Y') ?? '—',
        ];
    }
}
