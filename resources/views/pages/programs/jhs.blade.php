@extends('layouts.welcome')
@section('title', 'Junior High School — MMSC')
@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800;900&display=swap');
    body { font-family:'Montserrat',sans-serif; }
    .reveal { opacity:0; transform:translateY(24px); transition:opacity 0.7s ease,transform 0.7s ease; }
    .reveal.visible { opacity:1; transform:translateY(0); }
</style>

<div style="padding-top:calc(64px + 3rem); padding-bottom:3.5rem; background:linear-gradient(135deg, #1e3a8a, #0891b2);">
    <div class="max-w-4xl mx-auto px-4 text-center">
        <p style="font-size:0.7rem; font-weight:700; letter-spacing:0.2em; text-transform:uppercase; color:#93c5fd; margin-bottom:0.5rem;">Lower Secondary</p>
        <h1 style="font-weight:800; font-size:clamp(1.8rem,4vw,2.8rem); color:#fff; margin:0 0 1rem;">Junior High School</h1>
        <p style="font-size:0.95rem; color:rgba(255,255,255,0.75); max-width:520px; margin:0 auto; line-height:1.75;">Grades 7–10 · Building critical thinkers and future leaders</p>
    </div>
</div>

<section class="py-20 reveal" style="background:#f8fafc;">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-start mb-16">
            <div>
                <h2 style="font-weight:800; font-size:1.6rem; color:#0c2340; margin:0 0 1rem;">Program Overview</h2>
                <p style="font-size:0.9rem; color:#475569; line-height:1.85; margin-bottom:1rem;">
                    MMSC's Junior High School program covers Grades 7–10 under the K–12 curriculum, preparing students with strong foundations in core subjects while developing leadership, creativity, and Christian character.
                </p>
                <p style="font-size:0.9rem; color:#475569; line-height:1.85;">
                    Our JHS students benefit from well-rounded academics, co-curricular activities, and mentorship from dedicated, licensed teachers committed to each learner's holistic growth.
                </p>
            </div>
            <div class="grid grid-cols-2 gap-4">
                @foreach([
                    ['📚','Core Subjects','Mathematics, Science, English, Filipino & more'],
                    ['🎨','MAPEH','Music, Arts, Physical Education & Health'],
                    ['💻','TLE','Technology & Livelihood Education'],
                    ['🙏','Values Ed.','Christian Living & Character Formation'],
                ] as [$icon,$title,$desc])
                <div style="background:#fff; border-radius:12px; padding:1.25rem; border:1px solid #e2e8f0; box-shadow:0 1px 6px rgba(0,0,0,0.04);">
                    <span style="font-size:1.5rem; display:block; margin-bottom:0.5rem;">{{ $icon }}</span>
                    <p style="font-weight:700; font-size:0.82rem; color:#0c2340; margin:0 0 4px;">{{ $title }}</p>
                    <p style="font-size:0.75rem; color:#64748b; margin:0; line-height:1.5;">{{ $desc }}</p>
                </div>
                @endforeach
            </div>
        </div>

        {{-- ESC Grant Info --}}
        <div style="background:linear-gradient(to right, #1e3a8a, #0891b2); border-radius:16px; padding:2rem 2.5rem; color:#fff; margin-bottom:2.5rem;">
            <div style="display:flex; flex-wrap:wrap; align-items:center; justify-content:space-between; gap:1.5rem;">
                <div>
                    <p style="font-size:0.7rem; font-weight:700; letter-spacing:0.15em; text-transform:uppercase; color:#93c5fd; margin-bottom:0.3rem;">DepEd Program</p>
                    <h3 style="font-weight:800; font-size:1.1rem; margin:0 0 0.5rem;">ESC Grant Available</h3>
                    <p style="font-size:0.85rem; color:rgba(255,255,255,0.8); margin:0; max-width:420px; line-height:1.6;">
                        Grade 6 completers from public elementary schools may qualify for the Education Service Contracting (ESC) grant when enrolling in MMSC's Grade 7.
                    </p>
                </div>
                <a href="{{ route('admission.requirements') }}" style="display:inline-flex; align-items:center; gap:8px; background:#fff; color:#1e3a8a; font-weight:700; font-size:0.8rem; padding:12px 24px; border-radius:8px; text-decoration:none; white-space:nowrap;">
                    Check Requirements <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                </a>
            </div>
        </div>

        <div class="text-center">
            <a href="{{ route('online.registration.step1') }}" style="display:inline-flex; align-items:center; gap:8px; background:#0d4c8f; color:#fff; font-weight:700; font-size:0.8rem; letter-spacing:0.1em; text-transform:uppercase; padding:13px 30px; border-radius:6px; text-decoration:none;">
                Apply Now <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
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
