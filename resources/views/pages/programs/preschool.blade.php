@extends('layouts.welcome')
@section('title', 'Pre-School — MMSC')
@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800;900&display=swap');
    body { font-family:'Montserrat',sans-serif; }
    .reveal { opacity:0; transform:translateY(24px); transition:opacity 0.7s ease,transform 0.7s ease; }
    .reveal.visible { opacity:1; transform:translateY(0); }
</style>

<div style="padding-top:calc(64px + 3rem); padding-bottom:3.5rem; background:linear-gradient(135deg, #b45309, #f59e0b);">
    <div class="max-w-4xl mx-auto px-4 text-center">
        <p style="font-size:0.7rem; font-weight:700; letter-spacing:0.2em; text-transform:uppercase; color:#fde68a; margin-bottom:0.5rem;">Early Childhood</p>
        <h1 style="font-weight:800; font-size:clamp(1.8rem,4vw,2.8rem); color:#fff; margin:0 0 1rem;">Pre-School</h1>
        <p style="font-size:0.95rem; color:rgba(255,255,255,0.82); max-width:520px; margin:0 auto; line-height:1.75;">Nursery · Kinder 1 · Kinder 2 · A joyful start to lifelong learning</p>
    </div>
</div>

<section class="py-20 reveal" style="background:#f8fafc;">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 style="font-weight:800; font-size:1.6rem; color:#0c2340; margin:0 0 0.75rem;">Where Learning Begins with Joy</h2>
            <p style="font-size:0.9rem; color:#475569; max-width:500px; margin:0 auto; line-height:1.75;">
                MMSC's Pre-School program creates a warm, safe, and stimulating environment where young learners develop at their own pace — growing in curiosity, confidence, and faith.
            </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-14">
            @foreach([
                ['Nursery','Ages 3–4','Introduction to school life, play-based learning, and early social skills.','#fef3c7','#b45309'],
                ['Kinder 1','Age 4–5','Language, numbers, arts, and Christian values in a nurturing classroom.','#dcfce7','#15803d'],
                ['Kinder 2','Age 5–6','Readiness program aligned with DepEd standards for Grade 1 transition.','#dbeafe','#1d4ed8'],
            ] as [$level,$age,$desc,$bg,$color])
            <div style="background:#fff; border-radius:14px; border:1px solid #e2e8f0; padding:1.75rem; box-shadow:0 2px 10px rgba(0,0,0,0.05); text-align:center;">
                <div style="width:52px; height:52px; background:{{ $bg }}; border-radius:14px; display:flex; align-items:center; justify-content:center; margin:0 auto 1rem;">
                    <span style="font-size:1.5rem;">🎈</span>
                </div>
                <h3 style="font-weight:800; font-size:1rem; color:#0c2340; margin:0 0 0.25rem;">{{ $level }}</h3>
                <p style="font-size:0.75rem; font-weight:600; color:{{ $color }}; margin:0 0 0.75rem; text-transform:uppercase; letter-spacing:0.08em;">{{ $age }}</p>
                <p style="font-size:0.82rem; color:#475569; line-height:1.65; margin:0;">{{ $desc }}</p>
            </div>
            @endforeach
        </div>

        <div style="background:#fff; border-radius:14px; border:1px solid #e2e8f0; padding:2rem; display:flex; flex-wrap:wrap; align-items:center; gap:1.5rem; justify-content:space-between;">
            <div>
                <h3 style="font-weight:800; font-size:1rem; color:#0c2340; margin:0 0 0.4rem;">Ready to Enroll Your Child?</h3>
                <p style="font-size:0.85rem; color:#64748b; margin:0;">Contact us for enrollment schedules, tuition information, and open house dates.</p>
            </div>
            <div style="display:flex; gap:10px; flex-wrap:wrap;">
                <a href="{{ route('online.registration.step1') }}" style="display:inline-flex; align-items:center; gap:8px; background:#0d4c8f; color:#fff; font-weight:700; font-size:0.8rem; letter-spacing:0.08em; text-transform:uppercase; padding:12px 22px; border-radius:6px; text-decoration:none;">
                    Apply Now
                </a>
                <a href="{{ route('contact') }}" style="display:inline-flex; align-items:center; gap:8px; border:2px solid #0d4c8f; color:#0d4c8f; font-weight:700; font-size:0.8rem; letter-spacing:0.08em; text-transform:uppercase; padding:10px 22px; border-radius:6px; text-decoration:none;">
                    Contact Us
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
