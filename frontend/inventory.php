<?php
session_start();
require '../backend/db.php';

// Fetch vehicles
try {
    $stmt = $pdo->query("SELECT * FROM vehicles ORDER BY id ASC");
    $db_vehicles = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $cars_data = [];
    foreach ($db_vehicles as $v) {
        $cars_data[] = [
            'id' => (int)$v['id'],
            'name' => $v['name'],
            'type' => $v['type'],
            'price' => number_format($v['price'], 0), 
            'img' => $v['image_url'],
            'desc' => $v['description'],
            'gallery' => [$v['image_url'], $v['image_url'], $v['image_url'], $v['image_url']],
            'specs' => [
                ['label' => 'Engine', 'value' => $v['engine'], 'unit' => ''],
                ['label' => 'Power', 'value' => $v['power'], 'unit' => ''],
                ['label' => '0–100 km/h', 'value' => $v['acceleration'], 'unit' => ''],
                ['label' => 'Drive', 'value' => $v['drive'], 'unit' => '']
            ]
        ];
    }
} catch (PDOException $e) {
    $cars_data = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prestige Auto | Premium Vehicles</title>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="ne.css">
</head>
<body>

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
                <button class="btn-primary-gold" onclick="document.querySelector('.section-inventory').scrollIntoView({behavior:'smooth'})">
                    View Inventory
                </button>
                <button class="btn-outline-gold">Request Appointment</button>
            </div>
        </div>

        <div class="hero-side">
            <div class="hero-stat">
                <div class="hero-stat-num">20</div>
                <div class="hero-stat-label">Premium Models</div>
            </div>
            <div class="hero-divider"></div>
            <div class="hero-stat">
                <div class="hero-stat-num">100%</div>
                <div class="hero-stat-label">Verified History</div>
            </div>
        </div>
    </header>

    <section class="section-inventory">
        <div class="section-header reveal">
            <div>
                <div class="section-label">Our Collection</div>
                <h2 class="section-title">Featured<br>Vehicles</h2>
            </div>
            <button class="view-all-btn" id="viewAllBtn">
                <span id="viewAllText">View All</span>
                <span class="arrow">→</span>
            </button>
        </div>

        <div class="car-grid" id="carGrid">
            </div>
    </section>

    <div id="carDetail">
        <button class="detail-close" id="detailClose">✕</button>

        <div class="detail-hero">
            <img id="detailHeroImg" src="" alt="" class="detail-hero-img">
            <div class="detail-hero-overlay"></div>
            <div class="detail-hero-info">
                <div>
                    <div class="detail-type-badge" id="detailType"></div>
                    <h1 class="detail-name" id="detailName"></h1>
                </div>
                <div>
                    <div class="detail-price" id="detailPrice"></div>
                    <div class="detail-price-sub">Starting price · VAT inclusive</div>
                </div>
            </div>
        </div>

        <div class="detail-body">
            <div class="detail-gallery" id="detailGallery"></div>

            <div class="detail-specs">
                <div class="detail-specs-left">
                    <h3 class="spec-section-title" id="detailDescTitle"></h3>
                    <p class="spec-description" id="detailDesc"></p>
                </div>
                <div class="spec-grid" id="specGrid"></div>
            </div>

            <div class="detail-reserve">
                <div class="reserve-text">
                    <h3>Ready to Reserve?</h3>
                    <p>Secure this vehicle with a fully refundable deposit. Our team will contact you within 24 hours.</p>
                </div>
                <button class="btn-primary-gold" id="reserveBtn" onclick="addToCart()">
                    Reserve This Vehicle →
                </button>
            </div>
        </div>
    </div>

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
                <h4>Services</h4>
                <ul>
                    <li><a href="#">Vehicle Financing</a></li>
                    <li><a href="#">Trade-In</a></li>
                    <li><a href="#">Concierge</a></li>
                    <li><a href="#">Warranty</a></li>
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

    <script>
        const dbCars = <?= json_encode($cars_data) ?>;
    </script>
    <script defer src="ne.js"></script>
</body>
</html>