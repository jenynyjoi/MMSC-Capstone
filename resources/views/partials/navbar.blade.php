<!-- !-- ===================== NAVBAR ===================== -->
    <header>
    <nav id="navbar" class="fixed top-0 left-0 right-0 z-50 bg-white/90 dark:bg-slate-900/90 backdrop-blur-sm border-b border-slate-100 dark:border-slate-800 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">

                <a href="{{ url('/') }}" class="flex items-center gap-3 group">
                    <img src="{{ asset('images/logos.jpg') }}"alt="Logos" class="rounded-lg ">
                    <span class="hidden sm:block text-sm font-bold text-slate-700 dark:text-white tracking-wide leading-tight uppercase">
                        My Messiah School of Cavite
                    </span>
                </a>

                <ul class="hidden lg:flex items-center gap-1">
                    <li>
                        <a href="{{ url('/') }}" class="px-4 py-2 text-sm font-medium text-slate-700 dark:text-slate-300 hover:text-blue-600 dark:hover:text-blue-400 rounded-lg hover:bg-blue-50 dark:hover:bg-slate-800 transition-all">
                            Home
                        </a>
                    </li>
                    <li>
                        <a href="#" class="px-4 py-2 text-sm font-medium text-slate-700 dark:text-slate-300 hover:text-blue-600 dark:hover:text-blue-400 rounded-lg hover:bg-blue-50 dark:hover:bg-slate-800 transition-all">
                            About
                        </a>
                    </li>

                    <li x-data="{ open: false }" class="relative" @mouseenter="open = true" @mouseleave="open = false">
                        <button class="flex items-center gap-1 px-4 py-2 text-sm font-medium text-slate-700 dark:text-slate-300 hover:text-blue-600 dark:hover:text-blue-400 rounded-lg hover:bg-blue-50 dark:hover:bg-slate-800 transition-all">
                            Programs
                            <i class="ri-arrow-down-s-line transition-transform duration-200" :class="open ? 'rotate-180' : ''"></i>
                        </button>
                        <div x-show="open" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                            class="absolute top-full left-0 mt-1 w-48 bg-white dark:bg-slate-800 rounded-xl shadow-lg border border-slate-100 dark:border-slate-700 py-1 z-50">
                            @foreach(['Senior High School', 'Junior High School', 'Elementary', 'Pre-School'] as $program)
                                <a href="#" class="block px-4 py-2 text-sm text-slate-700 dark:text-slate-300 hover:bg-blue-50 dark:hover:bg-slate-700 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                                    {{ $program }}
                                </a>
                            @endforeach
                        </div>
                    </li>

                    <li x-data="{ open: false }" class="relative" @mouseenter="open = true" @mouseleave="open = false">
                        <button class="flex items-center gap-1 px-4 py-2 text-sm font-medium text-slate-700 dark:text-slate-300 hover:text-blue-600 dark:hover:text-blue-400 rounded-lg hover:bg-blue-50 dark:hover:bg-slate-800 transition-all">
                            Admission
                            <i class="ri-arrow-down-s-line transition-transform duration-200" :class="open ? 'rotate-180' : ''"></i>
                        </button>
                        
                        <div x-show="open" 
                            x-transition:enter="transition ease-out duration-150" 
                            x-transition:enter-start="opacity-0 -translate-y-1" 
                            x-transition:enter-end="opacity-100 translate-y-0" 
                            x-transition:leave="transition ease-in duration-100" 
                            x-transition:leave-start="opacity-100" 
                            x-transition:leave-end="opacity-0"
                            class="absolute top-full left-0 mt-1 w-52 bg-white dark:bg-slate-800 rounded-xl shadow-lg border border-slate-100 dark:border-slate-700 py-1 z-50">
                            
                            @foreach(['Requirements', 'Enrollment Process', 'Online Application'] as $item)
                                <a href="{{ $item === 'Online Application' ? route('online.registration') : '#' }}" 
                                   class="block px-4 py-2 text-sm text-slate-700 dark:text-slate-300 hover:bg-blue-50 dark:hover:bg-slate-700 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                                    {{ $item }}
                                </a>
                            @endforeach
                        </div>
                    </li>

                    <li>
                        <a href="#" class="px-4 py-2 text-sm font-medium text-slate-700 dark:text-slate-300 hover:text-blue-600 dark:hover:text-blue-400 rounded-lg hover:bg-blue-50 dark:hover:bg-slate-800 transition-all">
                            Contact
                        </a>
                    </li>
                </ul>

                <div class="flex items-center gap-2">
                    <button @click="dark = !dark"
                        class="w-9 h-9 flex items-center justify-center rounded-full border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-all"
                        aria-label="Toggle theme">
                        <i class="fa-regular fa-moon text-sm" x-show="!dark"></i>
                        <i class="fa-regular fa-sun text-sm" x-show="dark"></i>
                    </button>

                    <a href="{{ route('login') }}"
                        class="hidden sm:inline-flex items-center px-4 py-2 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-lg shadow-sm shadow-blue-500/30 transition-all">
                        Login 
                    </a>

                    <button @click="mobileMenu = !mobileMenu"
                        class="lg:hidden w-9 h-9 flex items-center justify-center rounded-lg text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-all">
                        <i class="ri-menu-line text-xl" x-show="!mobileMenu"></i>
                        <i class="ri-close-line text-xl" x-show="mobileMenu"></i>
                    </button>
                </div>
            </div>
        </div>

        <div x-show="mobileMenu"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 -translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="lg:hidden border-t border-slate-100 dark:border-slate-800 bg-white dark:bg-slate-900 px-4 pb-4 pt-2 space-y-1">

            <a href="{{ url('/') }}" class="block px-3 py-2.5 text-sm font-medium text-slate-700 dark:text-slate-300 hover:bg-blue-50 dark:hover:bg-slate-800 rounded-lg transition-colors">Home</a>
            <a href="#" class="block px-3 py-2.5 text-sm font-medium text-slate-700 dark:text-slate-300 hover:bg-blue-50 dark:hover:bg-slate-800 rounded-lg transition-colors">About</a>

            <div x-data="{ open: false }">
                <button @click="open = !open" class="w-full flex items-center justify-between px-3 py-2.5 text-sm font-medium text-slate-700 dark:text-slate-300 hover:bg-blue-50 dark:hover:bg-slate-800 rounded-lg transition-colors">
                    Programs
                    <i class="ri-arrow-down-s-line transition-transform" :class="open ? 'rotate-180' : ''"></i>
                </button>
                <div x-show="open" class="pl-4 mt-1 space-y-1">
                    @foreach(['Senior High School', 'Junior High School', 'Elementary', 'Pre-School'] as $program)
                        <a href="#" class="block px-3 py-2 text-sm text-slate-600 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-blue-50 dark:hover:bg-slate-800 rounded-lg transition-colors">{{ $program }}</a>
                    @endforeach
                </div>
            </div>

            <div x-data="{ open: false }">
                <button @click="open = !open" class="w-full flex items-center justify-between px-3 py-2.5 text-sm font-medium text-slate-700 dark:text-slate-300 hover:bg-blue-50 dark:hover:bg-slate-800 rounded-lg transition-colors">
                    Admission
                    <i class="ri-arrow-down-s-line transition-transform" :class="open ? 'rotate-180' : ''"></i>
                </button>
                <div x-show="open" class="pl-4 mt-1 space-y-1">
                    @foreach(['Requirements', 'Enrollment Process', 'Online Application'] as $item)
                        <a href="{{ $item === 'Online Application' ? route('online.registration') : '#' }}" 
                           class="block px-3 py-2 text-sm text-slate-600 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-blue-50 dark:hover:bg-slate-800 rounded-lg transition-colors">{{ $item }}</a>
                    @endforeach
                </div>
            </div>

            <a href="#" class="block px-3 py-2.5 text-sm font-medium text-slate-700 dark:text-slate-300 hover:bg-blue-50 dark:hover:bg-slate-800 rounded-lg transition-colors">Contact</a>

            <div class="pt-2 border-t border-slate-100 dark:border-slate-800">
               <a href="{{ route('login') }}" 
                class="block w-full text-center px-4 py-2.5 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-all">
                Login
                </a>
            </div>
        </div>
    </nav>
</header>