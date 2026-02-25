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
const cars = [
    {
        id: 1, name: "Rolls-Royce Phantom Drophead", type: "Luxury", price: "45,000,000",
        ...getImages("Rolls-Royce Phantom Drophead black"),
        img: "images/phantom.webp",
        desc: "The Phantom Drophead is the pinnacle of open-air luxury motoring. Handcrafted by artisans, every inch exudes opulence — from hand-stitched leather to polished stainless-steel brightwork.",
        specs: [
            { label: "Engine", value: "6.75L", unit: "V12 Twin-Turbo" },
            { label: "Power", value: "563", unit: "bhp" },
            { label: "0–100 km/h", value: "5.1", unit: "seconds" },
            { label: "Drive", value: "RWD", unit: "" }
        ]
    },
    {
        id: 2, name: "Porsche 911 Turbo S", type: "Sports", price: "22,000,000",
        ...getImages("Porsche 911 Turbo S silver"),
        img: "images/911 turbo.jpg",
        desc: "An icon reimagined. The 911 Turbo S distills over five decades of evolution into a machine that is simultaneously daily-driveable and track-ready.",
        specs: [
            { label: "Engine", value: "3.8L", unit: "Flat-6 Twin-Turbo" },
            { label: "Power", value: "650", unit: "bhp" },
            { label: "0–100 km/h", value: "2.7", unit: "seconds" },
            { label: "Drive", value: "AWD", unit: "" }
        ]
    },
    {
        id: 3, name: "Ferrari F8 Tributo", type: "Exotic", price: "35,000,000",
        ...getImages("Ferrari F8 Tributo red"),
        desc: "Few sounds in the automotive world match the howl of a Maranello V8 at full throttle. The F8 is pure, undiluted Italian driving passion.",
        specs: [
            { label: "Engine", value: "3.9L", unit: "V8 Twin-Turbo" },
            { label: "Power", value: "710", unit: "cv" },
            { label: "0–100 km/h", value: "2.9", unit: "seconds" },
            { label: "Drive", value: "RWD", unit: "" }
        ]
    },
    {
        id: 4, name: "Bentley Continental GT W12", type: "Grand Tourer", price: "32,000,000",
        ...getImages("Bentley Continental GT W12 green"),
        desc: "With its handcrafted W12 engine, it effortlessly devours continents while cocooning its occupants in diamond-quilted leather.",
        specs: [
            { label: "Engine", value: "6.0L", unit: "W12 Twin-Turbo" },
            { label: "Power", value: "635", unit: "bhp" },
            { label: "0–100 km/h", value: "3.7", unit: "seconds" },
            { label: "Drive", value: "AWD", unit: "" }
        ]
    },
    {
        id: 5, name: "Lamborghini Aventador SVJ", type: "Exotic", price: "40,000,000",
        ...getImages("Lamborghini Aventador SVJ yellow"),
        desc: "Engineered for maximum performance. The carbon fibre monocoque chassis pairs with a naturally aspirated V12 that screams to 8,500rpm.",
        specs: [
            { label: "Engine", value: "6.5L", unit: "V12 NA" },
            { label: "Power", value: "770", unit: "cv" },
            { label: "0–100 km/h", value: "2.8", unit: "seconds" },
            { label: "Drive", value: "AWD", unit: "" }
        ]
    },
    {
        id: 6, name: "Tesla Model S Plaid", type: "EV", price: "15,000,000",
        ...getImages("Tesla Model S Plaid black"),
        desc: "Plaid rewrites electric performance. Three independent motors produce a combined 1,020 horsepower, enabling a sub-two-second sprint.",
        specs: [
            { label: "Motors", value: "3", unit: "Permanent Magnet" },
            { label: "Power", value: "1,020", unit: "bhp" },
            { label: "0–100 km/h", value: "2.1", unit: "seconds" },
            { label: "Drive", value: "AWD", unit: "" }
        ]
    },
    {
        id: 7, name: "Nissan GT-R Nismo", type: "Sports", price: "18,000,000",
        ...getImages("Nissan GTR Nismo white"),
        img: "images/nippon.jpg",
        desc: "Godzilla. Built on the streets of Tokyo and perfected on the track. The RB-derived VR38 is hand-assembled by a single master technician (Takumi).",
        specs: [
            { label: "Engine", value: "3.8L", unit: "V6 Twin-Turbo" },
            { label: "Power", value: "600", unit: "bhp" },
            { label: "0–100 km/h", value: "2.5", unit: "seconds" },
            { label: "Drive", value: "ATTESA AWD", unit: "" }
        ]
    },
    {
        id: 8, name: "Mercedes-AMG G 63", type: "SUV", price: "25,000,000",
        ...getImages("Mercedes AMG G63 black"),
        desc: "Born military, evolved luxury. The G-Class commands attention while retaining the ability to lock all three differentials and climb mountains.",
        specs: [
            { label: "Engine", value: "4.0L", unit: "V8 Biturbo" },
            { label: "Power", value: "577", unit: "bhp" },
            { label: "0–100 km/h", value: "4.5", unit: "seconds" },
            { label: "Drive", value: "4MATIC AWD", unit: "" }
        ]
    },
    {
        id: 9, name: "Jaguar F-Type R", type: "Coupe", price: "12,500,000",
        ...getImages("Jaguar F-Type R grey"),
        desc: "Its supercharged V8 produces an operatic exhaust note. The all-aluminium body keeps weight obsessively low for a razor-sharp drive.",
        specs: [
            { label: "Engine", value: "5.0L", unit: "V8 Supercharged" },
            { label: "Power", value: "575", unit: "bhp" },
            { label: "0–100 km/h", value: "3.5", unit: "seconds" },
            { label: "Drive", value: "AWD", unit: "" }
        ]
    },
    {
        id: 10, name: "Porsche Taycan Turbo", type: "EV", price: "16,000,000",
        ...getImages("Porsche Taycan Turbo blue"),
        desc: "An 800-volt architecture enables rapid charging, and the two-speed rear gearbox provides an authentic sports car feel no other EV matches.",
        specs: [
            { label: "Motors", value: "2", unit: "Synchronous" },
            { label: "Power", value: "670", unit: "bhp" },
            { label: "0–100 km/h", value: "3.0", unit: "seconds" },
            { label: "Drive", value: "AWD", unit: "" }
        ]
    },
    {
        id: 11, name: "Audi R8 V10 Performance", type: "Supercar", price: "21,000,000",
        ...getImages("Audi R8 V10 Performance red"),
        desc: "A naturally aspirated V10 marvel sharing its DNA with Lamborghini, offering gated-shifter precision and Quattro grip.",
        specs: [
            { label: "Engine", value: "5.2L", unit: "V10 NA" },
            { label: "Power", value: "602", unit: "bhp" },
            { label: "0–100 km/h", value: "3.2", unit: "seconds" },
            { label: "Drive", value: "Quattro AWD", unit: "" }
        ]
    },
    {
        id: 12, name: "McLaren 720S", type: "Exotic", price: "33,000,000",
        ...getImages("McLaren 720S orange"),
        desc: "A masterclass in aerodynamics and lightweight engineering. The Monocage II carbon structure makes it ferociously fast and agile.",
        specs: [
            { label: "Engine", value: "4.0L", unit: "V8 Twin-Turbo" },
            { label: "Power", value: "710", unit: "bhp" },
            { label: "0–100 km/h", value: "2.8", unit: "seconds" },
            { label: "Drive", value: "RWD", unit: "" }
        ]
    },
    {
        id: 13, name: "Aston Martin DBS", type: "Grand Tourer", price: "36,000,000",
        ...getImages("Aston Martin DBS Superleggera grey"),
        desc: "A brute in a tailored suit. The DBS Superleggera delivers crushing twin-turbo V12 torque wrapped in a stunning carbon fibre body.",
        specs: [
            { label: "Engine", value: "5.2L", unit: "V12 Twin-Turbo" },
            { label: "Power", value: "715", unit: "bhp" },
            { label: "0–100 km/h", value: "3.4", unit: "seconds" },
            { label: "Drive", value: "RWD", unit: "" }
        ]
    },
    {
        id: 14, name: "BMW M5 CS", type: "Sedan", price: "17,500,000",
        ...getImages("BMW M5 CS green"),
        desc: "The quickest, most powerful production BMW ever built. Stripped of excess weight and tuned for devastating lap times while seating four.",
        specs: [
            { label: "Engine", value: "4.4L", unit: "V8 Twin-Turbo" },
            { label: "Power", value: "627", unit: "bhp" },
            { label: "0–100 km/h", value: "2.9", unit: "seconds" },
            { label: "Drive", value: "xDrive AWD", unit: "" }
        ]
    },
    {
        id: 15, name: "Ford Mustang GT500", type: "Muscle", price: "11,000,000",
        ...getImages("Ford Mustang Shelby GT500 blue"),
        desc: "The pinnacle of American muscle. The supercharged Predator V8 is mated to a Tremec dual-clutch transmission for brutal acceleration.",
        specs: [
            { label: "Engine", value: "5.2L", unit: "V8 Supercharged" },
            { label: "Power", value: "760", unit: "bhp" },
            { label: "0–100 km/h", value: "3.3", unit: "seconds" },
            { label: "Drive", value: "RWD", unit: "" }
        ]
    },
    {
        id: 16, name: "Chevrolet Corvette Z06", type: "Sports", price: "13,500,000",
        ...getImages("Chevrolet Corvette Z06 yellow"),
        desc: "America's mid-engine supercar. Featuring a flat-plane crank V8 that revs to 8,600 rpm, delivering a European exotic experience.",
        specs: [
            { label: "Engine", value: "5.5L", unit: "V8 Flat-Plane" },
            { label: "Power", value: "670", unit: "bhp" },
            { label: "0–100 km/h", value: "2.6", unit: "seconds" },
            { label: "Drive", value: "RWD", unit: "" }
        ]
    },
    {
        id: 17, name: "Range Rover SV", type: "SUV", price: "24,000,000",
        ...getImages("Range Rover SV Autobiography black"),
        desc: "The standard bearer for luxury SUVs. The SV division elevates the cabin with ceramic touchpoints and executive class rear seating.",
        specs: [
            { label: "Engine", value: "4.4L", unit: "V8 Twin-Turbo" },
            { label: "Power", value: "606", unit: "bhp" },
            { label: "0–100 km/h", value: "4.3", unit: "seconds" },
            { label: "Drive", value: "AWD", unit: "" }
        ]
    },
    {
        id: 18, name: "Alfa Romeo Quadrifoglio", type: "Sedan", price: "10,500,000",
        ...getImages("Alfa Romeo Giulia Quadrifoglio red"),
        desc: "A Ferrari-derived V6 wrapped in a chassis that sets the benchmark for driving dynamics in a four-door sedan.",
        specs: [
            { label: "Engine", value: "2.9L", unit: "V6 Twin-Turbo" },
            { label: "Power", value: "505", unit: "bhp" },
            { label: "0–100 km/h", value: "3.8", unit: "seconds" },
            { label: "Drive", value: "RWD", unit: "" }
        ]
    },
    {
        id: 19, name: "Lexus LC 500", type: "Coupe", price: "12,000,000",
        ...getImages("Lexus LC 500 structural blue"),
        desc: "A concept car brought to life. The LC 500 pairs stunning design with one of the best sounding naturally aspirated V8s in existence.",
        specs: [
            { label: "Engine", value: "5.0L", unit: "V8 NA" },
            { label: "Power", value: "471", unit: "bhp" },
            { label: "0–100 km/h", value: "4.4", unit: "seconds" },
            { label: "Drive", value: "RWD", unit: "" }
        ]
    },
    {
        id: 20, name: "Bugatti Chiron", type: "Hypercar", price: "350,000,000",
        ...getImages("Bugatti Chiron blue black"),
        desc: "An engineering masterpiece. Sixteen cylinders, four turbochargers, and a top speed that requires a specialized secondary key to unlock.",
        specs: [
            { label: "Engine", value: "8.0L", unit: "W16 Quad-Turbo" },
            { label: "Power", value: "1,479", unit: "bhp" },
            { label: "0–100 km/h", value: "2.4", unit: "seconds" },
            { label: "Drive", value: "AWD", unit: "" }
        ]
    }
];

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