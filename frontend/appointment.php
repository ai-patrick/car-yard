<?php
session_start();
require '../backend/db.php';

// Require login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$msg = '';
$msg_type = '';

// Fetch vehicles for the dropdown
try {
    $vstmt = $pdo->query("SELECT id, name FROM vehicles ORDER BY name ASC");
    $vehicle_list = $vstmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $vehicle_list = [];
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = trim($_POST['full_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $preferred_date = $_POST['preferred_date'] ?? '';
    $preferred_time = $_POST['preferred_time'] ?? '';
    $vehicle_interest = $_POST['vehicle_interest'] ?? '';
    $message = trim($_POST['message'] ?? '');

    // Validation
    if (empty($full_name) || empty($email) || empty($phone) || empty($preferred_date) || empty($preferred_time)) {
        $msg = 'Please fill in all required fields.';
        $msg_type = 'error';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $msg = 'Please enter a valid email address.';
        $msg_type = 'error';
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO appointments (user_id, full_name, email, phone, preferred_date, preferred_time, vehicle_interest, message) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $_SESSION['user_id'],
                $full_name,
                $email,
                $phone,
                $preferred_date,
                $preferred_time,
                $vehicle_interest,
                $message
            ]);
            $msg = 'Your appointment request has been submitted successfully! Our team will contact you shortly.';
            $msg_type = 'success';
        } catch (PDOException $e) {
            $msg = 'An error occurred. Please try again later.';
            $msg_type = 'error';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Appointment | Prestige Auto</title>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="ne.css">
    <style>
        .appt-section {
            padding: 10rem 4rem 6rem;
            max-width: 720px;
            margin: 0 auto;
        }

        .appt-header {
            margin-bottom: 3rem;
        }

        .appt-header .section-label {
            color: var(--gold);
            font-size: 0.72rem;
            letter-spacing: 0.25em;
            text-transform: uppercase;
            margin-bottom: 0.8rem;
            display: flex;
            align-items: center;
            gap: 0.8rem;
        }

        .appt-header .section-label::before {
            content: '';
            display: block;
            width: 30px;
            height: 1px;
            background: var(--gold);
        }

        .appt-header h2 {
            font-family: 'Cormorant Garamond', serif;
            font-size: clamp(2rem, 4vw, 3rem);
            font-weight: 300;
            line-height: 1.1;
            margin-bottom: 1rem;
        }

        .appt-header p {
            color: var(--muted);
            font-size: 0.95rem;
            line-height: 1.7;
        }

        .appt-form {
            background: var(--surface);
            border: 1px solid var(--border);
            padding: 3rem;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group.full-width {
            grid-column: 1 / -1;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-size: 0.72rem;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: 0.12em;
            font-weight: 400;
        }

        .form-group label .required {
            color: var(--accent);
            margin-left: 2px;
        }

        .form-control {
            width: 100%;
            background: transparent;
            border: 1px solid var(--border);
            color: var(--text);
            padding: 0.9rem 1rem;
            font-family: 'DM Sans', sans-serif;
            font-size: 0.9rem;
            font-weight: 300;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--gold);
            box-shadow: 0 0 0 3px rgba(201, 168, 76, 0.08);
        }

        .form-control::placeholder {
            color: rgba(122, 117, 112, 0.5);
        }

        select.form-control {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%237a7570' stroke-width='1.5' fill='none'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 1rem center;
            padding-right: 2.5rem;
            cursor: pointer;
        }

        select.form-control option {
            background: var(--surface);
            color: var(--text);
        }

        textarea.form-control {
            resize: vertical;
            min-height: 120px;
        }

        .appt-submit {
            display: flex;
            justify-content: flex-end;
            gap: 1rem;
            margin-top: 0.5rem;
        }

        .appt-submit .btn-primary-gold {
            padding: 1rem 3rem;
        }

        .msg {
            padding: 1.2rem 1.5rem;
            margin-bottom: 2rem;
            border: 1px solid;
            font-size: 0.9rem;
            line-height: 1.5;
        }

        .msg-success {
            background: rgba(46, 204, 113, 0.08);
            border-color: rgba(46, 204, 113, 0.4);
            color: #2ecc71;
        }

        .msg-error {
            background: rgba(212, 87, 30, 0.08);
            border-color: rgba(212, 87, 30, 0.4);
            color: var(--accent);
        }

        @media (max-width: 600px) {
            .appt-section {
                padding: 8rem 1.5rem 4rem;
            }

            .appt-form {
                padding: 1.5rem;
            }

            .form-row {
                grid-template-columns: 1fr;
                gap: 0;
            }
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
                <li><a href="../backend/logout.php">Logout (<?= htmlspecialchars($_SESSION['username']) ?>)</a></li>
            <?php else: ?>
                <li><a href="login.php">Client Portal</a></li>
            <?php endif; ?>
        </ul>
        <button class="nav-garage" onclick="window.location.href='garage.php'">
            Garage <span class="cart-count" id="cartCount">0</span>
        </button>
    </nav>

    <section class="appt-section">
        <div class="appt-header">
            <div class="section-label">Schedule a Visit</div>
            <h2>Request an<br><em style="font-style:italic;color:var(--gold-light)">Appointment</em></h2>
            <p>Complete the form below to schedule a personal viewing at our showroom. Our concierge team will confirm your appointment within 24 hours.</p>
        </div>

        <?php if ($msg): ?>
            <div class="msg msg-<?= $msg_type ?>"><?= htmlspecialchars($msg) ?></div>
        <?php endif; ?>

        <?php if ($msg_type !== 'success'): ?>
        <div class="appt-form">
            <form method="POST" id="appointmentForm">
                <div class="form-row">
                    <div class="form-group">
                        <label>Full Name <span class="required">*</span></label>
                        <input type="text" name="full_name" class="form-control" placeholder="John Doe" value="<?= htmlspecialchars($_POST['full_name'] ?? '') ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Email Address <span class="required">*</span></label>
                        <input type="email" name="email" class="form-control" placeholder="john@example.com" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Phone Number <span class="required">*</span></label>
                        <input type="tel" name="phone" class="form-control" placeholder="+254 700 000 000" value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Vehicle of Interest</label>
                        <select name="vehicle_interest" class="form-control">
                            <option value="">— Select a vehicle (optional) —</option>
                            <?php foreach ($vehicle_list as $veh): ?>
                                <option value="<?= htmlspecialchars($veh['name']) ?>" <?= (isset($_POST['vehicle_interest']) && $_POST['vehicle_interest'] === $veh['name']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($veh['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Preferred Date <span class="required">*</span></label>
                        <input type="date" name="preferred_date" class="form-control" value="<?= htmlspecialchars($_POST['preferred_date'] ?? '') ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Preferred Time <span class="required">*</span></label>
                        <input type="time" name="preferred_time" class="form-control" value="<?= htmlspecialchars($_POST['preferred_time'] ?? '') ?>" required>
                    </div>
                </div>

                <div class="form-group">
                    <label>Additional Message</label>
                    <textarea name="message" class="form-control" placeholder="Any special requests or questions..."><?= htmlspecialchars($_POST['message'] ?? '') ?></textarea>
                </div>

                <div class="appt-submit">
                    <a href="inventory.php" style="text-decoration:none;">
                        <button type="button" class="btn-outline-gold">Back to Showroom</button>
                    </a>
                    <button type="submit" class="btn-primary-gold">Submit Request →</button>
                </div>
            </form>
        </div>
        <?php else: ?>
            <div style="text-align: center; margin-top: 2rem;">
                <a href="inventory.php" style="text-decoration:none;"><button class="btn-primary-gold">Back to Showroom</button></a>
                <a href="appointment.php" style="text-decoration:none; margin-left: 1rem;"><button class="btn-outline-gold">Book Another</button></a>
            </div>
        <?php endif; ?>
    </section>

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

    <script>
        // Cart badge
        let garage = JSON.parse(localStorage.getItem('prestige_garage')) || [];
        const badge = document.getElementById('cartCount');
        if (badge) badge.textContent = garage.length;

        // Set minimum date to today
        const dateInput = document.querySelector('input[name="preferred_date"]');
        if (dateInput) {
            const today = new Date().toISOString().split('T')[0];
            dateInput.setAttribute('min', today);
        }
    </script>
</body>
</html>
