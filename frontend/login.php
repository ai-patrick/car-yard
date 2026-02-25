<?php
session_start();
require '../backend/db.php';

// If already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $error = "Both email and password are required.";
    } else {
        try {
            $stmt = $pdo->prepare("SELECT id, username, password_hash, role FROM users WHERE email = :email");
            $stmt->execute([':email' => $email]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password_hash'])) {
                // Login success
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];

                // Cookie preference parsing (optional example feature context)
                if (isset($_POST['remember'])) {
                    // Store email in cookie for 30 days
                    setcookie('remember_email', $email, time() + (86400 * 30), "/");
                }

                if ($user['role'] === 'admin') {
                    header("Location: admin.php");
                } else {
                    header("Location: index.php");
                }
                exit;
            } else {
                $error = "Invalid email or password.";
            }
        } catch (PDOException $e) {
            $error = "An error occurred. Please try again later.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login | Prestige Auto</title>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;600&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="proj.css">
    <style>
        .auth-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 4rem 2rem;
            position: relative;
        }
        .auth-box {
            background: rgba(17, 17, 17, 0.95);
            padding: 3rem;
            border: 1px solid var(--border);
            width: 100%;
            max-width: 450px;
            z-index: 2;
            backdrop-filter: blur(10px);
        }
        .auth-title {
            font-family: 'Cormorant Garamond', serif;
            font-size: 2.5rem;
            color: var(--gold);
            margin-bottom: 2rem;
            text-align: center;
        }
        .form-group { margin-bottom: 1.5rem; }
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-size: 0.8rem;
            color: var(--muted);
            letter-spacing: 0.1em;
            text-transform: uppercase;
        }
        .form-control {
            width: 100%;
            background: transparent;
            border: 1px solid var(--border);
            color: var(--text);
            padding: 1rem;
            font-family: 'DM Sans', sans-serif;
            transition: border-color 0.3s;
        }
        .form-control:focus { outline: none; border-color: var(--gold); }
        .auth-btn {
            width: 100%;
            margin-top: 1rem;
            padding: 1rem;
            background: var(--gold);
            color: var(--bg);
            border: none;
            font-family: 'DM Sans', sans-serif;
            font-weight: 500;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            cursor: pointer;
            transition: all 0.3s;
        }
        .auth-btn:hover { box-shadow: 0 0 15px rgba(201,168,76,0.3); }
        
        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
            font-size: 0.85rem;
            color: var(--muted);
        }
        
        .auth-links { margin-top: 2rem; text-align: center; font-size: 0.9rem; }
        .auth-links a { color: var(--gold); text-decoration: none; }
        .msg { padding: 1rem; margin-bottom: 1.5rem; border: 1px solid; font-size: 0.9rem; }
        .msg-error { background: rgba(212, 87, 30, 0.1); border-color: var(--accent); color: #ff8b5a; }
    </style>
</head>
<body>
    <div class="auth-container">
        <!-- Reusing hero background style -->
        <div class="hero-bg" style="opacity: 0.2"></div>
        <div class="hero-grain"></div>

        <div class="auth-box">
            <h1 class="auth-title">Client Portal</h1>
            
            <?php if ($error): ?>
                <div class="msg msg-error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" class="form-control" required 
                           value="<?= isset($_COOKIE['remember_email']) ? htmlspecialchars($_COOKIE['remember_email']) : '' ?>">
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" class="form-control" required>
                </div>
                
                <div class="checkbox-group">
                    <input type="checkbox" id="remember" name="remember" <?= isset($_COOKIE['remember_email']) ? 'checked' : '' ?>>
                    <label for="remember" style="margin: 0; text-transform: none; letter-spacing: normal;">Remember Me</label>
                </div>

                <button type="submit" class="auth-btn">Sign In</button>
            </form>

            <div class="auth-links">
                <p>New client? <a href="register.php">Create an Account</a></p>
                <p style="margin-top: 10px"><a href="index.php">← Back to Showroom</a></p>
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
</body>
</html>
