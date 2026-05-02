# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

**MMSC** is a Laravel 10 school management system for K-12 institutions. It handles the full student lifecycle: online admission → admin review → section assignment → enrollment → academics, behavioral records, and clearance/graduation.

Roles: `super_admin`, `admin`, `teacher`, `student`, `parent` (managed via Spatie Laravel Permission).

## Commands

```bash
# Setup
composer install && npm install
cp .env.example .env && php artisan key:generate
php artisan migrate

# Development
php artisan serve          # http://localhost:8000
npm run dev                # Vite asset watcher

# Build
npm run build

# Testing
php artisan test
php artisan test tests/Feature
php artisan test tests/Unit

# Linting / formatting
./vendor/bin/pint

# Common cache clears
php artisan route:clear && php artisan view:clear && php artisan config:clear
```

## Architecture

### Request Flow
```
Public routes → OnlineRegistrationController (multi-step form, PDF)
Auth routes   → Laravel Breeze
Admin routes  → middleware(['auth', 'role:admin']) → /app/Http/Controllers/Admin/
```

### Key Modules & Controllers

| Module | Controller(s) | Purpose |
|--------|--------------|---------|
| Online Admission | `OnlineRegistrationController` | 4-step public registration form; generates PDF |
| Admission Review | `AdmissionReviewController` | Admin reviews apps, changes status, creates student accounts on approval |
| Enrollment | `EnrollController`, `EnrollmentController` | Assigns enrolled students to sections (regular vs. SHS-irregular tracks) |
| Sections | `SectionController` | CRUD for class sections; capacity tracking; homeroom adviser assignment |
| Student Records | `StudentRecordController` | Profile, withdrawal, archives |
| Behavioral Records | `BehavioralRecordController` | Incidents, severity, parent notifications, document attachments |
| Academics | `AcademicController` | Subjects, section subject allocation, teacher assignment, grade components, assessments |
| School Calendar | `SchoolCalendarController` | Events CRUD + PDF export |
| Clearance | `AdminController` | Multi-department clearance per student |

### Core Models & Relationships

```
User (Spatie roles)
Application → Student (created on approval via Student::createFromApplication())
Student → StudentEnrollment (per school year) → Section
Section → SubjectAllocation → Subject + Teacher
SubjectAllocation → SubjectSchedule (day/time/room)
SubjectAllocation → GradeComponent → Assessment
Student → BehavioralRecord → BehavioralDocument
```

### Student Type Classification (Critical)

```
Irregular = SHS student with irregular enrollment type ONLY
Regular   = All Elementary, JHS, and SHS-Regular students

This determines which tab appears in the enrollment UI and which enrollment path is taken.
```


### Section Availability States

| State | Condition |
|-------|-----------|
| `available` | < 20 enrolled AND > 5 slots free |
| `below_minimum` | < 20 enrolled |
| `near_capacity` | ≤ 5 slots remaining |
| `full` | At capacity |

### Route Structure

```
/                          → public landing
/registration              → multi-step admission form (Steps 1–4)
/admin/*                   → admin panel (middleware: auth + role:admin)
  /dashboard
  /admission               → application review
  /enrollment/enroll        → section assignment (AJAX-heavy)
  /classes/sections         → section CRUD
  /student-records/*        → student profiles, withdrawn, archives
  /academic/*               → subjects, allocation, assessments
  /school-calendar          → events + PDF export
  /clearance/*              → departmental clearances
/teacher/*                 → teacher portal
/student/*                 → student portal
/parent/*                  → parent portal
```

### Frontend Stack

- **Blade** templates with **Tailwind CSS 3** and **Alpine.js 3**
- **Chart.js 4** for dashboard visualizations
- **Vite** as asset bundler
- Enrollment screens use AJAX endpoints for dynamic section loading and assignment

### PDF & Excel Generation

- PDFs: `barryvdh/laravel-dompdf` (school calendar, application forms, rosters)
- Excel: `phpoffice/phpspreadsheet` (student lists, roster exports)

### Database Notes

- Multi-year data is scoped by `school_year` field (single institution, multiple school years)
- All student movements (section changes, withdrawals) have audit trails
- Key composite uniques: `student_id + school_year` in `student_enrollment`; `section_id + subject_id + school_year` in `subject_allocation`

## Environment

Key `.env` values to configure:
- `DB_DATABASE` — default is `laravel`, change to your local DB name
- `MAIL_*` — defaults to Mailpit for local dev; email is used for application status notifications
- `APP_URL` — set correctly for PDF/file URL generation

---

## Session Notes (April 2026)

### New Modules Added

#### School Calendar
- **`SchoolCalendarController`** — full CRUD for `school_calendar_events` table; `store` uses `updateOrCreate(['school_year','date'])` so one entry per day per SY.
- **`SchoolCalendarEvent` model** — helpers: `badgeClass()` (Tailwind classes), `dayTypeLabel()` (human string). Day types: `regular`, `holiday`, `suspended`, `early_dismissal`, `exam_day`, `school_event`, `break`.
- **Dashboard sync** — `AdminController::index()` loads events via `SchoolYear::activeName()` and passes `$calEvents` as a grouped array (one array of events per date key `YYYY-M-D`) to `admin.dashboard`. The dashboard JS consumes `ev.badge_class` (not `ev.color`).
- **Calendar JS quirks**: All show/hide on the calendar modal uses `style.display` (not `classList.add/remove('hidden')`) to avoid Tailwind class-order conflicts. The form-submit checks `pickerRow.style.display !== 'none'` (not `classList.contains`).
- **Routes**: prefix `school-calendar`, name group `admin.school-calendar.*` — `store` (POST `/`), `update` (PUT `/{id}`), `destroy` (DELETE `/{id}`), `show` (GET `/{id}`), `get-by-date` (GET), `download-pdf` (GET).

#### School Year Configuration
- **`SchoolYear` model** — `school_years` table: `name` (unique, e.g. `2025-2026`), `start_date`, `end_date`, `effective_date`, `class_days` (JSON int array, 0=Sun…6=Sat), `status` (active/upcoming/ended), `description`. Only one `active` at a time — controller auto-demotes others on save.
- **`SchoolYear::activeName()`** — static helper used by `SchoolCalendarController` and `AdminController` to resolve the current school year. Fallback: `'2025-2026'`.
- **`SchoolYearController`** — CRUD at `/admin/school-year-config`, name group `admin.school-year-config.*`.
- **Sidebar** — "School Calendar" is now a dropdown with sub-items: Calendar + School Year Config.
- **Calendar page** — school year selector dropdown (populated from `SchoolYear` table) at top-right; switching reloads the page with `?school_year=`.

#### School Calendar PDF (`admin.school-calendar-pdf`)
- Full-year PDF (July → June, 12 months), DepEd-style two-column table.
- Only **major events** shown in activity list: `holiday`, `exam_day`, `school_event`, `break`. Minor types (`suspended`, `early_dismissal`, `regular`) are excluded from the activity table but still reflected in the mini-calendar.
- exclude the default regular day on calendar
- school calendar and calendar widget in dashboard must be sync
- Months with no major events are **skipped** entirely (`@continue`).
- Activity column uses a nested table: colored type badge | bullet title.
- Mini-calendar highlights event days (`#0d4c8f` fill), crosses out no-class days (`✗`), dims weekends.
- **Controller** builds `$allEvents` as a plain PHP array (`$allEvents[$key][]= $e`) grouped by `"YYYY-M"` key — avoids Laravel Collection issues inside DomPDF rendering.
- Uses `@continue` (Blade directive) — not PHP `continue` — inside `@foreach`.

### Key Bug Fixes

| Bug | Fix |
|-----|-----|
| Modal not closing after save | `closeModal()` called first; render calls wrapped in `try/catch` |
| Date not selected when clicking calendar day | Form-submit checked `classList.contains('hidden')` on date-picker row after switch to `style.display` — changed to `style.display !== 'none'` |
| Dashboard events not showing | `AdminController` was querying wrong school year (`2026-2027`); fixed to use `SchoolYear::activeName()` |
| PDF events empty | `groupBy` returned Collection of Collections; replaced with plain PHP array + `@continue` |
| `calEvents` format dashboard vs calendar | Dashboard uses `groupBy` → array per date; calendar page uses `mapWithKeys` → single object per date. They are **different formats** — do not conflate. |

### DomPDF Notes
- `enable_remote` set to `true` in `config/dompdf.php` so local image paths (`public_path(...)`) resolve.
- Use `@page { margin: ... }` for document margins.
- Avoid Laravel Collection methods inside PDF Blade views — convert to plain PHP arrays in the controller first.
- `@continue` / `@break` Blade directives work; PHP `continue` inside `@php` blocks within `@foreach` is unreliable.

### Sidebar Patterns
- Show/hide uses `style.display` throughout (not Tailwind `hidden` class) to avoid `hidden`+`flex` conflict.
- Active sub-item: `border-l-2 border-[#0d4c8f] text-[#0d4c8f]`, no background.
- Container line: `ml-8 pl-3 border-l-2 border-slate-200`.
- Sub-item offset: `-ml-[13px] pl-[11px]` so the per-item border overlaps the container line.

---

## Session Notes (April–May 2026)

### Admission Approval Pipeline

- **Portal accounts created on approval** (not deferred to enrollment). `approveAndTransfer()` in `AdmissionReviewController` now runs all 5 steps:
  1. Create `Student` record via `Student::createFromApplication()`
  2. Link finance config
  3. Create `StudentEnrollment` (queues student for section assignment)
  4. Create student `User` account (role: `student`) — email = school email, password = reference number
  5. Create parent/guardian `User` account (role: `parent`) — email = `guardian_email`
- Both portal creations are **idempotent**: guarded by `portal_account_created` flag and `User::where('email')->exists()`.
- `EnrollController::createPortalAccountsIfNeeded()` remains as a safe no-op backstop.
- Approval emails use different copy than enrollment emails ("application approved" vs "officially enrolled").

### Class Schedule Grid — Key Fixes & Rules

#### Grid Slot Boundary Matching
- `SubjectSchedule.time_start` is stored as `HH:MM:SS` (MySQL TIME). `generateSlots()` produces `HH:MM` slot starts.
- After a break period, slot boundaries shift (e.g. break `10:00–10:30` → next slot starts at `10:30`). A schedule stored at `12:00` falls inside slot `11:30–12:30`, not at a slot start.
- **Fix**: `gridData()` uses range matching — `substr(time_start,0,5) >= slot.start && < slot.end` — instead of exact equality.

#### Teacher-less Subject Blocking
- Subjects with no `teacher_id` in `subject_allocation` **cannot** be scheduled (manual or auto-assign).
- Blocked at three layers: UI (amber warning + excluded from dropdown), AJAX modal (`availableAllocations` getter filters them), server-side (422 guard in `storeSchedule()` and `autoAssign()`).

#### Auto-Assign Algorithm (Day-Centric Fill)
- **Strategy**: day → slot → allocation (not allocation → day → slot).
- For each day, sorts allocations by remaining-need descending, then fills each slot with the highest-priority subject that has no conflict and hasn't appeared on that day yet.
- Result: days fill from start-to-end with no gaps between placed subjects.
- `$remaining[$alloc->id]` tracks meetings still to place; collected outside the DB transaction for the skipped report.
- Teacher-less allocations are pre-rejected into `$skipped` before the transaction.

#### Conflict Detection (`detectConflicts()`)
Checks in order:
1. Section overlap (error)
2. Teacher overlap — message now names the conflicting subject + section + exact time + all busy times on that day (error)
3. Room overlap (warning)
4. Time window / break overlap (warning)
5. Meetings-per-week limit (error)
6. **Same subject same day** (error) — suggests available days
7. Duration mismatch (warning)

#### Client-Side Same-Day Warning
- `sameDayWarning` Alpine computed getter scans the live grid to detect if the selected allocation already appears on the selected day — no server round-trip.
- Shows inline red banner under the day buttons with available-day suggestions.
- Server-side check still blocks the save as final guard.

### Teacher Name Format
- Teacher names follow the same format as students: `LASTNAME, FIRSTNAME M.I.` (all caps).
- `TeacherProfile` model has `boot()` that auto-uppercases `first_name`, `last_name`, `middle_name` on save (mirrors `Student` model).
- `TeacherProfile::getFormattedNameAttribute()` computes the display name from stored fields — all existing teachers show the correct format without a DB migration.
- `TeacherController::store()` and `update()` both build `$fullName` in the formatted pattern and store it to `User.name`.
- `show()` API endpoint returns `formatted_name` (not `User.name`) so the JS detail/edit panels are always correct.
- Teachers page list uses `$tp->formatted_name` (not `$tp->user->name`).

### Subject Management — Subject Specializations in Teacher Modal
- The "Subject Specializations" checkboxes in the Add/Edit Teacher modal are **dynamically populated** from the `subjects` table (`is_active = true`, ordered by name).
- `TeacherController::index()` queries `Subject::where('is_active', true)->orderBy('subject_name')->pluck('subject_name')` and passes it as `$subjectSpecializations`.
- The filter dropdown on the teacher list page also uses `$subjectSpecializations` (both were previously hardcoded arrays).
- Empty-state message shown if no subjects exist yet.

### Subject View Details Modal
- "View Details" button added as the first option in each subject row's action dropdown (solar:eye-bold indigo icon).
- Opens `#subject-detail-modal` — a read-only panel showing: subject code/name, active/inactive badge, description, classification grid (department, type, grade, program), SHS track/strand (hidden if not SHS), schedule config stat cards (hrs/meeting · meetings/wk · hrs/wk), semester info.
- `viewSubjectDetails(id)` JS function fetches from the existing `showSubject` AJAX endpoint.
- "Edit Subject" button in the modal footer closes the detail and opens the edit form.
- Description and SHS/semester sections are conditionally hidden when empty/not applicable.
