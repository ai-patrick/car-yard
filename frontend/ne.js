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

if (grid) {
    cars.forEach((car, i) => {
        const div = document.createElement('div');
        div.className = 'car-card';
        div.innerHTML = `
                <img src="${car.img}" class="car-card-img" alt="${car.name}" loading="lazy">
                <div class="car-card-overlay"></div>
                <div class="car-card-num">${(i + 1).toString().padStart(2, '0')}</div>
                <div class="car-card-content">
                    <div class="car-card-type">${car.type}</div>
                    <h3 class="car-card-name">${car.name}</h3>
                    <div class="car-card-price">$ ${car.price}</div>
                </div>
                <div class="car-card-cta">
                    <div class="cta-circle">View<br>Details</div>
                </div>
            `;
        div.addEventListener('click', () => openDetail(car));
        grid.appendChild(div);
    });
}

// ─── DETAIL PANEL ────────────────────────────────────────────────────────
const detailPanel = document.getElementById('carDetail');
const detailClose = document.getElementById('detailClose');

let currentCar = null;

function openDetail(car) {
    currentCar = car;
    document.getElementById('detailHeroImg').src = car.img;
    document.getElementById('detailHeroImg').alt = car.name;
    document.getElementById('detailType').textContent = car.type;
    document.getElementById('detailName').textContent = car.name;
    document.getElementById('detailPrice').textContent = `$ ${car.price}`;
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
let garage = JSON.parse(localStorage.getItem('prestige_garage')) || [];

function updateCartBadge() {
    const badge = document.getElementById('cartCount');
    if (badge) {
        badge.textContent = garage.length;
    }
}
updateCartBadge();

function addToCart() {
    if (!currentCar) return;

    // Avoid duplicates
    if (!garage.find(c => c.id === currentCar.id)) {
        garage.push(currentCar);
        localStorage.setItem('prestige_garage', JSON.stringify(garage));
    }

    const badge = document.getElementById('cartCount');
    badge.textContent = garage.length;
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

// ─── SCROLL REVEAL ───────────────────────────────────────────────────────
const reveals = document.querySelectorAll('.reveal');
const revealObserver = new IntersectionObserver((entries) => {
    entries.forEach(e => {
        if (e.isIntersecting) { e.target.classList.add('visible'); }
    });
}, { threshold: 0.15 });
reveals.forEach(el => revealObserver.observe(el));