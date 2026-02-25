// ─── DATA ───────────────────────────────────────────────────────────────
    const cars = [
        {
            id: 1, name: "Phantom Drophead", type: "Luxury", price: "45,000,000",
            img: "images/phantom.webp",
            gallery: [
                "https://images.unsplash.com/photo-1631269666014-11826f4f469c?auto=format&fit=crop&w=600&q=80",
                "https://images.unsplash.com/photo-1555215695-3004980ad54e?auto=format&fit=crop&w=600&q=80",
                "https://images.unsplash.com/photo-1603584173870-7f23fdae1b7a?auto=format&fit=crop&w=600&q=80",
                "https://images.unsplash.com/photo-1544829099-b9a0c07fad1a?auto=format&fit=crop&w=600&q=80"
            ],
            desc: "The Phantom Drophead is the pinnacle of open-air luxury motoring. Handcrafted by Rolls-Royce artisans in Goodwood, every inch of this convertible exudes opulence — from its hand-stitched leather to the polished stainless-steel brightwork that frames the interior.",
            specs: [
                { label: "Engine", value: "6.75L", unit: "V12 Twin-Turbo" },
                { label: "Power", value: "563", unit: "bhp" },
                { label: "0–100 km/h", value: "5.1", unit: "seconds" },
                { label: "Top Speed", value: "250", unit: "km/h" },
                { label: "Transmission", value: "8-Spd", unit: "Automatic" },
                { label: "Drive", value: "RWD", unit: "" }
            ]
        },
        {
            id: 2, name: "Stuttgart 911 Turbo S", type: "Sports", price: "22,000,000",
            img: "images/911 turbo.jpg",
            gallery: [
                "https://images.unsplash.com/photo-1503376780353-7e6692767b70?auto=format&fit=crop&w=600&q=80",
                "https://images.unsplash.com/photo-1503376712353-c8d1542fce2b?auto=format&fit=crop&w=600&q=80",
                "https://images.unsplash.com/photo-1614200187524-dc4b892acf16?auto=format&fit=crop&w=600&q=80",
                "https://images.unsplash.com/photo-1556189250-72ba954cfc2b?auto=format&fit=crop&w=600&q=80"
            ],
            desc: "An icon reimagined. The 911 Turbo S represents over five decades of evolution distilled into a machine that is simultaneously daily-driveable and track-ready. The rear-engine layout, now harnessing 650hp of flat-six fury, delivers a visceral driving experience unlike anything else.",
            specs: [
                { label: "Engine", value: "3.8L", unit: "Flat-6 Twin-Turbo" },
                { label: "Power", value: "650", unit: "bhp" },
                { label: "0–100 km/h", value: "2.7", unit: "seconds" },
                { label: "Top Speed", value: "330", unit: "km/h" },
                { label: "Transmission", value: "8-Spd", unit: "PDK" },
                { label: "Drive", value: "AWD", unit: "" }
            ]
        },
        {
            id: 3, name: "Maranello V8 Spyder", type: "Exotic", price: "35,000,000",
            img: "https://images.unsplash.com/photo-1583121274602-3e2820c69888?auto=format&fit=crop&w=1200&q=80",
            gallery: [
                "https://images.unsplash.com/photo-1583121274602-3e2820c69888?auto=format&fit=crop&w=600&q=80",
                "https://images.unsplash.com/photo-1614026480209-cd9934144671?auto=format&fit=crop&w=600&q=80",
                "https://images.unsplash.com/photo-1560958089-b8a1929cea89?auto=format&fit=crop&w=600&q=80",
                "https://images.unsplash.com/photo-1584345611127-8fb37cb5b520?auto=format&fit=crop&w=600&q=80"
            ],
            desc: "Few sounds in the automotive world match the howl of a Ferrari V8 at full throttle. The Spyder pairs that sensory theatre with a retractable hardtop, allowing you to experience Maranello's finest with wind in your hair. This is pure, undiluted driving passion.",
            specs: [
                { label: "Engine", value: "3.9L", unit: "V8 Twin-Turbo" },
                { label: "Power", value: "710", unit: "cv" },
                { label: "0–100 km/h", value: "3.0", unit: "seconds" },
                { label: "Top Speed", value: "320", unit: "km/h" },
                { label: "Transmission", value: "8-Spd", unit: "F1 DCT" },
                { label: "Drive", value: "RWD", unit: "" }
            ]
        },
        {
            id: 4, name: "Continental GT W12", type: "Grand Tourer", price: "32,000,000",
            img: "https://images.unsplash.com/photo-1605559424843-9e4c228bf1c2?auto=format&fit=crop&w=1200&q=80",
            gallery: [
                "https://images.unsplash.com/photo-1605559424843-9e4c228bf1c2?auto=format&fit=crop&w=600&q=80",
                "https://images.unsplash.com/photo-1603584173870-7f23fdae1b7a?auto=format&fit=crop&w=600&q=80",
                "https://images.unsplash.com/photo-1544829099-b9a0c07fad1a?auto=format&fit=crop&w=600&q=80",
                "https://images.unsplash.com/photo-1553440569-bcc63803a83d?auto=format&fit=crop&w=600&q=80"
            ],
            desc: "The Continental GT redefines what a Grand Tourer can be. With its handcrafted 6.0-litre W12 engine and 4Motion all-wheel drive, it effortlessly devours continents while cocooning its occupants in hand-stitched leather, diamond-quilted headlining and polished metal.",
            specs: [
                { label: "Engine", value: "6.0L", unit: "W12 Twin-Turbo" },
                { label: "Power", value: "635", unit: "bhp" },
                { label: "0–100 km/h", value: "3.7", unit: "seconds" },
                { label: "Top Speed", value: "333", unit: "km/h" },
                { label: "Transmission", value: "8-Spd", unit: "Dual-Clutch" },
                { label: "Drive", value: "AWD", unit: "" }
            ]
        },
        {
            id: 5, name: "Milanese Superleggera", type: "Exotic", price: "40,000,000",
            img: "https://images.unsplash.com/photo-1544829099-b9a0c07fad1a?auto=format&fit=crop&w=1200&q=80",
            gallery: [
                "https://images.unsplash.com/photo-1544829099-b9a0c07fad1a?auto=format&fit=crop&w=600&q=80",
                "https://images.unsplash.com/photo-1583121274602-3e2820c69888?auto=format&fit=crop&w=600&q=80",
                "https://images.unsplash.com/photo-1614026480209-cd9934144671?auto=format&fit=crop&w=600&q=80",
                "https://images.unsplash.com/photo-1531927557220-a9e23c1e4794?auto=format&fit=crop&w=600&q=80"
            ],
            desc: "Superleggera — Italian for 'superlight'. Every element of this Lamborghini-rivalling supercar has been engineered for maximum performance with minimum mass. The carbon fibre monocoque chassis weighs less than a suitcase, and the naturally aspirated V12 screams to 9,000rpm.",
            specs: [
                { label: "Engine", value: "6.5L", unit: "V12 NA" },
                { label: "Power", value: "770", unit: "cv" },
                { label: "0–100 km/h", value: "2.85", unit: "seconds" },
                { label: "Top Speed", value: "340", unit: "km/h" },
                { label: "Transmission", value: "7-Spd", unit: "Robotised" },
                { label: "Drive", value: "RWD", unit: "" }
            ]
        },
        {
            id: 6, name: "Electric Zenith Plaid", type: "EV", price: "15,000,000",
            img: "https://images.unsplash.com/photo-1560958089-b8a1929cea89?auto=format&fit=crop&w=1200&q=80",
            gallery: [
                "https://images.unsplash.com/photo-1560958089-b8a1929cea89?auto=format&fit=crop&w=600&q=80",
                "https://images.unsplash.com/photo-1593460354583-4224ab273ef0?auto=format&fit=crop&w=600&q=80",
                "https://images.unsplash.com/photo-1614162692292-7ac56d7f7f1e?auto=format&fit=crop&w=600&q=80",
                "https://images.unsplash.com/photo-1620015573489-35c82cc708b7?auto=format&fit=crop&w=600&q=80"
            ],
            desc: "Plaid rewrites the rulebook on what electric performance means. Three independent electric motors produce a combined 1,020 horsepower, enabling a sub-two-second 0–60mph sprint. The world has never seen anything like it from the factory floor.",
            specs: [
                { label: "Motors", value: "3", unit: "Permanent Magnet" },
                { label: "Power", value: "1,020", unit: "bhp" },
                { label: "0–100 km/h", value: "2.1", unit: "seconds" },
                { label: "Range", value: "628", unit: "km (WLTP)" },
                { label: "Transmission", value: "Single", unit: "Speed" },
                { label: "Drive", value: "AWD", unit: "" }
            ]
        },
        {
            id: 7, name: "Nippon GTR V-Spec", type: "Sports", price: "18,000,000",
            img: "images/nippon.jpg",
            gallery: [
                "https://images.unsplash.com/photo-1620015573489-35c82cc708b7?auto=format&fit=crop&w=600&q=80",
                "https://images.unsplash.com/photo-1503376712353-c8d1542fce2b?auto=format&fit=crop&w=600&q=80",
                "https://images.unsplash.com/photo-1556189250-72ba954cfc2b?auto=format&fit=crop&w=600&q=80",
                "https://images.unsplash.com/photo-1584345611127-8fb37cb5b520?auto=format&fit=crop&w=600&q=80"
            ],
            desc: "Godzilla. The GTR V-Spec is a legend built on the streets of Tokyo and perfected on the Nürburgring. Its RB26 engine is hand-assembled by a single technician, who signs their name on the engine block. This is not a car — it's a philosophy.",
            specs: [
                { label: "Engine", value: "2.6L", unit: "RB26 Twin-Turbo" },
                { label: "Power", value: "565", unit: "bhp" },
                { label: "0–100 km/h", value: "3.5", unit: "seconds" },
                { label: "Top Speed", value: "315", unit: "km/h" },
                { label: "Transmission", value: "6-Spd", unit: "Sequential" },
                { label: "Drive", value: "ATTESA AWD", unit: "" }
            ]
        },
        {
            id: 8, name: "Swabian G-Class AMG", type: "SUV", price: "25,000,000",
            img: "https://images.unsplash.com/photo-1520031441872-265e4ff70366?auto=format&fit=crop&w=1200&q=80",
            gallery: [
                "https://images.unsplash.com/photo-1520031441872-265e4ff70366?auto=format&fit=crop&w=600&q=80",
                "https://images.unsplash.com/photo-1533473359331-0135ef1b58bf?auto=format&fit=crop&w=600&q=80",
                "https://images.unsplash.com/photo-1559416523-140ddc3d238c?auto=format&fit=crop&w=600&q=80",
                "https://images.unsplash.com/photo-1506015391300-4152709024f7?auto=format&fit=crop&w=600&q=80"
            ],
            desc: "Few vehicles command attention like the G-Class. Born as a military vehicle in 1979, it has evolved into the definitive luxury SUV — yet still climbs mountains. The AMG version adds 585 horsepower and a soundtrack borrowed from a thoroughbred race car.",
            specs: [
                { label: "Engine", value: "4.0L", unit: "V8 Biturbo" },
                { label: "Power", value: "585", unit: "bhp" },
                { label: "0–100 km/h", value: "4.5", unit: "seconds" },
                { label: "Top Speed", value: "240", unit: "km/h" },
                { label: "Transmission", value: "9-Spd", unit: "AMG Speedshift" },
                { label: "Drive", value: "4MATIC AWD", unit: "" }
            ]
        },
        {
            id: 9, name: "Coventry F-Type R", type: "Coupe", price: "12,500,000",
            img: "https://images.unsplash.com/photo-1553440569-bcc63803a83d?auto=format&fit=crop&w=1200&q=80",
            gallery: [
                "https://images.unsplash.com/photo-1553440569-bcc63803a83d?auto=format&fit=crop&w=600&q=80",
                "https://images.unsplash.com/photo-1555215695-3004980ad54e?auto=format&fit=crop&w=600&q=80",
                "https://images.unsplash.com/photo-1614200187524-dc4b892acf16?auto=format&fit=crop&w=600&q=80",
                "https://images.unsplash.com/photo-1593460354583-4224ab273ef0?auto=format&fit=crop&w=600&q=80"
            ],
            desc: "The F-Type R is Jaguar's most focused sports car in a generation. Its supercharged V8 produces a sound that has been described as the best engine note in the world — raw, operatic, addictive. The all-aluminium body keeps weight obsessively low.",
            specs: [
                { label: "Engine", value: "5.0L", unit: "V8 Supercharged" },
                { label: "Power", value: "575", unit: "bhp" },
                { label: "0–100 km/h", value: "3.7", unit: "seconds" },
                { label: "Top Speed", value: "300", unit: "km/h" },
                { label: "Transmission", value: "8-Spd", unit: "Quickshift" },
                { label: "Drive", value: "AWD", unit: "" }
            ]
        },
        {
            id: 10, name: "Stuttgart Taycan Turbo", type: "EV", price: "16,000,000",
            img: "https://images.unsplash.com/photo-1614162692292-7ac56d7f7f1e?auto=format&fit=crop&w=1200&q=80",
            gallery: [
                "https://images.unsplash.com/photo-1614162692292-7ac56d7f7f1e?auto=format&fit=crop&w=600&q=80",
                "https://images.unsplash.com/photo-1560958089-b8a1929cea89?auto=format&fit=crop&w=600&q=80",
                "https://images.unsplash.com/photo-1603584173870-7f23fdae1b7a?auto=format&fit=crop&w=600&q=80",
                "https://images.unsplash.com/photo-1620015573489-35c82cc708b7?auto=format&fit=crop&w=600&q=80"
            ],
            desc: "Porsche redefines electromobility with the Taycan Turbo — a sports car in every meaningful sense, that happens to be fully electric. The 800-volt architecture enables 270kW rapid charging, and the two-speed rear gearbox gives it an authentic sports car feel that no EV competitor can match.",
            specs: [
                { label: "Motors", value: "2", unit: "Synchronous" },
                { label: "Power", value: "680", unit: "bhp (overboost)" },
                { label: "0–100 km/h", value: "3.2", unit: "seconds" },
                { label: "Range", value: "498", unit: "km (WLTP)" },
                { label: "Charging", value: "270", unit: "kW DC" },
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
            <div class="car-card-num">0${i + 1}</div>
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
                el.style.animationDelay = `${i * 0.08}s`;
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
        // Populate
        document.getElementById('detailHeroImg').src = car.img;
        document.getElementById('detailHeroImg').alt = car.name;
        document.getElementById('detailType').textContent = car.type;
        document.getElementById('detailName').textContent = car.name;
        document.getElementById('detailPrice').textContent = `KSh ${car.price}`;
        document.getElementById('detailDescTitle').textContent = `About the ${car.name}`;
        document.getElementById('detailDesc').textContent = car.desc;

        // Gallery
        const gallery = document.getElementById('detailGallery');
        gallery.innerHTML = '';
        car.gallery.forEach((src, i) => {
            const div = document.createElement('div');
            div.className = 'detail-thumb' + (i === 0 ? ' active' : '');
            div.innerHTML = `<img src="${src}" alt="${car.name} view ${i+1}" loading="lazy">`;
            div.addEventListener('click', () => {
                document.getElementById('detailHeroImg').src = src;
                gallery.querySelectorAll('.detail-thumb').forEach(t => t.classList.remove('active'));
                div.classList.add('active');
            });
            gallery.appendChild(div);
        });

        // Specs
        const specGrid = document.getElementById('specGrid');
        specGrid.innerHTML = '';
        car.specs.forEach(s => {
            const div = document.createElement('div');
            div.className = 'spec-item';
            div.innerHTML = `<div class="spec-label">${s.label}</div><div class="spec-value">${s.value}<span>${s.unit}</span></div>`;
            specGrid.appendChild(div);
        });

        // Open panel
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

        // Hero parallax
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