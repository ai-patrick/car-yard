// ─── PROGRAMMATIC IMAGE GENERATOR ───────────────────────────────────────
// This function guarantees every car strictly gets unique, accurate images 
// by calling an AI image generator specifically for that car model and angle.
const getImages = (carName) => {
    const base = "https://image.pollinations.ai/prompt/";
    const params = "?width=800&height=600&nologo=true";
    return {
        img: `${base}${encodeURIComponent(carName + " realistic car photography 8k")} ${params}`,
        gallery: [
            `${base}${encodeURIComponent(carName + " realistic car front view 8k")}${params}`,
            `${base}${encodeURIComponent(carName + " realistic car side profile 8k")}${params}`,
            `${base}${encodeURIComponent(carName + " realistic car rear view 8k")}${params}`,
            `${base}${encodeURIComponent(carName + " realistic luxury car interior dashboard 8k")}${params}`
        ]
    };
};

// ─── DATA (20 DISTINCT CARS) ────────────────────────────────────────────
const cars = typeof dbCars !== "undefined" ? dbCars : [];

// ─── RENDER GRID ────────────────────────────────────────────────────────
const grid = document.getElementById('carGrid');
const INITIAL_COUNT = 6;

cars.forEach((car, i) => {
    const extra = i >= INITIAL_COUNT;
    const div = document.createElement('div');
    div.className = 'car-card' + (extra ? ' extra-card' : '');
    div.innerHTML = `
            <img src="${car.img}" class="car-card-img" alt="${car.name}" loading="lazy">
            <div class="car-card-overlay"></div>
            <div class="car-card-num">${(i + 1).toString().padStart(2, '0')}</div>
            <div class="car-card-content">
                <div class="car-card-type">${car.type}</div>
                <h3 class="car-card-name">${car.name}</h3>
                <div class="car-card-price">KSh ${car.price}</div>
            </div>
            <div class="car-card-cta">
                <div class="cta-circle">View<br>Details</div>
            </div>
        `;
    div.addEventListener('click', () => openDetail(car));
    grid.appendChild(div);
});

// ─── VIEW ALL TOGGLE ─────────────────────────────────────────────────────
let expanded = false;
document.getElementById('viewAllBtn').addEventListener('click', () => {
    expanded = !expanded;
    const extras = document.querySelectorAll('.extra-card');
    extras.forEach((el, i) => {
        if (expanded) {
            el.classList.add('show');
            el.style.animationDelay = `${i * 0.05}s`;
        } else {
            el.classList.remove('show');
        }
    });
    document.getElementById('viewAllText').textContent = expanded ? 'Show Less' : 'View All';
    document.querySelector('.view-all-btn .arrow').textContent = expanded ? '↑' : '→';
});

// ─── DETAIL PANEL ────────────────────────────────────────────────────────
const detailPanel = document.getElementById('carDetail');
const detailClose = document.getElementById('detailClose');

function openDetail(car) {
    document.getElementById('detailHeroImg').src = car.img;
    document.getElementById('detailHeroImg').alt = car.name;
    document.getElementById('detailType').textContent = car.type;
    document.getElementById('detailName').textContent = car.name;
    document.getElementById('detailPrice').textContent = `KSh ${car.price}`;
    document.getElementById('detailDescTitle').textContent = `About the ${car.name}`;
    document.getElementById('detailDesc').textContent = car.desc;

    const gallery = document.getElementById('detailGallery');
    gallery.innerHTML = '';
    car.gallery.forEach((src, i) => {
        const div = document.createElement('div');
        div.className = 'detail-thumb' + (i === 0 ? ' active' : '');
        div.innerHTML = `<img src="${src}" alt="${car.name} view ${i + 1}" loading="lazy">`;
        div.addEventListener('click', () => {
            document.getElementById('detailHeroImg').src = src;
            gallery.querySelectorAll('.detail-thumb').forEach(t => t.classList.remove('active'));
            div.classList.add('active');
        });
        gallery.appendChild(div);
    });

    const specGrid = document.getElementById('specGrid');
    specGrid.innerHTML = '';
    car.specs.forEach(s => {
        const div = document.createElement('div');
        div.className = 'spec-item';
        div.innerHTML = `<div class="spec-label">${s.label}</div><div class="spec-value">${s.value}<span>${s.unit}</span></div>`;
        specGrid.appendChild(div);
    });

    detailPanel.classList.add('open');
    detailPanel.scrollTop = 0;
    document.body.style.overflow = 'hidden';
}

function closeDetail() {
    detailPanel.classList.remove('open');
    document.body.style.overflow = '';
}

detailClose.addEventListener('click', closeDetail);
detailPanel.addEventListener('keydown', e => { if (e.key === 'Escape') closeDetail(); });
document.addEventListener('keydown', e => { if (e.key === 'Escape') closeDetail(); });

// ─── CART ────────────────────────────────────────────────────────────────
let cartItems = 0;
function addToCart() {
    cartItems++;
    const badge = document.getElementById('cartCount');
    badge.textContent = cartItems;
    badge.classList.add('bump');
    setTimeout(() => badge.classList.remove('bump'), 300);
    closeDetail();
}

// ─── NAVBAR SCROLL ───────────────────────────────────────────────────────
window.addEventListener('scroll', () => {
    const nav = document.getElementById('mainNav');
    nav.classList.toggle('scrolled', window.scrollY > 80);

    const heroBg = document.getElementById('heroBg');
    if (heroBg) {
        heroBg.style.transform = `scale(1.05) translateY(${window.scrollY * 0.25}px)`;
    }
});

// ─── CURSOR ──────────────────────────────────────────────────────────────
const cursor = document.getElementById('cursor');
const cursorRing = document.getElementById('cursorRing');
let mouseX = 0, mouseY = 0, ringX = 0, ringY = 0;

document.addEventListener('mousemove', e => {
    mouseX = e.clientX; mouseY = e.clientY;
    cursor.style.left = mouseX - 4 + 'px';
    cursor.style.top = mouseY - 4 + 'px';
});

function animateCursor() {
    ringX += (mouseX - ringX - 18) * 0.12;
    ringY += (mouseY - ringY - 18) * 0.12;
    cursorRing.style.left = ringX + 'px';
    cursorRing.style.top = ringY + 'px';
    requestAnimationFrame(animateCursor);
}
animateCursor();

// ─── SCROLL REVEAL ───────────────────────────────────────────────────────
const reveals = document.querySelectorAll('.reveal');
const revealObserver = new IntersectionObserver((entries) => {
    entries.forEach(e => {
        if (e.isIntersecting) { e.target.classList.add('visible'); }
    });
}, { threshold: 0.15 });
reveals.forEach(el => revealObserver.observe(el));