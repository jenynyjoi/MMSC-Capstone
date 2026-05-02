import Chart from 'chart.js/auto';
window.Chart = Chart;

// keep references in the outer scope so helper functions can access them
let sidebar, overlay;
let isSidebarOpen = false;
let isIconOnly = false;
let isProfileMenuOpen = false;

// expose functions early so inline onclick works
window.toggleSidebar = function() {
    if (!sidebar) sidebar = document.getElementById('sidebar');
    if (!overlay) overlay = document.getElementById('sidebar-overlay');
    if (!sidebar || !overlay) return;
    
    isSidebarOpen = !isSidebarOpen;
    if (isSidebarOpen) {
        sidebar.classList.remove('-translate-x-full');
        overlay.classList.remove('hidden');
        setTimeout(() => overlay.classList.remove('opacity-0'), 10);
    } else {
        overlay.classList.add('opacity-0');
        sidebar.classList.add('-translate-x-full');
        setTimeout(() => overlay.classList.add('hidden'), 300);
    }
};

window.toggleSidebarCollapse = function() {
    if (!sidebar) sidebar = document.getElementById('sidebar');
    if (!sidebar) return;
    
    isIconOnly = !isIconOnly;
    sidebar.classList.toggle('sidebar-icon-only');

    if (isIconOnly) {
        window.dispatchEvent(new CustomEvent('sidebar-collapsed'));
    }

    const btnIcon = document.getElementById('collapse-icon');
    if (btnIcon) {
        btnIcon.setAttribute('icon', isIconOnly ? 'solar:hamburger-menu-linear' : 'rivet-icons:menu');
    }
};

window.toggleProfileMenu = function(event) {
    if (event) {
        event.preventDefault();
        event.stopPropagation();
    }
    const profileMenu = document.getElementById('profile-menu');
    if (!profileMenu) return;
    
    isProfileMenuOpen = !isProfileMenuOpen;
    if (isProfileMenuOpen) {
        profileMenu.classList.remove('hidden');
    } else {
        profileMenu.classList.add('hidden');
    }
};

// Close profile menu when clicking outside
document.addEventListener('click', (e) => {
    const profileDropdown = document.getElementById('profile-dropdown');
    if (profileDropdown && !profileDropdown.contains(e.target)) {
        const profileMenu = document.getElementById('profile-menu');
        if (profileMenu) {
            profileMenu.classList.add('hidden');
            isProfileMenuOpen = false;
        }
    }
});

// when any navigation item is clicked, return to normal on mobile
function setupNavLinks() {
    document.querySelectorAll('#sidebar nav a').forEach(link => {
        link.addEventListener('click', () => {
            if (window.innerWidth < 1024 && isSidebarOpen) {
                window.toggleSidebar();
            }
        });
    });
}

// Dark Mode Logic
function setupDarkMode() {
    const themeToggle = document.getElementById('theme-toggle');
    const themeIcon = document.getElementById('theme-icon');
    const html = document.documentElement;

    if (!themeToggle || !themeIcon) return;

    // Check local storage
    if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        html.classList.add('dark');
        themeIcon.setAttribute('icon', 'solar:moon-linear');
    } else {
        html.classList.remove('dark');
        themeIcon.setAttribute('icon', 'solar:sun-2-linear');
    }

    themeToggle.addEventListener('click', () => {
        html.classList.toggle('dark');
        if (html.classList.contains('dark')) {
            localStorage.theme = 'dark';
            themeIcon.setAttribute('icon', 'solar:moon-linear');
            updateCharts();
        } else {
            localStorage.theme = 'light';
            themeIcon.setAttribute('icon', 'solar:sun-2-linear');
            updateCharts();
        }
    });
}

// Charts Configuration
let enrollChart, moveChart, statusChart;

function initCharts() {
    const enrollCtx = document.getElementById('enrollmentChart');
    if (enrollCtx) {
        enrollChart = new Chart(enrollCtx, {
            type: 'bar',
            data: {
                labels: ['Pre School', 'Elementary', 'Junior HS', 'Senior HS'],
                datasets: [{
                    data: [270, 530, 480, 590],
                    backgroundColor: ['#22c55e', '#ef4444', '#60a5fa', '#bef264'],
                    borderRadius: 4,
                    borderSkipped: false,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { grid: { display: false }, ticks: { color: '#94a3b8', font: { size: 11 } } },
                    y: { grid: { color: '#f1f5f9' }, ticks: { color: '#94a3b8', font: { size: 11 } }, beginAtZero: true }
                }
            }
        });
    }

    const moveCtx = document.getElementById('movementChart');
    if (moveCtx) {
        moveChart = new Chart(moveCtx, {
            type: 'line',
            data: {
                labels: ['January', 'February', 'March', 'April', 'May', 'June'],
                datasets: [
                    {
                        label: 'Enrollments',
                        data: [45, 50, 50, 57, 60, 61],
                        borderColor: '#22c55e',
                        tension: 0.4,
                        fill: false,
                        pointBackgroundColor: '#22c55e',
                        pointRadius: 4,
                    },
                    {
                        label: 'Withdrawals',
                        data: [21, 15, 28, 29, 44, 44],
                        borderColor: '#ef4444',
                        tension: 0.4,
                        fill: false,
                        pointBackgroundColor: '#ef4444',
                        pointRadius: 4,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { grid: { display: false }, ticks: { color: '#94a3b8', font: { size: 11 } } },
                    y: { grid: { color: '#f1f5f9' }, ticks: { color: '#94a3b8', font: { size: 11 } }, beginAtZero: true }
                }
            }
        });
    }

    const statusCtx = document.getElementById('studentStatusChart');
    if (statusCtx) {
        const sd = window.mmscStatusData || {};
        statusChart = new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: ['Active', 'Graduated', 'Completed', 'Inactive', 'Withdrawn'],
                datasets: [{
                    data: [sd.active||0, sd.graduated||0, sd.completed||0, sd.inactive||0, sd.withdrawn||0],
                    backgroundColor: ['#3b82f6', '#facc15', '#22c55e', '#94a3b8', '#ef4444'],
                    borderWidth: 2,
                    borderColor: '#ffffff',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '65%',
                plugins: { legend: { display: false } }
            }
        });
    }
}

function updateCharts() {
    [enrollChart, moveChart, statusChart].forEach(c => {
        if (c) c.update();
    });
}

// run everything after DOM is available
document.addEventListener('DOMContentLoaded', () => {
    sidebar = document.getElementById('sidebar');
    overlay = document.getElementById('sidebar-overlay');

    setupNavLinks();
    setupDarkMode();
    initCharts();
    
    // Also init calendar if present
    if (document.getElementById('calendar-grid')) {
        renderCalendar();
    }
});


// CALENDAR SECTION (simplified and made global)
let currentDate = new Date();
let currentView = 'month';
let calendarEvents = {};

const DAYS = ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'];
const MONTHS = ['January','February','March','April','May','June','July','August','September','October','November','December'];
const DAY_NAMES = ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];

window.calendarNav = function(action) {
    if (action === 'prev-month') currentDate.setMonth(currentDate.getMonth() - 1);
    else if (action === 'next-month') currentDate.setMonth(currentDate.getMonth() + 1);
    else if (action === 'prev-year') currentDate.setFullYear(currentDate.getFullYear() - 1);
    else if (action === 'next-year') currentDate.setFullYear(currentDate.getFullYear() + 1);
    else if (action === 'today') currentDate = new Date();
    renderCalendar();
};

window.setView = function(view) {
    currentView = view;
    ['month','week','day','list'].forEach(v => {
        const btn = document.getElementById('view-' + v);
        if (!btn) return;
        if (v === view) {
            btn.classList.add('bg-violet-600','text-white');
            btn.classList.remove('text-slate-600','hover:bg-slate-50');
        } else {
            btn.classList.remove('bg-violet-600','text-white');
            btn.classList.add('text-slate-600','hover:bg-slate-50');
        }
    });
    renderCalendar();
};

function renderCalendar() {
    const title = document.getElementById('calendar-title');
    const grid = document.getElementById('calendar-grid');
    if (!title || !grid) return;
    
    const today = new Date();
    const todayStr = dateKey(today);

    title.textContent = MONTHS[currentDate.getMonth()] + ' ' + currentDate.getFullYear();
    grid.innerHTML = '';

    if (currentView === 'month') {
        const year = currentDate.getFullYear();
        const month = currentDate.getMonth();
        const firstDay = new Date(year, month, 1).getDay();
        const daysInMonth = new Date(year, month + 1, 0).getDate();
        const daysInPrevMonth = new Date(year, month, 0).getDate();
        const totalCells = Math.ceil((firstDay + daysInMonth) / 7) * 7;

        for (let i = 0; i < totalCells; i++) {
            const cell = document.createElement('div');
            let dayNum, cellDateStr, isCurrentMonth = true;

            if (i < firstDay) {
                dayNum = daysInPrevMonth - firstDay + i + 1;
                isCurrentMonth = false;
                const d = new Date(year, month - 1, dayNum);
                cellDateStr = dateKey(d);
            } else if (i >= firstDay + daysInMonth) {
                dayNum = i - firstDay - daysInMonth + 1;
                isCurrentMonth = false;
                const d = new Date(year, month + 1, dayNum);
                cellDateStr = dateKey(d);
            } else {
                dayNum = i - firstDay + 1;
                cellDateStr = dateKey(new Date(year, month, dayNum));
            }

            const isToday = cellDateStr === todayStr;
            const events = calendarEvents[cellDateStr] || [];

            cell.className = 'border-r border-b border-slate-200 dark:border-dark-border min-h-[100px] p-1 cursor-pointer transition-colors hover:bg-violet-50 dark:hover:bg-violet-900/10 relative';

            if (isToday && isCurrentMonth) {
                cell.classList.add('bg-violet-600');
            }

            const numEl = document.createElement('span');
            numEl.className = 'text-sm font-medium block text-right pr-1 pt-1 ' +
                (isToday && isCurrentMonth ? 'text-white' : isCurrentMonth ? 'text-slate-700 dark:text-slate-300' : 'text-slate-300 dark:text-slate-600');
            numEl.textContent = dayNum;
            cell.appendChild(numEl);

            // Render events
            events.forEach(ev => {
                const evEl = document.createElement('div');
                evEl.className = 'mt-0.5 truncate rounded px-1.5 py-0.5 text-xs font-medium ' +
                    (isToday && isCurrentMonth ? 'bg-white/20 text-white' : 'bg-violet-100 text-violet-700 dark:bg-violet-900/30 dark:text-violet-300');
                evEl.textContent = ev.title;
                cell.appendChild(evEl);
            });

            if (isCurrentMonth) {
                const clickDate = new Date(year, month, dayNum);
                cell.addEventListener('click', () => openModal(clickDate));
            }

            grid.appendChild(cell);
        }

    } else if (currentView === 'week') {
        const startOfWeek = new Date(currentDate);
        startOfWeek.setDate(currentDate.getDate() - currentDate.getDay());
        title.textContent = MONTHS[currentDate.getMonth()] + ' ' + currentDate.getFullYear();

        for (let i = 0; i < 7; i++) {
            const d = new Date(startOfWeek);
            d.setDate(startOfWeek.getDate() + i);
            const dStr = dateKey(d);
            const isToday = dStr === todayStr;
            const events = calendarEvents[dStr] || [];

            const cell = document.createElement('div');
            cell.className = 'border-r border-b border-slate-200 dark:border-dark-border min-h-[160px] p-2 cursor-pointer hover:bg-violet-50 dark:hover:bg-violet-900/10 transition-colors ' +
                (isToday ? 'bg-violet-600' : '');

            const label = document.createElement('div');
            label.className = 'text-xs font-semibold mb-1 ' + (isToday ? 'text-white' : 'text-slate-500 dark:text-slate-400');
            label.textContent = DAYS[i] + ' ' + d.getDate();
            cell.appendChild(label);

            events.forEach(ev => {
                const evEl = document.createElement('div');
                evEl.className = 'truncate rounded px-1.5 py-0.5 text-xs font-medium mt-0.5 ' +
                    (isToday ? 'bg-white/20 text-white' : 'bg-violet-100 text-violet-700 dark:bg-violet-900/30 dark:text-violet-300');
                evEl.textContent = ev.title;
                cell.appendChild(evEl);
            });

            cell.addEventListener('click', () => openModal(d));
            grid.appendChild(cell);
        }

    } else if (currentView === 'day') {
        const dStr = dateKey(currentDate);
        const isToday = dStr === todayStr;
        const events = calendarEvents[dStr] || [];

        title.textContent = DAY_NAMES[currentDate.getDay()] + ', ' + MONTHS[currentDate.getMonth()] + ' ' + currentDate.getDate() + ', ' + currentDate.getFullYear();

        const cell = document.createElement('div');
        cell.className = 'col-span-7 border-r border-b border-slate-200 dark:border-dark-border min-h-[200px] p-4 cursor-pointer hover:bg-violet-50 dark:hover:bg-violet-900/10 transition-colors ' +
            (isToday ? 'bg-violet-600' : '');

        if (events.length === 0) {
            const empty = document.createElement('p');
            empty.className = 'text-sm ' + (isToday ? 'text-white/70' : 'text-slate-400');
            empty.textContent = 'No events. Click to add one.';
            cell.appendChild(empty);
        }

        events.forEach(ev => {
            const evEl = document.createElement('div');
            evEl.className = 'rounded-lg px-3 py-2 text-sm font-medium mb-2 ' +
                (isToday ? 'bg-white/20 text-white' : 'bg-violet-100 text-violet-700 dark:bg-violet-900/30 dark:text-violet-300');
            evEl.innerHTML = '<span class="font-bold">' + ev.title + '</span>' +
                (ev.location ? ' — ' + ev.location : '') +
                (ev.description ? '<br><span class="text-xs opacity-75">' + ev.description + '</span>' : '');
            cell.appendChild(evEl);
        });

        cell.addEventListener('click', () => openModal(currentDate));
        grid.appendChild(cell);

    } else if (currentView === 'list') {
        title.textContent = MONTHS[currentDate.getMonth()] + ' ' + currentDate.getFullYear();
        const year = currentDate.getFullYear();
        const month = currentDate.getMonth();
        const daysInMonth = new Date(year, month + 1, 0).getDate();
        let hasEvents = false;

        const listWrapper = document.createElement('div');
        listWrapper.className = 'col-span-7 divide-y divide-slate-100 dark:divide-dark-border';

        for (let d = 1; d <= daysInMonth; d++) {
            const date = new Date(year, month, d);
            const dStr = dateKey(date);
            const events = calendarEvents[dStr] || [];
            if (events.length === 0) continue;
            hasEvents = true;

            events.forEach(ev => {
                const row = document.createElement('div');
                row.className = 'flex items-start gap-4 px-4 py-3 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors';
                row.innerHTML = `
                    <div class="w-16 shrink-0 text-sm font-semibold text-violet-600 dark:text-violet-400">${DAYS[date.getDay()]} ${d}</div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-slate-800 dark:text-white">${ev.title}</p>
                        ${ev.location ? `<p class="text-xs text-slate-500 dark:text-slate-400">${ev.location}</p>` : ''}
                        ${ev.description ? `<p class="text-xs text-slate-400 mt-0.5">${ev.description}</p>` : ''}
                    </div>
                    <span class="text-xs rounded-full bg-violet-100 text-violet-700 dark:bg-violet-900/30 dark:text-violet-300 px-2 py-0.5 font-medium">${ev.role || ''}</span>
                `;
                listWrapper.appendChild(row);
            });
        }

        if (!hasEvents) {
            const empty = document.createElement('p');
            empty.className = 'col-span-7 py-12 text-center text-sm text-slate-400';
            empty.textContent = 'No events this month.';
            grid.appendChild(empty);
            return;
        }

        grid.appendChild(listWrapper);
    }
}

function dateKey(date) {
    return date.getFullYear() + '-' +
        String(date.getMonth() + 1).padStart(2, '0') + '-' +
        String(date.getDate()).padStart(2, '0');
}

window.openModal = function(date) {
    const dayName = DAY_NAMES[date.getDay()];
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    const year = date.getFullYear();

    const modalTitle = document.getElementById('modal-title');
    const eventDateInput = document.getElementById('event-date');
    const eventForm = document.getElementById('event-form');
    const eventModal = document.getElementById('event-modal');
    
    if (!modalTitle || !eventDateInput || !eventForm || !eventModal) return;

    modalTitle.textContent = `Add Event (${dayName}- ${month}- ${day}- ${year})`;
    eventDateInput.value = dateKey(date);
    eventForm.reset();
    eventDateInput.value = dateKey(date);
    eventModal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
};

window.closeModal = function() {
    const eventModal = document.getElementById('event-modal');
    if (eventModal) eventModal.classList.add('hidden');
    document.body.style.overflow = '';
};

window.saveEvent = function(e) {
    e.preventDefault();
    const dateInput = document.getElementById('event-date');
    const titleInput = document.getElementById('event-title');
    const roleInput = document.getElementById('event-role');
    const locationInput = document.getElementById('event-location');
    const descriptionInput = document.getElementById('event-description');
    const urlInput = document.getElementById('event-url');

    if (!dateInput || !titleInput) return;

    const date = dateInput.value;
    const title = titleInput.value.trim();
    const role = roleInput ? roleInput.value : '';
    const location = locationInput ? locationInput.value.trim() : '';
    const description = descriptionInput ? descriptionInput.value.trim() : '';
    const url = urlInput ? urlInput.value.trim() : '';

    if (!date || !title) return;

    if (!calendarEvents[date]) calendarEvents[date] = [];
    calendarEvents[date].push({ title, role, location, description, url });

    window.closeModal();
    renderCalendar();

    // CSRF token for Laravel
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (csrfToken) {
        fetch('/events', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken.content,
            },
            body: JSON.stringify({ event_date: date, event_title: title, role, event_location: location, description, url })
        }).then(res => res.json()).then(data => {
            console.log('Saved:', data);
        }).catch(err => console.error(err));
    }
};
