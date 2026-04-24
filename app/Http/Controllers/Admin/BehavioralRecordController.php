<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BehavioralDocument;
use App\Models\BehavioralRecord;
use App\Models\Student;
use App\Models\StudentEnrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class BehavioralRecordController extends Controller
{
    // ══════════════════════════════════════════════════════
    // INDEX — list all behavioral records
    // ══════════════════════════════════════════════════════
    public function index(Request $request)
    {
        $schoolYear    = $request->get('school_year', \App\Models\SchoolYear::activeName());
        $gradeSection  = $request->get('grade_section');
        $behaviorType  = $request->get('behavior_type');
        $status        = $request->get('status');
        $search        = $request->get('search');

        $query = BehavioralRecord::with(['student', 'documents', 'recorder', 'enrollment'])
            ->where('school_year', $schoolYear)
            ->when($behaviorType,  fn($q) => $q->where('behavior_type', $behaviorType))
            ->when($status,        fn($q) => $q->where('status', $status))
            ->when($gradeSection,  fn($q) => $q->where('grade_level', $gradeSection))
            ->when($search, fn($q) => $q->whereHas('student', fn($sq) =>
                $sq->where('first_name', 'like', "%$search%")
                   ->orWhere('last_name', 'like', "%$search%")
                   ->orWhere('student_id', 'like', "%$search%")
            ))
            ->orderByDesc('incident_date');

        $records = $query->paginate(10)->withQueryString();

        // Stats
        $stats = [
            'total'     => BehavioralRecord::where('school_year', $schoolYear)->count(),
            'pending'   => BehavioralRecord::where(['school_year' => $schoolYear, 'status' => 'pending'])->count(),
            'resolved'  => BehavioralRecord::where(['school_year' => $schoolYear, 'status' => 'resolved'])->count(),
            'escalated' => BehavioralRecord::where(['school_year' => $schoolYear, 'status' => 'escalated'])->count(),
        ];

        // Students for search dropdown in Add modal
        $students = Student::where('school_year', $schoolYear)
            ->where('student_status', 'active')
            ->orderBy('last_name')
            ->get(['id', 'student_id', 'first_name', 'last_name', 'grade_level']);

        return view('admin.student-records.behavioral', compact('records', 'stats', 'students', 'schoolYear'));
    }

    // ══════════════════════════════════════════════════════
    // STORE — create new behavioral record
    // ══════════════════════════════════════════════════════
    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id'    => 'required|exists:students,id',
            'incident_date' => 'required|date',
            'behavior_type' => 'required|string|max:100',
            'severity'      => 'required|in:Minor,Moderate,Major,Critical',
            'action_taken'  => 'required|string|max:100',
            'action_details'=> 'nullable|string|max:500',
            'referral_to'   => 'nullable|string|max:100',
            'description'   => 'required|string',
            'status'        => 'required|in:pending,resolved,dismissed,escalated',
            'document'      => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
        ]);

        $student    = Student::findOrFail($validated['student_id']);
        $enrollment = StudentEnrollment::where('student_id', $student->id)
            ->where('school_year', request()->get('school_year', \App\Models\SchoolYear::activeName()))
            ->first();

        $record = BehavioralRecord::create([
            'student_id'    => $student->id,
            'enrollment_id' => $enrollment?->id,
            'school_year'   => $request->get('school_year', \App\Models\SchoolYear::activeName()),
            'grade_level'   => $student->grade_level,
            'section_name'  => $enrollment?->section_name ?? '—',
            'incident_date' => $validated['incident_date'],
            'behavior_type' => $validated['behavior_type'],
            'severity'      => $validated['severity'],
            'action_taken'  => $validated['action_taken'],
            'action_details'=> $validated['action_details'],
            'referral_to'   => $validated['referral_to'],
            'description'   => $validated['description'],
            'status'        => $validated['status'],
            'recorded_by'   => auth()->id(),
        ]);

        // Handle document upload
        if ($request->hasFile('document')) {
            $this->uploadDocument($record, $request->file('document'), $request->get('document_description'));
        }

        DB::table('audit_log')->insert([
            'student_id'      => $student->id,
            'action'          => 'behavioral_record_created',
            'action_type'     => 'create',
            'action_category' => 'behavioral',
            'new_value'       => json_encode(['behavior_type' => $record->behavior_type, 'severity' => $record->severity]),
            'performed_by'    => auth()->id(),
            'performed_at'    => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Behavioral record created successfully.',
            'record'  => $record->load('student'),
        ]);
    }

    // ══════════════════════════════════════════════════════
    // SHOW — single record details (AJAX)
    // ══════════════════════════════════════════════════════
    public function show(int $id)
    {
        $record = BehavioralRecord::with(['student', 'documents', 'recorder'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'record'  => [
                'id'             => $record->id,
                'student_id'     => $record->student->student_id ?? '—',
                'student_name'   => $record->student->full_name ?? '—',
                'grade'          => $record->grade_level . ($record->section_name !== '—' ? ' - ' . $record->section_name : ''),
                'school_year'    => $record->school_year,
                'incident_date'  => $record->incident_date?->format('F d, Y'),
                'incident_date_raw' => $record->incident_date?->format('Y-m-d'),
                'behavior_type'  => $record->behavior_type,
                'severity'       => $record->severity,
                'action_taken'   => $record->action_taken,
                'action_details' => $record->action_details,
                'referral_to'    => $record->referral_to ?? '—',
                'description'    => $record->description,
                'resolution_notes'=> $record->resolution_notes,
                'status'         => $record->status,
                'parent_notified'=> $record->parent_notified ? 'Yes' : 'No',
                'recorded_by'    => $record->recorder?->name ?? 'Admin',
                'created_at'     => $record->created_at?->format('F d, Y g:i A'),
                'updated_at'     => $record->updated_at?->format('F d, Y g:i A'),
                'documents'      => $record->documents->map(fn($d) => [
                    'id'          => $d->id,
                    'file_name'   => $d->file_name,
                    'file_size'   => $d->file_size_formatted,
                    'description' => $d->description,
                    'created_at'  => $d->created_at?->format('M d, Y'),
                    'download_url'=> route('admin.behavioral.document.download', $d->id),
                ]),
            ],
        ]);
    }

    // ══════════════════════════════════════════════════════
    // UPDATE — edit behavioral record
    // ══════════════════════════════════════════════════════
    public function update(Request $request, int $id)
    {
        $record = BehavioralRecord::findOrFail($id);

        $validated = $request->validate([
            'incident_date'    => 'required|date',
            'behavior_type'    => 'required|string|max:100',
            'severity'         => 'required|in:Minor,Moderate,Major,Critical',
            'action_taken'     => 'required|string|max:100',
            'action_details'   => 'nullable|string|max:500',
            'referral_to'      => 'nullable|string|max:100',
            'description'      => 'required|string',
            'resolution_notes' => 'nullable|string',
            'status'           => 'required|in:pending,resolved,dismissed,escalated',
            'document'         => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
        ]);

        $record->update(array_merge($validated, ['updated_by' => auth()->id()]));

        if ($request->hasFile('document')) {
            $this->uploadDocument($record, $request->file('document'), $request->get('document_description'));
        }

        DB::table('audit_log')->insert([
            'student_id'      => $record->student_id,
            'action'          => 'behavioral_record_updated',
            'action_type'     => 'update',
            'action_category' => 'behavioral',
            'new_value'       => json_encode(['status' => $record->status, 'severity' => $record->severity]),
            'performed_by'    => auth()->id(),
            'performed_at'    => now(),
        ]);

        return response()->json(['success' => true, 'message' => 'Record updated successfully.']);
    }

    // ══════════════════════════════════════════════════════
    // UPDATE STATUS — quick status change
    // ══════════════════════════════════════════════════════
    public function updateStatus(Request $request, int $id)
    {
        $request->validate([
            'status'           => 'required|in:pending,resolved,dismissed,escalated',
            'resolution_notes' => 'nullable|string',
            'notify_parent'    => 'boolean',
        ]);

        $record = BehavioralRecord::findOrFail($id);
        $record->update([
            'status'           => $request->status,
            'resolution_notes' => $request->resolution_notes,
            'updated_by'       => auth()->id(),
        ]);

        if ($request->notify_parent) {
            $record->update(['parent_notified' => true, 'parent_notified_at' => now()]);
            // TODO: dispatch notification
        }

        DB::table('audit_log')->insert([
            'student_id'      => $record->student_id,
            'action'          => 'behavioral_status_updated',
            'action_type'     => 'update',
            'action_category' => 'behavioral',
            'new_value'       => json_encode(['status' => $request->status]),
            'performed_by'    => auth()->id(),
            'performed_at'    => now(),
        ]);

        return response()->json(['success' => true, 'message' => 'Status updated to ' . ucfirst($request->status) . '.']);
    }

    // ══════════════════════════════════════════════════════
    // DESTROY — delete record
    // ══════════════════════════════════════════════════════
    public function destroy(int $id)
    {
        $record = BehavioralRecord::findOrFail($id);

        // Delete associated documents from storage
        foreach ($record->documents as $doc) {
            Storage::delete($doc->file_path);
        }

        $record->delete();

        return response()->json(['success' => true, 'message' => 'Record deleted successfully.']);
    }

    // ══════════════════════════════════════════════════════
    // UPLOAD DOCUMENT
    // ══════════════════════════════════════════════════════
    public function uploadDoc(Request $request, int $id)
    {
        $request->validate([
            'document'    => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
            'description' => 'nullable|string|max:255',
        ]);

        $record = BehavioralRecord::findOrFail($id);
        $doc    = $this->uploadDocument($record, $request->file('document'), $request->description);

        return response()->json([
            'success'  => true,
            'message'  => 'Document uploaded successfully.',
            'document' => [
                'id'          => $doc->id,
                'file_name'   => $doc->file_name,
                'file_size'   => $doc->file_size_formatted,
                'description' => $doc->description,
                'created_at'  => $doc->created_at?->format('M d, Y'),
                'download_url'=> route('admin.behavioral.document.download', $doc->id),
            ],
        ]);
    }

    // ══════════════════════════════════════════════════════
    // DOWNLOAD DOCUMENT
    // ══════════════════════════════════════════════════════
    public function downloadDoc(int $docId)
    {
        $doc = BehavioralDocument::findOrFail($docId);
        return Storage::download($doc->file_path, $doc->file_name);
    }

    // ══════════════════════════════════════════════════════
    // DELETE DOCUMENT
    // ══════════════════════════════════════════════════════
    public function deleteDoc(int $docId)
    {
        $doc = BehavioralDocument::findOrFail($docId);
        Storage::delete($doc->file_path);
        $doc->delete();
        return response()->json(['success' => true, 'message' => 'Document deleted.']);
    }

    // ══════════════════════════════════════════════════════
    // SEND NOTICE (single or bulk)
    // ══════════════════════════════════════════════════════
    public function sendNotice(Request $request)
    {
        $request->validate([
            'record_ids'  => 'required|array',
            'notice_type' => 'required|string',
            'subject'     => 'required|string|max:255',
            'message'     => 'required|string',
            'send_to'     => 'required|array',
        ]);

        $records = BehavioralRecord::with('student')
            ->whereIn('id', $request->record_ids)->get();
        $sent    = 0;

        foreach ($records as $record) {
            $student = $record->student;
            if (!$student) continue;

            if (in_array('student', $request->send_to) && $student->personal_email) {
                DB::table('assignment_notifications')->insert([
                    'enrollment_id'     => null,
                    'student_id'        => $student->id,
                    'notification_type' => $request->notice_type,
                    'recipient_email'   => $student->personal_email,
                    'recipient_type'    => 'student',
                    'email_subject'     => $request->subject,
                    'email_body'        => $request->message,
                    'status'            => 'pending',
                    'queued_at'         => now(),
                ]);
                $sent++;
            }
            if (in_array('parent', $request->send_to) && $student->guardian_email) {
                DB::table('assignment_notifications')->insert([
                    'enrollment_id'     => null,
                    'student_id'        => $student->id,
                    'notification_type' => $request->notice_type,
                    'recipient_email'   => $student->guardian_email,
                    'recipient_type'    => 'parent',
                    'email_subject'     => $request->subject,
                    'email_body'        => $request->message,
                    'status'            => 'pending',
                    'queued_at'         => now(),
                ]);
                $sent++;
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Notice queued for ' . $records->count() . ' record(s). ' . $sent . ' email(s) will be sent.',
        ]);
    }

    // ══════════════════════════════════════════════════════
    // GET STUDENT INFO for Add modal (AJAX)
    // ══════════════════════════════════════════════════════
    public function getStudentInfo(Request $request)
    {
        $student    = Student::findOrFail($request->student_id);
        $enrollment = StudentEnrollment::where('student_id', $student->id)
            ->where('school_year', $request->get('school_year', \App\Models\SchoolYear::activeName()))
            ->first();

        return response()->json([
            'student_id'   => $student->student_id,
            'full_name'    => $student->full_name,
            'grade'        => $student->grade_level . ($enrollment?->section_name ? ' - ' . $enrollment->section_name : ''),
            'guardian_email' => $student->guardian_email,
            'personal_email' => $student->personal_email,
        ]);
    }

    // ══════════════════════════════════════════════════════
    // PRIVATE HELPER — upload document
    // ══════════════════════════════════════════════════════
    private function uploadDocument(BehavioralRecord $record, $file, ?string $description = null): BehavioralDocument
    {
        $path = $file->store('behavioral_documents/' . $record->id, 'public');

        return BehavioralDocument::create([
            'behavioral_record_id' => $record->id,
            'file_name'            => $file->getClientOriginalName(),
            'file_path'            => $path,
            'file_type'            => $file->getClientOriginalExtension(),
            'file_size'            => $file->getSize(),
            'description'          => $description,
            'uploaded_by'          => auth()->id(),
        ]);
    }
}