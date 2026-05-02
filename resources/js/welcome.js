document.addEventListener('DOMContentLoaded', () => {

    /* ── Navbar scroll ── */
    const navbar = document.getElementById('navbar');
    if (navbar) {
        window.addEventListener('scroll', () => {
            navbar.classList.toggle('navbar-scrolled', window.scrollY > 20);
        });
    }

    /* ── Hero Carousel ── */
    const slides = document.querySelectorAll('.hero-slide');
    const dots   = document.querySelectorAll('.dot-btn');
    if (slides.length) {
        let current = 0, timer;

        function goTo(n) {
            slides[current].classList.remove('active');
            dots[current].classList.remove('active');
            current = (n + slides.length) % slides.length;
            slides[current].classList.add('active');
            dots[current].classList.add('active');
        }
        function next() { goTo(current + 1); }
        function prev() { goTo(current - 1); }
        function startTimer() { timer = setInterval(next, 5000); }
        function resetTimer() { clearInterval(timer); startTimer(); }

        dots.forEach(d => d.addEventListener('click', () => { goTo(+d.dataset.index); resetTimer(); }));

        const btnNext = document.getElementById('carouselNext');
        const btnPrev = document.getElementById('carouselPrev');
        if (btnNext) btnNext.addEventListener('click', () => { next(); resetTimer(); });
        if (btnPrev) btnPrev.addEventListener('click', () => { prev(); resetTimer(); });

        startTimer();
    }

    /* ── Scroll Reveal ── */
    const io = new IntersectionObserver(entries => {
        entries.forEach(e => {
            if (e.isIntersecting) { e.target.classList.add('visible'); io.unobserve(e.target); }
        });
    }, { threshold: 0.1 });
    document.querySelectorAll('.reveal').forEach(el => io.observe(el));

    /* ── Gallery Filter ── */
    const filterBtns   = document.querySelectorAll('.filter-btn');
    const galleryItems = document.querySelectorAll('.gallery-item');

    filterBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            filterBtns.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            const filter = btn.dataset.filter;
            galleryItems.forEach(item => {
                item.style.display = (filter === 'all' || item.dataset.category === filter) ? '' : 'none';
            });
        });
    });

    /* ── Lightbox ── */
    const lightbox = document.getElementById('galleryLightbox');
    if (lightbox && galleryItems.length) {
        const lbImage   = document.getElementById('lbImage');
        const lbCaption = document.getElementById('lbCaption');
        const lbClose   = document.getElementById('lbClose');
        const lbPrev    = document.getElementById('lbPrev');
        const lbNext    = document.getElementById('lbNext');
        let visibleItems = [], lbIndex = 0;

        function getVisible() {
            return [...galleryItems].filter(el => el.style.display !== 'none');
        }
        function showImage() {
            const item = visibleItems[lbIndex];
            lbImage.src = item.dataset.src;
            lbCaption.textContent = item.dataset.caption;
        }
        function open(item) {
            visibleItems = getVisible();
            lbIndex = visibleItems.indexOf(item);
            if (lbIndex === -1) lbIndex = 0;
            showImage();
            lightbox.classList.add('open');
            document.body.style.overflow = 'hidden';
        }
        function close() {
            lightbox.classList.remove('open');
            document.body.style.overflow = '';
        }

        galleryItems.forEach(item => item.addEventListener('click', () => open(item)));
        lbClose.addEventListener('click', close);
        lightbox.addEventListener('click', e => { if (e.target === lightbox) close(); });
        lbPrev.addEventListener('click', e => {
            e.stopPropagation();
            lbIndex = (lbIndex - 1 + visibleItems.length) % visibleItems.length;
            showImage();
        });
        lbNext.addEventListener('click', e => {
            e.stopPropagation();
            lbIndex = (lbIndex + 1) % visibleItems.length;
            showImage();
        });
        document.addEventListener('keydown', e => {
            if (!lightbox.classList.contains('open')) return;
            if (e.key === 'Escape') close();
            if (e.key === 'ArrowLeft') { lbIndex = (lbIndex - 1 + visibleItems.length) % visibleItems.length; showImage(); }
            if (e.key === 'ArrowRight') { lbIndex = (lbIndex + 1) % visibleItems.length; showImage(); }
        });
    }
    
});
