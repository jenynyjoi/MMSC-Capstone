 <!-- ===================== NAVBAR ===================== -->
    <header>
        <nav id="navbar" class="fixed top-0 left-0 right-0 z-50 bg-white/90 backdrop-blur-sm border-b border-slate-100 transition-all duration-300">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">

                    <!-- Logo -->
                    <a href="{{ url('/') }}" class="flex items-center gap-3 group">
                        <img src="{{ asset('images/full-logo.jpg') }}" alt="Logos">

                        <!-- <img src="{{ asset('images/full-logo.jpg') }}" alt="Logossdsd" class="w-10 h-10 object-contain"> -->
                        <span class="hidden sm:block text-sm font-bold text-slate-700 tracking-wide leading-tight uppercase">
                            My Messiah School of Cavite
                        </span>
                    </a>


                    <!-- Desktop Nav Links -->
                    <ul class="hidden lg:flex items-center gap-1">
                        <li>
                            <a href="#" class="px-4 py-2 text-sm font-medium text-slate-700 hover:text-blue-600 rounded-lg hover:bg-blue-50 transition-all">
                                Home
                            </a>
                        </li>
                        <li>
                            <a href="#" class="px-4 py-2 text-sm font-medium text-slate-700 hover:text-blue-600 rounded-lg hover:bg-blue-50 transition-all">
                                About
                            </a>
                        </li>

                        <!-- Programs Dropdown -->
                        <li x-data="{ open: false }" class="relative" @mouseenter="open = true" @mouseleave="open = false">
                            <button class="flex items-center gap-1 px-4 py-2 text-sm font-medium text-slate-700 hover:text-blue-600 rounded-lg hover:bg-blue-50 transition-all">
                                Programs
                                <i class="ri-arrow-down-s-line transition-transform duration-200" :class="open ? 'rotate-180' : ''"></i>
                            </button>
                            <div x-show="open" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                                class="absolute top-full left-0 mt-1 w-48 bg-white rounded-xl shadow-lg border border-slate-100 py-1 z-50">
                                @foreach(['Senior High School', 'Junior High School', 'Elementary', 'Pre-School'] as $program)
                                    <a href="#" class="block px-4 py-2 text-sm text-slate-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">
                                        {{ $program }}
                                    </a>
                                @endforeach
                            </div>
                        </li>

                        <!-- Admission Dropdown -->
                        <li x-data="{ open: false }" class="relative" @mouseenter="open = true" @mouseleave="open = false">
                            <button class="flex items-center gap-1 px-4 py-2 text-sm font-medium text-slate-700 hover:text-blue-600 rounded-lg hover:bg-blue-50 transition-all">
                                Admission
                                <i class="ri-arrow-down-s-line transition-transform duration-200" :class="open ? 'rotate-180' : ''"></i>
                            </button>
                            <div x-show="open" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                                class="absolute top-full left-0 mt-1 w-52 bg-white rounded-xl shadow-lg border border-slate-100 py-1 z-50">
                                @foreach(['Requirements', 'Enrollment Process', 'Online Application'] as $item)
                                    <a href="#" class="block px-4 py-2 text-sm text-slate-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">
                                        {{ $item }}
                                    </a>
                                @endforeach
                            </div>
                        </li>

                        <li>
                            <a href="#" class="px-4 py-2 text-sm font-medium text-slate-700 hover:text-blue-600 rounded-lg hover:bg-blue-50 transition-all">
                                Contact
                            </a>
                        </li>
                    </ul>

                    <!-- Right Actions -->
                    <div class="flex items-center gap-2">
                        <!-- Theme Toggle -->
                        <button @click="dark = !dark"
                            class="w-9 h-9 flex items-center justify-center rounded-full border border-slate-200 text-slate-600 hover:bg-slate-100 transition-all"
                            aria-label="Toggle theme">
                            <i class="fa-regular fa-moon text-sm" x-show="!dark"></i>
                            <i class="fa-regular fa-sun text-sm" x-show="dark"></i>
                        </button>

                        <!-- Login Button -->
                        <!-- <a href="{{ route('login') }}" -->
                        <a href="{{ route('login') }}"
                            class="hidden sm:inline-flex items-center px-4 py-2 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-lg shadow-sm shadow-blue-500/30 transition-all">
                            Login 
                        </a>

                        <!-- Mobile Hamburger -->
                        <button @click="mobileMenu = !mobileMenu"
                            class="lg:hidden w-9 h-9 flex items-center justify-center rounded-lg text-slate-600 hover:bg-slate-100 transition-all">
                            <i class="ri-menu-line text-xl" x-show="!mobileMenu"></i>
                            <i class="ri-close-line text-xl" x-show="mobileMenu"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Mobile Menu -->
            <div x-show="mobileMenu"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 -translate-y-2"
                x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="lg:hidden border-t border-slate-100 bg-white px-4 pb-4 pt-2 space-y-1">

                <a href="#" class="block px-3 py-2.5 text-sm font-medium text-slate-700 hover:bg-blue-50 rounded-lg transition-colors">Home</a>
                <a href="#" class="block px-3 py-2.5 text-sm font-medium text-slate-700 hover:bg-blue-50 rounded-lg transition-colors">About</a>

                <!-- Mobile Programs -->
                <div x-data="{ open: false }">
                    <button @click="open = !open" class="w-full flex items-center justify-between px-3 py-2.5 text-sm font-medium text-slate-700 hover:bg-blue-50 rounded-lg transition-colors">
                        Programs
                        <i class="ri-arrow-down-s-line transition-transform" :class="open ? 'rotate-180' : ''"></i>
                    </button>
                    <div x-show="open" class="pl-4 mt-1 space-y-1">
                        @foreach(['Senior High School', 'Junior High School', 'Elementary', 'Pre-School'] as $program)
                            <a href="#" class="block px-3 py-2 text-sm text-slate-600 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">{{ $program }}</a>
                        @endforeach
                    </div>
                </div>

                <!-- Mobile Admission -->
                <div x-data="{ open: false }">
                    <button @click="open = !open" class="w-full flex items-center justify-between px-3 py-2.5 text-sm font-medium text-slate-700 hover:bg-blue-50 rounded-lg transition-colors">
                        Admission
                        <i class="ri-arrow-down-s-line transition-transform" :class="open ? 'rotate-180' : ''"></i>
                    </button>
                    <div x-show="open" class="pl-4 mt-1 space-y-1">
                        @foreach(['Requirements', 'Enrollment Process', 'Online Application'] as $item)
                            <a href="#" class="block px-3 py-2 text-sm text-slate-600 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">{{ $item }}</a>
                        @endforeach
                    </div>
                </div>

                <a href="#" class="block px-3 py-2.5 text-sm font-medium text-slate-700 hover:bg-blue-50 rounded-lg transition-colors">Contact</a>

                <div class="pt-2 border-t border-slate-100">
                    <a href="{{ route('login') }}" class="block w-full text-center px-4 py-2.5 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-all">
                        Login
                    </a>
                </div>
            </div>
        </nav>
    </header>