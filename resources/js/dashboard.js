import Chart from 'chart.js/auto';
window.Chart = Chart;

// keep references in the outer scope so helper functions can access them
let sidebar, overlay;
let isSidebarOpen = false;
let isIconOnly = false;
let isProfileMenuOpen = false;

// helper functions rely on sidebar/overlay variables being set; they will
// be assigned once the DOM is ready (see the DOMContentLoaded listener below).
function toggleSidebar() {
    // guard against missing elements (e.g. if DOM wasn't ready)
    if (!sidebar || !overlay) return;
    isSidebarOpen = !isSidebarOpen;
    if (isSidebarOpen) {
        sidebar.classList.remove('-translate-x-full');
        overlay.classList.remove('hidden', 'opacity-0');
    } else {
        sidebar.classList.add('-translate-x-full');
        overlay.classList.add('opacity-0');
        setTimeout(() => overlay.classList.add('hidden'), 300);
    }
}

// collapse button toggles icon-only mode
function toggleSidebarCollapse() {
    // ensure sidebar reference is available even if function called before DOMContentLoaded
    if (!sidebar) sidebar = document.getElementById('sidebar');
    if (!sidebar) return;
    isIconOnly = !isIconOnly;
    sidebar.classList.toggle('sidebar-icon-only');
    const btnIcon = document.querySelector('#collapse-icon');
    if (btnIcon) {
        btnIcon.setAttribute('icon', isIconOnly ? 'solar:expand-line' : 'solar:compress-line');
    }
    console.debug('toggleSidebarCollapse -> isIconOnly=', isIconOnly);
}

// expose early so inline onclick works before DOMContentLoaded completes
window.toggleSidebarCollapse = toggleSidebarCollapse;

// Profile Dropdown Toggle
function toggleProfileMenu(event) {
    event.preventDefault();
    event.stopPropagation();
    const profileMenu = document.getElementById('profile-menu');
    if (!profileMenu) return;
    isProfileMenuOpen = !isProfileMenuOpen;
    if (isProfileMenuOpen) {
        profileMenu.classList.remove('hidden');
    } else {
        profileMenu.classList.add('hidden');
    }
}

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

// when any navigation item is clicked, return to normal
function setupNavLinks() {
    document.querySelectorAll('nav a').forEach(link => {
        link.addEventListener('click', () => {
            if (isIconOnly) {
                toggleSidebarCollapse();
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
            updateCharts(true);
        } else {
            localStorage.theme = 'light';
            themeIcon.setAttribute('icon', 'solar:sun-2-linear');
            updateCharts(false);
        }
    });
}

// Charts Configuration
// chart instances exposed for later updates
let enrollChart, moveChart, statusChart;

function initCharts() {
    // this function can be safely called after DOM is ready (and again to re-init if needed)
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
        statusChart = new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: ['Active', 'Graduated', 'Completed', 'Inactive', 'Withdrawn'],
                datasets: [{
                    data: [35, 25, 20, 10, 10],
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

// called by dark mode toggle to trigger a refresh; the actual appearance
// adjustments are handled by CSS, so we simply force charts to re-render.
function updateCharts() {
    [enrollChart, moveChart, statusChart].forEach(c => {
        if (c) c.update();
    });
}

(function() {
    const enrollCtx = document.getElementById('enrollmentChart');
    if (enrollCtx) {
        new Chart(enrollCtx, {
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
        new Chart(moveCtx, {
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
        new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: ['Active', 'Graduated', 'Completed', 'Inactive', 'Withdrawn'],
                datasets: [{
                    data: [35, 25, 20, 10, 10],
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
})();

// run everything after DOM is available
document.addEventListener('DOMContentLoaded', () => {
    sidebar = document.getElementById('sidebar');
    overlay = document.getElementById('sidebar-overlay');

    setupNavLinks();
    setupDarkMode();
    initCharts();

    window.toggleSidebar = toggleSidebar;
    window.toggleSidebarCollapse = toggleSidebarCollapse;
    window.toggleProfileMenu = toggleProfileMenu;
});