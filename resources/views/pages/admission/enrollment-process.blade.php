@extends('layouts.welcome')
@section('title', 'Enrollment Process — MMSC')
@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800;900&display=swap');
    body { font-family:'Montserrat',sans-serif; }
    .reveal { opacity:0; transform:translateY(24px); transition:opacity 0.7s ease,transform 0.7s ease; }
    .reveal.visible { opacity:1; transform:translateY(0); }
</style>

<div style="padding-top:calc(64px + 3rem); padding-bottom:3.5rem; background:linear-gradient(135deg, #0d4c8f, #059669);">
    <div class="max-w-4xl mx-auto px-4 text-center">
        <p style="font-size:0.7rem; font-weight:700; letter-spacing:0.2em; text-transform:uppercase; color:#6ee7b7; margin-bottom:0.5rem;">Admission</p>
        <h1 style="font-weight:800; font-size:clamp(1.8rem,4vw,2.8rem); color:#fff; margin:0 0 1rem;">Enrollment Process</h1>
        <p style="font-size:0.95rem; color:rgba(255,255,255,0.75); max-width:520px; margin:0 auto; line-height:1.75;">Simple steps to become part of the MMSC family</p>
    </div>
</div>

<section class="py-20 reveal" style="background:#f8fafc;">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="space-y-6">
            @php
            $steps = [
                ['step'=>'01','title'=>'Online Application','desc'=>'Fill out the 4-step online application form with your personal details, parent/guardian information, academic background, and required documents. You will receive a reference number upon submission.','color'=>'#0d4c8f','icon'=>'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
                ['step'=>'02','title'=>'Document Submission','desc'=>'Present original and photocopied documents to the Registrar\'s Office during your scheduled visit. The registrar will verify your academic records, good moral certificate, and PSA birth certificate.','color'=>'#059669','icon'=>'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4'],
                ['step'=>'03','title'=>'Entrance Assessment','desc'=>'New students may undergo a short academic readiness assessment appropriate to their level. This helps us place students in the right section and provide any necessary academic support.','color'=>'#7c3aed','icon'=>'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z'],
                ['step'=>'04','title'=>'Payment of Fees','desc'=>'Settle the enrollment fees at the Finance Office. Students availing ESC Grants or SHS Vouchers should present their valid voucher certificates for the corresponding deduction. Installment plans are available.','color'=>'#0891b2','icon'=>'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z'],
                ['step'=>'05','title'=>'Section Assignment','desc'=>'The Registrar assigns enrolled students to their respective grade levels and sections. Section assignments are based on grade level, enrollment type, and available slots. You will be notified of your section before the school year begins.','color'=>'#ea580c','icon'=>'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0'],
                ['step'=>'06','title'=>'Welcome to MMSC!','desc'=>'Once fully enrolled, students receive their class schedule, handbook, and school ID. Attend the Orientation Day to meet your teachers, classmates, and learn about school policies. Welcome to the MMSC family!','color'=>'#16a34a','icon'=>'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
            ];
            @endphp

            @foreach($steps as $i => $step)
            <div style="display:flex; gap:1.25rem; align-items:flex-start; background:#fff; border-radius:14px; padding:1.75rem; border:1px solid #e2e8f0; box-shadow:0 2px 8px rgba(0,0,0,0.04);">
                <div style="width:48px; height:48px; background:{{ $step['color'] }}; border-radius:12px; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                    <svg width="22" height="22" fill="none" stroke="#fff" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                        <path d="{{ $step['icon'] }}"/>
                    </svg>
                </div>
                <div style="flex:1;">
                    <div style="display:flex; align-items:center; gap:10px; margin-bottom:0.5rem;">
                        <span style="font-size:0.65rem; font-weight:800; color:{{ $step['color'] }}; letter-spacing:0.12em; text-transform:uppercase;">Step {{ $step['step'] }}</span>
                        <h3 style="font-weight:800; font-size:0.95rem; color:#0c2340; margin:0;">{{ $step['title'] }}</h3>
                    </div>
                    <p style="font-size:0.85rem; color:#475569; line-height:1.75; margin:0;">{{ $step['desc'] }}</p>
                </div>
            </div>
            @endforeach
        </div>

        <div style="margin-top:3rem; text-align:center;">
            <p style="font-size:0.85rem; color:#64748b; margin-bottom:1.5rem;">Questions about enrollment? Contact our Registrar's Office directly.</p>
            <div style="display:flex; gap:12px; justify-content:center; flex-wrap:wrap;">
                <a href="{{ route('online.registration.step1') }}" style="display:inline-flex; align-items:center; gap:8px; background:#0d4c8f; color:#fff; font-weight:700; font-size:0.8rem; letter-spacing:0.1em; text-transform:uppercase; padding:13px 28px; border-radius:6px; text-decoration:none;">
                    Start Application <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                </a>
                <a href="{{ route('contact') }}" style="display:inline-flex; align-items:center; gap:8px; border:2px solid #0d4c8f; color:#0d4c8f; font-weight:700; font-size:0.8rem; letter-spacing:0.1em; text-transform:uppercase; padding:11px 26px; border-radius:6px; text-decoration:none;">
                    Contact Registrar
                </a>
            </div>
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
