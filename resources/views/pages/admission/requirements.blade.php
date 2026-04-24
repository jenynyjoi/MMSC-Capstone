@extends('layouts.welcome')
@section('title', 'Admission Requirements — MMSC')
@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800;900&display=swap');
    body { font-family:'Montserrat',sans-serif; }
    .reveal { opacity:0; transform:translateY(24px); transition:opacity 0.7s ease,transform 0.7s ease; }
    .reveal.visible { opacity:1; transform:translateY(0); }
    .req-item::before { content:'✓'; color:#22c55e; font-weight:700; margin-right:8px; }
</style>

<div style="padding-top:calc(64px + 3rem); padding-bottom:3.5rem; background:linear-gradient(135deg, #0d4c8f, #0891b2);">
    <div class="max-w-4xl mx-auto px-4 text-center">
        <p style="font-size:0.7rem; font-weight:700; letter-spacing:0.2em; text-transform:uppercase; color:#7dd3fc; margin-bottom:0.5rem;">Admission</p>
        <h1 style="font-weight:800; font-size:clamp(1.8rem,4vw,2.8rem); color:#fff; margin:0 0 1rem;">Admission Requirements</h1>
        <p style="font-size:0.95rem; color:rgba(255,255,255,0.75); max-width:520px; margin:0 auto; line-height:1.75;">What you need to prepare for enrollment at MMSC</p>
    </div>
</div>

<section class="py-20 reveal" style="background:#f8fafc;">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

            @php
            $levels = [
                [
                    'level' => 'Pre-School',
                    'color' => '#f59e0b',
                    'bg'    => '#fef3c7',
                    'emoji' => '🎈',
                    'reqs'  => [
                        'PSA Birth Certificate (original & photocopy)',
                        '2x2 ID Photos (4 pieces)',
                        'Baptismal Certificate (if applicable)',
                        'Immunization / Health Record',
                        'Report Card / Nursery Certificate (for Kinder)',
                    ],
                ],
                [
                    'level' => 'Elementary (Gr. 1–6)',
                    'color' => '#22c55e',
                    'bg'    => '#dcfce7',
                    'emoji' => '📚',
                    'reqs'  => [
                        'PSA Birth Certificate (original & photocopy)',
                        'Report Card / Form 138 (previous school year)',
                        'Good Moral Certificate from previous school',
                        'Medical Certificate',
                        '2x2 ID Photos (4 pieces)',
                        'Baptismal Certificate (if applicable)',
                    ],
                ],
                [
                    'level' => 'Junior High School',
                    'color' => '#3b82f6',
                    'bg'    => '#dbeafe',
                    'emoji' => '🏫',
                    'reqs'  => [
                        'PSA Birth Certificate (original & photocopy)',
                        'Form 138 / Report Card (Grade 6)',
                        'Good Moral Certificate',
                        'Medical Certificate',
                        '2x2 ID Photos (4 pieces)',
                        'ESC Certificate / Voucher (if applicable)',
                    ],
                ],
                [
                    'level' => 'Senior High School',
                    'color' => '#a855f7',
                    'bg'    => '#f3e8ff',
                    'emoji' => '🎓',
                    'reqs'  => [
                        'PSA Birth Certificate (original & photocopy)',
                        'Form 138 / Report Card (Grade 10)',
                        'Good Moral Certificate',
                        'Medical Certificate',
                        '2x2 ID Photos (4 pieces)',
                        'SHS Voucher / ESC Certificate (if applicable)',
                        'Strand preference letter',
                    ],
                ],
                [
                    'level' => 'Transferees',
                    'color' => '#ef4444',
                    'bg'    => '#fee2e2',
                    'emoji' => '🔄',
                    'reqs'  => [
                        'Honorable Dismissal',
                        'Form 137 (request from previous school)',
                        'Latest Report Card',
                        'Good Moral Certificate',
                        'PSA Birth Certificate',
                        '2x2 ID Photos (4 pieces)',
                        'Medical Certificate',
                    ],
                ],
                [
                    'level' => 'ESC / SHS Voucher',
                    'color' => '#0891b2',
                    'bg'    => '#cffafe',
                    'emoji' => '🎟️',
                    'reqs'  => [
                        'Accomplished ESC / Voucher application form',
                        'Photocopy of valid ESC Certificate or Voucher',
                        'Public school Form 138 (Grade 6 or 10)',
                        'PSA Birth Certificate photocopy',
                        'Certificate of public school enrollment',
                    ],
                ],
            ];
            @endphp

            @foreach($levels as $item)
            <div style="background:#fff; border-radius:14px; border:1px solid #e2e8f0; overflow:hidden; box-shadow:0 2px 10px rgba(0,0,0,0.04);">
                <div style="height:4px; background:{{ $item['color'] }};"></div>
                <div style="padding:1.5rem;">
                    <div style="display:flex; align-items:center; gap:10px; margin-bottom:1rem;">
                        <div style="width:38px; height:38px; background:{{ $item['bg'] }}; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:1.1rem; flex-shrink:0;">{{ $item['emoji'] }}</div>
                        <h3 style="font-weight:800; font-size:0.9rem; color:#0c2340; margin:0;">{{ $item['level'] }}</h3>
                    </div>
                    <ul style="list-style:none; padding:0; margin:0; space-y:0.4rem;">
                        @foreach($item['reqs'] as $req)
                        <li class="req-item" style="font-size:0.82rem; color:#475569; line-height:1.7; padding:3px 0;">{{ $req }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endforeach
        </div>

        <div style="margin-top:3rem; background:#0c2340; border-radius:14px; padding:2rem 2.5rem; color:#fff; text-align:center;">
            <p style="font-size:0.75rem; font-weight:700; letter-spacing:0.15em; text-transform:uppercase; color:#55afe1; margin-bottom:0.4rem;">Ready to Enroll?</p>
            <h3 style="font-weight:800; font-size:1.2rem; margin:0 0 0.75rem;">Submit Your Application Online</h3>
            <p style="font-size:0.85rem; color:rgba(255,255,255,0.72); margin:0 0 1.5rem; line-height:1.7;">Complete our 4-step online application form. Bring original documents on your scheduled school visit.</p>
            <a href="{{ route('online.registration.step1') }}" style="display:inline-flex; align-items:center; gap:8px; background:#55afe1; color:#fff; font-weight:700; font-size:0.8rem; letter-spacing:0.1em; text-transform:uppercase; padding:13px 30px; border-radius:6px; text-decoration:none;">
                Apply Online <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </a>
        </div>
    </div>
</section>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const io = new IntersectionObserver(entries => {
        entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('visible'); io.unobserve(e.target); } });
    }, { threshold: 0.1 });
    document.querySelectorAll('.reveal').forEach(el => io.observe(el));
});
</script>
@endpush
@endsection
