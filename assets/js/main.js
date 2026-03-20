// ===== BRAINHUB MAIN JS =====

// Background Particles
(function createParticles() {
    const container = document.getElementById('bgParticles');
    if (!container) return;
    const colors = ['#6c63ff', '#00b4d8', '#f7931e', '#38b000', '#ff006e'];
    for (let i = 0; i < 25; i++) {
        const p = document.createElement('div');
        p.className = 'particle';
        const size = Math.random() * 6 + 2;
        p.style.cssText = `
            width: ${size}px;
            height: ${size}px;
            left: ${Math.random() * 100}%;
            background: ${colors[Math.floor(Math.random() * colors.length)]};
            animation-duration: ${Math.random() * 20 + 15}s;
            animation-delay: ${Math.random() * 15}s;
        `;
        container.appendChild(p);
    }
})();

// Navbar active link highlight
(function highlightNav() {
    const links = document.querySelectorAll('.nav-link');
    links.forEach(link => {
        if (link.href && window.location.pathname.startsWith(new URL(link.href).pathname) && link.href !== window.location.origin + '/') {
            link.style.color = 'var(--primary)';
        }
    });
})();

// Animate elements on scroll
(function animateOnScroll() {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.animationPlayState = 'running';
            }
        });
    }, { threshold: 0.1 });

    document.querySelectorAll('.fade-in-delay-1, .fade-in-delay-2, .fade-in-delay-3').forEach(el => {
        el.style.animationPlayState = 'paused';
        observer.observe(el);
    });
})();
