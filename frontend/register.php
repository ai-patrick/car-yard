<?php
session_start();
require '../backend/db.php';

// If already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];

    // Basic Validation
    if (empty($username) || empty($email) || empty($password)) {
        $error = "All fields are required.";
    } elseif ($password !== $confirm) {
        $error = "Passwords do not match.";
    } else {
        // Hash password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert into database
        try {
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password_hash) VALUES (:username, :email, :password)");
            $stmt->execute([
                ':username' => $username,
                ':email' => $email,
                ':password' => $hashed_password
            ]);
            $success = "Registration successful! You can now login.";
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) { // Integrity constraint violation (Duplicate entry)
                $error = "Username or email already exists.";
            } else {
                $error = "An error occurred. Please try again later.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register | Prestige Auto</title>
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
            max-width: 500px;
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
        .form-group {
            margin-bottom: 1.5rem;
        }
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
        .form-control:focus {
            outline: none;
            border-color: var(--gold);
        }
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
        .auth-btn:hover {
            box-shadow: 0 0 15px rgba(201,168,76,0.3);
        }
        .auth-links {
            margin-top: 2rem;
            text-align: center;
            font-size: 0.9rem;
        }
        .auth-links a {
            color: var(--gold);
            text-decoration: none;
        }
        .msg {
            padding: 1rem;
            margin-bottom: 1.5rem;
            border: 1px solid;
            font-size: 0.9rem;
        }
        .msg-error {
            background: rgba(212, 87, 30, 0.1);
            border-color: var(--accent);
            color: #ff8b5a;
        }
        .msg-success {
            background: rgba(46, 204, 113, 0.1);
            border-color: #2ecc71;
            color: #2ecc71;
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <!-- Reusing hero background style -->
        <div class="hero-bg" style="opacity: 0.2"></div>
        <div class="hero-grain"></div>

        <div class="auth-box">
            <h1 class="auth-title">Create Account</h1>
            
            <?php if ($error): ?>
                <div class="msg msg-error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            <?php if ($success): ?>
                <div class="msg msg-success"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" class="form-control" required value="<?= isset($_POST['username']) ? htmlspecialchars($_POST['username']) : '' ?>">
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" class="form-control" required value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>">
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" class="form-control" required minlength="6">
                </div>
                <div class="form-group">
                    <label for="confirm_password">Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" class="form-control" required minlength="6">
                </div>
                <button type="submit" class="auth-btn">Register</button>
            </form>

            <div class="auth-links">
                <p>Already have an account? <a href="login.php">Log In</a></p>
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
