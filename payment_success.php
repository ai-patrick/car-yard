<?php
session_start();
require 'db.php';

$reference = $_GET['reference'] ?? null;
$payment = null;
$vehicles = [];

if ($reference) {
    try {
        $stmt = $pdo->prepare("SELECT p.*, u.username, u.email FROM payments p JOIN users u ON p.user_id = u.id WHERE p.reference = ? AND p.status = 'success'");
        $stmt->execute([$reference]);
        $payment = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($payment) {
            $vehicle_ids = json_decode($payment['vehicle_ids'], true);
            if (!empty($vehicle_ids)) {
                $placeholders = implode(',', array_fill(0, count($vehicle_ids), '?'));
                $stmt = $pdo->prepare("SELECT name, type, price, image_url FROM vehicles WHERE id IN ($placeholders)");
                $stmt->execute($vehicle_ids);
                $vehicles = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
        }
    } catch (PDOException $e) {
        $payment = null;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Confirmed | Prestige Auto</title>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="proj.css">
    <style>
        .success-section {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 6rem 2rem;
            position: relative;
        }
        .success-card {
            background: var(--surface);
            border: 1px solid var(--border);
            max-width: 680px;
            width: 100%;
            padding: 4rem;
            text-align: center;
            position: relative;
            z-index: 2;
        }
        .success-icon {
            width: 80px;
            height: 80px;
            border: 2px solid var(--gold);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 2rem;
            font-size: 2.5rem;
            color: var(--gold);
            animation: scaleIn 0.5s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
        }
        @keyframes scaleIn {
            from { transform: scale(0); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }
        .success-title {
            font-family: 'Cormorant Garamond', serif;
            font-size: 2.5rem;
            font-weight: 300;
            margin-bottom: 1rem;
            color: var(--gold-light);
        }
        .success-subtitle {
            color: var(--muted);
            font-size: 0.95rem;
            line-height: 1.6;
            margin-bottom: 2.5rem;
        }
        .receipt-details {
            border-top: 1px solid var(--border);
            padding-top: 2rem;
            text-align: left;
        }
        .receipt-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.8rem 0;
            border-bottom: 1px solid var(--border);
        }
        .receipt-label {
            font-size: 0.75rem;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: var(--muted);
        }
        .receipt-value {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.1rem;
            color: var(--text);
        }
        .receipt-value.ref {
            font-family: 'DM Sans', sans-serif;
            font-size: 0.85rem;
            color: var(--gold);
        }
        .vehicle-list {
            margin-top: 2rem;
            border-top: 1px solid var(--border);
            padding-top: 1.5rem;
        }
        .vehicle-list-title {
            font-size: 0.75rem;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: var(--muted);
            margin-bottom: 1rem;
        }
        .vehicle-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 0.8rem 0;
            border-bottom: 1px solid var(--border);
        }
        .vehicle-item img {
            width: 60px;
            height: 45px;
            object-fit: cover;
            filter: brightness(0.8);
        }
        .vehicle-item-info {
            flex: 1;
        }
        .vehicle-item-name {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.1rem;
        }
        .vehicle-item-type {
            font-size: 0.7rem;
            color: var(--gold);
            letter-spacing: 0.15em;
            text-transform: uppercase;
        }
        .vehicle-item-price {
            font-size: 0.9rem;
            color: var(--muted);
        }
        .success-actions {
            margin-top: 2.5rem;
            display: flex;
            gap: 1rem;
            justify-content: center;
        }
        .success-actions a {
            text-decoration: none;
        }
        .error-section {
            text-align: center;
            padding: 8rem 2rem;
        }
        .error-section h2 {
            font-family: 'Cormorant Garamond', serif;
            font-size: 2rem;
            color: var(--accent);
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>

    <nav id="mainNav" class="scrolled">
        <a class="nav-logo" href="index.php">PRESTIGE<span>AUTO</span></a>
        <ul class="nav-links">
            <li><a href="inventory.php">Showroom</a></li>
            <?php if (isset($_SESSION['user_id'])): ?>
                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                    <li><a href="admin.php">Admin Dashboard</a></li>
                <?php endif; ?>
                <li><a href="logout.php">Logout (<?= htmlspecialchars($_SESSION['username']) ?>)</a></li>
            <?php else: ?>
                <li><a href="login.php">Client Portal</a></li>
            <?php endif; ?>
        </ul>
        <button class="nav-garage" onclick="window.location.href='garage.php'">
            Garage <span class="cart-count" id="cartCount">0</span>
        </button>
    </nav>

    <?php if ($payment): ?>
    <section class="success-section">
        <div class="hero-bg" style="opacity: 0.1"></div>
        <div class="hero-grain"></div>

        <div class="success-card">
            <div class="success-icon">✓</div>
            <h1 class="success-title">Payment Confirmed</h1>
            <p class="success-subtitle">
                Thank you, <strong><?= htmlspecialchars($payment['username']) ?></strong>. 
                Your payment has been successfully processed. Our team will be in touch within 24 hours to arrange delivery.
            </p>

            <div class="receipt-details">
                <div class="receipt-row">
                    <span class="receipt-label">Reference</span>
                    <span class="receipt-value ref"><?= htmlspecialchars($payment['reference']) ?></span>
                </div>
                <div class="receipt-row">
                    <span class="receipt-label">Amount Paid</span>
                    <span class="receipt-value">KSh <?= number_format($payment['amount'], 0) ?></span>
                </div>
                <div class="receipt-row">
                    <span class="receipt-label">Date</span>
                    <span class="receipt-value"><?= date('d M Y, H:i', strtotime($payment['created_at'])) ?></span>
                </div>
                <div class="receipt-row">
                    <span class="receipt-label">Email</span>
                    <span class="receipt-value ref"><?= htmlspecialchars($payment['email']) ?></span>
                </div>
            </div>

            <?php if (!empty($vehicles)): ?>
            <div class="vehicle-list">
                <div class="vehicle-list-title">Vehicles Purchased</div>
                <?php foreach ($vehicles as $v): ?>
                    <div class="vehicle-item">
                        <img src="<?= htmlspecialchars($v['image_url']) ?>" alt="<?= htmlspecialchars($v['name']) ?>">
                        <div class="vehicle-item-info">
                            <div class="vehicle-item-name"><?= htmlspecialchars($v['name']) ?></div>
                            <div class="vehicle-item-type"><?= htmlspecialchars($v['type']) ?></div>
                        </div>
                        <div class="vehicle-item-price">KSh <?= number_format($v['price'], 0) ?></div>
                    </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>

            <div class="success-actions">
                <a href="inventory.php"><button class="btn-primary-gold">Continue Shopping</button></a>
                <a href="garage.php"><button class="btn-outline-gold">View Garage</button></a>
            </div>
        </div>
    </section>
    <?php else: ?>
    <section class="error-section">
        <h2>Payment Not Found</h2>
        <p style="color: var(--muted); margin-bottom: 2rem;">We couldn't find a payment record for this reference.</p>
        <a href="garage.php" style="color: var(--gold); text-decoration: none; border-bottom: 1px solid var(--gold);">Return to Garage</a>
    </section>
    <?php endif; ?>

    <footer>
        <p>© 2026 Prestige Auto. All rights reserved.</p>
        <p style="color:var(--muted)">Nairobi, Kenya · +254 700 000 000</p>
    </footer>

    <script>
        // Clear the garage after successful payment
        <?php if ($payment): ?>
        localStorage.removeItem('prestige_garage');
        <?php endif; ?>

        // Update badge
        let garage = JSON.parse(localStorage.getItem('prestige_garage')) || [];
        const badge = document.getElementById('cartCount');
        if (badge) badge.textContent = garage.length;
    </script>
</body>
</html>
