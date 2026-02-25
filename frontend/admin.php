<?php
session_start();
require '../backend/db.php';

// Check if admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

$msg = '';

// Handle CRUD Operations
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'] ?? '';

    if ($action === 'add' || $action === 'edit') {
        $id = $_POST['id'] ?? null;
        $name = $_POST['name'];
        $type = $_POST['type'];
        $price = $_POST['price'];
        $image_url = $_POST['image_url'];
        $desc = $_POST['description'];
        $engine = $_POST['engine'];
        $power = $_POST['power'];
        $accel = $_POST['acceleration'];
        $drive = $_POST['drive'];

        if ($action === 'add') {
            $stmt = $pdo->prepare("INSERT INTO vehicles (name, type, price, image_url, description, engine, power, acceleration, drive) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$name, $type, $price, $image_url, $desc, $engine, $power, $accel, $drive]);
            $msg = "<div class='msg msg-success'>Vehicle added successfully.</div>";
        } else if ($action === 'edit' && $id) {
            $stmt = $pdo->prepare("UPDATE vehicles SET name=?, type=?, price=?, image_url=?, description=?, engine=?, power=?, acceleration=?, drive=? WHERE id=?");
            $stmt->execute([$name, $type, $price, $image_url, $desc, $engine, $power, $accel, $drive, $id]);
            $msg = "<div class='msg msg-success'>Vehicle updated successfully.</div>";
        }
    } elseif ($action === 'delete') {
        $id = $_POST['id'];
        $stmt = $pdo->prepare("DELETE FROM vehicles WHERE id=?");
        $stmt->execute([$id]);
        $msg = "<div class='msg msg-success'>Vehicle deleted successfully.</div>";
    } elseif ($action === 'update_appointment') {
        $id = $_POST['id'];
        $status = $_POST['status'];
        $stmt = $pdo->prepare("UPDATE appointments SET status=? WHERE id=?");
        $stmt->execute([$status, $id]);
        $msg = "<div class='msg msg-success'>Appointment status updated.</div>";
    }
}

// Fetch all vehicles for the table
$stmt = $pdo->query("SELECT * FROM vehicles ORDER BY id DESC");
$vehicles = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch all appointments
try {
    $appt_stmt = $pdo->query("SELECT a.*, u.username FROM appointments a LEFT JOIN users u ON a.user_id = u.id ORDER BY a.created_at DESC");
    $appointments = $appt_stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $appointments = [];
}
$pending_count = count(array_filter($appointments, fn($a) => $a['status'] === 'pending'));

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard | Prestige Auto</title>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;600&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="proj.css">
    <style>
        body { background: var(--bg); padding: 6rem 4rem; overflow-y: auto; }
        .admin-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 3rem; }
        .admin-title { font-family: 'Cormorant Garamond', serif; font-size: 2.5rem; color: var(--gold); }
        .btn { padding: 0.8rem 1.5rem; background: var(--gold); color: var(--bg); border: none; font-family: 'DM Sans', sans-serif; cursor: pointer; text-transform: uppercase; font-size: 0.8rem; letter-spacing: 0.1em; text-decoration: none; display: inline-block; }
        .btn-danger { background: #d4571e; color: white; }
        .admin-grid { display: grid; grid-template-columns: 1fr 350px; gap: 3rem; }
        
        table { width: 100%; border-collapse: collapse; background: var(--surface); border: 1px solid var(--border); }
        th, td { padding: 1rem; text-align: left; border-bottom: 1px solid var(--border); font-size: 0.9rem; }
        th { color: var(--muted); text-transform: uppercase; letter-spacing: 0.1em; font-size: 0.75rem; }
        td img { width: 60px; height: 40px; object-fit: cover; border-radius: 4px; }
        
        .form-panel { background: var(--surface); padding: 2rem; border: 1px solid var(--border); position: sticky; top: 6rem; }
        .form-group { margin-bottom: 1rem; }
        .form-group label { display: block; margin-bottom: 0.4rem; font-size: 0.75rem; color: var(--muted); text-transform: uppercase; letter-spacing: 0.1em; }
        .form-control { width: 100%; background: transparent; border: 1px solid var(--border); color: var(--text); padding: 0.8rem; font-family: 'DM Sans', sans-serif; font-size: 0.9rem; }
        .form-control:focus { outline: none; border-color: var(--gold); }
        
        .msg { padding: 1rem; margin-bottom: 1.5rem; border: 1px solid; font-size: 0.9rem; }
        .msg-success { background: rgba(46, 204, 113, 0.1); border-color: #2ecc71; color: #2ecc71; }

        /* Appointments Section */
        .appt-section-admin { margin-top: 4rem; }
        .appt-section-admin .admin-title { display: flex; align-items: center; gap: 1rem; }
        .appt-badge { background: var(--accent); color: white; font-family: 'DM Sans', sans-serif; font-size: 0.75rem; padding: 0.3rem 0.8rem; border-radius: 20px; font-weight: 500; }
        .status-badge { display: inline-block; padding: 0.25rem 0.7rem; font-size: 0.7rem; letter-spacing: 0.08em; text-transform: uppercase; border-radius: 3px; font-weight: 500; }
        .status-pending { background: rgba(241, 196, 15, 0.15); color: #f1c40f; border: 1px solid rgba(241, 196, 15, 0.3); }
        .status-contacted { background: rgba(52, 152, 219, 0.15); color: #3498db; border: 1px solid rgba(52, 152, 219, 0.3); }
        .status-completed { background: rgba(46, 204, 113, 0.15); color: #2ecc71; border: 1px solid rgba(46, 204, 113, 0.3); }
        .status-cancelled { background: rgba(231, 76, 60, 0.15); color: #e74c3c; border: 1px solid rgba(231, 76, 60, 0.3); }
        .status-select { background: var(--surface); border: 1px solid var(--border); color: var(--text); padding: 0.3rem 0.5rem; font-family: 'DM Sans', sans-serif; font-size: 0.78rem; cursor: pointer; }
        .status-select:focus { outline: none; border-color: var(--gold); }
        td .detail-text { color: var(--muted); font-size: 0.78rem; }
    </style>
</head>
<body>

    <nav id="mainNav" class="scrolled">
        <a class="nav-logo" href="index.php">PRESTIGE<span>AUTO</span> (ADMIN)</a>
        <ul class="nav-links">
            <li><a href="index.php">View Showroom</a></li>
            <li><a href="../backend/logout.php">Logout (<?= htmlspecialchars($_SESSION['username']) ?>)</a></li>
        </ul>
    </nav>

    <div class="admin-header">
        <h1 class="admin-title">Vehicle Management</h1>
    </div>

    <?= $msg ?>

    <div class="admin-grid">
        <!-- Table -->
        <div>
            <table>
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Price (USD)</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($vehicles as $v): ?>
                    <tr>
                        <td><img src="<?= htmlspecialchars($v['image_url']) ?>" alt="Car"></td>
                        <td><?= htmlspecialchars($v['name']) ?></td>
                        <td><?= htmlspecialchars($v['type']) ?></td>
                        <td><?= number_format($v['price'], 0) ?></td>
                        <td>
                            <button class="btn" style="padding: 0.4rem 0.8rem" onclick='editVehicle(<?= json_encode($v) ?>)'>Edit</button>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?= $v['id'] ?>">
                                <button type="submit" class="btn btn-danger" style="padding: 0.4rem 0.8rem" onclick="return confirm('Are you sure you want to delete this vehicle?');">Delete</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (count($vehicles) === 0): ?>
                    <tr><td colspan="5">No vehicles found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Add/Edit Form -->
        <div>
            <div class="form-panel">
                <h3 id="formTitle" style="font-family: 'Cormorant Garamond', serif; font-size: 1.8rem; color: var(--gold); margin-bottom: 1.5rem;">Add New Vehicle</h3>
                <form method="POST" id="vehicleForm">
                    <input type="hidden" name="action" id="formAction" value="add">
                    <input type="hidden" name="id" id="formId" value="">
                    
                    <div class="form-group">
                        <label>Vehicle Name</label>
                        <input type="text" name="name" id="f_name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Type / Category</label>
                        <select name="type" id="f_type" class="form-control" required>
                            <option value="">Select Category</option>
                            <option value="Luxury SUVs">Luxury SUVs</option>
                            <option value="Luxury Sedans">Luxury Sedans</option>
                            <option value="Luxury Coupes">Luxury Coupes</option>
                            <option value="Luxury Wagons">Luxury Wagons</option>
                            <option value="Sports Cars">Sports Cars</option>
                            <option value="Supercars">Supercars</option>
                            <option value="Hypercars">Hypercars</option>
                            <option value="Vintage Classics">Vintage Classics</option>
                            <option value="Electric Luxury Cars">Electric Luxury Cars</option>
                            <option value="Limited Edition">Limited Edition</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Price (USD)</label>
                        <input type="number" name="price" id="f_price" class="form-control" step="0.01" required>
                    </div>
                    <div class="form-group">
                        <label>Image URL / Path</label>
                        <input type="text" name="image_url" id="f_image" class="form-control" value="images/" required>
                    </div>
                    <div class="form-group">
                        <label>Engine</label>
                        <input type="text" name="engine" id="f_engine" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Power (e.g. 563 bhp)</label>
                        <input type="text" name="power" id="f_power" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>0-100 km/h (e.g. 3.5 seconds)</label>
                        <input type="text" name="acceleration" id="f_accel" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Drive (e.g. AWD, RWD)</label>
                        <input type="text" name="drive" id="f_drive" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="description" id="f_desc" class="form-control" rows="3"></textarea>
                    </div>
                    
                    <button type="submit" class="btn" style="width: 100%">Save Vehicle</button>
                    <button type="button" class="btn" style="width: 100%; margin-top: 0.5rem; background: transparent; border: 1px solid var(--border);" onclick="resetForm()">Clear Form</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Appointment Requests Section -->
    <div class="appt-section-admin">
        <div class="admin-header">
            <h1 class="admin-title">
                Appointment Requests
                <?php if ($pending_count > 0): ?>
                    <span class="appt-badge"><?= $pending_count ?> Pending</span>
                <?php endif; ?>
            </h1>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Client</th>
                    <th>Contact</th>
                    <th>Date & Time</th>
                    <th>Vehicle Interest</th>
                    <th>Message</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($appointments as $appt): ?>
                <tr>
                    <td>
                        <strong><?= htmlspecialchars($appt['full_name']) ?></strong><br>
                        <span class="detail-text">@<?= htmlspecialchars($appt['username'] ?? 'N/A') ?></span>
                    </td>
                    <td>
                        <?= htmlspecialchars($appt['email']) ?><br>
                        <span class="detail-text"><?= htmlspecialchars($appt['phone']) ?></span>
                    </td>
                    <td>
                        <?= date('M j, Y', strtotime($appt['preferred_date'])) ?><br>
                        <span class="detail-text"><?= date('g:i A', strtotime($appt['preferred_time'])) ?></span>
                    </td>
                    <td><?= htmlspecialchars($appt['vehicle_interest'] ?: '—') ?></td>
                    <td><?= htmlspecialchars(mb_strimwidth($appt['message'] ?: '—', 0, 60, '...')) ?></td>
                    <td>
                        <span class="status-badge status-<?= $appt['status'] ?>">
                            <?= ucfirst($appt['status']) ?>
                        </span>
                    </td>
                    <td>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="action" value="update_appointment">
                            <input type="hidden" name="id" value="<?= $appt['id'] ?>">
                            <select name="status" class="status-select" onchange="this.form.submit()">
                                <option value="pending" <?= $appt['status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                                <option value="contacted" <?= $appt['status'] === 'contacted' ? 'selected' : '' ?>>Contacted</option>
                                <option value="completed" <?= $appt['status'] === 'completed' ? 'selected' : '' ?>>Completed</option>
                                <option value="cancelled" <?= $appt['status'] === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                            </select>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (count($appointments) === 0): ?>
                <tr><td colspan="7" style="color: var(--muted); text-align: center; padding: 2rem;">No appointment requests yet.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <footer class="site-footer" style="margin-top: 4rem;">
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
        function editVehicle(v) {
            document.getElementById('formTitle').innerText = 'Edit Vehicle';
            document.getElementById('formAction').value = 'edit';
            document.getElementById('formId').value = v.id;
            document.getElementById('f_name').value = v.name;
            document.getElementById('f_type').value = v.type;
            document.getElementById('f_price').value = v.price;
            document.getElementById('f_image').value = v.image_url;
            document.getElementById('f_engine').value = v.engine;
            document.getElementById('f_power').value = v.power;
            document.getElementById('f_accel').value = v.acceleration;
            document.getElementById('f_drive').value = v.drive;
            document.getElementById('f_desc').value = v.description;
            window.scrollTo(0, 0);
        }

        function resetForm() {
            document.getElementById('formTitle').innerText = 'Add New Vehicle';
            document.getElementById('formAction').value = 'add';
            document.getElementById('formId').value = '';
            document.getElementById('vehicleForm').reset();
            document.getElementById('f_image').value = 'images/';
        }
    </script>
</body>
</html>
