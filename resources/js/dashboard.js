import Chart from 'chart.js/auto';
window.Chart = Chart;

// Sidebar Toggle Logic
const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');
        let isSidebarOpen = false;
        let isIconOnly = false;

        function toggleSidebar() {
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
            isIconOnly = !isIconOnly;
            sidebar.classList.toggle('sidebar-icon-only');
            const btnIcon = document.querySelector('#collapse-icon');
            if (btnIcon) {
                btnIcon.setAttribute('icon', isIconOnly ? 'solar:expand-line' : 'solar:compress-line');
            }
        }

        // Profile Dropdown Toggle
        let isProfileMenuOpen = false;
        function toggleProfileMenu(event) {
            event.preventDefault();
            event.stopPropagation();
            const profileMenu = document.getElementById('profile-menu');
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
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('nav a').forEach(link => {
                link.addEventListener('click', () => {
                    if (isIconOnly) {
                        toggleSidebarCollapse();
                    }
                });
            });
        });

        // Dark Mode Logic
        const themeToggle = document.getElementById('theme-toggle');
        const themeIcon = document.getElementById('theme-icon');
        const html = document.documentElement;
        
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

        // Charts Configuration
        Chart.defaults.font.family = "'Inter', sans-serif";
        Chart.defaults.color = '#94a3b8';
        
        let revenueChartInstance;
        let trafficChartInstance;

        function initCharts() {
            const isDark = html.classList.contains('dark');
            const gridColor = isDark ? '#1E2536' : '#f1f5f9';
            const textColor = isDark ? '#94a3b8' : '#64748b';

            // Revenue Chart (Line)
            const ctxRev = document.getElementById('revenueChart').getContext('2d');
            
            // Gradient
            let gradient = ctxRev.createLinearGradient(0, 0, 0, 400);
            gradient.addColorStop(0, 'rgba(1, 6, 148, 0.2)');
            gradient.addColorStop(1, 'rgba(1, 6, 148, 0)');

            revenueChartInstance = new Chart(ctxRev, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug'],
                    datasets: [{
                        label: 'Revenue',
                        data: [12, 19, 15, 25, 22, 30, 28, 35],
                        borderColor: '#010694',
                        backgroundColor: gradient,
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: '#ffffff',
                        pointBorderColor: '#010694',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: gridColor, borderDash: [5, 5] },
                            ticks: { color: textColor, font: { size: 11 } },
                            border: { display: false }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { color: textColor, font: { size: 11 } },
                            border: { display: false }
                        }
                    }
                }
            });

            // Traffic Chart (Doughnut)
            const ctxTraffic = document.getElementById('trafficChart').getContext('2d');
            trafficChartInstance = new Chart(ctxTraffic, {
                type: 'doughnut',
                data: {
                    labels: ['Direct', 'Social', 'Referral'],
                    datasets: [{
                        data: [45, 32, 23],
                        backgroundColor: ['#010694', '#6d70fc', isDark ? '#334155' : '#e2e8f0'],
                        borderWidth: 0,
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '75%',
                    plugins: {
                        legend: { display: false }
                    }
                }
            });
        }

        function updateCharts(isDark) {
            const gridColor = isDark ? '#1E2536' : '#f1f5f9';
            const textColor = isDark ? '#94a3b8' : '#64748b';
            
            // Update Line Chart
            revenueChartInstance.options.scales.y.grid.color = gridColor;
            revenueChartInstance.options.scales.y.ticks.color = textColor;
            revenueChartInstance.options.scales.x.ticks.color = textColor;
            revenueChartInstance.update();

            // Update Doughnut Chart (Colors)
            trafficChartInstance.data.datasets[0].backgroundColor = ['#010694', '#6d70fc', isDark ? '#334155' : '#e2e8f0'];
            trafficChartInstance.update();
        }

// Initialize
document.addEventListener('DOMContentLoaded', initCharts);

// Make functions globally available for onclick handlers
window.toggleSidebar = toggleSidebar;
window.toggleSidebarCollapse = toggleSidebarCollapse;
window.toggleProfileMenu = toggleProfileMenu;