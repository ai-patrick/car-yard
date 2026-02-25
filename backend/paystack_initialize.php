<?php
session_start();
require 'db.php';
require 'paystack_config.php';

header('Content-Type: application/json');

// Must be logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Please log in to make a payment.']);
    exit;
}

// Only accept POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed.']);
    exit;
}

// Read JSON body
$input = json_decode(file_get_contents('php://input'), true);
$vehicle_ids = $input['vehicle_ids'] ?? [];

if (empty($vehicle_ids) || !is_array($vehicle_ids)) {
    http_response_code(400);
    echo json_encode(['error' => 'No vehicles selected.']);
    exit;
}

// Sanitize vehicle IDs to integers
$vehicle_ids = array_map('intval', $vehicle_ids);
$placeholders = implode(',', array_fill(0, count($vehicle_ids), '?'));

// Recalculate total from database (never trust client-side amounts)
try {
    $stmt = $pdo->prepare("SELECT id, name, price FROM vehicles WHERE id IN ($placeholders)");
    $stmt->execute($vehicle_ids);
    $vehicles = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($vehicles) === 0) {
        http_response_code(400);
        echo json_encode(['error' => 'No valid vehicles found.']);
        exit;
    }

    $total = 0;
    foreach ($vehicles as $v) {
        $total += floatval($v['price']);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error.']);
    exit;
}

// Get user email
try {
    $stmt = $pdo->prepare("SELECT email FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    $email = $user['email'];
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Could not retrieve user info.']);
    exit;
}

// Amount in smallest currency unit
// KES uses shillings (not subunits like NGN kobo), so multiply by 100 for Paystack
$amount_in_cents = intval($total * 100);
$reference = 'PRESTIGE_' . uniqid() . '_' . time();

// Initialize Paystack transaction via cURL
$url = "https://api.paystack.co/transaction/initialize";
$fields = [
    'email' => $email,
    'amount' => $amount_in_cents,
    'currency' => PAYSTACK_CURRENCY,
    'reference' => $reference,
    'callback_url' => PAYSTACK_CALLBACK_URL,
    'metadata' => [
        'user_id' => $_SESSION['user_id'],
        'vehicle_ids' => $vehicle_ids,
        'custom_fields' => [
            [
                'display_name' => 'Customer',
                'variable_name' => 'customer',
                'value' => $_SESSION['username']
            ]
        ]
    ]
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer " . PAYSTACK_SECRET_KEY,
    "Content-Type: application/json",
    "Cache-Control: no-cache"
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlError = curl_error($ch);
curl_close($ch);

if ($curlError) {
    http_response_code(500);
    echo json_encode(['error' => 'Payment gateway connection failed.']);
    exit;
}

$result = json_decode($response, true);

if (!$result['status']) {
    http_response_code(400);
    echo json_encode(['error' => $result['message'] ?? 'Transaction initialization failed.']);
    exit;
}

// Save pending payment to database
try {
    $stmt = $pdo->prepare("INSERT INTO payments (user_id, reference, amount, currency, status, vehicle_ids) VALUES (?, ?, ?, ?, 'pending', ?)");
    $stmt->execute([
        $_SESSION['user_id'],
        $reference,
        $total,
        PAYSTACK_CURRENCY,
        json_encode($vehicle_ids)
    ]);
} catch (PDOException $e) {
    // Non-critical: payment can still proceed, verification will handle it
}

// Return data for Paystack Popup
echo json_encode([
    'status' => true,
    'authorization_url' => $result['data']['authorization_url'],
    'access_code' => $result['data']['access_code'],
    'reference' => $reference
]);
?>
