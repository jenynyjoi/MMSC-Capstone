
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

        // ---- Navbar scroll effect ----
        const navbar = document.getElementById('navbar');
        window.addEventListener('scroll', () => {
            navbar.classList.toggle('navbar-scrolled', window.scrollY > 20);
        });

        // ---- Background slideshow ----
        const images = [
            "{{ asset('images/landing bg.png') }}",
            "{{ asset('images/schoolBG.png') }}",
            "{{ asset('images/bg messiah.jpg') }}"
        ];

        const gradient = `linear-gradient(
            to bottom,
            rgba(13, 96, 184, 0.9) 0%,
            rgba(26, 94, 158, 0.75) 25%,
            rgba(48, 125, 183, 0.6) 50%,
            rgba(141, 204, 238, 0.75) 75%,
            rgba(233, 244, 250, 0.95) 100%
        )`;

        let currentIndex = 0;
        const landing = document.getElementById('landingPage');
        const dots = document.querySelectorAll('.dot');

        function setBackground(i) {
            currentIndex = i;
            landing.style.backgroundImage = `${gradient}, url("${images[i]}")`;
            dots.forEach((d, idx) => {
                d.classList.toggle('active', idx === i);
                d.classList.toggle('!w-6', idx === i);
                d.classList.toggle('!bg-white', idx === i);
                d.classList.toggle('bg-white/50', idx !== i);
            });
        }

        dots.forEach(dot => {
            dot.addEventListener('click', () => setBackground(parseInt(dot.dataset.index)));
        });

        setInterval(() => setBackground((currentIndex + 1) % images.length), 5000);
