<?php
session_start();
require 'db.php';
require 'paystack_config.php';

// Get user email for Paystack if logged in
$user_email = '';
if (isset($_SESSION['user_id'])) {
    try {
        $stmt = $pdo->prepare("SELECT email FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        $user_email = $user['email'] ?? '';
    } catch (PDOException $e) {
        $user_email = '';
    }
}

$payment_error = $_GET['payment_error'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Garage | Prestige Auto</title>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="proj.css">
    <script src="https://js.paystack.co/v1/inline.js"></script>
    <style>
        .garage-section {
            padding: 8rem 4rem;
            min-height: 80vh;
        }
        .garage-empty {
            text-align: center;
            font-family: 'DM Sans', sans-serif;
            color: var(--muted);
            margin-top: 4rem;
        }
        .btn-remove {
            background: #d4571e;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            font-family: 'DM Sans', sans-serif;
            text-transform: uppercase;
            font-size: 0.7rem;
            letter-spacing: 0.1em;
            cursor: pointer;
            width: 100%;
            margin-top: 10px;
        }

        /* ─── ORDER SUMMARY ─────────────────────────────────── */
        .order-summary {
            max-width: 700px;
            margin: 3rem auto 0;
            background: var(--surface);
            border: 1px solid var(--border);
            padding: 2.5rem;
            display: none;
        }
        .order-summary.show {
            display: block;
            animation: fadeUp 0.6s ease forwards;
        }
        .order-title {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.8rem;
            font-weight: 300;
            margin-bottom: 1.5rem;
            color: var(--gold-light);
            display: flex;
            align-items: center;
            gap: 0.8rem;
        }
        .order-title::before {
            content: '';
            display: block;
            width: 30px;
            height: 1px;
            background: var(--gold);
        }
        .order-line {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 0;
            border-bottom: 1px solid var(--border);
        }
        .order-line:last-of-type {
            border-bottom: none;
        }
        .order-car-name {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.15rem;
            color: var(--text);
        }
        .order-car-type {
            font-size: 0.65rem;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            color: var(--gold);
            margin-top: 0.2rem;
        }
        .order-car-price {
            font-size: 0.95rem;
            color: var(--muted);
            white-space: nowrap;
        }
        .order-divider {
            height: 1px;
            background: var(--gold);
            opacity: 0.3;
            margin: 0.5rem 0;
        }
        .order-total-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.5rem 0 0;
        }
        .order-total-label {
            font-size: 0.75rem;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            color: var(--muted);
        }
        .order-total-amount {
            font-family: 'Cormorant Garamond', serif;
            font-size: 2rem;
            font-weight: 400;
            color: var(--gold-light);
        }
        .paystack-section {
            margin-top: 2rem;
            text-align: center;
        }
        .btn-paystack {
            background: linear-gradient(135deg, var(--gold) 0%, #b8942f 100%);
            color: var(--bg);
            border: none;
            padding: 1.2rem 3rem;
            font-family: 'DM Sans', sans-serif;
            font-size: 0.9rem;
            font-weight: 500;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            cursor: pointer;
            position: relative;
            overflow: hidden;
            transition: all 0.4s ease;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.8rem;
        }
        .btn-paystack::after {
            content: '';
            position: absolute;
            inset: 0;
            background: rgba(255,255,255,0.15);
            transform: translateX(-100%);
            transition: transform 0.4s ease;
        }
        .btn-paystack:hover::after {
            transform: translateX(0);
        }
        .btn-paystack:hover {
            box-shadow: 0 0 30px rgba(201,168,76,0.3);
            transform: translateY(-2px);
        }
        .btn-paystack:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none;
        }
        .btn-paystack:disabled:hover {
            box-shadow: none;
            transform: none;
        }
        .btn-paystack:disabled::after {
            display: none;
        }
        .paystack-logo {
            height: 18px;
            vertical-align: middle;
        }
        .login-prompt {
            text-align: center;
            margin-top: 1.5rem;
            padding: 1.2rem;
            border: 1px solid var(--border);
            background: rgba(201,168,76,0.05);
        }
        .login-prompt p {
            color: var(--muted);
            font-size: 0.85rem;
            margin-bottom: 0.8rem;
        }
        .login-prompt a {
            color: var(--gold);
            text-decoration: none;
            border-bottom: 1px solid var(--gold);
            font-size: 0.85rem;
            letter-spacing: 0.1em;
            text-transform: uppercase;
        }
        .payment-error {
            max-width: 700px;
            margin: 1rem auto;
            padding: 1rem 1.5rem;
            background: rgba(212, 87, 30, 0.1);
            border: 1px solid var(--accent);
            color: #ff8b5a;
            font-size: 0.9rem;
            text-align: center;
        }
        .processing-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(10,10,10,0.9);
            z-index: 5000;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            gap: 1.5rem;
        }
        .processing-overlay.show {
            display: flex;
        }
        .spinner {
            width: 40px;
            height: 40px;
            border: 2px solid var(--border);
            border-top-color: var(--gold);
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        .processing-text {
            color: var(--gold);
            font-size: 0.8rem;
            letter-spacing: 0.2em;
            text-transform: uppercase;
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(25px); }
            to { opacity: 1; transform: translateY(0); }
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

    <section class="garage-section">
        <div class="section-label" style="text-align: center;">Your Selection</div>
        <h1 class="section-title" style="text-align: center; margin-bottom: 3rem;">My Garage</h1>
        
        <?php if ($payment_error): ?>
            <div class="payment-error"><?= htmlspecialchars($payment_error) ?></div>
        <?php endif; ?>

        <div class="car-grid" id="garageGrid">
            <!-- Rendered by JS -->
        </div>
        <div id="emptyMsg" class="garage-empty" style="display: none;">
            <h3>Your garage is currently empty.</h3>
            <p style="margin-top: 1rem;"><a href="inventory.php" style="color:var(--gold); text-decoration:none; border-bottom: 1px solid var(--gold);">Browse the Showroom</a></p>
        </div>

        <!-- Order Summary -->
        <div class="order-summary" id="orderSummary">
            <div class="order-title">Order Summary</div>
            <div id="orderLines">
                <!-- Rendered by JS -->
            </div>
            <div class="order-divider"></div>
            <div class="order-total-row">
                <span class="order-total-label">Grand Total</span>
                <span class="order-total-amount" id="grandTotal">KSh 0</span>
            </div>

            <div class="paystack-section">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <button class="btn-paystack" id="btnPaystack" onclick="payWithPaystack()">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                        Pay via Paystack
                    </button>
                <?php else: ?>
                    <div class="login-prompt">
                        <p>Sign in to complete your purchase</p>
                        <a href="login.php">Sign In →</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Processing overlay -->
    <div class="processing-overlay" id="processingOverlay">
        <div class="spinner"></div>
        <div class="processing-text">Initializing Payment...</div>
    </div>

    <footer>
        <p>© 2026 Prestige Auto. All rights reserved.</p>
        <p style="color:var(--muted)">Nairobi, Kenya · +254 700 000 000</p>
    </footer>

    <script>
        // ─── GARAGE DATA ──────────────────────────────────────
        let garage = JSON.parse(localStorage.getItem('prestige_garage')) || [];
        
        function updateCartBadge() {
            const badge = document.getElementById('cartCount');
            if (badge) badge.textContent = garage.length;
        }

        function removeCar(id) {
            garage = garage.filter(c => c.id !== id);
            localStorage.setItem('prestige_garage', JSON.stringify(garage));
            renderGarage();
        }

        function parsePrice(priceStr) {
            // Remove commas and non-numeric characters except dots
            if (typeof priceStr === 'number') return priceStr;
            return parseFloat(String(priceStr).replace(/[^0-9.]/g, '')) || 0;
        }

        function formatPrice(num) {
            return num.toLocaleString('en-KE');
        }

        function renderGarage() {
            updateCartBadge();
            const grid = document.getElementById('garageGrid');
            const emptyMsg = document.getElementById('emptyMsg');
            const orderSummary = document.getElementById('orderSummary');
            const orderLines = document.getElementById('orderLines');
            
            grid.innerHTML = '';
            orderLines.innerHTML = '';
            
            if (garage.length === 0) {
                emptyMsg.style.display = 'block';
                orderSummary.classList.remove('show');
            } else {
                emptyMsg.style.display = 'none';
                orderSummary.classList.add('show');
                
                let grandTotal = 0;

                garage.forEach((car, i) => {
                    // ─── Car card ───
                    const div = document.createElement('div');
                    div.className = 'car-card show'; 
                    div.innerHTML = `
                        <img src="${car.img}" class="car-card-img" alt="${car.name}">
                        <div class="car-card-overlay"></div>
                        <div class="car-card-content">
                            <div class="car-card-type">${car.type}</div>
                            <h3 class="car-card-name">${car.name}</h3>
                            <div class="car-card-price">KSh ${car.price}</div>
                        </div>
                        <div class="car-card-cta" style="bottom: 10px; opacity: 1; transform: translateY(0);">
                            <button class="btn-remove" onclick="removeCar(${car.id})">Remove</button>
                        </div>
                    `;
                    grid.appendChild(div);

                    // ─── Order line ───
                    const price = parsePrice(car.price);
                    grandTotal += price;

                    const line = document.createElement('div');
                    line.className = 'order-line';
                    line.innerHTML = `
                        <div>
                            <div class="order-car-name">${car.name}</div>
                            <div class="order-car-type">${car.type}</div>
                        </div>
                        <div class="order-car-price">KSh ${formatPrice(price)}</div>
                    `;
                    orderLines.appendChild(line);
                });

                document.getElementById('grandTotal').textContent = 'KSh ' + formatPrice(grandTotal);
            }
        }

        // ─── PAYSTACK PAYMENT ─────────────────────────────────
        function payWithPaystack() {
            const btn = document.getElementById('btnPaystack');
            const overlay = document.getElementById('processingOverlay');
            
            if (garage.length === 0) {
                alert('Your garage is empty.');
                return;
            }

            btn.disabled = true;
            overlay.classList.add('show');

            const vehicleIds = garage.map(c => c.id);

            fetch('paystack_initialize.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ vehicle_ids: vehicleIds })
            })
            .then(res => res.json())
            .then(data => {
                overlay.classList.remove('show');

                if (!data.status) {
                    alert(data.error || 'Failed to initialize payment.');
                    btn.disabled = false;
                    return;
                }

                // Open Paystack Popup
                const handler = PaystackPop.setup({
                    key: '<?= PAYSTACK_PUBLIC_KEY ?>',
                    email: '<?= htmlspecialchars($user_email) ?>',
                    amount: garage.reduce((sum, c) => sum + parsePrice(c.price), 0) * 100,
                    currency: 'KES',
                    ref: data.reference,
                    callback: function(response) {
                        // Payment completed — redirect to callback for server-side verification
                        window.location.href = 'paystack_callback.php?reference=' + encodeURIComponent(response.reference);
                    },
                    onClose: function() {
                        btn.disabled = false;
                    }
                });
                handler.openIframe();
            })
            .catch(err => {
                overlay.classList.remove('show');
                btn.disabled = false;
                alert('Network error. Please try again.');
                console.error(err);
            });
        }
        
        // Initial render
        renderGarage();
    </script>
</body>
</html>
