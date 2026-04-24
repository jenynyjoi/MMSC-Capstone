<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains over 2000 video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the Laravel [Patreon page](https://patreon.com/taylorotwell).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Cubet Techno Labs](https://cubettech.com)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[Many](https://www.many.co.uk)**
- **[Webdock, Fast VPS Hosting](https://www.webdock.io/en)**
- **[DevSquad](https://devsquad.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[OP.GG](https://op.gg)**
- **[WebReinvent](https://webreinvent.com/?utm_source=laravel&utm_medium=github&utm_campaign=patreon-sponsors)**
- **[Lendio](https://lendio.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).




SYSTEM FLOW

PHASE 2 — ENROLLMENT / SECTION ASSIGNMENT
=========================================
FILE PLACEMENT GUIDE
=========================================

MIGRATIONS (run in order):
  database/migrations/2026_02_01_000001_create_sections_table.php
  database/migrations/2026_02_01_000002_create_student_enrollment_table.php
  database/migrations/2026_02_01_000003_create_enrollment_support_tables.php

MODELS:
  app/Models/Section.php
  app/Models/StudentEnrollment.php

CONTROLLERS:
  app/Http/Controllers/Admin/EnrollmentController.php
  app/Http/Controllers/Admin/SectionController.php

VIEWS:
  resources/views/admin/enrollment/enroll.blade.php       ← replaces old enroll.blade.php
  resources/views/admin/classes/sections.blade.php        ← replaces old sections.blade.php

ROUTES (routes/web.php):
  Add the contents of routes_to_add.php INSIDE the admin group
  Add these imports at the top:
    use App\Http\Controllers\Admin\EnrollmentController;
    use App\Http\Controllers\Admin\SectionController;

Also update Student model (app/Models/Student.php):
  The AdmissionReviewController already calls:
    StudentEnrollment::createFromApplication($application, $student);
  So import StudentEnrollment in AdmissionReviewController.

UPDATE AdmissionReviewController.php:
  Add at top: use App\Models\StudentEnrollment;
  In approveAndTransfer(), after creating student:
    StudentEnrollment::createFromApplication($application, $student);

RUN:
  php artisan migrate
  php artisan route:clear
  php artisan view:clear

=========================================
WHAT EACH FILE DOES
=========================================

EnrollmentController:
  - index()              → Shows Regular + Irregular tabs
  - getAvailableSections → AJAX: returns sections filtered by grade/track/strand
  - assignSection        → AJAX: assigns student to section, increments enrollment
  - bulkAssignPreview    → AJAX: returns section availability for bulk modal
  - bulkAssign           → AJAX: processes bulk using chosen method
  - balancingPreview     → AJAX: shows split-sections distribution preview
  - editSection          → AJAX: moves student between sections with reason

SectionController:
  - index()  → Section management list with filters + stats
  - store()  → Create new section (form POST or AJAX)
  - update() → Edit section
  - destroy()→ Delete section (blocks if students enrolled)

enroll.blade.php:
  - Regular tab: table + stat cards + filters
  - Irregular tab: table with Track/Strand columns
  - Individual Assign modal: shows section list with capacity
  - Bulk Assign modal: shows section table, insufficient slots handling
  - Balancing Preview modal: shows distribution before confirmation
  - Edit Section modal: move student with reason

sections.blade.php:
  - Section list with capacity, adviser status, availability badges
  - Add Section modal (form)
  - Edit Section modal (populated via JS)
  - Delete via AJAX with guard for enrolled students