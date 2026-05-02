@extends('layouts.welcome')
@section('title', 'Contact Us — MMSC')
@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800;900&display=swap');
    body { font-family:'Montserrat',sans-serif; }
    .reveal { opacity:0; transform:translateY(24px); transition:opacity 0.7s ease,transform 0.7s ease; }
    .reveal.visible { opacity:1; transform:translateY(0); }
</style>

<div style="padding-top:calc(64px + 3rem); padding-bottom:3.5rem; background:linear-gradient(135deg, #0c2340, #0d4c8f);">
    <div class="max-w-4xl mx-auto px-4 text-center">
        <p style="font-size:0.7rem; font-weight:700; letter-spacing:0.2em; text-transform:uppercase; color:#55afe1; margin-bottom:0.5rem;">Get in Touch</p>
        <h1 style="font-weight:800; font-size:clamp(1.8rem,4vw,2.8rem); color:#fff; margin:0 0 1rem;">Contact Us</h1>
        <p style="font-size:0.95rem; color:rgba(255,255,255,0.75); max-width:480px; margin:0 auto; line-height:1.75;">We're happy to answer your questions about enrollment, programs, and school life at MMSC.</p>
    </div>
</div>

<section class="py-20 reveal" style="background:#f8fafc;">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-10">

            {{-- Contact Info --}}
            <div class="lg:col-span-2 space-y-6">

                <div>
                    <h2 style="font-weight:800; font-size:1.3rem; color:#0c2340; margin:0 0 0.5rem;">School Information</h2>
                    <p style="font-size:0.85rem; color:#64748b; line-height:1.7; margin:0;">Reach out to us through any of the following channels. Our staff is available Monday–Friday, 7:30 AM – 5:00 PM.</p>
                </div>

                @foreach([
                    ['ri-map-pin-2-line','Address','Tanza, Cavite, Philippines','#0d4c8f'],
                    ['ri-phone-line','Phone / Mobile','+63 (XXX) XXX-XXXX','#059669'],
                    ['ri-mail-line','Email','info@mmsc.edu.ph','#7c3aed'],
                    ['ri-time-line','Office Hours','Mon–Fri · 7:30 AM – 5:00 PM','#ea580c'],
                ] as [$icon,$label,$value,$color])
                <div style="display:flex; gap:14px; align-items:flex-start; background:#fff; border-radius:12px; padding:1.25rem; border:1px solid #e2e8f0; box-shadow:0 1px 6px rgba(0,0,0,0.04);">
                    <div style="width:40px; height:40px; background:{{ $color }}15; border-radius:10px; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                        <i class="{{ $icon }}" style="font-size:18px; color:{{ $color }};"></i>
                    </div>
                    <div>
                        <p style="font-size:0.7rem; font-weight:700; text-transform:uppercase; letter-spacing:0.1em; color:#94a3b8; margin:0 0 3px;">{{ $label }}</p>
                        <p style="font-weight:600; font-size:0.88rem; color:#0c2340; margin:0;">{{ $value }}</p>
                    </div>
                </div>
                @endforeach

                {{-- Offices --}}
                <div style="background:#fff; border-radius:12px; padding:1.25rem; border:1px solid #e2e8f0; box-shadow:0 1px 6px rgba(0,0,0,0.04);">
                    <p style="font-weight:800; font-size:0.85rem; color:#0c2340; margin:0 0 0.75rem;">Specific Offices</p>
                    <div class="space-y-2">
                        @foreach(['Registrar\'s Office','Finance / Accounting Office','Principal\'s Office','Guidance Office'] as $office)
                        <div style="display:flex; align-items:center; gap:8px; font-size:0.82rem; color:#475569;">
                            <span style="width:6px; height:6px; background:#55afe1; border-radius:50%; flex-shrink:0;"></span>
                            {{ $office }}
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Inquiry Form --}}
            <div class="lg:col-span-3">
                <div style="background:#fff; border-radius:16px; border:1px solid #e2e8f0; box-shadow:0 4px 20px rgba(0,0,0,0.06); padding:2.5rem;">
                    <h3 style="font-weight:800; font-size:1.1rem; color:#0c2340; margin:0 0 0.25rem;">Send an Inquiry</h3>
                    <p style="font-size:0.82rem; color:#64748b; margin:0 0 1.75rem;">Fill out the form below and we'll get back to you within 1-2 business days.</p>

                    <form class="space-y-4">
                        @csrf
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label style="display:block; font-size:0.75rem; font-weight:700; color:#374151; margin-bottom:6px; text-transform:uppercase; letter-spacing:0.05em;">Full Name <span style="color:#ef4444;">*</span></label>
                                <input type="text" placeholder="Juan Dela Cruz" required
                                    style="width:100%; border:1.5px solid #e2e8f0; border-radius:8px; padding:10px 14px; font-size:0.85rem; font-family:inherit; outline:none; box-sizing:border-box; transition:border-color 0.2s;"
                                    onfocus="this.style.borderColor='#0d4c8f'" onblur="this.style.borderColor='#e2e8f0'">
                            </div>
                            <div>
                                <label style="display:block; font-size:0.75rem; font-weight:700; color:#374151; margin-bottom:6px; text-transform:uppercase; letter-spacing:0.05em;">Email Address <span style="color:#ef4444;">*</span></label>
                                <input type="email" placeholder="you@email.com" required
                                    style="width:100%; border:1.5px solid #e2e8f0; border-radius:8px; padding:10px 14px; font-size:0.85rem; font-family:inherit; outline:none; box-sizing:border-box; transition:border-color 0.2s;"
                                    onfocus="this.style.borderColor='#0d4c8f'" onblur="this.style.borderColor='#e2e8f0'">
                            </div>
                        </div>
                        <div>
                            <label style="display:block; font-size:0.75rem; font-weight:700; color:#374151; margin-bottom:6px; text-transform:uppercase; letter-spacing:0.05em;">Contact Number</label>
                            <input type="tel" placeholder="+63 900 000 0000"
                                style="width:100%; border:1.5px solid #e2e8f0; border-radius:8px; padding:10px 14px; font-size:0.85rem; font-family:inherit; outline:none; box-sizing:border-box; transition:border-color 0.2s;"
                                onfocus="this.style.borderColor='#0d4c8f'" onblur="this.style.borderColor='#e2e8f0'">
                        </div>
                        <div>
                            <label style="display:block; font-size:0.75rem; font-weight:700; color:#374151; margin-bottom:6px; text-transform:uppercase; letter-spacing:0.05em;">Inquiry Type</label>
                            <select style="width:100%; border:1.5px solid #e2e8f0; border-radius:8px; padding:10px 14px; font-size:0.85rem; font-family:inherit; outline:none; box-sizing:border-box; background:#fff; appearance:none;">
                                <option value="">— Select topic —</option>
                                <option>Enrollment / Admission</option>
                                <option>Tuition Fees & Payment</option>
                                <option>ESC Grant / SHS Voucher</option>
                                <option>Programs & Curriculum</option>
                                <option>Transfer / Transferee</option>
                                <option>Other</option>
                            </select>
                        </div>
                        <div>
                            <label style="display:block; font-size:0.75rem; font-weight:700; color:#374151; margin-bottom:6px; text-transform:uppercase; letter-spacing:0.05em;">Message <span style="color:#ef4444;">*</span></label>
                            <textarea rows="5" placeholder="Type your question or message here…" required
                                style="width:100%; border:1.5px solid #e2e8f0; border-radius:8px; padding:10px 14px; font-size:0.85rem; font-family:inherit; outline:none; box-sizing:border-box; resize:vertical; transition:border-color 0.2s;"
                                onfocus="this.style.borderColor='#0d4c8f'" onblur="this.style.borderColor='#e2e8f0'"></textarea>
                        </div>
                        <button type="submit"
                            style="width:100%; background:#0d4c8f; color:#fff; font-family:inherit; font-weight:700; font-size:0.82rem; letter-spacing:0.1em; text-transform:uppercase; padding:13px; border-radius:8px; border:none; cursor:pointer; transition:background 0.2s;"
                            onmouseover="this.style.background='#093462'" onmouseout="this.style.background='#0d4c8f'">
                            Send Inquiry
                        </button>
                        <p style="font-size:0.75rem; color:#94a3b8; text-align:center; margin:0;">
                            Or email us directly at <a href="mailto:info@mmsc.edu.ph" style="color:#55afe1; font-weight:600;">info@mmsc.edu.ph</a>
                        </p>
                    </form>
                </div>
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
