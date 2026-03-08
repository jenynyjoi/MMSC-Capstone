  <!-- Sidebar -->
        <aside id="sidebar" class="sidebar-transition fixed inset-y-0 left-0 z-50 w-64 -translate-x-full bg-white lg:static lg:translate-x-0 dark:border-dark-border dark:bg-dark-card flex flex-col justify-between">
            <div class="">
                <!-- Logo -->
                <div class="flex h-16 items-center justify-between px-6 border-b border-white/10 bg-[#0d4c8f] dark:bg-[#0a3a6e]">
                    <div class="flex items-center gap-2">
                   <img src="{{ asset('img/download.jpg') }}" class="h-10 w-10 rounded-full object-cover">                        
                        <div class="flex flex-col leading-tight">
                            <span id="logo-text" class="text-xl font-bold text-white tracking-tight font-poppins">MMSC</span>
                            <span id="logo-text" class="text-xs text-white/70 font-medium">Admin</span>
                        </div>
                    </div>
                </div>

                <!-- Nav bar content --> 
                <nav class="space-y-1 px-3 py-6"> 
                    @php 
                        $navItems = [
                            'Dashboard'       => 'boxicons:dashboard-filled',
                            'Admission'       => 'material-symbols:other-admission',
                            'Enrollment'      => 'lets-icons:user-fill-add',
                            'Student Records' => 'ph:folders-fill',
                            'Clearance'       => 'tdesign:task-checked-filled',
                            'Academics'       => 'mdi:books',
                            'Classes'         => 'icon-park-solid:bell-ring',
                            'Schedule'        => 'uis:schedule',
                            'Teachers'        => 'fa6-solid:user-tie',
                            'Announcements'   => 'mdi:announcement',
                            'Reports'         => 'mage:file-2-fill',
                            'Settings'        => 'material-symbols:settings',
                        ];
                    @endphp
                    @foreach ($navItems as $label => $icon)
                        <a href="#" class="group flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-slate-900 dark:text-slate-400 dark:hover:bg-white/5 dark:hover:text-white transition-all @if($label === 'Dashboard') bg-brand-50 dark:bg-brand-900/20 dark:text-brand-100 @endif" title="{{ $label }}">
                            <iconify-icon icon="{{ $icon }}" width="20" stroke-width="1.5"></iconify-icon>
                            <span class="nav-text">{{ $label }}</span>
                        </a>
                    @endforeach
                </nav>
            </div>

        </aside>
