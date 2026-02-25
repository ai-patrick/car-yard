<?php
session_start();
require 'db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Garage | Prestige Auto</title>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="proj.css">
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
        
        <div class="car-grid" id="garageGrid">
            <!-- Rendered by JS -->
        </div>
        <div id="emptyMsg" class="garage-empty" style="display: none;">
            <h3>Your garage is currently empty.</h3>
            <p style="margin-top: 1rem;"><a href="index.php" style="color:var(--gold); text-decoration:none; border-bottom: 1px solid var(--gold);">Return to Showroom</a></p>
        </div>
    </section>

    <footer>
        <p>© 2026 Prestige Auto. All rights reserved.</p>
        <p style="color:var(--muted)">Nairobi, Kenya · +254 700 000 000</p>
    </footer>

    <script>
        // Init logic for displaying localStorage items
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

        function renderGarage() {
            updateCartBadge();
            const grid = document.getElementById('garageGrid');
            const emptyMsg = document.getElementById('emptyMsg');
            
            grid.innerHTML = '';
            
            if (garage.length === 0) {
                emptyMsg.style.display = 'block';
            } else {
                emptyMsg.style.display = 'none';
                
                garage.forEach((car, i) => {
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
                });
            }
        }
        
        // Initial render
        renderGarage();
    </script>
</body>
</html>
