<?php
session_start();
require '../backend/db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prestige Auto | Premium Vehicles</title>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="proj.css">
</head>
<body>

    <!-- Page Transition -->
    <div class="page-transition" id="pageTransition"></div>

    <!-- NAV -->
    <nav id="mainNav">
        <a class="nav-logo" href="index.php">PRESTIGE<span>AUTO</span></a>
        <ul class="nav-links">
            <li><a href="inventory.php">Showroom</a></li>
            <?php if (isset($_SESSION['user_id'])): ?>
                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                    <li><a href="admin.php">Admin Dashboard</a></li>
                <?php endif; ?>
                <li><a href="../backend/logout.php">Logout (<?= htmlspecialchars($_SESSION['username']) ?>)</a></li>
            <?php else: ?>
                <li><a href="login.php">Client Portal</a></li>
            <?php endif; ?>
        </ul>
        <button class="nav-garage" onclick="window.location.href='garage.php'">
            Garage <span class="cart-count" id="cartCount">0</span>
        </button>
    </nav>

    <!-- HERO -->
    <header class="hero" id="hero">
        <div class="hero-bg" id="heroBg"></div>
        <div class="hero-overlay"></div>
        <div class="hero-grain"></div>
        <div class="hero-line"></div>

        <div class="hero-content">
            <div class="hero-label">Est. 2018 · Nairobi, Kenya</div>
            <h1>Find Your<br><em>Dream Ride</em></h1>
            <p>Uncompromising luxury. Breathtaking performance. Every vehicle in our collection is a statement of intent.</p>
            <div class="hero-cta">
                <button class="btn-primary-gold" onclick="document.querySelector('.categories-section').scrollIntoView({behavior:'smooth'})">
                    Explore Categories
                </button>
                <a href="inventory.php" style="text-decoration:none;"><button class="btn-outline-gold">View All Cars</button></a>
            </div>
        </div>

        <div class="hero-side">
            <div class="hero-stat">
                <div class="hero-stat-num">10</div>
                <div class="hero-stat-label">Collections</div>
            </div>
            <div class="hero-divider"></div>
            <div class="hero-stat">
                <div class="hero-stat-num">100%</div>
                <div class="hero-stat-label">Verified History</div>
            </div>
        </div>

        <div class="scroll-indicator">
            <span>Scroll</span>
            <div class="scroll-line"></div>
        </div>
    </header>

    <!-- MARQUEE -->
    <div class="marquee-section" style="margin-top:0;margin-bottom:0;border-bottom:none;">
        <div class="marquee-track" id="marqueeTrack">
            <div class="marquee-item">Luxury SUVs <span class="dot"></span></div>
            <div class="marquee-item">Luxury Sedans <span class="dot"></span></div>
            <div class="marquee-item">Supercars <span class="dot"></span></div>
            <div class="marquee-item">Hypercars <span class="dot"></span></div>
            <div class="marquee-item">Electric Luxury <span class="dot"></span></div>
            <div class="marquee-item">Vintage Classics <span class="dot"></span></div>
            <div class="marquee-item">Luxury SUVs <span class="dot"></span></div>
            <div class="marquee-item">Luxury Sedans <span class="dot"></span></div>
            <div class="marquee-item">Supercars <span class="dot"></span></div>
            <div class="marquee-item">Hypercars <span class="dot"></span></div>
            <div class="marquee-item">Electric Luxury <span class="dot"></span></div>
            <div class="marquee-item">Vintage Classics <span class="dot"></span></div>
        </div>
    </div>

    <!-- CATEGORIES -->
    <section class="categories-section">
        <div class="section-header reveal">
            <div>
                <div class="section-label">Our Collections</div>
                <h2 class="section-title">Browse by<br>Category</h2>
            </div>
        </div>

        <div class="category-grid">

            <a href="category.php?type=Luxury+SUVs" class="category-card reveal">
                <img src="images/pure.jpg" class="category-card-img" alt="Luxury SUVs">
                <div class="category-card-overlay"></div>
                <div class="category-card-num">01</div>
                <div class="category-card-content">
                    <div class="category-card-label">Collection</div>
                    <h3 class="category-card-title">Luxury SUVs</h3>
                    <p class="category-card-desc">Commanding presence meets unparalleled comfort and capability.</p>
                    <div class="category-card-cta">View Collection <span>→</span></div>
                </div>
            </a>

            <a href="category.php?type=Luxury+Sedans" class="category-card reveal">
                <img src="images/rs7.jpg" class="category-card-img" alt="Luxury Sedans">
                <div class="category-card-overlay"></div>
                <div class="category-card-num">02</div>
                <div class="category-card-content">
                    <div class="category-card-label">Collection</div>
                    <h3 class="category-card-title">Luxury Sedans</h3>
                    <p class="category-card-desc">The art of refined travel — elegance in every detail.</p>
                    <div class="category-card-cta">View Collection <span>→</span></div>
                </div>
            </a>

            <a href="category.php?type=Luxury+Coupes" class="category-card reveal">
                <img src="images/amggt.jpg" class="category-card-img" alt="Luxury Coupes">
                <div class="category-card-overlay"></div>
                <div class="category-card-num">03</div>
                <div class="category-card-content">
                    <div class="category-card-label">Collection</div>
                    <h3 class="category-card-title">Luxury Coupes</h3>
                    <p class="category-card-desc">Two doors. Infinite sophistication. Pure driving pleasure.</p>
                    <div class="category-card-cta">View Collection <span>→</span></div>
                </div>
            </a>

            <a href="category.php?type=Luxury+Wagons" class="category-card reveal">
                <img src="https://images.unsplash.com/photo-1503376780353-7e6692767b70?auto=format&fit=crop&w=1200&q=80" class="category-card-img" alt="Luxury Wagons">
                <div class="category-card-overlay"></div>
                <div class="category-card-num">04</div>
                <div class="category-card-content">
                    <div class="category-card-label">Collection</div>
                    <h3 class="category-card-title">Luxury Wagons</h3>
                    <p class="category-card-desc">Performance and practicality wrapped in stunning design.</p>
                    <div class="category-card-cta">View Collection <span>→</span></div>
                </div>
            </a>

            <a href="category.php?type=Sports+Cars" class="category-card reveal">
                <img src="https://images.unsplash.com/photo-1580274455191-1c62238fa333?auto=format&fit=crop&w=1200&q=80" class="category-card-img" alt="Sports Cars">
                <div class="category-card-overlay"></div>
                <div class="category-card-num">05</div>
                <div class="category-card-content">
                    <div class="category-card-label">Collection</div>
                    <h3 class="category-card-title">Sports Cars</h3>
                    <p class="category-card-desc">Born on the track. Refined for the road. Built for thrill.</p>
                    <div class="category-card-cta">View Collection <span>→</span></div>
                </div>
            </a>

            <a href="category.php?type=Supercars" class="category-card reveal">
                <img src="https://images.unsplash.com/photo-1621135802920-133df287f89c?auto=format&fit=crop&w=1200&q=80" class="category-card-img" alt="Supercars">
                <div class="category-card-overlay"></div>
                <div class="category-card-num">06</div>
                <div class="category-card-content">
                    <div class="category-card-label">Collection</div>
                    <h3 class="category-card-title">Supercars</h3>
                    <p class="category-card-desc">Where engineering meets artistry at extraordinary speed.</p>
                    <div class="category-card-cta">View Collection <span>→</span></div>
                </div>
            </a>

            <a href="category.php?type=Hypercars" class="category-card reveal">
                <img src="https://images.unsplash.com/photo-1600712242805-5f78671b24da?auto=format&fit=crop&w=1200&q=80" class="category-card-img" alt="Hypercars">
                <div class="category-card-overlay"></div>
                <div class="category-card-num">07</div>
                <div class="category-card-content">
                    <div class="category-card-label">Collection</div>
                    <h3 class="category-card-title">Hypercars</h3>
                    <p class="category-card-desc">The absolute pinnacle of automotive engineering and design.</p>
                    <div class="category-card-cta">View Collection <span>→</span></div>
                </div>
            </a>

            <a href="category.php?type=Vintage+Classics" class="category-card reveal">
                <img src="https://images.unsplash.com/photo-1583121274602-3e2820c69888?auto=format&fit=crop&w=1200&q=80" class="category-card-img" alt="Vintage Classics">
                <div class="category-card-overlay"></div>
                <div class="category-card-num">08</div>
                <div class="category-card-content">
                    <div class="category-card-label">Collection</div>
                    <h3 class="category-card-title">Vintage Classics</h3>
                    <p class="category-card-desc">Timeless icons that defined generations of motoring history.</p>
                    <div class="category-card-cta">View Collection <span>→</span></div>
                </div>
            </a>

            <a href="category.php?type=Electric+Luxury+Cars" class="category-card reveal">
                <img src="https://images.unsplash.com/photo-1560958089-b8a1929cea89?auto=format&fit=crop&w=1200&q=80" class="category-card-img" alt="Electric Luxury Cars">
                <div class="category-card-overlay"></div>
                <div class="category-card-num">09</div>
                <div class="category-card-content">
                    <div class="category-card-label">Collection</div>
                    <h3 class="category-card-title">Electric Luxury Cars</h3>
                    <p class="category-card-desc">The future of luxury — silent power, zero compromise.</p>
                    <div class="category-card-cta">View Collection <span>→</span></div>
                </div>
            </a>

            <a href="category.php?type=Limited+Edition" class="category-card category-card-wide reveal">
                <img src="https://images.unsplash.com/photo-1618843479313-40f8afb4b4d8?auto=format&fit=crop&w=1600&q=80" class="category-card-img" alt="Limited Edition & Collector Cars">
                <div class="category-card-overlay"></div>
                <div class="category-card-num">10</div>
                <div class="category-card-content">
                    <div class="category-card-label">Exclusive Collection</div>
                    <h3 class="category-card-title">Limited Edition & Collector Cars</h3>
                    <p class="category-card-desc">Rare masterpieces. One-of-a-kind vehicles for the discerning collector.</p>
                    <div class="category-card-cta">View Collection <span>→</span></div>
                </div>
            </a>

        </div>
    </section>

    <!-- FOOTER -->
    <footer class="site-footer">
        <div class="footer-content">
            <div class="footer-brand">
                <a class="nav-logo" href="index.php">PRESTIGE<span>AUTO</span></a>
                <p>Uncompromising luxury and breathtaking performance. Every vehicle in our collection is a statement of automotive excellence.</p>
            </div>
            <div class="footer-col">
                <h4>Navigate</h4>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="inventory.php">Showroom</a></li>
                    <li><a href="garage.php">My Garage</a></li>
                    <li><a href="login.php">Client Portal</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>Contact</h4>
                <p>Nairobi, Kenya<br>+254 700 000 000<br>info@prestigeauto.co.ke</p>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2026 Prestige Auto. All rights reserved.</p>
            <div class="footer-socials">
                <a href="#" title="Instagram">IG</a>
                <a href="#" title="Twitter">X</a>
                <a href="#" title="Facebook">FB</a>
                <a href="#" title="LinkedIn">IN</a>
            </div>
        </div>
    </footer>

    <script src="proj.js" defer></script>
</body>
</html>