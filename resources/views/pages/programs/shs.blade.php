@extends('layouts.welcome')
@section('title', 'Senior High School — MMSC')
@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800;900&display=swap');
    body { font-family:'Montserrat',sans-serif; }
    .reveal { opacity:0; transform:translateY(24px); transition:opacity 0.7s ease,transform 0.7s ease; }
    .reveal.visible { opacity:1; transform:translateY(0); }
    .track-card { border-radius:14px; overflow:hidden; background:#fff; border:1px solid #e2e8f0; box-shadow:0 2px 12px rgba(0,0,0,0.05); transition:transform 0.2s,box-shadow 0.2s; }
    .track-card:hover { transform:translateY(-4px); box-shadow:0 12px 32px rgba(0,0,0,0.1); }
</style>

<div style="padding-top:calc(64px + 3rem); padding-bottom:3.5rem; background:linear-gradient(135deg, #4f1b8a, #0d4c8f);">
    <div class="max-w-4xl mx-auto px-4 text-center">
        <p style="font-size:0.7rem; font-weight:700; letter-spacing:0.2em; text-transform:uppercase; color:#c4b5fd; margin-bottom:0.5rem;">Upper Secondary</p>
        <h1 style="font-weight:800; font-size:clamp(1.8rem,4vw,2.8rem); color:#fff; margin:0 0 1rem;">Senior High School</h1>
        <p style="font-size:0.95rem; color:rgba(255,255,255,0.75); max-width:520px; margin:0 auto; line-height:1.75;">Grades 11–12 · Academic & Technical-Vocational Tracks</p>
    </div>
</div>

<section class="py-20 reveal" style="background:#f8fafc;">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 style="font-weight:800; font-size:1.8rem; color:#0c2340; margin:0 0 0.5rem;">Our SHS Tracks &amp; Strands</h2>
            <p style="font-size:0.9rem; color:#475569;">Choose the path that fits your goals — college, career, or both.</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- Academic Track --}}
            <div class="track-card">
                <div style="height:5px; background:linear-gradient(to right, #7c3aed, #4f46e5);"></div>
                <div class="p-6">
                    <div style="display:flex; align-items:center; gap:10px; margin-bottom:1rem;">
                        <div style="width:40px; height:40px; background:#ede9fe; border-radius:10px; display:flex; align-items:center; justify-content:center;">
                            <svg width="20" height="20" fill="none" stroke="#7c3aed" stroke-width="2" viewBox="0 0 24 24"><path d="M12 14l9-5-9-5-9 5 9 5z"/><path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055"/></svg>
                        </div>
                        <h3 style="font-weight:800; font-size:1rem; color:#0c2340; margin:0;">Academic Track</h3>
                    </div>
                    <p style="font-size:0.85rem; color:#475569; line-height:1.75; margin-bottom:1rem;">Designed for students aiming for college admission, offering rigorous academic preparation in specialized disciplines.</p>
                    <div class="space-y-2">
                        @foreach([
                            ['STEM','Science, Technology, Engineering & Mathematics','#7c3aed'],
                            ['ABM','Accountancy, Business & Management','#2563eb'],
                            ['HUMSS','Humanities & Social Sciences','#0891b2'],
                        ] as [$code,$desc,$color])
                        <div style="display:flex; align-items:center; gap:10px; padding:10px 12px; background:#f8fafc; border-radius:8px; border:1px solid #e2e8f0;">
                            <span style="font-weight:800; font-size:0.75rem; color:{{ $color }}; min-width:52px;">{{ $code }}</span>
                            <span style="font-size:0.82rem; color:#475569;">{{ $desc }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- TVL Track --}}
            <div class="track-card">
                <div style="height:5px; background:linear-gradient(to right, #ea580c, #f59e0b);"></div>
                <div class="p-6">
                    <div style="display:flex; align-items:center; gap:10px; margin-bottom:1rem;">
                        <div style="width:40px; height:40px; background:#ffedd5; border-radius:10px; display:flex; align-items:center; justify-content:center;">
                            <svg width="20" height="20" fill="none" stroke="#ea580c" stroke-width="2" viewBox="0 0 24 24"><path d="M14.7 6.3a1 1 0 000 1.4l1.6 1.6a1 1 0 001.4 0l3.77-3.77a6 6 0 01-7.94 7.94l-6.91 6.91a2.12 2.12 0 01-3-3l6.91-6.91a6 6 0 017.94-7.94l-3.76 3.76z"/></svg>
                        </div>
                        <h3 style="font-weight:800; font-size:1rem; color:#0c2340; margin:0;">TVL Track</h3>
                    </div>
                    <p style="font-size:0.85rem; color:#475569; line-height:1.75; margin-bottom:1rem;">Technical-Vocational-Livelihood track with TESDA-aligned qualifications for students aiming to enter the workforce.</p>
                    <div class="space-y-2">
                        @foreach([
                            ['Tourism','Hotel & Restaurant Services','#ea580c'],
                            ['Cookery','Food & Beverage Services','#d97706'],
                            ['ICT','Information & Communications Technology','#0891b2'],
                            ['IA','Industrial Arts','#059669'],
                            ['BPP','Beauty Care & Wellness','#db2777'],
                        ] as [$code,$desc,$color])
                        <div style="display:flex; align-items:center; gap:10px; padding:10px 12px; background:#f8fafc; border-radius:8px; border:1px solid #e2e8f0;">
                            <span style="font-weight:800; font-size:0.75rem; color:{{ $color }}; min-width:52px;">{{ $code }}</span>
                            <span style="font-size:0.82rem; color:#475569;">{{ $desc }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- Incentives --}}
        <div style="margin-top:3rem; background:#fff; border-radius:14px; border:1px solid #e2e8f0; padding:1.75rem 2rem;">
            <h3 style="font-weight:800; font-size:1rem; color:#0c2340; margin:0 0 1rem;">SHS Financial Assistance</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div style="display:flex; gap:10px; align-items:flex-start;">
                    <span style="color:#7c3aed; font-size:1.1rem; margin-top:2px;">✓</span>
                    <div>
                        <p style="font-weight:700; font-size:0.85rem; color:#0c2340; margin:0;">SHS Voucher Program</p>
                        <p style="font-size:0.8rem; color:#64748b; margin:2px 0 0;">For public JHS completers — covers partial to full tuition.</p>
                    </div>
                </div>
                <div style="display:flex; gap:10px; align-items:flex-start;">
                    <span style="color:#7c3aed; font-size:1.1rem; margin-top:2px;">✓</span>
                    <div>
                        <p style="font-weight:700; font-size:0.85rem; color:#0c2340; margin:0;">Academic Scholarship</p>
                        <p style="font-size:0.8rem; color:#64748b; margin:2px 0 0;">Merit-based discount for students with GWA 90+.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center mt-10">
            <a href="{{ route('online.registration.step1') }}" style="display:inline-flex; align-items:center; gap:8px; background:#0d4c8f; color:#fff; font-weight:700; font-size:0.8rem; letter-spacing:0.1em; text-transform:uppercase; padding:13px 30px; border-radius:6px; text-decoration:none; margin-right:12px;">
                Apply for SHS <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </a>
            <a href="{{ route('admission.requirements') }}" style="display:inline-flex; align-items:center; gap:8px; border:2px solid #0d4c8f; color:#0d4c8f; font-weight:700; font-size:0.8rem; letter-spacing:0.1em; text-transform:uppercase; padding:11px 28px; border-radius:6px; text-decoration:none;">
                View Requirements
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
