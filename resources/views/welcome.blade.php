


{{-- resources/views/welcome.blade.php --}}

@extends('layout.welcome')

@section('title', 'My Messiah School of Cavite')

@section('content')
    <!-- ===================== HERO / LANDING ===================== -->
    <section id="landingPage"
        class="relative min-h-screen flex flex-col items-center justify-center text-center px-4 pt-16"
        style="background-image: linear-gradient(to bottom, rgba(13,96,184,0.9) 0%, rgba(26,94,158,0.75) 25%, rgba(48,125,183,0.6) 50%, rgba(141,204,238,0.75) 75%, rgba(233,244,250,0.95) 100%), url('{{ asset('public/images/landing bg.png') }}'); background-size: cover; background-position: center;">

        <div class="max-w-3xl mx-auto">
            <p class="text-blue-200 text-sm font-semibold uppercase tracking-widest mb-4 animate-pulse">Welcome to MMSC</p>

            <h2 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-white leading-tight mb-5 drop-shadow-lg">
                Empowering Students for
                <span class="block text-blue-200">a Brighter Tomorrow</span>
            </h2>

            <p class="text-blue-100 text-lg sm:text-xl max-w-xl mx-auto mb-10 drop-shadow">
                Quality education. Innovative learning. Endless possibilities.
            </p>

            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="#"
                    class="inline-flex items-center justify-center px-7 py-3.5 text-sm font-bold text-white bg-blue-600 hover:bg-blue-500 rounded-xl shadow-lg shadow-blue-600/40 hover:shadow-blue-500/50 hover:-translate-y-0.5 transition-all duration-200">
                    Apply Now
                    <i class="ri-arrow-right-line ml-2"></i>
                </a>
                <a href="#cards"
                    class="inline-flex items-center justify-center px-7 py-3.5 text-sm font-bold text-white border-2 border-white/70 hover:border-white hover:bg-white/10 rounded-xl backdrop-blur-sm hover:-translate-y-0.5 transition-all duration-200">
                    Explore Programs
                </a>
            </div>
        </div>

        <!-- Slideshow Dots -->
        <div class="absolute bottom-8 flex items-center gap-2">
            <span class="dot active h-2 w-6 bg-white rounded-full cursor-pointer transition-all duration-300" data-index="0"></span>
            <span class="dot h-2 w-2 bg-white/50 rounded-full cursor-pointer hover:bg-white/80 transition-all duration-300" data-index="1"></span>
            <span class="dot h-2 w-2 bg-white/50 rounded-full cursor-pointer hover:bg-white/80 transition-all duration-300" data-index="2"></span>
        </div>
    </section>


    <!-- ===================== FULL PHOTO STRIP ===================== -->
    <section class="w-full h-56 sm:h-72 lg:h-96 overflow-hidden">
        <img src="{{ asset('public/images/bg messiah.jpg') }}" alt="Messiah School" class="w-full h-full object-cover">
    </section>


    <!-- ===================== PROGRAMS ===================== -->
    <section id="cards" class="py-20 bg-slate-50 dark:bg-slate-900/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="mb-12">
                <p class="text-blue-600 dark:text-blue-400 text-sm font-bold uppercase tracking-widest mb-2">What We Offer</p>
                <h3 class="text-3xl sm:text-4xl font-extrabold text-slate-800 dark:text-white">Our Programs</h3>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

                <!-- Pre School -->
                <div class="program-card bg-white dark:bg-slate-800 rounded-2xl shadow-sm hover:shadow-xl border border-slate-100 dark:border-slate-700 overflow-hidden flex flex-col">
                    <div class="h-1.5 bg-gradient-to-r from-yellow-400 to-orange-400"></div>
                    <div class="p-6 flex-1">
                        <div class="w-10 h-10 bg-yellow-100 dark:bg-yellow-900/30 rounded-xl flex items-center justify-center mb-4">
                            <i class="ri-seedling-line text-yellow-500 text-xl"></i>
                        </div>
                        <h5 class="text-lg font-bold text-slate-800 dark:text-white mb-1">Pre School</h5>
                        <small class="text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Early Childhood Education</small>
                        <p class="mt-3 text-sm text-slate-600 dark:text-slate-300 leading-relaxed">
                            A fun and nurturing environment that sparks curiosity and builds foundational skills for lifelong learning.
                        </p>
                    </div>
                    <div class="px-6 pb-6">
                        <a href="#" class="inline-flex items-center text-sm font-semibold text-blue-600 dark:text-blue-400 hover:gap-2 gap-1 transition-all group">
                            Read More <i class="ri-arrow-right-line group-hover:translate-x-1 transition-transform"></i>
                        </a>
                    </div>
                </div>

                <!-- Elementary -->
                <div class="program-card bg-white dark:bg-slate-800 rounded-2xl shadow-sm hover:shadow-xl border border-slate-100 dark:border-slate-700 overflow-hidden flex flex-col">
                    <div class="h-1.5 bg-gradient-to-r from-green-400 to-emerald-500"></div>
                    <div class="p-6 flex-1">
                        <div class="w-10 h-10 bg-green-100 dark:bg-green-900/30 rounded-xl flex items-center justify-center mb-4">
                            <i class="ri-book-open-line text-green-500 text-xl"></i>
                        </div>
                        <h5 class="text-lg font-bold text-slate-800 dark:text-white mb-1">Elementary</h5>
                        <small class="text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Primary Education (Grades 1–6)</small>
                        <p class="mt-3 text-sm text-slate-600 dark:text-slate-300 leading-relaxed">
                            Comprehensive academic program that strengthens literacy, numeracy, and creativity in young learners.
                        </p>
                    </div>
                    <div class="px-6 pb-6">
                        <a href="#" class="inline-flex items-center text-sm font-semibold text-blue-600 dark:text-blue-400 hover:gap-2 gap-1 transition-all group">
                            Read More <i class="ri-arrow-right-line group-hover:translate-x-1 transition-transform"></i>
                        </a>
                    </div>
                </div>

                <!-- Junior High -->
                <div class="program-card bg-white dark:bg-slate-800 rounded-2xl shadow-sm hover:shadow-xl border border-slate-100 dark:border-slate-700 overflow-hidden flex flex-col">
                    <div class="h-1.5 bg-gradient-to-r from-blue-400 to-indigo-500"></div>
                    <div class="p-6 flex-1">
                        <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-xl flex items-center justify-center mb-4">
                            <i class="ri-graduation-cap-line text-blue-500 text-xl"></i>
                        </div>
                        <h5 class="text-lg font-bold text-slate-800 dark:text-white mb-1">Junior High School</h5>
                        <small class="text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Lower Secondary (Grades 7–10)</small>
                        <p class="mt-3 text-sm text-slate-600 dark:text-slate-300 leading-relaxed">
                            Academic program that strengthens literacy, numeracy, and creativity in learners.
                        </p>
                    </div>
                    <div class="px-6 pb-6">
                        <a href="#" class="inline-flex items-center text-sm font-semibold text-blue-600 dark:text-blue-400 hover:gap-2 gap-1 transition-all group">
                            Read More <i class="ri-arrow-right-line group-hover:translate-x-1 transition-transform"></i>
                        </a>
                    </div>
                </div>

                <!-- Senior High -->
                <div class="program-card bg-white dark:bg-slate-800 rounded-2xl shadow-sm hover:shadow-xl border border-slate-100 dark:border-slate-700 overflow-hidden flex flex-col">
                    <div class="h-1.5 bg-gradient-to-r from-purple-500 to-pink-500"></div>
                    <div class="p-6 flex-1">
                        <div class="w-10 h-10 bg-purple-100 dark:bg-purple-900/30 rounded-xl flex items-center justify-center mb-4">
                            <i class="ri-award-line text-purple-500 text-xl"></i>
                        </div>
                        <h5 class="text-lg font-bold text-slate-800 dark:text-white mb-1">Senior High School</h5>
                        <small class="text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Upper Secondary (Grades 11–12)</small>
                        <p class="mt-3 text-sm text-slate-600 dark:text-slate-300 leading-relaxed">
                            Focused academic and technical programs to prepare students for college.
                        </p>
                        <div class="mt-3 space-y-1">
                            <p class="text-xs text-slate-600 dark:text-slate-400">
                                <span class="font-bold text-purple-600 dark:text-purple-400">Academic:</span> STEM, ABM, HUMSS
                            </p>
                            <p class="text-xs text-slate-600 dark:text-slate-400">
                                <span class="font-bold text-purple-600 dark:text-purple-400">TVL:</span> Tourism, Cookery, ICT, IA, BPP
                            </p>
                        </div>
                    </div>
                    <div class="px-6 pb-6">
                        <a href="#" class="inline-flex items-center text-sm font-semibold text-blue-600 dark:text-blue-400 hover:gap-2 gap-1 transition-all group">
                            Read More <i class="ri-arrow-right-line group-hover:translate-x-1 transition-transform"></i>
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </section>
@endsection


 {{--  Push scripts  --}}
@push('scripts')
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://kit.fontawesome.com/a81368914c.js" crossorigin="anonymous"></script>
@endpush