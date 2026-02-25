<?php
// Paystack Configuration
// Replace these with your actual Paystack API keys from https://dashboard.paystack.com/#/settings/developers

define('PAYSTACK_SECRET_KEY', 'sk_test_49e1b731226313f1d294d17bd39e2c0b2c8b6c0b');
define('PAYSTACK_PUBLIC_KEY', 'pk_test_c0e8c9192a7e237b2ea5b83916381e916b13f9d0');

// Callback URL — Paystack redirects here after payment
define('PAYSTACK_CALLBACK_URL', 'https://42ff-105-231-189-220.ngrok-free.app/car_yard/paystack_callback.php');

// Currency
define('PAYSTACK_CURRENCY', 'KES');
?>
