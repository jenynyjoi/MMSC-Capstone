@extends('layouts.welcome')
@section('title', 'About Us — My Messiah School of Cavite')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800;900&display=swap');
    body { font-family: 'Montserrat', sans-serif; }
    .page-hero { padding-top: 64px; }
    .reveal { opacity: 0; transform: translateY(24px); transition: opacity 0.7s ease, transform 0.7s ease; }
    .reveal.visible { opacity: 1; transform: translateY(0); }
    .mv-card { border-top: 4px solid #55afe1; background: #fff; border-radius: 12px; padding: 2rem 1.75rem; box-shadow: 0 2px 16px rgba(12,78,146,0.07); }
</style>

{{-- Hero --}}
<div class="page-hero" style="background:linear-gradient(to bottom, #0d4c8f, #041629); padding-bottom:3.5rem; padding-top:calc(64px + 3rem);">
    <div class="max-w-4xl mx-auto px-4 text-center">
        <p style="font-size:0.7rem; font-weight:700; letter-spacing:0.2em; text-transform:uppercase; color:#55afe1; margin-bottom:0.5rem;">Who We Are</p>
        <h1 style="font-weight:800; font-size:clamp(1.8rem,4vw,2.8rem); color:#fff; margin:0 0 1rem;">About MMSC</h1>
        <p style="font-size:0.95rem; color:rgba(255,255,255,0.72); max-width:520px; margin:0 auto; line-height:1.75;">
            A Christ-centered school committed to holistic education in the heart of Cavite since 1998.
        </p>
    </div>
</div>

{{-- About Content --}}
<section class="py-20 reveal" style="background:#f1f5f9;">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
            <div>
                <p style="font-size:0.68rem; font-weight:700; letter-spacing:0.22em; text-transform:uppercase; color:#55afe1; margin-bottom:0.4rem;">Our Story</p>
                <h2 style="font-weight:800; font-size:clamp(1.5rem,3vw,2rem); color:#0c2340; margin:0 0 1.25rem;">Built on Faith, Grown in Excellence</h2>
                <p style="font-size:0.9rem; color:#475569; line-height:1.85; margin-bottom:1rem;">
                    My Messiah School of Cavite (MMSC) was established with a clear vision: to provide quality, Christ-centered education that nurtures the whole student — mind, character, and spirit.
                </p>
                <p style="font-size:0.9rem; color:#475569; line-height:1.85; margin-bottom:1rem;">
                    Since opening our doors in 1998, we have grown into a thriving K–12 institution serving learners from Pre-School through Senior High School. Our community of educators, parents, and students share one goal — to honor God through academic excellence and faithful service.
                </p>
                <p style="font-size:0.9rem; color:#475569; line-height:1.85;">
                    Accredited by DepEd and guided by Christian values, MMSC continues to be a place where every child is known by name, valued by God, and equipped for life.
                </p>
            </div>
            <div class="relative rounded-2xl overflow-hidden shadow-xl" style="height:380px;">
                <img src="{{ asset('images/img1.jpg') }}" alt="MMSC Campus" class="absolute inset-0 w-full h-full object-cover">
                <div style="position:absolute; inset:0; background:linear-gradient(to top, rgba(4,22,41,0.6) 0%, transparent 55%);"></div>
                <div style="position:absolute; bottom:1.5rem; left:1.5rem; right:1.5rem;">
                    <div style="background:rgba(255,255,255,0.12); backdrop-filter:blur(10px); border:1px solid rgba(255,255,255,0.18); border-radius:12px; padding:1rem 1.25rem; color:#fff;">
                        <p style="font-weight:700; font-size:0.85rem; margin:0;">✦ DepEd Accredited — Est. 1998</p>
                        <p style="font-size:0.75rem; color:rgba(255,255,255,0.72); margin:4px 0 0;">Serving Cavite families for over 25 years</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Mission & Vision --}}
<section class="py-20 reveal" style="background:#fff;">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <p style="font-size:0.68rem; font-weight:700; letter-spacing:0.22em; text-transform:uppercase; color:#55afe1; margin-bottom:0.5rem;">Purpose & Direction</p>
            <h2 style="font-weight:800; font-size:clamp(1.5rem,3vw,2rem); color:#0c2340; margin:0;">Our Mission &amp; Vision</h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-10">
            <div class="mv-card">
                <div style="display:flex; align-items:center; gap:12px; margin-bottom:1rem;">
                    <div style="width:44px; height:44px; background:#e8f4fd; border-radius:10px; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                        <svg width="22" height="22" fill="none" stroke="#55afe1" stroke-width="2" viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5z"/><path d="M2 17l10 5 10-5"/><path d="M2 12l10 5 10-5"/></svg>
                    </div>
                    <h3 style="font-weight:800; font-size:1.05rem; color:#0c2340; margin:0;">Our Mission</h3>
                </div>
                <p style="font-size:0.9rem; color:#475569; line-height:1.8; margin:0;">
                    To provide holistic, Christ-centered education that empowers every learner — nurturing academic excellence, moral integrity, and a compassionate heart — so they may serve God, family, and community with purpose.
                </p>
            </div>
            <div class="mv-card">
                <div style="display:flex; align-items:center; gap:12px; margin-bottom:1rem;">
                    <div style="width:44px; height:44px; background:#e8f4fd; border-radius:10px; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                        <svg width="22" height="22" fill="none" stroke="#55afe1" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7z"/><circle cx="12" cy="12" r="3"/></svg>
                    </div>
                    <h3 style="font-weight:800; font-size:1.05rem; color:#0c2340; margin:0;">Our Vision</h3>
                </div>
                <p style="font-size:0.9rem; color:#475569; line-height:1.8; margin:0;">
                    MMSC envisions a community of lifelong learners and God-fearing leaders — equipped with knowledge, faith, and character — ready to make a lasting difference in an ever-changing world.
                </p>
            </div>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-center">
            @foreach([['✝','Faith'],['⭐','Excellence'],['🤝','Service'],['💡','Integrity']] as [$icon,$label])
            <div style="background:#f8fafc; border-radius:10px; padding:1.5rem 0.75rem; border:1px solid #e2e8f0;">
                <span style="font-size:1.75rem; display:block; margin-bottom:0.5rem;">{{ $icon }}</span>
                <p style="font-weight:700; font-size:0.78rem; color:#0c2340; margin:0; text-transform:uppercase; letter-spacing:0.1em;">{{ $label }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Stats --}}
<section class="py-14 reveal" style="background:#0c4e92;">
    <div class="max-w-5xl mx-auto px-4 grid grid-cols-2 sm:grid-cols-4 gap-6 text-center">
        @foreach([['25+','Years of Excellence'],['1,200+','Enrolled Students'],['80+','Dedicated Educators'],['98%','Graduation Rate']] as [$n,$l])
        <div>
            <p style="font-size:2rem; font-weight:900; color:#fff; line-height:1;">{{ $n }}</p>
            <p style="font-size:0.8rem; color:rgba(255,255,255,0.7); margin-top:0.4rem;">{{ $l }}</p>
        </div>
        @endforeach
    </div>
</section>

{{-- CTA --}}
<section class="py-16 reveal" style="background:#fff;">
    <div class="max-w-xl mx-auto px-4 text-center">
        <h2 style="font-weight:800; font-size:1.6rem; color:#0c2340; margin-bottom:0.75rem;">Be Part of Our Community</h2>
        <p style="font-size:0.9rem; color:#475569; margin-bottom:1.75rem; line-height:1.7;">Enrollment is open. Take the first step toward a Christ-centered education.</p>
        <a href="{{ route('online.registration.step1') }}" style="display:inline-flex; align-items:center; gap:8px; background:#0d4c8f; color:#fff; font-weight:700; font-size:0.8rem; letter-spacing:0.1em; text-transform:uppercase; padding:13px 30px; border-radius:6px; text-decoration:none;">
            Apply Now <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
        </a>
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
