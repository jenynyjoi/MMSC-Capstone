{{-- resources/views/welcome.blade.php --}}

@extends('layouts.welcome')

@section('title', 'My Messiah School of Cavite')

@section('content')

<style>
    @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800;900&display=swap');
    body { font-family: 'Montserrat', sans-serif; }

    /* ── Hero Carousel ── */
    #landingPage { font-family: 'Montserrat', sans-serif; }
    .hero-slide { position: absolute; inset: 0; opacity: 0; transition: opacity 1s ease; }
    .hero-slide.active { opacity: 1; }

    .carousel-arrow {
        position: absolute; top: 50%; z-index: 20;
        transform: translateY(-50%);
        width: 42px; height: 42px;
        background: rgba(255,255,255,0.14);
        border: 1.5px solid rgba(255,255,255,0.32);
        border-radius: 50%; color: #fff;
        display: flex; align-items: center; justify-content: center;
        cursor: pointer; transition: background 0.2s;
        backdrop-filter: blur(4px);
    }
    .carousel-arrow:hover { background: rgba(255,255,255,0.26); }
    .carousel-arrow.prev { left: 20px; }
    .carousel-arrow.next { right: 20px; }

    .dot-btn { width: 10px; height: 10px; border-radius: 9999px; background: rgba(255,255,255,0.40); border: none; cursor: pointer; padding: 0; transition: all 0.3s; }
    .dot-btn.active { width: 28px; background: #55afe1; }

    .btn-apply {
        display: inline-flex; align-items: center; gap: 10px;
        background: #55afe1; color: #fff;
        font-family: 'Montserrat', sans-serif; font-weight: 750;
        font-size: 0.82rem; letter-spacing: 0.12em; text-transform: uppercase;
        padding: 13px 28px; border-radius: 10px; text-decoration: none;
        transition: background 0.2s, transform 0.15s;
    }
    .btn-apply:hover { background: #3d9fd6; transform: translateY(-1px); }

    .btn-outline {
        display: inline-flex; align-items: center; gap: 8px;
        background: transparent; color: #fff;
        font-family: 'Montserrat', sans-serif; font-weight: 700;
        font-size: 0.82rem; letter-spacing: 0.12em; text-transform: uppercase;
        padding: 12px 28px; border: 2px solid rgba(255,255,255,0.65); border-radius: 10px;
        text-decoration: none; transition: background 0.2s, border-color 0.2s, transform 0.15s;
    }
    .btn-outline:hover { background: rgba(255,255,255,0.10); border-color: #fff; transform: translateY(-1px); }

    .program-card { transition: transform 0.25s, box-shadow 0.25s; }
    .program-card:hover { transform: translateY(-4px); box-shadow: 0 20px 40px rgba(0,0,0,0.10); }

    .ticker-wrap { overflow: hidden; }
    .ticker-inner { display: flex; gap: 3rem; white-space: nowrap; animation: ticker 30s linear infinite; }
    .ticker-inner:hover { animation-play-state: paused; }
    @keyframes ticker { from { transform: translateX(0); } to { transform: translateX(-50%); } }

    .reveal { opacity: 0; transform: translateY(24px); transition: opacity 0.7s ease, transform 0.7s ease; }
    .reveal.visible { opacity: 1; transform: translateY(0); }

    @keyframes fadeUp { from { opacity:0; transform:translateY(16px); } to { opacity:1; transform:translateY(0); } }
    .stat-item { animation: fadeUp 0.6s ease both; }

    .mv-card {
        border-top: 4px solid #55afe1;
        background: #fff;
        border-radius: 12px;
        padding: 2rem 1.75rem;
        box-shadow: 0 2px 16px rgba(12,78,146,0.07);
        transition: transform 0.25s, box-shadow 0.25s;
    }
    .mv-card:hover { transform: translateY(-3px); box-shadow: 0 8px 28px rgba(12,78,146,0.12); }
</style>



{{-- ══════════════ HERO CAROUSEL ══════════════ --}}
<section id="landingPage" class="relative overflow-hidden" style="height:calc(100vh - 34px); min-height:480px;">

    @php
        $heroGradient = "linear-gradient(to bottom, rgba(13,76,143,0.82) 21%, rgba(9,52,98,0.78) 53%, rgba(4,22,41,0.86) 93%)";
    @endphp

    <div class="hero-slide active"
         style="background:{{ $heroGradient }}, url('{{ asset('/images/img5.jpg') }}') center/cover no-repeat;">
    </div>
    <div class="hero-slide"
         style="background:{{ $heroGradient }}, url('{{ asset('/images/img6.jpg') }}') center/cover no-repeat;">
    </div>
    <div class="hero-slide"
         style="background:{{ $heroGradient }}, url('{{ asset('/images/img7.jpg') }}') center/cover no-repeat;">
    </div>

<div class="absolute inset-0 z-10 flex flex-col items-start justify-center text-start px-10 sm:px-16 lg:px-24">
    <p class="text-blue-200 text-sm font-extrabold uppercase tracking-widest mb-4 animate-pulse">Welcome to MMSC</p>
    <h1 class="text-4xl sm:text-5xl lg:text-5xl font-extrabold text-white leading-tight mb-5 drop-shadow-lg">
        <span class="block text-white">
            Christ-centered. Academically strong. <br>Wonderfully made.
        </span>
    </h1>
    <p class="text-white/80 max-w-xl mb-8" style="font-family:'Montserrat',sans-serif; font-size:0.95rem; line-height:1.6;">
        Empowering learners to grow in faith, excel in academics, and serve with heart — since 2009.
    </p>
    <div style="display:flex; flex-wrap:wrap; gap:1rem; align-items:center; justify-content:center;">
        <a href="{{ route('online.registration.step1') }}" class="btn-apply">
            Apply Now
            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
        </a>
        <a href="#programs" class="btn-outline">Explore Programs</a>
    </div>
</div>
    <div class="absolute bottom-8 left-1/2 z-10 flex items-center gap-2" style="transform:translateX(-50%);">
        <button class="dot-btn active" data-index="0" aria-label="Slide 1"></button>
        <button class="dot-btn" data-index="1" aria-label="Slide 2"></button>
        <button class="dot-btn" data-index="2" aria-label="Slide 3"></button>
    </div>

</section>


{{-- ══════════════ STATS STRIP ══════════════ --}}
<section class="py-10 reveal" style="background:#0c4e92;">
    <div class="max-w-5xl mx-auto px-4 grid grid-cols-2 sm:grid-cols-4 gap-6 text-center">
        <div class="stat-item" style="animation-delay:0s">
            <p class="text-3xl font-extrabold text-white" style="font-family:'Montserrat',sans-serif;">25<span style="color:#55afe1;">+</span></p>
            <p class="text-sm mt-1" style="color:rgba(255,255,255,0.7); font-family:'Montserrat',sans-serif;">Years of Excellence</p>
        </div>
        <div class="stat-item" style="animation-delay:0.1s">
            <p class="text-3xl font-extrabold text-white" style="font-family:'Montserrat',sans-serif;">1,200<span style="color:#55afe1;">+</span></p>
            <p class="text-sm mt-1" style="color:rgba(255,255,255,0.7); font-family:'Montserrat',sans-serif;">Enrolled Students</p>
        </div>
        <div class="stat-item" style="animation-delay:0.2s">
            <p class="text-3xl font-extrabold text-white" style="font-family:'Montserrat',sans-serif;">80<span style="color:#55afe1;">+</span></p>
            <p class="text-sm mt-1" style="color:rgba(255,255,255,0.7); font-family:'Montserrat',sans-serif;">Dedicated Educators</p>
        </div>
        <div class="stat-item" style="animation-delay:0.3s">
            <p class="text-3xl font-extrabold text-white" style="font-family:'Montserrat',sans-serif;">98<span style="color:#55afe1;">%</span></p>
            <p class="text-sm mt-1" style="color:rgba(255,255,255,0.7); font-family:'Montserrat',sans-serif;">Graduation Rate</p>
        </div>
    </div>
</section>



{{-- ══════════════ WHY CHOOSE MMSC ══════════════ --}}
<section class="py-20 reveal bg-slate-100 dark:bg-slate-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
            <div>
                <p style="font-family:'Montserrat',sans-serif; font-size:0.68rem; font-weight:700; letter-spacing:0.22em; text-transform:uppercase; color:#55afe1; margin-bottom:0.4rem;">Why MMSC</p>
                <h2 class="dark:text-slate-100" style="font-family:'Montserrat',sans-serif; font-weight:800; font-size:clamp(1.6rem,3vw,2.2rem); color:#0c2340; margin:0 0 1.2rem;">
                    A School Built on<br>Faith, Excellence &amp; Care
                </h2>
                <p class="dark:text-slate-400" style="font-family:'Montserrat',sans-serif; font-size:0.9rem; color:#475569; line-height:1.8; margin-bottom:2rem;">
                    At My Messiah School of Cavite, we believe every child deserves an education that nurtures the whole person — mind, character, and spirit.
                </p>
                <div class="space-y-5">
                    @php
                        $features = [
                            ['icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'title' => 'Christ-Centered Values', 'desc' => 'Faith woven into every lesson and school activity.'],
                            ['icon' => 'M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z', 'title' => 'Qualified, Caring Teachers', 'desc' => 'Licensed educators who invest in every student\'s growth.'],
                            ['icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4', 'title' => 'Modern Facilities', 'desc' => 'Updated classrooms, labs, and learning spaces.'],
                        ];
                    @endphp
                    @foreach($features as $f)
                    <div class="flex gap-4 items-start">
                        <div class="shrink-0 w-10 h-10 rounded-xl flex items-center justify-center mt-0.5" style="background:#e8f4fd;">
                            <svg class="w-5 h-5" style="color:#55afe1;" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $f['icon'] }}"/></svg>
                        </div>
                        <div>
                            <p class="dark:text-slate-100" style="font-family:'Montserrat',sans-serif; font-weight:700; font-size:0.9rem; color:#0c2340;">{{ $f['title'] }}</p>
                            <p class="dark:text-slate-400" style="font-family:'Montserrat',sans-serif; font-size:0.85rem; color:#475569; margin-top:2px;">{{ $f['desc'] }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="relative rounded-2xl overflow-hidden shadow-xl" style="height:380px;">
                <img src="{{ asset('images/img2.jpg') }}" alt="Students at MMSC" class="absolute inset-0 w-full h-full object-cover">
                <div style="position:absolute; inset:0; background:linear-gradient(to top, rgba(4,22,41,0.65) 0%, transparent 60%);"></div>
                <div style="position:absolute; bottom:1.5rem; left:1.5rem; right:1.5rem;">
                    <div style="background:rgba(255,255,255,0.12); backdrop-filter:blur(10px); border:1px solid rgba(255,255,255,0.18); border-radius:12px; padding:1rem 1.25rem; color:#fff;">
                        <p style="font-family:'Montserrat',sans-serif; font-weight:700; font-size:0.85rem; margin:0;">✦ DepEd Accredited School</p>
                        <p style="font-family:'Montserrat',sans-serif; font-size:0.75rem; color:rgba(255,255,255,0.72); margin:4px 0 0;">Providing quality education since 2000.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- 
{{-- ══════════════ MISSION & VISION ══════════════ --}}
<section class="py-20 reveal" style="background:#f1f5f9;">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="text-center mb-12">
            <p style="font-family:'Montserrat',sans-serif; font-size:0.68rem; font-weight:700; letter-spacing:0.22em; text-transform:uppercase; color:#55afe1; margin-bottom:0.5rem;">Who We Are</p>
            <h2 style="font-family:'Montserrat',sans-serif; font-weight:800; font-size:clamp(1.6rem,3vw,2.2rem); color:#0c2340; margin:0;">Our Mission &amp; Vision</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

            {{-- Mission --}}
            <div class="mv-card">
                <div style="display:flex; align-items:center; gap:12px; margin-bottom:1rem;">
                    <div style="width:44px; height:44px; background:#e8f4fd; border-radius:10px; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                        <svg width="22" height="22" fill="none" stroke="#55afe1" stroke-width="2" viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5z"/><path d="M2 17l10 5 10-5"/><path d="M2 12l10 5 10-5"/></svg>
                    </div>
                    <h3 style="font-family:'Montserrat',sans-serif; font-weight:800; font-size:1.05rem; color:#0c2340; margin:0;">Our Mission</h3>
                </div>
                <p style="font-family:'Montserrat',sans-serif; font-size:0.9rem; color:#475569; line-height:1.8; margin:0;">
                    To provide holistic, Christ-centered education that empowers every learner — nurturing academic excellence, moral integrity, and a compassionate heart — so they may serve God, family, and community with purpose.
                </p>
            </div>

            {{-- Vision --}}
            <div class="mv-card">
                <div style="display:flex; align-items:center; gap:12px; margin-bottom:1rem;">
                    <div style="width:44px; height:44px; background:#e8f4fd; border-radius:10px; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                        <svg width="22" height="22" fill="none" stroke="#55afe1" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7z"/><circle cx="12" cy="12" r="3"/></svg>
                    </div>
                    <h3 style="font-family:'Montserrat',sans-serif; font-weight:800; font-size:1.05rem; color:#0c2340; margin:0;">Our Vision</h3>
                </div>
                <p style="font-family:'Montserrat',sans-serif; font-size:0.9rem; color:#475569; line-height:1.8; margin:0;">
                    My Messiah School of Cavite envisions a community of lifelong learners and God-fearing leaders — equipped with knowledge, faith, and character — ready to make a lasting difference in an ever-changing world.
                </p>
            </div>

        </div> -->

        <!-- {{-- Core Values --}}
        <div class="mt-10 grid grid-cols-2 sm:grid-cols-4 gap-4 text-center">
            @php
                $values = [
                    ['icon'=>'✝', 'label'=>'Faith'],
                    ['icon'=>'⭐', 'label'=>'Excellence'],
                    ['icon'=>'🤝', 'label'=>'Service'],
                    ['icon'=>'💡', 'label'=>'Integrity'],
                ];
            @endphp
            @foreach($values as $v)
            <div style="background:#fff; border-radius:10px; padding:1.25rem 0.75rem; box-shadow:0 1px 8px rgba(12,78,146,0.07);">
                <span style="font-size:1.5rem; display:block; margin-bottom:0.5rem;">{{ $v['icon'] }}</span>
                <p style="font-family:'Montserrat',sans-serif; font-weight:700; font-size:0.78rem; color:#0c2340; margin:0; text-transform:uppercase; letter-spacing:0.1em;">{{ $v['label'] }}</p>
            </div>
            @endforeach
        </div> -->

    </div> 
</section>


{{-- ══════════════ PROGRAMS ══════════════ --}}
<section id="programs" class="py-20 bg-white dark:bg-slate-900 reveal">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="mb-12 flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
            <div>
                <p style="font-family:'Montserrat',sans-serif; font-size:0.68rem; font-weight:700; letter-spacing:0.22em; text-transform:uppercase; color:#55afe1; margin-bottom:0.4rem;">What We Offer</p>
                <h2 style="font-family:'Montserrat',sans-serif; font-weight:800; font-size:clamp(1.6rem,3vw,2.2rem); color:#0c2340; margin:0;" class="dark:text-white">Our Programs</h2>
            </div>
            <p class="text-slate-500 text-sm max-w-xs leading-relaxed" style="font-family:'Montserrat',sans-serif;">
                From early childhood through senior high — a program for every learner.
            </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

            {{-- Pre School --}}
            <div class="program-card bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700 overflow-hidden flex flex-col shadow-sm">
                <div class="h-1.5 rounded-t-2xl" style="background:linear-gradient(to right,#f59e0b,#f97316);"></div>
                <div class="p-6 flex-1">
                    <div class="w-11 h-11 rounded-xl flex items-center justify-center mb-5" style="background:#fef3c7;">
                        <svg class="w-5 h-5" style="color:#f59e0b;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 2a5 5 0 015 5c0 3-2.5 6-5 8-2.5-2-5-5-5-8a5 5 0 015-5z"/><path d="M12 12v10"/></svg>
                    </div>
                    <h3 class="text-base text-slate-800 dark:text-white mb-0.5" style="font-family:'Montserrat',sans-serif; font-weight:800;">Pre School</h3>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-3" style="font-family:'Montserrat',sans-serif;">Early Childhood Education</p>
                    <p class="text-sm text-slate-600 dark:text-slate-300 leading-relaxed" style="font-family:'Montserrat',sans-serif;">A fun and nurturing environment that sparks curiosity and builds foundational skills for lifelong learning.</p>
                </div>
                <div class="px-6 pb-6">
                    <a href="#" class="inline-flex items-center gap-1 text-sm font-semibold hover:gap-2 transition-all group" style="color:#55afe1; font-family:'Montserrat',sans-serif;">
                        Learn more <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                    </a>
                </div>
            </div>

            {{-- Elementary --}}
            <div class="program-card bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700 overflow-hidden flex flex-col shadow-sm">
                <div class="h-1.5 rounded-t-2xl" style="background:linear-gradient(to right,#22c55e,#10b981);"></div>
                <div class="p-6 flex-1">
                    <div class="w-11 h-11 rounded-xl flex items-center justify-center mb-5" style="background:#dcfce7;">
                        <svg class="w-5 h-5" style="color:#22c55e;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 19.5A2.5 2.5 0 016.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 014 19.5v-15A2.5 2.5 0 016.5 2z"/></svg>
                    </div>
                    <h3 class="text-base text-slate-800 dark:text-white mb-0.5" style="font-family:'Montserrat',sans-serif; font-weight:800;">Elementary</h3>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-3" style="font-family:'Montserrat',sans-serif;">Primary Education — Grades 1–6</p>
                    <p class="text-sm text-slate-600 dark:text-slate-300 leading-relaxed" style="font-family:'Montserrat',sans-serif;">Comprehensive academic program strengthening literacy, numeracy, and creativity in young learners.</p>
                </div>
                <div class="px-6 pb-6">
                    <a href="#" class="inline-flex items-center gap-1 text-sm font-semibold hover:gap-2 transition-all group" style="color:#55afe1; font-family:'Montserrat',sans-serif;">
                        Learn more <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                    </a>
                </div>
            </div>

            {{-- Junior High --}}
            <div class="program-card bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700 overflow-hidden flex flex-col shadow-sm">
                <div class="h-1.5 rounded-t-2xl" style="background:linear-gradient(to right,#3b82f6,#6366f1);"></div>
                <div class="p-6 flex-1">
                    <div class="w-11 h-11 rounded-xl flex items-center justify-center mb-5" style="background:#dbeafe;">
                        <svg class="w-5 h-5" style="color:#3b82f6;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg>
                    </div>
                    <h3 class="text-base text-slate-800 dark:text-white mb-0.5" style="font-family:'Montserrat',sans-serif; font-weight:800;">Junior High School</h3>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-3" style="font-family:'Montserrat',sans-serif;">Lower Secondary — Grades 7–10</p>
                    <p class="text-sm text-slate-600 dark:text-slate-300 leading-relaxed" style="font-family:'Montserrat',sans-serif;">A well-rounded curriculum developing critical thinking, character, and skills for the future.</p>
                </div>
                <div class="px-6 pb-6">
                    <a href="#" class="inline-flex items-center gap-1 text-sm font-semibold hover:gap-2 transition-all group" style="color:#55afe1; font-family:'Montserrat',sans-serif;">
                        Learn more <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                    </a>
                </div>
            </div>

            {{-- Senior High --}}
            <div class="program-card bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700 overflow-hidden flex flex-col shadow-sm">
                <div class="h-1.5 rounded-t-2xl" style="background:linear-gradient(to right,#a855f7,#ec4899);"></div>
                <div class="p-6 flex-1">
                    <div class="w-11 h-11 rounded-xl flex items-center justify-center mb-5" style="background:#f3e8ff;">
                        <svg class="w-5 h-5" style="color:#a855f7;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="8" r="6"/><path d="M15.477 12.89L17 22l-5-3-5 3 1.523-9.11"/></svg>
                    </div>
                    <h3 class="text-base text-slate-800 dark:text-white mb-0.5" style="font-family:'Montserrat',sans-serif; font-weight:800;">Senior High School</h3>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-3" style="font-family:'Montserrat',sans-serif;">Upper Secondary — Grades 11–12</p>
                    <p class="text-sm text-slate-600 dark:text-slate-300 leading-relaxed" style="font-family:'Montserrat',sans-serif;">Focused academic and technical tracks to prepare students for college and career pathways.</p>
                    <div class="mt-4 space-y-1.5">
                        <p class="text-xs text-slate-600 dark:text-slate-400" style="font-family:'Montserrat',sans-serif;"><span class="font-bold" style="color:#a855f7;">Academic:</span> STEM · ABM · HUMSS</p>
                        <p class="text-xs text-slate-600 dark:text-slate-400" style="font-family:'Montserrat',sans-serif;"><span class="font-bold" style="color:#a855f7;">TVL:</span> Tourism · Cookery · ICT · IA · BPP</p>
                    </div>
                </div>
                <div class="px-6 pb-6">
                    <a href="#" class="inline-flex items-center gap-1 text-sm font-semibold hover:gap-2 transition-all group" style="color:#55afe1; font-family:'Montserrat',sans-serif;">
                        Learn more <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                    </a>
                </div>
            </div>

        </div>
    </div>
</section>




{{-- ══════════════ FINANCIAL ASSISTANCE & INCENTIVES ══════════════ --}}
<section class="py-20 reveal bg-slate-100 dark:bg-slate-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="text-center mb-12">
            <p style="font-family:'Montserrat',sans-serif; font-size:0.68rem; font-weight:700; letter-spacing:0.22em; text-transform:uppercase; color:#55afe1; margin-bottom:0.5rem;">Vouchers & Grants</p>
            <h2 class="dark:text-slate-100" style="font-family:'Montserrat',sans-serif; font-weight:800; font-size:clamp(1.6rem,3vw,2.2rem); color:#0c2340; margin:0 0 0.75rem;">Financial Assistance Programs</h2>
            <p class="dark:text-slate-400" style="font-family:'Montserrat',sans-serif; font-size:0.9rem; color:#475569; max-width:540px; margin:0 auto; line-height:1.7;">Quality education at MMSC is made accessible through government vouchers, scholarships, and special grants.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

            {{-- ESC Grant --}}
            <div class="program-card bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700 p-6 flex flex-col shadow-sm">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center mb-4" style="background:#dbeafe;">
                    <svg class="w-6 h-6" style="color:#3b82f6;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                </div>
                <div class="flex-1">
                    <div class="inline-block text-xs font-bold px-2 py-0.5 rounded-full mb-2" style="background:#dbeafe; color:#1d4ed8;">DepEd Program</div>
                    <h3 style="font-family:'Montserrat',sans-serif; font-weight:800; font-size:0.95rem; color:#0c2340; margin:0 0 0.5rem;">ESC Grant</h3>
                    <p style="font-family:'Montserrat',sans-serif; font-size:0.82rem; color:#475569; line-height:1.7; margin:0 0 0.75rem;">Education Service Contracting (ESC) grant for Grade 6 completers from public schools continuing to Junior High School at MMSC.</p>
                    <ul style="font-family:'Montserrat',sans-serif; font-size:0.8rem; color:#475569; padding-left:1rem; line-height:1.9;">
                        <li>Covers partial tuition subsidy</li>
                        <li>Government-funded (DepEd)</li>
                        <li>For incoming Gr. 7 students</li>
                    </ul>
                </div>
                <a href="{{ route('admission.requirements') }}" style="font-family:'Montserrat',sans-serif; font-size:0.82rem; font-weight:700; color:#55afe1; margin-top:1rem; display:inline-flex; align-items:center; gap:4px;">
                    View Requirements <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                </a>
            </div>

            {{-- SHS Voucher --}}
            <div class="program-card bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700 p-6 flex flex-col shadow-sm">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center mb-4" style="background:#f3e8ff;">
                    <svg class="w-6 h-6" style="color:#a855f7;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="8" r="6"/><path d="M15.477 12.89L17 22l-5-3-5 3 1.523-9.11"/></svg>
                </div>
                <div class="flex-1">
                    <div class="inline-block text-xs font-bold px-2 py-0.5 rounded-full mb-2" style="background:#f3e8ff; color:#7e22ce;">DepEd / PEAC</div>
                    <h3 style="font-family:'Montserrat',sans-serif; font-weight:800; font-size:0.95rem; color:#0c2340; margin:0 0 0.5rem;">SHS Voucher Program</h3>
                    <p style="font-family:'Montserrat',sans-serif; font-size:0.82rem; color:#475569; line-height:1.7; margin:0 0 0.75rem;">Senior High School Voucher for Grade 10 completers from public Junior High Schools who wish to enroll in MMSC's SHS programs.</p>
                    <ul style="font-family:'Montserrat',sans-serif; font-size:0.8rem; color:#475569; padding-left:1rem; line-height:1.9;">
                        <li>Covers full / partial tuition</li>
                        <li>All SHS tracks eligible</li>
                        <li>Annual renewal required</li>
                    </ul>
                </div>
                <a href="{{ route('admission.requirements') }}" style="font-family:'Montserrat',sans-serif; font-size:0.82rem; font-weight:700; color:#55afe1; margin-top:1rem; display:inline-flex; align-items:center; gap:4px;">
                    View Requirements <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                </a>
            </div>

            {{-- Scholarship --}}
            <div class="program-card bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700 p-6 flex flex-col shadow-sm">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center mb-4" style="background:#dcfce7;">
                    <svg class="w-6 h-6" style="color:#22c55e;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 14l9-5-9-5-9 5 9 5z"/><path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/></svg>
                </div>
                <div class="flex-1">
                    <div class="inline-block text-xs font-bold px-2 py-0.5 rounded-full mb-2" style="background:#dcfce7; color:#15803d;">School Grant</div>
                    <h3 style="font-family:'Montserrat',sans-serif; font-weight:800; font-size:0.95rem; color:#0c2340; margin:0 0 0.5rem;">Academic Scholarship</h3>
                    <p style="font-family:'Montserrat',sans-serif; font-size:0.82rem; color:#475569; line-height:1.7; margin:0 0 0.75rem;">Merit-based scholarship for high-achieving students who demonstrate outstanding academic performance and school involvement.</p>
                    <ul style="font-family:'Montserrat',sans-serif; font-size:0.8rem; color:#475569; padding-left:1rem; line-height:1.9;">
                        <li>GWA of 90+ required</li>
                        <li>25%–100% tuition discount</li>
                        <li>Must maintain academic standing</li>
                    </ul>
                </div>
                <a href="{{ route('admission.requirements') }}" style="font-family:'Montserrat',sans-serif; font-size:0.82rem; font-weight:700; color:#55afe1; margin-top:1rem; display:inline-flex; align-items:center; gap:4px;">
                    View Requirements <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                </a>
            </div>

            {{-- Sibling Discount --}}
            <div class="program-card bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700 p-6 flex flex-col shadow-sm">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center mb-4" style="background:#fef3c7;">
                    <svg class="w-6 h-6" style="color:#f59e0b;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>
                </div>
                <div class="flex-1">
                    <div class="inline-block text-xs font-bold px-2 py-0.5 rounded-full mb-2" style="background:#fef3c7; color:#92400e;">School Incentive</div>
                    <h3 style="font-family:'Montserrat',sans-serif; font-weight:800; font-size:0.95rem; color:#0c2340; margin:0 0 0.5rem;">Sibling Discount</h3>
                    <p style="font-family:'Montserrat',sans-serif; font-size:0.82rem; color:#475569; line-height:1.7; margin:0 0 0.75rem;">Families with two or more children enrolled simultaneously at MMSC are entitled to a tuition discount for the second enrollee onwards.</p>
                    <ul style="font-family:'Montserrat',sans-serif; font-size:0.8rem; color:#475569; padding-left:1rem; line-height:1.9;">
                        <li>10% off for 2nd sibling</li>
                        <li>15% off for 3rd sibling</li>
                        <li>Applied at enrollment</li>
                    </ul>
                </div>
                <a href="{{ route('contact') }}" style="font-family:'Montserrat',sans-serif; font-size:0.82rem; font-weight:700; color:#55afe1; margin-top:1rem; display:inline-flex; align-items:center; gap:4px;">
                    Inquire Now <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                </a>
            </div>

        </div>

        <p class="dark:text-slate-600" style="font-family:'Montserrat',sans-serif; font-size:0.78rem; color:#94a3b8; text-align:center; margin-top:2rem;">
            * Grant availability is subject to DepEd / PEAC confirmation each school year. Contact the Registrar's Office for details.
        </p>
    </div>
</section>


{{-- ══════════════ CTA BANNER ══════════════ --}}
<section class="py-20 reveal" style="background:linear-gradient(to bottom, #0d4c8f 21%, #093462 53%, #041629 93%);">
    <div class="max-w-3xl mx-auto px-4 text-center">
        <h2 style="font-family:'Montserrat',sans-serif; font-weight:800; font-size:clamp(1.6rem,3vw,2.2rem); color:#fff; margin-bottom:1rem;">
            Ready to Be Part of MMSC?
        </h2>
        <p style="font-family:'Montserrat',sans-serif; color:rgba(255,255,255,0.70); margin-bottom:2.2rem; font-size:0.95rem; line-height:1.75;">
            Join our growing community of learners. Enrollment is open — take the first step today.
        </p>
        <a href="{{ route('online.registration.step1') }}" class="btn-apply" style="margin:0 auto;">
            Start Your Application
            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
        </a>
    </div>
</section>

@endsection


@push('scripts')
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {

    /* ── Carousel ── */
    const slides = document.querySelectorAll('.hero-slide');
    const dots   = document.querySelectorAll('.dot-btn');
    let current  = 0, timer;

    function goTo(n) {
        slides[current].classList.remove('active');
        dots[current].classList.remove('active');
        current = (n + slides.length) % slides.length;
        slides[current].classList.add('active');
        dots[current].classList.add('active');
    }

    function next() { goTo(current + 1); }
    function prev() { goTo(current - 1); }
    function startTimer() { timer = setInterval(next, 5000); }
    function resetTimer() { clearInterval(timer); startTimer(); }

    dots.forEach(d => d.addEventListener('click', () => { goTo(+d.dataset.index); resetTimer(); }));

    const btnNext = document.getElementById('carouselNext');
    const btnPrev = document.getElementById('carouselPrev');
    if (btnNext) btnNext.addEventListener('click', () => { next(); resetTimer(); });
    if (btnPrev) btnPrev.addEventListener('click', () => { prev(); resetTimer(); });

    startTimer();

    /* ── Scroll reveal ── */
    const io = new IntersectionObserver(entries => {
        entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('visible'); io.unobserve(e.target); } });
    }, { threshold: 0.1 });

    document.querySelectorAll('.reveal').forEach(el => io.observe(el));
});
</script>
@endpush