@extends('layouts.welcome')
@section('title', 'Elementary — MMSC')
@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800;900&display=swap');
    body { font-family:'Montserrat',sans-serif; }
    .reveal { opacity:0; transform:translateY(24px); transition:opacity 0.7s ease,transform 0.7s ease; }
    .reveal.visible { opacity:1; transform:translateY(0); }
</style>

<div style="padding-top:calc(64px + 3rem); padding-bottom:3.5rem; background:linear-gradient(135deg, #065f46, #059669);">
    <div class="max-w-4xl mx-auto px-4 text-center">
        <p style="font-size:0.7rem; font-weight:700; letter-spacing:0.2em; text-transform:uppercase; color:#6ee7b7; margin-bottom:0.5rem;">Primary Education</p>
        <h1 style="font-weight:800; font-size:clamp(1.8rem,4vw,2.8rem); color:#fff; margin:0 0 1rem;">Elementary</h1>
        <p style="font-size:0.95rem; color:rgba(255,255,255,0.75); max-width:520px; margin:0 auto; line-height:1.75;">Grades 1–6 · Nurturing young minds with strong academic foundations</p>
    </div>
</div>

<section class="py-20 reveal" style="background:#f8fafc;">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-start mb-16">
            <div>
                <h2 style="font-weight:800; font-size:1.6rem; color:#0c2340; margin:0 0 1rem;">Program Overview</h2>
                <p style="font-size:0.9rem; color:#475569; line-height:1.85; margin-bottom:1rem;">
                    Our Elementary program (Grades 1–6) lays the essential groundwork for lifelong learning. We blend core academic subjects with Christian values, creative development, and character formation.
                </p>
                <p style="font-size:0.9rem; color:#475569; line-height:1.85;">
                    Small class sizes ensure that every learner receives personal attention, with teachers who are committed to making learning joyful, meaningful, and spiritually grounded.
                </p>
            </div>
            <div class="grid grid-cols-2 gap-4">
                @foreach([
                    ['📖','Language Arts','English, Filipino reading & writing'],
                    ['🔢','Mathematics','Number sense through problem solving'],
                    ['🌍','Science & Health','Exploring the natural world'],
                    ['🙏','Christian Living','Faith & values integrated daily'],
                    ['🎭','MAPEH','Arts, Music, PE & Health'],
                    ['🖥️','Computer Ed.','Digital literacy from early grades'],
                ] as [$icon,$title,$desc])
                <div style="background:#fff; border-radius:12px; padding:1.25rem; border:1px solid #e2e8f0; box-shadow:0 1px 6px rgba(0,0,0,0.04);">
                    <span style="font-size:1.4rem; display:block; margin-bottom:0.4rem;">{{ $icon }}</span>
                    <p style="font-weight:700; font-size:0.82rem; color:#0c2340; margin:0 0 4px;">{{ $title }}</p>
                    <p style="font-size:0.75rem; color:#64748b; margin:0; line-height:1.5;">{{ $desc }}</p>
                </div>
                @endforeach
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
