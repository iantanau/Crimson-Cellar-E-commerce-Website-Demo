<?php

$page = $_GET['page'] ?? '';
$page = trim($page, "/ \t\n\r\0\x0B");

if ($page === '') {
    $page = 'index.php';
}

$allowed = [
    'about.php',
    'add_to_cart.php',
    'cart.php',
    'contact.php',
    'index.php',
    'login.php',
    'login_action.php',
    'logout.php',
    'member.php',
    'order_confirmation.php',
    'payment.php',
    'payment_success.php',
    'process_payment.php',
    'product_detail.php',
    'profile_edit.php',
    'register.php',
    'register_action.php',
    'search.php',
    'shop.php',
];

$script = basename($page);

if (!in_array($script, $allowed, true)) {
    http_response_code(404);
    exit('Not found');
}

require __DIR__ . '/' . $script;
