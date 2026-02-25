// ─── CART BADGE ──────────────────────────────────────────────────────────
let garage = JSON.parse(localStorage.getItem('prestige_garage')) || [];

function updateCartBadge() {
    const badge = document.getElementById('cartCount');
    if (badge) badge.textContent = garage.length;
}
updateCartBadge();

// ─── NAVBAR SCROLL ───────────────────────────────────────────────────────
window.addEventListener('scroll', () => {
    const nav = document.getElementById('mainNav');
    nav.classList.toggle('scrolled', window.scrollY > 80);

    // Hero parallax
    const heroBg = document.getElementById('heroBg');
    if (heroBg) {
        heroBg.style.transform = `scale(1.05) translateY(${window.scrollY * 0.25}px)`;
    }
});

// ─── SCROLL REVEAL ───────────────────────────────────────────────────────
const reveals = document.querySelectorAll('.reveal');
const revealObserver = new IntersectionObserver((entries) => {
    entries.forEach(e => {
        if (e.isIntersecting) { e.target.classList.add('visible'); }
    });
}, { threshold: 0.1 });
reveals.forEach(el => revealObserver.observe(el));

// ─── CATEGORY CARD STAGGERED REVEAL ──────────────────────────────────────
const categoryCards = document.querySelectorAll('.category-card');
categoryCards.forEach((card, i) => {
    card.style.transitionDelay = `${i * 0.08}s`;
});