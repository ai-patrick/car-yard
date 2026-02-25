<?php
session_start();
require '../backend/db.php';

$category = $_GET['type'] ?? '';

// Category metadata
$categories = [
    'Luxury SUVs' => ['desc' => 'Commanding presence meets unparalleled comfort and capability.', 'img' => 'https://images.unsplash.com/photo-1606664515524-ed2f786a0bd6?auto=format&fit=crop&w=1920&q=80'],
    'Luxury Sedans' => ['desc' => 'The art of refined travel — elegance in every detail.', 'img' => 'https://images.unsplash.com/photo-1563720223185-11003d516935?auto=format&fit=crop&w=1920&q=80'],
    'Luxury Coupes' => ['desc' => 'Two doors. Infinite sophistication. Pure driving pleasure.', 'img' => 'https://images.unsplash.com/photo-1544636331-e26879cd4d9b?auto=format&fit=crop&w=1920&q=80'],
    'Luxury Wagons' => ['desc' => 'Performance and practicality wrapped in stunning design.', 'img' => 'https://images.unsplash.com/photo-1503376780353-7e6692767b70?auto=format&fit=crop&w=1920&q=80'],
    'Sports Cars' => ['desc' => 'Born on the track. Refined for the road. Built for thrill.', 'img' => 'https://images.unsplash.com/photo-1580274455191-1c62238fa333?auto=format&fit=crop&w=1920&q=80'],
    'Supercars' => ['desc' => 'Where engineering meets artistry at extraordinary speed.', 'img' => 'https://images.unsplash.com/photo-1621135802920-133df287f89c?auto=format&fit=crop&w=1920&q=80'],
    'Hypercars' => ['desc' => 'The absolute pinnacle of automotive engineering and design.', 'img' => 'https://images.unsplash.com/photo-1600712242805-5f78671b24da?auto=format&fit=crop&w=1920&q=80'],
    'Vintage Classics' => ['desc' => 'Timeless icons that defined generations of motoring history.', 'img' => 'https://images.unsplash.com/photo-1583121274602-3e2820c69888?auto=format&fit=crop&w=1920&q=80'],
    'Electric Luxury Cars' => ['desc' => 'The future of luxury — silent power, zero compromise.', 'img' => 'https://images.unsplash.com/photo-1560958089-b8a1929cea89?auto=format&fit=crop&w=1920&q=80'],
    'Limited Edition' => ['desc' => 'Rare masterpieces. One-of-a-kind vehicles for the discerning collector.', 'img' => 'https://images.unsplash.com/photo-1618843479313-40f8afb4b4d8?auto=format&fit=crop&w=1920&q=80'],
];

$cat_meta = $categories[$category] ?? ['desc' => 'Explore our curated collection.', 'img' => 'https://images.unsplash.com/photo-1492144534655-ae79c964c9d7?auto=format&fit=crop&w=1920&q=80'];

// Fetch vehicles matching this category
try {
    $stmt = $pdo->prepare("SELECT * FROM vehicles WHERE type = :type ORDER BY id ASC");
    $stmt->execute([':type' => $category]);
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
    <title><?= htmlspecialchars($category) ?> | Prestige Auto</title>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="ne.css">
</head>
<body>

    <nav id="mainNav">
        <a class="nav-logo" href="index.php">PRESTIGE<span>AUTO</span></a>
        <ul class="nav-links">
            <li><a href="index.php">Collections</a></li>
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

    <!-- CATEGORY HERO -->
    <header class="hero" id="hero" style="height: 60vh; min-height: 450px;">
        <div class="hero-bg" id="heroBg" style="background-image: url('<?= htmlspecialchars($cat_meta['img']) ?>');"></div>
        <div class="hero-overlay"></div>
        <div class="hero-grain"></div>

        <div class="hero-content" style="padding-bottom: 5rem;">
            <div class="hero-label"><?= htmlspecialchars($category) ?> Collection</div>
            <h1><?= htmlspecialchars($category) ?></h1>
            <p><?= htmlspecialchars($cat_meta['desc']) ?></p>
            <a href="index.php" style="text-decoration: none;">
                <button class="btn-outline-gold">← Back to Collections</button>
            </a>
        </div>
    </header>

    <!-- CAR LISTING -->
    <section class="section-inventory">
        <div class="section-header reveal">
            <div>
                <div class="section-label"><?= htmlspecialchars($category) ?></div>
                <h2 class="section-title">Available<br>Vehicles</h2>
            </div>
            <div style="color: var(--muted); font-size: 0.85rem;">
                <?= count($cars_data) ?> vehicle<?= count($cars_data) !== 1 ? 's' : '' ?> found
            </div>
        </div>

        <?php if (empty($cars_data)): ?>
        <div style="text-align: center; padding: 6rem 2rem;">
            <div style="font-family: 'Cormorant Garamond', serif; font-size: 3rem; font-weight: 300; color: var(--gold-light); margin-bottom: 1rem;">Coming Soon</div>
            <p style="color: var(--muted); font-size: 0.95rem; max-width: 450px; margin: 0 auto 2rem; line-height: 1.7;">
                We're curating an exceptional selection of <?= htmlspecialchars($category) ?> for our collection. 
                Check back soon or browse our other categories.
            </p>
            <a href="index.php" style="text-decoration: none;">
                <button class="btn-primary-gold">Explore Collections</button>
            </a>
        </div>
        <?php else: ?>
        <div class="car-grid" id="carGrid"></div>
        <?php endif; ?>
    </section>

    <!-- CAR DETAIL VIEW -->
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
