
    <!-- ===================== FOOTER ===================== -->
    <footer class="bg-slate-900 text-slate-300 pt-14 pb-0">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-10 pb-12">

                <!-- Logo -->
                <div class="lg:col-span-1 flex flex-col items-start gap-3">
                    <a href="{{ url('/') }}">
                        <img src="{{ asset('public/images/messiah-logo.png') }}" alt="My Messiah School Logo" class="w-20 h-20 object-contain">
                    </a>
                </div>

                <!-- School Info -->
                <div class="lg:col-span-1">
                    <h6 class="text-sm font-bold text-white uppercase tracking-wide mb-3">MY MESSIAH SCHOOL OF CAVITE, INC.</h6>
                    <p class="text-sm text-slate-400 leading-relaxed mb-2">
                        NHA Compound, Brgy. Poblacion 4117<br>
                        General Mariano Alvarez, Philippines
                    </p>
                    <p class="text-sm text-slate-400">mmsc@gmail.com</p>
                    <p class="text-sm text-slate-400">0946 226 9257</p>
                </div>

                <!-- About Links -->
                <div>
                    <h6 class="text-sm font-bold text-white uppercase tracking-wide mb-4">About</h6>
                    <ul class="space-y-2">
                        @foreach(['Vision and Mission', 'Core Values', 'Awards and Accreditation', 'MSSC Life', 'Contact Us', 'FAQs', 'Help'] as $link)
                            <li><a href="#" class="text-sm text-slate-400 hover:text-white hover:translate-x-0.5 inline-block transition-all">{{ $link }}</a></li>
                        @endforeach
                    </ul>
                </div>

                <!-- Admission Links -->
                <div>
                    <h6 class="text-sm font-bold text-white uppercase tracking-wide mb-4">Admission</h6>
                    <ul class="space-y-2">
                        @foreach(['Financial Aid', 'Requirements', 'Enrollment for Continuing Students', 'Tuition Fees', 'Enrollment Procedure'] as $link)
                            <li><a href="#" class="text-sm text-slate-400 hover:text-white hover:translate-x-0.5 inline-block transition-all">{{ $link }}</a></li>
                        @endforeach
                    </ul>
                </div>

                <!-- Programs Links -->
                <div>
                    <h6 class="text-sm font-bold text-white uppercase tracking-wide mb-4">Programs</h6>
                    <ul class="space-y-2">
                        @foreach(['Preschool', 'Elementary', 'Junior High School', 'Senior High School'] as $link)
                            <li><a href="#" class="text-sm text-slate-400 hover:text-white hover:translate-x-0.5 inline-block transition-all">{{ $link }}</a></li>
                        @endforeach
                    </ul>
                </div>

            </div>
        </div>

        <!-- Bottom Bar -->
        <div class="border-t border-slate-700/50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-5 flex flex-col sm:flex-row items-center justify-between gap-4">
                <p class="text-xs text-slate-500 text-center sm:text-left">
                    © 2025 My Messiah School of Cavite, Inc. All rights reserved. ·
                    <a href="#" class="hover:text-white underline underline-offset-2 transition-colors">Privacy Notice</a>
                </p>
                <div class="flex items-center gap-3">
                    @foreach([
                        ['fab fa-facebook-f', '#'],
                        ['fab fa-instagram', '#'],
                        ['fab fa-tiktok', '#'],
                        ['fab fa-linkedin-in', '#'],
                    ] as [$icon, $href])
                        <a href="{{ $href }}"
                            class="w-8 h-8 flex items-center justify-center rounded-full bg-slate-700 hover:bg-blue-600 text-slate-300 hover:text-white text-xs transition-all">
                            <i class="{{ $icon }}"></i>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </footer>
