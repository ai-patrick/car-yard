<?php
session_start();
require 'db.php';
require 'paystack_config.php';

// Get reference from query string (Paystack redirects here with ?reference=xxx)
// Also handle ?trxref= which Paystack sometimes uses
$reference = $_GET['reference'] ?? $_GET['trxref'] ?? null;

if (!$reference) {
    header("Location: garage.php?payment_error=" . urlencode("No payment reference found."));
    exit;
}

// Verify transaction with Paystack API
$url = "https://api.paystack.co/transaction/verify/" . rawurlencode($reference);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer " . PAYSTACK_SECRET_KEY,
    "Content-Type: application/json",
    "Cache-Control: no-cache"
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

$result = json_decode($response, true);

if (!$result || !$result['status']) {
    header("Location: garage.php?payment_error=" . urlencode("Payment verification failed. Please contact support."));
    exit;
}

$paystack_data = $result['data'];
$status = $paystack_data['status']; // 'success', 'failed', 'abandoned'
$amount_paid = $paystack_data['amount'] / 100; // Convert from cents back to KES

if ($status === 'success') {
    // Update payment record in database
    try {
        $stmt = $pdo->prepare("UPDATE payments SET status = 'success', paystack_response = ?, amount = ? WHERE reference = ?");
        $stmt->execute([
            json_encode($paystack_data),
            $amount_paid,
            $reference
        ]);

        // Get vehicle IDs from payment record
        $stmt = $pdo->prepare("SELECT vehicle_ids, user_id FROM payments WHERE reference = ?");
        $stmt->execute([$reference]);
        $payment = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($payment) {
            $vehicle_ids = json_decode($payment['vehicle_ids'], true);
            $user_id = $payment['user_id'];

            // Update any existing reservations to 'completed'
            if (!empty($vehicle_ids)) {
                $placeholders = implode(',', array_fill(0, count($vehicle_ids), '?'));
                $params = array_merge([$user_id], $vehicle_ids);
                $stmt = $pdo->prepare("UPDATE reservations SET status = 'completed' WHERE user_id = ? AND vehicle_id IN ($placeholders)");
                $stmt->execute($params);
            }
        }
    } catch (PDOException $e) {
        // Log error but still redirect to success since payment was confirmed
        error_log("Paystack callback DB error: " . $e->getMessage());
    }

    // Redirect to success page
    header("Location: payment_success.php?reference=" . urlencode($reference));
    exit;
} else {
    // Payment failed or was abandoned
    try {
        $stmt = $pdo->prepare("UPDATE payments SET status = 'failed', paystack_response = ? WHERE reference = ?");
        $stmt->execute([json_encode($paystack_data), $reference]);
    } catch (PDOException $e) {
        error_log("Paystack callback DB error: " . $e->getMessage());
    }

    header("Location: garage.php?payment_error=" . urlencode("Payment was not successful. Status: " . $status));
    exit;
}
?>
