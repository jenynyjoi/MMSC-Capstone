@php
$steps = [
    1 => 'Program Selection',
    2 => 'Personal Info',
    3 => 'Documents',
    4 => 'Review & Submit',
];
@endphp

<div style="position:relative; display:flex; align-items:flex-start; justify-content:space-between; max-width:560px; margin:0 auto;">

    {{-- Connecting line --}}
    <div style="position:absolute; top:19px; left:10%; right:10%; height:2px; background:rgba(255,255,255,0.2); z-index:0;"></div>

    @foreach($steps as $num => $label)
    @php
        $done    = $num < $currentStep;
        $active  = $num === $currentStep;
        $future  = $num > $currentStep;

        if ($done) {
            $circleBg    = '#22c55e';
            $circleText  = '#fff';
            $circleBorder = 'none';
        } elseif ($active) {
            $circleBg    = '#fff';
            $circleText  = '#0d4c8f';
            $circleBorder = 'none';
        } else {
            $circleBg    = 'transparent';
            $circleText  = 'rgba(255,255,255,0.5)';
            $circleBorder = '2px solid rgba(255,255,255,0.3)';
        }

        $labelColor = ($done || $active) ? 'rgba(255,255,255,0.95)' : 'rgba(255,255,255,0.4)';
    @endphp

    <div style="position:relative; z-index:1; display:flex; flex-direction:column; align-items:center; gap:6px; flex:1;">
        <div style="width:38px; height:38px; border-radius:50%; background:{{ $circleBg }}; color:{{ $circleText }}; border:{{ $circleBorder }}; display:flex; align-items:center; justify-content:center; font-size:0.8rem; font-weight:800; box-shadow:0 2px 8px rgba(0,0,0,0.15);">
            @if($done)
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round"/></svg>
            @else
                {{ $num }}
            @endif
        </div>
        <span style="font-size:0.62rem; font-weight:700; color:{{ $labelColor }}; text-align:center; line-height:1.3; max-width:70px; display:none;" class="sm-visible">{{ $label }}</span>
    </div>
    @endforeach
</div>

<style>
@media (min-width: 480px) {
    .sm-visible { display: block !important; }
}
</style>
