@extends('layouts.welcome')
@section('title', 'Online Registration — Step 3')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800;900&display=swap');
    body { font-family: 'Montserrat', sans-serif; }
</style>

{{-- ── Registration Header ── --}}
<div style="padding-top:64px; background:linear-gradient(135deg, #0c2340 0%, #0d4c8f 60%, #0891b2 100%);">

    <div class="max-w-3xl mx-auto px-4 pt-8 pb-6 text-center">
        <p style="font-size:0.65rem; font-weight:700; letter-spacing:0.22em; text-transform:uppercase; color:#7dd3fc; margin-bottom:4px;">My Messiah School of Cavite</p>
        <h1 style="font-size:1.5rem; font-weight:800; color:#fff; margin:0 0 2px;">Online Admission Application</h1>
        <p style="font-size:0.8rem; color:rgba(255,255,255,0.65);">Academic Year 2026–2027</p>
    </div>

    <div class="max-w-2xl mx-auto px-4 pb-8">
        @include('online-registration._stepper', ['currentStep' => 3])
    </div>
</div>

{{-- ── Form ── --}}
<section style="background:#f1f5f9; padding:2.5rem 0; min-height:70vh;">
    <div class="max-w-2xl mx-auto px-4">

        <div style="display:flex; align-items:center; gap:10px; margin-bottom:1.5rem;">
            <div style="width:36px; height:36px; background:#0d4c8f; border-radius:10px; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                <svg width="18" height="18" fill="none" stroke="#fff" stroke-width="2.5" viewBox="0 0 24 24"><path d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </div>
            <div>
                <h2 style="font-size:1rem; font-weight:800; color:#0c2340; margin:0;">Document Upload</h2>
                <p style="font-size:0.75rem; color:#64748b; margin:0;">Upload supporting documents for faster processing (optional)</p>
            </div>
        </div>

        <form method="POST" action="{{ route('online.registration.save-step3') }}" enctype="multipart/form-data">
            @csrf

            <div style="background:#fff; border-radius:16px; border:1px solid #e2e8f0; box-shadow:0 2px 12px rgba(0,0,0,0.05); padding:1.75rem; margin-bottom:1.25rem;">

                {{-- Notice banner --}}
                <div style="background:#eff6ff; border:1px solid #bfdbfe; border-radius:10px; padding:1rem 1.25rem; margin-bottom:1.5rem; display:flex; gap:10px; align-items:flex-start;">
                    <svg width="18" height="18" fill="none" stroke="#0d4c8f" stroke-width="2" viewBox="0 0 24 24" style="flex-shrink:0; margin-top:1px;"><path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    <div>
                        <p style="font-size:0.8rem; font-weight:700; color:#0d4c8f; margin:0 0 2px;">Document Upload is Optional</p>
                        <p style="font-size:0.75rem; color:#1e40af; margin:0; line-height:1.5;">Uploading here speeds up initial screening. Original hard copies are still required when you visit the Registrar's Office for final enrollment.</p>
                    </div>
                </div>

                {{-- Upload items --}}
                @foreach([
                    ['psa',         'PSA Birth Certificate',    'Required for all applicants. Certified true copy preferred.'],
                    ['report_card', 'Report Card (Form 138)',   'Most recent card with official school stamp.'],
                    ['good_moral',  'Good Moral Certificate',   'Issued by previous school. Not required for incoming Grade 1/Kinder.'],
                ] as [$name, $label, $hint])
                <div style="padding:1.25rem; border-radius:10px; border:1px solid #e2e8f0; margin-bottom:1rem; background:#fafafa;">
                    <div style="display:flex; justify-content:space-between; align-items:flex-start; gap:12px; flex-wrap:wrap;">
                        <div style="flex:1; min-width:0;">
                            <p style="font-size:0.85rem; font-weight:700; color:#0c2340; margin:0 0 3px;">{{ $label }}</p>
                            <p style="font-size:0.72rem; color:#94a3b8; margin:0; line-height:1.4;">{{ $hint }}</p>
                        </div>
                        <label style="cursor:pointer; flex-shrink:0;">
                            <span id="{{ $name }}_btn" style="display:inline-flex; align-items:center; gap:6px; padding:8px 16px; border-radius:7px; border:1.5px solid #0d4c8f; background:#fff; font-size:0.75rem; font-weight:700; color:#0d4c8f; letter-spacing:0.05em; transition:background 0.2s;"
                                onmouseover="this.style.background='#eff6ff'" onmouseout="this.style.background='#fff'">
                                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                Choose File
                            </span>
                            <input type="file" name="{{ $name }}" accept=".pdf,.jpg,.jpeg,.png" style="display:none;"
                                onchange="updateFile(this, '{{ $name }}')">
                        </label>
                    </div>
                    <div id="{{ $name }}_preview" style="display:none; margin-top:0.75rem; border-radius:8px; overflow:hidden; border:1px solid #e2e8f0;">
                        {{-- Image preview --}}
                        <div id="{{ $name }}_img_wrap" style="display:none; text-align:center; background:#f8fafc; padding:8px;">
                            <img id="{{ $name }}_img" src="" alt="preview"
                                style="max-height:180px; max-width:100%; border-radius:6px; object-fit:contain; box-shadow:0 1px 6px rgba(0,0,0,0.10);">
                        </div>
                        {{-- PDF preview --}}
                        <div id="{{ $name }}_pdf_wrap" style="display:none; background:#fff5f5; padding:10px 14px; display:none;">
                            <div style="display:flex; align-items:center; gap:8px;">
                                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#dc2626" stroke-width="1.8"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z" stroke-linecap="round" stroke-linejoin="round"/><polyline points="14,2 14,8 20,8" stroke-linecap="round" stroke-linejoin="round"/><text x="6" y="18" font-size="5" fill="#dc2626" stroke="none" font-family="Arial" font-weight="bold">PDF</text></svg>
                                <div>
                                    <div id="{{ $name }}_name" style="font-size:0.8rem; font-weight:700; color:#1e293b; word-break:break-all;"></div>
                                    <div id="{{ $name }}_size" style="font-size:0.7rem; color:#94a3b8; margin-top:1px;"></div>
                                </div>
                            </div>
                        </div>
                        {{-- File name bar (always shown) --}}
                        <div style="padding:7px 12px; background:#f0fdf4; border-top:1px solid #bbf7d0; display:flex; align-items:center; gap:6px;">
                            <svg width="13" height="13" fill="none" stroke="#16a34a" stroke-width="2.5" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            <span id="{{ $name }}_bar_name" style="font-size:0.72rem; font-weight:600; color:#15803d; word-break:break-all;"></span>
                            <button type="button" onclick="removeFile('{{ $name }}')"
                                style="margin-left:auto; background:none; border:none; cursor:pointer; color:#94a3b8; font-size:1rem; line-height:1; padding:0 2px;"
                                title="Remove">&times;</button>
                        </div>
                    </div>
                </div>
                @endforeach

                <p style="font-size:0.72rem; color:#94a3b8; margin:0; text-align:center;">
                    Accepted: PDF, JPG, PNG &nbsp;·&nbsp; Max 5 MB per file
                </p>

            </div>

            {{-- Buttons --}}
            <div style="display:flex; align-items:center; justify-content:space-between; margin-top:0.5rem; padding-bottom:2rem;">
                <a href="{{ route('online.registration.step2') }}"
                   style="display:inline-flex; align-items:center; gap:6px; padding:11px 24px; border-radius:8px; border:1.5px solid #cbd5e1; background:#fff; font-size:0.82rem; font-weight:700; color:#64748b; text-decoration:none; letter-spacing:0.05em;"
                   onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#fff'">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M19 12H5M12 5l-7 7 7 7" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    Back
                </a>
                <button type="submit"
                    style="display:inline-flex; align-items:center; gap:6px; padding:11px 28px; border-radius:8px; background:#0d4c8f; color:#fff; font-size:0.82rem; font-weight:700; letter-spacing:0.08em; text-transform:uppercase; border:none; cursor:pointer; transition:background 0.2s;"
                    onmouseover="this.style.background='#093462'" onmouseout="this.style.background='#0d4c8f'">
                    Next Step
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </button>
            </div>

        </form>
    </div>
</section>

<script>
function updateFile(input, name) {
    const file     = input.files[0];
    const preview  = document.getElementById(name + '_preview');
    const imgWrap  = document.getElementById(name + '_img_wrap');
    const imgEl    = document.getElementById(name + '_img');
    const pdfWrap  = document.getElementById(name + '_pdf_wrap');
    const nameEl   = document.getElementById(name + '_name');
    const sizeEl   = document.getElementById(name + '_size');
    const barName  = document.getElementById(name + '_bar_name');

    if (!file) { preview.style.display = 'none'; return; }

    const isImage = file.type.startsWith('image/');
    const isPdf   = file.type === 'application/pdf';
    const sizeMB  = (file.size / 1048576).toFixed(2);

    imgWrap.style.display  = 'none';
    pdfWrap.style.display  = 'none';
    barName.textContent    = file.name;

    if (isImage) {
        const url = URL.createObjectURL(file);
        imgEl.onload = () => URL.revokeObjectURL(url);
        imgEl.src = url;
        imgWrap.style.display = 'block';
    } else if (isPdf) {
        if (nameEl) nameEl.textContent = file.name;
        if (sizeEl) sizeEl.textContent = sizeMB + ' MB';
        pdfWrap.style.display = 'block';
    }

    preview.style.display = 'block';
}

function removeFile(name) {
    const input   = document.querySelector('[name="' + name + '"]');
    const preview = document.getElementById(name + '_preview');
    const imgEl   = document.getElementById(name + '_img');
    if (input)  input.value = '';
    if (imgEl)  imgEl.src = '';
    preview.style.display = 'none';
}
</script>

@endsection
